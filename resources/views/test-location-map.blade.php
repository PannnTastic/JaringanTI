<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Location Map</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        body {
            margin: 0;
            padding: 20px;
            font-family: Arial, sans-serif;
            background: #f5f5f5;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }
        
        #simple-map {
            width: 100%;
            height: 500px;
            border: 2px solid #ddd;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        
        .status {
            padding: 10px;
            background: #e7f3ff;
            border: 1px solid #bee5eb;
            border-radius: 4px;
            margin-bottom: 10px;
        }
        
        .error {
            background: #f8d7da;
            border-color: #f5c6cb;
            color: #721c24;
        }
        
        .success {
            background: #d4edda;
            border-color: #c3e6cb;
            color: #155724;
        }
        
        .buttons {
            text-align: center;
            gap: 10px;
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
        }
        
        button {
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            margin: 5px;
        }
        
        .btn-primary { background: #007bff; color: white; }
        .btn-success { background: #28a745; color: white; }
        .btn-danger { background: #dc3545; color: white; }
        .btn-info { background: #17a2b8; color: white; }
        
        button:hover {
            opacity: 0.8;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🗺️ Test Location Map - Simple Version</h1>
        
        <div id="status" class="status">
            ⏳ Initializing map...
        </div>
        
        <div id="simple-map"></div>
        
        <div class="buttons">
            <button class="btn-primary" onclick="createBasicMap()">Create Basic Map</button>
            <button class="btn-success" onclick="addTestMarkers()">Add Test Markers</button>
            <button class="btn-info" onclick="checkMapStatus()">Check Status</button>
            <button class="btn-danger" onclick="resetMap()">Reset Map</button>
        </div>
        
        <div id="debug-info" style="margin-top: 20px; padding: 10px; background: #f8f9fa; border-radius: 4px; font-family: monospace; font-size: 12px; white-space: pre-wrap;"></div>
    </div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        let testMap = null;
        let testMarkers = [];
        
        function updateStatus(message, type = 'info') {
            const statusDiv = document.getElementById('status');
            statusDiv.textContent = message;
            statusDiv.className = `status ${type}`;
        }
        
        function updateDebugInfo(info) {
            document.getElementById('debug-info').textContent = info;
        }
        
        function createBasicMap() {
            updateStatus('🔧 Creating basic map...', 'info');
            
            try {
                // Clear any existing map
                if (testMap) {
                    testMap.remove();
                    testMap = null;
                }
                
                // Clear container
                const container = document.getElementById('simple-map');
                container.innerHTML = '';
                
                // Create map
                testMap = L.map('simple-map', {
                    center: [-6.2088, 106.8456], // Jakarta
                    zoom: 10,
                    zoomControl: true
                });
                
                // Add tile layer
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '© OpenStreetMap contributors',
                    maxZoom: 19
                }).addTo(testMap);
                
                updateStatus('✅ Basic map created successfully!', 'success');
                updateDebugInfo(`Map created at: ${new Date().toISOString()}
Container ID: simple-map
Map center: ${testMap.getCenter().toString()}
Map zoom: ${testMap.getZoom()}
Leaflet version: ${L.version}`);
                
            } catch (error) {
                updateStatus(`❌ Error creating map: ${error.message}`, 'error');
                updateDebugInfo(`Error: ${error.message}
Stack: ${error.stack}`);
            }
        }
        
        function addTestMarkers() {
            if (!testMap) {
                updateStatus('❌ No map available. Create map first.', 'error');
                return;
            }
            
            try {
                // Clear existing markers
                testMarkers.forEach(marker => testMap.removeLayer(marker));
                testMarkers = [];
                
                // Add test markers around Jakarta
                const testLocations = [
                    { lat: -6.2088, lng: 106.8456, name: 'Jakarta Center', type: 'city' },
                    { lat: -6.1751, lng: 106.8650, name: 'Monas', type: 'landmark' },
                    { lat: -6.2297, lng: 106.6890, name: 'Jakarta Airport', type: 'airport' },
                    { lat: -6.1944, lng: 106.8229, name: 'Jakarta Station', type: 'station' }
                ];
                
                testLocations.forEach(location => {
                    const color = location.type === 'city' ? 'red' : 
                                location.type === 'landmark' ? 'blue' :
                                location.type === 'airport' ? 'green' : 'orange';
                    
                    const marker = L.marker([location.lat, location.lng])
                        .addTo(testMap)
                        .bindPopup(`
                            <b>${location.name}</b><br>
                            Type: ${location.type}<br>
                            Lat: ${location.lat}<br>
                            Lng: ${location.lng}
                        `);
                    
                    testMarkers.push(marker);
                });
                
                updateStatus(`✅ Added ${testMarkers.length} test markers`, 'success');
                
                // Fit map to markers
                const group = new L.featureGroup(testMarkers);
                testMap.fitBounds(group.getBounds().pad(0.1));
                
            } catch (error) {
                updateStatus(`❌ Error adding markers: ${error.message}`, 'error');
                updateDebugInfo(`Marker Error: ${error.message}`);
            }
        }
        
        function checkMapStatus() {
            const container = document.getElementById('simple-map');
            const leafletContainer = container.querySelector('.leaflet-container');
            
            const status = {
                mapExists: !!testMap,
                containerExists: !!container,
                leafletContainerExists: !!leafletContainer,
                containerDimensions: container ? `${container.offsetWidth}x${container.offsetHeight}` : 'N/A',
                leafletVersion: typeof L !== 'undefined' ? L.version : 'N/A',
                markersCount: testMarkers.length,
                mapCenter: testMap ? testMap.getCenter().toString() : 'N/A',
                mapZoom: testMap ? testMap.getZoom() : 'N/A'
            };
            
            const statusText = Object.entries(status)
                .map(([key, value]) => `${key}: ${value}`)
                .join('\n');
                
            updateDebugInfo(`Map Status Check at ${new Date().toISOString()}:
${statusText}`);
            
            if (status.mapExists && status.leafletContainerExists) {
                updateStatus('✅ Map status: OK', 'success');
            } else {
                updateStatus('⚠️ Map status: Issues detected', 'error');
            }
        }
        
        function resetMap() {
            updateStatus('🔄 Resetting map...', 'info');
            
            try {
                // Remove all markers
                testMarkers.forEach(marker => {
                    if (testMap) testMap.removeLayer(marker);
                });
                testMarkers = [];
                
                // Remove map
                if (testMap) {
                    testMap.remove();
                    testMap = null;
                }
                
                // Clear container
                const container = document.getElementById('simple-map');
                container.innerHTML = '';
                
                updateStatus('✅ Map reset complete', 'success');
                updateDebugInfo('Map reset completed successfully');
                
            } catch (error) {
                updateStatus(`❌ Reset error: ${error.message}`, 'error');
                updateDebugInfo(`Reset Error: ${error.message}`);
            }
        }
        
        // Auto-initialize when page loads
        document.addEventListener('DOMContentLoaded', function() {
            updateStatus('🚀 Page loaded, ready to create map', 'info');
            
            // Check if Leaflet is available
            if (typeof L === 'undefined') {
                updateStatus('❌ Leaflet library not loaded!', 'error');
                return;
            }
            
            updateDebugInfo(`Page loaded at: ${new Date().toISOString()}
Leaflet version: ${L.version}
Ready to create map.`);
            
            // Auto-create map after 1 second
            setTimeout(createBasicMap, 1000);
        });
        
        // Handle window resize
        window.addEventListener('resize', function() {
            if (testMap) {
                setTimeout(() => {
                    testMap.invalidateSize();
                    updateStatus('🔄 Map resized for new window size', 'info');
                }, 100);
            }
        });
    </script>
</body>
</html>
