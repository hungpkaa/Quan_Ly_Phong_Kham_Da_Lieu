@extends('layouts.app')

@section('title', 'Trang Chủ - Bệnh viện Đại học Phenikaa')

<style>
/* ========== GLOBAL UTILITIES ========== */
.relative { position: relative; }
.w-full { width: 100%; }
.flex { display: flex; }
a { text-decoration: none !important; }

.bg-light-white-blue {
    background-image: linear-gradient(to top, #fff, #cfeaff);
}

/* ========== HERO BANNER ========== */
.hero-slide {
    position: relative;
    height: 520px;
    background-size: cover;
    background-position: center;
    display: flex;
    align-items: center;
}
.hero-slide::before {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(90deg, rgba(3,66,142,0.65) 0%, rgba(0,174,239,0.25) 60%, transparent 100%);
    z-index: 1;
}
.hero-content {
    position: relative;
    z-index: 2;
    max-width: 600px;
    padding: 40px;
    color: #fff;
}
.hero-content h2 {
    font-family: 'Poppins', sans-serif;
    font-size: 32px;
    font-weight: 700;
    line-height: 1.3;
    margin-bottom: 12px;
    text-shadow: 0 2px 8px rgba(0,0,0,0.3);
}
.hero-content p {
    font-size: 16px;
    line-height: 1.7;
    margin-bottom: 24px;
    opacity: 0.95;
}
.hero-cta {
    display: inline-flex;
    align-items: center;
    padding: 12px 28px;
    border-radius: 32px;
    font-family: 'Poppins', sans-serif;
    font-weight: 600;
    font-size: 15px;
    text-decoration: none;
    transition: all 0.3s ease;
    margin-right: 12px;
}
.hero-cta-primary {
    background-color: #F26522;
    color: #fff;
    box-shadow: 0 4px 16px rgba(242,101,34,0.4);
}
.hero-cta-primary:hover {
    background-color: #d3571c;
    color: #fff;
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(242,101,34,0.5);
}
.hero-cta-secondary {
    background: rgba(255,255,255,0.2);
    color: #fff;
    border: 2px solid rgba(255,255,255,0.6);
    backdrop-filter: blur(4px);
}
.hero-cta-secondary:hover {
    background: rgba(255,255,255,0.35);
    color: #fff;
    transform: translateY(-2px);
}

/* ========== SECTION TITLES ========== */
.section-title {
    font-family: 'Poppins', sans-serif;
    font-size: 26px;
    font-weight: 700;
    text-transform: uppercase;
    color: #03428E;
    text-align: center;
    margin-bottom: 8px;
}
.section-underline {
    display: block;
    width: 80px;
    height: 4px;
    background: linear-gradient(90deg, #00AEEF, #03428E);
    border-radius: 2px;
    margin: 0 auto 16px;
}
.section-subtitle {
    font-size: 15px;
    color: #555;
    text-align: center;
    max-width: 800px;
    margin: 0 auto 36px;
    line-height: 1.7;
}

/* ========== ABOUT SECTION ========== */
.about-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 32px;
    align-items: center;
}
.about-text {
    font-size: 15px;
    color: #333;
    line-height: 1.9;
    max-height: 320px;
    overflow-y: auto;
    padding-right: 12px;
    scrollbar-width: thin;
    scrollbar-color: #4bade9 transparent;
}
.about-img {
    width: 100%;
    height: 320px;
    object-fit: cover;
    border-radius: 16px;
    box-shadow: 0 8px 24px rgba(0,0,0,0.1);
}

/* ========== VISION/MISSION CARDS ========== */
.vision-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 30px;
    max-width: 1200px;
    margin: 0 auto;
}
.vision-card {
    background: #fff;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 4px 16px rgba(0,0,0,0.08);
    transition: transform 0.35s ease, box-shadow 0.35s ease;
    cursor: pointer;
}
.vision-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 12px 32px rgba(0,0,0,0.14);
}
.vision-card img {
    width: 100%;
    height: 240px;
    object-fit: cover;
}
.vision-card h3 {
    font-family: 'Poppins', sans-serif;
    font-size: 16px;
    font-weight: 600;
    color: #03428E;
    text-align: center;
    padding: 14px 12px;
    margin: 0;
    background: #fff;
    transition: all 0.25s ease;
}
.vision-card:hover h3 {
    background-color: #03428E;
    color: #fff;
}

