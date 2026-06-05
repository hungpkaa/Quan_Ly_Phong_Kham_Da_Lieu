@extends('layouts.app')

@section('title', 'Đăng ký')

@section('content')

<style>
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

/* ========== CENTERED REGISTER LAYOUT ========== */
.register-page {
    min-height: calc(100vh - 150px);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 40px 16px;
    position: relative;
    font-family: 'Poppins', sans-serif;
}

/* Full-screen background */
.register-bg {
    position: absolute;
    inset: 0;
    background: url('/img/bg3.webp') center/cover no-repeat;
    z-index: 0;
}

.register-bg::after {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(
        135deg,
        rgba(0, 116, 217, 0.78) 0%,
        rgba(3, 66, 142, 0.82) 50%,
        rgba(0, 80, 160, 0.75) 100%
    );
    backdrop-filter: blur(4px);
}

/* Centered card */
.register-card {
    position: relative;
    z-index: 1;
    width: 100%;
    max-width: 620px;
    background: #fff;
    border-radius: 24px;
    box-shadow:
        0 20px 60px rgba(0,0,0,0.15),
        0 0 0 1px rgba(255,255,255,0.1);
    padding: 40px 44px 36px;
    animation: regCardFadeIn 0.5s ease;
}

@keyframes regCardFadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

/* Logo */
.register-logo {
    text-align: center;
    margin-bottom: 24px;
}

.register-logo img {
    height: 44px;
}

/* Title */
.register-card h1 {
    text-align: center;
    font-size: 26px;
    font-weight: 700;
    color: #03428E;
    margin-bottom: 4px;
}

.register-card .subtitle {
    text-align: center;
    font-size: 14px;
    color: #889;
    margin-bottom: 28px;
}

/* Fields */
.register-field {
    margin-bottom: 18px;
}

.register-field label {
    display: block;
    font-size: 13px;
    font-weight: 500;
    color: #445;
    margin-bottom: 6px;
}

.register-input-wrap {
    position: relative;
}

.register-input-wrap > i {
    position: absolute;
    left: 15px;
    top: 50%;
    transform: translateY(-50%);
    color: #00AEEF;
    font-size: 16px;
    pointer-events: none;
}

.register-input-wrap input {
    width: 100%;
    border: 2px solid #e4ecf7;
    border-radius: 12px;
    padding: 11px 44px 11px 44px;
    font-family: 'Poppins', sans-serif;
    font-size: 14px;
    color: #333;
    background: #fafcff;
    transition: all 0.25s ease;
    outline: none;
}

.register-input-wrap input:focus {
    border-color: #00AEEF;
    box-shadow: 0 0 0 4px rgba(0,174,239,0.1);
    background: #fff;
}

.register-input-wrap input::placeholder {
    color: #b0b8c8;
}

/* Toggle password */
.reg-toggle-pass {
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

.reg-toggle-pass:hover {
    color: #03428E;
}

/* Two-col grid */
.register-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 0 20px;
}

/* Alert */
.register-alert {
    background: #fef2f2;
    border: 1px solid #fecaca;
    border-radius: 12px;
    padding: 12px 16px;
    margin-bottom: 18px;
    font-size: 13px;
    color: #b91c1c;
}

.register-alert ul {
    margin: 0;
    padding-left: 16px;
}

