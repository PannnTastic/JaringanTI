/**
 * Real-time Location Tracker
 * Automatically tracks user location and sends updates to the server
 */
class RealTimeLocationTracker {
    constructor(options = {}) {
        this.options = {
            interval: options.interval || 5000, // Default 5 seconds
            apiUrl: options.apiUrl || '/api/location/update',
            enableHighAccuracy: options.enableHighAccuracy || true,
            maximumAge: options.maximumAge || 10000,
            timeout: options.timeout || 15000,
            autoStart: options.autoStart || false,
            onLocationUpdate: options.onLocationUpdate || null,
            onError: options.onError || null,
            debug: options.debug || false,
            ...options
        };

        this.isTracking = false;
        this.watchId = null;
        this.intervalId = null;
        this.lastPosition = null;
        this.authToken = this.getAuthToken();
        
        this.init();
    }

    init() {
        if (!this.isGeolocationSupported()) {
            this.log('Geolocation is not supported by this browser');
            return;
        }

        if (this.options.autoStart) {
            this.startTracking();
        }

        this.log('RealTime Location Tracker initialized');
    }

    isGeolocationSupported() {
        return 'geolocation' in navigator;
    }

    getAuthToken() {
        // Try to get auth token from meta tag or localStorage
        const metaToken = document.querySelector('meta[name="csrf-token"]');
        if (metaToken) {
            return metaToken.getAttribute('content');
        }
        
        return localStorage.getItem('auth_token') || null;
    }

    startTracking() {
        if (this.isTracking) {
            this.log('Location tracking is already active');
            return;
        }

        this.log('Starting location tracking...');
        this.isTracking = true;

        // Start watching position
        this.watchId = navigator.geolocation.watchPosition(
            (position) => this.onPositionSuccess(position),
            (error) => this.onPositionError(error),
            {
                enableHighAccuracy: this.options.enableHighAccuracy,
                maximumAge: this.options.maximumAge,
                timeout: this.options.timeout
            }
        );

        // Start interval updates
        this.intervalId = setInterval(() => {
            if (this.lastPosition) {
                this.sendLocationUpdate(this.lastPosition);
            }
        }, this.options.interval);

        this.log('Location tracking started');
    }

    stopTracking() {
        if (!this.isTracking) {
            this.log('Location tracking is not active');
            return;
        }

        this.log('Stopping location tracking...');
        this.isTracking = false;

        if (this.watchId !== null) {
            navigator.geolocation.clearWatch(this.watchId);
            this.watchId = null;
        }

        if (this.intervalId !== null) {
            clearInterval(this.intervalId);
            this.intervalId = null;
        }

        this.log('Location tracking stopped');
    }

    onPositionSuccess(position) {
        this.lastPosition = {
            latitude: position.coords.latitude,
            longitude: position.coords.longitude,
            accuracy: position.coords.accuracy,
            heading: position.coords.heading,
            speed: position.coords.speed,
            timestamp: position.timestamp
        };

        this.log('Position updated:', this.lastPosition);

        // Call callback if provided
        if (this.options.onLocationUpdate) {
            this.options.onLocationUpdate(this.lastPosition);
        }

        // Send to server immediately for first position
        if (!this.intervalId) {
            this.sendLocationUpdate(this.lastPosition);
        }
    }

    onPositionError(error) {
        let errorMessage = 'Location error: ';
        
        switch (error.code) {
            case error.PERMISSION_DENIED:
                errorMessage += 'User denied the request for Geolocation.';
                break;
            case error.POSITION_UNAVAILABLE:
                errorMessage += 'Location information is unavailable.';
                break;
            case error.TIMEOUT:
                errorMessage += 'The request to get user location timed out.';
                break;
            default:
                errorMessage += 'An unknown error occurred.';
                break;
        }

        this.log(errorMessage);

        if (this.options.onError) {
            this.options.onError(error, errorMessage);
        }
    }

    async sendLocationUpdate(position) {
        if (!position) {
            this.log('No position data to send');
            return;
        }

        try {
            const response = await fetch(this.options.apiUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.authToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    latitude: position.latitude,
                    longitude: position.longitude,
                    accuracy: position.accuracy,
                    heading: position.heading,
                    speed: position.speed
                })
            });

            if (response.ok) {
                const data = await response.json();
                this.log('Location update sent successfully:', data);
                
                // Dispatch custom event for other components to listen
                window.dispatchEvent(new CustomEvent('locationUpdated', {
                    detail: { position, response: data }
                }));
            } else {
                this.log('Failed to send location update:', response.status, response.statusText);
            }
        } catch (error) {
            this.log('Error sending location update:', error);
        }
    }

    getCurrentPosition() {
        return new Promise((resolve, reject) => {
            if (!this.isGeolocationSupported()) {
                reject(new Error('Geolocation not supported'));
                return;
            }

            navigator.geolocation.getCurrentPosition(
                (position) => resolve(position),
                (error) => reject(error),
                {
                    enableHighAccuracy: this.options.enableHighAccuracy,
                    maximumAge: this.options.maximumAge,
                    timeout: this.options.timeout
                }
            );
        });
    }

    async requestLocationOnce() {
        try {
            const position = await this.getCurrentPosition();
            this.onPositionSuccess(position);
            await this.sendLocationUpdate(this.lastPosition);
            return this.lastPosition;
        } catch (error) {
            this.onPositionError(error);
            throw error;
        }
    }

    log(message, ...args) {
        if (this.options.debug) {
            console.log('[LocationTracker]', message, ...args);
        }
    }

    // Static method to create and auto-start tracker
    static start(options = {}) {
        return new RealTimeLocationTracker({
            ...options,
            autoStart: true
        });
    }

    // Static method to get current location once
    static async getCurrentLocation(options = {}) {
        const tracker = new RealTimeLocationTracker(options);
        return await tracker.requestLocationOnce();
    }
}

// Export for module systems
if (typeof module !== 'undefined' && module.exports) {
    module.exports = RealTimeLocationTracker;
}

// Add to global scope for browser usage
if (typeof window !== 'undefined') {
    window.RealTimeLocationTracker = RealTimeLocationTracker;
}

// Auto-initialize if data attribute is present
document.addEventListener('DOMContentLoaded', function() {
    const autoElements = document.querySelectorAll('[data-realtime-location]');
    
    autoElements.forEach(element => {
        const options = {
            interval: parseInt(element.dataset.interval) || 5000,
            debug: element.dataset.debug === 'true',
            autoStart: element.dataset.autoStart !== 'false'
        };

        const tracker = new RealTimeLocationTracker(options);
        
        // Store tracker instance on element for later access
        element.locationTracker = tracker;
    });
});
