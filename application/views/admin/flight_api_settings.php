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
                <i class="fa-solid fa-plane-departure text-primary"></i> Akbar / Benzy Flight API Settings
            </h3>
            <p class="text-muted small mb-0">Manage live and sandbox API credentials, service endpoints, environment toggling, and live signature token connectivity.</p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <span class="badge <?php echo ($settings['environment'] === 'live') ? 'bg-success' : 'bg-warning text-dark'; ?> px-3 py-2 fs-6 rounded-pill">
                <i class="fa-solid fa-circle-dot me-1"></i> <?php echo strtoupper($settings['environment']); ?> MODE ACTIVE
            </span>
            <span class="badge <?php echo (!empty($settings['is_enabled'])) ? 'bg-primary' : 'bg-secondary'; ?> px-3 py-2 fs-6 rounded-pill">
                <?php echo (!empty($settings['is_enabled'])) ? '<i class="fa-solid fa-check-circle me-1"></i> Enabled' : '<i class="fa-solid fa-ban me-1"></i> Disabled'; ?>
            </span>
        </div>
    </div>

    <div class="row g-4">
        
        <!-- Main Flight API Configuration Form -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold text-dark mb-0 d-flex align-items-center gap-2">
                        <i class="fa-solid fa-sliders text-primary"></i> Environment & Credentials
                    </h5>
                    <span class="badge bg-light text-secondary border px-3 py-2 rounded-pill font-monospace">
                        ClientID: <?php echo htmlspecialchars(($settings['environment'] === 'live') ? $settings['live_client_id'] : $settings['sandbox_client_id']); ?>
                    </span>
                </div>
                <div class="card-body p-4">
                    <form action="<?php echo site_url('admin/flight_api_settings'); ?>" method="POST" id="flightApiForm">
                        <input type="hidden" name="action" value="save">

                        <!-- Mode & Status Row -->
                        <div class="row g-3 mb-4 p-3 bg-light rounded-3 border">
                            <div class="col-md-6">
                                <label class="form-label fw-bold small text-muted">FLIGHT API STATUS</label>
                                <div class="form-check form-switch fs-5">
                                    <input class="form-check-input" type="checkbox" name="is_enabled" id="is_enabled" value="1" <?php echo (!empty($settings['is_enabled'])) ? 'checked' : ''; ?>>
                                    <label class="form-check-label fs-6 fw-semibold text-dark ms-2" for="is_enabled">
                                        Enable Live Flight Search & Booking
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold small text-muted">ACTIVE ENVIRONMENT</label>
                                <div class="d-flex gap-3 mt-1">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="environment" id="env_sandbox" value="sandbox" <?php echo ($settings['environment'] === 'sandbox') ? 'checked' : ''; ?>>
                                        <label class="form-check-label fw-bold text-warning-emphasis" for="env_sandbox">
                                            <i class="fa-solid fa-flask me-1"></i> Sandbox (bitest)
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="environment" id="env_live" value="live" <?php echo ($settings['environment'] === 'live') ? 'checked' : ''; ?>>
                                        <label class="form-check-label fw-bold text-success" for="env_live">
                                            <i class="fa-solid fa-bolt me-1"></i> Live Production
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Live Production Credentials Section -->
                        <div class="card border border-success-subtle bg-white rounded-3 p-3 mb-4 shadow-sm">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="fw-bold text-success mb-0">
                                    <i class="fa-solid fa-globe me-2"></i> Live Production API Credentials (Akbar Travels)
                                </h6>
                                <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1">Live Endpoint</span>
                            </div>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold small">Live ClientID <span class="text-danger">*</span></label>
                                    <input type="text" name="live_client_id" class="form-control font-monospace" value="<?php echo htmlspecialchars($settings['live_client_id']); ?>" placeholder="APISKYPLANETN" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold small">Live Password <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="password" name="live_password" id="live_password" class="form-control font-monospace" value="<?php echo htmlspecialchars($settings['live_password']); ?>" required>
                                        <button class="btn btn-outline-secondary" type="button" onclick="togglePasswordVisibility('live_password')">
                                            <i class="fa-solid fa-eye" id="live_password_icon"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-bold small">Live MerchantID</label>
                                    <input type="text" name="live_merchant_id" class="form-control font-monospace" value="<?php echo htmlspecialchars($settings['live_merchant_id']); ?>" placeholder="200" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-bold small">Live ApiKey</label>
                                    <input type="text" name="live_api_key" class="form-control font-monospace" value="<?php echo htmlspecialchars($settings['live_api_key']); ?>" placeholder="kXAY9yHARK" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-bold small">Live AgentCode</label>
                                    <input type="text" name="live_agent_code" class="form-control font-monospace" value="<?php echo htmlspecialchars($settings['live_agent_code']); ?>" placeholder="Optional">
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label fw-bold small">Live BrowserKey <span class="text-danger">*</span></label>
                                    <input type="text" name="live_browser_key" class="form-control font-monospace" value="<?php echo htmlspecialchars($settings['live_browser_key']); ?>" placeholder="069ab7973ac12116ccc1802546ad52bf" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold small">Live Flight Service URL</label>
                                    <input type="text" name="live_flight_url" class="form-control font-monospace" value="<?php echo htmlspecialchars($settings['live_flight_url']); ?>" placeholder="https://apiagents.akbartravelsonline.com" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold small">Live Utils Service URL</label>
                                    <input type="text" name="live_utils_url" class="form-control font-monospace" value="<?php echo htmlspecialchars($settings['live_utils_url']); ?>" placeholder="https://apiutilsagents.akbartravelsonline.com" required>
                                </div>
                            </div>
                        </div>

                        <!-- Sandbox Credentials Section -->
                        <div class="card border border-warning-subtle bg-white rounded-3 p-3 mb-4 shadow-sm">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="fw-bold text-warning-emphasis mb-0">
                                    <i class="fa-solid fa-flask me-2"></i> Sandbox / Testing API Credentials (Benzy)
                                </h6>
                                <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle px-2 py-1">Staging Endpoint</span>
                            </div>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold small">Sandbox ClientID</label>
                                    <input type="text" name="sandbox_client_id" class="form-control font-monospace" value="<?php echo htmlspecialchars($settings['sandbox_client_id']); ?>" placeholder="bitest">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold small">Sandbox Password</label>
                                    <div class="input-group">
                                        <input type="password" name="sandbox_password" id="sandbox_password" class="form-control font-monospace" value="<?php echo htmlspecialchars($settings['sandbox_password']); ?>">
                                        <button class="btn btn-outline-secondary" type="button" onclick="togglePasswordVisibility('sandbox_password')">
                                            <i class="fa-solid fa-eye" id="sandbox_password_icon"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-bold small">Sandbox MerchantID</label>
                                    <input type="text" name="sandbox_merchant_id" class="form-control font-monospace" value="<?php echo htmlspecialchars($settings['sandbox_merchant_id']); ?>" placeholder="300">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-bold small">Sandbox ApiKey</label>
                                    <input type="text" name="sandbox_api_key" class="form-control font-monospace" value="<?php echo htmlspecialchars($settings['sandbox_api_key']); ?>" placeholder="kXAY9yHARK">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-bold small">Sandbox AgentCode</label>
                                    <input type="text" name="sandbox_agent_code" class="form-control font-monospace" value="<?php echo htmlspecialchars($settings['sandbox_agent_code']); ?>" placeholder="Optional">
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label fw-bold small">Sandbox BrowserKey</label>
                                    <input type="text" name="sandbox_browser_key" class="form-control font-monospace" value="<?php echo htmlspecialchars($settings['sandbox_browser_key']); ?>" placeholder="ef20-925c-4489-bfeb-236c8b406f7e">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold small">Sandbox Flight Service URL</label>
                                    <input type="text" name="sandbox_flight_url" class="form-control font-monospace" value="<?php echo htmlspecialchars($settings['sandbox_flight_url']); ?>" placeholder="https://b2bapiflights.benzyinfotech.com">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold small">Sandbox Utils Service URL</label>
                                    <input type="text" name="sandbox_utils_url" class="form-control font-monospace" value="<?php echo htmlspecialchars($settings['sandbox_utils_url']); ?>" placeholder="https://b2bapiutils.benzyinfotech.com">
                                </div>
                            </div>
                        </div>

                        <!-- General Settings -->
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-bold small">Default Channel ID</label>
                                <input type="text" name="channel_id" class="form-control font-monospace" value="<?php echo htmlspecialchars($settings['channel_id']); ?>" placeholder="b2bIndiaDeals" required>
                                <div class="form-text small text-muted">Akbar / Benzy channel profile code.</div>
                            </div>
                        </div>

                        <hr class="my-4">

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary px-4 py-2 fw-bold shadow-sm rounded-pill">
                                <i class="fa-solid fa-floppy-disk me-2"></i> Save Flight API Settings
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Sidebar Tools & IP Whitelisting Guide -->
        <div class="col-lg-4">
            
            <!-- Connection Test Widget -->
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-white py-3 border-0">
                    <h5 class="fw-bold text-dark mb-0 d-flex align-items-center gap-2">
                        <i class="fa-solid fa-plug-circle-check text-success"></i> Test Connection
                    </h5>
                </div>
                <div class="card-body p-4">
                    <p class="text-muted small">
                        Perform a live real-time signature and Bearer Token test with the currently selected active environment (<strong><?php echo strtoupper($settings['environment']); ?></strong>).
                    </p>
                    <form action="<?php echo site_url('admin/flight_api_settings'); ?>" method="POST">
                        <input type="hidden" name="action" value="test_connection">
                        <div class="d-grid">
                            <button type="submit" class="btn btn-outline-success fw-bold py-2 rounded-pill shadow-sm">
                                <i class="fa-solid fa-bolt me-2"></i> Test Active API Signature
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Server IP & Whitelisting Card -->
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-white py-3 border-0">
                    <h5 class="fw-bold text-dark mb-0 d-flex align-items-center gap-2">
                        <i class="fa-solid fa-shield-halved text-info"></i> Server IP Whitelisting
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="alert alert-info border-0 rounded-3 mb-3 p-3">
                        <div class="fw-bold small mb-1"><i class="fa-solid fa-network-wired me-1"></i> Detected Server IP:</div>
                        <code class="fs-6 fw-bold text-dark"><?php echo htmlspecialchars($server_ip); ?></code>
                    </div>
                    <p class="small text-muted mb-3">
                        Akbar Travels requires your hosting server's <strong>Public Static IP</strong> to be whitelisted for the Live API (<code>apiagents.akbartravelsonline.com</code>).
                    </p>
                    <ol class="small text-muted ps-3 mb-0">
                        <li class="mb-2">Ensure your live production server has a fixed Static IP.</li>
                        <li class="mb-2">Share this IP address with the Akbar / Benzy API Support team.</li>
                        <li>Once confirmed whitelisted, click <strong>Test Active API Signature</strong> to verify.</li>
                    </ol>
                </div>
            </div>

            <!-- Quick Links -->
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    <h6 class="fw-bold text-dark mb-3"><i class="fa-solid fa-link me-2 text-primary"></i> Helpful Tools</h6>
                    <div class="d-flex flex-column gap-2">
                        <a href="<?php echo site_url('flight_cert'); ?>" class="btn btn-light text-start border rounded-3 py-2">
                            <i class="fa-solid fa-certificate text-warning me-2"></i> Flight Certification Suite
                        </a>
                        <a href="<?php echo site_url('admin/api_logs'); ?>" class="btn btn-light text-start border rounded-3 py-2">
                            <i class="fa-solid fa-clock-rotate-left text-success me-2"></i> API Activity & Request Logs
                        </a>
                        <a href="<?php echo site_url('admin/razorpay_settings'); ?>" class="btn btn-light text-start border rounded-3 py-2">
                            <i class="fa-solid fa-credit-card text-primary me-2"></i> Razorpay Payment Settings
                        </a>
                    </div>
                </div>
            </div>

        </div>

    </div>

</div>

<script>
function togglePasswordVisibility(id) {
    var input = document.getElementById(id);
    var icon = document.getElementById(id + '_icon');
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
</script>
