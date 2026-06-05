@extends('layouts.app')

@section('title', 'Đăng nhập')

@section('content')

<style>
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

/* ========== CENTERED LOGIN LAYOUT ========== */
.login-page {
    min-height: calc(100vh - 150px);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 40px 16px;
    position: relative;
    font-family: 'Poppins', sans-serif;
}

/* Full-screen background */
.login-bg {
    position: absolute;
    inset: 0;
    background: url('/img/bg2.webp') center/cover no-repeat;
    z-index: 0;
}

.login-bg::after {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(
        135deg,
        rgba(3, 66, 142, 0.82) 0%,
        rgba(0, 116, 217, 0.72) 50%,
        rgba(0, 174, 239, 0.65) 100%
    );
    backdrop-filter: blur(4px);
}

/* Centered card */
.login-card {
    position: relative;
    z-index: 1;
    width: 100%;
    max-width: 420px;
    background: #fff;
    border-radius: 24px;
    box-shadow:
        0 20px 60px rgba(0,0,0,0.15),
        0 0 0 1px rgba(255,255,255,0.1);
    padding: 44px 40px 36px;
    animation: cardFadeIn 0.5s ease;
}

@keyframes cardFadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

/* Logo */
.login-logo {
    text-align: center;
    margin-bottom: 28px;
}

.login-logo img {
    height: 44px;
}

/* Title */
.login-card h1 {
    text-align: center;
    font-size: 26px;
    font-weight: 700;
    color: #03428E;
    margin-bottom: 4px;
}

.login-card .subtitle {
    text-align: center;
    font-size: 14px;
    color: #889;
    margin-bottom: 30px;
}

/* Fields */
.login-field {
    margin-bottom: 20px;
}

.login-field label {
    display: block;
    font-size: 13px;
    font-weight: 500;
    color: #445;
    margin-bottom: 7px;
}

.login-input-wrap {
    position: relative;
}

.login-input-wrap > i {
    position: absolute;
    left: 15px;
    top: 50%;
    transform: translateY(-50%);
    color: #00AEEF;
    font-size: 16px;
    pointer-events: none;
}

.login-input-wrap input {
    width: 100%;
    border: 2px solid #e4ecf7;
    border-radius: 12px;
    padding: 12px 44px 12px 44px;
    font-family: 'Poppins', sans-serif;
    font-size: 14px;
    color: #333;
    background: #fafcff;
    transition: all 0.25s ease;
    outline: none;
}

.login-input-wrap input:focus {
    border-color: #00AEEF;
    box-shadow: 0 0 0 4px rgba(0,174,239,0.1);
    background: #fff;
}

.login-input-wrap input::placeholder {
    color: #b0b8c8;
}

/* Toggle password */
.toggle-pass {
    position: absolute;
    right: 14px;
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    cursor: pointer;
    color: #99a;
    font-size: 16px;
    padding: 0;
    transition: color 0.2s;
}

.toggle-pass:hover {
    color: #03428E;
}

/* Alert */
.login-alert {
    background: #fef2f2;
    border: 1px solid #fecaca;
    border-radius: 12px;
    padding: 12px 16px;
    margin-bottom: 20px;
    font-size: 13px;
    color: #b91c1c;
}

.login-alert ul {
    margin: 0;
    padding-left: 16px;
}

/* Submit */
.login-btn {
    width: 100%;
    padding: 14px;
    border: none;
    border-radius: 12px;
    font-family: 'Poppins', sans-serif;
    font-size: 15px;
    font-weight: 600;
    color: #fff;
    background: linear-gradient(135deg, #03428E, #0074D9);
    cursor: pointer;
    box-shadow: 0 4px 18px rgba(3,66,142,0.3);
    transition: all 0.3s ease;
    margin-top: 4px;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
}

.login-btn:hover {
    background: linear-gradient(135deg, #023570, #005fba);
    transform: translateY(-2px);
    box-shadow: 0 8px 26px rgba(3,66,142,0.4);
}

.login-btn:active {
    transform: translateY(0);
}

/* Divider */
.login-divider {
    display: flex;
    align-items: center;
    gap: 12px;
    margin: 24px 0;
    color: #bbc;
    font-size: 12px;
}

.login-divider::before,
.login-divider::after {
    content: '';
    flex: 1;
    height: 1px;
    background: #e4ecf7;
}

/* Register link */
.login-register-link {
    text-align: center;
    font-size: 13px;
    color: #778;
}

.login-register-link a {
    color: #03428E;
    font-weight: 600;
    text-decoration: none;
    transition: color 0.2s;
}

.login-register-link a:hover {
    color: #0074D9;
    text-decoration: underline;
}

/* Trust */
.login-trust {
    text-align: center;
    margin-top: 20px;
    font-size: 12px;
    color: #99a;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
}

.login-trust i {
    color: #00AEEF;
}

/* ========== RESPONSIVE ========== */
@media (max-width: 480px) {
    .login-card {
        padding: 32px 24px 28px;
        border-radius: 20px;
    }

    .login-card h1 {
        font-size: 22px;
    }
}
</style>

<div class="login-page">
    {{-- Background --}}
    <div class="login-bg"></div>

    {{-- Centered Card --}}
    <div class="login-card">

        {{-- Logo --}}
        <div class="login-logo">
            <img src="/img/logo.webp" alt="Logo Phenikaa">
        </div>

        <h1>Đăng nhập</h1>
        <p class="subtitle">Vui lòng nhập thông tin tài khoản của bạn</p>

        {{-- Errors --}}
        @if ($errors->any())
            <div class="login-alert">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            {{-- Email --}}
            <div class="login-field">
                <label for="email">Địa chỉ Email</label>
                <div class="login-input-wrap">
                    <i class="bi bi-envelope"></i>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        placeholder="example@email.com"
                        value="{{ old('email') }}"
                        required
                        autocomplete="email"
                    >
                </div>
            </div>

            {{-- Password --}}
            <div class="login-field">
                <label for="password">Mật khẩu</label>
                <div class="login-input-wrap">
                    <i class="bi bi-lock"></i>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="Nhập mật khẩu..."
                        required
                        autocomplete="current-password"
                    >
                    <button type="button" class="toggle-pass" onclick="togglePassword()" title="Hiện/Ẩn mật khẩu">
                        <i class="bi bi-eye" id="toggleIcon"></i>
                    </button>
                </div>
            </div>

            {{-- Submit --}}
            <button type="submit" class="login-btn">
                <i class="bi bi-box-arrow-in-right"></i>
                Đăng nhập
            </button>
        </form>

        <div class="login-divider">hoặc</div>

        {{-- Register Link --}}
        <div class="login-register-link">
            Chưa có tài khoản?
            <a href="{{ route('register') }}">Đăng ký ngay →</a>
        </div>

        {{-- Trust --}}
        <div class="login-trust">
            <i class="bi bi-shield-lock-fill"></i>
            Thông tin được bảo mật tuyệt đối
        </div>

    </div>
</div>

<script>
function togglePassword() {
    const input = document.getElementById('password');
    const icon = document.getElementById('toggleIcon');
    if (input.type === 'password') {
        input.type = 'text';
        icon.className = 'bi bi-eye-slash';
    } else {
        input.type = 'password';
        icon.className = 'bi bi-eye';
    }
}
</script>

@endsection