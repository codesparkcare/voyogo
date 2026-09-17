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
                <i class="fa-solid fa-ship text-info"></i> Manage Cruise Enquiries
            </h3>
            <p class="text-muted small mb-0">Customer requests for international cruises, coastal voyages, and luxury cabins</p>
        </div>
    </div>

    <!-- Quick Stats Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-4 col-sm-6">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 52px; height: 52px; background: #ecfeff; color: #0891b2; font-size: 22px;">
                        <i class="fa-solid fa-ship"></i>
                    </div>
                    <div>
                        <div class="text-muted small fw-bold text-uppercase">Total Cruise Enquiries</div>
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
            <form method="GET" action="<?php echo site_url('admin/cruises'); ?>" class="row g-2 align-items-center">
                <div class="col-md-5 col-sm-6">
                    <div class="input-group">
                        <span class="input-group-text bg-light border-0 text-muted"><i class="fa-solid fa-magnifying-glass"></i></span>
                        <input type="text" name="search" class="form-control bg-light border-0" placeholder="Search by name, phone, destination, cruise line..." value="<?php echo htmlspecialchars($search ?? ''); ?>">
                    </div>
                </div>
                <div class="col-md-4 col-sm-6">
                    <select name="status" class="form-select bg-light border-0">
                        <option value="">All Statuses</option>
                        <option value="New" <?php echo (($status_filter ?? '') === 'New') ? 'selected' : ''; ?>>New</option>
                        <option value="Cabin Held" <?php echo (($status_filter ?? '') === 'Cabin Held') ? 'selected' : ''; ?>>Cabin Held</option>
                        <option value="Booked" <?php echo (($status_filter ?? '') === 'Booked') ? 'selected' : ''; ?>>Booked</option>
                        <option value="Closed" <?php echo (($status_filter ?? '') === 'Closed') ? 'selected' : ''; ?>>Closed</option>
                    </select>
                </div>
                <div class="col-md-3 col-sm-12 d-flex gap-2">
                    <button type="submit" class="btn btn-info rounded-3 px-3 w-100 fw-bold text-white">Filter</button>
                    <?php if (!empty($search) || !empty($status_filter)): ?>
                        <a href="<?php echo site_url('admin/cruises'); ?>" class="btn btn-light rounded-3 px-3 text-muted">Clear</a>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    </div>

    <!-- Cruises Table Card -->
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr class="text-uppercase small text-muted">
                            <th style="width: 130px;">Date</th>
                            <th>Customer</th>
                            <th>Cruise Destination</th>
                            <th>Date & Travelers</th>
                            <th>Cabin & Budget</th>
                            <th>Cruise Line & Notes</th>
                            <th>Status</th>
                            <th style="width: 70px;" class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($enquiries)): foreach ($enquiries as $e): 
                            $badgeClass = 'bg-secondary';
                            if ($e['status'] === 'New') $badgeClass = 'bg-warning text-dark';
                            elseif ($e['status'] === 'Cabin Held') $badgeClass = 'bg-info text-dark';
                            elseif ($e['status'] === 'Booked') $badgeClass = 'bg-success';
                            elseif ($e['status'] === 'Closed') $badgeClass = 'bg-dark';
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
                                <span class="badge bg-info-subtle text-info-emphasis border border-info-subtle px-2 py-1 fs-6">
                                    <i class="fa-solid fa-ship me-1"></i> <?php echo htmlspecialchars($e['destination']); ?>
                                </span>
                            </td>
                            <td>
                                <div><i class="fa-regular fa-calendar text-muted me-1"></i> <?php echo htmlspecialchars($e['travel_date'] ?: 'Flexible'); ?></div>
                                <small class="text-muted"><i class="fa-solid fa-users me-1"></i> <?php echo htmlspecialchars($e['travelers']); ?></small>
                            </td>
                            <td>
                                <div><span class="badge bg-light text-dark border"><?php echo htmlspecialchars($e['cabin_type']); ?></span></div>
                                <?php if (!empty($e['budget_per_person'])): ?>
                                    <small class="text-muted d-block mt-1">Budget: <?php echo htmlspecialchars($e['budget_per_person']); ?></small>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if (!empty($e['cruise_line'])): ?>
                                    <div class="fw-semibold text-dark"><?php echo htmlspecialchars($e['cruise_line']); ?></div>
                                <?php endif; ?>
                                <?php if (!empty($e['special_notes'])): ?>
                                    <small class="text-secondary fst-italic"><?php echo htmlspecialchars(mb_strimwidth($e['special_notes'], 0, 35, '...')); ?></small>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="dropdown">
                                    <button class="btn btn-sm <?php echo $badgeClass; ?> dropdown-toggle rounded-pill px-3 py-1" type="button" data-bs-toggle="dropdown" aria-expanded="false" style="font-size: 11px; font-weight: 700;">
                                        <?php echo htmlspecialchars($e['status']); ?>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0">
                                        <li><a class="dropdown-item py-2" href="<?php echo site_url('admin/cruises/status/' . $e['id'] . '?status=New'); ?>">New</a></li>
                                        <li><a class="dropdown-item py-2" href="<?php echo site_url('admin/cruises/status/' . $e['id'] . '?status=Cabin Held'); ?>">Cabin Held</a></li>
                                        <li><a class="dropdown-item py-2" href="<?php echo site_url('admin/cruises/status/' . $e['id'] . '?status=Booked'); ?>">Booked</a></li>
                                        <li><a class="dropdown-item py-2" href="<?php echo site_url('admin/cruises/status/' . $e['id'] . '?status=Closed'); ?>">Closed</a></li>
                                    </ul>
                                </div>
                            </td>
                            <td class="text-center">
                                <a href="<?php echo site_url('admin/cruises/delete/' . $e['id']); ?>" class="btn btn-sm btn-outline-danger rounded-circle p-1" style="width: 30px; height: 30px; line-height: 1;" onclick="return confirm('Delete this cruise enquiry?');" title="Delete">
                                    <i class="fa-solid fa-trash-can" style="font-size: 11px;"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; else: ?>
                        <tr>
                            <td colspan="8" class="text-center text-muted py-5">
                                <i class="fa-solid fa-ship fa-2x mb-2 text-muted d-block opacity-50"></i>
                                No cruise enquiries found.
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
