@extends('layouts.admin_layout')

@section('title', 'Admin Dashboard')

@section('content')
<div class="container-fluid py-2">
    <div class="row mb-4">
        <div class="col-12 d-flex justify-content-between align-items-end">
            <div>
                <h3 class="mb-1" style="color: #0056b3; font-weight: 600;">Tổng quan hệ thống</h3>
                <p class="text-secondary mb-0">Xin chào {{ Auth::user()->name ?? 'Admin' }}! Dưới đây là tóm tắt tình hình hoạt động.</p>
            </div>
            <div class="text-end d-flex align-items-center gap-2">
                <span class="badge bg-light text-secondary border px-3 py-2" style="font-weight: 500;">
                    <i class="bi bi-calendar3 me-1"></i> Dữ liệu ngày {{ date('d/m/Y') }}
                </span>
                <select class="form-select form-select-sm bg-light border text-secondary" style="width: auto; height: 33px; font-weight: 500; outline: none; box-shadow: none;" onchange="window.location.href='?filter=' + this.value">
                    <option value="today" {{ (isset($filter) && $filter == 'today') ? 'selected' : '' }}>Hôm nay</option>
                    <option value="7days" {{ (isset($filter) && $filter == '7days') ? 'selected' : '' }}>7 ngày qua</option>
                    <option value="this_month" {{ (isset($filter) && $filter == 'this_month') ? 'selected' : '' }}>Tháng này</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Quick Stats Row -->
    <div class="row g-4 mb-4">
        <!-- Stat 1 -->
        <div class="col-md-3">
            <div class="card border shadow-sm h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h6 class="text-secondary mb-0" style="font-size: 14px;">Tổng bệnh nhân</h6>
                        <div class="text-primary rounded p-2" style="background-color: #f0f7ff;">
                            <i class="bi bi-people fs-5"></i>
                        </div>
                    </div>
                    <h3 class="mb-1 text-dark" style="font-weight: 600;">{{ number_format($totalPatients) }}</h3>
                    <small class="text-success" style="font-size: 13px;"><i class="bi bi-arrow-up-short"></i>+12 bệnh nhân mới (tháng này)</small>
                </div>
            </div>
        </div>

        <!-- Stat 2 -->
        <div class="col-md-3">
            <div class="card border shadow-sm h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h6 class="text-secondary mb-0" style="font-size: 14px;">Lịch hẹn hôm nay</h6>
                        <div class="text-success rounded p-2" style="background-color: #ebf9f1;">
                            <i class="bi bi-calendar-check fs-5"></i>
                        </div>
                    </div>
                    <h3 class="mb-1 text-dark" style="font-weight: 600;">{{ number_format($appointmentsToday) }}</h3>
                    <small class="text-warning text-dark" style="font-size: 13px;"><i class="bi bi-exclamation-circle-fill me-1"></i>3 lịch đang chờ xác nhận</small>
                </div>
            </div>
        </div>

        <!-- Stat 3 -->
        <div class="col-md-3">
            <div class="card border shadow-sm h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h6 class="text-secondary mb-0" style="font-size: 14px;">Tổng bác sĩ</h6>
                        <div class="text-warning rounded p-2" style="background-color: #fff8e6;">
                            <i class="bi bi-person-badge fs-5"></i>
                        </div>
                    </div>
                    <h3 class="mb-1 text-dark" style="font-weight: 600;">{{ number_format($totalDoctors) }}</h3>
                    <small class="text-secondary" style="font-size: 13px;">12/15 bác sĩ đang hoạt động</small>
                </div>
            </div>
        </div>

        <!-- Stat 4 -->
        <div class="col-md-3">
            <div class="card border shadow-sm h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h6 class="text-secondary mb-0" style="font-size: 14px;">Doanh thu tháng</h6>
                        <div class="text-danger rounded p-2" style="background-color: #fcebeb;">
                            <i class="bi bi-wallet2 fs-5"></i>
                        </div>
                    </div>
                    <h3 class="mb-1 text-dark" style="font-weight: 600;">{{ $revenueFormatted == 0 ? '0' : $revenueFormatted }} VNĐ</h3>
                    <small class="text-success" style="font-size: 13px;"><i class="bi bi-arrow-up-short"></i>Tăng 8,5% so với tháng trước</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row g-4 mb-4">
        <div class="col-lg-6">
            <div class="card border shadow-sm h-100">
                <div class="card-header bg-white border-bottom py-3 px-4">
                    <h6 class="mb-0" style="color: #0056b3; font-weight: 600;">Thống kê Lịch hẹn (7 ngày qua)</h6>
                </div>
                <div class="card-body px-4 py-4">
                    <canvas id="appointmentsChart" height="250"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card border shadow-sm h-100">
                <div class="card-header bg-white border-bottom py-3 px-4">
                    <h6 class="mb-0" style="color: #0056b3; font-weight: 600;">Biểu đồ Doanh thu (6 tháng qua)</h6>
                </div>
                <div class="card-body px-4 py-4">
                    <canvas id="revenueChart" height="250"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Dashboard Area -->
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card border shadow-sm h-100">
                <div class="card-header bg-white border-bottom py-3 px-4">
                    <h6 class="mb-0" style="color: #0056b3; font-weight: 600;">Hoạt động gần đây</h6>
                </div>
                <div class="card-body p-0">
                    @if($recentAppointments->isEmpty())
                        <div class="text-center text-secondary py-5">
                            <i class="bi bi-inbox fs-1 d-block mb-3 opacity-50"></i>
                            <p class="mb-0">Chưa có lịch hẹn nào gần đây.</p>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light text-secondary" style="font-size: 13px;">
                                    <tr>
                                        <th class="ps-4">Bệnh nhân</th>
                                        <th>Bác sĩ</th>
                                        <th>Thời gian</th>
                                        <th>Trạng thái</th>
                                        <th class="text-end pe-4">Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentAppointments as $appointment)
                                    <tr>
                                        <td class="ps-4">
                                            <p class="mb-0 text-dark fw-bold" style="font-size: 14px;">{{ optional($appointment->user)->name }}</p>
                                            <small class="text-secondary opacity-75">{{ optional($appointment->user)->phone }}</small>
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark border px-2 py-1" style="font-weight: 500;">
                                                {{ optional(optional($appointment->doctor)->user)->name ?? 'N/A' }}
                                            </span>
                                        </td>
                                        <td>
                                            <p class="mb-0 text-dark" style="font-size: 14px;">{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('H:i') }}</p>
                                            <small class="text-secondary">{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('d/m/Y') }}</small>
                                        </td>
                                        <td>
                                            @if($appointment->status == 'approved')
                                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-2 py-1">Đã xác nhận</span>
                                            @elseif($appointment->status == 'pending')
                                                <span class="badge bg-warning-subtle text-warning border border-warning-subtle rounded-pill px-2 py-1">Chờ xác nhận</span>
                                            @elseif($appointment->status == 'rejected')
                                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-2 py-1">Đã hủy</span>
                                            @else
                                                <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2 py-1">Hoàn thành</span>
                                            @endif
                                        </td>
                                        <td class="text-end pe-4">
                                            <a href="{{ url('/admin/appointments?search=' . urlencode(optional($appointment->user)->name)) }}" class="btn btn-sm btn-light border text-primary">Xem</a>
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
        <div class="col-lg-4">
            <div class="card border shadow-sm h-100">
                <div class="card-header bg-white border-bottom py-3 px-4">
                    <h6 class="mb-0" style="color: #0056b3; font-weight: 600;">Yêu cầu cần xử lý</h6>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        @if($pendingAppointmentsCount > 0)
                        <a href="{{ url('/admin/appointments') }}" class="list-group-item list-group-item-action px-4 py-3 d-flex align-items-center justify-content-between text-decoration-none">
                            <div class="d-flex align-items-center">
                                <div class="text-warning me-3">
                                    <i class="bi bi-clock-history fs-5"></i>
                                </div>
                                <div>
                                    <p class="mb-0 text-dark" style="font-size: 14px; font-weight: 500;">Lịch hẹn chờ xác nhận</p>
                                </div>
                            </div>
                            <span class="badge bg-warning text-dark rounded-pill">{{ $pendingAppointmentsCount }}</span>
                        </a>
                        @endif
                        <a href="{{ url('/admin/invoices') }}" class="list-group-item list-group-item-action px-4 py-3 d-flex align-items-center justify-content-between text-decoration-none">
                            <div class="d-flex align-items-center">
                                <div class="text-danger me-3">
                                    <i class="bi bi-receipt fs-5"></i>
                                </div>
                                <div>
                                    <p class="mb-0 text-dark" style="font-size: 14px; font-weight: 500;">Hóa đơn chưa thanh toán</p>
                                </div>
                            </div>
                            <span class="badge bg-danger rounded-pill">1</span>
                        </a>
                        <a href="{{ url('/admin/patients') }}" class="list-group-item list-group-item-action px-4 py-3 d-flex align-items-center justify-content-between text-decoration-none">
                            <div class="d-flex align-items-center">
                                <div class="text-primary me-3">
                                    <i class="bi bi-person-lines-fill fs-5"></i>
                                </div>
                                <div>
                                    <p class="mb-0 text-dark" style="font-size: 14px; font-weight: 500;">Hồ sơ bệnh nhân chưa hoàn thiện</p>
                                </div>
                            </div>
                            <span class="badge bg-primary rounded-pill">2</span>
                        </a>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Dữ liệu biểu đồ Lịch hẹn
    const apptData = @json($appointmentsChartData);
    const apptLabels = apptData.map(item => {
        const d = new Date(item.date);
        return d.toLocaleDateString('vi-VN', { day: '2-digit', month: '2-digit' });
    });
    const apptCounts = apptData.map(item => item.count);

    const ctxAppt = document.getElementById('appointmentsChart').getContext('2d');
    new Chart(ctxAppt, {
        type: 'bar',
        data: {
            labels: apptLabels.length > 0 ? apptLabels : ['Chưa có dữ liệu'],
            datasets: [{
                label: 'Số lịch hẹn',
                data: apptCounts.length > 0 ? apptCounts : [0],
                backgroundColor: '#0d6efd',
                borderRadius: 4,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { precision: 0 }
                }
            },
            plugins: {
                legend: { display: false }
            }
        }
    });

    // Dữ liệu biểu đồ Doanh thu
    const revData = @json($revenueChartData);
    const revLabels = revData.map(item => `Tháng ${item.month}/${item.year}`);
    const revTotals = revData.map(item => item.total);

    const ctxRev = document.getElementById('revenueChart').getContext('2d');
    new Chart(ctxRev, {
        type: 'line',
        data: {
            labels: revLabels.length > 0 ? revLabels : ['Chưa có dữ liệu'],
            datasets: [{
                label: 'Doanh thu (VNĐ)',
                data: revTotals.length > 0 ? revTotals : [0],
                borderColor: '#198754',
                backgroundColor: 'rgba(25, 135, 84, 0.1)',
                borderWidth: 2,
                fill: true,
                tension: 0.3,
                pointBackgroundColor: '#198754'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            },
            plugins: {
                legend: { display: false }
            }
        }
    });
});
</script>
@endpush
@endsection
