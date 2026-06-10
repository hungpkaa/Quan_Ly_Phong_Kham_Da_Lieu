@extends('layouts.app')

@section('title', 'Tài khoản của tôi')

@section('content')
<div class="container py-5">
    <div class="row g-4">
        <div class="col-12 text-center mb-4">
            <h2 class="fw-bold text-primary">Khu vực Bệnh Nhân</h2>
            <p class="text-secondary">Quản lý lịch khám, hồ sơ bệnh án và tiến độ điều trị của bạn</p>
        </div>
        
        <!-- Sidebar Navigation (Tabs) -->
        <div class="col-lg-3">
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body p-4 text-center border-bottom border-light">
                    <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                        <i class="bi bi-person fs-1"></i>
                    </div>
                    <h5 class="fw-bold mb-1">{{ $user->name }}</h5>
                    <p class="text-secondary small mb-0">{{ $user->email }}</p>
                </div>
                <div class="nav flex-column nav-pills p-2" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                    <button class="nav-link text-start active px-3 py-3 rounded-3 mb-1" id="v-pills-account-tab" data-bs-toggle="pill" data-bs-target="#v-pills-account" type="button" role="tab" aria-controls="v-pills-account" aria-selected="true">
                        <i class="bi bi-info-circle me-2"></i> Thông tin tài khoản
                    </button>
                    <button class="nav-link text-start px-3 py-3 rounded-3 mb-1" id="v-pills-appointments-tab" data-bs-toggle="pill" data-bs-target="#v-pills-appointments" type="button" role="tab" aria-controls="v-pills-appointments" aria-selected="false">
                        <i class="bi bi-calendar-event me-2"></i> Lịch sử đặt khám
                    </button>
                    <button class="nav-link text-start px-3 py-3 rounded-3 mb-1" id="v-pills-records-tab" data-bs-toggle="pill" data-bs-target="#v-pills-records" type="button" role="tab" aria-controls="v-pills-records" aria-selected="false">
                        <i class="bi bi-file-medical me-2"></i> Hồ sơ bệnh án
                    </button>
                    <button class="nav-link text-start px-3 py-3 rounded-3 mb-1" id="v-pills-progress-tab" data-bs-toggle="pill" data-bs-target="#v-pills-progress" type="button" role="tab" aria-controls="v-pills-progress" aria-selected="false">
                        <i class="bi bi-camera me-2"></i> Cập nhật tình trạng da
                    </button>
                </div>
            </div>
        </div>

        <!-- Content Area -->
        <div class="col-lg-9">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-4" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm rounded-4" role="alert">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="tab-content" id="v-pills-tabContent">
                
                <!-- Tab 1: Thông tin tài khoản -->
                <div class="tab-pane fade show active" id="v-pills-account" role="tabpanel" aria-labelledby="v-pills-account-tab" tabindex="0">
                    <div class="card border-0 shadow-sm rounded-4 h-100">
                        <div class="card-header bg-white border-0 pt-4 pb-0 px-4">
                            <h5 class="fw-bold text-primary mb-0"><i class="bi bi-info-circle me-2"></i>Thông tin tài khoản</h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <label class="text-secondary small fw-medium mb-1">Họ tên</label>
                                    <div class="p-3 bg-light rounded-3 fw-medium">{{ $user->name }}</div>
                                </div>
                                <div class="col-md-6">
                                    <label class="text-secondary small fw-medium mb-1">Email</label>
                                    <div class="p-3 bg-light rounded-3 fw-medium">{{ $user->email }}</div>
                                </div>
                                <div class="col-md-6">
                                    <label class="text-secondary small fw-medium mb-1">Số điện thoại</label>
                                    <div class="p-3 bg-light rounded-3 fw-medium">{{ $user->phone ?? 'Chưa cập nhật' }}</div>
                                </div>
                                <div class="col-md-6">
                                    <label class="text-secondary small fw-medium mb-1">Vai trò</label>
                                    <div class="p-3 bg-light rounded-3 fw-medium">Bệnh nhân</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tab 2: Lịch sử đặt khám -->
                <div class="tab-pane fade" id="v-pills-appointments" role="tabpanel" aria-labelledby="v-pills-appointments-tab" tabindex="0">
                    <div class="card border-0 shadow-sm rounded-4 h-100">
                        <div class="card-header bg-white border-0 pt-4 pb-0 px-4">
                            <h5 class="fw-bold text-primary mb-0"><i class="bi bi-calendar-event me-2"></i>Lịch sử đặt khám</h5>
                        </div>
                        <div class="card-body p-4">
                            @if($appointments->isEmpty())
                                <div class="text-center py-5 text-secondary">
                                    <i class="bi bi-calendar-x fs-1 opacity-50 mb-3 d-block"></i>
                                    Bạn chưa có lịch hẹn nào.
                                    <div class="mt-3">
                                        <a href="{{ route('appointments.create') }}" class="btn btn-primary rounded-pill px-4">Đặt lịch khám ngay</a>
                                    </div>
                                </div>
                            @else
                                <div class="table-responsive">
                                    <table class="table table-borderless align-middle mb-0">
                                        <thead class="bg-light rounded-3">
                                            <tr>
                                                <th class="py-3 px-4 rounded-start-3 text-secondary fw-semibold">Ngày hẹn</th>
                                                <th class="py-3 px-4 text-secondary fw-semibold">Dịch vụ / Bác sĩ</th>
                                                <th class="py-3 px-4 text-secondary fw-semibold">Ghi chú</th>
                                                <th class="py-3 px-4 rounded-end-3 text-secondary fw-semibold">Trạng thái</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($appointments as $apt)
                                            <tr class="border-bottom border-light">
                                                <td class="px-4 py-3 text-dark fw-medium">{{ \Carbon\Carbon::parse($apt->appointment_date)->format('d/m/Y') }}</td>
                                                <td class="px-4 py-3">
                                                    <div class="fw-medium text-dark">{{ $apt->doctor && $apt->doctor->specialty ? 'Khám ' . $apt->doctor->specialty : 'Khám tổng quát' }}</div>
                                                    @if($apt->doctor)
                                                        <div class="text-secondary small">BS. {{ $apt->doctor->user->name }}</div>
                                                    @endif
                                                </td>
                                                <td class="px-4 py-3 text-secondary small">{{ $apt->notes ?: 'Không có ghi chú' }}</td>
                                                <td class="px-4 py-3">
                                                    @if($apt->status == 'pending')
                                                        <span class="badge bg-warning bg-opacity-10 text-warning border border-warning-subtle rounded-pill px-3 py-2">Chờ xác nhận</span>
                                                    @elseif($apt->status == 'approved')
                                                        <span class="badge bg-success bg-opacity-10 text-success border border-success-subtle rounded-pill px-3 py-2">Đã xác nhận</span>
                                                    @elseif($apt->status == 'rejected')
                                                        <span class="badge bg-danger bg-opacity-10 text-danger border border-danger-subtle rounded-pill px-3 py-2">Đã hủy</span>
                                                    @endif
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

                <!-- Tab 3: Hồ sơ bệnh án -->
                <div class="tab-pane fade" id="v-pills-records" role="tabpanel" aria-labelledby="v-pills-records-tab" tabindex="0">
                    <div class="card border-0 shadow-sm rounded-4 h-100">
                        <div class="card-header bg-white border-0 pt-4 pb-0 px-4">
                            <h5 class="fw-bold text-primary mb-0"><i class="bi bi-file-medical me-2"></i>Hồ sơ bệnh án</h5>
                        </div>
                        <div class="card-body p-4">
                            @if($medicalRecords->isEmpty())
                                <div class="text-center py-5 text-secondary">
                                    <i class="bi bi-inbox fs-1 opacity-50 mb-3 d-block"></i>
                                    Bạn chưa có hồ sơ bệnh án nào.
                                </div>
                            @else
                                <div class="accordion accordion-flush" id="accordionRecords">
                                    @foreach($medicalRecords as $record)
                                    <div class="accordion-item border rounded-3 mb-3 overflow-hidden">
                                        <h2 class="accordion-header">
                                            <button class="accordion-button collapsed bg-light text-dark fw-bold px-4 py-3" type="button" data-bs-toggle="collapse" data-bs-target="#collapseRecord{{ $record->id }}" aria-expanded="false" aria-controls="collapseRecord{{ $record->id }}">
                                                <div class="d-flex w-100 justify-content-between pe-3">
                                                    <span>Ngày khám: {{ \Carbon\Carbon::parse($record->exam_date)->format('d/m/Y') }}</span>
                                                    <span class="badge bg-primary rounded-pill">{{ $record->service ?: 'Khám Da liễu' }}</span>
                                                </div>
                                            </button>
                                        </h2>
                                        <div id="collapseRecord{{ $record->id }}" class="accordion-collapse collapse" data-bs-parent="#accordionRecords">
                                            <div class="accordion-body p-4">
                                                <div class="row g-4">
                                                    <div class="col-md-6">
                                                        <div class="mb-3">
                                                            <div class="text-secondary small fw-medium mb-1">Bác sĩ khám:</div>
                                                            <div class="fw-medium text-dark">{{ $record->doctor ? 'BS. ' . $record->doctor->user->name : 'N/A' }}</div>
                                                        </div>
                                                        <div class="mb-3">
                                                            <div class="text-secondary small fw-medium mb-1">Chẩn đoán:</div>
                                                            <div class="p-3 bg-light rounded-3 text-dark">{{ $record->diagnosis ?: 'Không có dữ liệu' }}</div>
                                                        </div>
                                                        <div class="mb-3">
                                                            <div class="text-secondary small fw-medium mb-1">Tái khám:</div>
                                                            <div class="fw-bold text-danger">{{ $record->follow_up_date ? \Carbon\Carbon::parse($record->follow_up_date)->format('d/m/Y') : 'Không hẹn tái khám' }}</div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="mb-3">
                                                            <div class="text-secondary small fw-medium mb-1">Toa thuốc:</div>
                                                            <div class="p-3 bg-light rounded-3 text-dark" style="white-space: pre-line;">{{ $record->prescription ?: 'Không có toa thuốc' }}</div>
                                                        </div>
                                                        <div class="mb-3">
                                                            <div class="text-secondary small fw-medium mb-1">Ghi chú (Dặn dò):</div>
                                                            <div class="p-3 bg-light rounded-3 text-dark" style="white-space: pre-line;">{{ $record->notes ?: 'Không có dặn dò' }}</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Tab 4: Cập nhật tình trạng da -->
                <div class="tab-pane fade" id="v-pills-progress" role="tabpanel" aria-labelledby="v-pills-progress-tab" tabindex="0">
                    <div class="card border-0 shadow-sm rounded-4 h-100">
                        <div class="card-header bg-white border-0 pt-4 pb-0 px-4 d-flex justify-content-between align-items-center">
                            <h5 class="fw-bold text-primary mb-0"><i class="bi bi-camera me-2"></i>Cập nhật tình trạng da</h5>
                            <button type="button" class="btn btn-primary btn-sm rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#addProgressModal">
                                <i class="bi bi-plus-lg me-1"></i> Thêm hình ảnh
                            </button>
                        </div>
                        <div class="card-body p-4">
                            @if($progresses->isEmpty())
                                <div class="text-center py-5 text-secondary">
                                    <i class="bi bi-images fs-1 opacity-50 mb-3 d-block"></i>
                                    Chưa có hình ảnh tiến độ nào. Hãy cập nhật để bác sĩ theo dõi nhé!
                                </div>
                            @else
                                <div class="row g-4">
                                    @foreach($progresses as $prog)
                                    <div class="col-md-4 col-sm-6">
                                        <div class="card border border-light h-100 shadow-sm rounded-4 overflow-hidden">
                                            <div style="height: 200px; overflow: hidden;" class="bg-light">
                                                <img src="{{ asset('storage/' . $prog->image_path) }}" class="w-100 h-100 object-fit-cover" alt="Tiến độ da" data-bs-toggle="modal" data-bs-target="#imageModal{{ $prog->id }}" style="cursor: pointer;">
                                            </div>
                                            <div class="card-body p-3">
                                                <div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-2">
                                                    <span class="text-secondary small"><i class="bi bi-calendar3 me-1"></i> {{ $prog->created_at->format('d/m/Y') }}</span>
                                                    <span class="badge bg-light text-dark border"><i class="bi bi-person-fill me-1"></i> {{ $prog->doctor ? 'BS. ' . $prog->doctor->user->name : 'N/A' }}</span>
                                                </div>
                                                <p class="card-text text-dark small mb-0">{{ Str::limit($prog->notes, 50) ?: 'Không có ghi chú' }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Modal xem hình ảnh -->
                                    <div class="modal fade" id="imageModal{{ $prog->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered modal-lg">
                                            <div class="modal-content bg-transparent border-0">
                                                <div class="modal-header border-0 justify-content-end p-2">
                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body p-0 text-center">
                                                    <img src="{{ asset('storage/' . $prog->image_path) }}" class="img-fluid rounded-4 shadow" alt="Tiến độ da">
                                                    @if($prog->notes)
                                                        <div class="mt-3 bg-white p-3 rounded-4 shadow text-start">
                                                            <h6 class="fw-bold mb-2">Ghi chú:</h6>
                                                            <p class="mb-0 text-dark">{{ $prog->notes }}</p>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- Modal Thêm Tiến Độ -->
<div class="modal fade" id="addProgressModal" tabindex="-1" aria-labelledby="addProgressModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-4 text-start">
            <div class="modal-header border-bottom py-3 px-4 bg-light rounded-top-4">
                <h5 class="modal-title fw-bold text-primary" id="addProgressModalLabel">
                    <i class="bi bi-camera me-2"></i>Cập nhật tình trạng da
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('patient.progress.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label text-secondary fw-medium small mb-1">Gửi tới Bác sĩ <span class="text-danger">*</span></label>
                        @if($doctors->isEmpty())
                            <select name="doctor_id" class="form-select bg-light border-0 py-2" required disabled>
                                <option value="">Bạn chưa khám với bác sĩ nào</option>
                            </select>
                            <div class="form-text small text-warning"><i class="bi bi-info-circle me-1"></i>Bạn cần đặt lịch khám trước khi có thể gửi thông tin theo dõi.</div>
                        @else
                            <select name="doctor_id" class="form-select bg-light border-0 focus-ring focus-ring-primary py-2" required>
                                <option value="">-- Chọn bác sĩ --</option>
                                @foreach($doctors as $doc)
                                    <option value="{{ $doc->id }}">BS. {{ $doc->user->name }} - {{ $doc->specialty }}</option>
                                @endforeach
                            </select>
                        @endif
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-secondary fw-medium small mb-1">Hình ảnh tình trạng da <span class="text-danger">*</span></label>
                        <input type="file" name="image" class="form-control bg-light border-0 focus-ring focus-ring-primary py-2" accept="image/*" required>
                        <div class="form-text small">Chụp rõ vùng da cần bác sĩ theo dõi (Tối đa 5MB)</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-secondary fw-medium small mb-1">Ghi chú / Triệu chứng (nếu có)</label>
                        <textarea name="notes" class="form-control bg-light border-0 focus-ring focus-ring-primary" rows="3" placeholder="Mô tả tình trạng da hiện tại của bạn..."></textarea>
                    </div>
                </div>
                <div class="modal-footer border-top bg-light p-3 rounded-bottom-4">
                    <button type="button" class="btn btn-secondary px-4 rounded-pill fw-medium" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary px-4 rounded-pill fw-medium">Tải lên</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
