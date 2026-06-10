@extends('layouts.admin_layout')

@section('title', 'Theo Dõi Tiến Độ Điều Trị')

@section('content')
<div class="container-fluid p-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="h4 mb-1 text-primary fw-bold">Theo Dõi Tiến Độ Điều Trị</h2>
            <p class="text-secondary small mb-0">Quản lý hình ảnh và tiến độ điều trị của bệnh nhân</p>
        </div>
        <div>
            <form action="{{ route('admindoctor.progress.index') }}" method="GET" class="d-flex align-items-center">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0 text-secondary"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" class="form-control border-start-0 focus-ring focus-ring-light" placeholder="Tìm tên, SĐT..." value="{{ request('search') }}">
                </div>
            </form>
        </div>
    </div>

    <!-- Content -->
    @if($progresses->isEmpty())
        <div class="card border-0 shadow-sm rounded-4 mt-4">
            <div class="card-body p-5 text-center text-secondary">
                <i class="bi bi-images display-1 opacity-25 mb-3"></i>
                <h5 class="fw-medium">Không có dữ liệu tiến độ</h5>
                <p class="small mb-0">Hiện tại chưa có hình ảnh tiến độ nào từ bệnh nhân.</p>
            </div>
        </div>
    @else
        <div class="row g-4">
            @foreach($progresses as $prog)
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden">
                    <div class="position-relative" style="height: 200px;">
                        <img src="{{ asset('storage/' . $prog->image_path) }}" class="w-100 h-100 object-fit-cover" alt="Tiến độ" data-bs-toggle="modal" data-bs-target="#imageModal{{ $prog->id }}" style="cursor: pointer;">
                        <div class="position-absolute top-0 end-0 p-2">
                            <span class="badge bg-dark bg-opacity-75 rounded-pill shadow-sm"><i class="bi bi-calendar3 me-1"></i>{{ $prog->created_at->format('d/m/Y') }}</span>
                        </div>
                    </div>
                    <div class="card-body p-3">
                        <div class="mb-2">
                            <h6 class="mb-1 fw-bold text-dark text-truncate">{{ $prog->user->name ?? 'Bệnh nhân ẩn danh' }}</h6>
                            <div class="text-secondary small"><i class="bi bi-telephone-fill me-1"></i>{{ $prog->user->phone ?? 'Không có SĐT' }}</div>
                        </div>
                        <p class="text-secondary small mb-0 lh-sm line-clamp-2" title="{{ $prog->notes }}">{{ $prog->notes ?: 'Không có ghi chú' }}</p>
                    </div>
                </div>
            </div>

            <!-- Modal -->
            <div class="modal fade" id="imageModal{{ $prog->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content bg-transparent border-0">
                        <div class="modal-header border-0 justify-content-between p-3">
                            <form action="{{ route('admindoctor.progress.destroy', $prog->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa ảnh tiến độ này không?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger rounded-pill shadow-sm"><i class="bi bi-trash3 me-1"></i>Xóa ảnh</button>
                            </form>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body p-0 text-center">
                            <img src="{{ asset('storage/' . $prog->image_path) }}" class="img-fluid rounded-4 shadow" alt="Tiến độ">
                            <div class="mt-3 bg-white p-4 rounded-4 shadow text-start">
                                <h5 class="fw-bold mb-3 text-primary border-bottom pb-2">Thông tin cập nhật</h5>
                                <div class="row g-3">
                                    <div class="col-sm-6">
                                        <div class="text-secondary small">Bệnh nhân</div>
                                        <div class="fw-medium text-dark">{{ $prog->user->name ?? 'N/A' }}</div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="text-secondary small">Ngày cập nhật</div>
                                        <div class="fw-medium text-dark">{{ $prog->created_at->format('d/m/Y H:i') }}</div>
                                    </div>
                                    <div class="col-12">
                                        <div class="text-secondary small">Ghi chú của bệnh nhân</div>
                                        <div class="p-3 bg-light rounded-3 text-dark mt-1">{{ $prog->notes ?: 'Không có ghi chú' }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="mt-4 d-flex justify-content-end">
            {{ $progresses->links('pagination::bootstrap-4') }}
        </div>
    @endif
</div>

<style>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
@endsection
