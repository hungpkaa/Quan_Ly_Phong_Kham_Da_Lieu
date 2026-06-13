<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard')</title>
    
    <!-- Bootstrap & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <style>
        body {
            background-color: #f8f9fa; /* Nền xám rất nhẹ chuẩn enterprise */
            overflow-x: hidden;
            margin: 0;
            color: #333;
        }
        .admin-wrapper {
            display: flex;
            min-height: 100vh;
        }
        
        /* Sidebar layout & transition */
        .sidebar-container {
            width: 250px;
            transition: all 0.3s ease;
            z-index: 1040;
        }
        .sidebar-collapsed .sidebar-container {
            width: 70px;
        }
        .sidebar-collapsed .sidebar-container .fs-5 span,
        .sidebar-collapsed .sidebar-container .nav-link span {
            display: none;
        }
        .sidebar-collapsed .sidebar-container .nav-link {
            text-align: center;
            padding-left: 0 !important;
            padding-right: 0 !important;
        }
        .sidebar-collapsed .sidebar-container .nav-link i {
            margin-right: 0 !important;
            font-size: 1.25rem;
        }

        .admin-main-content {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            width: calc(100% - 250px);
            transition: all 0.3s ease;
        }
        .sidebar-collapsed .admin-main-content {
            width: calc(100% - 70px);
        }

        .content-area {
            flex-grow: 1;
            padding: 24px;
        }
        
        /* Đảm bảo Header trông gọn gàng trong layout mới */
        .admin-main-content > header {
            background-color: #fff;
            border-bottom: 1px solid #eee;
        }

        /* Sidebar Styling */
        .sidebar-link {
            border-radius: 6px;
            transition: all 0.2s ease;
            color: #495057 !important;
            font-weight: 500;
            white-space: nowrap;
            overflow: hidden;
        }
        .sidebar-link:hover {
            background-color: #f0f7ff;
            color: #0056b3 !important;
        }
        .sidebar-link.active {
            background-color: #f0f7ff !important;
            color: #0056b3 !important;
            border-left: 4px solid #0056b3;
            border-radius: 0 6px 6px 0;
        }

        @media (max-width: 768px) {
            .sidebar-container {
                position: fixed;
                height: 100vh;
                left: -250px;
            }
            .sidebar-mobile-open .sidebar-container {
                left: 0;
            }
            .admin-main-content {
                width: 100%;
            }
            .sidebar-collapsed .admin-main-content {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="admin-wrapper">
        <!-- Sidebar -->
        <div class="sidebar-container bg-white border-end shadow-sm">
            @include('partials.admin_sidebar')
        </div>

        <!-- Main Content -->
        <div class="admin-main-content">
            <header class="bg-white border-bottom py-2 shadow-sm">
                <div class="container-fluid px-4 d-flex justify-content-between align-items-center">
                    <!-- Left side: Toggle + Search -->
                    <div class="d-flex align-items-center gap-3" style="flex: 1;">
                        <button id="sidebarToggle" class="btn btn-light btn-sm text-secondary border-0 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                            <i class="bi bi-list fs-5"></i>
                        </button>
                        <div class="d-flex align-items-center" style="max-width: 350px; width: 100%; m-0">
                            <div class="input-group input-group-sm">
                                <span class="input-group-text bg-light border-end-0 rounded-start-pill text-secondary">
                                    <i class="bi bi-search"></i>
                                </span>
                                <input type="text" id="globalSearchInput" class="form-control bg-light border-start-0 rounded-end-pill focus-ring focus-ring-light" placeholder="Tìm kiếm nhanh trên trang hiện tại...">
                            </div>
                        </div>
                    </div>

                    <!-- Right side Actions -->
                    <div class="d-flex align-items-center gap-3">
                        <!-- Notifications -->
                        <div class="dropdown">
                            <button class="btn btn-light btn-sm rounded-circle position-relative p-2 text-secondary border-0" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-bell fs-5"></i>
                                <span class="position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-light rounded-circle">
                                    <span class="visually-hidden">New alerts</span>
                                </span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 mt-2" style="width: 250px;">
                                <li><h6 class="dropdown-header">Thông báo mới</h6></li>
                                <li><a class="dropdown-item py-2" href="{{ url('/admin/appointments') }}"><small class="text-primary fw-medium">Lịch hẹn mới chờ duyệt</small></a></li>
                                <li><a class="dropdown-item py-2 text-secondary" href="#"><small>Hệ thống hoạt động ổn định</small></a></li>
                            </ul>
                        </div>

                        <div class="vr bg-secondary opacity-25" style="height: 24px;"></div>

                        <!-- User Profile Dropdown -->
                        <div class="dropdown">
                            <button class="btn btn-light btn-sm border-0 d-flex align-items-center gap-2" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <img src="/img/user.png" onerror="this.src='https://ui-avatars.com/api/?name=Admin&background=0D8ABC&color=fff'" alt="Admin" width="32" height="32" class="rounded-circle object-fit-cover">
                                <span class="d-none d-md-block text-dark fw-medium" style="font-size: 14px;">
                                    {{ Auth::user()->role === 'admindoctor' ? 'Bác sĩ' : 'Quản trị viên' }}
                                </span>
                                <i class="bi bi-chevron-down ms-1" style="font-size: 12px; color: #6c757d;"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 mt-2">
                                <li>
                                    <form method="POST" action="{{ route('logout') }}" class="m-0">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger"><i class="bi bi-box-arrow-right me-2"></i>Đăng xuất</button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Content Area -->
            <div class="content-area">
                @yield('content')
            </div>
            
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toggleBtn = document.getElementById('sidebarToggle');
            const body = document.body;
            
            toggleBtn.addEventListener('click', function() {
                if (window.innerWidth <= 768) {
                    body.classList.toggle('sidebar-mobile-open');
                } else {
                    body.classList.toggle('sidebar-collapsed');
                }
            });
        });
    </script>
    @stack('scripts')
</body>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('globalSearchInput');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const query = this.value.toLowerCase();
            const tableRows = document.querySelectorAll('table tbody tr');

            tableRows.forEach(row => {
                const rowText = row.textContent.toLowerCase();
                if (rowText.includes(query)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    }
});
</script>
</html>
