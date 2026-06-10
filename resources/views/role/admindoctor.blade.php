@extends('layouts.admin_layout')

@section('title', 'Bảng Điều Khiển - Bác Sĩ')

@section('content')
<div class="container-fluid px-4 py-4">
    <!-- Header -->
    <div class="row mb-5">
        <div class="col-12 text-center">
            <h2 class="mb-2" style="font-family: 'Poppins', sans-serif; color: #0056b3; font-weight: 700;">
                Chào mừng Bác sĩ, {{ optional($doctor->user)->name }}!
            </h2>
            <p class="text-secondary">Chúc bạn một ngày làm việc hiệu quả và mang lại nhiều sức khỏe cho bệnh nhân.</p>
        </div>
    </div>

    <div class="row justify-content-center mb-5">
        <!-- Thông tin bác sĩ -->
        <div class="col-xl-5 col-lg-6">
            <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden">
                <div class="bg-primary bg-opacity-10 py-4 d-flex justify-content-center border-bottom border-primary border-opacity-25">
                    <!-- Hình ảnh bác sĩ với viền -->
                    <div class="p-1 bg-white rounded-circle shadow-sm" style="border: 3px solid #0056b3;">
                        <img src="{{ asset($doctor->image) }}" class="rounded-circle object-fit-cover"
                            style="width: 130px; height: 130px;" alt="{{ optional($doctor->user)->name }}">
                    </div>
                </div>
                
                <div class="card-body p-4">
                    <h4 class="text-center mb-4" style="font-weight: 700; color: #0056b3;">
                        {{ optional($doctor->user)->name }}
                    </h4>

                    <!-- Thông tin liên hệ -->
                    <div class="d-flex flex-column gap-3 mb-4">
                        <div class="d-flex align-items-center bg-light p-3 rounded-3 border">
                            <div class="bg-white text-primary rounded-circle d-flex align-items-center justify-content-center shadow-sm me-3" style="width: 40px; height: 40px; min-width: 40px;">
                                <i class="bi bi-award fs-5"></i>
                            </div>
                            <div>
                                <div class="small text-secondary fw-medium">Chuyên khoa</div>
                                <div class="fw-semibold text-dark">{{ $doctor->specialty }}</div>
                            </div>
                        </div>
                        
                        <div class="d-flex align-items-center bg-light p-3 rounded-3 border">
                            <div class="bg-white text-info rounded-circle d-flex align-items-center justify-content-center shadow-sm me-3" style="width: 40px; height: 40px; min-width: 40px;">
                                <i class="bi bi-envelope fs-5"></i>
                            </div>
                            <div>
                                <div class="small text-secondary fw-medium">Email</div>
                                <div class="fw-semibold text-dark">{{ optional($doctor->user)->email }}</div>
                            </div>
                        </div>

                        <div class="d-flex align-items-center bg-light p-3 rounded-3 border">
                            <div class="bg-white text-success rounded-circle d-flex align-items-center justify-content-center shadow-sm me-3" style="width: 40px; height: 40px; min-width: 40px;">
                                <i class="bi bi-telephone fs-5"></i>
                            </div>
                            <div>
                                <div class="small text-secondary fw-medium">Điện thoại</div>
                                <div class="fw-semibold text-dark">{{ optional($doctor->user)->phone }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Giờ làm việc -->
                    <h6 class="fw-semibold text-dark mb-3"><i class="bi bi-clock-history me-2 text-warning"></i>Giờ làm việc cố định</h6>
                    <div class="table-responsive border rounded-3">
                        <table class="table table-sm table-hover text-center mb-0 align-middle">
                            <thead class="table-light text-secondary small">
                                <tr>
                                    <th class="py-2 border-bottom-0">Ngày làm việc</th>
                                    <th class="py-2 border-bottom-0">Ca làm việc</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $workingHours = is_array($doctor->working_hours) ? $doctor->working_hours :
                                        json_decode($doctor->working_hours, true) ?? [];
                                @endphp
                                @forelse($workingHours as $schedule)
                                <tr>
                                    <td class="py-2 fw-medium text-dark">
                                        {{ __('Thứ') }}
                                        {{ $schedule['day'] == 'Monday' ? 'Hai' :
                                        ($schedule['day'] == 'Tuesday' ? 'Ba' :
                                            ($schedule['day'] == 'Wednesday' ? 'Tư' :
                                                ($schedule['day'] == 'Thursday' ? 'Năm' :
                                                    ($schedule['day'] == 'Friday' ? 'Sáu' :
                                                        ($schedule['day'] == 'Saturday' ? 'Bảy' : 'Chủ Nhật'))))) }}
                                    </td>
                                    <td class="py-2">
                                        <span class="badge bg-light text-dark border fw-medium px-3 py-1 rounded-pill">
                                            {{ $schedule['shift'] == 'morning' ? '08:00 - 12:00' : '14:00 - 18:00' }}
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="2" class="py-3 text-secondary small fst-italic">Chưa có lịch làm việc được phân công.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Chức năng lối tắt -->
        <div class="col-xl-5 col-lg-6 mt-4 mt-lg-0 d-flex flex-column gap-4">
            <div class="card border-0 shadow-sm rounded-4 text-center h-100 p-2 position-relative overflow-hidden" style="transition: transform 0.2s;">
                <div class="position-absolute top-0 end-0 opacity-10" style="margin-top: -20px; margin-right: -20px;">
                    <i class="bi bi-calendar-check" style="font-size: 8rem; color: #0d6efd;"></i>
                </div>
                <div class="card-body p-4 p-md-5 d-flex flex-column justify-content-center align-items-center position-relative z-1">
                    <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center mb-4" style="width: 80px; height: 80px;">
                        <i class="bi bi-calendar2-week fs-1"></i>
                    </div>
                    <h4 class="fw-bold mb-3" style="color: #0056b3;">Lịch khám bệnh</h4>
                    <p class="text-secondary mb-4">Xem danh sách bệnh nhân đã đặt lịch hẹn và lên hồ sơ bệnh án nhanh chóng.</p>
                    <a href="{{ url('/admindoctor/schedule') }}" class="btn btn-primary rounded-pill px-5 shadow-sm fw-medium">Truy cập ngay <i class="bi bi-arrow-right ms-2"></i></a>
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-4 text-center h-100 p-2 position-relative overflow-hidden" style="transition: transform 0.2s;">
                <div class="position-absolute top-0 end-0 opacity-10" style="margin-top: -20px; margin-right: -20px;">
                    <i class="bi bi-folder2-open" style="font-size: 8rem; color: #198754;"></i>
                </div>
                <div class="card-body p-4 p-md-5 d-flex flex-column justify-content-center align-items-center position-relative z-1">
                    <div class="bg-success bg-opacity-10 text-success rounded-circle d-flex align-items-center justify-content-center mb-4" style="width: 80px; height: 80px;">
                        <i class="bi bi-file-earmark-medical fs-1"></i>
                    </div>
                    <h4 class="fw-bold mb-3 text-success">Hồ Sơ Bệnh Nhân</h4>
                    <p class="text-secondary mb-4">Quản lý và cập nhật toàn bộ hồ sơ khám chữa bệnh, chẩn đoán của bệnh nhân.</p>
                    <a href="{{ url('/admindoctor/medicalrecords') }}" class="btn btn-success rounded-pill px-5 shadow-sm fw-medium">Xem chi tiết <i class="bi bi-arrow-right ms-2"></i></a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
