<div class="container-fluid p-4">

    <!-- Flash Messages -->
    <?php if ($this->session->flashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-3" role="alert">
            <i class="fa-solid fa-circle-check me-2"></i> <?php echo $this->session->flashdata('success'); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    <?php if ($this->session->flashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm rounded-3" role="alert">
            <i class="fa-solid fa-triangle-exclamation me-2"></i> <?php echo $this->session->flashdata('error'); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- Page Header -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
        <div>
            <h3 class="fw-bold text-dark mb-1 d-flex align-items-center gap-2">
                <i class="fa-solid fa-credit-card text-primary"></i> Razorpay Payment Gateway Settings
            </h3>
            <p class="text-muted small mb-0">Configure your dynamic Razorpay API credentials, environment modes, checkout brand themes, and test connection in real-time.</p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <span class="badge <?php echo ($settings['environment'] === 'live') ? 'bg-success' : 'bg-warning text-dark'; ?> px-3 py-2 fs-6 rounded-pill">
                <i class="fa-solid fa-circle-dot me-1"></i> <?php echo strtoupper($settings['environment']); ?> MODE
            </span>
            <span class="badge <?php echo (!empty($settings['is_enabled'])) ? 'bg-primary' : 'bg-secondary'; ?> px-3 py-2 fs-6 rounded-pill">
                <?php echo (!empty($settings['is_enabled'])) ? '<i class="fa-solid fa-check-circle me-1"></i> Enabled' : '<i class="fa-solid fa-ban me-1"></i> Disabled'; ?>
            </span>
        </div>
    </div>

    <div class="row g-4">
        
        <!-- Main Razorpay Configuration Form -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold text-dark mb-0 d-flex align-items-center gap-2">
                        <i class="fa-solid fa-sliders text-primary"></i> API Credentials & Configuration
                    </h5>
                    <a href="https://dashboard.razorpay.com/app/keys" target="_blank" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                        <i class="fa-solid fa-arrow-up-right-from-square me-1"></i> Razorpay Dashboard
                    </a>
                </div>
                <div class="card-body p-4">
                    <form action="<?php echo site_url('admin/razorpay_settings'); ?>" method="POST" id="razorpayForm">
                        <input type="hidden" name="action" value="save_settings">

                        <!-- Mode & Status Row -->
                        <div class="row g-3 mb-4 p-3 bg-light rounded-3 border">
                            <div class="col-md-6">
                                <label class="form-label fw-bold small text-muted">GATEWAY STATUS</label>
                                <div class="form-check form-switch fs-5">
                                    <input class="form-check-input" type="checkbox" name="is_enabled" id="is_enabled" value="1" <?php echo (!empty($settings['is_enabled'])) ? 'checked' : ''; ?>>
                                    <label class="form-check-label fs-6 fw-semibold text-dark ms-2" for="is_enabled">
                                        Enable Razorpay Checkout for Flight & Hotel Bookings
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold small text-muted">ENVIRONMENT / MODE</label>
                                <div class="d-flex gap-3 mt-1">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="environment" id="env_test" value="test" <?php echo ($settings['environment'] !== 'live') ? 'checked' : ''; ?>>
                                        <label class="form-check-label fw-bold text-warning-emphasis" for="env_test">
                                            <i class="fa-solid fa-flask me-1"></i> Test Mode (Sandbox)
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="environment" id="env_live" value="live" <?php echo ($settings['environment'] === 'live') ? 'checked' : ''; ?>>
                                        <label class="form-check-label fw-bold text-success" for="env_live">
                                            <i class="fa-solid fa-bolt me-1"></i> Live Mode (Production)
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- API Keys Section -->
                        <div class="mb-4">
                            <h6 class="fw-bold text-secondary mb-3"><i class="fa-solid fa-key me-2"></i> Razorpay API Keys</h6>
                            
                            <!-- Key ID -->
                            <div class="mb-3">
                                <label class="form-label fw-bold small">Razorpay Key ID <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light text-muted"><i class="fa-solid fa-id-badge"></i></span>
                                    <input type="text" name="razorpay_key_id" id="razorpay_key_id" class="form-control font-monospace" placeholder="rzp_test_... or rzp_live_..." value="<?php echo htmlspecialchars($settings['razorpay_key_id']); ?>" required>
                                    <button class="btn btn-outline-secondary" type="button" onclick="copyToClipboard('razorpay_key_id')">
                                        <i class="fa-solid fa-copy"></i>
                                    </button>
                                </div>
                                <div class="form-text small text-muted">Starts with <code>rzp_test_</code> for test mode or <code>rzp_live_</code> for live production.</div>
                            </div>

                            <!-- Key Secret -->
                            <div class="mb-3">
                                <label class="form-label fw-bold small">Razorpay Key Secret <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light text-muted"><i class="fa-solid fa-lock"></i></span>
                                    <input type="password" name="razorpay_key_secret" id="razorpay_key_secret" class="form-control font-monospace" placeholder="Enter Razorpay Key Secret" value="<?php echo htmlspecialchars($settings['razorpay_key_secret']); ?>" required>
                                    <button class="btn btn-outline-secondary" type="button" onclick="toggleSecretVisibility()">
                                        <i class="fa-solid fa-eye" id="secretToggleIcon"></i>
                                    </button>
                                    <button class="btn btn-outline-secondary" type="button" onclick="copyToClipboard('razorpay_key_secret')">
                                        <i class="fa-solid fa-copy"></i>
                                    </button>
                                </div>
                                <div class="form-text small text-muted">Keep this secret secure. Never share or expose it in public code repositories.</div>
                            </div>
                        </div>

                        <hr class="my-4">

                        <!-- Branding & Customization -->
                        <div class="mb-4">
                            <h6 class="fw-bold text-secondary mb-3"><i class="fa-solid fa-palette me-2"></i> Checkout UI & Merchant Customization</h6>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold small">Merchant / Brand Display Name</label>
                                    <input type="text" name="merchant_name" class="form-control" placeholder="e.g. Voyogo Travels" value="<?php echo htmlspecialchars($settings['merchant_name']); ?>" required>
                                    <div class="form-text small text-muted">Displayed at top of Razorpay popup modal.</div>
                                </div>
                                
                                <div class="col-md-6">
                                    <label class="form-label fw-bold small">Default Payment Currency</label>
                                    <select name="currency" class="form-select">
                                        <option value="INR" <?php echo ($settings['currency'] == 'INR') ? 'selected' : ''; ?>>INR - Indian Rupee (₹)</option>
                                        <option value="USD" <?php echo ($settings['currency'] == 'USD') ? 'selected' : ''; ?>>USD - US Dollar ($)</option>
                                        <option value="EUR" <?php echo ($settings['currency'] == 'EUR') ? 'selected' : ''; ?>>EUR - Euro (€)</option>
                                        <option value="GBP" <?php echo ($settings['currency'] == 'GBP') ? 'selected' : ''; ?>>GBP - British Pound (£)</option>
                                        <option value="AED" <?php echo ($settings['currency'] == 'AED') ? 'selected' : ''; ?>>AED - UAE Dirham (د.إ)</option>
                                        <option value="SGD" <?php echo ($settings['currency'] == 'SGD') ? 'selected' : ''; ?>>SGD - Singapore Dollar (S$)</option>
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-bold small">Checkout Theme Color</label>
                                    <div class="d-flex align-items-center gap-2">
                                        <input type="color" id="theme_color_picker" class="form-control form-control-color" value="<?php echo htmlspecialchars($settings['theme_color'] ?: '#0d3470'); ?>" title="Choose theme color" onchange="document.getElementById('theme_color_input').value = this.value">
                                        <input type="text" name="theme_color" id="theme_color_input" class="form-control font-monospace" value="<?php echo htmlspecialchars($settings['theme_color'] ?: '#0d3470'); ?>" placeholder="#0d3470" onchange="document.getElementById('theme_color_picker').value = this.value">
                                    </div>
                                    <div class="form-text small text-muted">Hex color code for the checkout modal header.</div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                            <button type="submit" class="btn btn-primary px-4 py-2 fw-bold shadow-sm">
                                <i class="fa-solid fa-floppy-disk me-2"></i> Save Razorpay Settings
                            </button>
                            <?php if (!empty($settings['updated_at'])): ?>
                                <span class="text-muted small">
                                    <i class="fa-regular fa-clock me-1"></i> Last updated: <?php echo date('M d, Y H:i:s', strtotime($settings['updated_at'])); ?>
                                </span>
                            <?php endif; ?>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Sidebar Actions & Testing Tools -->
        <div class="col-lg-4">
            
            <!-- Connection Test Card -->
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-white py-3 border-0">
                    <h5 class="fw-bold text-dark mb-0"><i class="fa-solid fa-plug-circle-check text-success me-2"></i> Live Connection Tester</h5>
                </div>
                <div class="card-body p-4">
                    <p class="text-muted small">Verify that your configured Razorpay API Key ID and Key Secret are valid by connecting directly to Razorpay's authentication servers.</p>

                    <form action="<?php echo site_url('admin/razorpay_settings'); ?>" method="POST">
                        <input type="hidden" name="action" value="test_connection">
                        <input type="hidden" name="test_key_id" value="<?php echo htmlspecialchars($settings['razorpay_key_id']); ?>">
                        <input type="hidden" name="test_key_secret" value="<?php echo htmlspecialchars($settings['razorpay_key_secret']); ?>">

                        <div class="bg-light p-3 rounded-3 mb-3 border">
                            <div class="small text-muted mb-1">Testing Credentials for:</div>
                            <div class="font-monospace small fw-bold text-truncate text-dark mb-1">
                                <i class="fa-solid fa-key text-primary me-1"></i> <?php echo htmlspecialchars($settings['razorpay_key_id']); ?>
                            </div>
                            <div class="small badge bg-light text-dark border">
                                Mode: <strong><?php echo strtoupper($settings['environment']); ?></strong>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-success w-100 fw-bold py-2 shadow-sm">
                            <i class="fa-solid fa-bolt-lightning me-2"></i> Test API Connection Now
                        </button>
                    </form>
                </div>
            </div>

            <!-- Quick Setup / Webhook Info Card -->
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white py-3 border-0">
                    <h5 class="fw-bold text-dark mb-0"><i class="fa-solid fa-circle-info text-info me-2"></i> Setup Guide & Tips</h5>
                </div>
                <div class="card-body p-4">
                    <ol class="small text-muted ps-3 mb-3">
                        <li class="mb-2">Log in to your <strong><a href="https://dashboard.razorpay.com/" target="_blank" class="text-decoration-none text-primary">Razorpay Dashboard</a></strong>.</li>
                        <li class="mb-2">Go to <strong>Settings &gt; API Keys</strong>.</li>
                        <li class="mb-2">Click <strong>Generate Key</strong> in Test or Live mode.</li>
                        <li class="mb-2">Copy the <strong>Key ID</strong> and <strong>Key Secret</strong> into this admin form.</li>
                        <li class="mb-2">Click <strong>Save Razorpay Settings</strong> and click <strong>Test API Connection</strong>.</li>
                    </ol>

                    <div class="p-3 bg-light rounded-3 border">
                        <div class="fw-bold small text-dark mb-1"><i class="fa-solid fa-shield-halved text-success me-1"></i> Security Note:</div>
                        <div class="small text-muted">Never commit Key Secrets to version control. Keys saved here are securely retrieved dynamically by the checkout review pages.</div>
                    </div>
                </div>
            </div>

        </div>

    </div>
</div>

<script>
function toggleSecretVisibility() {
    var input = document.getElementById('razorpay_key_secret');
    var icon = document.getElementById('secretToggleIcon');
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}

function copyToClipboard(elementId) {
    var copyText = document.getElementById(elementId);
    if (!copyText.value) return;
    
    navigator.clipboard.writeText(copyText.value).then(function() {
        alert('Copied to clipboard: ' + copyText.value.substring(0, 12) + '...');
    }).catch(function(err) {
        copyText.select();
        document.execCommand('copy');
        alert('Copied to clipboard!');
    });
}
</script>
