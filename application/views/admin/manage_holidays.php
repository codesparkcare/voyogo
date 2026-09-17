<?php
// Helper to construct pagination URLs while retaining filters
if (!function_exists('holiday_page_url')) {
    function holiday_page_url($target_page, $search, $status, $limit) {
        $params = [];
        if (!empty($search)) $params['search'] = $search;
        if (!empty($status)) $params['status'] = $status;
        if (!empty($limit) && (int)$limit !== 10) $params['limit'] = $limit;
        if ((int)$target_page > 1) $params['page'] = $target_page;
        $qs = http_build_query($params);
        return site_url('admin/holidays') . ($qs ? '?' . $qs : '');
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
                <span class="badge bg-success-subtle text-success fw-bold px-2.5 py-1 rounded-pill" style="font-size: 11px;">
                    <i class="fa-solid fa-umbrella-beach me-1"></i> HOLIDAY PACKAGES DESK
                </span>
                <span class="text-muted small">Tour Packages, Honeymoon & Custom Vacations</span>
            </div>
            <h3 class="fw-black text-dark mb-0 d-flex align-items-center gap-2" style="font-weight: 800; letter-spacing: -0.5px;">
                Manage Holiday Enquiries
            </h3>
        </div>
        <div class="d-flex align-items-center gap-2">
            <a href="<?php echo site_url('admin/holidays'); ?>" class="btn btn-sm btn-light border rounded-3 fw-semibold text-secondary d-flex align-items-center gap-2 px-3 py-2">
                <i class="fa-solid fa-arrows-rotate"></i> Refresh
            </a>
        </div>
    </div>

    <!-- 4 KPI Stat Cards Strip -->
    <div class="row g-3 mb-4">
        <!-- Total Requests -->
        <div class="col-xl-3 col-sm-6">
            <div class="kpi-card">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small fw-bold text-uppercase" style="font-size: 11px; letter-spacing: 0.5px;">Total Enquiries</span>
                    <div class="kpi-icon" style="background: #ecfdf5; color: #10b981;">
                        <i class="fa-solid fa-umbrella-beach"></i>
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
                    <span class="text-muted small fw-bold text-uppercase" style="font-size: 11px; letter-spacing: 0.5px;">New / Uncontacted</span>
                    <div class="kpi-icon" style="background: #fef3c7; color: #d97706;">
                        <i class="fa-solid fa-bell"></i>
                    </div>
                </div>
                <div class="d-flex align-items-baseline gap-2">
                    <h2 class="fw-black text-dark mb-0" style="font-weight: 800;"><?php echo number_format($new_count ?? 0); ?></h2>
                    <?php if (($new_count ?? 0) > 0): ?>
                        <span class="badge bg-warning text-dark rounded-pill px-2 py-0.5 small d-flex align-items-center gap-1">
                            <span class="pulse-dot"></span> Needs Quote
                        </span>
                    <?php else: ?>
                        <span class="text-muted small">All clear</span>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- In Negotiation / Follow-up -->
        <div class="col-xl-3 col-sm-6">
            <div class="kpi-card">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small fw-bold text-uppercase" style="font-size: 11px; letter-spacing: 0.5px;">Quotes & Follow-up</span>
                    <div class="kpi-icon" style="background: #eff6ff; color: #2563eb;">
                        <i class="fa-solid fa-comments"></i>
                    </div>
                </div>
                <div class="d-flex align-items-baseline gap-2">
                    <h2 class="fw-black text-dark mb-0" style="font-weight: 800;"><?php echo number_format($in_prog_count ?? 0); ?></h2>
                    <span class="text-muted small">active discussions</span>
                </div>
            </div>
        </div>

        <!-- Booked Packages -->
        <div class="col-xl-3 col-sm-6">
            <div class="kpi-card">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small fw-bold text-uppercase" style="font-size: 11px; letter-spacing: 0.5px;">Booked Packages</span>
                    <div class="kpi-icon" style="background: #f0fdf4; color: #15803d;">
                        <i class="fa-solid fa-champagne-glasses"></i>
                    </div>
                </div>
                <div class="d-flex align-items-baseline gap-2">
                    <h2 class="fw-black text-dark mb-0" style="font-weight: 800;"><?php echo number_format($completed_count ?? 0); ?></h2>
                    <span class="text-muted small">converted sales</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter & Search Toolbar -->
    <div class="card border-0 shadow-sm rounded-4 mb-4 bg-white" style="border: 1px solid #e2e8f0 !important;">
        <div class="card-body p-3">
            <form method="GET" action="<?php echo site_url('admin/holidays'); ?>" class="row g-2 align-items-center">
                
                <div class="col-lg-5 col-md-6 col-12">
                    <div class="input-group">
                        <span class="input-group-text bg-light border-0 text-muted ps-3">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </span>
                        <input type="text" name="search" class="form-control bg-light border-0 py-2" placeholder="Search customer, phone, destination, package name..." value="<?php echo htmlspecialchars($search ?? ''); ?>">
                        <?php if (!empty($search)): ?>
                            <a href="<?php echo holiday_page_url(1, '', $status_filter, $paging['limit']); ?>" class="input-group-text bg-light border-0 text-muted" title="Clear search">
                                <i class="fa-solid fa-xmark"></i>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="col-lg-3 col-md-3 col-6">
                    <select name="status" class="form-select bg-light border-0 py-2">
                        <option value="">All Statuses</option>
                        <option value="New" <?php echo (($status_filter ?? '') === 'New') ? 'selected' : ''; ?>>New / Unprocessed</option>
                        <option value="Quote Sent" <?php echo (($status_filter ?? '') === 'Quote Sent') ? 'selected' : ''; ?>>Quote Sent</option>
                        <option value="Follow-up" <?php echo (($status_filter ?? '') === 'Follow-up') ? 'selected' : ''; ?>>Follow-up</option>
                        <option value="Booked" <?php echo (($status_filter ?? '') === 'Booked') ? 'selected' : ''; ?>>Booked / Won</option>
                        <option value="Lost" <?php echo (($status_filter ?? '') === 'Lost') ? 'selected' : ''; ?>>Lost / Closed</option>
                    </select>
                </div>

                <div class="col-lg-2 col-md-3 col-6">
                    <select name="limit" class="form-select bg-light border-0 py-2">
                        <option value="10" <?php echo (($paging['limit'] ?? 10) == 10) ? 'selected' : ''; ?>>10 / page</option>
                        <option value="25" <?php echo (($paging['limit'] ?? 10) == 25) ? 'selected' : ''; ?>>25 / page</option>
                        <option value="50" <?php echo (($paging['limit'] ?? 10) == 50) ? 'selected' : ''; ?>>50 / page</option>
                        <option value="100" <?php echo (($paging['limit'] ?? 10) == 100) ? 'selected' : ''; ?>>100 / page</option>
                    </select>
                </div>

                <div class="col-lg-2 col-md-12 col-12 d-flex gap-2">
                    <button type="submit" class="btn btn-success rounded-3 px-3 py-2 w-100 fw-bold d-flex align-items-center justify-content-center gap-2">
                        <i class="fa-solid fa-filter"></i> Filter
                    </button>
                    <?php if (!empty($search) || !empty($status_filter)): ?>
                        <a href="<?php echo site_url('admin/holidays'); ?>" class="btn btn-light border rounded-3 px-3 py-2 text-muted" title="Reset all filters">
                            <i class="fa-solid fa-rotate-left"></i>
                        </a>
                    <?php endif; ?>
                </div>

            </form>
        </div>
    </div>

    <!-- Holidays Table Card -->
    <div class="table-card mb-4">
        
        <div class="p-3 px-4 border-bottom bg-white d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div class="d-flex align-items-center gap-2">
                <h6 class="mb-0 fw-bold text-dark">Holiday Package Leads</h6>
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
                        <th style="min-width: 200px;">Customer</th>
                        <th style="min-width: 180px;">Destination</th>
                        <th>Package Name</th>
                        <th>Travel Date & Pax</th>
                        <th>Budget Range</th>
                        <th style="width: 170px;">Status</th>
                        <th style="width: 100px;" class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($enquiries)): foreach ($enquiries as $e): 
                        // Status class
                        $st = $e['status'];
                        $stClass = 'st-new';
                        if ($st === 'Quote Sent') $stClass = 'st-quoted';
                        elseif ($st === 'Follow-up') $stClass = 'st-progress';
                        elseif ($st === 'Booked') $stClass = 'st-completed';
                        elseif ($st === 'Lost') $stClass = 'st-closed';

                        // Initials
                        $initials = 'H';
                        if (!empty($e['name'])) {
                            $parts = explode(' ', trim($e['name']));
                            $initials = strtoupper(substr($parts[0], 0, 1) . (isset($parts[1]) ? substr($parts[1], 0, 1) : ''));
                        }

                        // WhatsApp
                        $cleanPhone = preg_replace('/[^0-9]/', '', $e['phone']);
                        if (strlen($cleanPhone) === 10) $cleanPhone = '91' . $cleanPhone;
                    ?>
                    <tr>
                        <!-- Date -->
                        <td>
                            <div class="fw-bold text-dark" style="font-size: 12.5px;"><?php echo date('d M Y', strtotime($e['created_at'])); ?></div>
                            <small class="text-muted" style="font-size: 11px;"><i class="fa-regular fa-clock me-1"></i><?php echo date('H:i', strtotime($e['created_at'])); ?></small>
                        </td>

                        <!-- Customer -->
                        <td>
                            <div class="d-flex align-items-center gap-2.5">
                                <div class="avatar-initials bg-success-subtle text-success">
                                    <?php echo $initials; ?>
                                </div>
                                <div>
                                    <div class="fw-bold text-dark" style="font-size: 13.5px;"><?php echo htmlspecialchars($e['name']); ?></div>
                                    <div class="mt-0.5">
                                        <a href="tel:<?php echo htmlspecialchars($e['phone']); ?>" class="text-dark text-decoration-none small fw-semibold">
                                            <i class="fa-solid fa-phone text-success me-1" style="font-size: 10px;"></i><?php echo htmlspecialchars($e['phone']); ?>
                                        </a>
                                        <a href="https://wa.me/<?php echo $cleanPhone; ?>" target="_blank" class="ms-1 text-success text-decoration-none" title="WhatsApp">
                                            <i class="fa-brands fa-whatsapp fs-6 align-middle"></i>
                                        </a>
                                    </div>
                                    <?php if (!empty($e['email'])): ?>
                                        <div class="text-muted small" style="font-size: 11px;"><?php echo htmlspecialchars($e['email']); ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </td>

                        <!-- Destination -->
                        <td>
                            <span class="badge bg-success-subtle text-success border border-success-subtle px-2.5 py-1.5 rounded-3 fw-bold" style="font-size: 12px;">
                                <i class="fa-solid fa-umbrella-beach me-1"></i> <?php echo htmlspecialchars($e['destination']); ?>
                            </span>
                        </td>

                        <!-- Package Name -->
                        <td>
                            <div class="fw-semibold text-dark text-truncate" style="max-width: 220px;" title="<?php echo htmlspecialchars($e['package_name'] ?: 'Customized Itinerary'); ?>">
                                <?php echo htmlspecialchars($e['package_name'] ?: 'Customized Itinerary'); ?>
                            </div>
                        </td>

                        <!-- Travel Date & Pax -->
                        <td>
                            <div class="fw-semibold text-dark" style="font-size: 12.5px;">
                                <i class="fa-regular fa-calendar me-1 text-primary"></i> <?php echo htmlspecialchars($e['travel_date'] ?: 'Flexible'); ?>
                            </div>
                            <small class="text-muted" style="font-size: 11px;">
                                <i class="fa-solid fa-users text-muted me-1"></i> <?php echo htmlspecialchars($e['people_count'] ?: '2 People'); ?>
                            </small>
                        </td>

                        <!-- Budget Range -->
                        <td>
                            <?php if (!empty($e['budget_range'])): ?>
                                <span class="badge bg-light text-dark border px-2 py-1 fw-bold" style="font-size: 11.5px;">
                                    <?php echo htmlspecialchars($e['budget_range']); ?>
                                </span>
                            <?php else: ?>
                                <span class="text-muted small">Standard</span>
                            <?php endif; ?>
                        </td>

                        <!-- Inline Status Select -->
                        <td>
                            <form method="POST" action="<?php echo site_url('admin/holidays/status/' . $e['id']); ?>" class="m-0 status-form">
                                <select name="status" class="status-select-pill <?php echo $stClass; ?>" onchange="this.form.submit()" title="Click to change status">
                                    <option value="New" <?php echo ($st === 'New') ? 'selected' : ''; ?>>New</option>
                                    <option value="Quote Sent" <?php echo ($st === 'Quote Sent') ? 'selected' : ''; ?>>Quote Sent</option>
                                    <option value="Follow-up" <?php echo ($st === 'Follow-up') ? 'selected' : ''; ?>>Follow-up</option>
                                    <option value="Booked" <?php echo ($st === 'Booked') ? 'selected' : ''; ?>>Booked</option>
                                    <option value="Lost" <?php echo ($st === 'Lost') ? 'selected' : ''; ?>>Lost</option>
                                </select>
                            </form>
                        </td>

                        <!-- Actions -->
                        <td class="text-center">
                            <div class="d-inline-flex align-items-center gap-1.5">
                                <button type="button" class="btn-action-icon" data-bs-toggle="modal" data-bs-target="#modalHol<?php echo $e['id']; ?>" title="View Full Details">
                                    <i class="fa-regular fa-eye"></i>
                                </button>
                                <a href="<?php echo site_url('admin/holidays/delete/' . $e['id']); ?>" class="btn-action-icon danger" onclick="return confirm('Delete this holiday enquiry?');" title="Delete Enquiry">
                                    <i class="fa-regular fa-trash-can"></i>
                                </a>
                            </div>

                            <!-- Modal Details -->
                            <div class="modal fade" id="modalHol<?php echo $e['id']; ?>" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered modal-lg text-start">
                                    <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
                                        <div class="modal-header border-bottom p-3 px-4 bg-light">
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="avatar-initials bg-success text-white">
                                                    <?php echo $initials; ?>
                                                </div>
                                                <div>
                                                    <h5 class="modal-title fw-bold text-dark mb-0">
                                                        <?php echo htmlspecialchars($e['name']); ?>
                                                    </h5>
                                                    <span class="text-muted small">Holiday Lead #<?php echo $e['id']; ?> &bull; <?php echo date('d M Y, H:i', strtotime($e['created_at'])); ?></span>
                                                </div>
                                            </div>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>

                                        <div class="modal-body p-4">
                                            <div class="row g-3 mb-3">
                                                <div class="col-md-6">
                                                    <div class="detail-box">
                                                        <div class="detail-label"><i class="fa-solid fa-phone me-1 text-success"></i> Contact Phone</div>
                                                        <div class="detail-val">
                                                            <a href="tel:<?php echo htmlspecialchars($e['phone']); ?>" class="text-dark text-decoration-none"><?php echo htmlspecialchars($e['phone']); ?></a>
                                                            <a href="https://wa.me/<?php echo $cleanPhone; ?>" target="_blank" class="btn btn-sm btn-success py-0 px-2 ms-2 rounded-pill fw-bold" style="font-size: 11px;">
                                                                <i class="fa-brands fa-whatsapp"></i> WhatsApp
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="detail-box">
                                                        <div class="detail-label"><i class="fa-solid fa-envelope me-1 text-success"></i> Email Address</div>
                                                        <div class="detail-val">
                                                            <?php echo !empty($e['email']) ? htmlspecialchars($e['email']) : '<span class="text-muted">Not provided</span>'; ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row g-3 mb-3">
                                                <div class="col-md-6">
                                                    <div class="detail-box">
                                                        <div class="detail-label"><i class="fa-solid fa-umbrella-beach me-1 text-success"></i> Destination Requested</div>
                                                        <div class="detail-val text-success"><?php echo htmlspecialchars($e['destination']); ?></div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="detail-box">
                                                        <div class="detail-label">Tour / Package Name</div>
                                                        <div class="detail-val"><?php echo htmlspecialchars($e['package_name'] ?: 'Customized Package'); ?></div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row g-3 mb-3">
                                                <div class="col-md-4">
                                                    <div class="detail-box">
                                                        <div class="detail-label">Travel Date</div>
                                                        <div class="detail-val"><?php echo htmlspecialchars($e['travel_date'] ?: 'Flexible'); ?></div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="detail-box">
                                                        <div class="detail-label">Travelers Count</div>
                                                        <div class="detail-val"><?php echo htmlspecialchars($e['people_count']); ?></div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="detail-box">
                                                        <div class="detail-label">Budget Range</div>
                                                        <div class="detail-val"><?php echo htmlspecialchars($e['budget_range'] ?: 'Not Specified'); ?></div>
                                                    </div>
                                                </div>
                                            </div>

                                            <?php if (!empty($e['special_requests'])): ?>
                                            <div class="detail-box mb-3">
                                                <div class="detail-label"><i class="fa-regular fa-comment-dots me-1 text-success"></i> Special Requests & Preferences</div>
                                                <div class="detail-val text-muted" style="white-space: pre-line; font-weight: 500; font-size: 13px;"><?php echo nl2br(htmlspecialchars($e['special_requests'])); ?></div>
                                            </div>
                                            <?php endif; ?>

                                            <div class="detail-box bg-white p-3 rounded-3" style="border: 1.5px dashed #cbd5e1;">
                                                <form method="POST" action="<?php echo site_url('admin/holidays/status/' . $e['id']); ?>" class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                                                    <div>
                                                        <div class="detail-label mb-0">Current Status:</div>
                                                        <span class="fw-bold text-dark fs-6"><?php echo htmlspecialchars($st); ?></span>
                                                    </div>
                                                    <div class="d-flex align-items-center gap-2">
                                                        <select name="status" class="form-select form-select-sm bg-light fw-bold" style="width: 180px;">
                                                            <option value="New" <?php echo ($st === 'New') ? 'selected' : ''; ?>>New</option>
                                                            <option value="Quote Sent" <?php echo ($st === 'Quote Sent') ? 'selected' : ''; ?>>Quote Sent</option>
                                                            <option value="Follow-up" <?php echo ($st === 'Follow-up') ? 'selected' : ''; ?>>Follow-up</option>
                                                            <option value="Booked" <?php echo ($st === 'Booked') ? 'selected' : ''; ?>>Booked</option>
                                                            <option value="Lost" <?php echo ($st === 'Lost') ? 'selected' : ''; ?>>Lost</option>
                                                        </select>
                                                        <button type="submit" class="btn btn-sm btn-success fw-bold px-3">Update</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>

                                        <div class="modal-footer border-top p-3 bg-light">
                                            <a href="<?php echo site_url('admin/holidays/delete/' . $e['id']); ?>" class="btn btn-sm btn-outline-danger fw-semibold" onclick="return confirm('Delete this enquiry?');">
                                                <i class="fa-solid fa-trash me-1"></i> Delete
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
                                <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 64px; height: 64px; background: #ecfdf5; color: #10b981; font-size: 26px;">
                                    <i class="fa-solid fa-umbrella-beach"></i>
                                </div>
                                <h6 class="fw-bold text-dark mb-1">No Holiday Enquiries Found</h6>
                                <p class="text-muted small mb-3">
                                    <?php if (!empty($search) || !empty($status_filter)): ?>
                                        No package requests matched your search filter.
                                    <?php else: ?>
                                        Vacation package leads and tour enquiries will show up here.
                                    <?php endif; ?>
                                </p>
                                <?php if (!empty($search) || !empty($status_filter)): ?>
                                    <a href="<?php echo site_url('admin/holidays'); ?>" class="btn btn-sm btn-outline-primary rounded-3 px-3 fw-bold">
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
            <nav aria-label="Holiday pagination">
                <ul class="pagination pagination-sm pagination-custom mb-0">
                    
                    <li class="page-item <?php echo ($paging['page'] <= 1) ? 'disabled' : ''; ?>">
                        <a class="page-link" href="<?php echo holiday_page_url(1, $search, $status_filter, $paging['limit']); ?>" aria-label="First">
                            <i class="fa-solid fa-angles-left"></i>
                        </a>
                    </li>

                    <li class="page-item <?php echo ($paging['page'] <= 1) ? 'disabled' : ''; ?>">
                        <a class="page-link" href="<?php echo holiday_page_url($paging['page'] - 1, $search, $status_filter, $paging['limit']); ?>" aria-label="Previous">
                            <i class="fa-solid fa-angle-left"></i>
                        </a>
                    </li>

                    <?php
                        $start_p = max(1, $paging['page'] - 2);
                        $end_p   = min($paging['total_pages'], $paging['page'] + 2);

                        if ($start_p > 1): ?>
                            <li class="page-item"><a class="page-link" href="<?php echo holiday_page_url(1, $search, $status_filter, $paging['limit']); ?>">1</a></li>
                            <?php if ($start_p > 2): ?>
                                <li class="page-item disabled"><span class="page-link">&hellip;</span></li>
                            <?php endif; ?>
                        <?php endif;

                        for ($p = $start_p; $p <= $end_p; $p++): ?>
                            <li class="page-item <?php echo ($p == $paging['page']) ? 'active' : ''; ?>">
                                <a class="page-link" href="<?php echo holiday_page_url($p, $search, $status_filter, $paging['limit']); ?>"><?php echo $p; ?></a>
                            </li>
                        <?php endfor;

                        if ($end_p < $paging['total_pages']): ?>
                            <?php if ($end_p < $paging['total_pages'] - 1): ?>
                                <li class="page-item disabled"><span class="page-link">&hellip;</span></li>
                            <?php endif; ?>
                            <li class="page-item"><a class="page-link" href="<?php echo holiday_page_url($paging['total_pages'], $search, $status_filter, $paging['limit']); ?>"><?php echo $paging['total_pages']; ?></a></li>
                        <?php endif;
                    ?>

                    <li class="page-item <?php echo ($paging['page'] >= $paging['total_pages']) ? 'disabled' : ''; ?>">
                        <a class="page-link" href="<?php echo holiday_page_url($paging['page'] + 1, $search, $status_filter, $paging['limit']); ?>" aria-label="Next">
                            <i class="fa-solid fa-angle-right"></i>
                        </a>
                    </li>

                    <li class="page-item <?php echo ($paging['page'] >= $paging['total_pages']) ? 'disabled' : ''; ?>">
                        <a class="page-link" href="<?php echo holiday_page_url($paging['total_pages'], $search, $status_filter, $paging['limit']); ?>" aria-label="Last">
                            <i class="fa-solid fa-angles-right"></i>
                        </a>
                    </li>

                </ul>
            </nav>
            <?php endif; ?>
        </div>

    </div>

</div>
