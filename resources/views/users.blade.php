@include('partials.head')
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil - {{ config('app.name') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        :root {
            color-scheme: light only !important;
            --primary: #4f46e5;
            --primary-dark: #ffffff;
            --primary-light: #e0e7ff;
            --secondary: #06b6d4;
            --accent: #f59e0b;
            --gray-50: #f9fafb;
            --gray-100: #f3f4f6;
            --gray-200: #e5e7eb;
            --gray-600: #4b5563;
            --gray-700: #374151;
            --gray-900: #111827;
            --gradient-1: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --gradient-2: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            --gradient-3: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            --gradient-4: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
        }
        html, body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #4738cd 100%);
            min-height: 100vh;
            position: relative;
        }
        body::before {
            content: '';
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            background:
                radial-gradient(circle at 25% 25%, rgba(255,255,255,0.08) 0%, transparent 50%),
                radial-gradient(circle at 75% 75%, rgba(255,255,255,0.08) 0%, transparent 50%);
            pointer-events: none;
            z-index: 0;
        }
        .main-container {
            background: rgba(255,255,255,0.13);
            backdrop-filter: blur(18px);
            border: 1px solid rgba(255,255,255,0.18);
            border-radius: 24px;
            box-shadow: 0 25px 50px rgba(0,0,0,0.10);
            position: relative;
            z-index: 1;
        }
        .page-header {
            background: rgba(255,255,255,0.97);
            backdrop-filter: blur(18px);
            border-radius: 20px;
            padding: 2rem;
            margin-bottom: 3rem;
            border: 1px solid rgba(255,255,255,0.22);
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            position: relative;
            overflow: hidden;
        }
        .page-header::before {
            content: '';
            position: absolute;
            top: -50%; left: -50%; width: 200%; height: 200%;
            background: linear-gradient(45deg, transparent 30%, rgba(255,255,255,0.08) 50%, transparent 70%);
            animation: shimmer 3s linear infinite;
        }
        @keyframes shimmer {
            0% { transform: translateX(-100%) translateY(-100%) rotate(45deg);}
            100% { transform: translateX(100%) translateY(100%) rotate(45deg);}
        }
        .idcard {
            width: 320px; height: 480px; border-radius: 20px; overflow: hidden;
            background: rgba(255,255,255,0.97); backdrop-filter: blur(18px);
            border: 1px solid rgba(255,255,255,0.22);
            box-shadow: 0 20px 40px rgba(0,0,0,0.10), inset 0 1px 0 rgba(255,255,255,0.13);
            transform-style: preserve-3d; perspective: 1000px;
            transition: all 0.4s cubic-bezier(0.175,0.885,0.32,1.275);
            position: relative; opacity: 0; transform: translateY(30px);
            animation: card-appear 0.8s ease-out forwards;
        }
        @keyframes card-appear { to { opacity: 1; transform: translateY(0); } }
        .idcard:hover {
            transform: translateY(-15px) rotateX(5deg) rotateY(5deg) scale(1.05);
            box-shadow: 0 35px 60px rgba(0,0,0,0.18), inset 0 1px 0 rgba(255,255,255,0.22);
        }
        .idcard-header {
            height: 80px; position: relative; overflow: hidden;
        }
        .idcard:nth-child(4n+1) .idcard-header { background: var(--gradient-1);}
        .idcard:nth-child(4n+2) .idcard-header { background: var(--gradient-2);}
        .idcard:nth-child(4n+3) .idcard-header { background: var(--gradient-3);}
        .idcard:nth-child(4n+4) .idcard-header { background: var(--gradient-4);}
        .idcard-header::before {
            content: ''; position: absolute; top: 0; left: -100%; width: 100%; height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.22), transparent);
            animation: shimmer 2s linear infinite;
        }
        .user-photo {
            width: 100%; height: 240px; object-fit: cover; transition: all 0.4s ease; position: relative;
        }
        .idcard:hover .user-photo { transform: scale(1.07);}
        .photo-overlay {
            position: absolute; top: 0; left: 0; width: 100%; height: 100%;
            background: linear-gradient(180deg, transparent 0%, rgba(0,0,0,0.10) 100%);
            opacity: 0; transition: opacity 0.3s ease;
        }
        .idcard:hover .photo-overlay { opacity: 1;}
        .idcard-body {
            padding: 1.5rem; text-align: center; background: rgba(255,255,255,0.85);
            backdrop-filter: blur(8px); position: relative;
        }
        .user-name {
            font-size: 1.125rem; font-weight: 700; color: var(--gray-900);
            margin-bottom: 0.5rem; position: relative;
        }
        .user-field {
            font-size: 0.875rem; color: var(--gray-600); font-weight: 500;
            background: var(--primary-light); padding: 0.25rem 0.75rem;
            border-radius: 20px; display: inline-block;
            border: 1px solid rgba(79,70,229,0.18);
            max-width: 90%; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
        }
        .lanyard {
            width: 24px; height: 80px;
            background: linear-gradient(180deg, #6366f1, #4338ca);
            border-radius: 12px; position: relative; margin-bottom: -10px;
            box-shadow: 0 8px 16px rgba(99,102,241,0.22); z-index: 2;
            animation: float 3s ease-in-out infinite;
        }
        @keyframes float { 0%,100%{transform:translateY(0px);} 50%{transform:translateY(-8px);} }
        .lanyard::after {
            content: ""; position: absolute; bottom: -8px; left: 50%; transform: translateX(-50%);
            width: 32px; height: 16px;
            background: linear-gradient(135deg, #374151, #1f2937);
            border-radius: 8px;
            box-shadow: inset 0 2px 4px rgba(255,255,255,0.18), 0 4px 8px rgba(0,0,0,0.22);
        }
        .stats-card {
            background: rgba(255,255,255,0.93); backdrop-filter: blur(13px);
            border: 1px solid rgba(255,255,255,0.22); border-radius: 16px;
            padding: 1.5rem; box-shadow: 0 10px 25px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
        }
        .stats-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.13);
        }
        .search-container { position: relative; max-width: 400px; margin: 0 auto 2rem;}
        .search-input {
            width: 100%; padding: 1rem 1rem 1rem 3rem;
            background: rgba(255,255,255,0.93); border: 1px solid rgba(255,255,255,0.22);
            border-radius: 50px; outline: none; transition: all 0.3s ease;
            backdrop-filter: blur(8px);
        }
        .search-input:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(79,70,229,0.09);
        }
        .search-icon {
            position: absolute; left: 1rem; top: 50%; transform: translateY(-50%);
            color: var(--gray-600);
        }
        .filter-btn {
            padding: 0.75rem 1.5rem; background: rgba(255,255,255,0.85);
            border: 1px solid rgba(255,255,255,0.22); border-radius: 25px;
            color: var(--gray-700); font-weight: 500; transition: all 0.3s ease;
            backdrop-filter: blur(8px);
        }
        .filter-btn:hover, .filter-btn.active {
            background: var(--primary); color: white; transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(79,70,229,0.22);
        }
        .fab {
            position: fixed; bottom: 2rem; right: 2rem; width: 60px; height: 60px;
            background: var(--primary); border-radius: 50%; display: flex; align-items: center; justify-content: center;
            color: white; font-size: 1.5rem; box-shadow: 0 10px 30px rgba(79,70,229,0.28);
            transition: all 0.3s ease; z-index: 1000;
        }
        .fab:hover { transform: scale(1.1); box-shadow: 0 15px 40px rgba(79,70,229,0.38);}
        .loading {
            display: inline-block; width: 20px; height: 20px;
            border: 3px solid rgba(255,255,255,0.22); border-radius: 50%;
            border-top-color: white; animation: spin 1s ease-in-out infinite;
        }
        @keyframes spin { to { transform: rotate(360deg); } }
        .user-grid {
            display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 3rem; place-items: center; padding: 2rem 0;
        }
        @media (max-width: 768px) {
            .idcard { width: 280px; height: 420px;}
            .user-grid { grid-template-columns: 1fr; gap: 2rem;}
            .main-container { margin: 1rem; padding: 1.5rem;}
        }
        @keyframes pulse-soft {
            0%,100% { box-shadow: 0 0 0 0 rgba(79,70,229,0.22);}
            50% { box-shadow: 0 0 0 10px rgba(79,70,229,0);}
        }
        .new-user { animation: pulse-soft 2s infinite;}
        .tooltip { position: relative;}
        .tooltip:hover::after {
            content: attr(data-tooltip); position: absolute; bottom: -35px; left: 50%; transform: translateX(-50%);
            background: rgba(0,0,0,0.85); color: white; padding: 0.5rem 1rem; border-radius: 6px;
            font-size: 0.75rem; white-space: nowrap; z-index: 1000;
        }
    </style>
