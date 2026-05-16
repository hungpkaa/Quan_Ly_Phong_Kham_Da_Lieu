<!DOCTYPE html>
<html lang="en">
@section('title', 'Quản lý Hồ Sơ Bệnh Án')

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
                <a href="{{ url('/admin/dashboard') }}" class="d-flex align-items-center">
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


    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>Thành công!</strong> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>Lỗi!</strong> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="container py-4">
        <h1 class="text-center mb-4" style="font-family: 'Poppins', sans-serif;">Quản lý Hồ Sơ Bệnh Án</h1>

        <!-- Form tìm kiếm hồ sơ bệnh án -->
        <form method="GET" action="{{ route('admin.medicalrecords.index') }}" class="mb-4">
            <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Tìm kiếm hồ sơ bệnh án..."
                    value="{{ $search ?? '' }}">
                <button type="submit" class="btn btn-primary">Tìm kiếm</button>
            </div>
        </form>

        <!-- Form thêm/sửa hồ sơ bệnh án -->
        @if(isset($editMedicalRecord))
        <h3 class="mb-3">Sửa Hồ Sơ Bệnh Án</h3>
        <form method="POST" action="{{ route('admin.medicalrecords.update', $editMedicalRecord->id) }}">
            @csrf
            @method('PUT')
            @else
            <h3 class="mb-3">Thêm Hồ Sơ Bệnh Án</h3>
            <form method="POST" action="{{ route('admin.medicalrecords.store') }}">
                @csrf
                @endif
                <div class="row">
                    <!-- Chọn Bác Sĩ -->
                    <div class="col-md-4 mb-2">
                        <label for="doctor_id" class="form-label">Chọn Bác Sĩ</label>
                        <select name="doctor_id" id="doctor_id" class="form-control" required>
                            <option value="">-- Chọn Bác Sĩ --</option>
                            @foreach($doctors as $doctor)
                            <option value="{{ $doctor->id }}"
                                {{ (isset($editMedicalRecord) && $editMedicalRecord->doctor_id == $doctor->id) ? 'selected' : '' }}>
                                {{ $doctor->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Tên Bệnh Nhân -->
                    <div class="col-md-4 mb-2">
                        <label for="name" class="form-label">Tên Bệnh Nhân</label>
                        <input type="text" name="name" id="name" class="form-control"
                            value="{{ $editMedicalRecord->name ?? '' }}" required>
                    </div>

                    <!-- Email -->
                    <div class="col-md-4 mb-2">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" name="email" id="email" class="form-control"
                            value="{{ $editMedicalRecord->email ?? '' }}" required>
                    </div>

                    <!-- Số Điện Thoại -->
                    <div class="col-md-4 mb-2">
                        <label for="phone" class="form-label">Số Điện Thoại</label>
                        <input type="text" name="phone" id="phone" class="form-control"
                            value="{{ $editMedicalRecord->phone ?? '' }}" required>
                    </div>

                    <!-- Tuổi -->
                    <div class="col-md-4 mb-2">
                        <label for="age" class="form-label">Tuổi</label>
                        <input type="number" name="age" id="age" class="form-control"
                            value="{{ $editMedicalRecord->age ?? '' }}" required>
                    </div>

                    <!-- CCCD -->
                    <div class="col-md-4 mb-2">
                        <label for="cccd" class="form-label">CCCD</label>
                        <input type="text" name="cccd" id="cccd" class="form-control"
                            value="{{ $editMedicalRecord->cccd ?? '' }}" required>
                    </div>

                    <!-- Dịch Vụ -->
                    <div class="col-md-4 mb-2">
                        <label for="service" class="form-label">Dịch Vụ</label>
                        <input type="text" name="service" id="service" class="form-control"
                            value="{{ $editMedicalRecord->service ?? '' }}">
                    </div>

                    <!-- Ngày Khám -->
                    <div class="col-md-4 mb-2">
                        <label for="exam_date" class="form-label">Ngày Khám</label>
                        <input type="date" name="exam_date" id="exam_date" class="form-control"
                            value="{{ $editMedicalRecord->exam_date ?? '' }}" required>
                    </div>

                    <!-- Chi Phí -->
                    <div class="col-md-4 mb-2">
                        <label for="cost" class="form-label">Chi Phí</label>
                        <div class="input-group">
                            <input type="number" step="any" name="cost" id="cost" class="form-control"
                                value="{{ isset($editMedicalRecord) ? $editMedicalRecord->cost / 1000 : old('cost') }}"
                                required>
                            <span class="input-group-text">.000 VNĐ</span>
                        </div>
                    </div>

                    <!-- Trạng Thái Thanh Toán -->
                    <div class="col-md-4 mb-2">
                        <label for="status" class="form-label">Trạng Thái Thanh Toán</label>
                        <select name="status" id="status" class="form-control" required>
                            <option value="unpaid"
                                {{ (isset($editMedicalRecord) && $editMedicalRecord->status == 'unpaid') ? 'selected' : '' }}>
                                Chưa thanh toán
                            </option>
                            <option value="paid"
                                {{ (isset($editMedicalRecord) && $editMedicalRecord->status == 'paid') ? 'selected' : '' }}>
                                Đã thanh toán
                            </option>
                        </select>
                    </div>

                    <!-- Chẩn Đoán -->
                    <div class="col-12 mb-2">
                        <label for="diagnosis" class="form-label">Chẩn Đoán</label>
                        <textarea name="diagnosis" id="diagnosis" class="form-control" rows="3"
                            required>{{ $editMedicalRecord->diagnosis ?? '' }}</textarea>
                    </div>

                    <!-- Toa Thuốc -->
                    <div class="col-12 mb-2">
                        <label for="prescription" class="form-label">Toa Thuốc</label>
                        <textarea name="prescription" id="prescription" class="form-control"
                            rows="3">{{ $editMedicalRecord->prescription ?? '' }}</textarea>
                    </div>

                    <!-- Ghi Chú -->
                    <div class="col-12 mb-2">
                        <label for="notes" class="form-label">Ghi Chú</label>
                        <textarea name="notes" id="notes" class="form-control"
                            rows="3">{{ $editMedicalRecord->notes ?? '' }}</textarea>
                    </div>

                    <!-- Nút Gửi -->
                    <div class="col-12 mb-2">
                        <button type="submit"
                            class="btn {{ isset($editMedicalRecord) ? 'btn-warning' : 'btn-success' }} w-100">
                            {{ isset($editMedicalRecord) ? 'Lưu Thay Đổi' : 'Thêm Hồ Sơ Bệnh Án' }}
                        </button>
                    </div>
                </div>
            </form>

            <!-- Danh sách hồ sơ bệnh án -->
            <div class="table-responsive">
                <table class="table table-bordered mt-4">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Bác Sĩ</th>
                            <th>Bệnh Nhân</th>
                            <th>Email</th>
                            <th>SĐT</th>
                            <th>Ngày Khám</th>
                            <th>Dịch Vụ</th>
                            <th>Chi Phí</th>
                            <th>Trạng Thái</th>
                            <th>Chẩn Đoán</th>
                            <th>Toa Thuốc</th>
                            <th>Ghi Chú</th>
                            <th>Hành Động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($medicalRecords as $record)
                        <tr>
                            <td>{{ $medicalRecords->firstItem() + $loop->index }}</td>
                            <td>{{ optional($record->doctor)->name ?? 'Không xác định' }}</td>
                            <td>{{ $record->name }}</td>
                            <td>{{ $record->email }}</td>
                            <td>{{ $record->phone }}</td>
                            <td>{{ $record->exam_date }}</td>
                            <td>{{ $record->service }}</td>
                            <td>{{ number_format($record->cost / 1000, 0) }}.000 VNĐ</td>
                            <td>
                                @if($record->status === 'unpaid')
                                <span class="badge bg-warning">Chưa thanh toán</span>
                                @elseif($record->status === 'paid')
                                <span class="badge bg-success">Đã thanh toán</span>
                                @endif
                            </td>
                            <td>{{ $record->diagnosis }}</td>
                            <td>{{ $record->prescription }}</td>
                            <td>{{ $record->notes }}</td>
                            <td>
                                <a href="{{ route('admin.medicalrecords.index', ['edit_id' => $record->id]) }}"
                                    class="btn btn-warning btn-sm">Sửa</a>
                                <form method="POST" action="{{ route('admin.medicalrecords.destroy', $record->id) }}"
                                    class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm"
                                        onclick="return confirm('Bạn có chắc chắn muốn xóa hồ sơ bệnh án này?')">Xóa</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
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

    .alert {
        text-align: center;
        width: 100%;
        margin: 20px auto;
        /* Căn giữa theo chiều ngang */
        padding: 15px;
        font-size: 18px;
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