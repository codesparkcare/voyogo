<style>
.profile-wrapper {
    background: #f8fafc;
    min-height: calc(100vh - 240px);
    padding: 50px 20px;
}
.profile-container {
    max-width: 780px;
    margin: 0 auto;
}
.profile-card {
    background: #ffffff;
    border-radius: 16px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.06);
    border: 1px solid #e2e8f0;
    overflow: hidden;
}
.profile-header-strip {
    background: linear-gradient(135deg, #09204b 0%, #0d3470 100%);
    color: #ffffff;
    padding: 32px 36px;
    display: flex;
    align-items: center;
    gap: 20px;
}
.profile-avatar-circle {
    width: 68px;
    height: 68px;
    border-radius: 50%;
    background: #78B722;
    color: #ffffff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 26px;
    font-weight: 800;
    box-shadow: 0 4px 12px rgba(120, 183, 34, 0.4);
    flex-shrink: 0;
}
.profile-title-box h2 {
    font-size: 22px;
    font-weight: 800;
    margin-bottom: 4px;
}
.profile-phone-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: rgba(255, 255, 255, 0.15);
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 12.5px;
    font-weight: 600;
}
.profile-phone-badge i {
    color: #4ade80;
}
.profile-body {
    padding: 36px;
}
.profile-alert {
    padding: 14px 18px;
    border-radius: 10px;
    font-size: 13.5px;
    font-weight: 600;
    margin-bottom: 24px;
    display: flex;
    align-items: center;
    gap: 10px;
}
.profile-alert-success {
    background: #ecfdf5;
    color: #065f46;
    border: 1px solid #a7f3d0;
}
.profile-alert-error {
    background: #fef2f2;
    color: #991b1b;
    border: 1px solid #fecaca;
}
.profile-alert-info {
    background: #eff6ff;
    color: #1e40af;
    border: 1px solid #bfdbfe;
}
.profile-form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
}
@media (max-width: 640px) {
    .profile-form-grid {
        grid-template-columns: 1fr;
    }
}
.profile-field-group {
    margin-bottom: 20px;
}
.profile-field-group.full-width {
    grid-column: 1 / -1;
}
.profile-label-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 8px;
}
.profile-label {
    display: block;
    font-size: 13px;
    font-weight: 700;
    color: #0f172a;
    margin-bottom: 0;
}
.profile-input-wrap {
    position: relative;
}
.profile-input-wrap i {
    position: absolute;
    left: 14px;
    top: 50%;
    transform: translateY(-50%);
    color: #94a3b8;
    font-size: 15px;
}
.profile-input {
    width: 100%;
    padding: 12px 14px 12px 42px;
    border: 1.5px solid #cbd5e1;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 600;
    color: #0f172a;
    background: #ffffff;
    transition: all 0.15s ease;
    box-sizing: border-box;
}
.profile-input:focus {
    outline: none;
    border-color: #78B722;
    box-shadow: 0 0 0 3px rgba(120, 183, 34, 0.15);
}
.profile-input.locked {
    background: #f1f5f9;
    color: #475569;
    cursor: not-allowed;
    border-color: #e2e8f0;
}
.profile-hint {
    font-size: 11.5px;
    color: #64748b;
    margin-top: 6px;
    display: flex;
    align-items: center;
    gap: 5px;
}
.profile-btn-save {
    background: #78B722;
    color: #ffffff;
    border: none;
    padding: 13px 28px;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 800;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: all 0.15s ease;
}
.profile-btn-save:hover {
    background: #6aa31e;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(120, 183, 34, 0.3);
}
.profile-btn-logout {
    color: #ef4444;
    text-decoration: none;
    font-size: 13.5px;
    font-weight: 700;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 12px 20px;
    border-radius: 8px;
    transition: background 0.15s ease;
}
.profile-btn-logout:hover {
    background: #fef2f2;
}

/* Autofill Button Styles */
.autofill-btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: #ffffff;
    border: 1px solid #cbd5e1;
    color: #334155;
    padding: 5px 11px;
    border-radius: 6px;
    font-size: 12px;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.2s ease;
}
.autofill-btn:hover {
    background: #f8fafc;
    border-color: #94a3b8;
    color: #0f172a;
    box-shadow: 0 2px 5px rgba(0,0,0,0.05);
}
.autofill-btn svg {
    flex-shrink: 0;
}
.autofill-feedback {
    display: none;
    align-items: center;
    gap: 6px;
    font-size: 12px;
    font-weight: 600;
    color: #15803d;
    background: #f0fdf4;
    border: 1px solid #bbf7d0;
    padding: 6px 12px;
    border-radius: 6px;
    margin-top: 8px;
}
</style>

