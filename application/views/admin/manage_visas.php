<div class="container-fluid p-4">

    <!-- Flash Messages -->
    <?php if ($this->session->flashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm border-0" role="alert" style="background: #ecfdf5; color: #065f46; border-left: 4px solid #10b981 !important;">
            <i class="fa-solid fa-circle-check me-2"></i> <?php echo $this->session->flashdata('success'); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- Page Title -->
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <div>
            <h3 class="fw-bold text-dark mb-1 d-flex align-items-center gap-2">
                <i class="fa-solid fa-passport text-primary"></i> Manage Visa Enquiries
            </h3>
            <p class="text-muted small mb-0">Customer visa consultations and destination assistance requests</p>
        </div>
    </div>

    <!-- Quick Stats Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-4 col-sm-6">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 52px; height: 52px; background: #eff6ff; color: #2563eb; font-size: 22px;">
                        <i class="fa-solid fa-passport"></i>
                    </div>
                    <div>
                        <div class="text-muted small fw-bold text-uppercase">Total Enquiries</div>
                        <h3 class="fw-black mb-0 text-dark"><?php echo number_format($total_count ?? 0); ?></h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-sm-6">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 52px; height: 52px; background: #fef3c7; color: #d97706; font-size: 22px;">
                        <i class="fa-solid fa-bell"></i>
                    </div>
                    <div>
                        <div class="text-muted small fw-bold text-uppercase">New / Unprocessed</div>
                        <h3 class="fw-black mb-0 text-dark"><?php echo number_format($new_count ?? 0); ?></h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter & Search Toolbar -->
    <div class="card border-0 shadow-sm rounded-4 mb-4 bg-white">
        <div class="card-body p-3">
            <form method="GET" action="<?php echo site_url('admin/visas'); ?>" class="row g-2 align-items-center">
                <div class="col-md-5 col-sm-6">
                    <div class="input-group">
                        <span class="input-group-text bg-light border-0 text-muted"><i class="fa-solid fa-magnifying-glass"></i></span>
                        <input type="text" name="search" class="form-control bg-light border-0" placeholder="Search by name, phone, email, country..." value="<?php echo htmlspecialchars($search ?? ''); ?>">
                    </div>
                </div>
                <div class="col-md-4 col-sm-6">
                    <select name="status" class="form-select bg-light border-0">
                        <option value="">All Statuses</option>
                        <option value="New" <?php echo (($status_filter ?? '') === 'New') ? 'selected' : ''; ?>>New</option>
                        <option value="Contacted" <?php echo (($status_filter ?? '') === 'Contacted') ? 'selected' : ''; ?>>Contacted</option>
                        <option value="Documents Received" <?php echo (($status_filter ?? '') === 'Documents Received') ? 'selected' : ''; ?>>Documents Received</option>
                        <option value="Processed" <?php echo (($status_filter ?? '') === 'Processed') ? 'selected' : ''; ?>>Processed</option>
                        <option value="Closed" <?php echo (($status_filter ?? '') === 'Closed') ? 'selected' : ''; ?>>Closed</option>
                    </select>
                </div>
                <div class="col-md-3 col-sm-12 d-flex gap-2">
                    <button type="submit" class="btn btn-primary rounded-3 px-3 w-100 fw-bold">Filter</button>
                    <?php if (!empty($search) || !empty($status_filter)): ?>
                        <a href="<?php echo site_url('admin/visas'); ?>" class="btn btn-light rounded-3 px-3 text-muted">Clear</a>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    </div>

    <!-- Enquiries Table Card -->
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr class="text-uppercase small text-muted">
                            <th style="width: 130px;">Date</th>
                            <th>Applicant</th>
                            <th>Contact Info</th>
                            <th>Destination Country</th>
                            <th>Purpose & Date</th>
                            <th>Travelers & Passport</th>
                            <th>Status</th>
                            <th style="width: 70px;" class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($enquiries)): foreach ($enquiries as $e): 
                            $badgeClass = 'bg-secondary';
                            if ($e['status'] === 'New') $badgeClass = 'bg-warning text-dark';
                            elseif ($e['status'] === 'Contacted') $badgeClass = 'bg-info text-dark';
                            elseif ($e['status'] === 'Documents Received') $badgeClass = 'bg-primary';
                            elseif ($e['status'] === 'Processed') $badgeClass = 'bg-success';
                            elseif ($e['status'] === 'Closed') $badgeClass = 'bg-dark';
                        ?>
                        <tr>
                            <td><small class="text-muted fw-bold"><?php echo date('d M Y, H:i', strtotime($e['created_at'])); ?></small></td>
                            <td>
                                <div class="fw-bold text-dark"><?php echo htmlspecialchars($e['name']); ?></div>
                                <small class="text-muted"><?php echo htmlspecialchars($e['source_form']); ?></small>
                            </td>
                            <td>
                                <div><a href="tel:<?php echo htmlspecialchars($e['phone']); ?>" class="fw-semibold text-dark text-decoration-none"><i class="fa-solid fa-phone text-muted me-1"></i> <?php echo htmlspecialchars($e['phone']); ?></a></div>
                                <?php if (!empty($e['email'])): ?>
                                    <div><small><a href="mailto:<?php echo htmlspecialchars($e['email']); ?>" class="text-muted"><i class="fa-solid fa-envelope text-muted me-1"></i> <?php echo htmlspecialchars($e['email']); ?></a></small></div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1 fs-6">
                                    <i class="fa-solid fa-location-dot me-1"></i> <?php echo htmlspecialchars($e['destination_country']); ?>
                                </span>
                            </td>
                            <td>
                                <div><span class="badge bg-light text-dark border"><?php echo htmlspecialchars($e['purpose_of_travel']); ?></span></div>
                                <?php if (!empty($e['travel_date'])): ?>
                                    <small class="text-muted d-block mt-1"><i class="fa-regular fa-calendar me-1"></i> <?php echo htmlspecialchars($e['travel_date']); ?></small>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div><small class="fw-semibold"><?php echo htmlspecialchars($e['passengers']); ?></small></div>
                                <small class="text-muted">Passport: <?php echo htmlspecialchars($e['has_passport']); ?><?php echo !empty($e['passport_number']) ? ' (' . htmlspecialchars($e['passport_number']) . ')' : ''; ?></small>
                            </td>
                            <td>
                                <div class="dropdown">
                                    <button class="btn btn-sm <?php echo $badgeClass; ?> dropdown-toggle rounded-pill px-3 py-1" type="button" data-bs-toggle="dropdown" aria-expanded="false" style="font-size: 11px; font-weight: 700;">
                                        <?php echo htmlspecialchars($e['status']); ?>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0">
                                        <li><a class="dropdown-item py-2" href="<?php echo site_url('admin/visas/status/' . $e['id'] . '?status=New'); ?>">New</a></li>
                                        <li><a class="dropdown-item py-2" href="<?php echo site_url('admin/visas/status/' . $e['id'] . '?status=Contacted'); ?>">Contacted</a></li>
                                        <li><a class="dropdown-item py-2" href="<?php echo site_url('admin/visas/status/' . $e['id'] . '?status=Documents Received'); ?>">Documents Received</a></li>
                                        <li><a class="dropdown-item py-2" href="<?php echo site_url('admin/visas/status/' . $e['id'] . '?status=Processed'); ?>">Processed</a></li>
                                        <li><a class="dropdown-item py-2" href="<?php echo site_url('admin/visas/status/' . $e['id'] . '?status=Closed'); ?>">Closed</a></li>
                                    </ul>
                                </div>
                            </td>
                            <td class="text-center">
                                <a href="<?php echo site_url('admin/visas/delete/' . $e['id']); ?>" class="btn btn-sm btn-outline-danger rounded-circle p-1" style="width: 30px; height: 30px; line-height: 1;" onclick="return confirm('Delete this visa enquiry?');" title="Delete">
                                    <i class="fa-solid fa-trash-can" style="font-size: 11px;"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; else: ?>
                        <tr>
                            <td colspan="8" class="text-center text-muted py-5">
                                <i class="fa-solid fa-passport fa-2x mb-2 text-muted d-block opacity-50"></i>
                                No visa enquiries found.
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
