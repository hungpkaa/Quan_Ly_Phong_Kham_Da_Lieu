@extends('layouts.app')

@section('title', 'Đăng ký')

@section('content')

<style>
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

/* ========== FULL-PAGE SPLIT LAYOUT ========== */
.register-wrapper {
    display: flex;
    min-height: calc(100vh - 150px);
    font-family: 'Poppins', sans-serif;
}

/* ---- LEFT PANEL: Hospital Image ---- */
.register-image-panel {
    flex: 1.1;
    position: relative;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    justify-content: flex-end;
}

.register-image-panel img {
    position: absolute;
    inset: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: center;
}

.register-image-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(
        to bottom,
        rgba(0, 116, 217, 0.2) 0%,
        rgba(3, 66, 142, 0.8) 100%
    );
}

.register-image-content {
    position: relative;
    z-index: 2;
    padding: 40px 48px;
    color: #fff;
}

.register-image-content .badge-tag {
    display: inline-block;
    background: rgba(255,255,255,0.2);
    border: 1px solid rgba(255,255,255,0.35);
    backdrop-filter: blur(8px);
    color: #fff;
    font-size: 12px;
    font-weight: 500;
    letter-spacing: 1px;
    text-transform: uppercase;
    padding: 6px 14px;
    border-radius: 30px;
    margin-bottom: 16px;
}

.register-image-content h2 {
    font-size: 28px;
    font-weight: 700;
    line-height: 1.35;
    margin-bottom: 12px;
    text-shadow: 0 2px 8px rgba(0,0,0,0.3);
}

.register-image-content p {
    font-size: 14px;
    opacity: 0.85;
    line-height: 1.6;
    max-width: 400px;
}

.register-benefits {
    margin-top: 28px;
    padding-top: 24px;
    border-top: 1px solid rgba(255,255,255,0.2);
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.register-benefit {
    display: flex;
    align-items: center;
    gap: 12px;
    font-size: 14px;
    color: rgba(255,255,255,0.9);
}

.register-benefit i {
    font-size: 18px;
    color: #7dd3fc;
    flex-shrink: 0;
}

/* ---- RIGHT PANEL: Form ---- */
.register-form-panel {
    flex: 0 0 460px;
    background: #f0f6ff;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 40px 40px;
}

.register-card {
    width: 100%;
    max-width: 400px;
}

.register-logo {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 24px;
}

.register-logo img {
    height: 38px;
}

.register-card h1 {
    font-size: 26px;
    font-weight: 700;
    color: #03428E;
    margin-bottom: 6px;
}

.register-card .subtitle {
    font-size: 14px;
    color: #778;
    margin-bottom: 28px;
}

/* Form fields */
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
    left: 14px;
    top: 50%;
    transform: translateY(-50%);
    color: #00AEEF;
    font-size: 16px;
    pointer-events: none;
}

.register-input-wrap input {
    width: 100%;
    border: 2px solid #dde8f5;
    border-radius: 12px;
    padding: 11px 14px 11px 42px;
    font-family: 'Poppins', sans-serif;
    font-size: 14px;
    color: #333;
    background: #fff;
    transition: all 0.25s ease;
    outline: none;
}

.register-input-wrap input:focus {
    border-color: #00AEEF;
    box-shadow: 0 0 0 4px rgba(0,174,239,0.12);
}

.register-input-wrap input::placeholder {
    color: #bbc;
}

/* Toggle password visibility */
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
    gap: 0 16px;
}

/* Alert errors */
.register-alert {
    background: #fff0f0;
    border: 1px solid #fcc;
    border-radius: 12px;
    padding: 12px 16px;
    margin-bottom: 18px;
    font-size: 13px;
    color: #c0392b;
}

.register-alert ul {
    margin: 0;
    padding-left: 16px;
}

/* Submit button */
.register-btn {
    width: 100%;
    padding: 13px;
    border: none;
    border-radius: 12px;
    font-family: 'Poppins', sans-serif;
    font-size: 15px;
    font-weight: 600;
    color: #fff;
    background: linear-gradient(135deg, #03428E, #0074D9);
    cursor: pointer;
    box-shadow: 0 4px 16px rgba(3,66,142,0.28);
    transition: all 0.3s ease;
    margin-top: 6px;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
}

.register-btn:hover {
    background: linear-gradient(135deg, #023570, #005fba);
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(3,66,142,0.38);
}

.register-btn:active {
    transform: translateY(0);
}

/* Login link */
.register-login-link {
    text-align: center;
    margin-top: 22px;
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

/* Divider */
.register-divider {
    display: flex;
    align-items: center;
    gap: 12px;
    margin: 20px 0;
    color: #bbc;
    font-size: 12px;
}

.register-divider::before,
.register-divider::after {
    content: '';
    flex: 1;
    height: 1px;
    background: #dde8f5;
}

/* Trust badges */
.register-trust {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    margin-top: 16px;
    font-size: 12px;
    color: #99a;
}

.register-trust i {
    color: #00AEEF;
    font-size: 14px;
}

/* ========== RESPONSIVE ========== */
@media (max-width: 900px) {
    .register-wrapper {
        flex-direction: column;
        min-height: auto;
    }

    .register-image-panel {
        min-height: 200px;
        flex: none;
    }

    .register-form-panel {
        flex: none;
        padding: 32px 24px;
    }

    .register-grid {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 480px) {
    .register-form-panel {
        padding: 24px 16px;
    }

    .register-card h1 {
        font-size: 22px;
    }
}
</style>

<div class="register-wrapper">

    {{-- ===== LEFT: Image Panel ===== --}}
    <div class="register-image-panel">
        <img src="/img/bg3.webp" alt="Cơ sở vật chất Bệnh viện Đại học Phenikaa">
        <div class="register-image-overlay"></div>
        <div class="register-image-content">
            <span class="badge-tag">✨ Gia nhập cộng đồng</span>
            <h2>Chăm sóc sức khỏe<br>của bạn từ hôm nay</h2>
            <p>Tạo tài khoản để đặt lịch khám nhanh chóng, theo dõi lịch sử khám bệnh và nhận tư vấn từ đội ngũ chuyên gia.</p>
            <div class="register-benefits">
                <div class="register-benefit">
                    <i class="bi bi-calendar2-check"></i>
                    Đặt lịch khám trực tuyến 24/7
                </div>
                <div class="register-benefit">
                    <i class="bi bi-clipboard2-pulse"></i>
                    Theo dõi hồ sơ sức khỏe cá nhân
                </div>
                <div class="register-benefit">
                    <i class="bi bi-shield-check"></i>
                    Bảo mật thông tin tuyệt đối
                </div>
                <div class="register-benefit">
                    <i class="bi bi-headset"></i>
                    Hỗ trợ tư vấn miễn phí
                </div>
            </div>
        </div>
    </div>

    {{-- ===== RIGHT: Form Panel ===== --}}
    <div class="register-form-panel">
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

                {{-- Name --}}
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

            {{-- Trust badge --}}
            <div class="register-trust">
                <i class="bi bi-shield-lock-fill"></i>
                Thông tin được bảo mật theo chuẩn quốc tế
            </div>

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
