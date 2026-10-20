<!doctype html>
<html lang="id"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">@vite(['resources/css/app.css','resources/js/app.js'])<title>Masuk | RuangKita</title></head>
<body class="grid min-h-screen place-items-center bg-slate-100 p-5"><form method="post" class="w-full max-w-md rounded-2xl bg-white p-6 shadow-sm sm:p-8">@csrf
    <h1 class="text-2xl font-bold">Ruang<span class="text-blue-600">Kita</span></h1><p class="mb-6 text-sm text-slate-500">Masuk untuk mengelola meeting Anda.</p>
    @error('email')<p class="mb-4 text-sm text-red-600">{{ $message }}</p>@enderror
    <div class="auth-field-group"><label for="email" class="auth-label">Email</label><input id="email" class="field" name="email" value="{{ old('email') }}" type="email" autocomplete="email" required autofocus></div>
    <x-auth.password-field name="password" label="Kata sandi" />
    <label class="mt-4 flex gap-2 text-sm"><input type="checkbox" name="remember"> Ingat saya</label>
    <button class="btn-primary mt-6 w-full">Masuk</button><p class="mt-5 text-center text-sm">Belum punya akun? <a class="text-blue-600" href="{{ route('register') }}">Daftar</a></p>
</form></body></html>
