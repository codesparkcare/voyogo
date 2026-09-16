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
    </style>
</head>
<body>

<!-- Top Green Utility Strip (Exact Colors from Screenshot 2) -->
<div class="f-top-strip">
    <div class="f-top-strip-left">
        <span><i class="fa-solid fa-headset"></i> 24x7 Support: <strong>1800-123-4567 / +91 22 4066 6000</strong></span>
        <span><i class="fa-solid fa-envelope"></i> support@voyogo.com</span>
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
            <img src="<?php echo base_url('assets/images/logo.png'); ?>" alt="Voyogo Logo">
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
                <a href="<?php echo site_url('franchise/bookings'); ?>" class="<?php echo ($active_menu ?? '') === 'bookings' ? 'active' : ''; ?>">
                    <i class="fa-solid fa-clipboard-list"></i> My Bookings
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
    </div>
</header>

<div class="store-container">

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
