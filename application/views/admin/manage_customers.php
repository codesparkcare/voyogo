<div class="container-fluid p-4">

    <!-- Flash Messages -->
    <?php if ($this->session->flashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm border-0" role="alert" style="background: #ecfdf5; color: #065f46; border-left: 4px solid #10b981 !important;">
            <i class="fa-solid fa-circle-check me-2"></i> <?php echo $this->session->flashdata('success'); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if ($this->session->flashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show rounded-3 shadow-sm border-0" role="alert" style="background: #fef2f2; color: #991b1b; border-left: 4px solid #ef4444 !important;">
            <i class="fa-solid fa-triangle-exclamation me-2"></i> <?php echo $this->session->flashdata('error'); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- Page Title & Header Actions -->
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <div>
            <h3 class="fw-bold text-dark mb-1 d-flex align-items-center gap-2">
                <i class="fa-solid fa-users text-primary"></i> Manage Customers
            </h3>
            <p class="text-muted small mb-0">List of all customers registered via Firebase Phone OTP with account status & profile info</p>
        </div>
    </div>

    <!-- Quick Stats Cards -->
    <?php
        $totalCount = isset($total_customers) ? $total_customers : count($customers);
        $activeCount = 0;
        $completeProfiles = 0;
        if (!empty($customers)) {
            foreach ($customers as $c) {
                if (($c['status'] ?? 'active') === 'active') $activeCount++;
                if (!empty($c['first_name']) && !empty($c['email'])) $completeProfiles++;
            }
        }
    ?>
    <div class="row g-3 mb-4">
        <div class="col-md-4 col-sm-6">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 52px; height: 52px; background: #eff6ff; color: #2563eb; font-size: 22px;">
                        <i class="fa-solid fa-user-group"></i>
                    </div>
                    <div>
                        <div class="text-muted small fw-bold">TOTAL REGISTERED</div>
                        <h3 class="fw-black mb-0 text-dark"><?php echo number_format($totalCount); ?></h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-sm-6">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 52px; height: 52px; background: #ecfdf5; color: #10b981; font-size: 22px;">
                        <i class="fa-solid fa-shield-check"></i>
                    </div>
                    <div>
                        <div class="text-muted small fw-bold">ACTIVE CUSTOMERS</div>
                        <h3 class="fw-black mb-0 text-success"><?php echo number_format($activeCount); ?></h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-sm-12">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 52px; height: 52px; background: #fdf4ff; color: #c026d3; font-size: 22px;">
                        <i class="fa-solid fa-address-card"></i>
                    </div>
                    <div>
                        <div class="text-muted small fw-bold">PROFILES COMPLETED</div>
                        <h3 class="fw-black mb-0 text-dark"><?php echo number_format($completeProfiles); ?></h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Search & Filter Card -->
    <div class="card border-0 shadow-sm rounded-4 mb-4 bg-white">
        <div class="card-body p-3">
            <form action="<?php echo site_url('admin/customers'); ?>" method="GET" class="row g-2 align-items-center">
                <div class="col-md-6 col-12">
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0 text-muted"><i class="fa-solid fa-magnifying-glass"></i></span>
                        <input type="text" name="search" class="form-control bg-light border-start-0 ps-0" placeholder="Search by Mobile number, Name, or Email..." value="<?php echo htmlspecialchars($search ?? ''); ?>">
                    </div>
                </div>
                <div class="col-md-4 col-12 d-flex gap-2">
                    <button type="submit" class="btn btn-primary fw-bold px-4">
                        <i class="fa-solid fa-filter me-1"></i> Filter
                    </button>
                    <?php if (!empty($search)): ?>
                        <a href="<?php echo site_url('admin/customers'); ?>" class="btn btn-outline-secondary fw-medium">
                            <i class="fa-solid fa-xmark me-1"></i> Clear
                        </a>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    </div>

    <!-- Customers Table Card -->
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead style="background: #f8fafc; font-size: 12px; color: #475569; text-transform: uppercase; letter-spacing: 0.5px;">
                        <tr>
                            <th class="ps-4" style="width: 70px;">ID</th>
                            <th>Customer Name</th>
                            <th>Mobile Number (OTP)</th>
                            <th>Email Address</th>
                            <th>Status</th>
                            <th>Registered On</th>
                            <th class="text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody style="font-size: 13.5px;">
                        <?php if (!empty($customers)): foreach ($customers as $u): 
                            $fullName = trim(($u['first_name'] ?? '') . ' ' . ($u['last_name'] ?? ''));
                            $initial = !empty($u['first_name']) ? strtoupper(substr($u['first_name'], 0, 1)) : 'U';
                            $isActive = ($u['status'] ?? 'active') === 'active';
                        ?>
                            <tr>
                                <td class="ps-4 fw-bold text-muted">
                                    #<?php echo $u['id']; ?>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold" style="width: 38px; height: 38px; background: linear-gradient(135deg, #09204b 0%, #0d3470 100%); font-size: 14px; flex-shrink: 0;">
                                            <?php echo $initial; ?>
                                        </div>
                                        <div>
                                            <?php if (!empty($fullName)): ?>
                                                <div class="fw-bold text-dark"><?php echo htmlspecialchars($fullName); ?></div>
                                            <?php else: ?>
                                                <div class="fw-bold text-secondary fst-italic">Name Not Set</div>
                                            <?php endif; ?>
                                            <span class="badge bg-light text-muted border small" style="font-size: 10px;">ID: <?php echo $u['id']; ?></span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="fw-bold text-dark d-flex align-items-center gap-2">
                                        <span><?php echo htmlspecialchars($u['phone']); ?></span>
                                        <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill" style="font-size: 10.5px; font-weight: 700;">
                                            <i class="fa-solid fa-shield-check"></i> OTP Verified
                                        </span>
                                    </div>
                                </td>
                                <td>
                                    <?php if (!empty($u['email'])): ?>
                                        <div class="text-dark fw-medium"><?php echo htmlspecialchars($u['email']); ?></div>
                                    <?php else: ?>
                                        <span class="badge bg-light text-secondary border small">Not Provided</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($isActive): ?>
                                        <span class="badge bg-success text-white px-2 py-1 rounded-pill" style="font-size: 11px;">
                                            <i class="fa-solid fa-circle me-1" style="font-size: 7px;"></i> Active
                                        </span>
                                    <?php else: ?>
                                        <span class="badge bg-danger text-white px-2 py-1 rounded-pill" style="font-size: 11px;">
                                            <i class="fa-solid fa-circle me-1" style="font-size: 7px;"></i> Inactive
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="text-dark fw-medium"><?php echo !empty($u['created_at']) ? date('d M Y, h:i A', strtotime($u['created_at'])) : '-'; ?></div>
                                </td>
                                <td class="text-end pe-4">
                                    <div class="btn-group btn-group-sm">
                                        <!-- Toggle Active/Inactive -->
                                        <a href="<?php echo site_url('admin/customers/toggle/' . $u['id']); ?>" 
                                           class="btn <?php echo $isActive ? 'btn-outline-warning' : 'btn-outline-success'; ?>" 
                                           title="<?php echo $isActive ? 'Deactivate Customer' : 'Activate Customer'; ?>"
                                           onclick="return confirm('Change status for this customer?');">
                                            <i class="fa-solid <?php echo $isActive ? 'fa-ban' : 'fa-check'; ?>"></i>
                                            <span class="d-none d-md-inline ms-1"><?php echo $isActive ? 'Deactivate' : 'Activate'; ?></span>
                                        </a>

                                        <!-- Delete Customer -->
                                        <a href="<?php echo site_url('admin/customers/delete/' . $u['id']); ?>" 
                                           class="btn btn-outline-danger" 
                                           title="Delete Customer"
                                           onclick="return confirm('Are you sure you want to delete this customer record?');">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; else: ?>
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <div style="width: 64px; height: 64px; border-radius: 50%; background: #f1f5f9; color: #94a3b8; display: inline-flex; align-items: center; justify-content: center; font-size: 28px; margin-bottom: 12px;">
                                        <i class="fa-solid fa-users-slash"></i>
                                    </div>
                                    <h5 class="fw-bold text-dark mb-1">No Customers Found</h5>
                                    <p class="text-muted small mb-0">No customer records matched your query or have registered via OTP yet.</p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
