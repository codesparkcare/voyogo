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
.profile-label {
    display: block;
    font-size: 13px;
    font-weight: 700;
    color: #0f172a;
    margin-bottom: 8px;
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

        <div class="profile-card">
            
            <!-- Header Strip -->
            <div class="profile-header-strip">
                <div class="profile-avatar-circle">
                    <?php 
                        $firstLetter = !empty($user['first_name']) ? strtoupper(substr($user['first_name'], 0, 1)) : 'U';
                        echo $firstLetter;
                    ?>
                </div>
                <div class="profile-title-box">
                    <h2><?php echo !empty($user['first_name']) ? htmlspecialchars(trim($user['first_name'] . ' ' . ($user['last_name'] ?? ''))) : 'My Account Profile'; ?></h2>
                    <div class="profile-phone-badge">
                        <i class="fa-solid fa-shield-check"></i>
                        <span><?php echo htmlspecialchars($user['phone']); ?></span>
                        <small style="opacity: 0.8;">(Verified via OTP)</small>
                    </div>
                </div>
            </div>

            <!-- Profile Details Form -->
            <div class="profile-body">
                <form action="<?php echo site_url('user/update_profile'); ?>" method="POST">
                    
                    <div class="profile-form-grid">

                        <!-- 1. Phone Number (Locked / Cannot be edited) -->
                        <div class="profile-field-group full-width">
                            <label class="profile-label">
                                Phone Number
                                <span style="font-weight: 500; font-size: 11px; color: #16a34a; margin-left: 6px;">
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
                            <label class="profile-label">First Name *</label>
                            <div class="profile-input-wrap">
                                <i class="fa-solid fa-user"></i>
                                <input type="text" name="first_name" class="profile-input" value="<?php echo htmlspecialchars($user['first_name'] ?? ''); ?>" placeholder="Enter first name" required>
                            </div>
                        </div>

                        <!-- 3. Last Name (Editable) -->
                        <div class="profile-field-group">
                            <label class="profile-label">Last Name *</label>
                            <div class="profile-input-wrap">
                                <i class="fa-solid fa-user"></i>
                                <input type="text" name="last_name" class="profile-input" value="<?php echo htmlspecialchars($user['last_name'] ?? ''); ?>" placeholder="Enter last name" required>
                            </div>
                        </div>

                        <!-- 4. Email ID (Editable) -->
                        <div class="profile-field-group full-width">
                            <label class="profile-label">Email Address</label>
                            <div class="profile-input-wrap">
                                <i class="fa-solid fa-envelope"></i>
                                <input type="email" name="email" class="profile-input" value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>" placeholder="name@example.com">
                            </div>
                            <div class="profile-hint">
                                <i class="fa-solid fa-circle-info"></i>
                                <span>Booking confirmations and e-tickets will be delivered to this email.</span>
                            </div>
                        </div>

                    </div>

                    <!-- Submit & Logout Actions -->
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 24px; padding-top: 20px; border-top: 1px solid #f1f5f9;">
                        <button type="submit" class="profile-btn-save">
                            <i class="fa-solid fa-floppy-disk"></i>
                            <span>Save Profile Changes</span>
                        </button>
                        <a href="<?php echo site_url('user/logout'); ?>" class="profile-btn-logout">
                            <i class="fa-solid fa-arrow-right-from-bracket"></i>
                            <span>Logout</span>
                        </a>
                    </div>

                </form>
            </div>

        </div>

    </div>
</div>
