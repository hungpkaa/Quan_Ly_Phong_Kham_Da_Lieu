@extends('layouts.admin_layout')

@section('title', 'Quản lý Bệnh Nhân')

@section('content')
<div class="container-fluid px-4 py-4">
    <!-- Header Page -->
    <div class="row mb-4">
        <div class="col-12">
            <h3 class="mb-1" style="color: #0056b3; font-weight: 600;">Danh Sách Bệnh Nhân</h3>
            <p class="text-secondary mb-0">Quản lý và theo dõi thông tin người dùng có tài khoản bệnh nhân.</p>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-header bg-white border-bottom py-3 px-4 rounded-top-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
            <h6 class="mb-0" style="color: #0056b3; font-weight: 600;">
                <i class="bi bi-people-fill me-2"></i> Danh sách tất cả bệnh nhân
            </h6>
            <span class="badge bg-primary bg-opacity-10 text-primary border border-primary rounded-pill px-3 py-2 fw-medium">Tổng số: {{ $patients->total() }} bệnh nhân</span>
        </div>
        
        <div class="card-body p-0">
            @if($patients->isEmpty())
                <div class="text-center py-5">
                    <div class="text-secondary opacity-50 mb-3"><i class="bi bi-people fs-1"></i></div>
                    <p class="mb-0 text-secondary">Chưa có bệnh nhân nào đăng ký tài khoản.</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="border-0 py-3 text-secondary fw-medium small text-center" style="width: 60px;">#</th>
                                <th class="border-0 py-3 text-secondary fw-medium small">Bệnh Nhân</th>
                                <th class="border-0 py-3 text-secondary fw-medium small">Liên hệ</th>
                                <th class="border-0 py-3 text-secondary fw-medium small">Ngày tham gia</th>
                                <th class="border-0 py-3 text-secondary fw-medium small text-center">Tổng số lần khám</th>
                                <th class="border-0 py-3 text-secondary fw-medium small text-center">Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($patients as $patient)
                            <tr>
                                <td class="py-3 text-center text-secondary">{{ $loop->iteration + ($patients->currentPage() - 1) * $patients->perPage() }}</td>
                                <td class="py-3">
                                    <div class="fw-semibold text-dark">{{ $patient->name }}</div>
                                    <div class="text-secondary small">ID: #{{ $patient->id }}</div>
                                </td>
                                <td class="py-3">
                                    <div class="text-dark small mb-1"><i class="bi bi-telephone-fill text-secondary me-2"></i>{{ $patient->phone ?: '---' }}</div>
                                    <div class="text-dark small"><i class="bi bi-envelope-fill text-secondary me-2"></i>{{ $patient->email }}</div>
                                </td>
                                <td class="py-3 text-secondary small">
                                    <i class="bi bi-calendar2-check text-primary me-1"></i> {{ $patient->created_at->format('d/m/Y') }}
                                </td>
                                <td class="py-3 text-center">
                                    <span class="badge bg-info bg-opacity-10 text-info border border-info rounded-pill px-3 py-1">
                                        {{ $patient->medical_records_count }} hồ sơ
                                    </span>
                                </td>
                                <td class="py-3 text-center">
                                    <a href="{{ route('admin.medicalrecords.index', ['search' => $patient->phone]) }}" class="btn btn-sm btn-light border shadow-sm text-primary" title="Xem hồ sơ bệnh án">
                                        <i class="bi bi-file-earmark-medical"></i> Lịch sử khám
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
        
        @if($patients->hasPages())
        <div class="card-footer bg-white border-top py-3">
            <div class="d-flex justify-content-end">
                {{ $patients->links('pagination::bootstrap-4') }}
            </div>
        </div>
        @endif
    </div>
</div>
@endsection