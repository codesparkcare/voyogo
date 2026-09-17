<?php
// Helper to construct pagination URLs while retaining filters
if (!function_exists('visa_page_url')) {
    function visa_page_url($target_page, $search, $status, $limit) {
        $params = [];
        if (!empty($search)) $params['search'] = $search;
        if (!empty($status)) $params['status'] = $status;
        if (!empty($limit) && (int)$limit !== 10) $params['limit'] = $limit;
        if ((int)$target_page > 1) $params['page'] = $target_page;
        $qs = http_build_query($params);
        return site_url('admin/visas') . ($qs ? '?' . $qs : '');
    }
}
?>
<div class="container-fluid p-4">

    <!-- Flash Messages -->
    <?php if ($this->session->flashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show rounded-4 shadow-sm border-0 d-flex align-items-center gap-2 mb-4" role="alert" style="background: #ecfdf5; color: #065f46; border-left: 5px solid #10b981 !important;">
            <i class="fa-solid fa-circle-check fs-5 text-success"></i>
            <div><strong>Success:</strong> <?php echo $this->session->flashdata('success'); ?></div>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <span class="badge bg-primary-subtle text-primary fw-bold px-2.5 py-1 rounded-pill" style="font-size: 11px;">
                    <i class="fa-solid fa-passport me-1"></i> VISA DESK
                </span>
                <span class="text-muted small">Leads & Enquiries Management</span>
            </div>
            <h3 class="fw-black text-dark mb-0 d-flex align-items-center gap-2" style="font-weight: 800; letter-spacing: -0.5px;">
                Manage Visa Enquiries
            </h3>
        </div>
        <div class="d-flex align-items-center gap-2">
            <a href="<?php echo site_url('admin/visas'); ?>" class="btn btn-sm btn-light border rounded-3 fw-semibold text-secondary d-flex align-items-center gap-2 px-3 py-2">
                <i class="fa-solid fa-arrows-rotate"></i> Refresh
            </a>
        </div>
    </div>

    <!-- 4 KPI Stat Cards Strip -->
    <div class="row g-3 mb-4">
        <!-- Total Enquiries -->
        <div class="col-xl-3 col-sm-6">
            <div class="kpi-card">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small fw-bold text-uppercase" style="font-size: 11px; letter-spacing: 0.5px;">Total Requests</span>
                    <div class="kpi-icon" style="background: #eff6ff; color: #2563eb;">
                        <i class="fa-solid fa-passport"></i>
                    </div>
                </div>
                <div class="d-flex align-items-baseline gap-2">
                    <h2 class="fw-black text-dark mb-0" style="font-weight: 800;"><?php echo number_format($total_count ?? 0); ?></h2>
                    <span class="text-muted small">all time</span>
                </div>
            </div>
        </div>

        <!-- New / Action Required -->
        <div class="col-xl-3 col-sm-6">
            <div class="kpi-card">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small fw-bold text-uppercase" style="font-size: 11px; letter-spacing: 0.5px;">New / Action Required</span>
                    <div class="kpi-icon" style="background: #fef3c7; color: #d97706;">
                        <i class="fa-solid fa-bell"></i>
                    </div>
                </div>
                <div class="d-flex align-items-baseline gap-2">
                    <h2 class="fw-black text-dark mb-0" style="font-weight: 800;"><?php echo number_format($new_count ?? 0); ?></h2>
                    <?php if (($new_count ?? 0) > 0): ?>
                        <span class="badge bg-warning text-dark rounded-pill px-2 py-0.5 small d-flex align-items-center gap-1">
                            <span class="pulse-dot"></span> Pending
                        </span>
                    <?php else: ?>
                        <span class="text-muted small">All clear</span>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- In Progress / Active -->
        <div class="col-xl-3 col-sm-6">
            <div class="kpi-card">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small fw-bold text-uppercase" style="font-size: 11px; letter-spacing: 0.5px;">Under Processing</span>
                    <div class="kpi-icon" style="background: #f5f3ff; color: #7c3aed;">
                        <i class="fa-solid fa-file-signature"></i>
                    </div>
                </div>
                <div class="d-flex align-items-baseline gap-2">
                    <h2 class="fw-black text-dark mb-0" style="font-weight: 800;"><?php echo number_format($in_prog_count ?? 0); ?></h2>
                    <span class="text-muted small">contacted/docs</span>
                </div>
            </div>
        </div>

        <!-- Completed / Closed -->
        <div class="col-xl-3 col-sm-6">
            <div class="kpi-card">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small fw-bold text-uppercase" style="font-size: 11px; letter-spacing: 0.5px;">Completed / Closed</span>
                    <div class="kpi-icon" style="background: #ecfdf5; color: #059669;">
                        <i class="fa-solid fa-circle-check"></i>
                    </div>
                </div>
                <div class="d-flex align-items-baseline gap-2">
                    <h2 class="fw-black text-dark mb-0" style="font-weight: 800;"><?php echo number_format($completed_count ?? 0); ?></h2>
                    <span class="text-muted small">processed</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter & Search Toolbar -->
    <div class="card border-0 shadow-sm rounded-4 mb-4 bg-white" style="border: 1px solid #e2e8f0 !important;">
        <div class="card-body p-3">
            <form method="GET" action="<?php echo site_url('admin/visas'); ?>" class="row g-2 align-items-center">
                
                <!-- Search Input -->
                <div class="col-lg-5 col-md-6 col-12">
                    <div class="input-group">
                        <span class="input-group-text bg-light border-0 text-muted ps-3">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </span>
                        <input type="text" name="search" class="form-control bg-light border-0 py-2" placeholder="Search applicant, phone, email, country..." value="<?php echo htmlspecialchars($search ?? ''); ?>">
                        <?php if (!empty($search)): ?>
                            <a href="<?php echo visa_page_url(1, '', $status_filter, $paging['limit']); ?>" class="input-group-text bg-light border-0 text-muted" title="Clear search">
                                <i class="fa-solid fa-xmark"></i>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Status Filter -->
                <div class="col-lg-3 col-md-3 col-6">
                    <select name="status" class="form-select bg-light border-0 py-2">
                        <option value="">All Statuses</option>
                        <option value="New" <?php echo (($status_filter ?? '') === 'New') ? 'selected' : ''; ?>>New / Unprocessed</option>
                        <option value="Contacted" <?php echo (($status_filter ?? '') === 'Contacted') ? 'selected' : ''; ?>>Contacted</option>
                        <option value="Documents Received" <?php echo (($status_filter ?? '') === 'Documents Received') ? 'selected' : ''; ?>>Documents Received</option>
                        <option value="Processed" <?php echo (($status_filter ?? '') === 'Processed') ? 'selected' : ''; ?>>Processed</option>
                        <option value="Closed" <?php echo (($status_filter ?? '') === 'Closed') ? 'selected' : ''; ?>>Closed</option>
                    </select>
                </div>

                <!-- Per Page Selector -->
                <div class="col-lg-2 col-md-3 col-6">
                    <select name="limit" class="form-select bg-light border-0 py-2">
                        <option value="10" <?php echo (($paging['limit'] ?? 10) == 10) ? 'selected' : ''; ?>>10 / page</option>
                        <option value="25" <?php echo (($paging['limit'] ?? 10) == 25) ? 'selected' : ''; ?>>25 / page</option>
                        <option value="50" <?php echo (($paging['limit'] ?? 10) == 50) ? 'selected' : ''; ?>>50 / page</option>
                        <option value="100" <?php echo (($paging['limit'] ?? 10) == 100) ? 'selected' : ''; ?>>100 / page</option>
                    </select>
                </div>

                <!-- Actions -->
                <div class="col-lg-2 col-md-12 col-12 d-flex gap-2">
                    <button type="submit" class="btn btn-primary rounded-3 px-3 py-2 w-100 fw-bold d-flex align-items-center justify-content-center gap-2">
                        <i class="fa-solid fa-filter"></i> Filter
                    </button>
                    <?php if (!empty($search) || !empty($status_filter)): ?>
                        <a href="<?php echo site_url('admin/visas'); ?>" class="btn btn-light border rounded-3 px-3 py-2 text-muted" title="Reset all filters">
                            <i class="fa-solid fa-rotate-left"></i>
                        </a>
                    <?php endif; ?>
                </div>

            </form>
        </div>
    </div>

    <!-- Enquiries Table Card -->
    <div class="table-card mb-4">
        
        <!-- Table Header Bar -->
        <div class="p-3 px-4 border-bottom bg-white d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div class="d-flex align-items-center gap-2">
                <h6 class="mb-0 fw-bold text-dark">Visa Enquiries List</h6>
                <span class="badge bg-light text-secondary border rounded-pill px-2.5 py-1" style="font-size: 11px;">
                    <?php echo number_format($paging['total_records'] ?? 0); ?> Results
                </span>
            </div>
            <div class="text-muted small">
                Showing <strong class="text-dark"><?php echo $paging['start_record']; ?>–<?php echo $paging['end_record']; ?></strong> of <strong class="text-dark"><?php echo number_format($paging['total_records']); ?></strong>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table modern-table align-middle">
                <thead>
                    <tr>
                        <th style="width: 120px;">Date & Time</th>
                        <th style="min-width: 220px;">Applicant</th>
                        <th style="min-width: 180px;">Contact Details</th>
                        <th>Destination</th>
                        <th>Purpose & Travel</th>
                        <th>Passengers & Passport</th>
                        <th style="width: 170px;">Status</th>
                        <th style="width: 100px;" class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($enquiries)): foreach ($enquiries as $e): 
                        // Clean status styling
                        $st = $e['status'];
                        $stClass = 'st-new';
                        if ($st === 'Contacted') $stClass = 'st-contacted';
                        elseif ($st === 'Documents Received') $stClass = 'st-progress';
                        elseif ($st === 'Processed') $stClass = 'st-completed';
                        elseif ($st === 'Closed') $stClass = 'st-closed';

                        // Initials
                        $initials = 'V';
                        if (!empty($e['name'])) {
                            $parts = explode(' ', trim($e['name']));
                            $initials = strtoupper(substr($parts[0], 0, 1) . (isset($parts[1]) ? substr($parts[1], 0, 1) : ''));
                        }

                        // Clean Phone for WhatsApp
                        $cleanPhone = preg_replace('/[^0-9]/', '', $e['phone']);
                        if (strlen($cleanPhone) === 10) $cleanPhone = '91' . $cleanPhone;
                    ?>
                    <tr>
                        <!-- Date -->
                        <td>
                            <div class="fw-bold text-dark" style="font-size: 12.5px;"><?php echo date('d M Y', strtotime($e['created_at'])); ?></div>
                            <small class="text-muted" style="font-size: 11px;"><i class="fa-regular fa-clock me-1"></i><?php echo date('H:i', strtotime($e['created_at'])); ?></small>
                        </td>

                        <!-- Applicant -->
                        <td>
                            <div class="d-flex align-items-center gap-2.5">
                                <div class="avatar-initials bg-primary-subtle text-primary">
                                    <?php echo $initials; ?>
                                </div>
                                <div>
                                    <div class="fw-bold text-dark" style="font-size: 13.5px;"><?php echo htmlspecialchars($e['name']); ?></div>
                                    <span class="badge bg-light text-muted border px-1.5 py-0.5" style="font-size: 10px;">
                                        <?php echo htmlspecialchars($e['source_form'] ?: 'Visa Request'); ?>
                                    </span>
                                </div>
                            </div>
                        </td>

                        <!-- Contact Details -->
                        <td>
                            <div>
                                <a href="tel:<?php echo htmlspecialchars($e['phone']); ?>" class="fw-semibold text-dark text-decoration-none d-inline-flex align-items-center gap-1">
                                    <i class="fa-solid fa-phone text-primary" style="font-size: 11px;"></i> <?php echo htmlspecialchars($e['phone']); ?>
                                </a>
                                <a href="https://wa.me/<?php echo $cleanPhone; ?>" target="_blank" class="ms-1 text-success text-decoration-none" title="Chat on WhatsApp">
                                    <i class="fa-brands fa-whatsapp fs-6 align-middle"></i>
                                </a>
                            </div>
                            <?php if (!empty($e['email'])): ?>
                                <div class="mt-0.5">
                                    <a href="mailto:<?php echo htmlspecialchars($e['email']); ?>" class="text-muted text-decoration-none small d-inline-flex align-items-center gap-1" style="font-size: 11.5px;">
                                        <i class="fa-solid fa-envelope text-secondary" style="font-size: 10px;"></i> <?php echo htmlspecialchars($e['email']); ?>
                                    </a>
                                </div>
                            <?php endif; ?>
                        </td>

                        <!-- Destination -->
                        <td>
                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2.5 py-1.5 rounded-3 fw-bold" style="font-size: 12px;">
                                <i class="fa-solid fa-earth-americas me-1"></i> <?php echo htmlspecialchars($e['destination_country']); ?>
                            </span>
                        </td>

                        <!-- Purpose & Travel Date -->
                        <td>
                            <div class="fw-semibold text-dark" style="font-size: 12.5px;">
                                <span class="badge bg-light text-dark border px-2 py-1"><?php echo htmlspecialchars($e['purpose_of_travel'] ?: 'Tourist'); ?></span>
                            </div>
                            <?php if (!empty($e['travel_date'])): ?>
                                <div class="text-muted small mt-1" style="font-size: 11.5px;">
                                    <i class="fa-regular fa-calendar me-1 text-primary"></i> <?php echo htmlspecialchars($e['travel_date']); ?>
                                </div>
                            <?php endif; ?>
                        </td>

                        <!-- Passengers & Passport -->
                        <td>
                            <div class="fw-semibold text-dark" style="font-size: 12.5px;">
                                <i class="fa-solid fa-user-group me-1 text-muted"></i> <?php echo htmlspecialchars($e['passengers'] ?: '1 Traveler'); ?>
                            </div>
                            <div class="text-muted small mt-0.5" style="font-size: 11px;">
                                Passport: <strong class="<?php echo ($e['has_passport'] === 'Yes') ? 'text-success' : 'text-muted'; ?>"><?php echo htmlspecialchars($e['has_passport']); ?></strong>
                                <?php if (!empty($e['passport_number'])): ?>
                                    <span class="text-dark fw-semibold ms-1">(<?php echo htmlspecialchars($e['passport_number']); ?>)</span>
                                <?php endif; ?>
                            </div>
                        </td>

                        <!-- Inline Status Select (No Clipping Bug, Instant 1-Click Update) -->
                        <td>
                            <form method="POST" action="<?php echo site_url('admin/visas/status/' . $e['id']); ?>" class="m-0 status-form">
                                <select name="status" class="status-select-pill <?php echo $stClass; ?>" onchange="this.form.submit()" title="Click to change status">
                                    <option value="New" <?php echo ($st === 'New') ? 'selected' : ''; ?>>New</option>
                                    <option value="Contacted" <?php echo ($st === 'Contacted') ? 'selected' : ''; ?>>Contacted</option>
                                    <option value="Documents Received" <?php echo ($st === 'Documents Received') ? 'selected' : ''; ?>>Docs Received</option>
                                    <option value="Processed" <?php echo ($st === 'Processed') ? 'selected' : ''; ?>>Processed</option>
                                    <option value="Closed" <?php echo ($st === 'Closed') ? 'selected' : ''; ?>>Closed</option>
                                </select>
                            </form>
                        </td>

                        <!-- Actions (View Detail Modal + Delete) -->
                        <td class="text-center">
                            <div class="d-inline-flex align-items-center gap-1.5">
                                <!-- View Details Button -->
                                <button type="button" class="btn-action-icon" data-bs-toggle="modal" data-bs-target="#modalVisa<?php echo $e['id']; ?>" title="View Full Details">
                                    <i class="fa-regular fa-eye"></i>
                                </button>
                                <!-- Delete Button -->
                                <a href="<?php echo site_url('admin/visas/delete/' . $e['id']); ?>" class="btn-action-icon danger" onclick="return confirm('Are you sure you want to delete this visa enquiry?');" title="Delete Enquiry">
                                    <i class="fa-regular fa-trash-can"></i>
                                </a>
                            </div>

                            <!-- View Details Modal -->
                            <div class="modal fade" id="modalVisa<?php echo $e['id']; ?>" tabindex="-1" aria-labelledby="modalLabelVisa<?php echo $e['id']; ?>" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered modal-lg text-start">
                                    <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
                                        <div class="modal-header border-bottom p-3 px-4 bg-light">
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="avatar-initials bg-primary text-white">
                                                    <?php echo $initials; ?>
                                                </div>
                                                <div>
                                                    <h5 class="modal-title fw-bold text-dark mb-0" id="modalLabelVisa<?php echo $e['id']; ?>">
                                                        <?php echo htmlspecialchars($e['name']); ?>
                                                    </h5>
                                                    <span class="text-muted small">Visa Enquiry #<?php echo $e['id']; ?> &bull; <?php echo date('d M Y, H:i', strtotime($e['created_at'])); ?></span>
                                                </div>
                                            </div>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>

                                        <div class="modal-body p-4">
                                            <!-- Row 1: Contact Information -->
                                            <div class="row g-3 mb-3">
                                                <div class="col-md-6">
                                                    <div class="detail-box">
                                                        <div class="detail-label"><i class="fa-solid fa-phone me-1 text-primary"></i> Phone Number</div>
                                                        <div class="detail-val">
                                                            <a href="tel:<?php echo htmlspecialchars($e['phone']); ?>" class="text-dark text-decoration-none"><?php echo htmlspecialchars($e['phone']); ?></a>
                                                            <a href="https://wa.me/<?php echo $cleanPhone; ?>" target="_blank" class="btn btn-sm btn-success py-0 px-2 ms-2 rounded-pill fw-bold" style="font-size: 11px;">
                                                                <i class="fa-brands fa-whatsapp"></i> Chat
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="detail-box">
                                                        <div class="detail-label"><i class="fa-solid fa-envelope me-1 text-primary"></i> Email Address</div>
                                                        <div class="detail-val">
                                                            <?php if (!empty($e['email'])): ?>
                                                                <a href="mailto:<?php echo htmlspecialchars($e['email']); ?>" class="text-primary text-decoration-none"><?php echo htmlspecialchars($e['email']); ?></a>
                                                            <?php else: ?>
                                                                <span class="text-muted">Not provided</span>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Row 2: Travel Details -->
                                            <div class="row g-3 mb-3">
                                                <div class="col-md-4">
                                                    <div class="detail-box">
                                                        <div class="detail-label"><i class="fa-solid fa-location-dot me-1 text-primary"></i> Destination Country</div>
                                                        <div class="detail-val text-primary"><?php echo htmlspecialchars($e['destination_country']); ?></div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="detail-box">
                                                        <div class="detail-label"><i class="fa-solid fa-briefcase me-1 text-primary"></i> Purpose of Travel</div>
                                                        <div class="detail-val"><?php echo htmlspecialchars($e['purpose_of_travel'] ?: 'Tourist'); ?></div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="detail-box">
                                                        <div class="detail-label"><i class="fa-regular fa-calendar me-1 text-primary"></i> Tentative Travel Date</div>
                                                        <div class="detail-val"><?php echo htmlspecialchars($e['travel_date'] ?: 'Not specified'); ?></div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Row 3: Passport & Travelers -->
                                            <div class="row g-3 mb-3">
                                                <div class="col-md-4">
                                                    <div class="detail-box">
                                                        <div class="detail-label"><i class="fa-solid fa-users me-1 text-primary"></i> Travelers</div>
                                                        <div class="detail-val"><?php echo htmlspecialchars($e['passengers'] ?: '1 Traveler'); ?></div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="detail-box">
                                                        <div class="detail-label"><i class="fa-solid fa-passport me-1 text-primary"></i> Has Valid Passport?</div>
                                                        <div class="detail-val">
                                                            <span class="badge <?php echo ($e['has_passport'] === 'Yes') ? 'bg-success' : 'bg-secondary'; ?> rounded-pill px-2.5 py-1">
                                                                <?php echo htmlspecialchars($e['has_passport']); ?>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="detail-box">
                                                        <div class="detail-label"><i class="fa-solid fa-id-card me-1 text-primary"></i> Passport Number</div>
                                                        <div class="detail-val font-monospace"><?php echo htmlspecialchars($e['passport_number'] ?: 'N/A'); ?></div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Notes & Special Requests -->
                                            <?php if (!empty($e['notes'])): ?>
                                            <div class="detail-box mb-3">
                                                <div class="detail-label"><i class="fa-regular fa-comment-dots me-1 text-primary"></i> Additional Notes / Special Instructions</div>
                                                <div class="detail-val text-muted" style="white-space: pre-line; font-weight: 500; font-size: 13px;"><?php echo nl2br(htmlspecialchars($e['notes'])); ?></div>
                                            </div>
                                            <?php endif; ?>

                                            <!-- Status Update Inside Modal -->
                                            <div class="detail-box bg-white border-primary-subtle p-3 rounded-3" style="border: 1.5px dashed #cbd5e1;">
                                                <form method="POST" action="<?php echo site_url('admin/visas/status/' . $e['id']); ?>" class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                                                    <div>
                                                        <div class="detail-label mb-0">Current Enquiry Status:</div>
                                                        <span class="fw-bold text-dark fs-6"><?php echo htmlspecialchars($st); ?></span>
                                                    </div>
                                                    <div class="d-flex align-items-center gap-2">
                                                        <select name="status" class="form-select form-select-sm bg-light fw-bold" style="width: 180px;">
                                                            <option value="New" <?php echo ($st === 'New') ? 'selected' : ''; ?>>New</option>
                                                            <option value="Contacted" <?php echo ($st === 'Contacted') ? 'selected' : ''; ?>>Contacted</option>
                                                            <option value="Documents Received" <?php echo ($st === 'Documents Received') ? 'selected' : ''; ?>>Documents Received</option>
                                                            <option value="Processed" <?php echo ($st === 'Processed') ? 'selected' : ''; ?>>Processed</option>
                                                            <option value="Closed" <?php echo ($st === 'Closed') ? 'selected' : ''; ?>>Closed</option>
                                                        </select>
                                                        <button type="submit" class="btn btn-sm btn-primary fw-bold px-3">Update</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>

                                        <div class="modal-footer border-top p-3 bg-light">
                                            <a href="<?php echo site_url('admin/visas/delete/' . $e['id']); ?>" class="btn btn-sm btn-outline-danger fw-semibold" onclick="return confirm('Delete this enquiry?');">
                                                <i class="fa-solid fa-trash me-1"></i> Delete Enquiry
                                            </a>
                                            <button type="button" class="btn btn-sm btn-secondary fw-semibold px-4" data-bs-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; else: ?>
                    <tr>
                        <td colspan="8" class="text-center py-5">
                            <div class="py-4">
                                <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 64px; height: 64px; background: #f1f5f9; color: #94a3b8; font-size: 26px;">
                                    <i class="fa-solid fa-passport"></i>
                                </div>
                                <h6 class="fw-bold text-dark mb-1">No Visa Enquiries Found</h6>
                                <p class="text-muted small mb-3">
                                    <?php if (!empty($search) || !empty($status_filter)): ?>
                                        No results matched your search criteria. Try clearing filters.
                                    <?php else: ?>
                                        New customer visa applications and leads will appear here automatically.
                                    <?php endif; ?>
                                </p>
                                <?php if (!empty($search) || !empty($status_filter)): ?>
                                    <a href="<?php echo site_url('admin/visas'); ?>" class="btn btn-sm btn-outline-primary rounded-3 px-3 fw-bold">
                                        Clear Search Filters
                                    </a>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination Bar -->
        <div class="p-3 px-4 bg-white border-top d-flex flex-column flex-md-row align-items-center justify-content-between gap-3">
            <div class="text-muted small">
                Showing <strong class="text-dark"><?php echo $paging['start_record']; ?></strong> to <strong class="text-dark"><?php echo $paging['end_record']; ?></strong> of <strong class="text-dark"><?php echo number_format($paging['total_records']); ?></strong> entries
                <?php if (!empty($search) || !empty($status_filter)): ?>
                    <span class="badge bg-light text-secondary ms-2 border">Filtered</span>
                <?php endif; ?>
            </div>

            <?php if ($paging['total_pages'] > 1): ?>
            <nav aria-label="Visa pagination">
                <ul class="pagination pagination-sm pagination-custom mb-0">
                    
                    <!-- First Page -->
                    <li class="page-item <?php echo ($paging['page'] <= 1) ? 'disabled' : ''; ?>">
                        <a class="page-link" href="<?php echo visa_page_url(1, $search, $status_filter, $paging['limit']); ?>" aria-label="First">
                            <i class="fa-solid fa-angles-left"></i>
                        </a>
                    </li>

                    <!-- Previous Page -->
                    <li class="page-item <?php echo ($paging['page'] <= 1) ? 'disabled' : ''; ?>">
                        <a class="page-link" href="<?php echo visa_page_url($paging['page'] - 1, $search, $status_filter, $paging['limit']); ?>" aria-label="Previous">
                            <i class="fa-solid fa-angle-left"></i>
                        </a>
                    </li>

                    <!-- Smart Page Numbers -->
                    <?php
                        $start_p = max(1, $paging['page'] - 2);
                        $end_p   = min($paging['total_pages'], $paging['page'] + 2);

                        if ($start_p > 1): ?>
                            <li class="page-item"><a class="page-link" href="<?php echo visa_page_url(1, $search, $status_filter, $paging['limit']); ?>">1</a></li>
                            <?php if ($start_p > 2): ?>
                                <li class="page-item disabled"><span class="page-link">&hellip;</span></li>
                            <?php endif; ?>
                        <?php endif;

                        for ($p = $start_p; $p <= $end_p; $p++): ?>
                            <li class="page-item <?php echo ($p == $paging['page']) ? 'active' : ''; ?>">
                                <a class="page-link" href="<?php echo visa_page_url($p, $search, $status_filter, $paging['limit']); ?>"><?php echo $p; ?></a>
                            </li>
                        <?php endfor;

                        if ($end_p < $paging['total_pages']): ?>
                            <?php if ($end_p < $paging['total_pages'] - 1): ?>
                                <li class="page-item disabled"><span class="page-link">&hellip;</span></li>
                            <?php endif; ?>
                            <li class="page-item"><a class="page-link" href="<?php echo visa_page_url($paging['total_pages'], $search, $status_filter, $paging['limit']); ?>"><?php echo $paging['total_pages']; ?></a></li>
                        <?php endif;
                    ?>

                    <!-- Next Page -->
                    <li class="page-item <?php echo ($paging['page'] >= $paging['total_pages']) ? 'disabled' : ''; ?>">
                        <a class="page-link" href="<?php echo visa_page_url($paging['page'] + 1, $search, $status_filter, $paging['limit']); ?>" aria-label="Next">
                            <i class="fa-solid fa-angle-right"></i>
                        </a>
                    </li>

                    <!-- Last Page -->
                    <li class="page-item <?php echo ($paging['page'] >= $paging['total_pages']) ? 'disabled' : ''; ?>">
                        <a class="page-link" href="<?php echo visa_page_url($paging['total_pages'], $search, $status_filter, $paging['limit']); ?>" aria-label="Last">
                            <i class="fa-solid fa-angles-right"></i>
                        </a>
                    </li>

                </ul>
            </nav>
            <?php endif; ?>
        </div>

    </div>

</div>