<div class="profile-wrapper">
    <div class="profile-container">

        <?php if ($this->session->flashdata('success')): ?>
            <div class="profile-alert profile-alert-success">
                <i class="fa-solid fa-circle-check"></i>
                <span><?php echo $this->session->flashdata('success'); ?></span>
            </div>
        <?php endif; ?>

        <?php if ($this->session->flashdata('error')): ?>
            <div class="profile-alert profile-alert-error">
                <i class="fa-solid fa-circle-exclamation"></i>
                <span><?php echo $this->session->flashdata('error'); ?></span>
            </div>
        <?php endif; ?>

        <?php 
            $isIncomplete = empty($user['first_name']) || empty($user['email']);
            if ($isIncomplete || $this->session->flashdata('welcome_notice')): 
        ?>
            <div class="profile-alert profile-alert-info">
                <i class="fa-solid fa-sparkles"></i>
                <span><strong>Welcome to Voyogo!</strong> Please complete your Name and Email Address below to unlock bookings, tickets, and cashback rewards.</span>
            </div>
        <?php endif; ?>

        <div class="profile-card">
            
            <!-- Header Strip -->
            <div class="profile-header-strip">
                <div class="profile-avatar-circle" id="profileAvatarCircle">
                    <?php 
                        $firstLetter = !empty($user['first_name']) ? strtoupper(substr($user['first_name'], 0, 1)) : 'U';
                        echo $firstLetter;
                    ?>
                </div>
                <div class="profile-title-box">
                    <h2 id="profileTitleName"><?php echo !empty($user['first_name']) ? htmlspecialchars(trim($user['first_name'] . ' ' . ($user['last_name'] ?? ''))) : 'My Account Profile'; ?></h2>
                    <div class="profile-phone-badge">
                        <i class="fa-solid fa-shield-check"></i>
                        <span><?php echo htmlspecialchars($user['phone']); ?></span>
                        <small style="opacity: 0.8;">(Verified via OTP)</small>
                    </div>
                </div>
            </div>

            <!-- Profile Details Form -->
            <div class="profile-body">
                <form action="<?php echo site_url('user/update_profile'); ?>" method="POST" id="userProfileForm">
                    
                    <div class="profile-form-grid">

                        <!-- 1. Phone Number (Locked / Cannot be edited) -->
                        <div class="profile-field-group full-width">
                            <label class="profile-label" style="margin-bottom: 8px;">
                                Phone Number
                                <span style="font-weight: 600; font-size: 11px; color: #16a34a; margin-left: 6px;">
                                    <i class="fa-solid fa-lock"></i> Locked & Verified
                                </span>
                            </label>
                            <div class="profile-input-wrap">
                                <i class="fa-solid fa-phone"></i>
                                <input type="text" class="profile-input locked" value="<?php echo htmlspecialchars($user['phone']); ?>" readonly disabled>
                            </div>
                            <div class="profile-hint">
                                <i class="fa-solid fa-circle-info"></i>
                                <span>Your mobile number is verified via Firebase OTP and cannot be modified.</span>
                            </div>
                        </div>

                        <!-- 2. First Name (Editable) -->
                        <div class="profile-field-group">
                            <label class="profile-label" style="margin-bottom: 8px;">First Name *</label>
                            <div class="profile-input-wrap">
                                <i class="fa-solid fa-user"></i>
                                <input type="text" name="first_name" id="profileFirstName" class="profile-input" 
                                       value="<?php echo htmlspecialchars($user['first_name'] ?? ''); ?>" 
                                       placeholder="Enter first name" 
                                       autocomplete="given-name" required>
                            </div>
                        </div>

                        <!-- 3. Last Name (Editable) -->
                        <div class="profile-field-group">
                            <label class="profile-label" style="margin-bottom: 8px;">Last Name *</label>
                            <div class="profile-input-wrap">
                                <i class="fa-solid fa-user"></i>
                                <input type="text" name="last_name" id="profileLastName" class="profile-input" 
                                       value="<?php echo htmlspecialchars($user['last_name'] ?? ''); ?>" 
                                       placeholder="Enter last name" 
                                       autocomplete="family-name" required>
                            </div>
                        </div>

                        <!-- 4. Email ID (Editable + 1-Tap Autofill) -->
                        <div class="profile-field-group full-width">
                            <div class="profile-label-row">
                                <label class="profile-label">Email Address *</label>
                                
                                <!-- 1-Tap Autofill Button from Google / Device -->
                                <button type="button" class="autofill-btn" id="btnAutofillGoogle" onclick="autofillEmailFromDevice()" title="Tap to select your Google email from device">
                                    <svg width="14" height="14" viewBox="0 0 24 24"><path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/><path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/><path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z"/><path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/></svg>
                                    <span>⚡ Autofill from Google</span>
                                </button>
                            </div>

                            <div class="profile-input-wrap">
                                <i class="fa-solid fa-envelope"></i>
                                <input type="email" name="email" id="profileEmail" class="profile-input" 
                                       value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>" 
                                       placeholder="name@example.com" 
                                       autocomplete="email" 
                                       inputmode="email" required>
                            </div>

                            <!-- Feedback notice when autofilled -->
                            <div class="autofill-feedback" id="autofillFeedback">
                                <i class="fa-solid fa-circle-check"></i>
                                <span id="autofillMsg">Email fetched from device!</span>
                            </div>

                            <div class="profile-hint">
                                <i class="fa-solid fa-circle-info"></i>
                                <span>Booking confirmations, tickets, and payment invoices will be sent to this email.</span>
                            </div>
                        </div>

                    </div>

                    <!-- Submit & Logout Actions -->
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 24px; padding-top: 20px; border-top: 1px solid #f1f5f9; flex-wrap: wrap; gap: 12px;">
                        <button type="submit" class="profile-btn-save">
                            <i class="fa-solid fa-floppy-disk"></i>
                            <span>Save Profile Changes</span>
                        </button>
                        <a href="<?php echo site_url('user/logout'); ?>" onclick="handleUserLogout(event);" class="profile-btn-logout">
                            <i class="fa-solid fa-arrow-right-from-bracket"></i>
                            <span>Logout</span>
                        </a>
                    </div>

                </form>
            </div>

        </div>

    </div>
