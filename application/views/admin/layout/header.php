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
    </style>
</head>
<body>
    <div class="wrapper">
