@extends('layouts.admin_layout')

@section('title', 'Lịch Khám Bệnh (Bác sĩ)')

@section('content')
@php
    $filter = $filter ?? request('filter', 'today');
    $search = $search ?? request('query', '');
    $filterStats = $filterStats ?? [
        'today' => 0,
        'needs_record' => 0,
        'upcoming' => 0,
        'completed' => 0,
        'all' => $appointments?->count() ?? 0,
    ];

    $filterItems = [
        'today' => ['label' => 'Hôm nay', 'icon' => 'bi-calendar-day'],
        'needs_record' => ['label' => 'Cần xử lý', 'icon' => 'bi-clipboard-pulse'],
        'upcoming' => ['label' => 'Sắp tới', 'icon' => 'bi-calendar-week'],
        'completed' => ['label' => 'Đã hoàn tất', 'icon' => 'bi-check2-circle'],
        'all' => ['label' => 'Tất cả', 'icon' => 'bi-list-ul'],
    ];

    $filterDescriptions = [
        'today' => 'Chỉ hiển thị lịch khám hôm nay chưa hoàn tất.',
        'needs_record' => 'Các ca đã được duyệt, đã đến ngày khám và chưa có hồ sơ bệnh án.',
        'upcoming' => 'Các lịch khám trong tương lai chưa hoàn tất.',
        'completed' => 'Các ca đã có hồ sơ bệnh án hoặc đã hoàn tất.',
        'all' => 'Toàn bộ lịch hẹn của bác sĩ.',
    ];

    $groupedAppointments = $appointments->groupBy(function ($appointment) {
        return \Carbon\Carbon::parse($appointment->appointment_date)->format('d/m/Y');
    });

    $recordOpeningTime = function ($appointment) {
        $date = \Carbon\Carbon::parse($appointment->appointment_date);

        return match ($appointment->shift) {
            'morning' => $date->setTime(8, 0),
            'afternoon' => $date->setTime(14, 0),
            default => $date->startOfDay(),
        };
    };

    $canCreateRecord = function ($appointment) use ($recordOpeningTime) {
        return $appointment->status === 'approved'
            && !$appointment->medicalRecord
            && now()->greaterThanOrEqualTo($recordOpeningTime($appointment));
    };

    $shiftBadge = function ($shift) {
        return match ($shift) {
            'morning' => ['Sáng', '08:00 - 12:00', 'bi-sun', 'info'],
            'afternoon' => ['Chiều', '14:00 - 18:00', 'bi-moon-stars', 'warning'],
            default => ['Không rõ', 'Chưa xác định', 'bi-clock', 'secondary'],
        };
    };
@endphp