</div>

<script>
/**
 * 1-Tap Autofill Email & Name from Device / Google Account
 * Uses Firebase Google Auth Provider popup so the user can pick
 * any Google account already logged into their phone/browser without typing.
 */
function autofillEmailFromDevice() {
    const btn = document.getElementById('btnAutofillGoogle');
    const originalContent = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> <span>Selecting...</span>';

    // 1. If Firebase Auth is ready, trigger Google Account selector
    if (typeof firebase !== 'undefined' && firebase.auth) {
        const provider = new firebase.auth.GoogleAuthProvider();
        provider.setCustomParameters({ prompt: 'select_account' });

        firebase.auth().signInWithPopup(provider)
            .then(function(result) {
                btn.disabled = false;
                btn.innerHTML = originalContent;

                const gUser = result.user;
                if (gUser) {
                    if (gUser.email) {
                        const emailField = document.getElementById('profileEmail');
                        if (emailField) {
                            emailField.value = gUser.email;
                            emailField.dispatchEvent(new Event('input', { bubbles: true }));
                        }
                    }

                    if (gUser.displayName) {
                        const names = gUser.displayName.trim().split(' ');
                        const firstName = names[0] || '';
                        const lastName = names.slice(1).join(' ') || '';

                        const fnField = document.getElementById('profileFirstName');
                        const lnField = document.getElementById('profileLastName');
                        if (fnField && (!fnField.value || fnField.value.trim() === '')) {
                            fnField.value = firstName;
                        }
                        if (lnField && (!lnField.value || lnField.value.trim() === '')) {
                            lnField.value = lastName;
                        }

                        // Update avatar & title preview
                        const avatar = document.getElementById('profileAvatarCircle');
                        const title = document.getElementById('profileTitleName');
                        if (avatar && firstName) avatar.innerText = firstName.charAt(0).toUpperCase();
                        if (title && gUser.displayName) title.innerText = gUser.displayName;
                    }

                    // Display friendly success notice
                    const feedback = document.getElementById('autofillFeedback');
                    const msg = document.getElementById('autofillMsg');
                    if (feedback && msg) {
                        msg.innerText = 'Autofilled from Google (' + (gUser.email || '') + ')! Click "Save Profile Changes" to save.';
                        feedback.style.display = 'flex';
                    }
                }
            })
            .catch(function(error) {
                btn.disabled = false;
                btn.innerHTML = originalContent;
                console.warn('Google Account autofill notice:', error);

                if (error.code === 'auth/popup-closed-by-user') {
                    // User simply closed the picker
                    return;
                }

                // If Google Provider is not enabled yet in Firebase console, 
                // prompt the user and focus the email field to trigger device keyboard autofill
                if (error.code === 'auth/operation-not-allowed') {
                    const emailField = document.getElementById('profileEmail');
                    if (emailField) {
                        emailField.focus();
                    }
                    alert('To use 1-tap Google Autofill, enable Google in Firebase Console (Authentication > Sign-in method > Google). In the meantime, your device keyboard will suggest your email when tapped!');
                } else {
                    const emailField = document.getElementById('profileEmail');
                    if (emailField) emailField.focus();
                }
            });
    } else {
        btn.disabled = false;
        btn.innerHTML = originalContent;
        const emailField = document.getElementById('profileEmail');
        if (emailField) emailField.focus();
    }
}

// Auto-focus first empty field if profile is incomplete
document.addEventListener('DOMContentLoaded', function() {
    const fn = document.getElementById('profileFirstName');
    const email = document.getElementById('profileEmail');
    if (fn && !fn.value) {
        fn.focus();
    } else if (email && !email.value) {
        email.focus();
    }
});
</script>