/* Submit */
.register-btn {
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

.register-btn:hover {
    background: linear-gradient(135deg, #023570, #005fba);
    transform: translateY(-2px);
    box-shadow: 0 8px 26px rgba(3,66,142,0.4);
}

.register-btn:active {
    transform: translateY(0);
}

/* Divider */
.register-divider {
    display: flex;
    align-items: center;
    gap: 12px;
    margin: 22px 0;
    color: #bbc;
    font-size: 12px;
}

.register-divider::before,
.register-divider::after {
    content: '';
    flex: 1;
    height: 1px;
    background: #e4ecf7;
}

/* Login link */
.register-login-link {
    text-align: center;
    font-size: 13px;
    color: #778;
}

.register-login-link a {
    color: #03428E;
    font-weight: 600;
    text-decoration: none;
    transition: color 0.2s;
}

.register-login-link a:hover {
    color: #0074D9;
    text-decoration: underline;
}

/* Trust */
.register-trust {
    text-align: center;
    margin-top: 18px;
    font-size: 12px;
    color: #99a;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
}

.register-trust i {
    color: #00AEEF;
}

/* ========== RESPONSIVE ========== */
@media (max-width: 700px) {
    .register-card {
        padding: 32px 24px 28px;
        border-radius: 20px;
        max-width: 440px;
    }

    .register-grid {
        grid-template-columns: 1fr;
    }

    .register-card h1 {
        font-size: 22px;
    }
}
</style>

<div class="register-page">
    {{-- Background --}}
    <div class="register-bg"></div>

    {{-- Centered Card --}}
    <div class="register-card">

        {{-- Logo --}}
        <div class="register-logo">
            <img src="/img/logo.webp" alt="Logo Phenikaa">
        </div>

        <h1>Tạo tài khoản</h1>
        <p class="subtitle">Đăng ký miễn phí chỉ trong 30 giây</p>

        {{-- Errors --}}
        @if ($errors->any())
            <div class="register-alert">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('register.submit') }}">
            @csrf

            {{-- Name (full width) --}}
            <div class="register-field">
                <label for="name">Họ và tên</label>
                <div class="register-input-wrap">
                    <i class="bi bi-person"></i>
                    <input
                        type="text"
                        id="name"
                        name="name"
                        placeholder="Nhập họ và tên đầy đủ"
                        value="{{ old('name') }}"
                        required
                        autocomplete="name"
                    >
                </div>
            </div>

            {{-- Email & Phone: 2 columns --}}
            <div class="register-grid">
                <div class="register-field">
                    <label for="email">Địa chỉ Email</label>
                    <div class="register-input-wrap">
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

                <div class="register-field">
                    <label for="phone">Số điện thoại</label>
                    <div class="register-input-wrap">
                        <i class="bi bi-telephone"></i>
                        <input
                            type="text"
                            id="phone"
                            name="phone"
                            placeholder="09xx xxx xxx"
                            value="{{ old('phone') }}"
                            required
                            autocomplete="tel"
                        >
                    </div>
                </div>
            </div>

            {{-- Password & Confirm: 2 columns --}}
            <div class="register-grid">
                <div class="register-field">
                    <label for="password">Mật khẩu</label>
                    <div class="register-input-wrap">
                        <i class="bi bi-lock"></i>
                        <input
                            type="password"
                            id="password"
                            name="password"
                            placeholder="Tối thiểu 6 ký tự"
                            required
                            autocomplete="new-password"
                        >
                        <button type="button" class="reg-toggle-pass" onclick="toggleRegPass('password','toggleIcon1')" title="Hiện/Ẩn mật khẩu">
                            <i class="bi bi-eye" id="toggleIcon1"></i>
                        </button>
                    </div>
                </div>

                <div class="register-field">
                    <label for="password_confirmation">Xác nhận mật khẩu</label>
                    <div class="register-input-wrap">
                        <i class="bi bi-lock-fill"></i>
                        <input
                            type="password"
                            id="password_confirmation"
                            name="password_confirmation"
                            placeholder="Nhập lại mật khẩu"
                            required
                            autocomplete="new-password"
                        >
                        <button type="button" class="reg-toggle-pass" onclick="toggleRegPass('password_confirmation','toggleIcon2')" title="Hiện/Ẩn mật khẩu">
                            <i class="bi bi-eye" id="toggleIcon2"></i>
                        </button>
                    </div>
                </div>
            </div>

            {{-- Submit --}}
            <button type="submit" class="register-btn">
                <i class="bi bi-person-plus"></i>
                Đăng ký tài khoản
            </button>
        </form>

        <div class="register-divider">hoặc</div>

        {{-- Login Link --}}
        <div class="register-login-link">
            Đã có tài khoản?
            <a href="{{ route('login') }}">Đăng nhập ngay →</a>
        </div>

        {{-- Trust --}}
        <div class="register-trust">
            <i class="bi bi-shield-lock-fill"></i>
            Thông tin được bảo mật theo chuẩn quốc tế
        </div>

    </div>
</div>

<script>
function toggleRegPass(inputId, iconId) {
    const input = document.getElementById(inputId);
    const icon = document.getElementById(iconId);
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
