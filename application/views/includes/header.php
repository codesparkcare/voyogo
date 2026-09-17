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
    <link rel="stylesheet" href="<?php echo function_exists('base_url') ? base_url('assets/css/pages_style.css?v=' . time()) : '/assets/css/pages_style.css?v=' . time(); ?>">
    <?php endif; ?>
    <!-- Firebase App & Auth Compat SDKs -->
    <script src="https://www.gstatic.com/firebasejs/10.8.0/firebase-app-compat.js"></script>
    <script src="https://www.gstatic.com/firebasejs/10.8.0/firebase-auth-compat.js"></script>
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
                    <a href="<?php echo function_exists('site_url') ? site_url('franchise/login') : '/index.php/franchise/login'; ?>" style="color: #cbd5e1;"><i class="fa-solid fa-briefcase"></i> Voyogo Business</a>
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
                <?php $current_page = isset($active_page) ? $active_page : (isset($current_page) ? $current_page : 'flight'); ?>
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
                    <?php if (isset($this->session) && $this->session->userdata('user_logged_in')): ?>
                        <!-- Logged In: User Profile & Logout Dropdown -->
                        <div class="user-dropdown-wrap" style="position: relative;">
                            <button type="button" class="btn-login user-menu-trigger" id="userMenuBtn" style="background: #09204b; border: 1.5px solid #78B722; padding: 6px 14px; border-radius: 25px; display: flex; align-items: center; gap: 8px; cursor: pointer; color: #ffffff;">
                                <div style="width: 28px; height: 28px; border-radius: 50%; background: #78B722; color: #ffffff; display: flex; align-items: center; justify-content: center; font-size: 13px; font-weight: 800;">
                                    <?php 
                                        $uName = $this->session->userdata('user_name');
                                        echo strtoupper(substr($uName ?: 'U', 0, 1)); 
                                    ?>
                                </div>
                                <span style="font-size: 13px; font-weight: 700; max-width: 110px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                    <?php echo htmlspecialchars($this->session->userdata('user_name') ?: $this->session->userdata('user_phone')); ?>
                                </span>
                                <i class="fa-solid fa-chevron-down" style="font-size: 10px; opacity: 0.8;"></i>
                            </button>
                            <div class="user-dropdown-menu" id="userDropdownMenu" style="position: absolute; right: 0; top: calc(100% + 8px); background: #ffffff; border-radius: 10px; box-shadow: 0 10px 25px rgba(0,0,0,0.15); border: 1px solid #e2e8f0; min-width: 200px; display: none; z-index: 10000; overflow: hidden; text-align: left;">
                                <div style="padding: 12px 16px; border-bottom: 1px solid #f1f5f9; background: #f8fafc;">
                                    <div style="font-size: 11px; color: #64748b; font-weight: 700;">LOGGED IN AS</div>
                                    <div style="font-size: 13px; font-weight: 700; color: #0f172a; margin-top: 2px;"><?php echo htmlspecialchars($this->session->userdata('user_phone')); ?></div>
                                </div>
                                <a href="<?php echo site_url('user/profile'); ?>" style="display: flex; align-items: center; gap: 10px; padding: 12px 16px; color: #0f172a; text-decoration: none; font-size: 13.5px; font-weight: 600; transition: background 0.15s;">
                                    <i class="fa-solid fa-user-gear" style="color: #78B722; width: 16px;"></i> My Profile
                                </a>
                                <a href="<?php echo site_url('user/logout'); ?>" style="display: flex; align-items: center; gap: 10px; padding: 12px 16px; color: #ef4444; text-decoration: none; font-size: 13.5px; font-weight: 600; border-top: 1px solid #f1f5f9; transition: background 0.15s;">
                                    <i class="fa-solid fa-arrow-right-from-bracket" style="width: 16px;"></i> Logout
                                </a>
                            </div>
                        </div>
                    <?php else: ?>
                        <!-- Logged Out: Login / Sign Up Button -->
                        <button class="btn-login" id="loginModalBtn">
                            <i class="fa-solid fa-user"></i>
                            <span>Login / Sign Up</span>
                        </button>
                    <?php endif; ?>

                    <button class="mobile-menu-btn" aria-label="Toggle Mobile Menu">
                        <i class="fa-solid fa-bars"></i>
                    </button>
                </div>

            </div>
        </div>
    </header>
    
    <?php if (isset($this->session) && ($this->session->flashdata('success') || $this->session->flashdata('error'))): ?>
        <div id="globalFlashToast" style="position: fixed; top: 25px; right: 25px; z-index: 999999; max-width: 420px; box-shadow: 0 12px 35px rgba(0,0,0,0.2); border-radius: 12px; overflow: hidden; animation: fadeIn 0.3s ease;">
            <?php if ($this->session->flashdata('success')): ?>
                <div style="background: linear-gradient(135deg, #15803d 0%, #16a34a 100%); color: #ffffff; padding: 14px 20px; display: flex; align-items: center; gap: 12px; font-weight: 700; font-size: 14px;">
                    <i class="fa-solid fa-circle-check" style="font-size: 18px;"></i>
                    <span><?php echo htmlspecialchars($this->session->flashdata('success')); ?></span>
                    <button type="button" onclick="document.getElementById('globalFlashToast').remove();" style="background: none; border: none; color: #ffffff; margin-left: auto; cursor: pointer; font-size: 16px; opacity: 0.8;"><i class="fa-solid fa-xmark"></i></button>
                </div>
            <?php elseif ($this->session->flashdata('error')): ?>
                <div style="background: linear-gradient(135deg, #b91c1c 0%, #dc2626 100%); color: #ffffff; padding: 14px 20px; display: flex; align-items: center; gap: 12px; font-weight: 700; font-size: 14px;">
                    <i class="fa-solid fa-triangle-exclamation" style="font-size: 18px;"></i>
                    <span><?php echo htmlspecialchars($this->session->flashdata('error')); ?></span>
                    <button type="button" onclick="document.getElementById('globalFlashToast').remove();" style="background: none; border: none; color: #ffffff; margin-left: auto; cursor: pointer; font-size: 16px; opacity: 0.8;"><i class="fa-solid fa-xmark"></i></button>
                </div>
            <?php endif; ?>
        </div>
        <script>
            setTimeout(function() {
                var t = document.getElementById('globalFlashToast');
                if (t) {
                    t.style.transition = 'all 0.5s ease';
                    t.style.opacity = '0';
                    t.style.transform = 'translateY(-10px)';
                    setTimeout(function() { if (t) t.remove(); }, 500);
                }
            }, 4000);
        </script>
    <?php endif; ?>

    <!-- Firebase Phone OTP Authentication Modal -->
    <div class="modal-backdrop" id="loginModal">
        <div class="modal-box" style="max-width: 420px; border-radius: 16px; padding: 28px 26px; box-shadow: 0 20px 50px rgba(0,0,0,0.25);">
            <button class="modal-close" id="closeLoginModal" type="button"><i class="fa-solid fa-xmark"></i></button>
            
            <div style="text-align: center; margin-bottom: 22px;">
                <div style="width: 52px; height: 52px; border-radius: 50%; background: #e8f5e9; color: #78B722; display: flex; align-items: center; justify-content: center; margin: 0 auto 12px auto; font-size: 24px;">
                    <i class="fa-solid fa-mobile-screen-button"></i>
                </div>
                <h3 style="font-family: var(--font-heading); font-size: 22px; color: var(--primary-dark); margin-bottom: 4px;">Welcome to Voyogo</h3>
                <p style="font-size: 13px; color: var(--text-muted);">Sign in with OTP to unlock exclusive fares & cashbacks</p>
            </div>

            <!-- Error / Status Alert Box -->
            <div id="otpAlertBox" style="display: none; padding: 10px 14px; border-radius: 8px; font-size: 12.5px; font-weight: 600; margin-bottom: 16px;"></div>

            <!-- STEP 1: Enter Mobile Number Form -->
            <div id="phoneStepBox">
                <form id="phoneSubmitForm" onsubmit="event.preventDefault(); handleSendOtp();">
                    <div style="margin-bottom: 18px;">
                        <label style="display: block; font-size: 12px; font-weight: 700; color: #0f172a; margin-bottom: 6px;">Mobile Number</label>
                        <div style="display: flex; align-items: center; border: 1.5px solid #cbd5e1; border-radius: 8px; overflow: hidden; background: #ffffff; transition: border-color 0.2s;">
                            <span style="background: #f1f5f9; padding: 12px 14px; font-size: 14px; font-weight: 700; color: #475569; border-right: 1px solid #e2e8f0;">+91</span>
                            <input type="tel" id="userPhoneInput" placeholder="Enter 10-digit mobile" maxlength="10" pattern="[0-9]{10}" required style="flex: 1; padding: 12px 14px; border: none; font-size: 15px; font-weight: 700; color: #0f172a; outline: none;">
                        </div>
                        <span style="font-size: 11px; color: #64748b; margin-top: 4px; display: block;">We will send a 6-digit verification code via SMS</span>
                    </div>

                    <!-- Invisible Recaptcha Container -->
                    <div id="recaptcha-container"></div>

                    <button type="submit" id="btnSendOtp" class="btn-search" style="width: 100%; padding: 13px; justify-content: center; font-size: 14px; font-weight: 800; border-radius: 8px;">
                        <span>Get OTP</span> <i class="fa-solid fa-arrow-right" style="font-size: 13px;"></i>
                    </button>
                </form>
            </div>

            <!-- STEP 2: Enter 6-Digit OTP Form -->
            <div id="otpStepBox" style="display: none;">
                <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 10px 14px; display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                    <div>
                        <div style="font-size: 11px; color: #64748b;">Code sent to</div>
                        <div style="font-size: 13.5px; font-weight: 800; color: #0f172a;" id="displayTargetPhone">+91 XXXXXXXXXX</div>
                    </div>
                    <button type="button" onclick="switchBackToPhoneStep()" style="background: none; border: none; color: #78B722; font-size: 12px; font-weight: 700; cursor: pointer;">
                        <i class="fa-solid fa-pen-to-square"></i> Change
                    </button>
                </div>

                <form id="otpSubmitForm" onsubmit="event.preventDefault(); handleVerifyOtp();">
                    <div style="margin-bottom: 18px;">
                        <label style="display: block; font-size: 12px; font-weight: 700; color: #0f172a; margin-bottom: 6px;">Enter 6-Digit OTP</label>
                        <input type="text" id="userOtpInput" placeholder="Enter 6-digit OTP" maxlength="6" pattern="[0-9]{6}" required style="width: 100%; padding: 12px 14px; border: 1.5px solid #cbd5e1; border-radius: 8px; font-size: 20px; font-weight: 800; text-align: center; letter-spacing: 8px; color: #0f172a; outline: none; box-sizing: border-box;">
                    </div>

                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 18px; font-size: 12.5px;">
                        <span id="resendCountdown" style="color: #64748b; font-weight: 600;">Resend OTP in <strong id="timerSec">30</strong>s</span>
                        <button type="button" id="btnResendOtp" onclick="handleSendOtp(true)" style="display: none; background: none; border: none; color: #78B722; font-weight: 700; cursor: pointer;">Resend OTP</button>
                    </div>

                    <button type="submit" id="btnVerifyOtp" class="btn-search" style="width: 100%; padding: 13px; justify-content: center; font-size: 14px; font-weight: 800; border-radius: 8px;">
                        <i class="fa-solid fa-shield-check"></i> <span>Verify & Continue</span>
                    </button>
                </form>
            </div>

            <div style="margin-top: 18px; text-align: center; font-size: 11.5px; color: #94a3b8;">
                By continuing, you agree to Voyogo's <a href="#" style="color: #78B722; text-decoration: none;">Terms of Service</a> & <a href="#" style="color: #78B722; text-decoration: none;">Privacy Policy</a>.
            </div>
        </div>
    </div>

    <!-- Firebase Phone OTP Authentication Script -->
    <script>
    // 1. Exact Firebase Configuration provided by user
    const firebaseConfig = {
        apiKey: "AIzaSyDwKDxQGL0SGFviveAOscLh2GEhyqJaYto",
        authDomain: "voyogos-31bb7.firebaseapp.com",
        projectId: "voyogos-31bb7",
        storageBucket: "voyogos-31bb7.firebasestorage.app",
        messagingSenderId: "223978461369",
        appId: "1:223978461369:web:d76b7ee6e12ec3a97aa368",
        measurementId: "G-P4VFC9V80V"
    };

    if (!firebase.apps.length) {
        firebase.initializeApp(firebaseConfig);
    }

    let confirmationResult = null;
    let recaptchaVerifier = null;
    let resendInterval = null;

    // Helper: Show alert inside modal
    function showOtpAlert(msg, isError = true) {
        const box = document.getElementById('otpAlertBox');
        if (!box) return;
        box.innerText = msg;
        box.style.display = 'block';
        if (isError) {
            box.style.background = '#fef2f2';
            box.style.color = '#991b1b';
            box.style.border = '1px solid #fecaca';
        } else {
            box.style.background = '#ecfdf5';
            box.style.color = '#065f46';
            box.style.border = '1px solid #a7f3d0';
        }
    }

    function hideOtpAlert() {
        const box = document.getElementById('otpAlertBox');
        if (box) box.style.display = 'none';
    }

    // Initialize Recaptcha Verifier
    function initRecaptcha() {
        if (!recaptchaVerifier) {
            recaptchaVerifier = new firebase.auth.RecaptchaVerifier('recaptcha-container', {
                'size': 'invisible',
                'callback': function(response) {
                    // reCAPTCHA solved
                },
                'expired-callback': function() {
                    showOtpAlert('reCAPTCHA expired. Please try sending OTP again.');
                }
            });
        }
    }

    // Step 1: Send OTP
    window.handleSendOtp = function(isResend = false) {
        hideOtpAlert();
        const phoneInput = document.getElementById('userPhoneInput');
        const phone = phoneInput ? phoneInput.value.trim() : '';

        if (!/^[0-9]{10}$/.test(phone)) {
            showOtpAlert('Please enter a valid 10-digit mobile number.');
            return;
        }

        const fullPhoneNumber = '+91' + phone;
        const sendBtn = document.getElementById('btnSendOtp');
        if (sendBtn) {
            sendBtn.disabled = true;
            sendBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> <span>Sending OTP...</span>';
        }

        initRecaptcha();

        firebase.auth().signInWithPhoneNumber(fullPhoneNumber, recaptchaVerifier)
            .then(function(result) {
                confirmationResult = result;
                if (sendBtn) {
                    sendBtn.disabled = false;
                    sendBtn.innerHTML = '<span>Get OTP</span> <i class="fa-solid fa-arrow-right"></i>';
                }

                // Switch UI to Step 2
                document.getElementById('phoneStepBox').style.display = 'none';
                document.getElementById('otpStepBox').style.display = 'block';
                document.getElementById('displayTargetPhone').innerText = fullPhoneNumber;
                document.getElementById('userOtpInput').value = '';
                document.getElementById('userOtpInput').focus();

                showOtpAlert('OTP sent successfully to ' + fullPhoneNumber, false);
                startResendTimer();
            })
            .catch(function(error) {
                if (sendBtn) {
                    sendBtn.disabled = false;
                    sendBtn.innerHTML = '<span>Get OTP</span> <i class="fa-solid fa-arrow-right"></i>';
                }
                console.error('Firebase Phone Auth Error:', error);
                if (error.code === 'auth/invalid-phone-number') {
                    showOtpAlert('Invalid mobile number format.');
                } else if (error.code === 'auth/too-many-requests') {
                    showOtpAlert('Too many requests. Please wait a moment before trying again.');
                } else {
                    showOtpAlert(error.message || 'Failed to send OTP. Please check your internet connection.');
                }

                // Reset recaptcha on error
                if (window.grecaptcha && recaptchaVerifier) {
                    try {
                        grecaptcha.reset(recaptchaVerifier.widgetId);
                    } catch(e) {}
                }
            });
    };

    // Step 2: Verify OTP
    window.handleVerifyOtp = function() {
        hideOtpAlert();
        const otpInput = document.getElementById('userOtpInput');
        const otp = otpInput ? otpInput.value.trim() : '';

        if (!/^[0-9]{6}$/.test(otp)) {
            showOtpAlert('Please enter the complete 6-digit OTP.');
            return;
        }

        if (!confirmationResult) {
            showOtpAlert('Session expired. Please request a new OTP.');
            switchBackToPhoneStep();
            return;
        }

        const verifyBtn = document.getElementById('btnVerifyOtp');
        if (verifyBtn) {
            verifyBtn.disabled = true;
            verifyBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> <span>Verifying...</span>';
        }

        confirmationResult.confirm(otp)
            .then(function(userCredential) {
                const user = userCredential.user;
                const phone = user.phoneNumber || ('+91' + document.getElementById('userPhoneInput').value.trim());
                const uid = user.uid;

                // Helper to resolve login endpoint matching the page protocol
                function getVerifyUrl() {
                    var endpoint = '<?php echo function_exists('site_url') ? site_url('user/verify_firebase_login') : '/index.php/user/verify_firebase_login'; ?>';
                    if (window.location.protocol === 'https:' && endpoint.indexOf('http:') === 0) {
                        endpoint = endpoint.replace(/^http:/, 'https:');
                    }
                    return endpoint;
                }

                // Sync with CodeIgniter Backend Session
                fetch(getVerifyUrl(), {
                    method: 'POST',
                    credentials: 'same-origin',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: 'phone=' + encodeURIComponent(phone) + '&firebase_uid=' + encodeURIComponent(uid)
                })
                .then(function(res) {
                    if (!res.ok) {
                        throw new Error('Server returned error ' + res.status);
                    }
                    return res.json();
                })
                .then(function(data) {
                    if (data.status) {
                        // Check if booking review page has an in-page login handler
                        if (typeof window.onBookingReviewLoginSuccess === 'function') {
                            showOtpAlert('Logged in successfully! Applying your booking details...', false);
                            setTimeout(function() {
                                const modal = document.getElementById('loginModal');
                                if (modal) modal.classList.remove('open');
                                window.onBookingReviewLoginSuccess(data.user);
                            }, 400);
                            return;
                        }

                        showOtpAlert('Logged in successfully! Opening profile...', false);
                        setTimeout(function() {
                            window.location.href = data.redirect_url || '<?php echo function_exists('site_url') ? site_url('user/profile') : '/user/profile'; ?>';
                        }, 500);
                    } else {
                        if (verifyBtn) {
                            verifyBtn.disabled = false;
                            verifyBtn.innerHTML = '<i class="fa-solid fa-shield-check"></i> <span>Verify & Continue</span>';
                        }
                        showOtpAlert(data.message || 'Failed to authenticate on server.');
                    }
                })
                .catch(function(err) {
                    if (verifyBtn) {
                        verifyBtn.disabled = false;
                        verifyBtn.innerHTML = '<i class="fa-solid fa-shield-check"></i> <span>Verify & Continue</span>';
                    }
                    console.error('Server sync error:', err);
                    showOtpAlert('Authentication error: ' + (err.message || 'Server session failed. Please retry.'));
                });
            })
            .catch(function(error) {
                if (verifyBtn) {
                    verifyBtn.disabled = false;
                    verifyBtn.innerHTML = '<i class="fa-solid fa-shield-check"></i> <span>Verify & Continue</span>';
                }
                console.error('OTP confirmation error:', error);
                if (error.code === 'auth/invalid-verification-code') {
                    showOtpAlert('Invalid OTP code. Please check and enter the correct 6-digit code.');
                } else if (error.code === 'auth/code-expired') {
                    showOtpAlert('The OTP has expired. Please click Resend OTP.');
                } else {
                    showOtpAlert(error.message || 'OTP verification failed.');
                }
            });
    };

    function switchBackToPhoneStep() {
        hideOtpAlert();
        clearInterval(resendInterval);
        document.getElementById('otpStepBox').style.display = 'none';
        document.getElementById('phoneStepBox').style.display = 'block';
    }

    function startResendTimer() {
        clearInterval(resendInterval);
        let timeLeft = 30;
        const timerSpan = document.getElementById('timerSec');
        const countdownBox = document.getElementById('resendCountdown');
        const resendBtn = document.getElementById('btnResendOtp');

        if (countdownBox) countdownBox.style.display = 'inline';
        if (resendBtn) resendBtn.style.display = 'none';

        resendInterval = setInterval(function() {
            timeLeft--;
            if (timerSpan) timerSpan.innerText = timeLeft;
            if (timeLeft <= 0) {
                clearInterval(resendInterval);
                if (countdownBox) countdownBox.style.display = 'none';
                if (resendBtn) resendBtn.style.display = 'inline';
            }
        }, 1000);
    }

    // Global trigger for login from review/booking pages
    window.triggerBookingLogin = function(customMsg) {
        const modal = document.getElementById('loginModal');
        if (!modal) return;
        if (customMsg) {
            showOtpAlert(customMsg, false);
            const box = document.getElementById('otpAlertBox');
            if (box) {
                box.style.background = '#eff6ff';
                box.style.color = '#1e40af';
                box.style.border = '1px solid #bfdbfe';
            }
        }
        modal.classList.add('open');
        switchBackToPhoneStep();
        setTimeout(function() {
            const phoneInput = document.getElementById('userPhoneInput');
            if (phoneInput) phoneInput.focus();
        }, 150);
    };

    // Auto-restore session from Firebase if user is already authenticated on this device
    const isPhpLoggedIn = <?php echo (isset($this->session) && $this->session->userdata('user_logged_in')) ? 'true' : 'false'; ?>;
    if (!isPhpLoggedIn && typeof firebase !== 'undefined' && firebase.auth) {
        firebase.auth().onAuthStateChanged(function(fUser) {
            if (fUser && fUser.phoneNumber) {
                var endpoint = '<?php echo function_exists('site_url') ? site_url('user/verify_firebase_login') : '/index.php/user/verify_firebase_login'; ?>';
                if (window.location.protocol === 'https:' && endpoint.indexOf('http:') === 0) {
                    endpoint = endpoint.replace(/^http:/, 'https:');
                }
                fetch(endpoint, {
                    method: 'POST',
                    credentials: 'same-origin',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: 'phone=' + encodeURIComponent(fUser.phoneNumber) + '&firebase_uid=' + encodeURIComponent(fUser.uid)
                })
                .then(function(res) { return res.json(); })
                .then(function(data) {
                    if (data && data.status) {
                        if (typeof window.onBookingReviewLoginSuccess === 'function') {
                            const modal = document.getElementById('loginModal');
                            if (modal) modal.classList.remove('open');
                            window.onBookingReviewLoginSuccess(data.user);
                        } else {
                            window.location.reload();
                        }
                    }
                })
                .catch(function(e) {
                    console.log('Silent auth sync notice:', e);
                });
            }
        });
    }

    // Modal Opening & Closing Events
    document.addEventListener('DOMContentLoaded', function() {
        const loginBtn = document.getElementById('loginModalBtn');
        const modal = document.getElementById('loginModal');
        const closeBtn = document.getElementById('closeLoginModal');

        if (loginBtn && modal) {
            loginBtn.addEventListener('click', function(e) {
                e.preventDefault();
                modal.classList.add('open');
                switchBackToPhoneStep();
                setTimeout(function() {
                    const phoneInput = document.getElementById('userPhoneInput');
                    if (phoneInput) phoneInput.focus();
                }, 150);
            });
        }

        if (closeBtn && modal) {
            closeBtn.addEventListener('click', function() {
                modal.classList.remove('open');
            });
        }

        if (modal) {
            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    modal.classList.remove('open');
                }
            });
        }

        // User Account Dropdown Toggle
        const userMenuBtn = document.getElementById('userMenuBtn');
        const userDropdownMenu = document.getElementById('userDropdownMenu');
        if (userMenuBtn && userDropdownMenu) {
            userMenuBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                const isShown = userDropdownMenu.style.display === 'block';
                userDropdownMenu.style.display = isShown ? 'none' : 'block';
            });

            document.addEventListener('click', function(e) {
                if (!userMenuBtn.contains(e.target) && !userDropdownMenu.contains(e.target)) {
                    userDropdownMenu.style.display = 'none';
                }
            });
        }
    });
    </script>