/* ========== FACILITY & EQUIPMENT CARDS ========== */
.facility-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 30px;
    max-width: 1200px;
    margin: 0 auto;
}
.facility-card {
    background: #fff;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 4px 16px rgba(0,0,0,0.08);
    transition: transform 0.35s ease, box-shadow 0.35s ease;
}
.facility-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 12px 32px rgba(0,0,0,0.14);
}
.facility-card img {
    width: 100%;
    height: 250px;
    object-fit: cover;
}
.facility-card-body {
    padding: 16px;
}
.facility-card-body h3 {
    font-size: 16px;
    font-weight: 600;
    color: #03428E;
    margin-bottom: 8px;
}
.facility-card-body p {
    font-size: 13px;
    color: #666;
    line-height: 1.6;
    margin: 0;
}

/* ========== SERVICES GRID ========== */
.services-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 30px;
    max-width: 1200px;
    margin: 0 auto;
}
.service-card {
    background: #fff;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 4px 16px rgba(0,0,0,0.08);
    transition: transform 0.35s ease, box-shadow 0.35s ease;
    cursor: pointer;
}
.service-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 12px 32px rgba(3,66,142,0.15);
}
.service-card img {
    width: 100%;
    height: 220px;
    object-fit: cover;
}
.service-card h3 {
    font-size: 16px;
    font-weight: 600;
    color: #03428E;
    text-align: center;
    padding: 14px 12px;
    margin: 0;
}

/* ========== ACHIEVEMENT CARDS ========== */
.achievement-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 30px;
    max-width: 1200px;
    margin: 0 auto;
}
.achievement-card {
    background: #fff;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 4px 16px rgba(0,0,0,0.08);
    transition: transform 0.35s ease, box-shadow 0.35s ease;
    cursor: pointer;
}
.achievement-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 12px 32px rgba(0,0,0,0.14);
}
.achievement-card img {
    width: 100%;
    height: 200px;
    object-fit: cover;
}
.achievement-card-body {
    padding: 16px;
}
.achievement-card-body h3 {
    font-size: 15px;
    font-weight: 600;
    color: #03428E;
    margin-bottom: 8px;
    line-height: 1.4;
}
.achievement-card-body p {
    font-size: 13px;
    color: #666;
    line-height: 1.6;
}

