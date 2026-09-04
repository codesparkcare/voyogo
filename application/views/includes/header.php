<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title : 'Voyogo - Book Cheap Flights, Hotels & Holiday Packages'; ?></title>
    <meta name="description" content="Voyogo is India's leading travel portal for flight ticket bookings, cheap hotel room bookings, holiday packages, and visas. Grab best airfare deals!">
    <meta name="keywords" content="flight booking, hotel booking, cheap flights, voyogo, akbar travels, hotel reservation">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Main Stylesheet -->
    <link rel="stylesheet" href="<?php echo function_exists('base_url') ? base_url('assets/css/style.css?v=' . time()) : '/assets/css/style.css?v=' . time(); ?>">
    
    <?php if (isset($active_page) && in_array($active_page, array('holidays', 'visa', 'forex', 'cruises', 'cabs', 'buses'))): ?>
    <!-- Pages Stylesheet -->
    <link rel="stylesheet" href="<?php echo function_exists('base_url') ? base_url('assets/css/pages_style.css') : '/assets/css/pages_style.css'; ?>">
    <?php endif; ?>
</head>
<body>

    <!-- Top Utility Bar -->
    <div class="top-strip">
        <div class="container">
            <div class="top-strip-left">
                <div class="top-strip-item">
                    <i class="fa-solid fa-headset"></i>
                    <span>24x7 Support: <strong>1800-123-4567 / +91 22 4066 6000</strong></span>
                </div>
                <div class="top-strip-item">
                    <i class="fa-solid fa-envelope"></i>
                    <span>support@voyogo.com</span>
                </div>
            </div>
            <div class="top-strip-right">
                <div class="top-strip-item">
                    <i class="fa-solid fa-globe"></i>
                    <select class="top-select">
                        <option value="en-in">India (INR ₹)</option>
                        <option value="en-ae">UAE (AED د.إ)</option>
                        <option value="en-us">USA (USD $)</option>
                        <option value="en-uk">UK (GBP £)</option>
                    </select>
                </div>
                <div class="top-strip-item">
                    <a href="#" style="color: #cbd5e1;"><i class="fa-solid fa-briefcase"></i> Voyogo Business</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Navigation Header -->
    <header class="main-header">
        <div class="container">
            <div class="header-wrapper">
                
                <!-- Brand Logo -->
                <a href="<?php echo function_exists('site_url') ? site_url('/') : '/'; ?>" class="brand-logo">
                    <img src="<?php echo function_exists('base_url') ? base_url('assets/images/logo.png') : './assets/images/logo.png'; ?>" alt="Voyogo Logo" style="max-height: 48px; width: auto;">
                </a>

                <!-- Navigation Links -->
                <?php $current_page = isset($active_page) ? $active_page : 'flight'; ?>
                <nav class="nav-menu">
                    <div class="nav-item">
                        <a href="<?php echo function_exists('site_url') ? site_url('flight') : '/index.php/flight'; ?>" class="nav-link <?php echo ($current_page == 'flight') ? 'active' : ''; ?>">
                            <i class="fa-solid fa-plane"></i>
                            <span>Flights</span>
                        </a>
                    </div>
                    <div class="nav-item">
                        <a href="<?php echo function_exists('site_url') ? site_url('hotels') : '/index.php/hotels'; ?>" class="nav-link <?php echo ($current_page == 'hotels') ? 'active' : ''; ?>">
                            <i class="fa-solid fa-hotel"></i>
                            <span>Hotels</span>
                            <span class="nav-badge">SAVE 25%</span>
                        </a>
                    </div>
                    <div class="nav-item">
                        <a href="<?php echo function_exists('site_url') ? site_url('holidays') : '/index.php/holidays'; ?>" class="nav-link <?php echo ($current_page == 'holidays') ? 'active' : ''; ?>">
                            <i class="fa-solid fa-umbrella-beach"></i>
                            <span>Holidays</span>
                        </a>
                    </div>
                    <div class="nav-item">
                        <a href="<?php echo function_exists('site_url') ? site_url('visa') : '/index.php/visa'; ?>" class="nav-link <?php echo ($current_page == 'visa') ? 'active' : ''; ?>">
                            <i class="fa-solid fa-passport"></i>
                            <span>Visas</span>
                        </a>
                    </div>
                    <div class="nav-item">
                        <a href="<?php echo function_exists('site_url') ? site_url('forex') : '/index.php/forex'; ?>" class="nav-link <?php echo ($current_page == 'forex') ? 'active' : ''; ?>">
                            <i class="fa-solid fa-money-bill-transfer"></i>
                            <span>Forex</span>
                        </a>
                    </div>
                    <div class="nav-item">
                        <a href="<?php echo function_exists('site_url') ? site_url('cruises') : '/index.php/cruises'; ?>" class="nav-link <?php echo ($current_page == 'cruises') ? 'active' : ''; ?>">
                            <i class="fa-solid fa-ship"></i>
                            <span>Cruises</span>
                        </a>
                    </div>
                    <div class="nav-item">
                        <a href="<?php echo function_exists('site_url') ? site_url('cabs') : '/index.php/cabs'; ?>" class="nav-link <?php echo ($current_page == 'cabs') ? 'active' : ''; ?>">
                            <i class="fa-solid fa-taxi"></i>
                            <span>Cabs</span>
                        </a>
                    </div>
                </nav>

                <!-- Header Right Action Buttons -->
                <div class="header-actions">
                    <button class="btn-login" id="loginModalBtn">
                        <i class="fa-solid fa-user"></i>
                        <span>Login / Sign Up</span>
                    </button>
                    <button class="mobile-menu-btn" aria-label="Toggle Mobile Menu">
                        <i class="fa-solid fa-bars"></i>
                    </button>
                </div>

            </div>
        </div>
    </header>

    <!-- Login / Signup Modal -->
    <div class="modal-backdrop" id="loginModal">
        <div class="modal-box">
            <button class="modal-close" id="closeLoginModal"><i class="fa-solid fa-xmark"></i></button>
            <div style="text-align: center; margin-bottom: 20px;">
                <h3 style="font-family: var(--font-heading); font-size: 22px; color: var(--primary-dark);">Welcome to Voyogo</h3>
                <p style="font-size: 13px; color: var(--text-muted);">Sign in to unlock exclusive flight fares & hotel cashbacks</p>
            </div>
            <form onsubmit="event.preventDefault(); alert('Login successful! Welcome to Voyogo.'); document.getElementById('loginModal').classList.remove('open');">
                <div style="margin-bottom: 16px;">
                    <label style="display: block; font-size: 12px; font-weight: 700; margin-bottom: 6px;">Email or Mobile Number</label>
                    <input type="text" class="field-input" placeholder="Enter your email or 10-digit mobile" required style="width: 100%; padding: 12px; border: 1px solid var(--border-color); border-radius: var(--radius-md);">
                </div>
                <div style="margin-bottom: 20px;">
                    <label style="display: block; font-size: 12px; font-weight: 700; margin-bottom: 6px;">Password</label>
                    <input type="password" class="field-input" placeholder="Enter password" required style="width: 100%; padding: 12px; border: 1px solid var(--border-color); border-radius: var(--radius-md);">
                </div>
                <button type="submit" class="btn-search" style="width: 100%; padding: 12px; justify-content: center;">Continue</button>
            </form>
        </div>
    </div>
