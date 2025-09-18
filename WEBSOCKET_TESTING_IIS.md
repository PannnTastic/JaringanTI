# WebSocket Location Tracker Testing - IIS Environment

## Prerequisites

1. **Laravel aplikasi sudah running di IIS** (http://jarti.plnbatam.com)
2. **PHP CLI tersedia** untuk menjalankan Artisan commands
3. **User sudah login** ke aplikasi untuk testing

## Step-by-Step Testing

### 1. Start Required Services

Jalankan script berikut untuk memulai services yang diperlukan:

```bash
# Dari PowerShell di direktori project
.\start-websocket-iis.bat
```

Atau jalankan manual:

```bash
# Terminal 1: Reverb WebSocket Server
php artisan reverb:start --debug

# Terminal 2: Queue Worker
php artisan queue:work --tries=1 --timeout=60
```

### 2. Verify Services Running

Pastikan services berjalan dengan benar:

- **Reverb Server**: Harus menampilkan "Starting server on 0.0.0.0:8080 (localhost)"
- **Queue Worker**: Harus menampilkan "Processing jobs from the default queue"
- **IIS Site**: http://jarti.plnbatam.com harus dapat diakses

### 3. Test WebSocket Connection

#### Option A: WebSocket Test Page
1. Buka browser ke: `http://jarti.plnbatam.com/websocket-test`
2. Login jika belum
3. Perhatikan log di halaman test:
   - Harus menampilkan "✅ Echo found"
   - Harus menampilkan "✅ Subscribed to location-tracking channel"
   - Status harus menunjukkan "Connected ✅"

#### Option B: Real-Time Location Tracker
1. Buka browser ke: `http://jarti.plnbatam.com/admin/real-time-location-tracker`
2. Login jika belum
3. Perhatikan console browser (F12):
   - Harus menampilkan "🔄 Initializing Simple WebSocket Location Service..."
   - Harus menampilkan "✅ Subscribed to location-tracking channel"

### 4. Test Location Broadcasting

#### Test dari WebSocket Test Page:
1. Di halaman `http://jarti.plnbatam.com/websocket-test`
2. Klik tombol "📍 Send Test Location"
3. Perhatikan log:
   - Harus menampilkan "📤 Sending test location update..."
   - Harus menampilkan "✅ Location update sent successfully"
   - Dalam beberapa detik, harus menerima: "📍 Location update received"

#### Test dari Multiple Browser/Tabs:
1. Buka 2 browser/tabs ke halaman test
2. Dari satu tab, kirim location update
3. Tab lainnya harus menerima update secara real-time

### 5. Test Location Tracker Integration

1. Buka Real-Time Location Tracker: `http://jarti.plnbatam.com/admin/real-time-location-tracker`
2. Dari tab lain, kirim location update via test page
3. Map harus menampilkan marker user yang baru di-update secara real-time
4. Status display harus terupdate tanpa refresh halaman

## Troubleshooting

### WebSocket Connection Issues

**403 Forbidden pada /broadcasting/auth:**
- Pastikan channel `location-tracking` adalah public channel
- Periksa file `routes/channels.php`

**Echo not found:**
- Pastikan Vite assets sudah di-build: `npm run build`
- Periksa apakah `@vite(['resources/js/app.js'])` dimuat di halaman

**Connection timeout:**
- Pastikan Reverb server running pada port 8080
- Periksa firewall tidak memblokir port 8080
- Test koneksi: `telnet localhost 8080`

### Queue/Broadcasting Issues

**Event tidak terkirim:**
- Pastikan `BROADCAST_CONNECTION=reverb` di .env
- Pastikan Queue Worker running
- Periksa log: `php artisan queue:failed`

**LocationUpdated event error:**
- Periksa struktur User model memiliki field yang benar
- Pastikan event implements `ShouldBroadcast`

### IIS Specific Issues

**Asset tidak loading:**
- Pastikan IIS dapat serve static files dari `/public`
- Periksa URL rewriting rules

**CORS Issues:**
- Pastikan WebSocket server dapat diakses dari domain IIS
- Test dengan browser dev tools Network tab

## Expected Output

### Successful WebSocket Connection Log:
```
[Simple WebSocket Location] 🔄 Initializing Simple WebSocket Location Service...
[Simple WebSocket Location] ⏳ Waiting for Echo... (1/10)
[Simple WebSocket Location] ✅ Echo found
[Simple WebSocket Location] 📡 Setting up location-tracking channel...
[Simple WebSocket Location] 🎯 Channel setup complete
[Simple WebSocket Location] ✅ Subscribed to location-tracking channel
[Simple WebSocket Location] 🔗 Connection status: connected
```

### Successful Location Update Log:
```
[Simple WebSocket Location] 📤 Sending test location update: 1.1456, 104.0305
[Simple WebSocket Location] ✅ Location update sent successfully: {...}
[Simple WebSocket Location] 📍 Location update received: {...}
[Simple WebSocket Location] 📍 Location update processed for user: Test User
```

### Successful Real-Time Map Update:
- Marker muncul di map tanpa refresh
- Popup menunjukkan "(WebSocket)" di status
- Stats cards terupdate otomatis

## Performance Notes

- **Polling Fallback**: Jika WebSocket gagal, sistem akan fallback ke polling setiap 15 detik
- **Memory Usage**: WebSocket connection ringan, minimal impact pada server
- **Latency**: Real-time updates biasanya < 100ms dari server ke client

## Next Steps

Jika testing berhasil, WebSocket location tracker sudah siap untuk production dengan:

1. ✅ Real-time location updates
2. ✅ Automatic fallback ke polling
3. ✅ Error handling dan reconnection
4. ✅ IIS compatibility
5. ✅ Multi-user support

Sistem telah berhasil diupgrade dari polling ke WebSocket untuk real-time location tracking yang lebih efisien.
