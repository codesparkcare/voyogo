<!-- Sidebar -->
<?php 
    $userRole = (isset($this->session) && $this->session->userdata('admin_role')) ? $this->session->userdata('admin_role') : 'superadmin'; 
    $adminUsername = (isset($this->session) && $this->session->userdata('admin_username')) ? $this->session->userdata('admin_username') : 'Admin';
    $isLeadsManager = ($userRole === 'leads_manager');
    $active = isset($active_menu) ? $active_menu : 'visas';
?>
<nav id="sidebar">
    <div class="sidebar-header">
        <div class="logo-icon"><i class="fa-solid fa-plane-departure" style="color:#ef4444;"></i></div>
        <h5 class="mb-0 fw-bold" style="color:#0d3470;"><?php echo $isLeadsManager ? 'Voyogo Leads' : 'Voyogo Admin'; ?></h5>
    </div>

    <ul class="sidebar-menu">
        <?php if (!$isLeadsManager): ?>
        <li class="menu-title">Main Navigation</li>
        <li>
            <a href="<?php echo site_url('admin'); ?>" class="<?php echo ($active == 'dashboard') ? 'active' : ''; ?>">
                <i class="fa-solid fa-gauge-high"></i> Dashboard
            </a>
        </li>
        <li>
            <a href="<?php echo site_url('admin/deals'); ?>" class="<?php echo ($active == 'deals') ? 'active' : ''; ?>">
                <i class="fa-solid fa-fire" style="color: #ef4444;"></i> Exclusive Deals
            </a>
        </li>
        
        <li class="menu-title"><i class="fa-solid fa-plane me-1"></i> Flight Management</li>
        <li>
            <a href="<?php echo site_url('admin/flight_bookings'); ?>" class="<?php echo ($active == 'flight_bookings') ? 'active' : ''; ?>">
                <i class="fa-solid fa-ticket"></i> Flight Bookings
            </a>
        </li>
        <li>
            <a href="<?php echo site_url('admin/flight_api_settings'); ?>" class="<?php echo ($active == 'flight_api_settings') ? 'active' : ''; ?>">
                <i class="fa-solid fa-sliders" style="color: #0284c7;"></i> Flight API Settings
            </a>
        </li>
        <li>
            <a href="<?php echo site_url('flight_cert'); ?>" class="<?php echo ($active == 'flight_cert') ? 'active' : ''; ?>">
                <i class="fa-solid fa-certificate" style="color: #f59e0b;"></i> Flight Certification Suite
            </a>
        </li>

        <li class="menu-title"><i class="fa-solid fa-hotel me-1"></i> Hotel Management</li>
        <li>
            <a href="<?php echo site_url('admin/hotel_bookings'); ?>" class="<?php echo ($active == 'hotel_bookings') ? 'active' : ''; ?>">
                <i class="fa-solid fa-bed" style="color: #6366f1;"></i> Hotel Bookings
            </a>
        </li>
        <li>
            <a href="<?php echo site_url('admin/hotel_api_settings'); ?>" class="<?php echo ($active == 'hotel_api_settings') ? 'active' : ''; ?>">
                <i class="fa-solid fa-gear" style="color: #ec4899;"></i> Hotel API Settings
            </a>
        </li>
        <li>
            <a href="<?php echo site_url('admin/hotel_api_logs'); ?>" class="<?php echo ($active == 'hotel_api_logs') ? 'active' : ''; ?>">
                <i class="fa-solid fa-clock-rotate-left" style="color: #8b5cf6;"></i> Hotel API Logs Checker
            </a>
        </li>
        <li>
            <a href="<?php echo site_url('hotel_cert'); ?>" class="<?php echo ($active == 'hotel_cert') ? 'active' : ''; ?>">
                <i class="fa-solid fa-certificate" style="color: #ec4899;"></i> Hotel Certification Suite
            </a>
        </li>
        <?php endif; ?>

        <li class="menu-title"><i class="fa-solid fa-layer-group me-1"></i> Leads & Services</li>
        <li>
            <a href="<?php echo site_url('admin/visas'); ?>" class="<?php echo ($active == 'visas') ? 'active' : ''; ?>">
                <i class="fa-solid fa-passport" style="color: #3b82f6;"></i> Visa Enquiries
            </a>
        </li>
        <li>
            <a href="<?php echo site_url('admin/cabs'); ?>" class="<?php echo ($active == 'cabs') ? 'active' : ''; ?>">
                <i class="fa-solid fa-taxi" style="color: #eab308;"></i> Cab Bookings
            </a>
        </li>
        <li>
            <a href="<?php echo site_url('admin/holidays'); ?>" class="<?php echo ($active == 'holidays') ? 'active' : ''; ?>">
                <i class="fa-solid fa-umbrella-beach" style="color: #10b981;"></i> Holiday Packages
            </a>
        </li>
        <li>
            <a href="<?php echo site_url('admin/forex'); ?>" class="<?php echo ($active == 'forex') ? 'active' : ''; ?>">
                <i class="fa-solid fa-coins" style="color: #f97316;"></i> Forex Orders
            </a>
        </li>
        <li>
            <a href="<?php echo site_url('admin/cruises'); ?>" class="<?php echo ($active == 'cruises') ? 'active' : ''; ?>">
                <i class="fa-solid fa-ship" style="color: #06b6d4;"></i> Cruise Enquiries
            </a>
        </li>

        <?php if (!$isLeadsManager): ?>
        <li class="menu-title">Common & System</li>
        <li>
            <a href="<?php echo site_url('admin/customers'); ?>" class="<?php echo ($active == 'customers') ? 'active' : ''; ?>">
                <i class="fa-solid fa-users" style="color: #06b6d4;"></i> Manage Customers
            </a>
        </li>
        <li>
            <a href="<?php echo site_url('admin/api_logs'); ?>" class="<?php echo ($active == 'api_logs') ? 'active' : ''; ?>">
                <i class="fa-solid fa-list-check" style="color: #10b981;"></i> All API Activity Logs
            </a>
        </li>
        <li>
            <a href="<?php echo site_url('admin/razorpay_settings'); ?>" class="<?php echo ($active == 'razorpay_settings') ? 'active' : ''; ?>">
                <i class="fa-solid fa-credit-card" style="color: #3b82f6;"></i> Razorpay Settings
            </a>
        </li>
        <li>
            <a href="<?php echo site_url('admin/email_settings'); ?>" class="<?php echo ($active == 'email_settings') ? 'active' : ''; ?>">
                <i class="fa-solid fa-envelope"></i> SMTP Email Settings
            </a>
        </li>
        <li>
            <a href="<?php echo site_url('admin/setup_db'); ?>" onclick="return confirm('Synchronize database schema and tables?');">
                <i class="fa-solid fa-database"></i> Database Sync Tool
            </a>
        </li>
        <?php endif; ?>
        
        <li class="menu-title">Account</li>
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
            <span class="ms-3 fw-medium" style="color: #64748b;">Welcome back, <strong><?php echo $isLeadsManager ? 'Leads Manager' : 'Super Admin'; ?></strong></span>
        </div>

        <div class="d-flex align-items-center gap-3">
            <a href="<?php echo site_url('/'); ?>" target="_blank" class="btn btn-sm btn-outline-primary fw-bold">
                <i class="fa-solid fa-globe me-1"></i> Visit Live Site
            </a>

            <div class="user-profile dropdown">
                <div class="d-flex align-items-center gap-2" data-bs-toggle="dropdown" aria-expanded="false" style="cursor: pointer;">
                    <div class="info text-end d-none d-md-flex">
                        <span class="name"><?php echo htmlspecialchars($adminUsername); ?></span>
                        <span class="role" style="font-size: 11px; color: <?php echo $isLeadsManager ? '#2563eb' : '#16a34a'; ?>; font-weight: 700;">
                            <?php echo $isLeadsManager ? 'Leads & Services Staff' : 'Super Administrator'; ?>
                        </span>
                    </div>
                    <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($adminUsername); ?>&background=<?php echo $isLeadsManager ? '2563eb' : '0d3470'; ?>&color=fff" alt="Admin" style="border-radius: 50%; width: 38px; height: 38px;">
                </div>
                <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 mt-2">
                    <?php if (!$isLeadsManager): ?>
                    <li><a class="dropdown-item py-2" href="<?php echo site_url('admin/flight_api_settings'); ?>"><i class="fa-solid fa-plane-departure text-info me-2"></i> Flight API Settings</a></li>
                    <li><a class="dropdown-item py-2" href="<?php echo site_url('admin/api_logs'); ?>"><i class="fa-solid fa-clock-rotate-left text-success me-2"></i> API Activity Logs</a></li>
                    <li><a class="dropdown-item py-2" href="<?php echo site_url('admin/razorpay_settings'); ?>"><i class="fa-solid fa-credit-card text-primary me-2"></i> Razorpay Settings</a></li>
                    <li><a class="dropdown-item py-2" href="<?php echo site_url('admin/email_settings'); ?>"><i class="fa-solid fa-gear me-2"></i> SMTP Settings</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <?php endif; ?>
                    <li><a class="dropdown-item py-2 text-danger" href="<?php echo site_url('admin/logout'); ?>"><i class="fa-solid fa-arrow-right-from-bracket me-2"></i> Logout</a></li>
                </ul>
            </div>
        </div>
    </header>