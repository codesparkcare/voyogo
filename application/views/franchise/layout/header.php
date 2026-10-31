<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($title) ? htmlspecialchars($title) : 'Franchise Partner Portal - Voyogo'; ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Outfit:wght@600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-navy: #09204b;
            --primary-navy-dark: #061633;
            --primary-navy-light: #0d3470;
            --accent-lime: #78B722;
            --accent-lime-hover: #6aa31e;
            --accent-orange: #f97316;
            --bg-gray: #f1f4f9;
            --border-color: #e2e8f0;
            --text-dark: #0f172a;
            --text-muted: #64748b;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Inter', sans-serif; }
        html, body { max-width: 100%; overflow-x: hidden; }
        body { background: var(--bg-gray); color: var(--text-dark); min-height: 100vh; display: flex; flex-direction: column; }

        /* Top Green Utility Strip matching Screenshot 2 */
        .f-top-strip {
            background: #78B722;
            color: #ffffff;
            padding: 7px 32px;
            font-size: 12.5px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-weight: 500;
        }
        .f-top-strip-left, .f-top-strip-right {
            display: flex;
            align-items: center;
            gap: 20px;
        }
        .f-top-strip a {
            color: #ffffff;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .f-top-strip strong {
            font-weight: 700;
        }

        /* Main Header Navbar matching Screenshot 2 (Deep Navy #09204b) */
        .store-navbar {
            background: #09204b;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            padding: 14px 32px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
        }
        .nav-left {
            display: flex;
            align-items: center;
            gap: 28px;
        }
        .brand-box {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
        }
        .brand-box img { 
            max-height: 40px; 
            width: auto;
        }
        .b2b-pill {
            background: #78B722;
            color: #ffffff;
            font-size: 11px;
            font-weight: 800;
            padding: 3px 10px;
            border-radius: 6px;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }
        .nav-links {
            display: flex;
            gap: 8px;
            list-style: none;
        }
        .nav-links a {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px 16px;
            border-radius: 8px;
            text-decoration: none;
            color: #cbd5e1;
            font-size: 13.5px;
            font-weight: 600;
            transition: all 0.15s;
        }
        .nav-links a:hover {
            background: rgba(255, 255, 255, 0.1);
            color: #ffffff;
        }
        .nav-links a.active {
            background: #78B722;
            color: #ffffff;
            font-weight: 700;
            box-shadow: 0 2px 8px rgba(120, 183, 34, 0.35);
        }

        .nav-right {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        /* Wallet Float Badge */
        .wallet-badge-card {
            display: flex;
            align-items: center;
            gap: 12px;
            background: rgba(255, 255, 255, 0.1);
            border: 1.5px solid rgba(255, 255, 255, 0.2);
            padding: 6px 16px;
            border-radius: 30px;
            text-decoration: none;
            transition: all 0.15s;
        }
        .wallet-badge-card:hover { 
            background: rgba(255, 255, 255, 0.15);
            border-color: #78B722;
        }
        .wallet-icon-circle {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: #78B722;
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            box-shadow: 0 2px 6px rgba(120, 183, 34, 0.4);
        }
        .wallet-label {
            font-size: 10px;
            font-weight: 700;
            color: #86efac;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .wallet-amount {
            font-size: 16px;
            font-weight: 800;
            color: #ffffff;
        }

        /* Store Owner Profile Badge */
        .store-user-card {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 6px 14px;
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 24px;
        }
        .store-avatar {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.2);
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 13px;
        }
        .store-info-text {
            line-height: 1.2;
            font-size: 12.5px;
        }
        .store-name-text {
            font-weight: 700;
            color: #ffffff;
            max-width: 140px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .store-code-text {
            font-size: 11px;
            color: #93c5fd;
            font-weight: 700;
        }

        .btn-logout {
            color: #f87171;
            font-size: 16px;
            padding: 8px;
            border-radius: 8px;
            transition: background 0.15s;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .btn-logout:hover {
            background: rgba(239, 68, 68, 0.15);
        }

        /* Container */
        .store-container {
            max-width: 1240px;
            margin: 28px auto;
            padding: 0 20px;
            flex: 1;
            width: 100%;
            box-sizing: border-box;
        }
        .store-container.wide-container {
            max-width: 98% !important;
            width: 98% !important;
            margin: 16px auto !important;
            padding: 0 10px !important;
        }

        /* Mobile hamburger button */
        .f-mobile-toggle {
            display: none;
            background: rgba(255, 255, 255, 0.12);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: #ffffff;
            width: 38px;
            height: 38px;
            border-radius: 8px;
            font-size: 17px;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.15s;
        }
        .f-mobile-toggle:hover {
            background: rgba(255, 255, 255, 0.22);
        }

        /* Mobile Off-canvas Drawer */
        .f-drawer-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background: rgba(6, 22, 51, 0.72);
            backdrop-filter: blur(4px);
            -webkit-backdrop-filter: blur(4px);
            z-index: 99998;
            opacity: 0;
            transition: opacity 0.25s ease;
        }
        .f-drawer-overlay.active {
            display: block;
            opacity: 1;
        }
        .f-mobile-drawer {
            position: fixed;
            top: 0;
            right: 0;
            width: 310px;
            max-width: 86vw;
            height: 100vh;
            background: #09204b;
            z-index: 99999;
            transform: translateX(100%);
            transition: transform 0.28s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            flex-direction: column;
            box-shadow: -10px 0 35px rgba(0, 0, 0, 0.4);
            border-left: 1px solid rgba(255, 255, 255, 0.12);
        }
        .f-mobile-drawer.active {
            transform: translateX(0);
        }
        .f-drawer-header {
            padding: 18px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            background: rgba(0, 0, 0, 0.18);
        }
        .f-drawer-user {
            display: flex;
            align-items: center;
            gap: 12px;
            min-width: 0;
        }
        .f-drawer-close {
            background: rgba(255, 255, 255, 0.12);
            border: 1px solid rgba(255, 255, 255, 0.15);
            color: #ffffff;
            width: 32px;
            height: 32px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 16px;
            transition: background 0.15s;
        }
        .f-drawer-close:hover {
            background: rgba(255, 255, 255, 0.25);
        }
        .f-drawer-wallet {
            margin: 16px 16px 8px 16px;
            padding: 12px 14px;
            background: rgba(255, 255, 255, 0.08);
            border: 1.5px solid rgba(255, 255, 255, 0.18);
            border-radius: 12px;
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
            transition: background 0.15s;
        }
        .f-drawer-wallet:hover {
            background: rgba(255, 255, 255, 0.12);
        }
        .f-drawer-nav {
            flex: 1;
            overflow-y: auto;
            padding: 10px 14px;
            display: flex;
            flex-direction: column;
            gap: 4px;
        }
        .f-drawer-nav-title {
            font-size: 11px;
            font-weight: 800;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            padding: 10px 12px 4px 12px;
        }
        .f-drawer-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 14px;
            color: #cbd5e1;
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
            border-radius: 8px;
            transition: all 0.15s;
        }
        .f-drawer-link:hover {
            background: rgba(255, 255, 255, 0.08);
            color: #ffffff;
        }
        .f-drawer-link.active {
            background: #78B722;
            color: #ffffff;
            font-weight: 700;
            box-shadow: 0 2px 8px rgba(120, 183, 34, 0.35);
        }
        .f-drawer-link i {
            width: 20px;
            text-align: center;
            font-size: 15px;
        }
        .f-drawer-badge {
            background: #ef4444;
            color: #ffffff;
            font-size: 10px;
            font-weight: 800;
            padding: 2px 7px;
            border-radius: 10px;
            margin-left: auto;
        }
        .f-drawer-footer {
            padding: 16px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            background: rgba(0, 0, 0, 0.18);
        }
        .f-drawer-logout {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            background: rgba(239, 68, 68, 0.15);
            color: #f87171;
            border: 1px solid rgba(239, 68, 68, 0.35);
            padding: 10px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 700;
            font-size: 13.5px;
            transition: all 0.15s;
        }
        .f-drawer-logout:hover {
            background: #ef4444;
            color: #ffffff;
        }

        /* Shared B2B Page Layout with Sidebar */
        .f-main-layout {
            display: grid;
            grid-template-columns: 240px 1fr;
            gap: 24px;
            align-items: start;
            width: 100%;
            min-width: 0;
            box-sizing: border-box;
        }
        .f-main-content {
            width: 100%;
            min-width: 0;
            box-sizing: border-box;
        }
        .f-sidebar-nav {
            background: #ffffff;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 2px 8px rgba(0,0,0,0.03);
            overflow: hidden;
            height: fit-content;
            min-width: 0;
        }
        .f-side-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 16px 20px;
            text-decoration: none;
            color: #475569;
            font-weight: 600;
            font-size: 14px;
            border-left: 4px solid transparent;
            transition: background 0.15s, color 0.15s;
            white-space: nowrap;
        }
        .f-side-link:hover {
            background: #f8fafc;
            color: #0f172a;
        }
        .f-side-link.active {
            color: #ea580c;
            font-weight: 700;
            border-left-color: #f97316;
            background: #fff7ed;
        }
        .f-side-link i {
            font-size: 16px;
            width: 20px;
            text-align: center;
            color: #64748b;
        }
        .f-side-link.active i {
            color: #ea580c;
        }
        .f-side-text-mobile {
            display: none;
        }

        /* Responsive Breakpoints for Header & Layout */
        @media (max-width: 991px) {
            .f-top-strip {
                padding: 6px 14px;
                font-size: 11.5px;
                gap: 8px;
            }
            .f-top-strip-left, .f-top-strip-right {
                gap: 12px;
            }
            .store-navbar {
                padding: 10px 14px;
            }
            .nav-left {
                gap: 10px;
            }
            .brand-box img {
                max-height: 32px;
            }
            .b2b-pill {
                font-size: 9px;
                padding: 2px 7px;
            }
            .nav-links {
                display: none !important;
            }
            .nav-right {
                gap: 10px;
            }
            .store-user-card {
                display: none !important;
            }
            .btn-logout {
                display: none !important;
            }
            .wallet-badge-card {
                padding: 5px 10px;
                gap: 6px;
            }
            .wallet-icon-circle {
                width: 26px;
                height: 26px;
                font-size: 11px;
            }
            .wallet-label {
                display: none !important;
            }
            .wallet-amount {
                font-size: 13.5px;
            }
            .f-mobile-toggle {
                display: inline-flex;
            }
            .store-container {
                margin: 14px auto;
                padding: 0 12px;
                width: 100%;
                max-width: 100vw;
                box-sizing: border-box;
            }
            .f-main-layout {
                grid-template-columns: 1fr;
                gap: 12px;
                width: 100%;
                min-width: 0;
            }
            .f-side-text-desktop {
                display: none;
            }
            .f-side-text-mobile {
                display: inline;
            }
            .f-sidebar-nav {
                display: grid;
                grid-template-columns: 1fr 1fr 1.15fr;
                width: 100%;
                max-width: 100%;
                overflow: hidden;
                border-radius: 10px;
                box-sizing: border-box;
            }
            .f-side-link {
                justify-content: center;
                padding: 11px 4px !important;
                font-size: 12px !important;
                border-left: none !important;
                border-bottom: 3px solid transparent;
                text-align: center;
                gap: 5px;
                min-width: 0;
                overflow: hidden;
                white-space: nowrap;
            }
            .f-side-link.active {
                border-bottom-color: #f97316 !important;
            }
            .f-side-link i {
                font-size: 13px;
                width: auto;
            }
        }

        @media (max-width: 600px) {
            .f-top-strip {
                padding: 6px 12px;
                flex-direction: column;
                gap: 4px;
                text-align: center;
            }
            .f-top-strip-right {
                display: none;
            }
            .f-top-strip-left {
                gap: 10px;
                flex-wrap: wrap;
                justify-content: center;
            }
            .f-top-strip-left span {
                white-space: nowrap;
            }
        }

        /* Alerts */
        .alert-box {
            padding: 12px 18px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 13.5px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .alert-success { background: #f0fdf4; border: 1px solid #bbf7d0; color: #166534; }
        .alert-danger  { background: #fef2f2; border: 1px solid #fecaca; color: #991b1b; }

        /* Modify Search Modal Overlay Base Rules (Hidden by default to avoid FOUC on load) */
        .modify-search-overlay {
            display: none !important;
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background: rgba(15, 23, 42, 0.72);
            backdrop-filter: blur(5px);
            -webkit-backdrop-filter: blur(5px);
            z-index: 99999;
            justify-content: center;
            align-items: center;
            padding: 20px;
            box-sizing: border-box;
            overflow-y: auto;
        }
        .modify-search-overlay.active {
            display: flex !important;
        }
    </style>
</head>
<body>

<!-- Top Green Utility Strip (Exact Colors from Screenshot 2) -->
<div class="f-top-strip">
    <div class="f-top-strip-left">
        <span><a href="tel:+918098999096"><i class="fa-solid fa-headset"></i> 24x7 Support: <strong>+91 8098999096</strong></a></span>
        <span><a href="mailto:supports@voyogo.com"><i class="fa-solid fa-envelope"></i> supports@voyogo.com</a></span>
    </div>
    <div class="f-top-strip-right">
        <span><i class="fa-solid fa-globe"></i> India (INR ₹)</span>
        <span style="background: rgba(0,0,0,0.15); padding: 2px 10px; border-radius: 12px; font-weight: 700;">
            <i class="fa-solid fa-store"></i> Franchise B2B Store
        </span>
    </div>
</div>

<!-- Main Navy Navigation Header (Exact Deep Navy #09204b from Screenshot 2) -->
<header class="store-navbar">
    <div class="nav-left">
        <a href="<?php echo site_url('franchise/flight'); ?>" class="brand-box">
            <img src="<?php echo base_url('assets/images/logo.png?v=' . (file_exists(FCPATH . 'assets/images/logo.png') ? filemtime(FCPATH . 'assets/images/logo.png') : time())); ?>" alt="Voyogo Logo">
            <span class="b2b-pill">B2B FRANCHISE</span>
        </a>

        <ul class="nav-links">
            <li>
                <a href="<?php echo site_url('franchise/flight'); ?>" class="<?php echo ($active_menu ?? '') === 'flight' ? 'active' : ''; ?>">
                    <i class="fa-solid fa-plane"></i> Flight
                </a>
            </li>
            <li>
                <a href="<?php echo site_url('franchise/hotel'); ?>" class="<?php echo ($active_menu ?? '') === 'hotel' ? 'active' : ''; ?>">
                    <i class="fa-solid fa-hotel"></i> Hotel
                </a>
            </li>
            <li>
                <a href="<?php echo site_url('franchise/group-tours'); ?>" class="<?php echo ($active_menu ?? '') === 'group_tours' ? 'active' : ''; ?>">
                    <i class="fa-solid fa-users-rectangle"></i> Group Tour Follow Up
                </a>
            </li>
            <li>
                <a href="<?php echo site_url('franchise/bookings'); ?>" class="<?php echo ($active_menu ?? '') === 'bookings' ? 'active' : ''; ?>">
                    <i class="fa-solid fa-clipboard-list"></i> My Bookings
                </a>
            </li>
            <?php
            $ci =& get_instance();
            $active_hold_count = 0;
            if (!empty($store['id'])) {
                $ci->load->model('Franchise_model');
                $active_hold_count = $ci->Franchise_model->count_active_hold_bookings($store['id']);
            }
            ?>
            <li>
                <a href="<?php echo site_url('franchise/hold_bookings'); ?>" class="<?php echo ($active_menu ?? '') === 'hold_bookings' ? 'active' : ''; ?>">
                    <i class="fa-solid fa-hand-holding-dollar"></i> Hold Bookings
                    <?php if ($active_hold_count > 0): ?>
                        <span style="background: #ef4444; color: #ffffff; font-size: 10px; font-weight: 800; padding: 2px 6px; border-radius: 10px; margin-left: 4px;"><?php echo $active_hold_count; ?></span>
                    <?php endif; ?>
                </a>
            </li>
            <li>
                <a href="<?php echo site_url('franchise/wallet_ledger'); ?>" class="<?php echo ($active_menu ?? '') === 'wallet' ? 'active' : ''; ?>">
                    <i class="fa-solid fa-clock-rotate-left"></i> Wallet Passbook
                </a>
            </li>
        </ul>
    </div>

    <div class="nav-right">
        <!-- Live Store Float Balance -->
        <a href="<?php echo site_url('franchise/wallet_ledger'); ?>" class="wallet-badge-card" title="Click to view Wallet Ledger">
            <div class="wallet-icon-circle">
                <i class="fa-solid fa-wallet"></i>
            </div>
            <div>
                <div class="wallet-label">Store Wallet Float</div>
                <div class="wallet-amount">₹ <?php echo number_format($store['wallet_balance'] ?? 0, 2); ?></div>
            </div>
        </a>

        <!-- Store Owner Profile Badge -->
        <div class="store-user-card">
            <div class="store-avatar">
                <i class="fa-solid fa-store"></i>
            </div>
            <div class="store-info-text">
                <div class="store-name-text"><?php echo htmlspecialchars($store['store_name'] ?? 'Franchise Store'); ?></div>
                <div class="store-code-text"><?php echo htmlspecialchars($store['agent_code'] ?? 'AGENT'); ?></div>
            </div>
        </div>

        <!-- Logout -->
        <a href="<?php echo site_url('franchise/logout'); ?>" class="btn-logout" title="Logout" onclick="return confirm('Do you want to log out of your Franchise Store portal?');">
            <i class="fa-solid fa-arrow-right-from-bracket"></i>
        </a>

        <!-- Mobile Navigation Toggle Button -->
        <button type="button" class="f-mobile-toggle" id="fMobileNavToggle" aria-label="Open Navigation Menu">
            <i class="fa-solid fa-bars"></i>
        </button>
    </div>
</header>

<!-- Mobile Navigation Drawer Overlay -->
<div class="f-drawer-overlay" id="fDrawerOverlay" onclick="closeFranchiseMobileMenu()"></div>

<!-- Mobile Navigation Drawer -->
<div class="f-mobile-drawer" id="fMobileDrawer">
    <div class="f-drawer-header">
        <div class="f-drawer-user">
            <div class="store-avatar">
                <i class="fa-solid fa-store"></i>
            </div>
            <div class="store-info-text">
                <div class="store-name-text"><?php echo htmlspecialchars($store['store_name'] ?? 'Franchise Store'); ?></div>
                <div class="store-code-text"><?php echo htmlspecialchars($store['agent_code'] ?? 'AGENT'); ?></div>
            </div>
        </div>
        <button type="button" class="f-drawer-close" onclick="closeFranchiseMobileMenu()" aria-label="Close Menu">
            <i class="fa-solid fa-xmark"></i>
        </button>
    </div>

    <!-- Drawer Wallet Card -->
    <a href="<?php echo site_url('franchise/wallet_ledger'); ?>" class="f-drawer-wallet">
        <div class="wallet-icon-circle">
            <i class="fa-solid fa-wallet"></i>
        </div>
        <div style="flex: 1; min-width: 0;">
            <div style="font-size: 10.5px; color: #86efac; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;">Store Wallet Float</div>
            <div style="font-size: 17px; font-weight: 800; color: #ffffff;">₹ <?php echo number_format($store['wallet_balance'] ?? 0, 2); ?></div>
        </div>
        <i class="fa-solid fa-chevron-right" style="color: #94a3b8; font-size: 12px;"></i>
    </a>

    <!-- Drawer Navigation Links -->
    <div class="f-drawer-nav">
        <div class="f-drawer-nav-title">Menu</div>
        <a href="<?php echo site_url('franchise/flight'); ?>" class="f-drawer-link <?php echo ($active_menu ?? '') === 'flight' ? 'active' : ''; ?>">
            <i class="fa-solid fa-plane"></i> <span>Flight Search</span>
        </a>
        <a href="<?php echo site_url('franchise/hotel'); ?>" class="f-drawer-link <?php echo ($active_menu ?? '') === 'hotel' ? 'active' : ''; ?>">
            <i class="fa-solid fa-hotel"></i> <span>Hotel Booking</span>
        </a>
        <a href="<?php echo site_url('franchise/group-tours'); ?>" class="f-drawer-link <?php echo ($active_menu ?? '') === 'group_tours' ? 'active' : ''; ?>">
            <i class="fa-solid fa-users-rectangle"></i> <span>Group Tour Follow Up</span>
        </a>
        <a href="<?php echo site_url('franchise/bookings'); ?>" class="f-drawer-link <?php echo ($active_menu ?? '') === 'bookings' ? 'active' : ''; ?>">
            <i class="fa-solid fa-clipboard-list"></i> <span>My Bookings</span>
        </a>
        <a href="<?php echo site_url('franchise/hold_bookings'); ?>" class="f-drawer-link <?php echo ($active_menu ?? '') === 'hold_bookings' ? 'active' : ''; ?>">
            <i class="fa-solid fa-hand-holding-dollar"></i> <span>Hold Bookings</span>
            <?php if ($active_hold_count > 0): ?>
                <span class="f-drawer-badge"><?php echo $active_hold_count; ?></span>
            <?php endif; ?>
        </a>
        <a href="<?php echo site_url('franchise/wallet_ledger'); ?>" class="f-drawer-link <?php echo ($active_menu ?? '') === 'wallet' ? 'active' : ''; ?>">
            <i class="fa-solid fa-clock-rotate-left"></i> <span>Wallet Passbook</span>
        </a>
    </div>

    <!-- Drawer Footer -->
    <div class="f-drawer-footer">
        <div style="font-size: 12px; color: #cbd5e1; margin-bottom: 12px; display: flex; align-items: center; gap: 8px;">
            <i class="fa-solid fa-headset" style="color: #78B722;"></i>
            <span>Support: <strong>+91 8098999096</strong></span>
        </div>
        <a href="<?php echo site_url('franchise/logout'); ?>" class="f-drawer-logout" onclick="return confirm('Do you want to log out of your Franchise Store portal?');">
            <i class="fa-solid fa-arrow-right-from-bracket"></i> <span>Logout Store</span>
        </a>
    </div>
</div>

<script>
function openFranchiseMobileMenu() {
    var drawer = document.getElementById('fMobileDrawer');
    var overlay = document.getElementById('fDrawerOverlay');
    if (drawer) drawer.classList.add('active');
    if (overlay) overlay.classList.add('active');
    document.body.style.overflow = 'hidden';
}
function closeFranchiseMobileMenu() {
    var drawer = document.getElementById('fMobileDrawer');
    var overlay = document.getElementById('fDrawerOverlay');
    if (drawer) drawer.classList.remove('active');
    if (overlay) overlay.classList.remove('active');
    document.body.style.overflow = '';
}
document.addEventListener('DOMContentLoaded', function() {
    var toggleBtn = document.getElementById('fMobileNavToggle');
    if (toggleBtn) {
        toggleBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            openFranchiseMobileMenu();
        });
    }
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeFranchiseMobileMenu();
    });
});
</script>

<div class="store-container <?php echo ($active_menu ?? '') === 'group_tours' ? 'wide-container' : ''; ?>">

    <!-- Flash Alerts -->
    <?php if ($this->session->flashdata('success')): ?>
            <div class="alert-box alert-success">
                <i class="fa-solid fa-circle-check" style="font-size: 16px;"></i>
                <span><?php echo $this->session->flashdata('success'); ?></span>
            </div>
    <?php endif; ?>

    <?php if ($this->session->flashdata('error')): ?>
            <div class="alert-box alert-danger">
                <i class="fa-solid fa-triangle-exclamation" style="font-size: 16px;"></i>
                <span><?php echo $this->session->flashdata('error'); ?></span>
            </div>
    <?php endif; ?>
