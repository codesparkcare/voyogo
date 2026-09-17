<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hostel Management | Admin Dashboard</title>
    <!-- Core CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary: #7c3aed; /* Vibrant Purple */
            --primary-hover: #000000; /* Black */
            --secondary: #64748b;
            --success: #10b981;
            --danger: #ef4444;
            --warning: #f59e0b;
            --dark: #0f172a; /* Reset dark from pure black */
            --dark-menu: #1e293b;
            --light: #ffffff;
            --gray-100: #f8fafc;
            --gray-200: #e2e8f0;
            
            /* Sidebar Purple Theme */
            --sidebar-bg: #4c1d95; /* Deep Purple */
            --sidebar-header: #2e1065; /* Darker Purple */
            --sidebar-hover: #000000; /* Black for hover */
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--gray-100);
            color: #334155;
            overflow-x: hidden;
        }

        /* Bootstrap Overrides for Custom Primary Color */
        .text-primary { color: var(--primary) !important; }
        .bg-primary { background-color: var(--primary) !important; }
        .border-primary { border-color: var(--primary) !important; }
        .btn-primary { 
            background-color: var(--primary) !important; 
            border-color: var(--primary) !important; 
            color: #ffffff !important;
            font-weight: 600;
        }
        .btn-primary:hover {
            background-color: var(--primary-hover) !important;
            border-color: var(--primary-hover) !important;
            color: #ffffff !important;
        }
        .btn-outline-primary {
            color: var(--primary) !important;
            border-color: var(--primary) !important;
        }
        .btn-outline-primary:hover {
            background-color: var(--primary) !important;
            color: #ffffff !important;
        }

        /* Wrapper */
        .wrapper {
            display: flex;
            width: 100%;
            align-items: stretch;
            min-height: 100vh;
        }

        /* Sidebar Styling */
        #sidebar {
            min-width: 260px;
            max-width: 260px;
            background: var(--sidebar-bg);
            color: #fff;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            z-index: 1000;
        }

        #sidebar.collapsed {
            margin-left: -260px;
        }

        .sidebar-header {
            padding: 24px;
            background: var(--sidebar-header);
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .sidebar-header .logo-icon {
            font-size: 24px;
            color: var(--primary);
            background: rgba(255, 255, 255, 0.1);
            padding: 8px 12px;
            border-radius: 8px;
        }

        .sidebar-menu {
            padding: 10px 0;
            list-style: none;
            margin: 0;
        }

        .sidebar-menu li {
            padding: 5px 20px;
        }

        .sidebar-menu a {
            display: flex;
            align-items: center;
            color: #94a3b8;
            padding: 12px 15px;
            text-decoration: none;
            border-radius: 8px;
            transition: all 0.3s;
            font-weight: 500;
            gap: 12px;
        }

        .sidebar-menu a:hover,
        .sidebar-menu a.active {
            background: var(--sidebar-hover);
            color: #ffffff;
            transform: translateX(5px);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.5);
        }

        .sidebar-menu a i {
            width: 20px;
            text-align: center;
            font-size: 1.1rem;
        }

        .menu-title {
            color: #64748b;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            padding: 15px 20px 5px;
            font-weight: 600;
        }

        /* Main Content */
        #content {
            width: 100%;
            min-height: 100vh;
            transition: all 0.3s;
            display: flex;
            flex-direction: column;
        }

        /* Top Navbar */
        .top-navbar {
            background: white;
            padding: 15px 25px;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.05);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .navbar-btn {
            background: none;
            border: none;
            font-size: 1.2rem;
            color: var(--secondary);
            cursor: pointer;
            padding: 8px 12px;
            border-radius: 6px;
            transition: all 0.2s;
        }

        .navbar-btn:hover {
            background: var(--gray-100);
            color: var(--primary);
        }

        .user-profile {
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
        }

        .user-profile img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid white;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .user-profile .info {
            display: flex;
            flex-direction: column;
        }
        
        .user-profile .name {
            font-weight: 600;
            font-size: 0.9rem;
            color: var(--dark);
        }
        
        .user-profile .role {
            font-size: 0.75rem;
            color: var(--secondary);
        }

        /* Modern KPI Stat Cards */
        .kpi-card {
            background: #ffffff;
            border-radius: 16px;
            padding: 20px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 1px 3px rgba(0,0,0,0.03), 0 4px 12px rgba(0,0,0,0.02);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            position: relative;
            overflow: hidden;
        }
        .kpi-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(0,0,0,0.06);
        }
        .kpi-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            flex-shrink: 0;
        }
        .pulse-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #f59e0b;
            display: inline-block;
            box-shadow: 0 0 0 0 rgba(245, 158, 11, 0.7);
            animation: pulse-ring 1.8s infinite cubic-bezier(0.66, 0, 0, 1);
        }
        @keyframes pulse-ring {
            0% { box-shadow: 0 0 0 0 rgba(245, 158, 11, 0.7); }
            70% { box-shadow: 0 0 0 6px rgba(245, 158, 11, 0); }
            100% { box-shadow: 0 0 0 0 rgba(245, 158, 11, 0); }
        }

        /* Modern Table Card */
        .table-card {
            background: #ffffff;
            border-radius: 16px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 1px 3px rgba(0,0,0,0.04);
            overflow: hidden;
        }
        .modern-table {
            margin-bottom: 0;
            width: 100%;
        }
        .modern-table thead th {
            background: #f8fafc;
            color: #475569;
            font-weight: 700;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            padding: 14px 18px;
            border-bottom: 1px solid #e2e8f0;
            border-top: none;
            white-space: nowrap;
        }
        .modern-table tbody td {
            padding: 14px 18px;
            vertical-align: middle;
            border-bottom: 1px solid #f1f5f9;
            font-size: 13.5px;
            color: #1e293b;
        }
        .modern-table tbody tr:hover td {
            background-color: #f8fafc;
        }
        .modern-table tbody tr:last-child td {
            border-bottom: none;
        }

        /* Applicant Avatar Pill */
        .avatar-initials {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 13px;
            flex-shrink: 0;
            letter-spacing: 0.5px;
        }

        /* Status Select Pill (Zero Clipping, 1-Click Update) */
        .status-select-pill {
            display: inline-block;
            font-size: 11.5px;
            font-weight: 700;
            border-radius: 50px;
            padding: 4px 28px 4px 12px;
            border-width: 1.5px;
            border-style: solid;
            cursor: pointer;
            outline: none;
            transition: all 0.2s ease;
            appearance: none;
            -webkit-appearance: none;
            background-repeat: no-repeat;
            background-position: right 8px center;
            background-size: 10px 10px;
        }
        .status-select-pill:focus {
            box-shadow: 0 0 0 3px rgba(124, 58, 237, 0.15);
        }

        /* Status Themes */
        .st-new {
            background-color: #fffbeb !important;
            color: #b45309 !important;
            border-color: #fde68a !important;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20' fill='%23b45309'%3E%3Cpath fill-rule='evenodd' d='M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z' clip-rule='evenodd'/%3E%3C/svg%3E");
        }
        .st-contacted, .st-quoted, .st-held {
            background-color: #eff6ff !important;
            color: #1d4ed8 !important;
            border-color: #bfdbfe !important;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20' fill='%231d4ed8'%3E%3Cpath fill-rule='evenodd' d='M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z' clip-rule='evenodd'/%3E%3C/svg%3E");
        }
        .st-progress, .st-assigned, .st-followup, .st-pending {
            background-color: #f5f3ff !important;
            color: #6d28d9 !important;
            border-color: #ddd6fe !important;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20' fill='%236d28d9'%3E%3Cpath fill-rule='evenodd' d='M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z' clip-rule='evenodd'/%3E%3C/svg%3E");
        }
        .st-completed, .st-processed, .st-booked, .st-delivered {
            background-color: #ecfdf5 !important;
            color: #047857 !important;
            border-color: #a7f3d0 !important;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20' fill='%23047857'%3E%3Cpath fill-rule='evenodd' d='M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z' clip-rule='evenodd'/%3E%3C/svg%3E");
        }
        .st-closed, .st-cancelled, .st-lost {
            background-color: #f8fafc !important;
            color: #64748b !important;
            border-color: #cbd5e1 !important;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20' fill='%2364748b'%3E%3Cpath fill-rule='evenodd' d='M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z' clip-rule='evenodd'/%3E%3C/svg%3E");
        }

        /* Action Icon Buttons */
        .btn-action-icon {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: 1px solid #e2e8f0;
            background: #ffffff;
            color: #475569;
            transition: all 0.15s ease;
            font-size: 12px;
            cursor: pointer;
            text-decoration: none;
        }
        .btn-action-icon:hover {
            background: #f1f5f9;
            color: var(--primary);
            border-color: #cbd5e1;
            transform: scale(1.05);
        }
        .btn-action-icon.danger:hover {
            background: #fef2f2;
            color: #dc2626;
            border-color: #fecaca;
        }

        /* Custom Pagination */
        .pagination-custom .page-link {
            border-radius: 8px !important;
            margin: 0 2px;
            color: #475569;
            border: 1px solid #e2e8f0;
            padding: 6px 12px;
            font-size: 13px;
            font-weight: 600;
            transition: all 0.15s ease;
        }
        .pagination-custom .page-link:hover {
            background: #f1f5f9;
            color: var(--primary);
            border-color: #cbd5e1;
        }
        .pagination-custom .page-item.active .page-link {
            background-color: var(--primary) !important;
            border-color: var(--primary) !important;
            color: #ffffff !important;
            box-shadow: 0 2px 4px rgba(124, 58, 237, 0.3);
        }
        .pagination-custom .page-item.disabled .page-link {
            color: #94a3b8;
            background: #f8fafc;
            border-color: #e2e8f0;
        }

        /* Detail Modal Styling */
        .detail-box {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 14px 16px;
        }
        .detail-label {
            font-size: 11px;
            font-weight: 700;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 4px;
        }
        .detail-val {
            font-size: 14px;
            font-weight: 600;
            color: #0f172a;
            word-break: break-word;
        }
    </style>
</head>
<body>
    <div class="wrapper">
