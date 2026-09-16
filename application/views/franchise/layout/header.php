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
            --accent-lime: #78B722;
            --accent-lime-dark: #6aa31e;
            --accent-orange: #f97316;
            --bg-gray: #f1f4f9;
            --border-color: #e2e8f0;
            --text-dark: #0f172a;
            --text-muted: #64748b;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Inter', sans-serif; }
        body { background: var(--bg-gray); color: var(--text-dark); min-height: 100vh; display: flex; flex-direction: column; }

        /* Top Navbar */
        .store-navbar {
            background: #ffffff;
            border-bottom: 1px solid var(--border-color);
            padding: 12px 32px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 1px 4px rgba(0,0,0,0.04);
        }
        .nav-left {
            display: flex;
            align-items: center;
            gap: 28px;
        }
        .brand-box {
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
        }
        .brand-box img { max-height: 36px; }
        .b2b-pill {
            background: var(--primary-navy);
            color: #ffffff;
            font-size: 11px;
            font-weight: 700;
            padding: 3px 8px;
            border-radius: 6px;
            letter-spacing: 0.5px;
        }
        .nav-links {
            display: flex;
            gap: 6px;
            list-style: none;
        }
        .nav-links a {
            display: flex;
            align-items: center;
            gap: 7px;
            padding: 8px 14px;
            border-radius: 6px;
            text-decoration: none;
            color: #475569;
            font-size: 13.5px;
            font-weight: 600;
            transition: all 0.15s;
        }
        .nav-links a:hover {
            background: #f8fafc;
            color: var(--primary-navy);
        }
        .nav-links a.active {
            background: #eff6ff;
            color: #2563eb;
            font-weight: 700;
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
            background: #f0fdf4;
            border: 1.5px solid #86efac;
            padding: 6px 16px;
            border-radius: 30px;
            text-decoration: none;
            transition: transform 0.15s;
        }
        .wallet-badge-card:hover { transform: translateY(-1px); }
        .wallet-icon-circle {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background: #16a34a;
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 13px;
        }
        .wallet-label {
            font-size: 11px;
            font-weight: 600;
            color: #166534;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }
        .wallet-amount {
            font-size: 15px;
            font-weight: 800;
            color: #15803d;
        }

        /* Store Owner Profile Badge */
        .store-user-card {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 6px 12px;
            background: #f8fafc;
            border: 1px solid var(--border-color);
            border-radius: 24px;
        }
        .store-avatar {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background: #e2e8f0;
            color: #475569;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 13px;
        }
        .store-info-text {
            line-height: 1.2;
            font-size: 12.5px;
        }
        .store-title {
            font-weight: 700;
            color: var(--text-dark);
            max-width: 140px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .store-code-sub {
            font-size: 11px;
            color: #0284c7;
            font-weight: 600;
        }

        .btn-signout {
            color: #ef4444;
            text-decoration: none;
            font-size: 13px;
            font-weight: 600;
            padding: 6px 10px;
            border-radius: 6px;
            transition: background 0.15s;
        }
        .btn-signout:hover { background: #fee2e2; }

        /* Main Body Wrapper */
        .store-container {
            max-width: 1380px;
            width: 100%;
            margin: 24px auto;
            padding: 0 24px;
            flex: 1;
        }

        /* Flash Messages */
        .alert {
            padding: 14px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 13.5px;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .alert-success { background: #f0fdf4; border: 1px solid #bbf7d0; color: #166534; }
        .alert-error { background: #fef2f2; border: 1px solid #fecaca; color: #991b1b; }
        .alert-warning { background: #fffbeb; border: 1px solid #fde68a; color: #92400e; }
    </style>
</head>
<body>

<header class="store-navbar">
    <div class="nav-left">
        <a href="<?php echo site_url('franchise/flight'); ?>" class="brand-box">
            <img src="<?php echo base_url('assets/images/logo.png'); ?>" alt="Voyogo Logo">
            <span class="b2b-pill">B2B FRANCHISE</span>
        </a>

        <ul class="nav-links">
            <li>
                <a href="<?php echo site_url('franchise/flight'); ?>" class="<?php echo ($active_menu == 'flight') ? 'active' : ''; ?>">
                    <i class="fa-solid fa-plane-departure"></i> Flight
                </a>
            </li>
            <li>
                <a href="<?php echo site_url('franchise/hotel'); ?>" class="<?php echo ($active_menu == 'hotel') ? 'active' : ''; ?>">
                    <i class="fa-solid fa-hotel"></i> Hotel
                </a>
            </li>
            <li>
                <a href="<?php echo site_url('franchise/bookings'); ?>" class="<?php echo ($active_menu == 'bookings') ? 'active' : ''; ?>">
                    <i class="fa-solid fa-receipt"></i> My Bookings
                </a>
            </li>
            <li>
                <a href="<?php echo site_url('franchise/wallet_ledger'); ?>" class="<?php echo ($active_menu == 'wallet_ledger') ? 'active' : ''; ?>">
                    <i class="fa-solid fa-clock-rotate-left"></i> Wallet Passbook
                </a>
            </li>
        </ul>
    </div>

    <div class="nav-right">
        <!-- Live Wallet Balance Display -->
        <a href="<?php echo site_url('franchise/wallet_ledger'); ?>" class="wallet-badge-card" title="Click to view Passbook / Transaction Statement">
            <div class="wallet-icon-circle">
                <i class="fa-solid fa-wallet"></i>
            </div>
            <div>
                <div class="wallet-label">Store Wallet Float</div>
                <div class="wallet-amount">₹ <?php echo number_format($store['wallet_balance'] ?? 0, 2); ?></div>
            </div>
        </a>

        <!-- Store Owner Profile -->
        <div class="store-user-card">
            <div class="store-avatar">
                <i class="fa-solid fa-store"></i>
            </div>
            <div class="store-info-text">
                <div class="store-title"><?php echo htmlspecialchars($store['store_name'] ?? 'Franchise Store'); ?></div>
                <div class="store-code-sub"><?php echo htmlspecialchars($store['agent_code'] ?? ''); ?></div>
            </div>
        </div>

        <a href="<?php echo site_url('franchise/logout'); ?>" class="btn-signout" title="Sign Out">
            <i class="fa-solid fa-arrow-right-from-bracket"></i>
        </a>
    </div>
</header>

<div class="store-container">

    <?php if ($this->session->flashdata('success')): ?>
    <div class="alert alert-success">
        <i class="fa-solid fa-circle-check"></i>
        <span><?php echo htmlspecialchars($this->session->flashdata('success')); ?></span>
    </div>
    <?php endif; ?>

    <?php if ($this->session->flashdata('error')): ?>
    <div class="alert alert-error">
        <i class="fa-solid fa-triangle-exclamation"></i>
        <span><?php echo htmlspecialchars($this->session->flashdata('error')); ?></span>
    </div>
    <?php endif; ?>

    <?php if (($store['wallet_balance'] ?? 0) <= 1000): ?>
    <div class="alert alert-warning">
        <i class="fa-solid fa-circle-info"></i>
        <span><strong>Notice:</strong> Your store wallet balance is low (₹ <?php echo number_format($store['wallet_balance'] ?? 0, 2); ?>). Please contact Franchise Admin to top up your wallet float to avoid booking interruptions.</span>
    </div>
    <?php endif; ?>
