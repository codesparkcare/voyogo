<div class="container-fluid p-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-dark mb-1"><i class="fa-solid fa-envelope-open-text text-warning me-2"></i> Customer Enquiries</h3>
            <p class="text-muted small mb-0">List of customer inquiries and contact messages submitted through the website</p>
        </div>
    </div>

    <!-- Enquiries Table Card -->
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Date</th>
                            <th>Customer Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Message</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($enquiries)): foreach ($enquiries as $e): ?>
                        <tr>
                            <td><small class="text-muted"><?php echo date('d M Y, H:i', strtotime($e['created_at'])); ?></small></td>
                            <td class="fw-bold text-dark"><?php echo htmlspecialchars($e['name']); ?></td>
                            <td><a href="mailto:<?php echo htmlspecialchars($e['email']); ?>"><?php echo htmlspecialchars($e['email']); ?></a></td>
                            <td><?php echo htmlspecialchars($e['phone']); ?></td>
                            <td class="text-muted"><?php echo nl2br(htmlspecialchars($e['message'])); ?></td>
                        </tr>
                        <?php endforeach; else: ?>
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">No customer enquiries received yet.</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
