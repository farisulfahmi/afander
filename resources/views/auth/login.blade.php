<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Afander</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center p-4 font-sans text-gray-800">
    <div class="w-full max-w-md bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden">
        <div class="bg-[#14171F] text-white px-6 py-6 flex items-center space-x-3">
            <div class="bg-gray-800 p-2.5 rounded-lg">
                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10l9-6 9 6M6 10h12l1 10H5L6 10z"/>
                </svg>
            </div>
            <div>
                <h1 class="font-bold text-lg leading-tight">Fashion Store</h1>
                <p class="text-xs text-gray-400">Point of Sales (POS) — Toko Pakaian Afander</p>
            </div>
        </div>

        <form action="{{ route('login.post') }}" method="POST" class="p-6 space-y-5">
            @csrf
            <div>
                <h2 class="text-lg font-bold text-gray-900">Masuk ke Sistem</h2>
                <p class="text-xs text-gray-500 mt-0.5">Silakan masukkan username dan kata sandi Anda</p>
            </div>

            @if($errors->has('login'))
                <div class="bg-red-50 border border-red-300 text-red-700 px-4 py-3 rounded-lg text-sm font-medium">
                    {{ $errors->first('login') }}
                </div>
            @endif

            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">Username</label>
                    <input type="text" name="username" value="{{ old('username') }}" placeholder="pemilik / kasir" required autofocus
                        class="w-full border border-gray-300 rounded-lg px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-gray-900">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">Kata Sandi</label>
                    <input type="password" name="password" placeholder="••••••••" required
                        class="w-full border border-gray-300 rounded-lg px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-gray-900">
                </div>
            </div>

            <button type="submit" class="w-full bg-[#14171F] hover:bg-black text-white font-semibold py-2.5 rounded-lg text-sm transition shadow">
                Masuk
            </button>

        </form>
    </div>
</body>
</html>