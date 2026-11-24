<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login Staf - {{ config('app.name', 'Laundry Kami') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/css/login.css', 'resources/js/staff.js'])
</head>
<body class="login-page min-h-screen bg-slate-950 font-sans text-white antialiased">
    <main class="login-shell grid w-full overflow-hidden">
        <section class="login-intro" aria-labelledby="intro-title">
            <a class="login-brand" href="{{ route('login') }}">
                <span class="brand-icon" aria-hidden="true">
                    <svg viewBox="0 0 32 32" fill="none">
                        <path d="M8 5h16l2 22H6L8 5Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
                        <path d="M10 11h12M12 17h8M14 22h4" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                </span>
                <span>Laundry<span class="brand-light">Kami</span></span>
            </a>

            <div class="intro-content">
                <span class="intro-label">SISTEM INFORMASI LAUNDRY</span>
                <h1 id="intro-title">Pakaian bersih,<br>urusan lebih mudah.</h1>
                <p>Kelola layanan, catat transaksi, dan pantau pekerjaan laundry dalam satu tempat.</p>

                <div class="intro-features">
                    <div class="feature-item">
                        <span class="feature-check" aria-hidden="true">✓</span>
                        <span>Data transaksi tercatat rapi</span>
                    </div>
                    <div class="feature-item">
                        <span class="feature-check" aria-hidden="true">✓</span>
                        <span>Status laundry mudah dipantau</span>
                    </div>
                </div>
            </div>

            <div class="laundry-machine" aria-hidden="true">
                <div class="machine-glow"></div>
                <div class="machine-top"></div>
                <div class="machine-body">
                    <div class="machine-screen"><span></span><span></span><span></span></div>
                    <div class="machine-door">
                        <div class="machine-door-glass"></div>
                        <div class="machine-clothes"></div>
                    </div>
                    <div class="machine-controls"><span></span><span></span><span></span></div>
                </div>
                <span class="machine-spark machine-spark-one">✦</span>
                <span class="machine-spark machine-spark-two">✦</span>
            </div>

            <div class="intro-footer">Ruang kerja khusus Admin dan Kasir</div>
        </section>

        <section class="login-panel" aria-labelledby="login-title">
            <div class="login-content">
                <div class="role-badge"><span aria-hidden="true"></span> AKSES STAF</div>
                <h2 id="login-title">Selamat datang kembali!</h2>
                <p class="login-subtitle">Masuk untuk melanjutkan ke dashboard laundry.</p>

                <div class="role-list" aria-label="Jenis akun">
                    <span class="role-chip">Admin</span>
                    <span class="role-chip">Kasir</span>
                </div>

                <x-auth-session-status class="session-status" :status="session('status')" />

                <form method="POST" action="{{ route('login') }}" class="login-form" data-loading-form>
                    @csrf

                    <div class="form-field">
                        <label for="email">Email</label>
                        <input id="email" name="email" type="email" value="{{ old('email') }}"
                               placeholder="nama@laundry.com" autocomplete="username" required autofocus
                               class="@error('email') input-error @enderror">
                        @error('email')
                            <span class="field-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-field">
                        <div class="field-heading">
                            <label for="password">Password</label>
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}">Lupa password?</a>
                            @endif
                        </div>
                        <div class="password-control">
                            <input id="password" name="password" type="password" placeholder="Masukkan password"
                                   autocomplete="current-password" required
                                   class="@error('password') input-error @enderror">
                            <button type="button" class="password-toggle" data-password-toggle
                                    aria-label="Tampilkan password" aria-pressed="false">
                                <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                    <path d="M2.5 12s3.3-6 9.5-6 9.5 6 9.5 6-3.3 6-9.5 6-9.5-6-9.5-6Z" stroke="currentColor" stroke-width="1.6"/>
                                    <circle cx="12" cy="12" r="2.6" stroke="currentColor" stroke-width="1.6"/>
                                </svg>
                            </button>
                        </div>
                        @error('password')
                            <span class="field-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <label class="remember-option" for="remember_me">
                        <input id="remember_me" type="checkbox" name="remember">
                        <span>Ingat saya</span>
                    </label>

                    <button class="login-button" type="submit" data-loading-button data-loading-label="Memproses...">
                        Masuk ke Dashboard
                        <span aria-hidden="true">&rarr;</span>
                    </button>
                </form>

                <div class="demo-note">
                    <span class="demo-note-title">Akun demo</span>
                    <p><strong>Admin:</strong> admin@laundry.test <span>/</span> password</p>
                    <p><strong>Kasir:</strong> kasir@laundry.test <span>/</span> password</p>
                </div>
            </div>

            <p class="copyright">&copy; {{ date('Y') }} Laundry Kami. Akses khusus staf.</p>
        </section>
    </main>
</body>
</html>