</head>
<body>
    <div class="min-h-screen py-8 px-4">
        <div class="container mx-auto">
            <div class="mb-6">
                <a href="/"
                   class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-white/80 hover:bg-indigo-100 text-indigo-700 font-semibold shadow transition">
                    <i class="fas fa-arrow-left"></i>
                    Kembali
                </a>
            </div>
            <div class="page-header text-center">
                <h1 class="text-4xl font-bold text-gray-900 mb-4">
                    <i class="fas fa-users text-indigo-600 mr-3"></i>
                    Daftar User
                </h1>
                <p class="text-gray-600 text-lg">Temukan dan jelajahi profil anggota tim kami</p>
            </div>

            <div class="main-container p-8">
                <!-- User Cards Grid -->
                <div class="user-grid" id="userGrid">
                    @forelse($users as $user)
                        <div class="idcard">
                            <div class="idcard-header">
                                
                            </div>
                            
                            <div class="relative">
                                <img src="{{ $user->user_photo 
                                    ? asset('storage/' . $user->user_photo) 
                                    : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) }}" 
                                    alt="{{ $user->name }}"
                                    class="user-photo">
                            </div>

                            <div class="idcard-body">
                                <h2 class="user-name">{{ $user->name }}</h2>
                                <div class="user-field">
                                    @if($user->field)
                                        <i class="fas fa-briefcase mr-2"></i>
                                        {{ $user->field->field_name }}
                                    @else
                                        <i class="fas fa-user mr-2"></i>
                                        General User
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center col-span-full">
                            <p class="text-gray-500">Tidak ada user yang ditemukan</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</body>


