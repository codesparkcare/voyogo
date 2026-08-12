<div class="container-fluid p-4">

    <!-- Flash Messages -->
    <?php if ($this->session->flashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fa-solid fa-circle-check me-2"></i> <?php echo $this->session->flashdata('success'); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    <?php if ($this->session->flashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fa-solid fa-circle-exclamation me-2"></i> <?php echo $this->session->flashdata('error'); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-dark mb-1"><i class="fa-solid fa-sliders text-primary me-2"></i> SMTP Email Settings</h3>
            <p class="text-muted small mb-0">Configure your SMTP server to auto-send HTML flight tickets & hotel vouchers after booking confirmation</p>
        </div>
    </div>

    <div class="row g-4">
        
        <!-- SMTP Form Card -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white py-3 border-0">
                    <h5 class="fw-bold text-dark mb-0"><i class="fa-solid fa-envelope text-primary me-2"></i> Mail Server Configuration</h5>
                </div>
                <div class="card-body p-4">
                    <form action="<?php echo site_url('admin/email_settings'); ?>" method="POST">
                        <input type="hidden" name="action" value="save_settings">

                        <div class="row g-3 mb-3">
                            <div class="col-md-8">
                                <label class="form-label fw-bold small">SMTP Host</label>
                                <input type="text" name="smtp_host" class="form-control" placeholder="e.g. smtp.gmail.com or mail.yourdomain.com" value="<?php echo htmlspecialchars($settings['smtp_host']); ?>" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold small">SMTP Port</label>
                                <input type="number" name="smtp_port" class="form-control" placeholder="587 or 465" value="<?php echo htmlspecialchars($settings['smtp_port']); ?>" required>
                            </div>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold small">SMTP Username / Email</label>
                                <input type="text" name="smtp_user" class="form-control" placeholder="your_email@gmail.com" value="<?php echo htmlspecialchars($settings['smtp_user']); ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold small">SMTP Password</label>
                                <input type="password" name="smtp_pass" class="form-control" placeholder="App password or mail password" value="<?php echo htmlspecialchars($settings['smtp_pass']); ?>">
                            </div>
                        </div>

                        <div class="row g-3 mb-4">
                            <div class="col-md-4">
                                <label class="form-label fw-bold small">Encryption Protocol</label>
                                <select name="smtp_crypto" class="form-select">
                                    <option value="tls" <?php echo ($settings['smtp_crypto'] == 'tls') ? 'selected' : ''; ?>>TLS (Recommended)</option>
                                    <option value="ssl" <?php echo ($settings['smtp_crypto'] == 'ssl') ? 'selected' : ''; ?>>SSL</option>
                                    <option value="" <?php echo (empty($settings['smtp_crypto'])) ? 'selected' : ''; ?>>None</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold small">Sender Email Address</label>
                                <input type="email" name="from_email" class="form-control" placeholder="noreply@voyogo.com" value="<?php echo htmlspecialchars($settings['from_email']); ?>" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold small">Sender Name</label>
                                <input type="text" name="from_name" class="form-control" placeholder="Voyogo Travels" value="<?php echo htmlspecialchars($settings['from_name']); ?>" required>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary px-4 fw-bold">
                            <i class="fa-solid fa-floppy-disk me-2"></i> Save SMTP Settings
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Test Email Sender Card -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white py-3 border-0">
                    <h5 class="fw-bold text-dark mb-0"><i class="fa-solid fa-paper-plane text-success me-2"></i> Test SMTP Dispatch</h5>
                </div>
                <div class="card-body p-4">
                    <p class="text-muted small">Send a test email using your saved SMTP server credentials to verify mail delivery works smoothly.</p>

                    <form action="<?php echo site_url('admin/email_settings'); ?>" method="POST">
                        <input type="hidden" name="action" value="test_email">

                        <div class="mb-3">
                            <label class="form-label fw-bold small">Recipient Email Address</label>
                            <input type="email" name="test_email_to" class="form-control" placeholder="admin@example.com" required value="<?php echo htmlspecialchars($settings['from_email']); ?>">
                        </div>

                        <button type="submit" class="btn btn-success w-100 fw-bold">
                            <i class="fa-solid fa-paper-plane me-2"></i> Send Test Email Now
                        </button>
                    </form>
                </div>
            </div>
        </div>

    </div>
</div>
