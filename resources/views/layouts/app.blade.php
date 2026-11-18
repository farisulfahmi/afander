<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fashion Store POS - Toko Pakaian Afander</title>
    <script src="https://cdn.tailwindcss.com"></script>
    @stack('styles')
</head>
<body class="bg-[#F3F4F6] font-sans antialiased text-gray-800">
    <div class="min-h-screen flex flex-col">
        <!-- TOP BAR (HEADER) DENGAN IKON MONOKROM PUTIH SEPERTI GAMBAR 2 -->
        <header class="bg-[#14171F] text-white h-16 px-6 flex items-center justify-between shadow">
            <div class="flex items-center space-x-3">
                <div class="text-white">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 6a2 2 0 10-2-2m2 2l8 5H4l8-5z"/>
                    </svg>
                </div>
                <div>
                    <div class="font-bold text-base leading-none">Fashion Store</div>
                    <div class="text-[11px] text-gray-400 mt-1">Point of Sales (POS)</div>
                </div>
            </div>

            <div class="flex items-center space-x-6 text-sm">
                <!-- Profil Pengguna -->
                <div class="flex items-center space-x-2 text-gray-200">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    <span>{{ auth()->check() ? auth()->user()->name : 'Pengguna' }}</span>
                </div>

                @if(auth()->check() && auth()->user()->role === 'Pemilik' && request()->is('kasir*'))
                    <a href="{{ route('produk.index') }}" class="text-xs bg-gray-800 hover:bg-gray-700 px-3 py-1.5 rounded-lg border border-gray-600">
                        ← Panel Pemilik
                    </a>
                @endif

                <div class="h-6 w-px bg-gray-700"></div>
                
                <!-- Tanggal & Waktu (Ikon Kalender Monokrom Putih) -->
                <div class="flex items-center space-x-2.5 text-gray-300 text-xs">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <div>
                        <div class="font-semibold text-white" id="live-date">10 Nov 2026</div>
                        <div class="text-gray-400" id="live-time">14:35:20</div>
                    </div>
                </div>

                <div class="h-6 w-px bg-gray-700"></div>

                <!-- Tombol Keluar / Logout (Ikon Logout Monokrom Putih) -->
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="flex flex-col items-center text-gray-300 hover:text-white text-xs">
                        <svg class="w-5 h-5 text-white mb-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                        <span>Keluar</span>
                    </button>
                </form>
            </div>
        </header>

        <div class="flex flex-grow">
            <!-- SIDEBAR (Hanya tampil jika bukan Kasir, misal: Pemilik) -->
            @if(auth()->check() && auth()->user()->role !== 'Kasir')
                <aside class="w-56 bg-white border-r border-gray-200 flex flex-col justify-between shrink-0">
                    <nav class="p-3 space-y-1 text-sm">
                        <a href="{{ route('produk.index') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-lg text-gray-700 hover:bg-gray-100">
                            <svg class="w-5 h-5 fill-current text-gray-700" viewBox="0 0 24 24"><path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/></svg>
                            <span>Dashboard</span>
                        </a>
                        <a href="{{ route('produk.index') }}" class="flex items-center justify-between px-3 py-2.5 rounded-lg {{ request()->routeIs('produk.*') ? 'bg-gray-200/80 text-gray-900 font-semibold' : 'text-gray-700 hover:bg-gray-100' }}">
                            <div class="flex items-center space-x-3">
                                <svg class="w-5 h-5 fill-current {{ request()->routeIs('produk.*') ? 'text-gray-900' : 'text-gray-700' }}" viewBox="0 0 24 24"><path d="M12 2l-5.5 9h11z"/><path d="M2 13h20v2H2z"/><path d="M4 17h16v2H4z"/><path d="M6 21h12v2H6z"/></svg>
                                <span>Data Produk</span>
                            </div>
                            @if(request()->routeIs('produk.*'))<span>›</span>@endif
                        </a>
                        <a href="{{ route('verifikasi.index') }}" class="flex items-center justify-between px-3 py-2.5 rounded-lg {{ request()->routeIs('verifikasi.*') ? 'bg-gray-200/80 text-gray-900 font-semibold' : 'text-gray-700 hover:bg-gray-100' }}">
                            <div class="flex items-center space-x-3">
                                <svg class="w-5 h-5 fill-current {{ request()->routeIs('verifikasi.*') ? 'text-gray-900' : 'text-gray-700' }}" viewBox="0 0 24 24"><path d="M20 8h-3V4H1v13h2a3 3 0 006 0h6a3 3 0 006 0h1v-5l-3-4zM6 18a1.5 1.5 0 110-3 1.5 1.5 0 010 3zm12 0a1.5 1.5 0 110-3 1.5 1.5 0 010 3zM19 10.5l1.96 2.5H17V10.5h2z"/></svg>
                                <span>Verifikasi Stok</span>
                            </div>
                            @if(request()->routeIs('verifikasi.*'))<span>›</span>@endif
                        </a>
                        <a href="{{ route('kasir.index') }}" class="flex items-center justify-between px-3 py-2.5 rounded-lg {{ request()->routeIs('kasir.*') ? 'bg-gray-200/80 text-gray-900 font-semibold' : 'text-gray-700 hover:bg-gray-100' }}">
                            <div class="flex items-center space-x-3">
                                <svg class="w-5 h-5 fill-current {{ request()->routeIs('kasir.*') ? 'text-gray-900' : 'text-gray-700' }}" viewBox="0 0 24 24"><path d="M3.5 18.49l6-6.01 4 4L22 6.92l-1.41-1.41-7.09 7.97-4-4L2 16.99z"/></svg>
                                <span>Transaksi</span>
                            </div>
                            @if(request()->routeIs('kasir.*'))<span>›</span>@endif
                        </a>
                        <a href="#" class="flex items-center space-x-3 px-3 py-2.5 rounded-lg text-gray-700 hover:bg-gray-100">
                            <svg class="w-5 h-5 fill-current text-gray-700" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8l-6-6zm2 16H8v-2h8v2zm0-4H8v-2h8v2zm-3-5V3.5L18.5 9H13z"/></svg>
                            <span>Laporan</span>
                        </a>
                        <a href="#" class="flex items-center space-x-3 px-3 py-2.5 rounded-lg text-gray-700 hover:bg-gray-100">
                            <svg class="w-5 h-5 fill-current text-gray-700" viewBox="0 0 24 24"><path d="M19.14 12.94c.04-.3.06-.61.06-.94 0-.32-.02-.64-.07-.94l2.03-1.58a.49.49 0 00.12-.61l-1.92-3.32a.488.488 0 00-.59-.22l-2.39.96c-.5-.38-1.03-.7-1.62-.94l-.36-2.54a.484.484 0 00-.48-.41h-3.84c-.24 0-.43.17-.47.41l-.36 2.54c-.59.24-1.13.57-1.62.94l-2.39-.96c-.22-.08-.47 0-.59.22L2.74 8.87c-.12.21-.08.47.12.61l2.03 1.58c-.05.3-.09.63-.09.94s.02.64.07.94l-2.03 1.58c-.19.15-.23.41-.12.61l1.92 3.32c.12.22.37.29.59.22l2.39-.96c.5.38 1.03.7 1.62.94l.36 2.54c.05.24.24.41.48.41h3.84c.24 0 .44-.17.47-.41l.36-2.54c.59-.24 1.13-.56 1.62-.94l2.39.96c.22.08.47 0 .59-.22l1.92-3.32c.12-.22.07-.47-.12-.61l-2.01-1.58zM12 15.6A3.6 3.6 0 1115.6 12 3.6 3.6 0 0112 15.6z"/></svg>
                            <span>Pengaturan</span>
                        </a>
                    </nav>
                    <div class="p-4 border-t border-gray-200 text-sm text-gray-600">
                        <span>ⓘ Tentang</span>
                    </div>
                </aside>
            @endif

            <!-- KONTEN UTAMA -->
            <main class="flex-grow p-6">
                @yield('content')
            </main>
        </div>
    </div>

    <script>
        function updateClock() {
            const now = new Date();
            const dateEl = document.getElementById('live-date');
            const timeEl = document.getElementById('live-time');
            if(dateEl) dateEl.innerText = now.toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' });
            if(timeEl) timeEl.innerText = now.toTimeString().split(' ')[0];
        }
        setInterval(updateClock, 1000);
        updateClock();
    </script>
    @stack('scripts')
</body>
</html>