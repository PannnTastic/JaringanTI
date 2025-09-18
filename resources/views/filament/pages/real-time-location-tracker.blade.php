<x-filament-panels::page>
    @push('styles')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @endpush
    
    @vite(['resources/js/app.js'])
    
    <style>
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        @keyframes pulse {
            0% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.2); opacity: 0.7; }
            100% { transform: scale(1); opacity: 1; }
        }
        .tracking-controls {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }
    </style>

    <!-- Simple Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center">
                        <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 2L3 7v11c0 1.1.9 2 2 2h10c1.1 0 2-.9 2-2V7l-7-5z"/>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Active Users</p>
                    <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ count($this->getActiveUsers()) }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-green-100 dark:bg-green-900 rounded-full flex items-center justify-center">
                        <svg class="w-4 h-4 text-green-600 dark:text-green-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Locations</p>
                    <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ count($this->getAllLocations()) }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-yellow-100 dark:bg-yellow-900 rounded-full flex items-center justify-center">
                        <svg class="w-4 h-4 text-yellow-600 dark:text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Last Update</p>
                    <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ now()->format('H:i:s') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Simple Map Section -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Real-time User Monitoring - Batam</h2>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Monitor users in real-time with draggable and zoomable map</p>
        </div>
        
        <div class="p-6">
            <!-- Simple Map Container -->
            <div id="simple-map" wire:ignore 
                 style="height: 600px; width: 100%; border: 2px solid #e5e7eb; border-radius: 8px; background: #f9fafb;">
                <!-- Loading State -->
                <div id="map-loading" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); text-align: center; z-index: 1000;">
                    <div style="font-size: 24px; margin-bottom: 10px;">🗺️</div>
                    <div style="font-size: 16px; color: #666;">Loading Batam Map...</div>
                    <div style="margin-top: 10px;">
                        <div style="width: 30px; height: 30px; border: 3px solid #f3f3f3; border-top: 3px solid #3498db; border-radius: 50%; animation: spin 1s linear infinite; margin: 0 auto;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    @endpush

    @push('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="{{ asset('js/simple-websocket-location.js') }}"></script>
    
    <script>
    // Reverb WebSocket configuration for IIS
    window.reverbConfig = {
        key: '{{ env('REVERB_APP_KEY') }}',
        host: '{{ env('REVERB_HOST', 'localhost') }}',
        port: {{ env('REVERB_PORT', 8080) }},
        scheme: '{{ env('REVERB_SCHEME', 'http') }}',
        // IIS specific settings
        currentHost: '{{ request()->getHost() }}'
    };
    </script>

    <script>
    // Enhanced Real-time User Monitoring for Batam
    window.batamMonitor = {
        map: null,
        userMarkers: null,
        locationMarkers: null,
        userTrails: null,
        userHistory: {},
        lastUpdateTime: null,
        initialized: false,
        isTracking: true,
        webSocketService: null,
        
        init: function() {
            console.log('🗺️ Initializing Batam Real-time Monitor...');
            
            const container = document.getElementById('simple-map');
            if (!container) {
                console.error('Map container not found');
                return;
            }

            try {
                // Create map centered on Batam
                this.map = L.map(container, {
                    center: [1.1304, 104.0530], // Batam coordinates
                    zoom: 11,
                    dragging: true,        // Enable dragging
                    touchZoom: true,       // Enable touch zoom
                    doubleClickZoom: true, // Enable double click zoom
                    scrollWheelZoom: true, // Enable scroll wheel zoom
                    boxZoom: true,         // Enable box zoom
                    keyboard: true,        // Enable keyboard navigation
                    zoomControl: true      // Show zoom controls
                });

                // Add OpenStreetMap tiles
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '© OpenStreetMap contributors',
                    maxZoom: 19
                }).addTo(this.map);

                // Create marker groups
                this.userMarkers = L.layerGroup().addTo(this.map);
                this.locationMarkers = L.layerGroup().addTo(this.map);
                this.userTrails = L.layerGroup().addTo(this.map);

                // Add map controls
                this.addTrackingControls();

                // Hide loading
                const loading = document.getElementById('map-loading');
                if (loading) loading.style.display = 'none';

                this.initialized = true;
                this.lastUpdateTime = new Date();
                console.log('✅ Enhanced Batam tracking initialized successfully');

                // Add keyboard event listener for dialog management
                this.addDialogKeyListener();

                // Load initial data
                this.loadData();
                
                // Initialize WebSocket service
                this.initWebSocket();

            } catch (error) {
                console.error('❌ Failed to initialize map:', error);
                this.showError('Failed to load map: ' + error.message);
            }
        },
        
        initWebSocket: function() {
            console.log('🔄 Initializing WebSocket service...');
            
            // Check if Simple WebSocket service is available
            if (typeof SimpleWebSocketLocationService === 'undefined') {
                console.warn('Simple WebSocket service not available, falling back to polling');
                return;
            }
            
            // Initialize Simple WebSocket service with callbacks
            this.webSocketService = new SimpleWebSocketLocationService({
                debug: true,
                onLocationUpdate: (locationData) => {
                    console.log('📍 WebSocket location update:', locationData);
                    this.handleWebSocketLocationUpdate(locationData);
                },
                onConnectionStatus: (status, data) => {
                    console.log('🔗 WebSocket status:', status, data);
                    this.handleWebSocketConnectionStatus(status, data);
                }
            });
            
            console.log('✅ WebSocket service initialized');
        },
        
        handleWebSocketLocationUpdate: function(locationData) {
            if (!this.initialized || !this.map || !this.isTracking) {
                return;
            }
            
            // Convert WebSocket data to our format
            const userData = {
                id: locationData.user_id,
                name: locationData.name,
                email: locationData.email,
                latitude: locationData.latitude,
                longitude: locationData.longitude,
                address: locationData.address,
                last_location_update: locationData.last_location_update,
                last_seen: locationData.last_seen,
                updated_at: locationData.last_seen
            };
            
            // Update single user marker
            this.updateSingleUserMarker(userData);
            
            // Update status display
            this.updateStatusDisplayFromWebSocket();
        },
        
        handleWebSocketConnectionStatus: function(status, data) {
            const statusEl = document.getElementById('location-status');
            if (statusEl) {
                switch(status) {
                    case 'connected':
                        statusEl.textContent = 'WebSocket connected';
                        statusEl.style.color = '#2ecc71';
                        break;
                    case 'disconnected':
                        statusEl.textContent = 'WebSocket disconnected';
                        statusEl.style.color = '#e74c3c';
                        break;
                    case 'error':
                        statusEl.textContent = 'WebSocket error';
                        statusEl.style.color = '#e74c3c';
                        break;
                    case 'fallback_required':
                        statusEl.textContent = 'Using polling fallback';
                        statusEl.style.color = '#f39c12';
                        break;
                }
            }
        },
        
        updateSingleUserMarker: function(user) {
            if (!user.latitude || !user.longitude) return;
            
            // Update user history for trail tracking
            this.updateUserHistory(user);
            
            const status = this.getStatusIcon(user);
            const isActive = status.icon === '🟢';
            
            // Find and remove existing marker for this user
            this.userMarkers.eachLayer(layer => {
                if (layer.userData && layer.userData.id === user.id) {
                    this.userMarkers.removeLayer(layer);
                }
            });
            
            // Add updated marker
            const marker = L.marker([parseFloat(user.latitude), parseFloat(user.longitude)], {
                icon: L.divIcon({
                    className: 'custom-marker',
                    html: `
                        <div style="position: relative;">
                            <div style="background: ${status.color}; color: white; border-radius: 50%; width: 24px; height: 24px; display: flex; align-items: center; justify-content: center; font-size: 12px; border: 2px solid white; box-shadow: 0 2px 4px rgba(0,0,0,0.3);">
                                👤
                            </div>
                            <div style="position: absolute; top: -2px; right: -2px; background: ${status.color}; border-radius: 50%; width: 8px; height: 8px; border: 1px solid white;"></div>
                            ${isActive ? '<div style="position: absolute; top: -4px; right: -4px; background: #2ecc71; border-radius: 50%; width: 12px; height: 12px; border: 2px solid white; animation: pulse 2s infinite;"></div>' : ''}
                        </div>
                    `,
                    iconSize: [24, 24],
                    iconAnchor: [12, 12]
                })
            }).bindPopup(`
                <div style="text-align: center; min-width: 180px;">
                    <div style="display: flex; align-items: center; justify-content: center; margin-bottom: 8px;">
                        <strong>👤 ${user.name}</strong>
                        <span style="margin-left: 8px;">${status.icon}</span>
                    </div>
                    <div style="font-size: 12px; color: ${status.color}; margin-bottom: 4px;">
                        ${status.status} (WebSocket)
                    </div>
                    <div style="font-size: 11px; margin-bottom: 4px;">
                        📧 ${user.email}
                    </div>
                    <div style="font-size: 11px; margin-bottom: 4px;">
                        📍 ${user.address || 'Unknown location'}
                    </div>
                    <div style="font-size: 10px; color: #666;">
                        🕐 Last seen: ${new Date(user.updated_at).toLocaleString()}
                    </div>
                    <div style="font-size: 10px; color: #666;">
                        📊 Coordinates: ${parseFloat(user.latitude).toFixed(4)}, ${parseFloat(user.longitude).toFixed(4)}
                    </div>
                </div>
            `);
            
            // Store user data on marker for identification
            marker.userData = user;
            this.userMarkers.addLayer(marker);
        },
        
        updateStatusDisplayFromWebSocket: function() {
            // Count active users from current markers
            let activeUserCount = 0;
            let totalUserCount = 0;
            
            this.userMarkers.eachLayer(layer => {
                if (layer.userData) {
                    totalUserCount++;
                    const status = this.getStatusIcon(layer.userData);
                    if (status.icon === '🟢') {
                        activeUserCount++;
                    }
                }
            });
            
            this.updateStatusDisplay(activeUserCount, totalUserCount);
        },

        addTrackingControls: function() {
            // Add tracking control buttons
            const trackingControl = L.control({ position: 'topright' });
            trackingControl.onAdd = function(map) {
                const div = L.DomUtil.create('div', 'leaflet-bar leaflet-control');
                div.innerHTML = `
                    <div id="tracking-control-panel" style="background: white; padding: 10px; border-radius: 4px; box-shadow: 0 2px 5px rgba(0,0,0,0.2); min-width: 200px; position: relative;">
                        
                        <!-- Header with title and close button -->
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px; padding-bottom: 5px; border-bottom: 1px solid #eee;">
                            <span style="font-size: 12px; font-weight: bold; color: #333;">📍 Location Controls</span>
                            <div>
                                <button id="minimize-tracking-panel" style="width: 18px; height: 18px; background: #f39c12; color: white; border: none; border-radius: 2px; cursor: pointer; font-size: 10px; display: inline-flex; align-items: center; justify-content: center; line-height: 1; margin-right: 3px;" title="Minimize Panel">
                                    −
                                </button>
                                <button id="close-tracking-panel" style="width: 18px; height: 18px; background: #e74c3c; color: white; border: none; border-radius: 2px; cursor: pointer; font-size: 10px; display: inline-flex; align-items: center; justify-content: center; line-height: 1;" title="Hide Panel">
                                    ×
                                </button>
                            </div>
                        </div>
                        
                        <!-- Panel Content (can be minimized) -->
                        <div id="tracking-panel-content">
                        
                        <div style="margin-bottom: 8px;">
                            <button id="toggle-tracking" style="padding: 5px 10px; background: #2ecc71; color: white; border: none; border-radius: 3px; cursor: pointer; font-size: 12px; width: 100%;">
                                🔴 Live Tracking
                            </button>
                        </div>
                        <div style="margin-bottom: 8px;">
                            <button id="toggle-trails" style="padding: 5px 10px; background: #3498db; color: white; border: none; border-radius: 3px; cursor: pointer; font-size: 12px; width: 100%;">
                                📍 Show Trails
                            </button>
                        </div>
                        
                        <!-- Location Update Methods -->
                        <div style="border-top: 1px solid #eee; padding-top: 8px; margin-top: 8px;">
                            <div style="font-size: 11px; font-weight: bold; color: #666; margin-bottom: 6px;">📍 Update My Location:</div>
                            
                            <button id="geolocation-btn" style="padding: 8px 12px; background: #2ecc71; color: white; border: none; border-radius: 3px; cursor: pointer; font-size: 12px; width: 100%; margin-bottom: 4px; font-weight: bold;">
                                � Geolocation
                            </button>
                        </div>
                        
                        <div style="font-size: 10px; color: #666; text-align: center; border-top: 1px solid #eee; padding-top: 6px; margin-top: 6px;">
                            <div id="last-update">Loading...</div>
                            <div id="active-users">0 users</div>
                            <div id="location-status" style="color: #e74c3c;">Location not set</div>
                        </div>
                        
                        </div> <!-- End of tracking-panel-content -->
                    </div>  
                `;
                return div;
            };
            trackingControl.addTo(this.map);

            // Add event listeners
            setTimeout(() => {
                const toggleTracking = document.getElementById('toggle-tracking');
                const toggleTrails = document.getElementById('toggle-trails');
                const closePanelBtn = document.getElementById('close-tracking-panel');
                const minimizePanelBtn = document.getElementById('minimize-tracking-panel');
                const geolocationBtn = document.getElementById('geolocation-btn');
                
                if (toggleTracking) {
                    toggleTracking.addEventListener('click', () => this.toggleTracking());
                }
                
                if (toggleTrails) {
                    toggleTrails.addEventListener('click', () => this.toggleTrails());
                }
                
                if (closePanelBtn) {
                    closePanelBtn.addEventListener('click', () => this.hideTrackingPanel());
                }
                
                if (minimizePanelBtn) {
                    minimizePanelBtn.addEventListener('click', () => this.toggleMinimizePanel());
                }
                
                if (geolocationBtn) {
                    geolocationBtn.addEventListener('click', () => this.handleGeolocation());
                }
                
                console.log('All location update methods initialized');
            }, 100);
        },

        toggleTracking: function() {
            this.isTracking = !this.isTracking;
            const button = document.getElementById('toggle-tracking');
            if (button) {
                if (this.isTracking) {
                    button.style.background = '#2ecc71';
                    button.innerHTML = '🔴 Live Tracking';
                    console.log('🔴 Live tracking enabled');
                } else {
                    button.style.background = '#95a5a6';
                    button.innerHTML = '⏸️ Paused';
                    console.log('⏸️ Live tracking paused');
                }
            }
        },

        toggleTrails: function() {
            if (this.userTrails.getLayers().length > 0) {
                this.userTrails.clearLayers();
                const button = document.getElementById('toggle-trails');
                if (button) {
                    button.style.background = '#3498db';
                    button.innerHTML = '📍 Show Trails';
                }
                console.log('📍 User trails hidden');
            } else {
                this.showUserTrails();
                const button = document.getElementById('toggle-trails');
                if (button) {
                    button.style.background = '#e67e22';
                    button.innerHTML = '🚫 Hide Trails';
                }
                console.log('📍 User trails shown');
            }
        },

        showUserTrails: function() {
            // Draw trails for each user based on their history
            Object.keys(this.userHistory).forEach(userId => {
                const positions = this.userHistory[userId];
                if (positions.length > 1) {
                    const latlngs = positions.map(pos => [pos.lat, pos.lng]);
                    const polyline = L.polyline(latlngs, {
                        color: '#3498db',
                        weight: 3,
                        opacity: 0.7,
                        dashArray: '5, 10'
                    }).bindPopup(`User trail for ${positions[0].name || 'Unknown'}`);
                    this.userTrails.addLayer(polyline);
                }
            });
        },

        updateUserHistory: function(user) {
            if (!user.id || !user.latitude || !user.longitude) return;
            
            const userId = user.id.toString();
            if (!this.userHistory[userId]) {
                this.userHistory[userId] = [];
            }
            
            const newPosition = {
                lat: parseFloat(user.latitude),
                lng: parseFloat(user.longitude),
                timestamp: new Date(),
                name: user.name
            };
            
            // Add new position
            this.userHistory[userId].push(newPosition);
            
            // Keep only last 50 positions to prevent memory issues
            if (this.userHistory[userId].length > 50) {
                this.userHistory[userId] = this.userHistory[userId].slice(-50);
            }
        },

        getStatusIcon: function(user) {
            const lastSeen = new Date(user.updated_at);
            const now = new Date();
            const minutesAgo = (now - lastSeen) / (1000 * 60);
            
            if (minutesAgo < 5) {
                return { color: '#2ecc71', status: 'Online', icon: '🟢' };
            } else if (minutesAgo < 30) {
                return { color: '#f39c12', status: 'Recently Active', icon: '🟡' };
            } else {
                return { color: '#e74c3c', status: 'Offline', icon: '🔴' };
            }
        },

        loadData: function() {
            if (!this.initialized || !this.map) {
                console.log('Map not ready for data loading');
                return;
            }

            if (!this.isTracking) {
                console.log('⏸️ Tracking paused, skipping data load');
                return;
            }

            console.log('📍 Loading enhanced user locations...');

            // Clear existing markers
            this.userMarkers.clearLayers();
            this.locationMarkers.clearLayers();

            // Get users data
            const users = @json($this->getActiveUsers());
            const locations = @json($this->getAllLocations());

            console.log('Users:', users.length, 'Locations:', locations.length);

            let activeUserCount = 0;

            // Add user markers with enhanced status indicators
            users.forEach(user => {
                if (user.latitude && user.longitude) {
                    // Update user history for trail tracking
                    this.updateUserHistory(user);
                    
                    const status = this.getStatusIcon(user);
                    const isActive = status.icon === '🟢';
                    if (isActive) activeUserCount++;
                    
                    const marker = L.marker([parseFloat(user.latitude), parseFloat(user.longitude)], {
                        icon: L.divIcon({
                            className: 'custom-marker',
                            html: `
                                <div style="position: relative;">
                                    <div style="background: ${status.color}; color: white; border-radius: 50%; width: 24px; height: 24px; display: flex; align-items: center; justify-content: center; font-size: 12px; border: 2px solid white; box-shadow: 0 2px 4px rgba(0,0,0,0.3);">
                                        👤
                                    </div>
                                    <div style="position: absolute; top: -2px; right: -2px; background: ${status.color}; border-radius: 50%; width: 8px; height: 8px; border: 1px solid white;"></div>
                                    ${isActive ? '<div style="position: absolute; top: -4px; right: -4px; background: #2ecc71; border-radius: 50%; width: 12px; height: 12px; border: 2px solid white; animation: pulse 2s infinite;"></div>' : ''}
                                </div>
                            `,
                            iconSize: [24, 24],
                            iconAnchor: [12, 12]
                        })
                    }).bindPopup(`
                        <div style="text-align: center; min-width: 180px;">
                            <div style="display: flex; align-items: center; justify-content: center; margin-bottom: 8px;">
                                <strong>👤 ${user.name}</strong>
                                <span style="margin-left: 8px;">${status.icon}</span>
                            </div>
                            <div style="font-size: 12px; color: ${status.color}; margin-bottom: 4px;">
                                ${status.status}
                            </div>
                            <div style="font-size: 11px; margin-bottom: 4px;">
                                📧 ${user.email}
                            </div>
                            <div style="font-size: 11px; margin-bottom: 4px;">
                                📍 ${user.address || 'Unknown location'}
                            </div>
                            <div style="font-size: 10px; color: #666;">
                                🕐 Last seen: ${new Date(user.updated_at).toLocaleString()}
                            </div>
                            <div style="font-size: 10px; color: #666;">
                                📊 Coordinates: ${parseFloat(user.latitude).toFixed(4)}, ${parseFloat(user.longitude).toFixed(4)}
                            </div>
                        </div>
                    `);
                    this.userMarkers.addLayer(marker);
                }
            });

            // Add predefined locations (unchanged)
            locations.forEach(location => {
                if (location.latitude && location.longitude) {
                    const marker = L.marker([parseFloat(location.latitude), parseFloat(location.longitude)], {
                        icon: L.divIcon({
                            className: 'custom-marker',
                            html: `<div style="background: #e74c3c; color: white; border-radius: 50%; width: 24px; height: 24px; display: flex; align-items: center; justify-content: center; font-size: 12px; border: 2px solid white; box-shadow: 0 2px 4px rgba(0,0,0,0.3);">🏢</div>`,
                            iconSize: [24, 24],
                            iconAnchor: [12, 12]
                        })
                    }).bindPopup(`
                        <div style="text-align: center; min-width: 150px;">
                            <strong>🏢 ${location.location}</strong><br>
                            📍 ${location.description || 'Office Location'}<br>
                            🏷️ ${location.address || 'Batam, Indonesia'}
                        </div>
                    `);
                    this.locationMarkers.addLayer(marker);
                }
            });

            // Update status display
            this.updateStatusDisplay(activeUserCount, users.length);

            console.log(`✅ Added ${users.length} users (${activeUserCount} active) and ${locations.length} locations`);
        },

        updateStatusDisplay: function(activeUsers, totalUsers) {
            this.lastUpdateTime = new Date();
            
            const lastUpdateEl = document.getElementById('last-update');
            const activeUsersEl = document.getElementById('active-users');
            
            if (lastUpdateEl) {
                lastUpdateEl.textContent = this.lastUpdateTime.toLocaleTimeString();
            }
            
            if (activeUsersEl) {
                activeUsersEl.textContent = `${activeUsers}/${totalUsers} active`;
            }
        },

        refresh: function() {
            if (!this.isTracking) {
                console.log('⏸️ Tracking paused, refresh skipped');
                return;
            }
            console.log('🔄 Refreshing enhanced tracking data...');
            this.loadData();
        },

        quickUserUpdate: function() {
            if (!this.initialized || !this.map || !this.isTracking) return;
            
            // Quick update just for user positions without clearing all markers
            fetch('/api/location/users/active')
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.data) {
                        console.log('🚀 Quick user update:', data.data.length, 'users');
                        this.updateUserPositions(data.data);
                    }
                })
                .catch(error => {
                    console.log('Quick update failed:', error);
                });
        },

        updateUserPositions: function(users) {
            // Update existing markers or create new ones for users
            this.userMarkers.clearLayers();
            
            let activeUserCount = 0;
            users.forEach(user => {
                if (user.latitude && user.longitude) {
                    this.updateUserHistory(user);
                    
                    const status = this.getStatusIcon(user);
                    const isActive = status.icon === '🟢';
                    if (isActive) activeUserCount++;
                    
                    const marker = L.marker([parseFloat(user.latitude), parseFloat(user.longitude)], {
                        icon: L.divIcon({
                            className: 'custom-marker',
                            html: `
                                <div style="position: relative;">
                                    <div style="background: ${status.color}; color: white; border-radius: 50%; width: 24px; height: 24px; display: flex; align-items: center; justify-content: center; font-size: 12px; border: 2px solid white; box-shadow: 0 2px 4px rgba(0,0,0,0.3);">
                                        👤
                                    </div>
                                    <div style="position: absolute; top: -2px; right: -2px; background: ${status.color}; border-radius: 50%; width: 8px; height: 8px; border: 1px solid white;"></div>
                                    ${isActive ? '<div style="position: absolute; top: -4px; right: -4px; background: #2ecc71; border-radius: 50%; width: 12px; height: 12px; border: 2px solid white; animation: pulse 2s infinite;"></div>' : ''}
                                </div>
                            `,
                            iconSize: [24, 24],
                            iconAnchor: [12, 12]
                        })
                    }).bindPopup(`
                        <div style="text-align: center; min-width: 180px;">
                            <div style="display: flex; align-items: center; justify-content: center; margin-bottom: 8px;">
                                <strong>👤 ${user.name}</strong>
                                <span style="margin-left: 8px;">${status.icon}</span>
                            </div>
                            <div style="font-size: 12px; color: ${status.color}; margin-bottom: 4px;">
                                ${status.status}
                            </div>
                            <div style="font-size: 11px; margin-bottom: 4px;">
                                📧 ${user.email}
                            </div>
                            <div style="font-size: 11px; margin-bottom: 4px;">
                                📍 ${user.address || 'Unknown location'}
                            </div>
                            <div style="font-size: 10px; color: #666;">
                                🕐 Last seen: ${new Date(user.updated_at).toLocaleString()}
                            </div>
                            <div style="font-size: 10px; color: #666;">
                                📊 Coordinates: ${parseFloat(user.latitude).toFixed(4)}, ${parseFloat(user.longitude).toFixed(4)}
                            </div>
                        </div>
                    `);
                    this.userMarkers.addLayer(marker);
                }
            });
            
            this.updateStatusDisplay(activeUserCount, users.length);
        },

        showError: function(message) {
            const container = document.getElementById('simple-map');
            if (container) {
                container.innerHTML = `
                    <div style="display: flex; align-items: center; justify-content: center; height: 100%; color: #e74c3c; text-align: center; flex-direction: column;">
                        <div style="font-size: 48px; margin-bottom: 20px;">🚨</div>
                        <div style="font-size: 18px; margin-bottom: 10px;">Map Error</div>
                        <div style="font-size: 14px; margin-bottom: 20px;">${message}</div>
                        <button onclick="location.reload()" style="padding: 10px 20px; background: #3498db; color: white; border: none; border-radius: 4px; cursor: pointer;">
                            Reload Page
                        </button>
                    </div>
                `;
            }
        },

        updateMyLocation: function() {
            // This method is now called from handleGeolocation for backward compatibility
            this.handleGeolocation();
        },

        handleGeolocation: function() {
            console.log('🌍 Smart Geolocation started...');
            
            const btn = document.getElementById('geolocation-btn');
            if (btn) {
                const originalText = btn.innerHTML;
                btn.innerHTML = '🌍 Detecting...';
                btn.disabled = true;
                
                // Reset button after timeout
                setTimeout(() => {
                    btn.innerHTML = originalText;
                    btn.disabled = false;
                }, 15000);
            }
            
            this.showLocationStatus('Trying smart geolocation...', 'loading');
            
            // Try GPS first if HTTPS
            const isSecure = location.protocol === 'https:' || 
                           location.hostname === 'localhost' || 
                           location.hostname === '127.0.0.1' ||
                           location.hostname.endsWith('.test') ||
                           location.hostname.endsWith('.local');
            
            if (isSecure && navigator.geolocation) {
                console.log('🛰️ Trying GPS first (HTTPS available)...');
                this.showLocationStatus('Getting GPS location...', 'loading');
                
                navigator.geolocation.getCurrentPosition(
                    (position) => {
                        const latitude = position.coords.latitude;
                        const longitude = position.coords.longitude;
                        
                        console.log('✅ GPS location obtained:', latitude, longitude);
                        this.updateLocationOnServer(latitude, longitude, 'GPS Auto-detected');
                        
                        if (btn) {
                            btn.innerHTML = '✅ GPS Success';
                            setTimeout(() => {
                                btn.innerHTML = originalText;
                                btn.disabled = false;
                            }, 3000);
                        }
                    },
                    (error) => {
                        console.log('❌ GPS failed, trying IP-based detection...');
                        this.detectLocationByIP();
                    },
                    {
                        enableHighAccuracy: true,
                        timeout: 8000,
                        maximumAge: 300000 // 5 minutes
                    }
                );
            } else {
                console.log('🌐 HTTPS not available, trying IP-based detection...');
                this.detectLocationByIP();
            }
        },

        autoDetectLocation: function() {
            console.log('GPS auto detection requested...');
            this.showLocationStatus('GPS requires HTTPS. Try IP-based detection instead.', 'error');
            
            // Auto redirect to IP-based detection
            setTimeout(() => {
                this.detectLocationByIP();
            }, 2000);
        },

        detectLocationByIP: function() {
            console.log('Detecting location by IP...');
            this.showLocationStatus('Detecting location by IP...', 'loading');
            
            // Try to get the geolocation button (new) or ip-location button (fallback)
            const btn = document.getElementById('geolocation-btn') || document.getElementById('ip-location');
            if (btn) {
                const originalText = btn.innerHTML;
                btn.innerHTML = '🌍 IP Detecting...';
                btn.disabled = true;
                
                // Reset button after timeout
                setTimeout(() => {
                    btn.innerHTML = originalText;
                    btn.disabled = false;
                }, 10000);
            }
            
            // Try multiple IP geolocation services
            this.tryIPGeolocationServices()
                .then(location => {
                    console.log('IP-based location detected:', location);
                    this.updateLocationOnServer(location.lat, location.lng, `IP-based: ${location.city}, ${location.country}`);
                    
                    if (btn) {
                        btn.innerHTML = '✅ IP Success';
                        setTimeout(() => {
                            btn.innerHTML = originalText;
                            btn.disabled = false;
                        }, 3000);
                    }
                })
                .catch(error => {
                    console.error('IP location detection failed:', error);
                    this.showLocationStatus('IP detection failed, trying timezone...', 'error');
                    
                    if (btn) {
                        btn.innerHTML = '🌍 Trying Timezone...';
                        setTimeout(() => {
                            btn.innerHTML = originalText;
                            btn.disabled = false;
                        }, 8000);
                    }
                    
                    // Fallback to timezone method
                    setTimeout(() => {
                        this.detectLocationByTimezone();
                    }, 2000);
                });
        },

        async tryIPGeolocationServices() {
            // Try multiple free IP geolocation services
            const services = [
                {
                    name: 'ipapi.co',
                    url: 'https://ipapi.co/json/',
                    parse: (data) => ({
                        lat: parseFloat(data.latitude),
                        lng: parseFloat(data.longitude),
                        city: data.city,
                        country: data.country_name
                    })
                },
                {
                    name: 'ip-api.com',
                    url: 'http://ip-api.com/json/',
                    parse: (data) => ({
                        lat: parseFloat(data.lat),
                        lng: parseFloat(data.lon),
                        city: data.city,
                        country: data.country
                    })
                },
                {
                    name: 'ipinfo.io',
                    url: 'https://ipinfo.io/json',
                    parse: (data) => {
                        const loc = data.loc.split(',');
                        return {
                            lat: parseFloat(loc[0]),
                            lng: parseFloat(loc[1]),
                            city: data.city,
                            country: data.country
                        };
                    }
                }
            ];
            
            for (const service of services) {
                try {
                    console.log(`Trying ${service.name}...`);
                    const response = await fetch(service.url, { timeout: 5000 });
                    const data = await response.json();
                    const location = service.parse(data);
                    
                    if (location.lat && location.lng && !isNaN(location.lat) && !isNaN(location.lng)) {
                        console.log(`${service.name} success:`, location);
                        return location;
                    }
                } catch (error) {
                    console.log(`${service.name} failed:`, error.message);
                    continue;
                }
            }
            
            throw new Error('All IP geolocation services failed');
        },

        detectLocationByTimezone: function() {
            console.log('Detecting location by timezone...');
            this.showLocationStatus('Detecting by timezone...', 'loading');
            
            // Try to get the geolocation button (new) or timezone-location button (fallback)
            const btn = document.getElementById('geolocation-btn') || document.getElementById('timezone-location');
            const originalText = btn ? btn.innerHTML : '🌍 Geolocation';
            
            if (btn) {
                btn.innerHTML = '🌍 Timezone...';
                btn.disabled = true;
                
                setTimeout(() => {
                    btn.innerHTML = originalText;
                    btn.disabled = false;
                }, 5000);
            }
            
            try {
                // Get user's timezone
                const timezone = Intl.DateTimeFormat().resolvedOptions().timeZone;
                console.log('User timezone:', timezone);
                
                // Map common Indonesia timezones to approximate locations
                const timezoneLocations = {
                    'Asia/Jakarta': { lat: -6.2088, lng: 106.8456, city: 'Jakarta, Indonesia' },
                    'Asia/Makassar': { lat: -5.1477, lng: 119.4327, city: 'Makassar, Indonesia' },
                    'Asia/Jayapura': { lat: -2.5489, lng: 140.7009, city: 'Jayapura, Indonesia' },
                    // Default for Indonesia if specific city not found
                    'default_indonesia': { lat: 1.1304, lng: 104.0530, city: 'Batam, Indonesia' }
                };
                
                let location = timezoneLocations[timezone];
                
                // If timezone not found, check if it's Indonesia-related
                if (!location && timezone.includes('Asia/')) {
                    location = timezoneLocations['default_indonesia'];
                }
                
                // If still not found, use Batam as default for Indonesian users
                if (!location) {
                    location = timezoneLocations['default_indonesia'];
                }
                
                console.log('Timezone-based location:', location);
                this.updateLocationOnServer(location.lat, location.lng, `Timezone-based: ${location.city}`);
                
                if (btn) {
                    btn.innerHTML = '✅ Timezone OK';
                    setTimeout(() => {
                        btn.innerHTML = originalText;
                        btn.disabled = false;
                    }, 3000);
                }
                
            } catch (error) {
                console.error('Timezone detection failed:', error);
                this.showLocationStatus('Timezone detection failed', 'error');
                
                if (btn) {
                    btn.innerHTML = '❌ TZ Failed';
                    setTimeout(() => {
                        btn.innerHTML = originalText;
                        btn.disabled = false;
                    }, 3000);
                }
                
                // Fallback to last known location
                setTimeout(() => {
                    this.useLastKnownLocation();
                }, 2000);
            }
        },

        useLastKnownLocation: function() {
            console.log('Using last known location...');
            this.showLocationStatus('Loading last known location...', 'loading');
            
            const btn = document.getElementById('last-location');
            const originalText = btn ? btn.innerHTML : '📌 Last Known Location';
            
            if (btn) {
                btn.innerHTML = '📌 Loading...';
                btn.disabled = true;
                
                setTimeout(() => {
                    btn.innerHTML = originalText;
                    btn.disabled = false;
                }, 5000);
            }
            
            // Get current user's stored location from the server
            fetch('/api/location/current', {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                credentials: 'include'
            })
            .then(response => response.json())
            .then(data => {
                if (data.success && data.data && data.data.latitude && data.data.longitude) {
                    const lat = parseFloat(data.data.latitude);
                    const lng = parseFloat(data.data.longitude);
                    const address = data.data.address || 'Last Known Location';
                    
                    console.log('Last known location found:', lat, lng, address);
                    
                    // Update with refreshed timestamp
                    this.updateLocationOnServer(lat, lng, `Last Known: ${address}`);
                    
                    if (btn) {
                        btn.innerHTML = '✅ Last Location';
                        setTimeout(() => {
                            btn.innerHTML = originalText;
                            btn.disabled = false;
                        }, 3000);
                    }
                } else {
                    throw new Error('No last known location found');
                }
            })
            .catch(error => {
                console.error('Last location failed:', error);
                this.showLocationStatus('No last location found', 'error');
                
                if (btn) {
                    btn.innerHTML = '❌ No Last Loc';
                    setTimeout(() => {
                        btn.innerHTML = originalText;
                        btn.disabled = false;
                    }, 3000);
                }
                
                // Fallback to quick locations
                setTimeout(() => {
                    this.showQuickLocations();
                }, 2000);
            });
        },

        showQuickLocations: function() {
            console.log('Showing quick locations...');
            
            // Close any existing dialogs first
            this.closeAllDialogs();
            
            // Quick access buttons for most common locations
            const quickLocs = [
                { name: '🏢 Kantor Pusat', lat: 1.1456, lng: 104.0305, desc: 'Batam Center Office' },
                { name: '🏬 Mall Batam', lat: 1.1344, lng: 104.0186, desc: 'Batam Center Mall' },
                { name: '🏭 Sekupang Port', lat: 1.1193, lng: 103.9738, desc: 'Sekupang Area' },
                { name: '🏖️ Nongsa Beach', lat: 1.1732, lng: 104.0530, desc: 'Nongsa Area' },
                { name: '🏠 Rumah', lat: 1.1040, lng: 104.0340, desc: 'Lubuk Baja Area' },
                { name: '📍 Di Jalan', lat: 1.1204, lng: 104.0204, desc: 'Traveling/Mobile' }
            ];
            
            const dialog = document.createElement('div');
            dialog.style.cssText = `
                position: fixed; top: 20px; right: 20px; width: 350px; 
                background: white; border: 2px solid #ddd; border-radius: 8px; 
                box-shadow: 0 4px 12px rgba(0,0,0,0.3); z-index: 1001; 
                max-height: 80vh; overflow-y: auto;
            `;
            
            const locationsList = quickLocs.map(loc => `
                <button onclick="batamMonitor.selectQuickLocation(${loc.lat}, ${loc.lng}, '${loc.name}', '${loc.desc}'); batamMonitor.closeAllDialogs();"
                        style="width: 100%; padding: 12px; margin-bottom: 8px; background: #e67e22; color: white; border: none; border-radius: 4px; cursor: pointer; text-align: left;">
                    <div style="font-weight: bold;">${loc.name}</div>
                    <div style="font-size: 12px; opacity: 0.9;">${loc.desc}</div>
                </button>
            `).join('');
            
            dialog.id = 'quick-locations-dialog';
            dialog.innerHTML = `
                <div style="background: white; padding: 20px; border-radius: 8px;">
                    <h3 style="margin-top: 0; color: #333;">⚡ Quick Locations</h3>
                    <p style="color: #666; font-size: 14px;">Select a common location:</p>
                    
                    <div>
                        ${locationsList}
                    </div>
                    
                    <div style="text-align: center; margin-top: 15px; border-top: 1px solid #eee; padding-top: 15px;">
                        <button onclick="batamMonitor.closeAllDialogs()" 
                                style="padding: 8px 16px; background: #ccc; border: none; border-radius: 4px; cursor: pointer;">
                            Close
                        </button>
                    </div>
                </div>
            `;
            
            document.body.appendChild(dialog);
        },

        selectQuickLocation: function(lat, lng, name, desc) {
            console.log('Quick location selected:', name, lat, lng);
            this.updateLocationOnServer(lat, lng, `${name} - ${desc}`);
        },

        manualLocationInput: function() {
            console.log('Manual location input requested');
            
            // Close any existing dialogs first
            this.closeAllDialogs();
            
            // Create a more user-friendly input dialog
            const dialog = document.createElement('div');
            dialog.style.cssText = `
                position: fixed; top: 50px; right: 50px; width: 400px; 
                background: white; border: 2px solid #ddd; border-radius: 8px; 
                box-shadow: 0 4px 12px rgba(0,0,0,0.3); z-index: 1001; 
                max-height: 80vh; overflow-y: auto;
            `;
            
            dialog.id = 'manual-location-dialog';
            dialog.innerHTML = `
                <div style="background: white; padding: 20px; border-radius: 8px;">
                    <h3 style="margin-top: 0; color: #333;">📍 Manual Location Input</h3>
                    <p style="color: #666; font-size: 14px;">Enter your coordinates manually:</p>
                    
                    <div style="margin-bottom: 15px;">
                        <label style="display: block; margin-bottom: 5px; font-weight: bold;">Latitude:</label>
                        <input type="number" id="manual-lat" step="any" placeholder="1.1456" 
                               style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                        <small style="color: #888;">Range: -90 to 90</small>
                    </div>
                    
                    <div style="margin-bottom: 15px;">
                        <label style="display: block; margin-bottom: 5px; font-weight: bold;">Longitude:</label>
                        <input type="number" id="manual-lng" step="any" placeholder="104.0305" 
                               style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                        <small style="color: #888;">Range: -180 to 180</small>
                    </div>
                    
                    <div style="margin-bottom: 15px;">
                        <label style="display: block; margin-bottom: 5px; font-weight: bold;">Address (Optional):</label>
                        <input type="text" id="manual-address" placeholder="e.g., Batam Center, Batam" 
                               style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                    </div>
                    
                    <div style="text-align: right;">
                        <button onclick="batamMonitor.closeAllDialogs()" 
                                style="padding: 8px 16px; margin-right: 10px; background: #ccc; border: none; border-radius: 4px; cursor: pointer;">
                            Cancel
                        </button>
                        <button id="manual-submit" 
                                style="padding: 8px 16px; background: #9b59b6; color: white; border: none; border-radius: 4px; cursor: pointer;">
                            Update Location
                        </button>
                    </div>
                </div>
            `;
            
            document.body.appendChild(dialog);
            
            // Handle submit
            document.getElementById('manual-submit').addEventListener('click', () => {
                const lat = parseFloat(document.getElementById('manual-lat').value);
                const lng = parseFloat(document.getElementById('manual-lng').value);
                const address = document.getElementById('manual-address').value || `Manual: ${lat}, ${lng}`;
                
                if (isNaN(lat) || isNaN(lng)) {
                    alert('Please enter valid numbers for latitude and longitude.');
                    return;
                }
                
                if (lat < -90 || lat > 90 || lng < -180 || lng > 180) {
                    alert('Invalid coordinates. Latitude: -90 to 90, Longitude: -180 to 180.');
                    return;
                }
                
                console.log('Manual location input:', lat, lng, address);
                this.updateLocationOnServer(lat, lng, address);
                this.closeAllDialogs();
            });
            
            // Focus on first input
            setTimeout(() => {
                document.getElementById('manual-lat').focus();
            }, 100);
        },

        showOfficeLocations: function() {
            console.log('Showing office locations...');
            
            // Close any existing dialogs first
            this.closeAllDialogs();
            
            // Company office locations
            const officeLocations = [
                { name: '🏢 Head Office', lat: 1.1456, lng: 104.0305, desc: 'Main Office Building' },
                { name: '🏭 Warehouse', lat: 1.1193, lng: 103.9738, desc: 'Storage & Distribution' },
                { name: '🏬 Branch Office 1', lat: 1.1344, lng: 104.0186, desc: 'Customer Service Center' },
                { name: '🏗️ Project Site A', lat: 1.1732, lng: 104.0530, desc: 'Construction Site' },
                { name: '🚛 Field Office', lat: 1.1040, lng: 104.0340, desc: 'Mobile Operations' },
                { name: '🏪 Service Center', lat: 1.1204, lng: 104.0204, desc: 'Technical Support' }
            ];
            
            const dialog = document.createElement('div');
            dialog.style.cssText = `
                position: fixed; top: 20px; left: 20px; width: 350px; 
                background: white; border: 2px solid #ddd; border-radius: 8px; 
                box-shadow: 0 4px 12px rgba(0,0,0,0.3); z-index: 1001; 
                max-height: 80vh; overflow-y: auto;
            `;
            
            const officesList = officeLocations.map(office => `
                <button onclick="batamMonitor.selectOfficeLocation(${office.lat}, ${office.lng}, '${office.name}', '${office.desc}'); batamMonitor.closeAllDialogs();"
                        style="width: 100%; padding: 12px; margin-bottom: 8px; background: #27ae60; color: white; border: none; border-radius: 4px; cursor: pointer; text-align: left;">
                    <div style="font-weight: bold;">${office.name}</div>
                    <div style="font-size: 12px; opacity: 0.9;">${office.desc}</div>
                </button>
            `).join('');
            
            dialog.id = 'office-locations-dialog';
            dialog.innerHTML = `
                <div style="background: white; padding: 20px; border-radius: 8px;">
                    <h3 style="margin-top: 0; color: #333;">🏢 Office Locations</h3>
                    <p style="color: #666; font-size: 14px;">Select your office location:</p>
                    
                    <div>
                        ${officesList}
                    </div>
                    
                    <div style="text-align: center; margin-top: 15px; border-top: 1px solid #eee; padding-top: 15px;">
                        <button onclick="batamMonitor.closeAllDialogs()" 
                                style="padding: 8px 16px; background: #ccc; border: none; border-radius: 4px; cursor: pointer;">
                            Close
                        </button>
                    </div>
                </div>
            `;
            
            document.body.appendChild(dialog);
        },

        selectOfficeLocation: function(lat, lng, name, desc) {
            console.log('Office location selected:', name, lat, lng);
            this.updateLocationOnServer(lat, lng, `${name} - ${desc}`);
        },

        enableMapClick: function() {
            console.log('Map click mode enabled...');
            this.showLocationStatus('Click on the map to set your location', 'loading');
            
            const btn = document.getElementById('map-click');
            if (btn) {
                const originalText = btn.innerHTML;
                btn.innerHTML = '🗺️ Click Map Now!';
                btn.style.background = '#e67e22';
                
                // Enable click listener on map
                if (this.map) {
                    this.map.once('click', (e) => {
                        const lat = e.latlng.lat;
                        const lng = e.latlng.lng;
                        
                        console.log('Map clicked at:', lat, lng);
                        this.updateLocationOnServer(lat, lng, `Map Click: ${lat.toFixed(4)}, ${lng.toFixed(4)}`);
                        
                        btn.innerHTML = '✅ Location Set!';
                        btn.style.background = '#27ae60';
                        
                        setTimeout(() => {
                            btn.innerHTML = originalText;
                            btn.style.background = '';
                        }, 3000);
                    });
                    
                    // Add temporary marker instruction
                    const instruction = L.popup()
                        .setLatLng(this.map.getCenter())
                        .setContent('<strong>Click anywhere on the map</strong><br>to set your location')
                        .openOn(this.map);
                    
                    // Remove instruction after 5 seconds
                    setTimeout(() => {
                        this.map.closePopup(instruction);
                    }, 5000);
                } else {
                    this.showLocationStatus('Map not ready', 'error');
                    btn.innerHTML = originalText;
                }
            }
        },

        closeAllDialogs: function() {
            // Close all location selection dialogs
            const dialogs = [
                'quick-locations-dialog',
                'office-locations-dialog',
                'manual-location-dialog'
            ];
            
            dialogs.forEach(dialogId => {
                const dialog = document.getElementById(dialogId);
                if (dialog) {
                    dialog.remove();
                }
            });
        },

        // Add keyboard event listener for ESC key to close dialogs
        addDialogKeyListener: function() {
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape') {
                    this.closeAllDialogs();
                }
            });
        },

        hideTrackingPanel: function() {
            const panel = document.getElementById('tracking-control-panel');
            if (panel) {
                panel.style.display = 'none';
                
                // Create a small show button
                this.createShowPanelButton();
            }
        },

        showTrackingPanel: function() {
            const panel = document.getElementById('tracking-control-panel');
            if (panel) {
                panel.style.display = 'block';
                
                // Remove the show button
                const showBtn = document.getElementById('show-tracking-panel-btn');
                if (showBtn) {
                    showBtn.remove();
                }
            }
        },

        toggleMinimizePanel: function() {
            const content = document.getElementById('tracking-panel-content');
            const minimizeBtn = document.getElementById('minimize-tracking-panel');
            
            if (content && minimizeBtn) {
                if (content.style.display === 'none') {
                    // Expand
                    content.style.display = 'block';
                    minimizeBtn.innerHTML = '−';
                    minimizeBtn.title = 'Minimize Panel';
                } else {
                    // Minimize
                    content.style.display = 'none';
                    minimizeBtn.innerHTML = '+';
                    minimizeBtn.title = 'Expand Panel';
                }
            }
        },

        createShowPanelButton: function() {
            // Remove existing button if any
            const existingBtn = document.getElementById('show-tracking-panel-btn');
            if (existingBtn) {
                existingBtn.remove();
            }
            
            // Create a small floating button to show panel again
            const showButton = L.control({ position: 'topright' });
            showButton.onAdd = function(map) {
                const div = L.DomUtil.create('div', 'leaflet-bar leaflet-control');
                div.innerHTML = `
                    <button id="show-tracking-panel-btn" style="width: 40px; height: 40px; background: #3498db; color: white; border: none; border-radius: 3px; cursor: pointer; font-size: 16px; display: flex; align-items: center; justify-content: center;">
                        📍
                    </button>
                `;
                return div;
            };
            showButton.addTo(this.map);
            
            // Add event listener
            setTimeout(() => {
                const btn = document.getElementById('show-tracking-panel-btn');
                if (btn) {
                    btn.addEventListener('click', () => {
                        this.showTrackingPanel();
                        showButton.remove();
                    });
                }
            }, 100);
        },

        showPresetLocations: function() {
            console.log('Showing preset locations...');
            
            // Predefined locations in Batam area
            const presetLocations = [
                { name: 'Batam Center', lat: 1.1456, lng: 104.0305, desc: 'Pusat Kota Batam' },
                { name: 'Sekupang', lat: 1.1193, lng: 103.9738, desc: 'Area Sekupang' },
                { name: 'Batu Aji', lat: 1.0778, lng: 103.9464, desc: 'Area Batu Aji' },
                { name: 'Nongsa', lat: 1.1732, lng: 104.0530, desc: 'Area Nongsa' },
                { name: 'Lubuk Baja', lat: 1.1040, lng: 104.0340, desc: 'Area Lubuk Baja' },
                { name: 'Batu Ampar', lat: 1.1344, lng: 104.0186, desc: 'Area Batu Ampar' },
                { name: 'Bengkong', lat: 1.1134, lng: 104.0098, desc: 'Area Bengkong' },
                { name: 'Tiban', lat: 1.0875, lng: 104.0267, desc: 'Area Tiban' }
            ];
            
            const dialog = document.createElement('div');
            dialog.style.cssText = `
                position: fixed; top: 0; left: 0; width: 100%; height: 100%; 
                background: rgba(0,0,0,0.5); z-index: 10000; 
                display: flex; align-items: center; justify-content: center;
            `;
            
            const locationsList = presetLocations.map(loc => `
                <div style="padding: 10px; border: 1px solid #ddd; border-radius: 4px; margin-bottom: 8px; cursor: pointer; transition: background 0.2s;"
                     onclick="batamMonitor.selectPresetLocation(${loc.lat}, ${loc.lng}, '${loc.name}', '${loc.desc}'); this.parentElement.parentElement.parentElement.remove();"
                     onmouseover="this.style.background='#f0f0f0'" onmouseout="this.style.background='white'">
                    <div style="font-weight: bold; color: #333;">📍 ${loc.name}</div>
                    <div style="font-size: 12px; color: #666;">${loc.desc}</div>
                    <div style="font-size: 11px; color: #999;">Lat: ${loc.lat}, Lng: ${loc.lng}</div>
                </div>
            `).join('');
            
            dialog.innerHTML = `
                <div style="background: white; padding: 20px; border-radius: 8px; max-width: 500px; width: 90%; max-height: 80%; overflow-y: auto;">
                    <h3 style="margin-top: 0; color: #333;">🏢 Select Preset Location</h3>
                    <p style="color: #666; font-size: 14px;">Choose from predefined locations in Batam:</p>
                    
                    <div style="max-height: 400px; overflow-y: auto;">
                        ${locationsList}
                    </div>
                    
                    <div style="text-align: right; margin-top: 15px; border-top: 1px solid #eee; padding-top: 15px;">
                        <button onclick="this.parentElement.parentElement.parentElement.remove()" 
                                style="padding: 8px 16px; background: #ccc; border: none; border-radius: 4px; cursor: pointer;">
                            Cancel
                        </button>
                    </div>
                </div>
            `;
            
            document.body.appendChild(dialog);
        },

        selectPresetLocation: function(lat, lng, name, desc) {
            console.log('Preset location selected:', name, lat, lng);
            const address = `${name} - ${desc}`;
            this.updateLocationOnServer(lat, lng, address);
        },

        enableMapClickMode: function() {
            console.log('Map click mode enabled');
            this.showLocationStatus('Click on map to set location', 'info');
            
            const btn = document.getElementById('map-click');
            if (btn) {
                btn.innerHTML = '🎯 Click on Map';
                btn.style.background = '#27ae60';
            }
            
            // Add click event to map
            this.map.once('click', (e) => {
                const lat = e.latlng.lat;
                const lng = e.latlng.lng;
                
                console.log('Map clicked at:', lat, lng);
                
                // Show confirmation
                const address = `Map Click: ${lat.toFixed(4)}, ${lng.toFixed(4)}`;
                
                if (confirm(`Set your location to:\nLatitude: ${lat.toFixed(6)}\nLongitude: ${lng.toFixed(6)}\n\nConfirm?`)) {
                    this.updateLocationOnServer(lat, lng, address);
                }
                
                if (btn) {
                    btn.innerHTML = '🗺️ Click on Map';
                    btn.style.background = '#1abc9c';
                }
            });
            
            // Show temporary marker where user will click
            this.map.on('mousemove', this.showTempMarker);
        },

        showTempMarker: function(e) {
            // Remove existing temp marker
            if (window.tempMarker) {
                batamMonitor.map.removeLayer(window.tempMarker);
            }
            
            // Add temporary marker
            window.tempMarker = L.marker([e.latlng.lat, e.latlng.lng], {
                icon: L.divIcon({
                    className: 'temp-marker',
                    html: '<div style="background: #f39c12; color: white; border-radius: 50%; width: 20px; height: 20px; display: flex; align-items: center; justify-content: center; font-size: 10px; border: 2px solid white; box-shadow: 0 2px 4px rgba(0,0,0,0.3); opacity: 0.7;">📍</div>',
                    iconSize: [20, 20],
                    iconAnchor: [10, 10]
                })
            }).addTo(batamMonitor.map);
        },

        updateLocationOnServer: function(latitude, longitude, address) {
            console.log('Updating location on server:', latitude, longitude, address);
            
            this.showLocationStatus('Updating location...', 'loading');
            
            fetch('/location/update', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                credentials: 'include',
                body: JSON.stringify({
                    latitude: latitude,
                    longitude: longitude,
                    address: address
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    this.showLocationStatus('Location updated successfully!', 'success');
                    
                    // Refresh the map to show updated location
                    setTimeout(() => {
                        if (this.initialized) {
                            this.refresh();
                        }
                    }, 1000);
                } else {
                    throw new Error(data.message || 'Failed to update location');
                }
            })
            .catch(error => {
                console.error('Error updating location:', error);
                this.showLocationStatus('Failed to update location', 'error');
            });
        },

        showLocationStatus: function(message, type) {
            const statusEl = document.getElementById('location-status');
            if (statusEl) {
                statusEl.textContent = message;
                
                switch(type) {
                    case 'success':
                        statusEl.style.color = '#2ecc71';
                        break;
                    case 'error':
                        statusEl.style.color = '#e74c3c';
                        break;
                    case 'loading':
                        statusEl.style.color = '#f39c12';
                        break;
                    case 'info':
                        statusEl.style.color = '#3498db';
                        break;
                    default:
                        statusEl.style.color = '#666';
                }
            }
        },

        showLocationMethodsDialog: function() {
            const dialog = document.createElement('div');
            dialog.style.cssText = `
                position: fixed; top: 0; left: 0; width: 100%; height: 100%; 
                background: rgba(0,0,0,0.5); z-index: 10000; 
                display: flex; align-items: center; justify-content: center;
            `;
            
            dialog.innerHTML = `
                <div style="background: white; padding: 20px; border-radius: 8px; max-width: 400px; width: 90%;">
                    <h3 style="margin-top: 0; color: #333;">📍 Update Your Location</h3>
                    <p style="color: #666; font-size: 14px;">Choose how you want to set your location:</p>
                    
                    <button onclick="batamMonitor.manualLocationInput(); this.parentElement.parentElement.remove();" 
                            style="width: 100%; padding: 12px; margin-bottom: 10px; background: #9b59b6; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 14px;">
                        ✏️ Manual Input (Enter Coordinates)
                    </button>
                    
                    <button onclick="batamMonitor.showPresetLocations(); this.parentElement.parentElement.remove();" 
                            style="width: 100%; padding: 12px; margin-bottom: 10px; background: #f39c12; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 14px;">
                        🏢 Preset Locations (Select from List)
                    </button>
                    
                    <button onclick="batamMonitor.enableMapClickMode(); this.parentElement.parentElement.remove();" 
                            style="width: 100%; padding: 12px; margin-bottom: 15px; background: #1abc9c; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 14px;">
                        🗺️ Click on Map (Visual Selection)
                    </button>
                    
                    <div style="text-align: center;">
                        <button onclick="this.parentElement.parentElement.remove()" 
                                style="padding: 8px 16px; background: #ccc; border: none; border-radius: 4px; cursor: pointer;">
                            Cancel
                        </button>
                    </div>
                </div>
            `;
            
            document.body.appendChild(dialog);
        }
    };

    // Initialize when page loads
    document.addEventListener('DOMContentLoaded', function() {
        console.log('DOM loaded, initializing Batam monitor...');
        
        setTimeout(() => {
            const container = document.getElementById('simple-map');
            if (container && container.offsetWidth > 0) {
                batamMonitor.init();
            } else {
                console.error('Container not ready');
            }
        }, 500);
    });

    // WebSocket fallback polling (only if WebSocket fails)
    let fallbackPolling = null;
    
    // Listen for WebSocket status to enable/disable fallback
    window.addEventListener('websocket-connection-status', function(event) {
        const { status } = event.detail;
        
        if (status === 'fallback_required' || status === 'max_reconnect_attempts_reached') {
            console.log('🔄 Enabling polling fallback');
            
            // Start fallback polling
            if (!fallbackPolling) {
                fallbackPolling = setInterval(() => {
                    if (batamMonitor.initialized && batamMonitor.isTracking) {
                        console.log('📡 Polling fallback refresh');
                        batamMonitor.refresh();
                    }
                }, 15000); // Slower polling as fallback
            }
        } else if (status === 'connected' && fallbackPolling) {
            console.log('✅ WebSocket reconnected, disabling polling fallback');
            clearInterval(fallbackPolling);
            fallbackPolling = null;
        }
    });

    // Global functions for easy access
    window.refreshMap = () => batamMonitor.refresh();
    window.resetMap = () => location.reload();
    
    // Cleanup WebSocket when page unloads
    window.addEventListener('beforeunload', function() {
        if (batamMonitor.webSocketService) {
            batamMonitor.webSocketService.destroy();
        }
        if (fallbackPolling) {
            clearInterval(fallbackPolling);
        }
    });
    </script>
    @endpush
</x-filament-panels::page>
