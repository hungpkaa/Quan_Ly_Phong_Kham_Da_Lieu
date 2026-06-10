@extends('layouts.admin_layout')

@section('title', 'Quản lý Bệnh Nhân (Bác sĩ)')

@section('content')
<div class="container-fluid px-4 py-4">
    <!-- Header Page -->
    <div class="row mb-4">
        <div class="col-12">
            <h3 class="mb-1" style="color: #0056b3; font-weight: 600;">Bệnh Nhân Của Tôi</h3>
            <p class="text-secondary mb-0">Danh sách các bệnh nhân đã đăng ký khám với bạn.</p>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-header bg-white border-bottom py-3 px-4 rounded-top-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
            <h6 class="mb-0" style="color: #0056b3; font-weight: 600;">
                <i class="bi bi-people-fill me-2"></i> Danh sách bệnh nhân chờ khám
            </h6>
            <span class="badge bg-primary bg-opacity-10 text-primary border border-primary rounded-pill px-3 py-2 fw-medium">Tổng số: {{ $patients->count() }} bệnh nhân</span>
        </div>
        
        <div class="card-body p-0">
            @if($patients->isEmpty())
                <div class="text-center py-5">
                    <div class="text-secondary opacity-50 mb-3"><i class="bi bi-emoji-smile fs-1"></i></div>
                    <p class="mb-0 text-secondary">Hiện tại bạn chưa có lịch hẹn khám nào.</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="border-0 py-3 text-secondary fw-medium small text-center" style="width: 60px;">#</th>
                                <th class="border-0 py-3 text-secondary fw-medium small">Thông Tin Bệnh Nhân</th>
                                <th class="border-0 py-3 text-secondary fw-medium small">Liên hệ</th>
                                <th class="border-0 py-3 text-secondary fw-medium small">Căn Cước (CCCD)</th>
                                <th class="border-0 py-3 text-secondary fw-medium small text-center">Ngày Hẹn</th>
                                <th class="border-0 py-3 text-secondary fw-medium small w-25">Triệu chứng / Ghi chú</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($patients as $appointment)
                            <tr>
                                <td class="py-3 text-center text-secondary">{{ $loop->iteration }}</td>
                                <td class="py-3">
                                    <div class="fw-semibold text-dark">{{ optional($appointment->user)->name }}</div>
                                    <div class="text-secondary small">Tuổi: {{ optional($appointment->user)->age }}</div>
                                </td>
                                <td class="py-3">
                                    <div class="text-dark small mb-1"><i class="bi bi-telephone-fill text-secondary me-2"></i>{{ optional($appointment->user)->phone }}</div>
                                    <div class="text-dark small"><i class="bi bi-envelope-fill text-secondary me-2"></i>{{ optional($appointment->user)->email }}</div>
                                </td>
                                <td class="py-3 text-dark small fw-medium">
                                    {{ optional($appointment->user)->cccd ?: '---' }}
                                </td>
                                <td class="py-3 text-center">
                                    <span class="badge bg-info bg-opacity-10 text-info border border-info rounded-pill fw-medium px-2 py-1"><i class="bi bi-calendar2-event me-1"></i>{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('d/m/Y') }}</span>
                                </td>
                                <td class="py-3">
                                    <div class="p-2 bg-light rounded-3 text-secondary small" style="max-height: 60px; overflow-y: auto;">
                                        {{ $appointment->description ?: 'Không có ghi chú' }}
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection