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
                <i class="fa-solid fa-coins text-warning"></i> Manage Forex Currency Orders
            </h3>
            <p class="text-muted small mb-0">Customer requests for currency exchange notes, multi-currency forex cards, and wire transfers</p>
        </div>
    </div>

    <!-- Quick Stats Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-4 col-sm-6">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 52px; height: 52px; background: #fff7ed; color: #ea580c; font-size: 22px;">
                        <i class="fa-solid fa-coins"></i>
                    </div>
                    <div>
                        <div class="text-muted small fw-bold text-uppercase">Total Forex Orders</div>
                        <h3 class="fw-black mb-0 text-dark"><?php echo number_format($total_count ?? 0); ?></h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-sm-6">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 52px; height: 52px; background: #eff6ff; color: #2563eb; font-size: 22px;">
                        <i class="fa-solid fa-clock-rotate-left"></i>
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
            <form method="GET" action="<?php echo site_url('admin/forex'); ?>" class="row g-2 align-items-center">
                <div class="col-md-5 col-sm-6">
                    <div class="input-group">
                        <span class="input-group-text bg-light border-0 text-muted"><i class="fa-solid fa-magnifying-glass"></i></span>
                        <input type="text" name="search" class="form-control bg-light border-0" placeholder="Search by name, phone, currency, city..." value="<?php echo htmlspecialchars($search ?? ''); ?>">
                    </div>
                </div>
                <div class="col-md-4 col-sm-6">
                    <select name="status" class="form-select bg-light border-0">
                        <option value="">All Statuses</option>
                        <option value="New" <?php echo (($status_filter ?? '') === 'New') ? 'selected' : ''; ?>>New</option>
                        <option value="Rate Confirmed" <?php echo (($status_filter ?? '') === 'Rate Confirmed') ? 'selected' : ''; ?>>Rate Confirmed</option>
                        <option value="Payment Pending" <?php echo (($status_filter ?? '') === 'Payment Pending') ? 'selected' : ''; ?>>Payment Pending</option>
                        <option value="Delivered" <?php echo (($status_filter ?? '') === 'Delivered') ? 'selected' : ''; ?>>Delivered</option>
                        <option value="Cancelled" <?php echo (($status_filter ?? '') === 'Cancelled') ? 'selected' : ''; ?>>Cancelled</option>
                    </select>
                </div>
                <div class="col-md-3 col-sm-12 d-flex gap-2">
                    <button type="submit" class="btn btn-warning rounded-3 px-3 w-100 fw-bold text-dark">Filter</button>
                    <?php if (!empty($search) || !empty($status_filter)): ?>
                        <a href="<?php echo site_url('admin/forex'); ?>" class="btn btn-light rounded-3 px-3 text-muted">Clear</a>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    </div>

    <!-- Forex Table Card -->
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr class="text-uppercase small text-muted">
                            <th style="width: 130px;">Date</th>
                            <th>Order Type</th>
                            <th>Customer</th>
                            <th>Location & Purpose</th>
                            <th>Currency & Product</th>
                            <th>Amount (INR)</th>
                            <th>Status</th>
                            <th style="width: 70px;" class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($enquiries)): foreach ($enquiries as $e): 
                            $badgeClass = 'bg-secondary';
                            if ($e['status'] === 'New') $badgeClass = 'bg-warning text-dark';
                            elseif ($e['status'] === 'Rate Confirmed') $badgeClass = 'bg-info text-dark';
                            elseif ($e['status'] === 'Payment Pending') $badgeClass = 'bg-primary';
                            elseif ($e['status'] === 'Delivered') $badgeClass = 'bg-success';
                            elseif ($e['status'] === 'Cancelled') $badgeClass = 'bg-danger';
                        ?>
                        <tr>
                            <td><small class="text-muted fw-bold"><?php echo date('d M Y, H:i', strtotime($e['created_at'])); ?></small></td>
                            <td>
                                <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle px-2 py-1">
                                    <?php echo htmlspecialchars($e['order_type']); ?>
                                </span>
                            </td>
                            <td>
                                <div class="fw-bold text-dark"><?php echo htmlspecialchars($e['name']); ?></div>
                                <div><a href="tel:<?php echo htmlspecialchars($e['phone']); ?>" class="fw-semibold text-dark text-decoration-none"><i class="fa-solid fa-phone text-muted me-1"></i> <?php echo htmlspecialchars($e['phone']); ?></a></div>
                                <?php if (!empty($e['email'])): ?>
                                    <small><a href="mailto:<?php echo htmlspecialchars($e['email']); ?>" class="text-muted"><?php echo htmlspecialchars($e['email']); ?></a></small>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div><i class="fa-solid fa-location-dot text-danger me-1"></i> <?php echo htmlspecialchars($e['location_city'] ?: 'Not specified'); ?></div>
                                <small class="text-muted"><?php echo htmlspecialchars($e['purpose_of_visit']); ?></small>
                            </td>
                            <td>
                                <span class="badge bg-primary px-2 py-1 fw-bold"><?php echo htmlspecialchars($e['currency']); ?></span>
                                <div class="small text-muted mt-1"><?php echo htmlspecialchars($e['product']); ?></div>
                            </td>
                            <td>
                                <?php if (!empty($e['amount_inr'])): ?>
                                    <span class="fw-bold text-dark">₹<?php echo number_format($e['amount_inr'], 2); ?></span>
                                <?php else: ?>
                                    <span class="text-muted">—</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="dropdown">
                                    <button class="btn btn-sm <?php echo $badgeClass; ?> dropdown-toggle rounded-pill px-3 py-1" type="button" data-bs-toggle="dropdown" aria-expanded="false" style="font-size: 11px; font-weight: 700;">
                                        <?php echo htmlspecialchars($e['status']); ?>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0">
                                        <li><a class="dropdown-item py-2" href="<?php echo site_url('admin/forex/status/' . $e['id'] . '?status=New'); ?>">New</a></li>
                                        <li><a class="dropdown-item py-2" href="<?php echo site_url('admin/forex/status/' . $e['id'] . '?status=Rate Confirmed'); ?>">Rate Confirmed</a></li>
                                        <li><a class="dropdown-item py-2" href="<?php echo site_url('admin/forex/status/' . $e['id'] . '?status=Payment Pending'); ?>">Payment Pending</a></li>
                                        <li><a class="dropdown-item py-2" href="<?php echo site_url('admin/forex/status/' . $e['id'] . '?status=Delivered'); ?>">Delivered</a></li>
                                        <li><a class="dropdown-item py-2" href="<?php echo site_url('admin/forex/status/' . $e['id'] . '?status=Cancelled'); ?>">Cancelled</a></li>
                                    </ul>
                                </div>
                            </td>
                            <td class="text-center">
                                <a href="<?php echo site_url('admin/forex/delete/' . $e['id']); ?>" class="btn btn-sm btn-outline-danger rounded-circle p-1" style="width: 30px; height: 30px; line-height: 1;" onclick="return confirm('Delete this forex order enquiry?');" title="Delete">
                                    <i class="fa-solid fa-trash-can" style="font-size: 11px;"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; else: ?>
                        <tr>
                            <td colspan="8" class="text-center text-muted py-5">
                                <i class="fa-solid fa-coins fa-2x mb-2 text-muted d-block opacity-50"></i>
                                No forex order requests found.
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
