/**
 * Simple WebSocket Location Service
 * Uses existing Laravel Echo instance to listen for location updates
 */
class SimpleWebSocketLocationService {
    constructor(options = {}) {
        this.options = {
            debug: options.debug || false,
            onLocationUpdate: options.onLocationUpdate || null,
            onConnectionStatus: options.onConnectionStatus || null,
            ...options
        };

        this.channel = null;
        this.isListening = false;
        
        this.init();
    }

    init() {
        this.log('🔄 Initializing Simple WebSocket Location Service...');
        
        // Wait for Echo to be available
        this.waitForEcho().then(() => {
            this.setupChannel();
        }).catch((error) => {
            this.log('❌ Echo not available:', error);
            this.notifyConnectionStatus('fallback_required', error);
        });
    }

    async waitForEcho(maxAttempts = 10, interval = 500) {
        for (let i = 0; i < maxAttempts; i++) {
            if (window.Echo) {
                this.log('✅ Echo found');
                return window.Echo;
            }
            
            this.log(`⏳ Waiting for Echo... (${i + 1}/${maxAttempts})`);
            await new Promise(resolve => setTimeout(resolve, interval));
        }
        
        throw new Error('Echo not available after waiting');
    }

    setupChannel() {
        try {
            this.log('📡 Setting up location-tracking channel...');
            
            // Listen to the public location-tracking channel
            this.channel = window.Echo.channel('location-tracking')
                .listen('location.updated', (event) => {
                    this.log('📍 Location update received:', event);
                    this.handleLocationUpdate(event);
                })
                .subscribed(() => {
                    this.log('✅ Subscribed to location-tracking channel');
                    this.isListening = true;
                    this.notifyConnectionStatus('connected');
                })
                .error((error) => {
                    this.log('❌ Channel subscription error:', error);
                    this.notifyConnectionStatus('error', error);
                });
                
            this.log('🎯 Channel setup complete');
            
        } catch (error) {
            this.log('❌ Failed to setup channel:', error);
            this.notifyConnectionStatus('error', error);
        }
    }

    handleLocationUpdate(event) {
        if (!event.user) {
            this.log('⚠️ Invalid location update event:', event);
            return;
        }

        const locationData = {
            user_id: event.user.user_id,
            name: event.user.name,
            email: event.user.email,
            role: event.user.role,
            latitude: event.user.latitude,
            longitude: event.user.longitude,
            address: event.user.address,
            last_location_update: event.user.last_location_update,
            last_seen: event.user.last_seen,
            is_online: event.user.is_online,
            coordinates: event.user.coordinates,
            avatar_url: event.user.avatar_url,
            timestamp: event.timestamp
        };

        // Call callback if provided
        if (this.options.onLocationUpdate) {
            this.options.onLocationUpdate(locationData);
        }

        // Dispatch custom event for other components
        window.dispatchEvent(new CustomEvent('websocket-location-update', {
            detail: locationData
        }));

        this.log('📍 Location update processed for user:', locationData.name);
    }

    notifyConnectionStatus(status, data = null) {
        this.log(`🔗 Connection status: ${status}`);
        
        if (this.options.onConnectionStatus) {
            this.options.onConnectionStatus(status, data);
        }

        // Dispatch global event
        window.dispatchEvent(new CustomEvent('websocket-connection-status', {
            detail: { status, data, timestamp: new Date() }
        }));
    }

    // Method to manually send location update
    async sendLocationUpdate(latitude, longitude, address = null) {
        try {
            const response = await fetch('/location/update', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.getCsrfToken(),
                    'Accept': 'application/json'
                },
                credentials: 'include',
                body: JSON.stringify({
                    latitude: latitude,
                    longitude: longitude,
                    address: address
                })
            });

            const data = await response.json();
            
            if (data.success) {
                this.log('✅ Location update sent successfully:', data);
                return data;
            } else {
                throw new Error(data.message || 'Failed to send location update');
            }
        } catch (error) {
            this.log('❌ Error sending location update:', error);
            throw error;
        }
    }

    getCsrfToken() {
        const tokenMeta = document.querySelector('meta[name="csrf-token"]');
        return tokenMeta ? tokenMeta.getAttribute('content') : null;
    }

    getConnectionStatus() {
        return {
            isListening: this.isListening,
            hasEcho: !!window.Echo,
            hasChannel: !!this.channel
        };
    }

    // Cleanup method
    destroy() {
        if (this.channel && window.Echo) {
            window.Echo.leaveChannel('location-tracking');
        }
        this.isListening = false;
        this.log('🧹 Simple WebSocket Location Service destroyed');
    }

    log(message, ...args) {
        if (this.options.debug) {
            console.log('[Simple WebSocket Location]', message, ...args);
        }
    }

    // Static factory methods
    static create(options = {}) {
        return new SimpleWebSocketLocationService(options);
    }

    static createWithDebug(options = {}) {
        return new SimpleWebSocketLocationService({
            ...options,
            debug: true
        });
    }
}

// Export for different module systems
if (typeof module !== 'undefined' && module.exports) {
    module.exports = SimpleWebSocketLocationService;
}

// Add to global scope for browser usage
if (typeof window !== 'undefined') {
    window.SimpleWebSocketLocationService = SimpleWebSocketLocationService;
}

// Auto-initialize if data attribute is present
document.addEventListener('DOMContentLoaded', function() {
    const autoElements = document.querySelectorAll('[data-simple-websocket-location]');
    
    autoElements.forEach(element => {
        const options = {
            debug: element.dataset.debug === 'true'
        };

        const service = new SimpleWebSocketLocationService(options);
        
        // Store service instance on element for later access
        element.simpleWebSocketLocationService = service;
    });
});
