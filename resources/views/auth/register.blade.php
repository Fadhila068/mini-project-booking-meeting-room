<!doctype html>
<html lang="id"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">@vite(['resources/css/app.css','resources/js/app.js'])<title>Daftar | RuangKita</title></head>
<body class="grid min-h-screen place-items-center bg-slate-100 p-5"><form method="post" class="w-full max-w-md rounded-2xl bg-white p-6 shadow-sm sm:p-8">@csrf
    <h1 class="text-2xl font-bold">Buat akun</h1>
    <div class="mt-6 auth-field-group"><label for="name" class="auth-label">Nama</label><input id="name" class="field" name="name" value="{{ old('name') }}" autocomplete="name" required></div>
    <div class="auth-field-group"><label for="email" class="auth-label">Email</label><input id="email" class="field" name="email" value="{{ old('email') }}" type="email" autocomplete="email" required></div>
    <div class="auth-field-group"><label for="department" class="auth-label">Departemen</label><input id="department" class="field" name="department" value="{{ old('department') }}" autocomplete="organization"></div>
    <x-auth.password-field name="password" label="Kata sandi" />
    <x-auth.password-field name="password_confirmation" label="Konfirmasi kata sandi" />
    <button class="btn-primary mt-6 w-full">Daftar</button><p class="mt-5 text-center text-sm"><a class="text-blue-600" href="{{ route('login') }}">Kembali masuk</a></p>
</form></body></html>
