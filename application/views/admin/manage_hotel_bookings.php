<div class="container-fluid p-4">

    <!-- Flash Messages -->
    <?php if ($this->session->flashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fa-solid fa-circle-check me-2"></i> <?php echo $this->session->flashdata('success'); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-dark mb-1"><i class="fa-solid fa-hotel text-danger me-2"></i> Manage Hotel Bookings</h3>
            <p class="text-muted small mb-0">View all customer hotel vouchers, edit booking statuses, and print hotel vouchers</p>
        </div>
    </div>

    <!-- Bookings Table Card -->
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Voucher Ref</th>
                            <th>Hotel Name</th>
                            <th>Primary Guest</th>
                            <th>Room & Stay Dates</th>
                            <th>Guests</th>
                            <th>Total Fare</th>
                            <th>Payment ID</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($bookings)): foreach ($bookings as $b): ?>
                        <tr>
                            <td>
                                <strong class="text-danger"><?php echo htmlspecialchars($b['booking_ref']); ?></strong>
                                <div class="text-muted small" style="font-size: 11px;"><?php echo date('d M Y, H:i', strtotime($b['created_at'])); ?></div>
                            </td>
                            <td>
                                <div class="fw-bold text-dark"><?php echo htmlspecialchars($b['hotel_name']); ?></div>
                                <div class="text-muted small"><?php echo htmlspecialchars($b['hotel_address']); ?></div>
                            </td>
                            <td>
                                <div class="fw-bold"><?php echo htmlspecialchars($b['primary_guest_name']); ?></div>
                                <div class="text-muted small"><?php echo htmlspecialchars($b['guest_email']); ?> | <?php echo htmlspecialchars($b['guest_phone']); ?></div>
                            </td>
                            <td>
                                <div class="fw-bold text-primary"><?php echo htmlspecialchars($b['room_type']); ?></div>
                                <span class="badge bg-light text-dark border"><?php echo date('d M Y', strtotime($b['checkin_date'])); ?> &rarr; <?php echo date('d M Y', strtotime($b['checkout_date'])); ?></span>
                            </td>
                            <td><?php echo $b['rooms_count']; ?> Room, <?php echo $b['guests_count']; ?> Guests</td>
                            <td class="fw-bold text-success">₹ <?php echo number_format($b['total_amount']); ?></td>
                            <td><small class="text-muted font-monospace"><?php echo htmlspecialchars($b['payment_id']); ?></small></td>
                            <td>
                                <span class="badge <?php echo ($b['booking_status'] == 'Confirmed') ? 'bg-success' : (($b['booking_status'] == 'Cancelled') ? 'bg-danger' : 'bg-warning'); ?>">
                                    <?php echo htmlspecialchars($b['booking_status']); ?>
                                </span>
                            </td>
                            <td class="text-end">
                                <div class="btn-group">
                                    <a href="<?php echo site_url('welcome/hotel_confirmation/' . $b['booking_ref']); ?>" target="_blank" class="btn btn-sm btn-outline-danger" title="Print Voucher">
                                        <i class="fa-solid fa-print"></i> Voucher
                                    </a>
                                    <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#editHotelModal<?php echo $b['id']; ?>" title="Edit Status">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </button>
                                </div>

                                <!-- Status Update Modal -->
                                <div class="modal fade text-start" id="editHotelModal<?php echo $b['id']; ?>" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content border-0 shadow">
                                            <form action="<?php echo site_url('admin/update_hotel_status'); ?>" method="POST">
                                                <input type="hidden" name="booking_id" value="<?php echo $b['id']; ?>">
                                                <div class="modal-header">
                                                    <h5 class="modal-title fw-bold">Update Hotel Booking #<?php echo htmlspecialchars($b['booking_ref']); ?></h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label class="form-label fw-bold">Booking Status</label>
                                                        <select name="booking_status" class="form-select">
                                                            <option value="Confirmed" <?php echo ($b['booking_status'] == 'Confirmed') ? 'selected' : ''; ?>>Confirmed</option>
                                                            <option value="Pending" <?php echo ($b['booking_status'] == 'Pending') ? 'selected' : ''; ?>>Pending</option>
                                                            <option value="Cancelled" <?php echo ($b['booking_status'] == 'Cancelled') ? 'selected' : ''; ?>>Cancelled</option>
                                                        </select>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label fw-bold">Payment Status</label>
                                                        <select name="payment_status" class="form-select">
                                                            <option value="Paid" <?php echo ($b['payment_status'] == 'Paid') ? 'selected' : ''; ?>>Paid</option>
                                                            <option value="Pending" <?php echo ($b['payment_status'] == 'Pending') ? 'selected' : ''; ?>>Pending</option>
                                                            <option value="Refunded" <?php echo ($b['payment_status'] == 'Refunded') ? 'selected' : ''; ?>>Refunded</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-danger">Save Changes</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                            </td>
                        </tr>
                        <?php endforeach; else: ?>
                        <tr>
                            <td colspan="9" class="text-center text-muted py-4">No hotel bookings found in database.</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
