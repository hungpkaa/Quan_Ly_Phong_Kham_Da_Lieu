<!DOCTYPE html>
<html lang="en">

@section('title', 'Lịch trình khám bệnh')

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>

<body>
    <header>
        <div class="py-3" style="background-color: #e0f7fa; border-bottom: 1px solid #ccc;">
            <div class="container d-flex justify-content-between align-items-center flex-wrap gap-3">
                <!-- Logo -->
                <a href="{{ url('/admindoctor/dashboard') }}" class="d-flex align-items-center">
                    <img src="/img/logo.webp" alt="Logo" style="height: 50px;">
                </a>

                <!-- Search -->
                <div class="d-flex align-items-center" style="max-width: 400px; width: 100%;">
                    <input type="text" class="form-control" placeholder="Tìm kiếm..." style="border-radius: 25px;">
                    <button class="btn btn-primary ms-2" style="border-radius: 25px;">
                        <i class="bi bi-search"></i>
                    </button>
                </div>

                <!-- Actions -->
                <a href="/appointments/create" class="btn btn-primary btn-sm rounded-pill px-3"
                    style="background-color: #007bff; border-color: #007bff;">Đặt lịch khám</a>
                <a href="#" class="btn btn-info btn-sm rounded-pill px-3" style="color: white;">1900 886648</a>
                <a href="#" class="btn btn-warning btn-sm rounded-pill px-3" style="color: white;">Hướng dẫn khách
                    hàng</a>

                <!-- Logout -->
                <form method="POST" action="{{ route('logout') }}" class="d-inline-block">
                    @csrf
                    <button type="submit" class="btn btn-danger btn-sm rounded-pill px-3">Đăng xuất</button>
                </form>

                <!-- Language Dropdown -->
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


    <div class="container py-4">
        <h1 class="text-center mb-4" style="font-family: 'Poppins', sans-serif;">Lịch trình Khám Bệnh</h1>

        <!-- Form tìm kiếm -->
        <div class="container">
            <form action="{{ route('appointments.search') }}" method="GET">
                <div class="input-group mb-3">
                    <input type="text" name="query" id="search" class="form-control"
                        placeholder="Nhập tên bệnh nhân, ngày khám hoặc trạng thái..." autocomplete="off">
                    <button type="submit" class="btn btn-primary">Tìm kiếm</button>
                </div>
            </form>
        </div>

        <!-- Hiển thị kết quả tìm kiếm -->
        @if(isset($appointments))
        @if($appointments->isEmpty())
        <div class="alert alert-info text-center">Không tìm thấy kết quả phù hợp.</div>
        @else
        @php
        $groupedAppointments = $appointments->groupBy(function ($appointment) {
        return \Carbon\Carbon::parse($appointment->appointment_date)->format('d/m/Y');
        });
        @endphp

        @foreach($groupedAppointments as $date => $dailyAppointments)
        <div class="card mb-4 shadow-lg border-0">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">{{ $date }}</h4>
            </div>
            <div class="card-body">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Bệnh Nhân</th>
                            <th>Ghi Chú</th>
                            <th>Trạng Thái</th>
                            <th>Thời gian hẹn</th>
                            <th>Hành Động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($dailyAppointments as $appointment)
                        <tr>
                            <td>{{ $appointment->name }}</td>
                            <td>{{ $appointment->description ?? 'Không có ghi chú' }}</td>
                            <td>
                                <span
                                    class="badge {{ $appointment->status === 'approved' ? 'bg-success' : 'bg-warning' }}">
                                    {{ $appointment->status === 'approved' ? 'Đã duyệt' : 'Đang chờ' }}
                                </span>
                            </td>
                            <td>
                                @switch($appointment->shift)
                                @case('morning')
                                <span class="badge bg-primary">08:00 - 12:00</span>
                                @break
                                @case('afternoon')
                                <span class="badge bg-info">14:00 - 18:00</span>
                                @break
                                @default
                                <span class="badge bg-secondary">Không xác định</span>
                                @endswitch
                            </td>
                            <td>
                                @if($appointment->status === 'approved')
                                <a href="{{ route('admindoctor.medicalrecords.create', ['appointment_id' => $appointment->id]) }}"
                                    class="btn btn-success btn-sm">
                                    Tạo Hồ Sơ Bệnh Án
                                </a>
                                @else
                                <button class="btn btn-secondary btn-sm" disabled>
                                    {{ $appointment->status === 'pending' ? 'Chờ duyệt' : 'Đã từ chối' }}
                                </button>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endforeach
        @endif
        @endif
    </div>

    <!-- CSS giúp bố cục gọn gàng hơn -->
    <style>
    .card-header {
        font-size: 20px;
        font-weight: 600;
    }

    .table th {
        background-color: #f8f9fa;
        font-weight: bold;
    }

    .table-hover tbody tr:hover {
        background-color: #f1f1f1;
    }
    </style>



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
                    <p>📞 Hotline: <a href="tel:1900886648">1900.88.66.48</a> - <a
                            href="tel:02422226688">02422226688</a></p>
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

    <style>
    /* Font chữ từ Google Fonts */
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap');

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
    </style>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>


</body>

</html>