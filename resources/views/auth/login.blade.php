@extends('layouts.app')

@section('title', 'Đăng nhập')

@section('content')

<style>
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

/* ========== FULL-PAGE SPLIT LAYOUT ========== */
.login-wrapper {
    display: flex;
    min-height: calc(100vh - 150px);
    font-family: 'Poppins', sans-serif;
}

/* ---- LEFT PANEL: Hospital Image ---- */
.login-image-panel {
    flex: 1.1;
    position: relative;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    justify-content: flex-end;
}

.login-image-panel img {
    position: absolute;
    inset: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: center;
}

.login-image-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(
        to bottom,
        rgba(3, 66, 142, 0.25) 0%,
        rgba(3, 66, 142, 0.75) 100%
    );
}

.login-image-content {
    position: relative;
    z-index: 2;
    padding: 40px 48px;
    color: #fff;
}

.login-image-content .badge-tag {
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

.login-image-content h2 {
    font-size: 30px;
    font-weight: 700;
    line-height: 1.35;
    margin-bottom: 12px;
    text-shadow: 0 2px 8px rgba(0,0,0,0.3);
}

.login-image-content p {
    font-size: 14px;
    opacity: 0.85;
    line-height: 1.6;
    max-width: 380px;
}

.login-image-stats {
    display: flex;
    gap: 28px;
    margin-top: 28px;
    padding-top: 24px;
    border-top: 1px solid rgba(255,255,255,0.2);
}

.login-image-stats .stat {
    text-align: center;
}

.login-image-stats .stat-num {
    font-size: 22px;
    font-weight: 700;
    color: #fff;
}

.login-image-stats .stat-label {
    font-size: 11px;
    opacity: 0.75;
    margin-top: 2px;
}

/* ---- RIGHT PANEL: Form ---- */
.login-form-panel {
    flex: 0 0 430px;
    background: #f0f6ff;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 48px 40px;
}

.login-card {
    width: 100%;
    max-width: 380px;
}

.login-logo {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 32px;
}

.login-logo img {
    height: 38px;
}

.login-card h1 {
    font-size: 26px;
    font-weight: 700;
    color: #03428E;
    margin-bottom: 6px;
}

.login-card .subtitle {
    font-size: 14px;
    color: #778;
    margin-bottom: 32px;
}

/* Form fields */
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

.login-input-wrap i {
    position: absolute;
    left: 14px;
    top: 50%;
    transform: translateY(-50%);
    color: #00AEEF;
    font-size: 16px;
    pointer-events: none;
}

.login-input-wrap input {
    width: 100%;
    border: 2px solid #dde8f5;
    border-radius: 12px;
    padding: 12px 14px 12px 42px;
    font-family: 'Poppins', sans-serif;
    font-size: 14px;
    color: #333;
    background: #fff;
    transition: all 0.25s ease;
    outline: none;
}

.login-input-wrap input:focus {
    border-color: #00AEEF;
    box-shadow: 0 0 0 4px rgba(0,174,239,0.12);
}

.login-input-wrap input::placeholder {
    color: #bbc;
}

/* Toggle password visibility */
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

/* Alert errors */
.login-alert {
    background: #fff0f0;
    border: 1px solid #fcc;
    border-radius: 12px;
    padding: 14px 16px;
    margin-bottom: 20px;
    font-size: 13px;
    color: #c0392b;
}

.login-alert ul {
    margin: 0;
    padding-left: 16px;
}

/* Submit button */
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
    box-shadow: 0 4px 16px rgba(3,66,142,0.28);
    transition: all 0.3s ease;
    margin-top: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
}

.login-btn:hover {
    background: linear-gradient(135deg, #023570, #005fba);
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(3,66,142,0.38);
}

.login-btn:active {
    transform: translateY(0);
}

/* Register link */
.login-register-link {
    text-align: center;
    margin-top: 24px;
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
    background: #dde8f5;
}

/* Trust badges */
.login-trust {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    margin-top: 20px;
    font-size: 12px;
    color: #99a;
}

.login-trust i {
    color: #00AEEF;
    font-size: 14px;
}

/* ========== RESPONSIVE ========== */
@media (max-width: 900px) {
    .login-wrapper {
        flex-direction: column;
        min-height: auto;
    }

    .login-image-panel {
        min-height: 220px;
        flex: none;
    }

    .login-form-panel {
        flex: none;
        padding: 36px 24px;
    }
}

@media (max-width: 480px) {
    .login-form-panel {
        padding: 28px 16px;
    }

    .login-card h1 {
        font-size: 22px;
    }
}
</style>

<div class="login-wrapper">

    {{-- ===== LEFT: Image Panel ===== --}}
    <div class="login-image-panel">
        <img src="/img/bg2.webp" alt="Bệnh viện Đại học Phenikaa">
        <div class="login-image-overlay"></div>
        <div class="login-image-content">
            <span class="badge-tag">🏥 Chào mừng trở lại</span>
            <h2>Hệ thống Quản lý<br>Phòng Khám Da Liễu</h2>
            <p>Đặt lịch khám tiện lợi, theo dõi sức khỏe dễ dàng cùng đội ngũ bác sĩ chuyên nghiệp hàng đầu.</p>
            <div class="login-image-stats">
                <div class="stat">
                    <div class="stat-num">10+</div>
                    <div class="stat-label">Bác sĩ chuyên khoa</div>
                </div>
                <div class="stat">
                    <div class="stat-num">5,000+</div>
                    <div class="stat-label">Bệnh nhân tin tưởng</div>
                </div>
                <div class="stat">
                    <div class="stat-num">24/7</div>
                    <div class="stat-label">Hỗ trợ trực tuyến</div>
                </div>
            </div>
        </div>
    </div>

    {{-- ===== RIGHT: Form Panel ===== --}}
    <div class="login-form-panel">
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

            {{-- Trust badge --}}
            <div class="login-trust">
                <i class="bi bi-shield-lock-fill"></i>
                Thông tin được bảo mật tuyệt đối
            </div>

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