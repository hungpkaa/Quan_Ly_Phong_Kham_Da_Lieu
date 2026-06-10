@extends('layouts.admin_layout')

@section('title', 'Hỗ Trợ Bệnh Nhân')

@section('content')
<div class="container-fluid px-4 py-4">
    <!-- Header Page -->
    <div class="row mb-4">
        <div class="col-12">
            <h3 class="mb-1" style="color: #0056b3; font-weight: 600;">Hỗ Trợ Bệnh Nhân</h3>
            <p class="text-secondary mb-0">Quản lý và phản hồi các yêu cầu cần hỗ trợ, tư vấn từ khách hàng.</p>
        </div>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-3" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i> <strong>Thành công!</strong> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif
    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm rounded-3" role="alert">
        <i class="bi bi-exclamation-triangle-fill me-2"></i> <strong>Lỗi!</strong> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="row">
        <div class="col-12">
            <!-- Danh sách Yêu cầu -->
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-white border-bottom py-3 px-4 rounded-top-4 d-flex justify-content-between align-items-center">
                    <h6 class="mb-0" style="color: #0056b3; font-weight: 600;">
                        <i class="bi bi-envelope-paper me-2"></i> Danh Sách Yêu Cầu Gần Đây
                    </h6>
                    <span class="badge bg-primary rounded-pill">{{ $supports->count() }} yêu cầu</span>
                </div>
                
                <div class="card-body p-0">
                    @if($supports->isEmpty())
                        <div class="text-center py-5">
                            <div class="text-secondary opacity-50 mb-3"><i class="bi bi-inbox fs-1"></i></div>
                            <p class="mb-0 text-secondary">Tuyệt vời! Không có yêu cầu hỗ trợ nào đang tồn đọng.</p>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="border-0 py-3 text-secondary fw-medium small text-center" style="width: 60px;">#</th>
                                        <th class="border-0 py-3 text-secondary fw-medium small">Thông tin người gửi</th>
                                        <th class="border-0 py-3 text-secondary fw-medium small">Liên hệ</th>
                                        <th class="border-0 py-3 text-secondary fw-medium small w-50">Nội dung cần hỗ trợ</th>
                                        <th class="border-0 py-3 text-secondary fw-medium small text-end px-4">Hành động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($supports as $support)
                                        <tr>
                                            <td class="py-3 text-center text-secondary">{{ $loop->iteration }}</td>
                                            <td class="py-3">
                                                <div class="fw-semibold text-dark">{{ $support->name }}</div>
                                                <div class="text-secondary small">Tuổi: {{ $support->age ?: 'Chưa cung cấp' }}</div>
                                            </td>
                                            <td class="py-3">
                                                <div class="text-dark small mb-1"><i class="bi bi-telephone-fill text-secondary me-2"></i>{{ $support->phone ?: '---' }}</div>
                                                <div class="text-dark small"><i class="bi bi-envelope-fill text-secondary me-2"></i>{{ $support->email ?: '---' }}</div>
                                            </td>
                                            <td class="py-3">
                                                <div class="p-3 bg-light rounded-3 border text-secondary small fst-italic">
                                                    "{{ $support->message }}"
                                                </div>
                                            </td>
                                            <td class="py-3 text-end px-4">
                                                <div class="d-flex justify-content-end gap-2">
                                                    <a href="mailto:{{ $support->email }}" class="btn btn-sm btn-light border text-primary rounded-circle" title="Gửi Email phản hồi" {{ !$support->email ? 'disabled' : '' }}>
                                                        <i class="bi bi-reply-fill"></i>
                                                    </a>
                                                    <form action="{{ route('admin.supports.destroy', $support->id) }}" method="POST" class="m-0">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-light border text-danger rounded-circle" 
                                                            onclick="return confirm('Bạn đã xử lý xong yêu cầu này và muốn xóa nó khỏi danh sách?')"
                                                            title="Đánh dấu đã xử lý / Xóa">
                                                            <i class="bi bi-check2-all"></i>
                                                        </button>
                                                    </form>
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
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
          return new bootstrap.Tooltip(tooltipTriggerEl)
        })
    });
</script>
@endpush
@endsection