/* ========== CTA BUTTONS ========== */
.btn-cta-orange {
    display: inline-flex;
    align-items: center;
    padding: 12px 32px;
    background: linear-gradient(135deg, #F26522, #FF8A50);
    color: #fff;
    border: none;
    border-radius: 32px;
    font-family: 'Poppins', sans-serif;
    font-size: 15px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 4px 16px rgba(242,101,34,0.3);
}
.btn-cta-orange:hover {
    background: linear-gradient(135deg, #d3571c, #F26522);
    color: #fff;
    transform: translateY(-2px);
    box-shadow: 0 6px 24px rgba(242,101,34,0.45);
}

/* ========== CONTACT / SUPPORT SECTION ========== */
.contact-section {
    background: linear-gradient(135deg, #03428E 0%, #0A6BC4 50%, #00AEEF 100%);
    padding: 60px 0;
    position: relative;
    overflow: hidden;
}
.contact-section::before {
    content: '';
    position: absolute;
    top: -100px;
    right: -100px;
    width: 400px;
    height: 400px;
    border-radius: 50%;
    background: rgba(255,255,255,0.05);
}
.contact-section::after {
    content: '';
    position: absolute;
    bottom: -80px;
    left: -80px;
    width: 300px;
    height: 300px;
    border-radius: 50%;
    background: rgba(255,255,255,0.03);
}
.contact-info-col h2 {
    font-family: 'Poppins', sans-serif;
    font-size: 28px;
    font-weight: 700;
    color: #fff;
    margin-bottom: 16px;
}
.contact-info-col p {
    color: rgba(255,255,255,0.85);
    font-size: 15px;
    line-height: 1.8;
    margin-bottom: 24px;
}
.contact-info-item {
    display: flex;
    align-items: center;
    gap: 12px;
    color: #fff;
    margin-bottom: 16px;
    font-size: 15px;
}
.contact-info-item i {
    font-size: 20px;
    color: #00AEEF;
    width: 40px;
    height: 40px;
    background: rgba(255,255,255,0.12);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
.contact-form-glass {
    background: rgba(255,255,255,0.12);
    backdrop-filter: blur(16px);
    -webkit-backdrop-filter: blur(16px);
    border: 1px solid rgba(255,255,255,0.2);
    border-radius: 20px;
    padding: 32px;
    position: relative;
    z-index: 2;
}
.contact-form-glass h4 {
    font-family: 'Poppins', sans-serif;
    font-size: 20px;
    font-weight: 600;
    color: #fff;
    text-align: center;
    margin-bottom: 24px;
}
.contact-form-glass .form-control {
    background: rgba(255,255,255,0.15);
    border: 1px solid rgba(255,255,255,0.25);
    color: #fff;
    border-radius: 12px;
    padding: 12px 16px;
    font-size: 14px;
    transition: all 0.2s ease;
}
.contact-form-glass .form-control::placeholder {
    color: rgba(255,255,255,0.5);
}
.contact-form-glass .form-control:focus {
    background: rgba(255,255,255,0.22);
    border-color: rgba(255,255,255,0.5);
    box-shadow: 0 0 0 3px rgba(0,174,239,0.25);
    color: #fff;
}
.contact-form-glass .form-label {
    color: rgba(255,255,255,0.85);
    font-size: 13px;
    font-weight: 500;
}
.contact-form-glass .btn-submit {
    width: 100%;
    padding: 14px;
    background: linear-gradient(135deg, #F26522, #FF8A50);
    color: #fff;
    border: none;
    border-radius: 12px;
    font-family: 'Poppins', sans-serif;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 4px 16px rgba(242,101,34,0.3);
}
.contact-form-glass .btn-submit:hover {
    background: linear-gradient(135deg, #d3571c, #F26522);
    transform: translateY(-2px);
    box-shadow: 0 6px 24px rgba(242,101,34,0.45);
}

/* ========== COOPERATION SECTION ========== */
.cooperation-section {
    text-align: center;
    padding: 60px 0;
    background: #f8fcff;
}
.cooperation-section img {
    width: 100%;
    max-width: 900px;
    border-radius: 16px;
    box-shadow: 0 8px 32px rgba(0,0,0,0.1);
}

/* ========== SOCIAL BUTTONS ========== */
.social-links {
    display: flex;
    gap: 16px;
}
.social-btn {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    display: flex;
    justify-content: center;
    align-items: center;
    font-size: 20px;
    color: #fff;
    text-decoration: none;
    transition: all 0.3s ease;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}
.social-btn.facebook { background: #1877F2; }
.social-btn.youtube { background: #FF0000; }
.social-btn.tiktok { background: #000000; border: 1px solid rgba(255,255,255,0.2); }

.social-btn:hover {
    transform: translateY(-4px);
    color: #fff;
    box-shadow: 0 8px 24px rgba(0,0,0,0.2);
}
.social-btn.facebook:hover { box-shadow: 0 8px 24px rgba(24,119,242,0.4); }
.social-btn.youtube:hover { box-shadow: 0 8px 24px rgba(255,0,0,0.4); }
.social-btn.tiktok:hover { box-shadow: 0 8px 24px rgba(0,0,0,0.4); }

/* ========== RESPONSIVE ========== */
@media (max-width: 991px) {
    .hero-slide { height: 400px; }
    .hero-content h2 { font-size: 24px; }
    .about-grid { grid-template-columns: 1fr; }
    .vision-grid, .facility-grid, .achievement-grid { grid-template-columns: repeat(2, 1fr); }
}
@media (max-width: 576px) {
    .hero-slide { height: 350px; }
    .hero-content { padding: 24px; }
    .hero-content h2 { font-size: 20px; }
    .hero-cta { padding: 10px 20px; font-size: 13px; }
    .vision-grid, .facility-grid, .achievement-grid { grid-template-columns: 1fr; }
    .section-title { font-size: 20px; }
}
</style>

@section('content')

<!-- ========== HERO CAROUSEL ========== -->
<div id="heroCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="5000">
    <div class="carousel-inner">
        <div class="carousel-item active">
            <div class="hero-slide" style="background-image: url('/img/bg1.png');">
                <div class="container">
                    <div class="hero-content" data-aos="fade-right" data-aos-delay="200">
                        <h2>Chăm sóc sức khỏe toàn diện với tiêu chuẩn Quốc tế</h2>
                        <p>Đội ngũ chuyên gia hàng đầu, trang thiết bị hiện đại bậc nhất, dịch vụ tận tâm vì sức khỏe cộng đồng.</p>
                        <a href="/appointments/create" class="hero-cta hero-cta-primary">
                            <i class="bi bi-calendar-check me-2"></i> Đặt lịch khám ngay
                        </a>
                        <a href="javascript:void(0)" onclick="if(typeof toggleChat === 'function') toggleChat(); else alert('Vui lòng đợi chatbot tải xong');" class="hero-cta hero-cta-secondary">
                            <i class="bi bi-chat-dots me-2"></i> Nhận tư vấn
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="carousel-item">
            <div class="hero-slide" style="background-image: url('/img/bg2.webp');">
                <div class="container">
                    <div class="hero-content">
                        <h2>Đội ngũ Bác sĩ Chuyên gia giàu kinh nghiệm</h2>
                        <p>Quy tụ các Giáo sư, Tiến sĩ, Bác sĩ đầu ngành từ các bệnh viện lớn trong và ngoài nước.</p>
                        <a href="/doctors" class="hero-cta hero-cta-primary">
                            <i class="bi bi-person-badge me-2"></i> Xem đội ngũ Bác sĩ
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="carousel-item">
            <div class="hero-slide" style="background-image: url('/img/bg3.webp');">
                <div class="container">
                    <div class="hero-content">
                        <h2>Trang thiết bị Y tế Hiện đại bậc nhất</h2>
                        <p>Hệ thống máy móc tiên tiến nhập khẩu từ Mỹ, Đức, Nhật Bản đảm bảo chẩn đoán chính xác.</p>
                        <a href="/services" class="hero-cta hero-cta-primary">
                            <i class="bi bi-heart-pulse me-2"></i> Khám phá Dịch vụ
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
    </button>
</div>

<!-- ========== MAIN CONTENT ========== -->
<div class="bg-light-white-blue">
    <div class="container" style="max-width: 1200px; padding: 40px 16px;">

        <!-- Tiêu đề chính -->
        <div data-aos="fade-up">
            <h1 class="section-title" style="font-size: 28px;">PhenikaaMec - Hệ thống y tế hàn lâm đẳng cấp quốc tế</h1>
            <span class="section-underline" style="width: 320px;"></span>
        </div>

        <!-- ===== VỀ CHÚNG TÔI ===== -->
        <div style="margin-top: 48px;">
            <h2 class="section-title" data-aos="fade-up">Về chúng tôi</h2>
            <span class="section-underline" data-aos="fade-up" data-aos-delay="100"></span>
            <div class="about-grid" data-aos="fade-up" data-aos-delay="200">
                <div class="about-text">
                    <p>PhenikaaMec là hệ thống y tế hàn lâm đẳng cấp quốc tế thuộc Hệ sinh thái Chăm sóc sức khỏe Tập đoàn Phenikaa. Thành lập năm 2010, PHENIKAA hiện là tập đoàn kinh tế đa ngành với hơn 30 đơn vị thành viên hoạt động trong và ngoài nước (Mỹ, Canada…), trên các lĩnh vực cốt lõi: Công nghiệp, Công nghệ, Giáo dục đào tạo, Chăm sóc sức khỏe và các dịch vụ khác.</p>
                    <p>Với định hướng phát triển bền vững và nền tảng vững chắc về khoa học công nghệ, hệ thống chuyên nghiệp, con người sẵn sàng thích ứng - đổi mới, hoạt động theo mô hình Hệ sinh thái "3 Nhà": Nhà Sản xuất Kinh doanh – Nhà Khoa học – Nhà Giáo dục.</p>
                    <p>Sở hữu thế mạnh về nền tảng đào tạo nguồn nhân lực Y - Dược và Nghiên cứu khoa học, Tập đoàn Phenikaa phát triển hệ sinh thái khép kín trong lĩnh vực Chăm sóc sức khỏe với 4 mảng: Giáo dục và Đào tạo – Chăm sóc sức khỏe - Sản xuất kinh doanh – Công nghệ và chuyển giao.</p>
                    <p>PHENIKAAMEC nuôi dưỡng khát vọng cống hiến, vun đắp niềm tin, nỗ lực vươn tầm nhằm mang đến các giá trị cho xã hội và nâng cao sức khỏe cộng đồng.</p>
                </div>
                <img src="./img/img36.webp" alt="PhenikaaMec" class="about-img">
            </div>
        </div>

        <!-- ===== TẦM NHÌN - SỨ MỆNH ===== -->
        <div style="margin-top: 56px;">
            <h2 class="section-title" data-aos="fade-up">Tầm nhìn - Sứ mệnh - Giá trị cốt lõi</h2>
            <span class="section-underline" data-aos="fade-up" data-aos-delay="100"></span>
            <div class="vision-grid">
                <div class="vision-card" data-aos="fade-up" data-aos-delay="100">
                    <img src="/img/img2.webp" alt="Tầm nhìn">
                    <h3>Tầm nhìn</h3>
                </div>
                <div class="vision-card" data-aos="fade-up" data-aos-delay="200">
                    <img src="/img/img3.webp" alt="Sứ mệnh">
                    <h3>Sứ mệnh</h3>
                </div>
                <div class="vision-card" data-aos="fade-up" data-aos-delay="300">
                    <img src="/img/img4.webp" alt="Giá trị cốt lõi">
                    <h3>Giá trị cốt lõi</h3>
                </div>
            </div>
        </div>

        <!-- ===== CƠ SỞ VẬT CHẤT ===== -->
        <div style="margin-top: 56px;">
            <h2 class="section-title" data-aos="fade-up">Cơ sở vật chất hiện đại</h2>
            <span class="section-underline" data-aos="fade-up" data-aos-delay="100"></span>
            <p class="section-subtitle" data-aos="fade-up" data-aos-delay="150">
                Với sự đầu tư mạnh mẽ, PHENIKAAMEC sử dụng các trang thiết bị y tế hiện đại bậc nhất trên thế giới cùng cơ sở vật chất tiêu chuẩn 5 sao.
            </p>
            <div class="facility-grid">
                <div class="facility-card" data-aos="fade-up" data-aos-delay="100">
                    <img src="/img/img20.webp" alt="Khu vực lễ tân">
                    <div class="facility-card-body">
                        <h3>Khu vực lễ tân</h3>
                        <p>Khu vực tiếp đón hiện đại, tiện nghi, mang đến trải nghiệm tốt nhất cho khách hàng.</p>
                    </div>
                </div>
                <div class="facility-card" data-aos="fade-up" data-aos-delay="200">
                    <img src="/img/img21.webp" alt="Phòng làm việc">
                    <div class="facility-card-body">
                        <h3>Phòng làm việc</h3>
                        <p>Được trang bị các thiết bị y tế hiện đại nhất, đảm bảo tiêu chuẩn quốc tế.</p>
                    </div>
                </div>
                <div class="facility-card" data-aos="fade-up" data-aos-delay="300">
                    <img src="/img/img22.webp" alt="Phòng điều trị">
                    <div class="facility-card-body">
                        <h3>Phòng điều trị</h3>
                        <p>Không gian rộng rãi, thoải mái, mang lại cảm giác yên tâm cho bệnh nhân.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- ===== DANH SÁCH DỊCH VỤ ===== -->
        <div style="margin-top: 56px;">
            <h2 class="section-title" data-aos="fade-up">Danh Sách Dịch Vụ</h2>
            <span class="section-underline" data-aos="fade-up" data-aos-delay="100"></span>
            <p class="section-subtitle" data-aos="fade-up" data-aos-delay="150">
                PHENIKAAMEC cung cấp các giải pháp y tế tiên tiến với chất lượng cao, đội ngũ bác sĩ chuyên nghiệp, trang thiết bị hiện đại, đảm bảo trải nghiệm điều trị tốt nhất cho người bệnh.
            </p>
            <div class="services-grid">
                @if(isset($services) && $services->count() > 0)
                    @foreach($services as $index => $service)
                    <div class="service-card" data-aos="fade-up" data-aos-delay="{{ ($index % 3) * 100 + 100 }}">
                        <img src="{{ asset($service->image) }}" alt="{{ $service->name }}" onerror="this.onerror=null; this.src='{{ asset('img/default.jpg') }}';">
                        <h3>{{ $service->name }}</h3>
                    </div>
                    @endforeach
                @else
                    <p class="text-center" style="grid-column: 1/-1; color: #888;">Hiện chưa có dịch vụ nào.</p>
                @endif
            </div>
            <div class="text-center" style="margin-top: 32px;" data-aos="fade-up">
                <a href="/services" class="btn-cta-orange">Xem thêm &gt;&gt;</a>
            </div>
        </div>

        <!-- ===== TRANG THIẾT BỊ ===== -->
        <div style="margin-top: 56px;">
            <h2 class="section-title" data-aos="fade-up">Trang thiết bị tân tiến</h2>
            <span class="section-underline" data-aos="fade-up" data-aos-delay="100"></span>
            <div class="vision-grid" style="max-width: 1200px;">
                <div class="vision-card" data-aos="fade-up" data-aos-delay="100">
                    <img src="/img/img5.webp" alt="Y học hạt nhân">
                    <h3>Y học hạt nhân</h3>
                </div>
                <div class="vision-card" data-aos="fade-up" data-aos-delay="200">
                    <img src="/img/img6.webp" alt="Máy xạ trị">
                    <h3>Máy xạ trị</h3>
                </div>
                <div class="vision-card" data-aos="fade-up" data-aos-delay="300">
                    <img src="/img/img7.webp" alt="Hệ thống can thiệp mạch">
                    <h3>Hệ thống can thiệp mạch</h3>
                </div>
                <div class="vision-card" data-aos="fade-up" data-aos-delay="100">
                    <img src="/img/img8.webp" alt="Máy CT">
                    <h3>Máy CT</h3>
                </div>
                <div class="vision-card" data-aos="fade-up" data-aos-delay="200">
                    <img src="/img/img9.webp" alt="Máy MRI">
                    <h3>Máy MRI</h3>
                </div>
                <div class="vision-card" data-aos="fade-up" data-aos-delay="300">
                    <img src="/img/img10.webp" alt="Máy X-Quang">
                    <h3>Máy X-Quang</h3>
                </div>
                <div class="vision-card" data-aos="fade-up" data-aos-delay="100">
                    <img src="/img/img11.webp" alt="Máy đo đa năng">
                    <h3>Máy đo đa năng</h3>
                </div>
                <div class="vision-card" data-aos="fade-up" data-aos-delay="200">
                    <img src="/img/img12.webp" alt="Bộ đo mạch và huyết áp">
                    <h3>Bộ đo mạch và huyết áp</h3>
                </div>
                <div class="vision-card" data-aos="fade-up" data-aos-delay="300">
                    <img src="/img/img13.jpeg" alt="Máy chẩn đoán hiện đại">
                    <h3>Máy chẩn đoán hiện đại</h3>
                </div>
            </div>
        </div>

        <!-- ===== THÀNH TỰU ===== -->
        <div style="margin-top: 56px;">
            <h2 class="section-title" data-aos="fade-up">Thành tựu PhenikaaMec</h2>
            <span class="section-underline" data-aos="fade-up" data-aos-delay="100"></span>
            <div class="achievement-grid">
                <div class="achievement-card" data-aos="fade-up" data-aos-delay="100">
                    <img src="/img/img14.webp" alt="HDF Online">
                    <div class="achievement-card-body">
                        <h3>PHENIKAAMEC sẵn sàng đưa vào hoạt động kỹ thuật lọc thận HDF online</h3>
                        <p>Khoa Thận nhân tạo – Bệnh viện Đại học Phenikaa ngay từ đầu thành lập đã được chú trọng đầu tư...</p>
                    </div>
                </div>
                <div class="achievement-card" data-aos="fade-up" data-aos-delay="200">
                    <img src="/img/img15.webp" alt="DSA Technology">
                    <div class="achievement-card-body">
                        <h3>PhenikaaMec làm chủ kỹ thuật chụp DSA – "thủ thuật Vàng" trong chẩn đoán hình ảnh</h3>
                        <p>Chụp mạch số hóa xóa nền (DSA) chính là "cánh tay đắc lực" hỗ trợ các chuyên gia...</p>
                    </div>
                </div>
                <div class="achievement-card" data-aos="fade-up" data-aos-delay="300">
                    <img src="/img/img16.webp" alt="I131 Treatment">
                    <div class="achievement-card-body">
                        <h3>Sắp thêm một địa chỉ điều trị ung thư tuyến giáp bằng I131 tại Hà Nội</h3>
                        <p>Xạ trị I131 là dược chất phóng xạ, dùng trong điều trị bệnh nhân ung thư tuyến giáp...</p>
                    </div>
                </div>
            </div>
            <div class="text-center" style="margin-top: 32px;" data-aos="fade-up">
                <a href="#" class="btn-cta-orange">Xem thêm &gt;&gt;</a>
            </div>
        </div>

        <!-- ===== VỀ HOẠT ĐỘNG ===== -->
        <div style="margin-top: 56px;">
            <h2 class="section-title" data-aos="fade-up">Về hoạt động của PhenikaaMec</h2>
            <span class="section-underline" data-aos="fade-up" data-aos-delay="100"></span>
            <div class="vision-grid" style="max-width: 1200px;">
                <div class="vision-card" data-aos="fade-up" data-aos-delay="100">
                    <img src="/img/img32.webp" alt="Nghiên cứu khoa học">
                    <h3>Nghiên cứu khoa học</h3>
                </div>
                <div class="vision-card" data-aos="fade-up" data-aos-delay="200">
                    <img src="/img/img33.webp" alt="Đào tạo">
                    <h3>Đào tạo</h3>
                </div>
                <div class="vision-card" data-aos="fade-up" data-aos-delay="300">
                    <img src="/img/img34.webp" alt="Hội thảo và hợp tác">
                    <h3>Hội thảo và hợp tác</h3>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- ===== HỢP TÁC BÁC SĨ ===== -->
<div class="cooperation-section">
    <div class="container" style="max-width: 1200px;">
        <h2 class="section-title" data-aos="fade-up">Hợp tác Bác sĩ</h2>
        <span class="section-underline" data-aos="fade-up" data-aos-delay="100"></span>
        <p class="section-subtitle" data-aos="fade-up" data-aos-delay="150">
            Bệnh viện Đại học Phenikaa là một trong số ít bệnh viện tại Việt Nam triển khai chương trình hợp tác với các chuyên gia, bác sĩ đến từ nhiều bệnh viện lớn cả trong nước và quốc tế.
        </p>
        <div data-aos="zoom-in" data-aos-delay="200">
            <img src="/img/img35.webp" alt="Hợp tác bác sĩ">
        </div>
    </div>
</div>

<!-- ===== LIÊN HỆ & HỖ TRỢ (GLASSMORPHISM) ===== -->
<div class="contact-section">
    <div class="container" style="max-width: 1200px;">
        <div class="row align-items-center">
            <div class="col-lg-5 contact-info-col" data-aos="fade-right">
                <h2>Liên hệ với chúng tôi</h2>
                <p>Bạn có câu hỏi hoặc cần hỗ trợ? Hãy để lại tin nhắn, đội ngũ chuyên gia của chúng tôi sẽ phản hồi trong thời gian sớm nhất.</p>
                <div class="contact-info-item">
                    <i class="bi bi-geo-alt-fill"></i>
                    <span>Tầng 1, 2, 3 - Số 167 Hoàng Ngân, Hà Nội</span>
                </div>
                <div class="contact-info-item">
                    <i class="bi bi-telephone-fill"></i>
                    <span>02422226699</span>
                </div>
                <div class="contact-info-item">
                    <i class="bi bi-envelope-fill"></i>
                    <span>support@phenikaamec.com</span>
                </div>
                <div class="contact-info-item">
                    <i class="bi bi-clock-fill"></i>
                    <span>Thứ 2 - Thứ 7: 7h30 - 17h00</span>
                </div>
                
            </div>
            <div class="col-lg-7" data-aos="fade-left">
                <div class="contact-form-glass">
                    <h4><i class="bi bi-send me-2"></i>Gửi Yêu Cầu Hỗ Trợ</h4>

                    @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show text-center" role="alert" style="background: rgba(40,167,69,0.2); border: 1px solid rgba(40,167,69,0.4); color: #fff;">
                        {{ session('success') }}
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif

                    <form action="{{ route('support.store_home') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Họ và tên</label>
                                <input type="text" name="name" id="name" class="form-control" placeholder="Nguyễn Văn A" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="age" class="form-label">Tuổi</label>
                                <input type="number" name="age" id="age" class="form-control" placeholder="25" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label">Số điện thoại</label>
                                <input type="text" name="phone" id="phone" class="form-control" placeholder="0912 345 678" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" name="email" id="email" class="form-control" placeholder="email@example.com" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="message" class="form-label">Nội dung cần hỗ trợ</label>
                            <textarea name="message" id="message" rows="4" class="form-control" placeholder="Mô tả triệu chứng hoặc câu hỏi của bạn..." required></textarea>
                        </div>
                        <button type="submit" class="btn-submit">
                            <i class="bi bi-send me-2"></i>Gửi yêu cầu
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>



@endsection