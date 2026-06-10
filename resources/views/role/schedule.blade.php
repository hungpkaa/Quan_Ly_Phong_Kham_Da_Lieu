@extends('layouts.admin_layout')

@section('title', 'Lịch Khám Bệnh (Bác sĩ)')

@section('content')
<div class="container-fluid px-4 py-4">
    <!-- Header Page -->
    <div class="row mb-4">
        <div class="col-12 d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h3 class="mb-1" style="color: #0056b3; font-weight: 600;">Lịch Khám Bệnh</h3>
                <p class="text-secondary mb-0">Quản lý các ca đặt lịch khám và tạo hồ sơ bệnh án.</p>
            </div>
            
            <!-- Form tìm kiếm -->
            <form action="{{ route('appointments.search') }}" method="GET" class="d-flex align-items-center" style="max-width: 400px; width: 100%;">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0 rounded-start-pill text-secondary px-3">
                        <i class="bi bi-search"></i>
                    </span>
                    <input type="text" name="query" id="search" class="form-control border-start-0 rounded-end-pill focus-ring focus-ring-light py-2" placeholder="Tên BN, ngày khám hoặc trạng thái..." autocomplete="off">
                </div>
            </form>
        </div>
    </div>

    <!-- Hiển thị kết quả -->
    @if(isset($appointments))
        @if($appointments->isEmpty())
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body text-center py-5">
                    <div class="text-secondary opacity-50 mb-3"><i class="bi bi-calendar-x fs-1"></i></div>
                    <p class="mb-0 text-secondary">Không tìm thấy kết quả phù hợp hoặc bạn chưa có lịch hẹn nào.</p>
                </div>
            </div>
        @else
            @php
            $groupedAppointments = $appointments->groupBy(function ($appointment) {
                return \Carbon\Carbon::parse($appointment->appointment_date)->format('d/m/Y');
            });
            @endphp

            @foreach($groupedAppointments as $date => $dailyAppointments)
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header border-bottom py-3 px-4 rounded-top-4 d-flex justify-content-between align-items-center bg-primary bg-opacity-10">
                    <h5 class="mb-0 fw-bold text-primary">
                        <i class="bi bi-calendar-day me-2"></i> {{ $date }}
                    </h5>
                    <span class="badge bg-primary rounded-pill px-3 py-2 fw-medium">{{ count($dailyAppointments) }} ca khám</span>
                </div>
                
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="border-0 py-3 text-secondary fw-medium small px-4">Bệnh Nhân</th>
                                    <th class="border-0 py-3 text-secondary fw-medium small">Thời Gian Hẹn</th>
                                    <th class="border-0 py-3 text-secondary fw-medium small w-25">Triệu Chứng / Ghi Chú</th>
                                    <th class="border-0 py-3 text-secondary fw-medium small text-center">Trạng Thái</th>
                                    <th class="border-0 py-3 text-secondary fw-medium small text-end px-4">Hành Động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($dailyAppointments as $appointment)
                                <tr>
                                    <td class="py-3 px-4">
                                        <div class="fw-semibold text-dark">{{ optional($appointment->user)->name }}</div>
                                        <div class="text-secondary small"><i class="bi bi-telephone-fill me-1 opacity-50"></i>{{ optional($appointment->user)->phone }}</div>
                                    </td>
                                    <td class="py-3">
                                        @switch($appointment->shift)
                                        @case('morning')
                                        <span class="badge bg-info bg-opacity-10 text-info border border-info rounded-pill fw-medium px-2 py-1"><i class="bi bi-sun me-1"></i>08:00 - 12:00</span>
                                        @break
                                        @case('afternoon')
                                        <span class="badge bg-warning bg-opacity-10 text-warning border border-warning rounded-pill fw-medium px-2 py-1"><i class="bi bi-moon-stars me-1"></i>14:00 - 18:00</span>
                                        @break
                                        @default
                                        <span class="badge bg-secondary rounded-pill px-2 py-1">Không xác định</span>
                                        @endswitch
                                    </td>
                                    <td class="py-3">
                                        <div class="p-2 bg-light rounded-3 text-secondary small fst-italic" style="max-height: 60px; overflow-y: auto;">
                                            "{{ $appointment->description ?: 'Không có mô tả triệu chứng' }}"
                                        </div>
                                    </td>
                                    <td class="py-3 text-center">
                                        @if($appointment->status === 'approved')
                                            <span class="badge bg-success bg-opacity-10 text-success border border-success rounded-pill px-2 py-1 fw-medium"><i class="bi bi-check-circle me-1"></i>Đã duyệt</span>
                                        @elseif($appointment->status === 'pending')
                                            <span class="badge bg-warning bg-opacity-10 text-warning border border-warning rounded-pill px-2 py-1 fw-medium"><i class="bi bi-hourglass-split me-1"></i>Chờ duyệt</span>
                                        @else
                                            <span class="badge bg-danger bg-opacity-10 text-danger border border-danger rounded-pill px-2 py-1 fw-medium"><i class="bi bi-x-circle me-1"></i>Đã từ chối</span>
                                        @endif
                                    </td>
                                    <td class="py-3 text-end px-4">
                                        @if($appointment->status === 'approved')
                                            <a href="{{ route('admindoctor.medicalrecords.create', ['appointment_id' => $appointment->id]) }}"
                                                class="btn btn-sm btn-primary rounded-pill shadow-sm fw-medium px-3">
                                                <i class="bi bi-file-earmark-medical me-1"></i>Tạo Hồ Sơ Bệnh Án
                                            </a>
                                        @else
                                            <button class="btn btn-sm btn-light border text-secondary rounded-pill fw-medium px-3" disabled>
                                                {{ $appointment->status === 'pending' ? 'Đang chờ Admin duyệt' : 'Lịch khám bị hủy' }}
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endforeach
        @endif
    @else
        <div class="alert alert-info border-0 shadow-sm rounded-3">Vui lòng nhập thông tin để tìm kiếm lịch khám.</div>
    @endif
</div>
@endsection
