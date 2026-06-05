@extends('layouts.app')

@section('title', 'Hỗ trợ bệnh nhân')

@section('content')

<style>
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

/* ========== SUPPORT PAGE ========== */
.support-page {
    min-height: calc(100vh - 150px);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 48px 16px;
    position: relative;
    font-family: 'Poppins', sans-serif;
    background: linear-gradient(135deg, #e8f4fd 0%, #f0f6ff 50%, #eef2ff 100%);
}

/* Main card */
.support-card {
    width: 100%;
    max-width: 900px;
    background: #fff;
    border-radius: 24px;
    box-shadow:
        0 20px 60px rgba(3,66,142,0.08),
        0 4px 20px rgba(0,0,0,0.04);
    display: flex;
    overflow: hidden;
    animation: supportFadeIn 0.5s ease;
}

@keyframes supportFadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

/* ---- LEFT: Contact Info ---- */
.support-info {
    flex: 0 0 320px;
    background: linear-gradient(160deg, #03428E 0%, #0074D9 60%, #00AEEF 100%);
    color: #fff;
    padding: 44px 32px;
    display: flex;
    flex-direction: column;
    position: relative;
    overflow: hidden;
}

.support-info::before {
    content: '';
    position: absolute;
    top: -60px;
    right: -60px;
    width: 180px;
    height: 180px;
    border-radius: 50%;
    background: rgba(255,255,255,0.06);
}

.support-info::after {
    content: '';
    position: absolute;
    bottom: -40px;
    left: -40px;
    width: 140px;
    height: 140px;
    border-radius: 50%;
    background: rgba(255,255,255,0.04);
}

.support-info h2 {
    font-size: 22px;
    font-weight: 700;
    margin-bottom: 8px;
    position: relative;
    z-index: 1;
}

.support-info .info-subtitle {
    font-size: 13px;
    opacity: 0.8;
    line-height: 1.6;
    margin-bottom: 32px;
    position: relative;
    z-index: 1;
}

.support-info-item {
    display: flex;
    align-items: flex-start;
    gap: 14px;
    margin-bottom: 22px;
    position: relative;
    z-index: 1;
}

.support-info-item .info-icon {
    width: 40px;
    height: 40px;
    border-radius: 12px;
    background: rgba(255,255,255,0.15);
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    font-size: 18px;
}

.support-info-item .info-text h4 {
    font-size: 13px;
    font-weight: 600;
    margin-bottom: 2px;
    opacity: 0.95;
}

.support-info-item .info-text p {
    font-size: 13px;
    opacity: 0.75;
    margin: 0;
    line-height: 1.5;
}

.support-hours {
    margin-top: auto;
    padding-top: 24px;
    border-top: 1px solid rgba(255,255,255,0.15);
    position: relative;
    z-index: 1;
}

.support-hours h4 {
    font-size: 13px;
    font-weight: 600;
    margin-bottom: 8px;
    display: flex;
    align-items: center;
    gap: 8px;
}

.support-hours p {
    font-size: 12px;
    opacity: 0.7;
    margin: 0;
    line-height: 1.6;
}

/* ---- RIGHT: Form ---- */
.support-form-panel {
    flex: 1;
    padding: 40px 40px;
}

.support-form-panel h1 {
    font-size: 24px;
    font-weight: 700;
    color: #03428E;
    margin-bottom: 4px;
}

.support-form-panel .form-subtitle {
    font-size: 13px;
    color: #889;
    margin-bottom: 28px;
}

/* Fields */
.support-field {
    margin-bottom: 18px;
}

.support-field label {
    display: block;
    font-size: 13px;
    font-weight: 500;
    color: #445;
    margin-bottom: 6px;
}

.support-input-wrap {
    position: relative;
}

.support-input-wrap > i {
    position: absolute;
    left: 15px;
    top: 14px;
    color: #00AEEF;
    font-size: 16px;
    pointer-events: none;
}

.support-input-wrap input,
.support-input-wrap textarea {
    width: 100%;
    border: 2px solid #e4ecf7;
    border-radius: 12px;
    padding: 11px 14px 11px 44px;
    font-family: 'Poppins', sans-serif;
    font-size: 14px;
    color: #333;
    background: #fafcff;
    transition: all 0.25s ease;
    outline: none;
    resize: vertical;
}

.support-input-wrap textarea {
    min-height: 100px;
}

.support-input-wrap input:focus,
.support-input-wrap textarea:focus {
    border-color: #00AEEF;
    box-shadow: 0 0 0 4px rgba(0,174,239,0.1);
    background: #fff;
}

.support-input-wrap input::placeholder,
.support-input-wrap textarea::placeholder {
    color: #b0b8c8;
}

/* Grid 2 cols */
.support-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 0 18px;
}

/* Submit */
.support-btn {
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
    margin-top: 6px;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
}

.support-btn:hover {
    background: linear-gradient(135deg, #023570, #005fba);
    transform: translateY(-2px);
    box-shadow: 0 8px 26px rgba(3,66,142,0.4);
}

.support-btn:active {
    transform: translateY(0);
}

/* Success alert */
.support-success {
    display: flex;
    align-items: center;
    gap: 12px;
    background: #ecfdf5;
    border: 1px solid #a7f3d0;
    border-radius: 12px;
    padding: 14px 18px;
    margin-bottom: 22px;
    font-size: 14px;
    color: #065f46;
}

.support-success i {
    font-size: 22px;
    color: #10b981;
    flex-shrink: 0;
}

/* ========== RESPONSIVE ========== */
@media (max-width: 768px) {
    .support-card {
        flex-direction: column;
    }

    .support-info {
        flex: none;
        padding: 32px 24px;
    }

    .support-form-panel {
        padding: 28px 24px;
    }

    .support-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<div class="support-page">
    <div class="support-card">

        {{-- ===== LEFT: Contact Info ===== --}}
        <div class="support-info">
            <h2>Thông tin liên hệ</h2>
            <p class="info-subtitle">
                Hãy liên hệ với chúng tôi qua bất kỳ kênh nào bên dưới, hoặc điền form bên cạnh để gửi yêu cầu hỗ trợ.
            </p>

            <div class="support-info-item">
                <div class="info-icon">
                    <i class="bi bi-telephone-fill"></i>
                </div>
                <div class="info-text">
                    <h4>Hotline</h4>
                    <p>1900.88.66.48</p>
                </div>
            </div>

            <div class="support-info-item">
                <div class="info-icon">
                    <i class="bi bi-envelope-fill"></i>
                </div>
                <div class="info-text">
                    <h4>Email</h4>
                    <p>support@phenikaamec.vn</p>
                </div>
            </div>

            <div class="support-info-item">
                <div class="info-icon">
                    <i class="bi bi-geo-alt-fill"></i>
                </div>
                <div class="info-text">
                    <h4>Địa chỉ</h4>
                    <p>Tổ 5 Hòe Thị, Phương Xuân Phương,<br>Nam Từ Liêm, TP Hà Nội</p>
                </div>
            </div>

            <div class="support-info-item">
                <div class="info-icon">
                    <i class="bi bi-phone-fill"></i>
                </div>
                <div class="info-text">
                    <h4>Cấp Cứu</h4>
                    <p>0869974466</p>
                </div>
            </div>

            <div class="support-hours">
                <h4><i class="bi bi-clock"></i> Giờ làm việc</h4>
                <p>Từ 7h30 đến 16h30<br>Tất cả các ngày trong tuần</p>
            </div>
        </div>

        {{-- ===== RIGHT: Form ===== --}}
        <div class="support-form-panel">

            <h1>Gửi yêu cầu hỗ trợ</h1>
            <p class="form-subtitle">Vui lòng điền thông tin, chúng tôi sẽ phản hồi sớm nhất</p>

            {{-- Success --}}
            @if(session('success'))
                <div class="support-success">
                    <i class="bi bi-check-circle-fill"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <form action="{{ route('support.store') }}" method="POST">
                @csrf

                {{-- Name + Age: 2 cols --}}
                <div class="support-grid">
                    <div class="support-field">
                        <label for="name">Họ và tên</label>
                        <div class="support-input-wrap">
                            <i class="bi bi-person"></i>
                            <input
                                type="text"
                                id="name"
                                name="name"
                                placeholder="Nhập họ và tên"
                                value="{{ old('name') }}"
                                required
                            >
                        </div>
                    </div>

                    <div class="support-field">
                        <label for="age">Tuổi</label>
                        <div class="support-input-wrap">
                            <i class="bi bi-calendar3"></i>
                            <input
                                type="number"
                                id="age"
                                name="age"
                                placeholder="VD: 30"
                                value="{{ old('age') }}"
                                required
                            >
                        </div>
                    </div>
                </div>

                {{-- Phone + Email: 2 cols --}}
                <div class="support-grid">
                    <div class="support-field">
                        <label for="phone">Số điện thoại</label>
                        <div class="support-input-wrap">
                            <i class="bi bi-telephone"></i>
                            <input
                                type="text"
                                id="phone"
                                name="phone"
                                placeholder="09xx xxx xxx"
                                value="{{ old('phone') }}"
                                required
                            >
                        </div>
                    </div>

                    <div class="support-field">
                        <label for="email">Email</label>
                        <div class="support-input-wrap">
                            <i class="bi bi-envelope"></i>
                            <input
                                type="email"
                                id="email"
                                name="email"
                                placeholder="example@email.com"
                                value="{{ old('email') }}"
                                required
                            >
                        </div>
                    </div>
                </div>

                {{-- Message: full width --}}
                <div class="support-field">
                    <label for="message">Nội dung cần hỗ trợ</label>
                    <div class="support-input-wrap">
                        <i class="bi bi-chat-left-text"></i>
                        <textarea
                            id="message"
                            name="message"
                            rows="4"
                            placeholder="Mô tả chi tiết vấn đề bạn cần hỗ trợ..."
                            required
                        >{{ old('message') }}</textarea>
                    </div>
                </div>

                {{-- Submit --}}
                <button type="submit" class="support-btn">
                    <i class="bi bi-send"></i>
                    Gửi yêu cầu hỗ trợ
                </button>
            </form>
        </div>

    </div>
</div>

@endsection