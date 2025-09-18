/**
 * WebSocket Location Service
 * Handles real-time location updates using Laravel Echo and Reverb WebSocket server
 */
class WebSocketLocationService {
    constructor(options = {}) {
        this.options = {
            debug: options.debug || false,
            reconnectTimeout: options.reconnectTimeout || 3000,
            maxReconnectAttempts: options.maxReconnectAttempts || 5,
            onLocationUpdate: options.onLocationUpdate || null,
            onUserConnect: options.onUserConnect || null,
            onUserDisconnect: options.onUserDisconnect || null,
            onConnectionStatus: options.onConnectionStatus || null,
            ...options
        };

        this.echo = null;
        this.channel = null;
        this.isConnected = false;
        this.reconnectAttempts = 0;
        this.reconnectTimer = null;
        this.heartbeatTimer = null;
        
        this.init();
    }

    async init() {
        try {
            await this.loadEcho();
            this.setupEcho();
            this.log('WebSocket Location Service initialized');
        } catch (error) {
            this.log('Failed to initialize WebSocket service:', error);
            this.handleConnectionError(error);
        }
    }

    async loadEcho() {
        // Check if Echo is already loaded
        if (window.Echo) {
            this.log('Echo already loaded');
            return;
        }

        // Try to use existing Echo from Vite bundle first
        if (window.Echo) {
            this.log('Using existing Echo instance');
            return;
        }
        
        this.log('Echo not available, will try fallback');
        throw new Error('Echo not available - using fallback mode');
    }

    setupEcho() {
        // Get Reverb config from window or environment
        const reverbConfig = {
            key: window.reverbConfig?.key || 'sw0iw5z32hu7wfpdyytz',
            host: window.reverbConfig?.host || 'localhost',
            port: window.reverbConfig?.port || 8080,
            scheme: window.reverbConfig?.scheme || 'http'
        };
        
        this.echo = new window.Echo({
            broadcaster: 'reverb',
            key: reverbConfig.key,
            wsHost: reverbConfig.host,
            wsPort: reverbConfig.port,
            wssPort: reverbConfig.port,
            forceTLS: reverbConfig.scheme === 'https',
            enabledTransports: ['ws', 'wss']
        });

        this.setupChannels();
        this.setupConnectionEvents();
    }

    setupChannels() {
        // Listen to public location tracking channel
        this.channel = this.echo.channel('location-tracking')
            .listen('location.updated', (e) => {
                this.log('Location update received:', e);
                this.handleLocationUpdate(e);
            });

        this.log('Channel subscribed: location-tracking');
    }

    setupConnectionEvents() {
        // Connection status events
        this.echo.connector.pusher.connection.bind('connected', () => {
            this.isConnected = true;
            this.reconnectAttempts = 0;
            this.clearReconnectTimer();
            this.log('WebSocket connected');
            this.notifyConnectionStatus('connected');
            this.startHeartbeat();
        });

        this.echo.connector.pusher.connection.bind('disconnected', () => {
            this.isConnected = false;
            this.log('WebSocket disconnected');
            this.notifyConnectionStatus('disconnected');
            this.stopHeartbeat();
            this.scheduleReconnect();
        });

        this.echo.connector.pusher.connection.bind('error', (error) => {
            this.log('WebSocket error:', error);
            this.notifyConnectionStatus('error', error);
            this.handleConnectionError(error);
        });

        this.echo.connector.pusher.connection.bind('state_change', (states) => {
            this.log('Connection state changed:', states.previous, '->', states.current);
            this.notifyConnectionStatus('state_change', states);
        });
    }