<div class="container-fluid px-4 py-4">
    <div class="row mb-4">
        <div class="col-12 d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h3 class="mb-1" style="color: #0056b3; font-weight: 600;">Lịch Khám Bệnh</h3>
                <p class="text-secondary mb-0">{{ $filterDescriptions[$filter] ?? $filterDescriptions['today'] }}</p>
            </div>

            <form action="{{ route('doctor.schedule') }}" method="GET" class="d-flex align-items-center" style="max-width: 420px; width: 100%;">
                <input type="hidden" name="filter" value="{{ $filter }}">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0 rounded-start-pill text-secondary px-3">
                        <i class="bi bi-search"></i>
                    </span>
                    <input type="text" name="query" class="form-control border-start-0 rounded-end-pill focus-ring focus-ring-light py-2" value="{{ $search }}" placeholder="Tên bệnh nhân, số điện thoại, ngày khám..." autocomplete="off">
                </div>
            </form>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-3" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i><strong>Thành công!</strong> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Đóng"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm rounded-3" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i><strong>Lỗi!</strong> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Đóng"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-3">
            <div class="d-flex flex-wrap gap-2">
                @foreach($filterItems as $key => $item)
                    @php
                        $isActive = $filter === $key;
                        $params = ['filter' => $key];
                        if ($search !== '') {
                            $params['query'] = $search;
                        }
                    @endphp
                    <a href="{{ route('doctor.schedule', $params) }}"
                       class="btn btn-sm rounded-pill px-3 py-2 {{ $isActive ? 'btn-primary shadow-sm' : 'btn-light border text-secondary' }}">
                        <i class="bi {{ $item['icon'] }} me-1"></i>{{ $item['label'] }}
                        <span class="badge rounded-pill ms-1 {{ $isActive ? 'bg-white text-primary' : 'bg-secondary bg-opacity-10 text-secondary' }}">
                            {{ $filterStats[$key] ?? 0 }}
                        </span>
                    </a>
                @endforeach
            </div>
        </div>
    </div>

    @if($appointments->isEmpty())
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body text-center py-5">
                <div class="text-secondary opacity-50 mb-3"><i class="bi bi-calendar-x fs-1"></i></div>
                <h6 class="text-dark mb-2">Không có lịch hẹn phù hợp</h6>
                <p class="mb-0 text-secondary">Bạn có thể đổi bộ lọc sang “Tất cả” để xem toàn bộ lịch hẹn.</p>
            </div>
        </div>
    @else
        @foreach($groupedAppointments as $date => $dailyAppointments)
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header border-bottom py-3 px-4 rounded-top-4 d-flex justify-content-between align-items-center bg-primary bg-opacity-10">
                    <h5 class="mb-0 fw-bold text-primary">
                        <i class="bi bi-calendar-day me-2"></i>{{ $date }}
                    </h5>
                    <span class="badge bg-primary rounded-pill px-3 py-2 fw-medium">{{ count($dailyAppointments) }} ca khám</span>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="border-0 py-3 text-secondary fw-medium small px-4">Bệnh nhân</th>
                                    <th class="border-0 py-3 text-secondary fw-medium small">Thời gian hẹn</th>
                                    <th class="border-0 py-3 text-secondary fw-medium small w-25">Triệu chứng / Ghi chú</th>
                                    <th class="border-0 py-3 text-secondary fw-medium small text-center">Trạng thái</th>
                                    <th class="border-0 py-3 text-secondary fw-medium small text-end px-4">Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($dailyAppointments as $appointment)
                                    @php
                                        [$shiftLabel, $shiftTime, $shiftIcon, $shiftColor] = $shiftBadge($appointment->shift);
                                        $openingTime = $recordOpeningTime($appointment);
                                    @endphp
                                    <tr>
                                        <td class="py-3 px-4">
                                            <div class="fw-semibold text-dark">{{ optional($appointment->user)->name ?: 'Chưa có tên' }}</div>
                                            <div class="text-secondary small"><i class="bi bi-telephone-fill me-1 opacity-50"></i>{{ optional($appointment->user)->phone ?: 'Chưa có SĐT' }}</div>
                                        </td>
                                        <td class="py-3">
                                            <span class="badge bg-{{ $shiftColor }} bg-opacity-10 text-{{ $shiftColor }} border border-{{ $shiftColor }} rounded-pill fw-medium px-2 py-1">
                                                <i class="bi {{ $shiftIcon }} me-1"></i>{{ $shiftTime }}
                                            </span>
                                        </td>
                                        <td class="py-3">
                                            <div class="p-2 bg-light rounded-3 text-secondary small fst-italic" style="max-height: 64px; overflow-y: auto;">
                                                “{{ $appointment->description ?: 'Không có mô tả triệu chứng' }}”
                                            </div>
                                        </td>
                                        <td class="py-3 text-center">
                                            @if($appointment->medicalRecord)
                                                <span class="badge bg-primary bg-opacity-10 text-primary border border-primary rounded-pill px-2 py-1 fw-medium">
                                                    <i class="bi bi-file-earmark-medical me-1"></i>Đã có hồ sơ
                                                </span>
                                            @elseif($appointment->status === 'approved')
                                                <span class="badge bg-success bg-opacity-10 text-success border border-success rounded-pill px-2 py-1 fw-medium">
                                                    <i class="bi bi-check-circle me-1"></i>Đã duyệt
                                                </span>
                                            @elseif($appointment->status === 'pending')
                                                <span class="badge bg-warning bg-opacity-10 text-warning border border-warning rounded-pill px-2 py-1 fw-medium">
                                                    <i class="bi bi-hourglass-split me-1"></i>Chờ duyệt
                                                </span>
                                            @elseif($appointment->status === 'completed')
                                                <span class="badge bg-primary bg-opacity-10 text-primary border border-primary rounded-pill px-2 py-1 fw-medium">
                                                    <i class="bi bi-check2-circle me-1"></i>Đã hoàn tất
                                                </span>
                                            @else
                                                <span class="badge bg-danger bg-opacity-10 text-danger border border-danger rounded-pill px-2 py-1 fw-medium">
                                                    <i class="bi bi-x-circle me-1"></i>Đã hủy / Từ chối
                                                </span>
                                            @endif
                                        </td>
                                        <td class="py-3 text-end px-4">
                                            @if($appointment->medicalRecord)
                                                <a href="{{ route('admindoctor.medicalrecords.index', ['edit_id' => $appointment->medicalRecord->id]) }}"
                                                   class="btn btn-sm btn-light border text-primary rounded-pill fw-medium px-3">
                                                    <i class="bi bi-pencil-square me-1"></i>Xem/Sửa hồ sơ
                                                </a>
                                            @elseif($canCreateRecord($appointment))
                                                <a href="{{ route('admindoctor.medicalrecords.create', ['appointment_id' => $appointment->id]) }}"
                                                   class="btn btn-sm btn-primary rounded-pill shadow-sm fw-medium px-3">
                                                    <i class="bi bi-file-earmark-medical me-1"></i>Tạo hồ sơ
                                                </a>
                                            @elseif($appointment->status === 'approved')
                                                <button class="btn btn-sm btn-light border text-secondary rounded-pill fw-medium px-3" disabled title="Có thể tạo hồ sơ từ {{ $openingTime->format('H:i d/m/Y') }}">
                                                    <i class="bi bi-clock me-1"></i>Chưa đến giờ khám
                                                </button>
                                            @elseif($appointment->status === 'pending')
                                                <button class="btn btn-sm btn-light border text-secondary rounded-pill fw-medium px-3" disabled>
                                                    Đang chờ Admin duyệt
                                                </button>
                                            @else
                                                <button class="btn btn-sm btn-light border text-secondary rounded-pill fw-medium px-3" disabled>
                                                    Không thể thao tác
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
</div>
@endsection
