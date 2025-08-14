
<!DOCTYPE html>
<html class="loading" lang="ar" data-textdirection="rtl">
<!-- BEGIN: Head-->

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="description" content="نظام إدارة الشحنات">
    <meta name="keywords" content="شحنات، إدارة، نظام">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <meta name="author" content="AmYale System">
    
    <title>نظام إدارة الشحنات</title>

    @yield('style')
    
    <!-- Modern UI CSS -->
    <link rel="stylesheet" href="{{ asset('css/modern-ui.css') }}">
    
    <!-- DataTables Fixes CSS -->
    <link rel="stylesheet" href="{{ asset('css/datatables-fix.css') }}">
    
    <!-- Dashboard Enhancements CSS -->
    <link rel="stylesheet" href="{{ asset('css/dashboard-enhancements.css') }}">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans+Arabic:wght@100;200;300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="{{ asset('app-assets/vendors/css/tables/datatable/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('app-assets/vendors/css/tables/datatable/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('app-assets/vendors/css/tables/datatable/buttons.bootstrap4.min.css') }}">
    
    <!-- Select2 CSS -->
    <link rel="stylesheet" href="{{ asset('app-assets/vendors/css/forms/select/select2.min.css') }}">
    
    <!-- Custom Styles -->
    <style>
        @font-face {
            font-family: "Cairo";
            src: url("{{ asset('Cairo-VariableFont_slnt.ttf') }}");
        }
        
        body, li, a, td {
            font-family: "IBM Plex Sans Arabic", "Cairo", sans-serif !important;
        }
        
        .shipment-details {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .shipment-details ul {
            list-style-type: none;
            padding: 0;
        }

        .shipment-details ul li {
            margin-bottom: 5px;
        }
        
        /* RTL Support */
        .rtl {
            direction: rtl;
            text-align: right;
        }
        
        /* Loading States */
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.8);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }
        
        .spinner {
            width: 50px;
            height: 50px;
            border: 5px solid #f3f3f3;
            border-top: 5px solid #3498db;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>

<!-- BEGIN: Body-->
<body class="vertical-layout vertical-menu-modern navbar-floating footer-static" data-open="click" data-menu="vertical-menu-modern" data-col="">

    <!-- BEGIN: Header-->
    <nav class="header-navbar navbar navbar-expand-lg align-items-center floating-nav navbar-light navbar-shadow">
        <div class="navbar-container d-flex content">
            <div class="bookmark-wrapper d-flex align-items-center">
                <ul class="nav navbar-nav d-xl-none">
                    <li class="nav-item">
                        <a class="nav-link menu-toggle" href="javascript:void(0);">
                            <i class="ficon" data-feather="menu"></i>
                        </a>
                    </li>
                </ul>
                <ul class="nav navbar-nav bookmark-icons">
                    <li class="nav-item d-none d-lg-block">
                        <a class="nav-link" href="javascript:void(0);" data-toggle="tooltip" data-placement="top" title="Bookmark this page">
                            <i class="ficon" data-feather="bookmark"></i>
                        </a>
                    </li>
                </ul>
                <ul class="nav navbar-nav">
                    <li class="nav-item dropdown dropdown-language">
                        <a class="nav-link dropdown-toggle" id="dropdown-flag" href="javascript:void(0);" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="flag-icon flag-icon-us"></i>
                            <span class="selected-language">English</span>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="dropdown-flag">
                            <a class="dropdown-item" href="javascript:void(0);" data-language="en">
                                <i class="flag-icon flag-icon-us"></i> English
                            </a>
                            <a class="dropdown-item" href="javascript:void(0);" data-language="ar">
                                <i class="flag-icon flag-icon-sa"></i> العربية
                            </a>
                        </div>
                    </li>
                </ul>
            </div>
            <ul class="nav navbar-nav align-items-center ml-auto">
                <li class="nav-item dropdown dropdown-user">
                    <a class="nav-link dropdown-toggle dropdown-user-link" id="dropdown-user" href="javascript:void(0);" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <div class="user-nav d-sm-flex d-none">
                            <span class="user-name font-weight-bolder">{{ Auth::user()->name ?? 'المستخدم' }}</span>
                            <span class="user-status">مستخدم</span>
                        </div>
                        <span class="avatar">
                            <img class="round" src="{{ asset('app-assets/images/portrait/small/avatar-s-11.jpg') }}" alt="avatar" height="40" width="40">
                            <span class="avatar-status-online"></span>
                        </span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdown-user">
                        <a class="dropdown-item" href="{{ route('profile.edit') }}">
                            <i class="mr-50" data-feather="user"></i> الملف الشخصي
                        </a>
                        <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="mr-50" data-feather="power"></i> تسجيل الخروج
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </div>
                </li>
            </ul>
        </div>
    </nav>
    <!-- END: Header-->

    <!-- BEGIN: Main Menu-->
    <div class="main-menu menu-fixed menu-light menu-accordion menu-shadow" data-scroll-to-active="true">
        <div class="navbar-header">
            <ul class="nav navbar-nav flex-row">
                <li class="nav-item mr-auto">
                    <a class="navbar-brand" href="{{ route('dashboard') }}">
                        <span class="brand-logo">
                            <svg viewBox="0 0 139 95" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" height="24">
                                <defs>
                                    <linearGradient id="linearGradient-1" x1="100%" y1="10.5120544%" x2="50%" y2="89.4879456%">
                                        <stop stop-color="#000000" offset="0%"></stop>
                                        <stop stop-color="#FFFFFF" offset="100%"></stop>
                                    </linearGradient>
                                    <linearGradient id="linearGradient-2" x1="64.0437835%" y1="46.3276743%" x2="37.373316%" y2="100%">
                                        <stop stop-color="#EEEEEE" stop-opacity="0" offset="0%"></stop>
                                        <stop stop-color="#FFFFFF" offset="100%"></stop>
                                    </linearGradient>
                                </defs>
                                <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <g id="Artboard" transform="translate(-400.000000, -178.000000)">
                                        <g id="Group" transform="translate(400.000000, 178.000000)">
                                            <path class="text-primary" id="Path" class="text-primary" d="M-5.68434189e-14,2.84217094e-14 L38.1810085,2.84217094e-14 L38.1810085,38.1810085 L-5.68434189e-14,38.1810085 Z" fill="#000000"></path>
                                            <g class="text-primary" id="Rectangle" transform="translate(19.0905042, 19.0905042) rotate(-19.000000) translate(-19.0905042, -19.0905042) ">
                                                <use xlink:href="#linearGradient-1" opacity="0.2"></use>
                                                <use xlink:href="#linearGradient-2" opacity="0.2"></use>
                                            </g>
                                        </g>
                                    </g>
                                </g>
                            </svg>
                        </span>
                        <h2 class="brand-text">نظام الشحنات</h2>
                    </a>
                </li>
                <li class="nav-item nav-toggle">
                    <a class="nav-link modern-nav-toggle d-xl-none" href="javascript:void(0);">
                        <i class="ficon" data-feather="menu"></i>
                    </a>
                </li>
            </ul>
        </div>
        <div class="shadow-bottom"></div>
        <div class="main-menu-content">
            <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">
                <li class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <a class="d-flex align-items-center" href="{{ route('dashboard') }}">
                        <i data-feather="home"></i>
                        <span class="menu-title text-truncate">لوحة التحكم</span>
                    </a>
                </li>
                <li class="nav-item {{ request()->routeIs('shipments.*') ? 'active' : '' }}">
                    <a class="d-flex align-items-center" href="{{ route('shipments.index') }}">
                        <i data-feather="truck"></i>
                        <span class="menu-title text-truncate">الشحنات</span>
                    </a>
                </li>
                <li class="nav-item {{ request()->routeIs('drivers.*') ? 'active' : '' }}">
                    <a class="d-flex align-items-center" href="{{ route('drivers.index') }}">
                        <i data-feather="users"></i>
                        <span class="menu-title text-truncate">السائقين</span>
                    </a>
                </li>
                <li class="nav-item {{ request()->routeIs('vehicles.*') ? 'active' : '' }}">
                    <a class="d-flex align-items-center" href="{{ route('vehicles.index') }}">
                        <i data-feather="car"></i>
                        <span class="menu-title text-truncate">المركبات</span>
                    </a>
                </li>
                <li class="nav-item {{ request()->routeIs('users.*') ? 'active' : '' }}">
                    <a class="d-flex align-items-center" href="{{ route('users.index') }}">
                        <i data-feather="user"></i>
                        <span class="menu-title text-truncate">المستخدمين</span>
                    </a>
                </li>
                <li class="nav-item {{ request()->routeIs('reports.*') ? 'active' : '' }}">
                    <a class="d-flex align-items-center" href="{{ route('reports.index') }}">
                        <i data-feather="bar-chart-2"></i>
                        <span class="menu-title text-truncate">التقارير</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
    <!-- END: Main Menu-->

    <!-- BEGIN: Content-->
    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper container-xxl p-0">
            <div class="content-header row">
                <div class="content-header-left col-md-9 col-12 mb-2">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <h2 class="content-header-title float-left mb-0">@yield('title', 'لوحة التحكم')</h2>
                            <div class="breadcrumb-wrapper">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">الرئيسية</a></li>
                                    @yield('breadcrumb')
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-body">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @yield('content')
            </div>
        </div>
    </div>
    <!-- END: Content-->

    <!-- Loading Overlay -->
    <div class="loading-overlay" id="loadingOverlay" style="display: none;">
        <div class="spinner"></div>
    </div>

    <!-- BEGIN: Footer-->
    <button class="btn btn-primary btn-icon scroll-top" type="button">
        <i data-feather="arrow-up"></i>
    </button>
    <!-- END: Footer-->

    <!-- BEGIN: Vendor JS-->
    <script src="{{ asset('app-assets/vendors/js/vendors.min.js') }}"></script>
    <!-- END: Vendor JS-->

    <!-- BEGIN: Page Vendor JS-->
    <script src="{{ asset('app-assets/vendors/js/tables/datatable/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('app-assets/vendors/js/tables/datatable/datatables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('app-assets/vendors/js/tables/datatable/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('app-assets/vendors/js/tables/datatable/responsive.bootstrap4.js') }}"></script>
    <script src="{{ asset('app-assets/vendors/js/tables/datatable/datatables.buttons.min.js') }}"></script>
    <script src="{{ asset('app-assets/vendors/js/tables/datatable/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('app-assets/vendors/js/forms/select/select2.full.min.js') }}"></script>
    <!-- END: Page Vendor JS-->

    <!-- BEGIN: Theme JS-->
    <script src="{{ asset('app-assets/js/core/app-menu.js') }}"></script>
    <script src="{{ asset('app-assets/js/core/app.js') }}"></script>
    <!-- END: Theme JS-->

    <!-- Modern UI JavaScript -->
    <script src="{{ asset('js/modern-ui.js') }}"></script>
    
    <!-- DataTables Fixes JavaScript -->
    <script src="{{ asset('js/datatables-fix.js') }}"></script>

    <!-- User Experience JavaScript -->
    <script src="{{ asset('js/user-experience.js') }}"></script>

    <!-- Custom JavaScript -->
    <script>
        // Global configuration
        window.AppConfig = {
            routes: {
                retrunShipmentInTabsByStatus: "{{ route('retrun_shipment_in_tabs_by_status.retrun_shipment_in_tabs_by_status.retrunShipmentInTabsByStatus')}}",
                createShipmentForm: "{{ route('return_prices.return_price.returnPrice')}}",
                getDatahipmentdetails: "{{ route('shipments.shipment.getDatahipmentdetails')}}",
                getVehcile: "{{ route('shipments.shipment.getVehcile')}}",
                getCarrierPrice: "{{ route('return_prices.return_price.returnCarrierPrice')}}",
                shipmentDetails: "{{ route('shipments.shipment.shipmentDetails')}}",
                statusesGet: "{{ route('shipments.shipment.statusesGet')}}",
            },
            csrfToken: "{{ csrf_token() }}",
            locale: "{{ app()->getLocale() }}"
        };

        // Initialize when document is ready
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Modern UI
            if (window.ModernUI) {
                window.ModernUI.init();
            }
            
            // Initialize User Experience Enhancements
            if (window.UXEnhancer) {
                window.UXEnhancer.init();
            }
            
            // Initialize DataTables Fixes
            if (window.DataTablesFix) {
                // Auto-initialize all tables
                document.querySelectorAll('table').forEach(table => {
                    if (!table.classList.contains('no-datatable')) {
                        const tableId = table.id || `table-${Math.random().toString(36).substr(2, 9)}`;
                        table.id = tableId;
                        DataTablesFix.init(`#${tableId}`);
                    }
                });
            }
            
            // Initialize Select2
            if (typeof $.fn.select2 !== 'undefined') {
                $('.select2').select2({
                    dir: 'rtl',
                    language: 'ar'
                });
            }
            
            // Initialize tooltips
            if (typeof bootstrap !== 'undefined') {
                const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
                tooltipTriggerList.map(function(tooltipTriggerEl) {
                    return new bootstrap.Tooltip(tooltipTriggerEl);
                });
            }
        });

        // Utility functions
        function showLoading() {
            document.getElementById('loadingOverlay').style.display = 'flex';
        }

        function hideLoading() {
            document.getElementById('loadingOverlay').style.display = 'none';
        }

        function showNotification(message, type = 'success') {
            if (window.ModernUI && window.ModernUI.Notification) {
                window.ModernUI.Notification.show(message, type);
            } else {
                // Fallback to Bootstrap alert
                const alertDiv = document.createElement('div');
                alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
                alertDiv.innerHTML = `
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                `;
                document.querySelector('.content-body').insertBefore(alertDiv, document.querySelector('.content-body').firstChild);
                
                // Auto remove after 5 seconds
                setTimeout(() => {
                    if (alertDiv.parentNode) {
                        alertDiv.remove();
                    }
                }, 5000);
            }
        }

        // AJAX setup
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Global error handler
        $(document).ajaxError(function(event, xhr, settings, error) {
            hideLoading();
            let message = 'حدث خطأ أثناء معالجة الطلب';
            
            if (xhr.responseJSON && xhr.responseJSON.message) {
                message = xhr.responseJSON.message;
            } else if (xhr.status === 422) {
                message = 'بيانات غير صحيحة';
            } else if (xhr.status === 404) {
                message = 'الصفحة غير موجودة';
            } else if (xhr.status === 500) {
                message = 'خطأ في الخادم';
            }
            
            showNotification(message, 'error');
        });

        // Global success handler
        $(document).ajaxSuccess(function(event, xhr, settings) {
            hideLoading();
            if (xhr.responseJSON && xhr.responseJSON.message) {
                showNotification(xhr.responseJSON.message, 'success');
            }
        });
    </script>

    @yield('script')
</body>
<!-- END: Body-->
</html>