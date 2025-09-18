<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>WebSocket Location Tracker Test</title>
    @vite(['resources/js/app.js'])
    <style>
        body { 
            font-family: Arial, sans-serif; 
            max-width: 800px; 
            margin: 0 auto; 
            padding: 20px; 
        }
        .status { 
            padding: 10px; 
            margin: 10px 0; 
            border-radius: 4px; 
        }
        .status.connected { 
            background-color: #d4edda; 
            color: #155724; 
            border: 1px solid #c3e6cb; 
        }
        .status.disconnected { 
            background-color: #f8d7da; 
            color: #721c24; 
            border: 1px solid #f5c6cb; 
        }
        .log { 
            height: 300px; 
            overflow-y: auto; 
            border: 1px solid #ddd; 
            padding: 10px; 
            font-family: monospace; 
            font-size: 12px; 
            background-color: #f8f9fa; 
        }
        .controls { 
            margin: 20px 0; 
        }
        .controls button { 
            margin: 5px; 
            padding: 10px 20px; 
            background-color: #007bff; 
            color: white; 
            border: none; 
            border-radius: 4px; 
            cursor: pointer; 
        }
        .controls button:hover { 
            background-color: #0056b3; 
        }
        .user-list {
            border: 1px solid #ddd;
            padding: 10px;
            margin: 10px 0;
            max-height: 200px;
            overflow-y: auto;
        }
        .user-item {
            padding: 5px;
            margin: 2px 0;
            background-color: #f1f3f4;
            border-radius: 3px;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <h1>🚀 WebSocket Location Tracker Test</h1>
    
    <div id="connection-status" class="status disconnected">
        WebSocket: Disconnected
    </div>
    
    <div class="controls">
        <button onclick="testLocationUpdate()">📍 Send Test Location</button>
        <button onclick="testMultipleUpdates()">📍 Send Multiple Updates</button>
        <button onclick="reconnectWebSocket()">🔄 Reconnect</button>
        <button onclick="clearLog()">🧹 Clear Log</button>
    </div>
    
    <h3>📋 Active Users</h3>
    <div id="user-list" class="user-list">
        No users yet...
    </div>
    
    <h3>📝 WebSocket Log</h3>
    <div id="log" class="log"></div>
    
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

    let webSocketService;
    let activeUsers = new Map();

    function log(message) {
        const logEl = document.getElementById('log');
        const timestamp = new Date().toLocaleTimeString();
        logEl.innerHTML += `<div>[${timestamp}] ${message}</div>`;
        logEl.scrollTop = logEl.scrollHeight;
    }

    function updateConnectionStatus(status, isConnected = false) {
        const statusEl = document.getElementById('connection-status');
        statusEl.textContent = `WebSocket: ${status}`;
        statusEl.className = `status ${isConnected ? 'connected' : 'disconnected'}`;
    }

    function updateUserList() {
        const userListEl = document.getElementById('user-list');
        if (activeUsers.size === 0) {
            userListEl.innerHTML = 'No users yet...';
            return;
        }

        let html = '';
        activeUsers.forEach((userData, userId) => {
            const lastUpdate = new Date(userData.timestamp).toLocaleTimeString();
            html += `
                <div class="user-item">
                    <strong>👤 ${userData.name}</strong> (${userData.email})
                    <br>📍 ${userData.latitude}, ${userData.longitude}
                    <br>🕐 ${lastUpdate}
                </div>
            `;
        });
        userListEl.innerHTML = html;
    }

    function initWebSocket() {
        log('🔄 Initializing WebSocket Location Service...');
        
        webSocketService = new SimpleWebSocketLocationService({
            debug: true,
            onLocationUpdate: (locationData) => {
                log(`📍 Location update received: ${locationData.name} at ${locationData.latitude}, ${locationData.longitude}`);
                
                // Update active users map
                activeUsers.set(locationData.user_id, {
                    ...locationData,
                    timestamp: new Date().toISOString()
                });
                
                updateUserList();
            },
            onConnectionStatus: (status, data) => {
                log(`🔗 WebSocket status: ${status}`);
                
                switch(status) {
                    case 'connected':
                        updateConnectionStatus('Connected ✅', true);
                        break;
                    case 'disconnected':
                        updateConnectionStatus('Disconnected ❌', false);
                        break;
                    case 'error':
                        updateConnectionStatus('Error ⚠️', false);
                        log(`❌ WebSocket error: ${data}`);
                        break;
                    case 'fallback_required':
                        updateConnectionStatus('Fallback Required ⚠️', false);
                        break;
                }
            }
        });
    }

    async function testLocationUpdate() {
        if (!webSocketService) {
            log('❌ WebSocket service not initialized');
            return;
        }

        const testLatitude = 1.1456 + (Math.random() - 0.5) * 0.01; // Random around Batam Center
        const testLongitude = 104.0305 + (Math.random() - 0.5) * 0.01;
        const testAddress = `Test Location ${Math.floor(Math.random() * 1000)}`;

        log(`📤 Sending test location update: ${testLatitude}, ${testLongitude}`);

        try {
            const result = await webSocketService.sendLocationUpdate(testLatitude, testLongitude, testAddress);
            log(`✅ Location update sent successfully: ${JSON.stringify(result)}`);
        } catch (error) {
            log(`❌ Failed to send location update: ${error.message}`);
        }
    }

    async function testMultipleUpdates() {
        log('📤 Sending multiple test location updates...');
        
        for (let i = 0; i < 5; i++) {
            setTimeout(async () => {
                await testLocationUpdate();
            }, i * 1000); // Send one update every second
        }
    }

    function reconnectWebSocket() {
        log('🔄 Reconnecting WebSocket...');
        
        if (webSocketService) {
            webSocketService.destroy();
        }
        
        setTimeout(() => {
            initWebSocket();
        }, 1000);
    }

    function clearLog() {
        document.getElementById('log').innerHTML = '';
    }

    // Initialize WebSocket when page loads
    document.addEventListener('DOMContentLoaded', function() {
        log('🚀 Page loaded, initializing WebSocket test...');
        initWebSocket();
    });

    // Cleanup on page unload
    window.addEventListener('beforeunload', function() {
        if (webSocketService) {
            webSocketService.destroy();
        }
    });
    </script>
</body>
</html>
