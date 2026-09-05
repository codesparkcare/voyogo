<!-- Hotel API Settings Management -->
<div class="container-fluid py-2">
    
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1" style="color: #0d3470;">
                <i class="fa-solid fa-hotel text-primary me-2"></i> Hotel API Settings
            </h3>
            <p class="text-muted small mb-0">Configure Akbar / Benzy B2B Hotel API Live & Sandbox credentials and environment toggle</p>
        </div>
        <div class="d-flex gap-2">
            <a href="<?php echo site_url('admin/hotel_api_logs'); ?>" class="btn btn-outline-secondary btn-sm fw-bold">
                <i class="fa-solid fa-clock-rotate-left me-1"></i> View Hotel API Logs
            </a>
            <a href="<?php echo site_url('hotels'); ?>" target="_blank" class="btn btn-outline-primary btn-sm fw-bold">
                <i class="fa-solid fa-arrow-up-right-from-square me-1"></i> Test Hotel Search Page
            </a>
        </div>
    </div>

    <!-- Flash Alerts -->
    <?php if ($this->session->flashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="fa-solid fa-circle-check me-2"></i> <?php echo $this->session->flashdata('success'); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if ($this->session->flashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
            <i class="fa-solid fa-triangle-exclamation me-2"></i> <?php echo $this->session->flashdata('error'); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="row">
        <!-- Settings Form Column -->
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 rounded-3 mb-4">
                <div class="card-header bg-white border-0 pt-3 pb-0 d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold text-dark mb-0">
                        <i class="fa-solid fa-sliders text-secondary me-2"></i> Environment & Credentials
                    </h5>
                    <span class="badge <?php echo ($settings['environment'] === 'live') ? 'bg-success' : 'bg-warning text-dark'; ?> px-3 py-2 fs-6">
                        Active: <?php echo strtoupper($settings['environment']); ?>
                    </span>
                </div>
                <div class="card-body">
                    <form action="<?php echo site_url('admin/hotel_api_settings'); ?>" method="POST" id="hotelApiForm">
                        <input type="hidden" name="action" value="save">

                        <!-- Environment Selector Switch -->
                        <div class="p-3 mb-4 rounded-3 border" style="background: #f8fafc;">
                            <label class="form-label fw-bold text-dark d-block mb-2">API Environment Mode</label>
                            <div class="d-flex gap-4">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="environment" id="envLive" value="live" <?php echo ($settings['environment'] === 'live') ? 'checked' : ''; ?> onchange="toggleEnvSections()">
                                    <label class="form-check-label fw-bold text-success" for="envLive">
                                        <i class="fa-solid fa-bolt me-1"></i> Live Production (Akbar Travels)
                                    </label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="environment" id="envSandbox" value="sandbox" <?php echo ($settings['environment'] === 'sandbox') ? 'checked' : ''; ?> onchange="toggleEnvSections()">
                                    <label class="form-check-label fw-bold text-warning text-dark" for="envSandbox">
                                        <i class="fa-solid fa-flask me-1"></i> Sandbox / Testing (Benzy Infotech)
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- LIVE CREDENTIALS SECTION -->
                        <div id="liveSection" class="mb-4">
                            <h6 class="fw-bold text-success border-bottom pb-2 mb-3">
                                <i class="fa-solid fa-shield-halved me-2"></i> Live Production Hotel Credentials
                            </h6>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold text-secondary">Live Client ID / Username</label>
                                    <input type="text" name="live_client_id" class="form-control" value="<?php echo htmlspecialchars($settings['live_client_id']); ?>" placeholder="APISKYPLANETN">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold text-secondary">Live Password</label>
                                    <input type="text" name="live_password" class="form-control" value="<?php echo htmlspecialchars($settings['live_password']); ?>" placeholder="Password">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label small fw-bold text-secondary">Live Merchant ID / AUI</label>
                                    <input type="text" name="live_merchant_id" class="form-control" value="<?php echo htmlspecialchars($settings['live_merchant_id']); ?>" placeholder="200">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label small fw-bold text-secondary">Live Agent Code</label>
                                    <input type="text" name="live_agent_code" class="form-control" value="<?php echo htmlspecialchars($settings['live_agent_code']); ?>" placeholder="Leave blank if not assigned">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label small fw-bold text-secondary">Live API Key</label>
                                    <input type="text" name="live_api_key" class="form-control font-monospace small" value="<?php echo htmlspecialchars($settings['live_api_key']); ?>" placeholder="API Key">
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label small fw-bold text-secondary">Live Browser Key / Key</label>
                                    <input type="text" name="live_browser_key" class="form-control font-monospace small" value="<?php echo htmlspecialchars($settings['live_browser_key']); ?>" placeholder="Browser Key">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold text-secondary">Live Utils URL (Auth / Signature)</label>
                                    <input type="text" name="live_utils_url" class="form-control" value="<?php echo htmlspecialchars($settings['live_utils_url']); ?>" placeholder="https://apiutilsagents.akbartravelsonline.com">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold text-secondary">Live Hotel API Base URL</label>
                                    <input type="text" name="live_hotel_url" class="form-control" value="<?php echo htmlspecialchars($settings['live_hotel_url']); ?>" placeholder="https://apiagents.akbartravelsonline.com">
                                </div>
                            </div>
                        </div>

                        <!-- SANDBOX CREDENTIALS SECTION -->
                        <div id="sandboxSection" class="mb-4">
                            <h6 class="fw-bold text-warning border-bottom pb-2 mb-3">
                                <i class="fa-solid fa-vial me-2"></i> Sandbox / Staging Hotel Credentials
                            </h6>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold text-secondary">Sandbox Client ID</label>
                                    <input type="text" name="sandbox_client_id" class="form-control" value="<?php echo htmlspecialchars($settings['sandbox_client_id']); ?>" placeholder="bitest">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold text-secondary">Sandbox Password</label>
                                    <input type="text" name="sandbox_password" class="form-control" value="<?php echo htmlspecialchars($settings['sandbox_password']); ?>" placeholder="staging@1">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label small fw-bold text-secondary">Sandbox Merchant ID</label>
                                    <input type="text" name="sandbox_merchant_id" class="form-control" value="<?php echo htmlspecialchars($settings['sandbox_merchant_id']); ?>" placeholder="300">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label small fw-bold text-secondary">Sandbox Agent Code</label>
                                    <input type="text" name="sandbox_agent_code" class="form-control" value="<?php echo htmlspecialchars($settings['sandbox_agent_code']); ?>" placeholder="Agent Code">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label small fw-bold text-secondary">Sandbox API Key</label>
                                    <input type="text" name="sandbox_api_key" class="form-control font-monospace small" value="<?php echo htmlspecialchars($settings['sandbox_api_key']); ?>" placeholder="kXAY9yHARK">
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label small fw-bold text-secondary">Sandbox Browser Key</label>
                                    <input type="text" name="sandbox_browser_key" class="form-control font-monospace small" value="<?php echo htmlspecialchars($settings['sandbox_browser_key']); ?>" placeholder="caecd3cd30225512c1811070dce615c1">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold text-secondary">Sandbox Utils URL</label>
                                    <input type="text" name="sandbox_utils_url" class="form-control" value="<?php echo htmlspecialchars($settings['sandbox_utils_url']); ?>" placeholder="https://b2bapiutils.benzyinfotech.com">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold text-secondary">Sandbox Hotel API Base URL</label>
                                    <input type="text" name="sandbox_hotel_url" class="form-control" value="<?php echo htmlspecialchars($settings['sandbox_hotel_url']); ?>" placeholder="https://travelportalapi.benzyinfotech.com">
                                </div>
                            </div>
                        </div>

                        <!-- GENERAL CONFIG -->
                        <div class="row g-3 border-top pt-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-secondary">Channel ID</label>
                                <input type="text" name="channel_id" class="form-control" value="<?php echo htmlspecialchars($settings['channel_id']); ?>" placeholder="b2bIndiaDeals">
                            </div>
                            <div class="col-md-6 d-flex align-items-center pt-3">
                                <div class="form-check form-switch mt-2">
                                    <input class="form-check-input" type="checkbox" name="is_enabled" id="isEnabled" value="1" <?php echo ($settings['is_enabled']) ? 'checked' : ''; ?>>
                                    <label class="form-check-label fw-bold text-dark" for="isEnabled">Enable Hotel Search & Booking APIs</label>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center">
                            <button type="submit" class="btn btn-primary px-4 fw-bold">
                                <i class="fa-solid fa-floppy-disk me-2"></i> Save Hotel API Settings
                            </button>
                            <span class="text-muted small">Last Updated: <?php echo $settings['updated_at'] ?? 'Recently'; ?></span>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Connection Test & Server Info Sidebar -->
        <div class="col-lg-4">
            <!-- Connection Test Card -->
            <div class="card shadow-sm border-0 rounded-3 mb-4">
                <div class="card-header bg-white border-0 pt-3 pb-0">
                    <h5 class="fw-bold text-dark mb-0">
                        <i class="fa-solid fa-plug-circle-check text-success me-2"></i> Test Connection
                    </h5>
                </div>
                <div class="card-body">
                    <p class="text-muted small mb-3">
                        Performs an immediate authentication handshake with the configured <strong><?php echo strtoupper($settings['environment']); ?></strong> Hotel Signature API and validates the JWT Bearer Token generation.
                    </p>

                    <form action="<?php echo site_url('admin/hotel_api_settings'); ?>" method="POST">
                        <input type="hidden" name="action" value="test_connection">
                        <button type="submit" class="btn btn-success w-100 py-2 fw-bold shadow-sm">
                            <i class="fa-solid fa-play me-2"></i> Run Hotel Connection Test
                        </button>
                    </form>
                </div>
            </div>

            <!-- Server IP Card -->
            <div class="card shadow-sm border-0 rounded-3 mb-4">
                <div class="card-header bg-white border-0 pt-3 pb-0">
                    <h5 class="fw-bold text-dark mb-0">
                        <i class="fa-solid fa-server text-info me-2"></i> Server Static IP
                    </h5>
                </div>
                <div class="card-body">
                    <p class="text-muted small mb-2">Akbar / Benzy requires this server IP to be whitelisted in their firewall:</p>
                    <div class="p-3 bg-light rounded-3 text-center border">
                        <span class="fs-5 fw-bold text-dark font-monospace"><?php echo htmlspecialchars($server_ip); ?></span>
                    </div>
                </div>
            </div>

            <!-- 14 APIs Guide Reference Card -->
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-header bg-white border-0 pt-3 pb-0">
                    <h5 class="fw-bold text-dark mb-0">
                        <i class="fa-solid fa-book text-primary me-2"></i> Hotel API References
                    </h5>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled small mb-0">
                        <li class="mb-2"><i class="fa-solid fa-check text-success me-2"></i> <strong>Auth:</strong> Signature (/Utils/Signature)</li>
                        <li class="mb-2"><i class="fa-solid fa-check text-success me-2"></i> <strong>Search:</strong> AutoSuggest & Init</li>
                        <li class="mb-2"><i class="fa-solid fa-check text-success me-2"></i> <strong>Rates:</strong> Hotel Rate & Hotel Content</li>
                        <li class="mb-2"><i class="fa-solid fa-check text-success me-2"></i> <strong>Rooms:</strong> More Rooms & Pricing</li>
                        <li class="mb-2"><i class="fa-solid fa-check text-success me-2"></i> <strong>Booking:</strong> Create Itinerary & Start Pay</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function toggleEnvSections() {
    const isLive = document.getElementById('envLive').checked;
    document.getElementById('liveSection').style.opacity = isLive ? '1' : '0.6';
    document.getElementById('sandboxSection').style.opacity = isLive ? '0.6' : '1';
}
document.addEventListener('DOMContentLoaded', toggleEnvSections);
</script>
