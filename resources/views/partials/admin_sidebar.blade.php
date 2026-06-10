<div class="d-flex flex-column flex-shrink-0 p-3 bg-white border-end w-100" style="min-height: 100vh;">
    <a href="{{ url(Auth::user()->role === 'admindoctor' ? '/admindoctor/dashboard' : '/admin/dashboard') }}" class="d-flex align-items-center mb-4 me-md-auto link-dark text-decoration-none px-2">
        <span class="fs-5" style="color: #0056b3; font-weight: 600;">
            <i class="bi bi-speedometer2 me-2"></i><span>Bảng điều khiển</span>
        </span>
    </a>
    
    @if(Auth::user()->role === 'admin')
    <ul class="nav nav-pills flex-column mb-auto">
        <li class="nav-item mb-1">
            <a href="{{ url('/admin/doctors') }}" class="nav-link px-3 sidebar-link {{ request()->is('admin/doctors*') ? 'active' : '' }}">
                <i class="bi bi-person-badge me-2"></i><span>Quản lý bác sĩ</span>
            </a>
        </li>
        <li class="nav-item mb-1">
            <a href="{{ url('/admin/patients') }}" class="nav-link px-3 sidebar-link {{ request()->is('admin/patients*') ? 'active' : '' }}">
                <i class="bi bi-people me-2"></i><span>Quản lý bệnh nhân</span>
            </a>
        </li>
        <li class="nav-item mb-1">
            <a href="{{ url('/admin/medicalrecords') }}" class="nav-link px-3 sidebar-link {{ request()->is('admin/medicalrecords*') ? 'active' : '' }}">
                <i class="bi bi-file-earmark-medical me-2"></i><span>Hồ sơ bệnh nhân</span>
            </a>
        </li>
        <li class="nav-item mb-1">
            <a href="{{ url('/admin/appointments') }}" class="nav-link px-3 sidebar-link {{ request()->is('admin/appointments*') ? 'active' : '' }}">
                <i class="bi bi-calendar-check me-2"></i><span>Quản lý lịch hẹn</span>
            </a>
        </li>
        <li class="nav-item mb-1">
            <a href="{{ url('/admin/manageservices') }}" class="nav-link px-3 sidebar-link {{ request()->is('admin/manageservices*') ? 'active' : '' }}">
                <i class="bi bi-list-check me-2"></i><span>Quản lý dịch vụ</span>
            </a>
        </li>
        <li class="nav-item mb-1">
            <a href="{{ url('/admin/workingschedule') }}" class="nav-link px-3 sidebar-link {{ request()->is('admin/workingschedule*') ? 'active' : '' }}">
                <i class="bi bi-clock-history me-2"></i><span>Lịch làm việc</span>
            </a>
        </li>
        <li class="nav-item mb-1">
            <a href="{{ url('/admin/invoices') }}" class="nav-link px-3 sidebar-link {{ request()->is('admin/invoices*') ? 'active' : '' }}">
                <i class="bi bi-receipt me-2"></i><span>Hóa đơn & Thống kê</span>
            </a>
        </li>
        <li class="nav-item mb-1">
            <a href="{{ url('/admin/supports') }}" class="nav-link px-3 sidebar-link {{ request()->is('admin/supports*') ? 'active' : '' }}">
                <i class="bi bi-headset me-2"></i><span>Hỗ trợ bệnh nhân</span>
            </a>
        </li>
    </ul>
    @endif

    @if(Auth::user()->role === 'admindoctor')
    <ul class="nav nav-pills flex-column mb-auto">
        <li class="nav-item mb-1">
            <a href="{{ url('/admindoctor/patients') }}" class="nav-link px-3 sidebar-link {{ request()->is('admindoctor/patients*') ? 'active' : '' }}">
                <i class="bi bi-people me-2"></i><span>Bệnh nhân của tôi</span>
            </a>
        </li>
        <li class="nav-item mb-1">
            <a href="{{ url('/admindoctor/schedule') }}" class="nav-link px-3 sidebar-link {{ request()->is('admindoctor/schedule*') ? 'active' : '' }}">
                <i class="bi bi-calendar-week me-2"></i><span>Lịch khám bệnh</span>
            </a>
        </li>
        <li class="nav-item mb-1">
            <a href="{{ url('/admindoctor/medicalrecords') }}" class="nav-link px-3 sidebar-link {{ request()->is('admindoctor/medicalrecords*') ? 'active' : '' }}">
                <i class="bi bi-file-medical me-2"></i><span>Hồ sơ bệnh nhân</span>
            </a>
        </li>
        <li class="nav-item mb-1">
            <a href="{{ url('/admindoctor/progress') }}" class="nav-link px-3 sidebar-link {{ request()->is('admindoctor/progress*') ? 'active' : '' }}">
                <i class="bi bi-camera me-2"></i><span>Theo dõi tiến độ</span>
            </a>
        </li>
        <li class="nav-item mb-1">
            <a href="{{ url('/admindoctor/invoices') }}" class="nav-link px-3 sidebar-link {{ request()->is('admindoctor/invoices*') ? 'active' : '' }}">
                <i class="bi bi-receipt me-2"></i><span>Quản lý hóa đơn</span>
            </a>
        </li>
    </ul>
    @endif
</div>
