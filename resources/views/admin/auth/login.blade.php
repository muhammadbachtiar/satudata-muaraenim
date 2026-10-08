<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — Admin Satu Data</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>

<body class="bg-slate-50 min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-sm">
        <div class="text-center mb-8">
            <div
                class="w-14 h-14 bg-blue-600 rounded-2xl flex items-center justify-center text-white font-bold text-xl mx-auto mb-4">
                SD</div>
            <h1 class="text-xl font-bold text-gray-900">Admin Satu Data</h1>
            <p class="text-sm text-gray-500 mt-1">Masuk ke panel administrasi</p>
        </div>

        <form action="{{ route('admin.login.submit') }}" method="POST"
            class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 space-y-5">
            @csrf

            @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-600 text-sm px-4 py-3 rounded-lg">
                {{ $errors->first() }}
            </div>
            @endif

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1.5">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus
                    class="w-full px-4 py-2.5 rounded-lg border border-gray-300 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition">
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1.5">Password</label>
                <input type="password" id="password" name="password" required
                    class="w-full px-4 py-2.5 rounded-lg border border-gray-300 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition">
            </div>

            <div class="flex items-center">
                <input type="checkbox" id="remember" name="remember"
                    class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                <label for="remember" class="ml-2 text-sm text-gray-600">Ingat saya</label>
            </div>

            <button type="submit"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 rounded-lg text-sm transition shadow-sm">
                Masuk
            </button>
        </form>

        <p class="text-center text-xs text-gray-400 mt-6">&copy; {{ date('Y') }} Satu Data Kabupaten Muara Enim</p>
    </div>
</body>

</html>