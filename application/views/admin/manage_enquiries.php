<div class="container-fluid p-4">

    <!-- Flash Alerts -->
    <?php if ($this->session->flashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-3 mb-4" role="alert">
            <i class="fa-solid fa-circle-check me-2"></i> <?php echo $this->session->flashdata('success'); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    <?php if ($this->session->flashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm rounded-3 mb-4" role="alert">
            <i class="fa-solid fa-circle-exclamation me-2"></i> <?php echo $this->session->flashdata('error'); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div>
            <h3 class="fw-bold text-dark mb-1"><i class="fa-solid fa-envelope-open-text text-warning me-2"></i> Customer Enquiries</h3>
            <p class="text-muted small mb-0">List of customer inquiries and contact messages submitted through the website</p>
        </div>
        <div>
            <a href="<?php echo site_url('admin/enquiries/clear_empty'); ?>" class="btn btn-outline-danger btn-sm rounded-pill px-3 shadow-sm" onclick="return confirm('Are you sure you want to remove all blank/bot spam entries?');">
                <i class="fa-solid fa-broom me-1"></i> Clean Blank / Bot Entries
            </a>
        </div>
    </div>

    <!-- Enquiries Table Card -->
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 140px;">Date</th>
                            <th>Customer Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Message</th>
                            <th style="width: 80px;" class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($enquiries)): foreach ($enquiries as $e): 
                            $isEmptyEntry = empty(trim((string)$e['phone'])) && empty(trim((string)$e['email'])) && empty(trim((string)$e['name']));
                        ?>
                        <tr class="<?php echo $isEmptyEntry ? 'table-light text-muted' : ''; ?>">
                            <td><small class="text-muted"><?php echo date('d M Y, H:i', strtotime($e['created_at'])); ?></small></td>
                            <td>
                                <?php if (!empty($e['name'])): ?>
                                    <span class="fw-bold text-dark"><?php echo htmlspecialchars($e['name']); ?></span>
                                <?php else: ?>
                                    <span class="badge bg-light text-secondary border">Empty / Bot Visit</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if (!empty($e['email'])): ?>
                                    <a href="mailto:<?php echo htmlspecialchars($e['email']); ?>"><?php echo htmlspecialchars($e['email']); ?></a>
                                <?php else: ?>
                                    <span class="text-muted">—</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if (!empty($e['phone'])): ?>
                                    <a href="tel:<?php echo htmlspecialchars($e['phone']); ?>" class="text-dark fw-semibold text-decoration-none"><?php echo htmlspecialchars($e['phone']); ?></a>
                                <?php else: ?>
                                    <span class="text-muted">—</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if (!empty($e['message'])): ?>
                                    <span class="text-secondary"><?php echo nl2br(htmlspecialchars($e['message'])); ?></span>
                                <?php else: ?>
                                    <span class="text-muted fst-italic">—</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <a href="<?php echo site_url('admin/enquiries/delete/' . $e['id']); ?>" class="btn btn-sm btn-outline-danger rounded-circle p-1" style="width: 28px; height: 28px; line-height: 1;" onclick="return confirm('Are you sure you want to delete this enquiry?');" title="Delete Enquiry">
                                    <i class="fa-solid fa-trash-can" style="font-size: 11px;"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; else: ?>
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">No customer enquiries received yet.</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
