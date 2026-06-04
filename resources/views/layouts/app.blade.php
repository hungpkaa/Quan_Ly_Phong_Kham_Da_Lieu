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

  <footer class="footer">
    <div class="container">
      <div class="row">
        <!-- Cột 1: Thông tin bệnh viện -->
        <div class="col-md-3 footer-col">
          <a href="#" class="footer-logo">
            <img src="{{ asset('img/phenikaamec.webp') }}" alt="PHENIKAA MEC">
          </a>

          <p><strong>Bệnh viện Đại Học Phenikaa</strong></p>
          <p>📍 Đường Kiều Mai, P. Phương Canh, Nam Từ Liêm, Hà Nội</p>
          <p>📜 Giấy phép hoạt động số 386/BYT</p>
          <p>📞 Hotline: <a href="tel:1900886648">1900.88.66.48</a> - <a href="tel:02422226688">02422226688</a></p>
          <p>📧 Email: <a href="mailto:support@phenikaamec.com">support@phenikaamec.com</a></p>
        </div>

        <!-- Cột 2: Hệ thống phòng khám -->
        <div class="col-md-3 footer-col">
          <h5 class="footer-title">HỆ THỐNG PHÒNG KHÁM</h5>
          <p><strong>Phòng Khám Đa Khoa - Hoàng Ngân</strong></p>
          <p>📍 Số 167 Hoàng Ngân, Hà Nội</p>
          <p>📞 <a href="tel:02422226699">02422226699</a></p>
          <p>⏰ Giờ làm việc: 7h30 - 17h00</p>

          <p><strong>Phòng Khám Răng Hàm Mặt</strong></p>
          <p>📍 Số 167 Hoàng Ngân, Hà Nội</p>
          <p>📞 <a href="tel:0978625499">0978625499</a></p>
          <p>⏰ Giờ làm việc: 8h00 - 18h00</p>
        </div>

        <!-- Cột 3: Liên kết nhanh -->
        <div class="col-md-3 footer-col">
          <h5 class="footer-title">LIÊN KẾT NHANH</h5>
          <ul class="list-unstyled">
            <li><a href="#">Chương trình Bác sĩ hợp tác</a></li>
            <li><a href="#">Chuyên khoa</a></li>
            <li><a href="#">Dịch vụ</a></li>
            <li><a href="#">Bệnh học</a></li>
          </ul>
        </div>

        <!-- Cột 4: Ứng dụng & Mạng xã hội -->
        <div class="col-md-3 footer-col">
          <h5 class="footer-title">TẢI APP PHENIKAA MEC</h5>
          <div class="qr-box">
            <a href="#"><img src="{{ asset('img/qr.png') }}" alt="Facebook"></a>
          </div>

          <div class="social-icons">
            <a href="https://www.facebook.com/phenikaamec.vn" target="_blank"><img src="{{ asset('img/iconfb.webp') }}" alt="Facebook"></a>
            <a href="https://www.youtube.com/@phenikaamec" target="_blank"><img src="{{ asset('img/iconyoutube.webp') }}" alt="YouTube"></a>
            <a href="https://www.tiktok.com/@phenikaamec" target="_blank"><img src="{{ asset('img/icontiktok.webp') }}" alt="TikTok"></a>
          </div>
        </div>
      </div>

      <hr class="footer-divider">

      <div class="text-center">
        <p>&copy; {{ date('Y') }} thuộc về Bệnh viện Đại học Phenikaa</p>
        <p><a href="#">Điều khoản sử dụng</a> | <a href="#">Chính sách bảo mật</a></p>
      </div>
    </div>
  </footer>


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



  /* Footer Styles */
  .footer {
    background-color: #b3e5fc;
    color: #003366;
    font-family: 'Poppins', sans-serif;
    padding: 40px 10%;
  }

  .footer-col {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
  }

  .footer-logo img {
    max-width: 180px;
    /* Giới hạn kích thước logo */
    display: block;
    margin-bottom: 10px;
    /* Tạo khoảng cách với nội dung */
  }

  .footer-title {
    font-size: 16px;
    font-weight: 600;
    color: #0056b3;
    margin-bottom: 12px;
  }

  .footer a {
    color: #003366;
    text-decoration: none;
    font-size: 14px;
    font-weight: 400;
  }

  .footer a:hover {
    color: #0056b3;
    text-decoration: underline;
  }

  .footer p {
    font-size: 14px;
    font-weight: 400;
  }

  .footer .list-unstyled li {
    margin-bottom: 6px;
  }

  .qr-box {
    background: white;
    padding: 10px;
    text-align: center;
    font-weight: 500;
    border: 2px solid #003366;
    border-radius: 5px;
  }

  /* Mạng xã hội */
  .social-icons {
    display: flex;
    gap: 10px;
    margin-top: 12px;
  }

  .social-icons img {
    width: 30px;
    height: 30px;
    transition: transform 0.2s ease-in-out;
  }

  .social-icons img:hover {
    transform: scale(1.1);
  }

  .footer-divider {
    margin: 20px 0;
    border-top: 1px solid #0056b3;
  }

  /* Responsive */
  @media (max-width: 768px) {
    .footer .row {
      text-align: center;
    }

    .footer-col {
      align-items: center;
    }


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
</body>

</html>
