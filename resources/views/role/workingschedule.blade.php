<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Lịch Làm Việc')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <style>
        /* Custom responsive adjustments */
        header .container {
            flex-wrap: wrap;
        }

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
            width: 100%;
            margin-bottom: 10px;
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

        @media (max-width: 768px) {
            .footer-col {
                align-items: center;
            }
        }
    </style>
</head>

<body>
    <header>
        <div class="py-3" style="background-color: #e0f7fa; border-bottom: 1px solid #ccc;">
            <div class="container d-flex justify-content-between align-items-center">
                <!-- Logo -->
                <a href="{{ url('/admin/dashboard') }}" class="d-flex align-items-center">
                    <img src="{{ asset('img/logo.webp') }}" alt="Logo" class="img-fluid" style="height: 50px;">
                </a>
                <!-- Search -->
                <div class="d-flex align-items-center flex-grow-1 mx-2" style="max-width: 400px; width: 100%;">
                    <input type="text" class="form-control" placeholder="Tìm kiếm..." style="border-radius: 25px;">
                    <button class="btn btn-primary ms-2" style="border-radius: 25px;">
                        <i class="bi bi-search"></i>
                    </button>
                </div>
                <!-- Actions -->
                <div class="d-flex align-items-center flex-wrap gap-2">
                    <a href="#" class="btn btn-primary btn-sm rounded-pill px-3">Đặt lịch khám</a>
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
                            <img src="{{ asset('img/iconVN.png') }}" alt="VN" class="img-fluid" style="height: 20px;">
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="languageDropdown">
                            <li><a class="dropdown-item" href="#">Vietnamese</a></li>
                            <li><a class="dropdown-item" href="#">English</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </header>






    <div class="container py-4">
        <h1 class="text-center mb-4"> Lịch Làm Việc Theo Chuyên Môn</h1>

        <div class="table-responsive">
            <table class="table table-bordered">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Chuyên môn</th>
                        <th>Bác sĩ</th>
                        <th>Điện thoại</th>
                        <th>Ảnh</th>
                        <th colspan="7" class="text-center"><i class="bi bi-calendar3"></i> Lịch Trực Trong Tuần</th>
                    </tr>
                    <tr>
                        <th colspan="5"></th>
                        <th>Thứ 2</th>
                        <th>Thứ 3</th>
                        <th>Thứ 4</th>
                        <th>Thứ 5</th>
                        <th>Thứ 6</th>
                        <th>Thứ 7</th>
                        <th>Chủ Nhật</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($specialtyGroups as $specialty => $doctorspecialty)
                                        @php
                                            $weekdays = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
                                            $shiftLabels = ['morning' => '08:00 - 12:00', 'afternoon' => '14:00 - 18:00'];
                                            $specialtyShiftEmpty = [];

                                            // Khởi tạo trạng thái kiểm tra ca làm việc của từng chuyên môn
                                            foreach ($weekdays as $day) {
                                                $specialtyShiftEmpty[$day] = ['morning' => true, 'afternoon' => true];
                                            }
                                        @endphp

                                        <tr class="table-primary text-center">
                                            <td colspan="13"><strong><i class="bi bi-stethoscope"></i> {{ $specialty }}</strong></td>
                                        </tr>

                                        @foreach($doctorspecialty as $doctor)
                                                        <tr>
                                                            <td>{{ $loop->parent->iteration }}.{{ $loop->iteration }}</td>
                                                            <td>{{ $doctor->specialty }}</td>
                                                            <td>{{ $doctor->name }}</td>
                                                            <td>{{ $doctor->phone }}</td>
                                                            <td>
                                                                @if($doctor->image)
                                                                    <img src="{{ asset($doctor->image) }}" class="img-thumbnail" style="max-width: 50px;">
                                                                @else
                                                                    <span>Không có ảnh</span>
                                                                @endif
                                                            </td>

                                                            @php
                                                                $working_hours = $doctor->working_hours ?? [];
                                                            @endphp

                                                            @foreach($weekdays as $day)
                                                                            <td>
                                                                                @php
                                                                                    $shifts = collect($working_hours)->where('day', $day)->pluck('shift')->toArray();
                                                                                @endphp
                                                                                @if(!empty($shifts))
                                                                                                @foreach($shifts as $shift)
                                                                                                    <span class="badge"
                                                                                                        style="background-color: {{ $shift == 'morning' ? '#2A95BF' : '#FFD700' }};
                                                                                                                                                                                         color: {{ $shift == 'morning' ? 'white' : 'black' }};">
                                                                                                        {{ $shiftLabels[$shift] }}
                                                                                                    </span><br>
                                                                                                @endforeach
                                                                                                @php
                                                                                                    // Nếu có bác sĩ trực ca nào thì đánh dấu ca đó không trống
                                                                                                    foreach ($shifts as $shift) {
                                                                                                        $specialtyShiftEmpty[$day][$shift] = false;
                                                                                                    }
                                                                                                @endphp
                                                                                @else
                                                                                    <span class="text-danger">Nghỉ</span>
                                                                                @endif
                                                                            </td>
                                                            @endforeach
                                                        </tr>
                                        @endforeach

                                        {{-- Hiển thị cảnh báo ca trống cho từng chuyên môn --}}
                                        @foreach($specialtyShiftEmpty as $day => $shifts)
                                            @foreach($shifts as $shift => $isEmpty)
                                                @if($isEmpty && $day != 'Sunday') {{-- Không cảnh báo nếu là Chủ Nhật --}}
                                                    <tr class="bg-warning text-center">
                                                        <td colspan="13" class="text-danger">
                                                            ⚠️ Chuyên môn "{{ $specialty }}" không có bác sĩ trực vào
                                                            **{{ __("Thứ " . (array_search($day, $weekdays) + 2)) }} - {{ $shiftLabels[$shift] }}**!
                                                        </td>
                                                    </tr>
                                                @endif
                                            @endforeach
                                        @endforeach
                    @endforeach

                </tbody>
            </table>
        </div>
    </div>
    <div class="container py-5" id="loaddoctor">
        <h2 class="text-center mb-4" style="font-weight: 700; color: #0056b3;">Chỉnh Sửa Lịch Làm Việc</h2>

        <!-- Chọn bác sĩ -->
        <div class="mb-4">
            <label for="doctorSelect" class="form-label"><strong>Chọn bác sĩ:</strong></label>
            <select id="doctorSelect" class="form-control" onchange="loadDoctorSchedule()">
                <option value="">-- Chọn bác sĩ --</option>
                @foreach ($doctors as $doctor)
                    <option value="{{ $doctor->id }}" {{ isset($selectedDoctor) && $selectedDoctor->id == $doctor->id ? 'selected' : '' }}>
                        {{ $doctor->name }}
                    </option>
                @endforeach
            </select>
        </div>


        @if(isset($selectedDoctor))
                <!-- Thông tin bác sĩ -->
                <div class="card shadow-sm mb-4">
                    <div class="card-body text-center">
                        <h4 class="mb-2">Thông tin bác sĩ</h4>
                        <img src="{{ asset($selectedDoctor->image) }}" class="rounded-circle mb-3"
                            style="width: 100px; height: 100px; object-fit: cover;" alt="{{ $selectedDoctor->name }}">
                        <p><strong>{{ $selectedDoctor->name }}</strong></p>
                        <p><strong>Chuyên môn:</strong> {{ $selectedDoctor->specialty }}</p>
                        <p><strong>Số điện thoại:</strong> {{ $selectedDoctor->phone }}</p>
                    </div>
                </div>

                <!-- Form chỉnh sửa lịch làm việc -->
                <form method="POST" action="{{ route('admin.updateSchedule', $selectedDoctor->id) }}" id="updateScheduleForm">
                    @csrf
                    <div class="card shadow-sm">
                        <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                            <span> Chỉnh sửa lịch làm việc</span>
                            <button type="button" class="btn btn-light btn-sm" onclick="addScheduleRow()">+ Thêm ca làm
                                việc</button>
                        </div>
                        <div class="card-body" id="scheduleContainer">
                            @php
                                $workingHours = is_array($selectedDoctor->working_hours) ? $selectedDoctor->working_hours :
                                    json_decode($selectedDoctor->working_hours, true) ?? [];
                            @endphp

                            @foreach ($workingHours as $index => $schedule)
                                <div class="schedule-row d-flex align-items-center mb-2">
                                    <select name="working_hours[{{ $index }}][day]" class="form-select me-2">
                                        <option value="Monday" {{ $schedule['day'] == 'Monday' ? 'selected' : '' }}>Thứ Hai</option>
                                        <option value="Tuesday" {{ $schedule['day'] == 'Tuesday' ? 'selected' : '' }}>Thứ Ba
                                        </option>
                                        <option value="Wednesday" {{ $schedule['day'] == 'Wednesday' ? 'selected' : '' }}>Thứ Tư
                                        </option>
                                        <option value="Thursday" {{ $schedule['day'] == 'Thursday' ? 'selected' : '' }}>Thứ Năm
                                        </option>
                                        <option value="Friday" {{ $schedule['day'] == 'Friday' ? 'selected' : '' }}>Thứ Sáu</option>
                                        <option value="Saturday" {{ $schedule['day'] == 'Saturday' ? 'selected' : '' }}>Thứ Bảy
                                        </option>
                                        <option value="Sunday" {{ $schedule['day'] == 'Sunday' ? 'selected' : '' }}>Chủ Nhật
                                        </option>
                                    </select>

                                    <select name="working_hours[{{ $index }}][shift]" class="form-select me-2">
                                        <option value="morning" {{ $schedule['shift'] == 'morning' ? 'selected' : '' }}>08:00 -
                                            12:00
                                        </option>
                                        <option value="afternoon" {{ $schedule['shift'] == 'afternoon' ? 'selected' : '' }}>14:00 -
                                            18:00</option>
                                    </select>

                                    <button type="button" class="btn btn-danger btn-sm"
                                        onclick="removeScheduleRow(this)">Xóa</button>
                                </div>
                            @endforeach
                        </div>
                        <div class="card-footer text-center">
                            <button type="submit" class="btn btn-warning">Lưu Thay Đổi</button>
                        </div>
                    </div>
                </form>
        @endif
    </div>

    <script>
        function addScheduleRow() {
            let index = document.querySelectorAll('.schedule-row').length;
            let scheduleDiv = document.createElement('div');
            scheduleDiv.classList.add('schedule-row', 'd-flex', 'align-items-center', 'mb-2');

            scheduleDiv.innerHTML = `
                <select name="working_hours[${index}][day]" class="form-select me-2">
                    <option value="Monday">Thứ Hai</option>
                    <option value="Tuesday">Thứ Ba</option>
                    <option value="Wednesday">Thứ Tư</option>
                    <option value="Thursday">Thứ Năm</option>
                    <option value="Friday">Thứ Sáu</option>
                    <option value="Saturday">Thứ Bảy</option>
                    <option value="Sunday">Chủ Nhật</option>
                </select>

                <select name="working_hours[${index}][shift]" class="form-select me-2">
                    <option value="morning">08:00 - 12:00</option>
                    <option value="afternoon">14:00 - 18:00</option>
                </select>

                <button type="button" class="btn btn-danger btn-sm" onclick="removeScheduleRow(this)">Xóa</button>
            `;
            document.getElementById('scheduleContainer').appendChild(scheduleDiv);
        }

        function removeScheduleRow(button) {
            button.parentElement.remove();
        }

        function loadDoctorSchedule() {
            let doctorId = document.getElementById('doctorSelect').value;
            if (doctorId) {
                localStorage.setItem("scrollToForm", "true");
                window.location.href = `?doctor_id=${doctorId}`;
            }
        }
        window.onload = function () {
            if (localStorage.getItem("scrollToForm") === "true") {
                setTimeout(() => {
                    document.getElementById('loaddoctor').scrollIntoView({
                        behavior: 'smooth'
                    });
                    localStorage.removeItem("scrollToForm"); // Xóa trạng thái để tránh cuộn lại khi không cần
                }, 500);
            }
        };
    </script>
    <footer class="footer">
        <div class="container">
            <div class="row">
                <!-- Cột 1: Thông tin bệnh viện -->
                <div class="col-md-3 footer-col">
                    <a href="#" class="footer-logo">
                        <img src="{{ asset('img/phenikaamec.webp') }}" alt="PHENIKAA MEC" class="img-fluid">
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
                        <a href="#"><img src="{{ asset('img/qr.png') }}" alt="QR Code" class="img-fluid"></a>
                    </div>
                    <div class="social-icons">
                        <a href="#"><img src="{{ asset('img/iconfb.webp') }}" alt="Facebook" class="img-fluid"></a>
                        <a href="#"><img src="{{ asset('img/iconyoutube.webp') }}" alt="YouTube" class="img-fluid"></a>
                        <a href="#"><img src="{{ asset('img/icontiktok.webp') }}" alt="TikTok" class="img-fluid"></a>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>


</html>