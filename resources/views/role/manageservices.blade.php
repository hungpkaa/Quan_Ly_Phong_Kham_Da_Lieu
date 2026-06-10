@extends('layouts.admin_layout')

@section('title', 'Quản Lý Dịch Vụ')

@section('content')
<div class="container-fluid px-4 py-4">
    <!-- Header Page -->
    <div class="row mb-4">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <div>
                <h3 class="mb-1" style="color: #0056b3; font-weight: 600;">Quản lý Dịch vụ</h3>
                <p class="text-secondary mb-0">Quản lý các loại dịch vụ khám chữa bệnh hiện có tại phòng khám.</p>
            </div>
            <!-- Nút Thêm Mới -->
            <div>
                <button class="btn btn-primary rounded-pill px-4 shadow-sm" style="font-weight: 500;" data-bs-toggle="modal" data-bs-target="#addServiceModal">
                    <i class="bi bi-plus-lg me-1"></i> Thêm Dịch Vụ
                </button>
            </div>
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
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-white border-bottom py-3 px-4 rounded-top-4 d-flex justify-content-between align-items-center">
                    <h6 class="mb-0" style="color: #0056b3; font-weight: 600;">
                        <i class="bi bi-list-ul me-2"></i> Danh sách Dịch vụ
                    </h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0 text-center">
                            <thead class="bg-light">
                                <tr>
                                    <th class="border-0 py-3 text-secondary fw-medium small" style="width: 80px; border-top-left-radius: 8px;">ID</th>
                                    <th class="border-0 py-3 text-secondary fw-medium small text-start">Tên Dịch Vụ</th>
                                    <th class="border-0 py-3 text-secondary fw-medium small">Hình Ảnh Minh Họa</th>
                                    <th class="border-0 py-3 text-secondary fw-medium small text-end px-4" style="border-top-right-radius: 8px;">Hành Động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($services as $service)
                                <tr>
                                    <td class="py-3 text-secondary">#{{ $service->id }}</td>
                                    <td class="py-3 text-start">
                                        <span class="fw-semibold text-dark">{{ $service->name }}</span>
                                    </td>
                                    <td class="py-3">
                                        <div class="d-flex justify-content-center">
                                            <img src="{{ asset($service->image) }}" width="80" height="80" style="object-fit: cover;" 
                                                onerror="this.onerror=null; this.src='{{ asset('img/default.jpg') }}';" 
                                                class="rounded-3 shadow-sm border">
                                        </div>
                                    </td>
                                    <td class="py-3 text-end px-4">
                                        <div class="d-flex justify-content-end gap-2">
                                            <!-- Nút sửa -->
                                            <button class="btn btn-sm btn-light border text-primary rounded-circle edit-btn" 
                                                    data-id="{{ $service->id }}" 
                                                    data-name="{{ $service->name }}" 
                                                    data-image="{{ $service->image }}" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#editServiceModal"
                                                    title="Chỉnh sửa">
                                                <i class="bi bi-pencil-square"></i>
                                            </button>
                                            <!-- Nút xóa -->
                                            <form action="{{ route('services.destroy', $service->id) }}" method="POST" class="m-0">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-light border text-danger rounded-circle" 
                                                    onclick="return confirm('Bạn có chắc chắn muốn xóa dịch vụ {{ $service->name }}?')"
                                                    title="Xóa">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-5">
                                        <div class="text-secondary opacity-50 mb-3"><i class="bi bi-box-seam fs-1"></i></div>
                                        <p class="mb-0">Chưa có dịch vụ nào.</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Thêm Dịch Vụ -->
<div class="modal fade" id="addServiceModal" tabindex="-1" aria-labelledby="addServiceModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-4">
            <div class="modal-header border-bottom py-3 px-4 bg-light rounded-top-4">
                <h5 class="modal-title fw-semibold" id="addServiceModalLabel" style="color: #0056b3;">
                    <i class="bi bi-plus-circle me-2"></i>Thêm Dịch Vụ Mới
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('services.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label text-secondary fw-medium small mb-1">Tên Dịch Vụ <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control bg-light border-0 focus-ring focus-ring-primary py-2" placeholder="Nhập tên dịch vụ" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-secondary fw-medium small mb-1">Hình Ảnh Minh Họa <span class="text-danger">*</span></label>
                        <input type="file" name="image" class="form-control bg-light border-0 focus-ring focus-ring-primary py-2" accept="image/*" required>
                    </div>
                </div>
                <div class="modal-footer border-top px-4 py-3 bg-light rounded-bottom-4">
                    <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 shadow-sm">Lưu Dịch Vụ</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Sửa Dịch Vụ -->
<div class="modal fade" id="editServiceModal" tabindex="-1" aria-labelledby="editServiceModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-4">
            <div class="modal-header border-bottom py-3 px-4 bg-light rounded-top-4">
                <h5 class="modal-title fw-semibold text-warning-emphasis" id="editServiceModalLabel">
                    <i class="bi bi-pencil-square me-2"></i>Chỉnh Sửa Dịch Vụ
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editServiceForm" action="" method="POST" enctype="multipart/form-data">
                @csrf
                @method('POST')
                <div class="modal-body p-4">
                    <input type="hidden" name="service_id" id="editServiceId">
                    <div class="mb-3">
                        <label class="form-label text-secondary fw-medium small mb-1">Tên Dịch Vụ <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="editServiceName" class="form-control bg-light border-0 focus-ring focus-ring-warning py-2" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-secondary fw-medium small mb-1">Hình Ảnh (Để trống nếu không muốn đổi)</label>
                        <input type="file" name="image" class="form-control bg-light border-0 focus-ring focus-ring-warning py-2" accept="image/*">
                        <div class="mt-3 text-center bg-light rounded-3 p-2 border">
                            <span class="d-block small text-secondary mb-2">Ảnh hiện tại</span>
                            <img id="editServiceImagePreview" src="" width="100" height="100" style="object-fit: cover;" class="rounded-3 shadow-sm border">
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top px-4 py-3 bg-light rounded-bottom-4">
                    <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-warning rounded-pill px-4 shadow-sm text-dark fw-medium">Cập Nhật</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Xử lý khi nhấn nút "Sửa"
        document.querySelectorAll(".edit-btn").forEach(btn => {
            btn.addEventListener("click", function () {
                let id = this.getAttribute("data-id");
                let name = this.getAttribute("data-name");
                let image = this.getAttribute("data-image");

                document.getElementById("editServiceId").value = id;
                document.getElementById("editServiceName").value = name;
                document.getElementById("editServiceForm").action = `/services/${id}/update`;

                let imgPreview = document.getElementById("editServiceImagePreview");
                imgPreview.src = image ? `/${image}` : "/img/default.jpg";
            });
        });
        
        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
          return new bootstrap.Tooltip(tooltipTriggerEl)
        })
    });
</script>
@endpush
@endsection