<script>
  // 3D tilt interaktif per card (ukuran card tidak diubah)
  document.querySelectorAll('.tilt3d').forEach(card => {
    const maxRotateY = 10;  // derajat
    const maxRotateX = 8;   // derajat

    card.addEventListener('mousemove', (e) => {
      const rect = card.getBoundingClientRect();
      const x = e.clientX - rect.left;
      const y = e.clientY - rect.top;
      const px = (x / rect.width) - 0.5;   // -0.5 .. 0.5
      const py = (y / rect.height) - 0.5;

      const ry = px * maxRotateY;          // horizontal → rotateY
      const rx = -py * maxRotateX;         // vertical → rotateX

      card.style.setProperty('--ry', ry.toFixed(2) + 'deg');
      card.style.setProperty('--rx', rx.toFixed(2) + 'deg');

      // posisi glare
      card.style.setProperty('--gx', (x / rect.width) * 100 + '%');
      card.style.setProperty('--gy', (y / rect.height) * 100 + '%');

      // intensitas glare dinamis
      const dCenter = Math.hypot(px, py); // 0..~0.7
      const glare = Math.max(0.15, 0.45 - dCenter); // lebih kuat dekat center
      card.style.setProperty('--glareHover', glare.toFixed(2));
    });

    card.addEventListener('mouseleave', () => {
      card.style.setProperty('--ry', '0deg');
      card.style.setProperty('--rx', '0deg');
      card.style.setProperty('--glareHover', '.0');
    });
  });
</script>

</body>
</html>