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
                <i class="fa-solid fa-umbrella-beach text-success"></i> Manage Holiday Package Enquiries
            </h3>
            <p class="text-muted small mb-0">Tour package requests, customized holiday plans, and destination quotes</p>
        </div>
    </div>

    <!-- Quick Stats Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-4 col-sm-6">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 52px; height: 52px; background: #ecfdf5; color: #059669; font-size: 22px;">
                        <i class="fa-solid fa-plane-departure"></i>
                    </div>
                    <div>
                        <div class="text-muted small fw-bold text-uppercase">Total Holiday Leads</div>
                        <h3 class="fw-black mb-0 text-dark"><?php echo number_format($total_count ?? 0); ?></h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-sm-6">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 52px; height: 52px; background: #eff6ff; color: #2563eb; font-size: 22px;">
                        <i class="fa-solid fa-bell"></i>
                    </div>
                    <div>
                        <div class="text-muted small fw-bold text-uppercase">New / Awaiting Quote</div>
                        <h3 class="fw-black mb-0 text-dark"><?php echo number_format($new_count ?? 0); ?></h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter & Search Toolbar -->
    <div class="card border-0 shadow-sm rounded-4 mb-4 bg-white">
        <div class="card-body p-3">
            <form method="GET" action="<?php echo site_url('admin/holidays'); ?>" class="row g-2 align-items-center">
                <div class="col-md-5 col-sm-6">
                    <div class="input-group">
                        <span class="input-group-text bg-light border-0 text-muted"><i class="fa-solid fa-magnifying-glass"></i></span>
                        <input type="text" name="search" class="form-control bg-light border-0" placeholder="Search by name, phone, destination, package..." value="<?php echo htmlspecialchars($search ?? ''); ?>">
                    </div>
                </div>
                <div class="col-md-4 col-sm-6">
                    <select name="status" class="form-select bg-light border-0">
                        <option value="">All Statuses</option>
                        <option value="New" <?php echo (($status_filter ?? '') === 'New') ? 'selected' : ''; ?>>New</option>
                        <option value="Quote Sent" <?php echo (($status_filter ?? '') === 'Quote Sent') ? 'selected' : ''; ?>>Quote Sent</option>
                        <option value="Follow-up" <?php echo (($status_filter ?? '') === 'Follow-up') ? 'selected' : ''; ?>>Follow-up</option>
                        <option value="Booked" <?php echo (($status_filter ?? '') === 'Booked') ? 'selected' : ''; ?>>Booked</option>
                        <option value="Lost" <?php echo (($status_filter ?? '') === 'Lost') ? 'selected' : ''; ?>>Lost</option>
                    </select>
                </div>
                <div class="col-md-3 col-sm-12 d-flex gap-2">
                    <button type="submit" class="btn btn-success rounded-3 px-3 w-100 fw-bold">Filter</button>
                    <?php if (!empty($search) || !empty($status_filter)): ?>
                        <a href="<?php echo site_url('admin/holidays'); ?>" class="btn btn-light rounded-3 px-3 text-muted">Clear</a>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    </div>

    <!-- Holiday Enquiries Table Card -->
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr class="text-uppercase small text-muted">
                            <th style="width: 130px;">Date</th>
                            <th>Customer</th>
                            <th>Destination</th>
                            <th>Travel Date & Pax</th>
                            <th>Package & Special Req</th>
                            <th>Status</th>
                            <th style="width: 70px;" class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($enquiries)): foreach ($enquiries as $e): 
                            $badgeClass = 'bg-secondary';
                            if ($e['status'] === 'New') $badgeClass = 'bg-warning text-dark';
                            elseif ($e['status'] === 'Quote Sent') $badgeClass = 'bg-info text-dark';
                            elseif ($e['status'] === 'Follow-up') $badgeClass = 'bg-primary';
                            elseif ($e['status'] === 'Booked') $badgeClass = 'bg-success';
                            elseif ($e['status'] === 'Lost') $badgeClass = 'bg-danger';
                        ?>
                        <tr>
                            <td><small class="text-muted fw-bold"><?php echo date('d M Y, H:i', strtotime($e['created_at'])); ?></small></td>
                            <td>
                                <div class="fw-bold text-dark"><?php echo htmlspecialchars($e['name']); ?></div>
                                <div><a href="tel:<?php echo htmlspecialchars($e['phone']); ?>" class="fw-semibold text-dark text-decoration-none"><i class="fa-solid fa-phone text-muted me-1"></i> <?php echo htmlspecialchars($e['phone']); ?></a></div>
                                <?php if (!empty($e['email'])): ?>
                                    <small><a href="mailto:<?php echo htmlspecialchars($e['email']); ?>" class="text-muted"><?php echo htmlspecialchars($e['email']); ?></a></small>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="badge bg-success-subtle text-success-emphasis border border-success-subtle px-2 py-1 fs-6">
                                    <i class="fa-solid fa-umbrella-beach me-1"></i> <?php echo htmlspecialchars($e['destination']); ?>
                                </span>
                            </td>
                            <td>
                                <div><i class="fa-regular fa-calendar text-muted me-1"></i> <?php echo htmlspecialchars($e['travel_date'] ?: 'Flexible'); ?></div>
                                <small class="text-muted"><i class="fa-solid fa-users me-1"></i> <?php echo htmlspecialchars($e['people_count']); ?></small>
                            </td>
                            <td>
                                <?php if (!empty($e['package_name'])): ?>
                                    <div class="fw-semibold text-dark"><?php echo htmlspecialchars($e['package_name']); ?></div>
                                <?php endif; ?>
                                <?php if (!empty($e['budget_range'])): ?>
                                    <small class="text-muted d-block">Budget: <?php echo htmlspecialchars($e['budget_range']); ?></small>
                                <?php endif; ?>
                                <?php if (!empty($e['special_requests'])): ?>
                                    <small class="text-secondary fst-italic"><?php echo htmlspecialchars(mb_strimwidth($e['special_requests'], 0, 40, '...')); ?></small>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="dropdown">
                                    <button class="btn btn-sm <?php echo $badgeClass; ?> dropdown-toggle rounded-pill px-3 py-1" type="button" data-bs-toggle="dropdown" aria-expanded="false" style="font-size: 11px; font-weight: 700;">
                                        <?php echo htmlspecialchars($e['status']); ?>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0">
                                        <li><a class="dropdown-item py-2" href="<?php echo site_url('admin/holidays/status/' . $e['id'] . '?status=New'); ?>">New</a></li>
                                        <li><a class="dropdown-item py-2" href="<?php echo site_url('admin/holidays/status/' . $e['id'] . '?status=Quote Sent'); ?>">Quote Sent</a></li>
                                        <li><a class="dropdown-item py-2" href="<?php echo site_url('admin/holidays/status/' . $e['id'] . '?status=Follow-up'); ?>">Follow-up</a></li>
                                        <li><a class="dropdown-item py-2" href="<?php echo site_url('admin/holidays/status/' . $e['id'] . '?status=Booked'); ?>">Booked</a></li>
                                        <li><a class="dropdown-item py-2" href="<?php echo site_url('admin/holidays/status/' . $e['id'] . '?status=Lost'); ?>">Lost</a></li>
                                    </ul>
                                </div>
                            </td>
                            <td class="text-center">
                                <a href="<?php echo site_url('admin/holidays/delete/' . $e['id']); ?>" class="btn btn-sm btn-outline-danger rounded-circle p-1" style="width: 30px; height: 30px; line-height: 1;" onclick="return confirm('Delete this holiday enquiry?');" title="Delete">
                                    <i class="fa-solid fa-trash-can" style="font-size: 11px;"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; else: ?>
                        <tr>
                            <td colspan="7" class="text-center text-muted py-5">
                                <i class="fa-solid fa-umbrella-beach fa-2x mb-2 text-muted d-block opacity-50"></i>
                                No holiday package enquiries found.
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