    handleLocationUpdate(event) {
        if (!event.user) {
            this.log('Invalid location update event:', event);
            return;
        }

        const locationData = {
            user_id: event.user.user_id,
            name: event.user.name,
            email: event.user.email,
            role: event.user.role,
            latitude: event.user.current_latitude,
            longitude: event.user.current_longitude,
            address: event.user.current_address,
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

        this.log('Location update processed for user:', locationData.name);
    }

    startHeartbeat() {
        this.heartbeatTimer = setInterval(() => {
            if (this.isConnected && this.echo) {
                try {
                    // Send a ping to keep connection alive
                    this.echo.connector.pusher.send_event('ping', {}, 'heartbeat');
                } catch (error) {
                    this.log('Heartbeat failed:', error);
                }
            }
        }, 30000); // Every 30 seconds
    }

    stopHeartbeat() {
        if (this.heartbeatTimer) {
            clearInterval(this.heartbeatTimer);
            this.heartbeatTimer = null;
        }
    }

    scheduleReconnect() {
        if (this.reconnectAttempts >= this.options.maxReconnectAttempts) {
            this.log('Max reconnect attempts reached');
            this.notifyConnectionStatus('max_reconnect_attempts_reached');
            return;
        }

        this.clearReconnectTimer();
        
        const timeout = this.options.reconnectTimeout * Math.pow(2, this.reconnectAttempts);
        this.log(`Scheduling reconnect in ${timeout}ms (attempt ${this.reconnectAttempts + 1})`);
        
        this.reconnectTimer = setTimeout(() => {
            this.reconnectAttempts++;
            this.reconnect();
        }, timeout);
    }

    clearReconnectTimer() {
        if (this.reconnectTimer) {
            clearTimeout(this.reconnectTimer);
            this.reconnectTimer = null;
        }
    }

    reconnect() {
        this.log('Attempting to reconnect...');
        try {
            if (this.echo) {
                this.echo.disconnect();
            }
            this.setupEcho();
        } catch (error) {
            this.log('Reconnect failed:', error);
            this.scheduleReconnect();
        }
    }

    handleConnectionError(error) {
        this.log('Connection error:', error);
        
        // Try fallback methods if WebSocket fails
        if (!this.isConnected && this.reconnectAttempts === 0) {
            this.log('WebSocket unavailable, consider using polling fallback');
            this.notifyConnectionStatus('fallback_required', error);
        }
    }

    notifyConnectionStatus(status, data = null) {
        if (this.options.onConnectionStatus) {
            this.options.onConnectionStatus(status, data);
        }

        // Dispatch global event
        window.dispatchEvent(new CustomEvent('websocket-connection-status', {
            detail: { status, data, timestamp: new Date() }
        }));
    }

    getCsrfToken() {
        const tokenMeta = document.querySelector('meta[name="csrf-token"]');
        return tokenMeta ? tokenMeta.getAttribute('content') : null;
    }

    // Utility methods
    getConnectionStatus() {
        return {
            isConnected: this.isConnected,
            reconnectAttempts: this.reconnectAttempts,
            maxReconnectAttempts: this.options.maxReconnectAttempts,
            hasEcho: !!this.echo,
            hasChannel: !!this.channel
        };
    }

    // Method to manually send location update (if needed)
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
                this.log('Location update sent successfully:', data);
                return data;
            } else {
                throw new Error(data.message || 'Failed to send location update');
            }
        } catch (error) {
            this.log('Error sending location update:', error);
            throw error;
        }
    }

    // Cleanup method
    destroy() {
        this.stopHeartbeat();
        this.clearReconnectTimer();
        
        if (this.channel) {
            this.echo.leaveChannel('location-tracking');
        }
        
        if (this.echo) {
            this.echo.disconnect();
        }
        
        this.log('WebSocket Location Service destroyed');
    }

    log(message, ...args) {
        if (this.options.debug) {
            console.log('[WebSocket Location]', message, ...args);
        }
    }

    // Static factory methods
    static create(options = {}) {
        return new WebSocketLocationService(options);
    }

    static createWithDebug(options = {}) {
        return new WebSocketLocationService({
            ...options,
            debug: true
        });
    }
}

// Export for different module systems
if (typeof module !== 'undefined' && module.exports) {
    module.exports = WebSocketLocationService;
}

// Add to global scope for browser usage
if (typeof window !== 'undefined') {
    window.WebSocketLocationService = WebSocketLocationService;
}

// Auto-initialize if data attribute is present
document.addEventListener('DOMContentLoaded', function() {
    const autoElements = document.querySelectorAll('[data-websocket-location]');
    
    autoElements.forEach(element => {
        const options = {
            debug: element.dataset.debug === 'true',
            reconnectTimeout: parseInt(element.dataset.reconnectTimeout) || 3000,
            maxReconnectAttempts: parseInt(element.dataset.maxReconnectAttempts) || 5
        };

        const service = new WebSocketLocationService(options);
        
        // Store service instance on element for later access
        element.webSocketLocationService = service;
    });
});
