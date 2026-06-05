<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title')</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;700&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

  <link rel="stylesheet" href="{{ asset('css/style.css') }}">
  <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
</head>

<body>
  <header>
    <!-- ========== DÒNG 1: SLOGAN BAR ========== -->
    <div class="pmec-slogan-bar">
      <div class="pmec-slogan-inner">
        <div class="pmec-slogan-layer pmec-slogan-orange"></div>
        <div class="pmec-slogan-layer pmec-slogan-lightblue"></div>
        <div class="pmec-slogan-layer pmec-slogan-darkblue">
          <p>Tận tâm - Sáng tạo - Nâng tầm tri thức</p>
        </div>
      </div>
    </div>

    <!-- ========== DÒNG 2: MAIN HEADER ========== -->
    <div class="pmec-main-header">
      <div class="container d-flex align-items-center justify-content-between flex-wrap gap-2 py-3">
        <!-- Logo -->
        <a href="{{ url('/') }}" class="pmec-logo">
          <img src="/img/logo.webp" alt="PHENIKAAMEC Logo" style="height: 48px;">
        </a>

        <!-- Ô tìm kiếm -->
        <div class="pmec-search-wrapper position-relative">
          <input type="text" id="searchInput" class="form-control pmec-search-input" placeholder="Tìm kiếm...">
          <a href="#" class="pmec-search-btn" onclick="return false;">
            <i class="bi bi-search"></i>
          </a>
          <div id="searchResults" class="position-absolute w-100 bg-white shadow rounded mt-2 d-none"
            style="max-height: 300px; overflow-y: auto; z-index: 1000;">
          </div>
        </div>

        <!-- Cụm nút chức năng -->
        <div class="d-flex align-items-center flex-wrap gap-2">
          <a href="/appointments/create" class="pmec-btn pmec-btn-orange">
            <i class="bi bi-calendar-check me-1"></i> Đặt lịch khám
          </a>
          @auth
            @if(Auth::user()->phone)
            <a href="tel:{{ Auth::user()->phone }}" class="pmec-btn pmec-btn-darkblue">
              <i class="bi bi-telephone-fill me-1"></i> {{ Auth::user()->phone }}
            </a>
            @else
            <a href="tel:0886314896" class="pmec-btn pmec-btn-darkblue">
              <i class="bi bi-telephone-fill me-1"></i> 0886314896
            </a>
            @endif
          @else
          <a href="tel:0886314896" class="pmec-btn pmec-btn-darkblue">
            <i class="bi bi-telephone-fill me-1"></i> 0886314896
          </a>
          @endauth
          <a href="/support" class="pmec-btn pmec-btn-lightblue">
            <i class="bi bi-info-circle me-1"></i> Hướng dẫn khách hàng
          </a>

          @guest
          <a href="{{ route('login') }}" class="pmec-btn pmec-btn-outline">Đăng nhập</a>
          <a href="{{ route('register') }}" class="pmec-btn pmec-btn-green">Đăng ký</a>
          @else
          @if(Auth::user()->role === 'patient')
          <a href="{{ route('patient.account') }}" class="pmec-btn pmec-btn-outline">
            <i class="bi bi-person-circle me-1"></i> Tài khoản
          </a>
          @endif
          <form method="POST" action="{{ route('logout') }}" class="d-inline-block m-0">
            @csrf
            <button type="submit" class="pmec-btn pmec-btn-danger">
              <i class="bi bi-box-arrow-right me-1"></i> Đăng xuất
            </button>
          </form>
          @endguest

          <!-- Ngôn ngữ -->
          <div class="dropdown">
            <button class="pmec-lang-btn dropdown-toggle" id="languageDropdown"
              data-bs-toggle="dropdown" aria-expanded="false">
              <img src="/img/vietnam.png" alt="VN" style="height: 20px;">
            </button>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="languageDropdown">
              <li><a class="dropdown-item" href="#">Vietnamese</a></li>
              <li><a class="dropdown-item" href="#">English</a></li>
            </ul>
          </div>
        </div>
      </div>
    </div>

    <!-- ========== DÒNG 3: NAVIGATION BAR ========== -->
    <nav class="pmec-navbar">
      <div class="container">
        <button class="navbar-toggler d-lg-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
          aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation"
          style="border: none; background: none; padding: 8px;">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
          <ul class="navbar-nav pmec-nav-list">
            <li class="nav-item"><a class="nav-link pmec-nav-link" href="{{ url('/') }}">Trang Chủ</a></li>
            <li class="nav-item"><a class="nav-link pmec-nav-link" href="{{ url('/about') }}">Thông Tin</a></li>
            <li class="nav-item"><a class="nav-link pmec-nav-link" href="{{ url('/services') }}">Dịch Vụ</a></li>
            <li class="nav-item"><a class="nav-link pmec-nav-link" href="{{ url('/contact') }}">Liên Lạc</a></li>
            <li class="nav-item"><a class="nav-link pmec-nav-link" href="{{ url('/doctors') }}">Bác Sĩ</a></li>
          </ul>
        </div>
      </div>
    </nav>
  </header>


  <style>
  /* Căn giữa thông báo thành công */
  .alert-success {
    text-align: center;
  }
  </style>


  @if(session('success'))
  <div class="alert alert-success">
    {{ session('success') }}
  </div>
  @endif
  <div class="content">
    @yield('content')
  </div>

  @include('partials.footer')




  <script>
  document.addEventListener("DOMContentLoaded", function() {
    let searchInput = document.getElementById("searchInput");
    let searchResults = document.getElementById("searchResults");

    searchInput.addEventListener("keyup", function() {
      let query = searchInput.value.trim();
      if (query.length < 1) {
        searchResults.classList.add("d-none");
        return;
      }

      fetch(`/search?query=${query}`)
        .then(response => response.json())
        .then(data => {
          searchResults.innerHTML = "";

          if (!data.doctors.length && !data.services.length) {
            searchResults.innerHTML = "<div class='p-2'>Không tìm thấy kết quả</div>";
            searchResults.classList.remove("d-none");
            return;
          }

          let html = "";

          // Hiển thị danh sách bác sĩ
          if (data.doctors.length > 0) {
            html += "<div class='p-2 fw-bold'>Bác sĩ</div>";
            data.doctors.forEach(doctor => {
              html += `
                            <div class="d-flex align-items-center p-2 border-bottom">
                                <img src="${doctor.image}" class="rounded-circle me-2" 
                                     style="width: 40px; height: 40px; object-fit: cover;">
                                <a href="/doctors/${doctor.id}" class="text-dark text-decoration-none">
                                    ${doctor.name}
                                </a>
                            </div>
                        `;
            });
          }

          // Hiển thị danh sách dịch vụ
          if (data.services.length > 0) {
            html += "<div class='p-2 fw-bold'>Dịch vụ</div>";
            data.services.forEach(service => {
              html += `
                            <div class="d-flex align-items-center p-2 border-bottom">
                                <img src="${service.image}" class="rounded-circle me-2" 
                                     style="width: 40px; height: 40px; object-fit: cover;">
                                <a href="/services/${service.id}" class="text-dark text-decoration-none">
                                    ${service.name}
                                </a>
                            </div>
                        `;
            });
          }

          searchResults.innerHTML = html;
          searchResults.classList.remove("d-none");
        })
        .catch(error => console.error("Lỗi tìm kiếm:", error));
    });

    // Ẩn kết quả khi click ra ngoài
    document.addEventListener("click", function(event) {
      if (!searchResults.contains(event.target) && event.target !== searchInput) {
        searchResults.classList.add("d-none");
      }
    });
  });
  </script>






  <style>
  /* Font chữ từ Google Fonts */
  @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

  /* ========== SLOGAN BAR ========== */
  .pmec-slogan-bar {
    display: flex;
    justify-content: center;
    padding-top: 0;
    background-color: #C2EFFF;
  }
  .pmec-slogan-inner {
    position: relative;
    width: 840px;
    height: 32px;
  }
  .pmec-slogan-layer {
    position: absolute;
    left: 50%;
    transform: translateX(-50%);
    height: 32px;
    border-bottom-left-radius: 40px;
    border-bottom-right-radius: 40px;
  }
  .pmec-slogan-orange {
    width: 840px;
    background-color: #F26522;
  }
  .pmec-slogan-lightblue {
    width: 818px;
    background-color: #00AEEF;
  }
  .pmec-slogan-darkblue {
    width: 792px;
    background-color: #03428E;
    display: flex;
    align-items: center;
    justify-content: center;
  }
  .pmec-slogan-darkblue p {
    color: #fff;
    font-family: 'Poppins', sans-serif;
    font-weight: 600;
    font-size: 14px;
    margin: 0;
    text-transform: uppercase;
    letter-spacing: 0.5px;
  }

  /* ========== MAIN HEADER ========== */
  .pmec-main-header {
    background-color: #C2EFFF;
  }
  .pmec-logo {
    flex-shrink: 0;
    text-decoration: none;
  }
  .pmec-logo img {
    transition: transform 0.2s ease;
  }
  .pmec-logo:hover img {
    transform: scale(1.03);
  }

  /* Search */
  .pmec-search-wrapper {
    flex: 1;
    max-width: 400px;
    min-width: 180px;
  }
  .pmec-search-input {
    height: 42px;
    border-radius: 40px !important;
    border: none !important;
    background-color: rgba(255,255,255,0.8) !important;
    padding: 0 50px 0 20px;
    font-size: 13px;
    transition: background-color 0.2s ease;
  }
  .pmec-search-input:hover,
  .pmec-search-input:focus {
    background-color: #fff !important;
    box-shadow: 0 0 0 2px rgba(0,174,239,0.3) !important;
  }
  .pmec-search-btn {
    position: absolute;
    right: 6px;
    top: 6px;
    width: 30px;
    height: 30px;
    border-radius: 50%;
    background-color: #4BADE9;
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    font-size: 14px;
    transition: background-color 0.2s ease;
  }
  .pmec-search-btn:hover {
    background-color: #03428E;
    color: #fff;
  }

  /* Buttons */
  .pmec-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 7px 16px;
    border-radius: 32px;
    font-family: 'Poppins', sans-serif;
    font-size: 13px;
    font-weight: 500;
    text-decoration: none;
    border: none;
    cursor: pointer;
    white-space: nowrap;
    transition: all 0.25s ease;
    line-height: 1.4;
  }
  .pmec-btn-orange {
    background-color: #F26522;
    color: #fff;
  }
  .pmec-btn-orange:hover {
    background-color: #d3571c;
    color: #fff;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(242,101,34,0.35);
  }
  .pmec-btn-darkblue {
    background-color: #03428E;
    color: #fff;
  }
  .pmec-btn-darkblue:hover {
    background-color: #013779;
    color: #fff;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(3,66,142,0.35);
  }
  .pmec-btn-lightblue {
    background-color: #00AEEF;
    color: #fff;
  }
  .pmec-btn-lightblue:hover {
    background-color: #03a5e2;
    color: #fff;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0,174,239,0.35);
  }
  .pmec-btn-outline {
    background-color: transparent;
    color: #03428E;
    border: 2px solid #03428E;
  }
  .pmec-btn-outline:hover {
    background-color: #03428E;
    color: #fff;
    transform: translateY(-1px);
  }
  .pmec-btn-green {
    background-color: #28a745;
    color: #fff;
  }
  .pmec-btn-green:hover {
    background-color: #218838;
    color: #fff;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(40,167,69,0.35);
  }
  .pmec-btn-danger {
    background-color: #dc3545;
    color: #fff;
  }
  .pmec-btn-danger:hover {
    background-color: #c82333;
    color: #fff;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(220,53,69,0.35);
  }

  /* Language button */
  .pmec-lang-btn {
    background: rgba(255,255,255,0.6);
    border: none;
    border-radius: 50%;
    width: 36px;
    height: 36px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: background-color 0.2s ease;
    padding: 0;
  }
  .pmec-lang-btn::after {
    display: none; /* Hide default dropdown caret */
  }
  .pmec-lang-btn:hover {
    background: rgba(255,255,255,1);
  }

  /* ========== NAVIGATION BAR ========== */
  .pmec-navbar {
    background-color: #ffffff;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    padding: 0;
  }
  .pmec-nav-list {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 4px;
  }
  .pmec-nav-link {
    font-family: 'Poppins', sans-serif !important;
    font-size: 15px !important;
    font-weight: 700 !important;
    color: #03428E !important;
    padding: 12px 16px !important;
    border-radius: 32px;
    transition: all 0.2s ease;
    margin: 0 !important;
  }
  .pmec-nav-link:hover {
    background-color: #C2EFFF !important;
    color: #03428E !important;
    transform: none;
  }

  /* ========== RESPONSIVE ========== */
  @media (max-width: 991px) {
    .pmec-slogan-bar {
      display: none;
    }
    .pmec-search-wrapper {
      max-width: 100%;
      order: 10;
      width: 100%;
      margin-top: 8px;
    }
    .pmec-nav-list {
      flex-direction: column;
      gap: 0;
      padding: 8px 0;
    }
    .pmec-nav-link {
      width: 100%;
      text-align: center;
    }
  }

  @media (max-width: 576px) {
    .pmec-btn {
      font-size: 11px;
      padding: 5px 10px;
    }
  }



  /* ========== NEW FOOTER STYLES ========== */
  .footer-custom {
      background-color: #C2EFFF;
      font-family: 'Poppins', sans-serif;
  }
  .footer-custom .footer-top {
      padding: 40px 0 20px 0;
  }
  .footer-logo img {
      transition: transform 0.2s ease;
  }
  .footer-logo:hover img {
      transform: scale(1.05);
  }
  .footer-info-list li {
      font-size: 13px;
      color: #333;
      margin-bottom: 8px;
      display: flex;
      align-items: flex-start;
      gap: 8px;
  }
  .footer-info-list i {
      margin-top: 3px;
  }
  .footer-heading {
      font-size: 14px;
      font-weight: 700;
      color: #03428E;
      text-transform: uppercase;
      margin-bottom: 20px;
  }
  .clinic-title {
      font-size: 13px;
      font-weight: 700;
      color: #03428E;
      margin-bottom: 6px;
  }
  .clinic-info li {
      font-size: 12px;
      color: #333;
      margin-bottom: 4px;
      display: flex;
      align-items: flex-start;
      gap: 6px;
  }
  .clinic-info i {
      color: #F26522; /* Orange icons */
      margin-top: 2px;
  }
  .footer-links li {
      margin-bottom: 10px;
  }
  .footer-links a {
      color: #333;
      text-decoration: none;
      font-size: 13px;
      transition: color 0.2s;
  }
  .footer-links a:hover {
      color: #03428E;
      text-decoration: underline;
  }
  .footer-social {
      display: flex;
      gap: 12px;
      justify-content: flex-end;
  }
  .footer-social .social-icon {
      width: 36px;
      height: 36px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
      text-decoration: none;
      font-size: 18px;
      transition: transform 0.2s;
  }
  .footer-social .social-icon.fb { background-color: #1877F2; }
  .footer-social .social-icon.yt { background-color: #FF0000; }
  .footer-social .social-icon.tt { background-color: #000000; }
  .footer-social .social-icon:hover {
      transform: scale(1.1);
  }

  /* ========== FLOATING CONTACT MENU ========== */
  .floating-contact {
      position: fixed;
      bottom: 24px;
      left: 24px;
      z-index: 999;
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 12px;
  }
  .contact-menu {
      display: flex;
      flex-direction: column;
      gap: 12px;
      opacity: 0;
      visibility: hidden;
      transform: translateY(20px);
      transition: all 0.3s ease;
  }
  .contact-menu.show {
      opacity: 1;
      visibility: visible;
      transform: translateY(0);
  }
  .contact-item {
      width: 48px;
      height: 48px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
      text-decoration: none;
      font-size: 20px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.15);
      transition: transform 0.2s ease;
  }
  .contact-item:hover {
      transform: scale(1.1);
      color: white;
  }
  .contact-item.zalo { background-color: #fff; border: 1px solid #0068FF; color: #0068FF; font-weight: bold; font-size: 14px;}
  .contact-item.zalo:hover { background-color: #0068FF; color: #fff;}
  .contact-item.phone { background-color: #28a745; }
  .contact-item.messenger { background-color: #0084FF; }
  
  .contact-toggle {
      width: 56px;
      height: 56px;
      border-radius: 50%;
      background-color: #fff;
      color: #F26522;
      border: 1px solid rgba(242,101,34,0.2);
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 24px;
      box-shadow: 0 4px 16px rgba(0,0,0,0.1);
      cursor: pointer;
      transition: all 0.3s ease;
      position: relative;
  }
  .contact-toggle::after {
      content: '';
      position: absolute;
      top: 6px;
      right: 6px;
      width: 10px;
      height: 10px;
      background-color: #ff3b30;
      border-radius: 50%;
      border: 2px solid #fff;
  }
  .contact-toggle:hover {
      transform: scale(1.05);
      box-shadow: 0 6px 20px rgba(0,0,0,0.15);
  }
  .contact-toggle.active {
      background-color: #F26522;
      color: #fff;
  }
  .contact-toggle.active i::before {
      content: "\f00d"; /* fa-times */
  }
  .contact-toggle.active::after {
      display: none;
  }

  .btn-360 {
      width: 56px;
      height: 56px;
      border-radius: 50%;
      background-color: #fff;
      color: #F26522;
      border: 1px solid rgba(242,101,34,0.2);
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      font-size: 20px;
      box-shadow: 0 4px 16px rgba(0,0,0,0.1);
      cursor: pointer;
      transition: all 0.3s ease;
      margin-top: 12px;
      text-decoration: none;
  }
  .btn-360 span {
      font-size: 11px;
      font-weight: bold;
      line-height: 1;
      margin-top: 2px;
  }
  .btn-360:hover {
      background-color: #F26522;
      color: #fff;
      transform: scale(1.05);
  }

  /* Responsive */
  @media (max-width: 768px) {
      .footer-custom .col-lg-3, .footer-custom .col-lg-4, .footer-custom .col-lg-2 {
          text-align: center;
      }
      .footer-social { justify-content: center; margin-top: 16px; }
      .footer-info-list li, .clinic-info li { justify-content: center; }
  }


  #searchResults {
    position: absolute;
    width: 100%;
    background: white;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    border-radius: 5px;
    max-height: 300px;
    overflow-y: auto;
    z-index: 1000;
  }

  #searchResults div {
    padding: 10px;
  }

  #searchResults img {
    border-radius: 50%;
    width: 40px;
    height: 40px;
    object-fit: cover;
  }

  #searchResults a {
    text-decoration: none;
    color: #333;
  }

  #searchResults a:hover {
    text-decoration: underline;
  }
  </style>


  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
  <script>AOS.init({ duration: 800, once: true, offset: 100 });</script>

  <script>
      // Toggle Floating Contact Menu
      document.getElementById('contactToggle').addEventListener('click', function() {
          this.classList.toggle('active');
          document.getElementById('contactMenu').classList.toggle('show');
      });
  </script>
</body>

</html>
