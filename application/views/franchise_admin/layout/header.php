<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($title) ? htmlspecialchars($title) : 'Franchise Admin - Voyogo'; ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Outfit:wght@600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --sidebar-bg: #09204b;
            --sidebar-hover: #0d3470;
            --accent-green: #78B722;
            --body-bg: #f8fafc;
            --card-bg: #ffffff;
            --text-dark: #0f172a;
            --text-muted: #64748b;
            --border: #e2e8f0;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Inter', sans-serif; }
        body { background-color: var(--body-bg); color: var(--text-dark); display: flex; min-height: 100vh; }
        
        /* Sidebar */
        .admin-sidebar {
            width: 260px;
            background: var(--sidebar-bg);
            color: #ffffff;
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0; bottom: 0; left: 0;
            z-index: 100;
        }
        .sidebar-brand {
            padding: 24px 20px;
            display: flex;
            align-items: center;
            gap: 12px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        .sidebar-brand img { max-height: 38px; }
        .sidebar-brand span {
            font-family: 'Outfit', sans-serif;
            font-size: 15px;
            font-weight: 700;
            color: #ffffff;
            line-height: 1.2;
        }
        .sidebar-brand small {
            display: block;
            font-size: 11px;
            color: var(--accent-green);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .sidebar-nav {
            padding: 20px 12px;
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 6px;
        }
        .nav-item a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            color: #cbd5e1;
            text-decoration: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.2s;
        }
        .nav-item a:hover {
            background: var(--sidebar-hover);
            color: #ffffff;
        }
        .nav-item.active a {
            background: var(--accent-green);
            color: #ffffff;
            font-weight: 700;
        }
        .nav-item a i { width: 20px; text-align: center; }
        .sidebar-footer {
            padding: 16px 20px;
            border-top: 1px solid rgba(255,255,255,0.1);
            font-size: 13px;
        }
        .btn-logout {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #f87171;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.2s;
        }
        .btn-logout:hover { color: #ef4444; }

        /* Main Content */
        .admin-main {
            margin-left: 260px;
            flex: 1;
            display: flex;
            flex-direction: column;
            min-width: 0;
        }
        .admin-topbar {
            background: #ffffff;
            border-bottom: 1px solid var(--border);
            padding: 16px 32px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 90;
        }
        .page-title {
            font-family: 'Outfit', sans-serif;
            font-size: 22px;
            color: var(--sidebar-bg);
            font-weight: 700;
        }
        .user-pill {
            display: flex;
            align-items: center;
            gap: 10px;
            background: #f1f5f9;
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
        }
        .admin-content {
            padding: 32px;
            flex: 1;
        }

        /* Common Components */
        .card {
            background: var(--card-bg);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 24px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
            margin-bottom: 24px;
        }
        .table-responsive { overflow-x: auto; }
        table.data-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13.5px;
        }
        table.data-table th {
            background: #f8fafc;
            color: #475569;
            text-align: left;
            padding: 12px 16px;
            font-weight: 600;
            border-bottom: 1px solid var(--border);
        }
        table.data-table td {
            padding: 14px 16px;
            border-bottom: 1px solid var(--border);
            color: #1e293b;
            vertical-align: middle;
        }
        table.data-table tr:hover td { background: #fdfdfd; }
        
        .badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
        }
        .badge-success { background: #dcfce7; color: #15803d; }
        .badge-danger { background: #fee2e2; color: #b91c1c; }
        .badge-info { background: #e0f2fe; color: #0369a1; }
        
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 16px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            border: none;
            cursor: pointer;
            transition: opacity 0.15s;
        }
        .btn:hover { opacity: 0.9; }
        .btn-primary { background: #2563eb; color: #fff; }
        .btn-success { background: #16a34a; color: #fff; }
        .btn-danger { background: #dc2626; color: #fff; }
        .btn-secondary { background: #64748b; color: #fff; }
        .btn-sm { padding: 5px 10px; font-size: 12px; }

        /* Alerts */
        .alert {
            padding: 14px 18px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 13.5px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .alert-success { background: #f0fdf4; border: 1px solid #bbf7d0; color: #166534; }
        .alert-error { background: #fef2f2; border: 1px solid #fecaca; color: #991b1b; }
    </style>
</head>
<body>

<!-- Sidebar -->
<aside class="admin-sidebar">
    <div class="sidebar-brand">
        <img src="<?php echo base_url('assets/images/logo.png'); ?>" alt="Voyogo Logo">
        <div>
            <span>Voyogo</span>
            <small>Franchise Admin</small>
        </div>
    </div>

    <ul class="sidebar-nav">
        <li class="nav-item <?php echo ($active_menu == 'dashboard') ? 'active' : ''; ?>">
            <a href="<?php echo site_url('franchise-admin'); ?>">
                <i class="fa-solid fa-gauge-high"></i>
                <span>Dashboard</span>
            </a>
        </li>
        <li class="nav-item <?php echo ($active_menu == 'stores') ? 'active' : ''; ?>">
            <a href="<?php echo site_url('franchise-admin/stores'); ?>">
                <i class="fa-solid fa-store"></i>
                <span>Store Owners</span>
            </a>
        </li>
        <li class="nav-item <?php echo ($active_menu == 'wallets') ? 'active' : ''; ?>">
            <a href="<?php echo site_url('franchise-admin/wallets'); ?>">
                <i class="fa-solid fa-wallet"></i>
                <span>Wallet Float</span>
            </a>
        </li>
        <li class="nav-item <?php echo ($active_menu == 'bookings') ? 'active' : ''; ?>">
            <a href="<?php echo site_url('franchise-admin/bookings'); ?>">
                <i class="fa-solid fa-receipt"></i>
                <span>All Bookings</span>
            </a>
        </li>
    </ul>

    <div class="sidebar-footer">
        <a href="<?php echo site_url('franchise-admin/logout'); ?>" class="btn-logout">
            <i class="fa-solid fa-arrow-right-from-bracket"></i>
            <span>Sign Out</span>
        </a>
    </div>
</aside>

<!-- Main Container -->
<div class="admin-main">
    <header class="admin-topbar">
        <h1 class="page-title"><?php echo isset($title) ? htmlspecialchars($title) : 'Dashboard'; ?></h1>
        <div class="user-pill">
            <i class="fa-solid fa-user-gear" style="color: var(--accent-green);"></i>
            <span><?php echo htmlspecialchars($this->session->userdata('franchise_admin_name') ?: 'Master Admin'); ?></span>
        </div>
    </header>

    <main class="admin-content">
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
