<!-- Sidebar -->
<nav id="sidebar">
    <div class="sidebar-header">
        <div class="logo-icon"><i class="fa-solid fa-plane-departure" style="color:#ef4444;"></i></div>
        <h5 class="mb-0 fw-bold" style="color:#0d3470;">Voyogo Admin</h5>
    </div>

    <?php $active = isset($active_menu) ? $active_menu : 'dashboard'; ?>
    <ul class="sidebar-menu">
        <li class="menu-title">Main Navigation</li>
        <li>
            <a href="<?php echo site_url('admin'); ?>" class="<?php echo ($active == 'dashboard') ? 'active' : ''; ?>">
                <i class="fa-solid fa-gauge-high"></i> Dashboard
            </a>
        </li>
        
        <li class="menu-title">Booking Management</li>
        <li>
            <a href="<?php echo site_url('admin/flight_bookings'); ?>" class="<?php echo ($active == 'flight_bookings') ? 'active' : ''; ?>">
                <i class="fa-solid fa-plane"></i> Flight Bookings
            </a>
        </li>
        <li>
            <a href="<?php echo site_url('admin/hotel_bookings'); ?>" class="<?php echo ($active == 'hotel_bookings') ? 'active' : ''; ?>">
                <i class="fa-solid fa-hotel"></i> Hotel Bookings
            </a>
        </li>
        <li>
            <a href="<?php echo site_url('admin/enquiries'); ?>" class="<?php echo ($active == 'enquiries') ? 'active' : ''; ?>">
                <i class="fa-solid fa-envelope-open-text"></i> Customer Enquiries
            </a>
        </li>

        <li class="menu-title">Settings & Tools</li>
        <li>
            <a href="<?php echo site_url('flight_cert'); ?>" class="<?php echo ($active == 'flight_cert') ? 'active' : ''; ?>">
                <i class="fa-solid fa-certificate" style="color: #f59e0b;"></i> API Certification Suite
            </a>
        </li>
        <li>
            <a href="<?php echo site_url('admin/email_settings'); ?>" class="<?php echo ($active == 'email_settings') ? 'active' : ''; ?>">
                <i class="fa-solid fa-sliders"></i> SMTP Email Settings
            </a>
        </li>
        <li>
            <a href="<?php echo site_url('admin/razorpay_settings'); ?>" class="<?php echo ($active == 'razorpay_settings') ? 'active' : ''; ?>">
                <i class="fa-solid fa-credit-card" style="color: #3b82f6;"></i> Razorpay Settings
            </a>
        </li>
        <li>
            <a href="<?php echo site_url('admin/api_logs'); ?>" class="<?php echo ($active == 'api_logs') ? 'active' : ''; ?>">
                <i class="fa-solid fa-clock-rotate-left" style="color: #10b981;"></i> API Activity Logs
            </a>
        </li>
        <li>
            <a href="<?php echo site_url('admin/setup_db'); ?>" onclick="return confirm('Synchronize database schema and tables?');">
                <i class="fa-solid fa-database"></i> Database Sync Tool
            </a>
        </li>
        <li>
            <a href="<?php echo site_url('admin/logout'); ?>" class="text-danger">
                <i class="fa-solid fa-arrow-right-from-bracket"></i> Logout
            </a>
        </li>
    </ul>
</nav>

<!-- Page Content Holder -->
<div id="content">

    <!-- Top Navbar -->
    <header class="top-navbar">
        <div>
            <button type="button" id="sidebarCollapse" class="navbar-btn">
                <i class="fa-solid fa-bars"></i>
            </button>
            <span class="ms-3 fw-medium" style="color: #64748b;">Welcome back, <strong>Super Admin</strong></span>
        </div>

        <div class="d-flex align-items-center gap-3">
            <a href="<?php echo site_url('/'); ?>" target="_blank" class="btn btn-sm btn-outline-primary fw-bold">
                <i class="fa-solid fa-globe me-1"></i> Visit Live Site
            </a>

            <div class="user-profile dropdown">
                <div class="d-flex align-items-center gap-2" data-bs-toggle="dropdown" aria-expanded="false" style="cursor: pointer;">
                    <div class="info text-end d-none d-md-flex">
                        <span class="name">Voyogo Admin</span>
                        <span class="role" style="font-size: 11px; color: #16a34a; font-weight: 700;">Super Administrator</span>
                    </div>
                    <img src="https://ui-avatars.com/api/?name=Voyogo+Admin&background=0d3470&color=fff" alt="Admin" style="border-radius: 50%; width: 38px; height: 38px;">
                </div>
                <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 mt-2">
                    <li><a class="dropdown-item py-2" href="<?php echo site_url('admin/api_logs'); ?>"><i class="fa-solid fa-clock-rotate-left text-success me-2"></i> API Activity Logs</a></li>
                    <li><a class="dropdown-item py-2" href="<?php echo site_url('admin/razorpay_settings'); ?>"><i class="fa-solid fa-credit-card text-primary me-2"></i> Razorpay Settings</a></li>
                    <li><a class="dropdown-item py-2" href="<?php echo site_url('admin/email_settings'); ?>"><i class="fa-solid fa-gear me-2"></i> SMTP Settings</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item py-2 text-danger" href="<?php echo site_url('admin/logout'); ?>"><i class="fa-solid fa-arrow-right-from-bracket me-2"></i> Logout</a></li>
                </ul>
            </div>
        </div>
    </header>