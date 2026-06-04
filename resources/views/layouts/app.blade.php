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
</head>

<body>
  <header>
    <div class="py-3" style="background-color: #e0f7fa; border-bottom: 1px solid #ccc;">
      <div class="container d-flex justify-content-center align-items-center flex-wrap gap-3">
        <!-- Logo -->
        <a href="{{ url('/') }}" class="d-flex align-items-center">
          <img src="/img/logo.webp" alt="Logo" style="height: 50px;">

        </a>

        <!-- Ô tìm kiếm -->
        <div class="position-relative" style="max-width: 400px; width: 100%;">
          <input type="text" id="searchInput" class="form-control" placeholder="Tìm kiếm..."
            style="border-radius: 25px;">
          <button class="btn btn-primary position-absolute top-0 end-0 m-1" style="border-radius: 25px;">
            <i class="bi bi-search"></i>
          </button>
          <!-- Kết quả tìm kiếm -->
          <div id="searchResults" class="position-absolute w-100 bg-white shadow rounded mt-2 d-none"
            style="max-height: 300px; overflow-y: auto; z-index: 1000;">
          </div>
        </div>


        <!-- Actions -->
        <a href="/appointments/create" class="btn btn-primary btn-sm rounded-pill px-3"
          style="background-color: #007bff; border-color: #007bff;">Đặt lịch khám</a>
        @auth
          @if(Auth::user()->phone)
          <span class="btn btn-info btn-sm rounded-pill px-3" style="color: white;">{{ Auth::user()->phone }}</span>
          @else
          <a href="https://zalo.me/0886314896" class="btn btn-info btn-sm rounded-pill px-3"
            style="color: white;">0886314896</a>
          @endif
        @else
        <a href="https://zalo.me/0886314896" class="btn btn-info btn-sm rounded-pill px-3"
          style="color: white;">0886314896</a>
        @endauth
        <a href="/support" class="btn btn-warning btn-sm rounded-pill px-3" style="color: white;">Hướng dẫn
          khách
          hàng</a>
        @guest
        <a href="{{ route('login') }}" class="btn btn-outline-primary btn-sm rounded-pill px-3">&#272;&#259;ng
          nh&#7853;p</a>
        <a href="{{ route('register') }}" class="btn btn-success btn-sm rounded-pill px-3">&#272;&#259;ng k&#253;</a>
        @else
        @if(Auth::user()->role === 'patient')
        <a href="{{ route('patient.account') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3">T&#224;i kho&#7843;n c&#7911;a t&#244;i</a>
        @endif
        <form method="POST" action="{{ route('logout') }}" class="d-inline-block m-0">
          @csrf
          <button type="submit" class="btn btn-danger btn-sm rounded-pill px-3">&#272;&#259;ng xu&#7845;t</button>
        </form>
        @endguest
        <div class="dropdown">
          <button class="btn btn-light btn-sm rounded-circle dropdown-toggle" id="languageDropdown"
            data-bs-toggle="dropdown" aria-expanded="false">
            <img src="/img/vietnam.png" alt="VN" style="height: 20px;"> <!-- Icon cờ -->
          </button>
          <ul class="dropdown-menu" aria-labelledby="languageDropdown">
            <li><a class="dropdown-item" href="#">Vietnamese</a></li>
            <li><a class="dropdown-item" href="#">English</a></li>
          </ul>
        </div>
      </div>
    </div>
  </header>


  <nav class="navbar navbar-expand-lg" style="background-color: #ffffff; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
    <div class="container">
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
        aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
        <ul class="navbar-nav">
          <li class="nav-item"><a class="nav-link" href="{{ url('/') }}" style="color: #0056b3; font-family: 'Poppins', sans-serif; font-size: 14px; font-weight: 500; 
                            margin: 0 10px;">Trang Chủ</a>
          </li>
          <li class="nav-item"><a class="nav-link" href="{{ url('/about') }}" style="color: #0056b3; font-family: 'Poppins', sans-serif; font-size: 14px; font-weight: 500;
                             margin: 0 10px;">Thông Tin</a></li>
          <li class="nav-item"><a class="nav-link" href="{{ url('/services') }}" style="color: #0056b3; font-family: 'Poppins', sans-serif; font-size: 14px; font-weight: 500; 
                            margin: 0 10px;">Dịch Vụ</a>
          </li>
          <li class="nav-item"><a class="nav-link" href="{{ url('/contact') }}" style="color: #0056b3; font-family: 'Poppins', sans-serif; font-size: 14px; font-weight: 500;
                            margin: 0 10px;">Liên Lạc</a>
          </li>
          <li class="nav-item"><a class="nav-link" href="{{ url('/doctors') }}" style="color: #0056b3; font-family: 'Poppins', sans-serif; font-size: 14px; font-weight: 500;
                             margin: 0 10px;">Bác Sĩ</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>


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
            <a href="#"><img src="{{ asset('img/iconfb.webp') }}" alt="Facebook"></a>
            <a href="#"><img src="{{ asset('img/iconyoutube.webp') }}" alt="YouTube"></a>
            <a href="#"><img src="{{ asset('img/icontiktok.webp') }}" alt="TikTok"></a>
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
  @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap');


  .nav-link {
    font-family: Arial, sans-serif !important;
    font-size: 14px;
    font-weight: 500;
    color: #0056b3;
    margin: 0 10px;
  }



  /* Hiệu ứng hover: sáng lên */
  nav a:hover {
    background-color: rgba(0, 115, 230, 0.1);
    /* Nền sáng nhẹ */
    color: #0073e6;
    /* Đổi màu chữ */
    transform: scale(1.05);
    /* Phóng to nhẹ */
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
</body>

</html>
