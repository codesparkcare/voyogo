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
                        <?php if (!empty($bookings)): foreach ($bookings as $b): 
                            $ref = $b['booking_reference'] ?? ($b['booking_ref'] ?? ('VOY-HTL-' . $b['id']));
                            $guestName = $b['lead_guest_name'] ?? ($b['primary_guest_name'] ?? 'Guest');
                            $guestEmail = $b['lead_guest_email'] ?? ($b['guest_email'] ?? '');
                            $guestPhone = $b['lead_guest_phone'] ?? ($b['guest_phone'] ?? '');
                            $roomsCount = $b['rooms_count'] ?? 1;
                            $guestsCount = isset($b['guests_count']) ? $b['guests_count'] : (($b['adults_count'] ?? 2) + ($b['children_count'] ?? 0));
                            $bStatus = ucfirst(strtolower($b['booking_status'] ?? 'Confirmed'));
                            $pStatus = ucfirst(strtolower($b['payment_status'] ?? 'Paid'));
                        ?>
                        <tr>
                            <td>
                                <strong class="text-danger"><?php echo htmlspecialchars($ref); ?></strong>
                                <div class="text-muted small" style="font-size: 11px;"><?php echo date('d M Y, H:i', strtotime($b['created_at'])); ?></div>
                            </td>
                            <td>
                                <div class="fw-bold text-dark"><?php echo htmlspecialchars($b['hotel_name'] ?? 'Hotel'); ?></div>
                                <div class="text-muted small"><?php echo htmlspecialchars($b['hotel_address'] ?? ($b['destination_city'] ?? '')); ?></div>
                            </td>
                            <td>
                                <div class="fw-bold"><?php echo htmlspecialchars($guestName); ?></div>
                                <div class="text-muted small"><?php echo htmlspecialchars($guestEmail); ?> | <?php echo htmlspecialchars($guestPhone); ?></div>
                            </td>
                            <td>
                                <div class="fw-bold text-primary"><?php echo htmlspecialchars($b['room_type'] ?? 'Room'); ?></div>
                                <span class="badge bg-light text-dark border"><?php echo date('d M Y', strtotime($b['checkin_date'])); ?> &rarr; <?php echo date('d M Y', strtotime($b['checkout_date'])); ?></span>
                            </td>
                            <td><?php echo $roomsCount; ?> Room, <?php echo $guestsCount; ?> Guests</td>
                            <td class="fw-bold text-success">₹ <?php echo number_format($b['total_amount']); ?></td>
                            <td><small class="text-muted font-monospace"><?php echo htmlspecialchars($b['supplier_reference'] ?? ($b['payment_id'] ?? '-')); ?></small></td>
                            <td>
                                <span class="badge <?php echo ($bStatus == 'Confirmed') ? 'bg-success' : (($bStatus == 'Cancelled') ? 'bg-danger' : 'bg-warning'); ?>">
                                    <?php echo htmlspecialchars($bStatus); ?>
                                </span>
                                <?php if (!empty($b['cancellation_id'])): ?>
                                    <div class="text-muted small font-monospace" style="font-size: 10px;">Canc ID: <?php echo htmlspecialchars($b['cancellation_id']); ?></div>
                                <?php endif; ?>
                            </td>
                            <td class="text-end">
                                <div class="btn-group">
                                    <a href="<?php echo site_url('hotels/confirmation/' . $ref); ?>" target="_blank" class="btn btn-sm btn-outline-danger" title="Print Voucher">
                                        <i class="fa-solid fa-print"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="retrieveHotelBooking(<?php echo $b['id']; ?>, '<?php echo htmlspecialchars($ref, ENT_QUOTES); ?>')" title="Retrieve live status from Akbar/Benzy API">
                                        <i class="fa-solid fa-rotate"></i> Retrieve
                                    </button>
                                    <?php if ($bStatus !== 'Cancelled'): ?>
                                    <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#cancelHotelModal<?php echo $b['id']; ?>" title="Cancel Booking via Akbar/Benzy API">
                                        <i class="fa-solid fa-ban"></i> Cancel
                                    </button>
                                    <?php endif; ?>
                                    <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#editHotelModal<?php echo $b['id']; ?>" title="Manual Status Edit">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </button>
                                </div>

                                <!-- Benzy API Cancel Confirmation Modal -->
                                <?php if ($bStatus !== 'Cancelled'): ?>
                                <div class="modal fade text-start" id="cancelHotelModal<?php echo $b['id']; ?>" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content border-0 shadow">
                                            <form action="<?php echo site_url('admin/hotel_cancel_booking'); ?>" method="POST">
                                                <input type="hidden" name="booking_id" value="<?php echo $b['id']; ?>">
                                                <div class="modal-header bg-danger text-white">
                                                    <h5 class="modal-title fw-bold">
                                                        <i class="fa-solid fa-triangle-exclamation me-2"></i> Cancel Booking with Akbar/Benzy API
                                                    </h5>
                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p class="mb-3">
                                                        Are you sure you want to cancel booking <strong>#<?php echo htmlspecialchars($ref); ?></strong>?
                                                    </p>
                                                    <div class="alert alert-warning small mb-3">
                                                        <i class="fa-solid fa-circle-info me-1"></i>
                                                        This will send a live request to <code>{HotelItineraryURL}/Hotel/CancelHotelBooking</code> with Transaction ID <strong><?php echo htmlspecialchars($b['transaction_id'] ?? $b['supplier_reference']); ?></strong>. The FinYearID will be automatically resolved via RetrieveBooking.
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label fw-bold small">Cancellation Remarks (Required by Benzy API)</label>
                                                        <input type="text" name="remarks" class="form-control" value="Customer Request" required placeholder="Reason for cancellation">
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Keep Booking</button>
                                                    <button type="submit" class="btn btn-danger fw-bold">
                                                        <i class="fa-solid fa-ban me-1"></i> Confirm Cancellation with Supplier
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <?php endif; ?>

                                <!-- Status Update Modal (Manual) -->
                                <div class="modal fade text-start" id="editHotelModal<?php echo $b['id']; ?>" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content border-0 shadow">
                                            <form action="<?php echo site_url('admin/update_hotel_status'); ?>" method="POST">
                                                <input type="hidden" name="booking_id" value="<?php echo $b['id']; ?>">
                                                <div class="modal-header">
                                                    <h5 class="modal-title fw-bold">Update Hotel Booking #<?php echo htmlspecialchars($b['booking_ref'] ?? $ref); ?></h5>
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

<!-- Modal for Viewing Live RetrieveBooking API Response -->
<div class="modal fade" id="retrieveBookingModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title fw-bold" id="retrieveModalTitle">
                    <i class="fa-solid fa-cloud-arrow-down me-2"></i> Live RetrieveBooking API Response
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="retrieveModalBody">
                <div class="text-center py-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <div class="mt-2 text-muted small">Querying Akbar / Benzy RetrieveBooking API...</div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
function retrieveHotelBooking(bookingId, ref) {
    const modalEl = document.getElementById('retrieveBookingModal');
    const modal = new bootstrap.Modal(modalEl);
    document.getElementById('retrieveModalTitle').innerHTML = '<i class="fa-solid fa-cloud-arrow-down me-2"></i> Live RetrieveBooking - #' + ref;
    document.getElementById('retrieveModalBody').innerHTML = `
        <div class="text-center py-4">
            <div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>
            <div class="mt-2 text-muted small">Querying Akbar / Benzy RetrieveBooking API ({HotelBookingURL}/Utils/RetrieveBooking)...</div>
        </div>
    `;
    modal.show();

    fetch('<?php echo site_url("admin/hotel_retrieve_booking"); ?>/' + bookingId, {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(r => r.json())
    .then(data => {
        if (data.status === 'success' && data.api_data) {
            const d = data.api_data;
            let html = `
                <div class="row g-3 mb-3">
                    <div class="col-md-4">
                        <div class="p-2 border rounded bg-light">
                            <small class="text-muted d-block">Booking Status</small>
                            <span class="badge ${d.BookingStatus === 'B0' || d.BookingStatus === 'Confirmed' ? 'bg-success' : 'bg-warning text-dark'} fs-6">${d.BookingStatus || d.CurrentStatus || 'N/A'}</span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="p-2 border rounded bg-light">
                            <small class="text-muted d-block">FinYearID (YearType)</small>
                            <span class="fw-bold font-monospace text-primary">${d.FinYearID || '19'}</span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="p-2 border rounded bg-light">
                            <small class="text-muted d-block">Supplier Confirmation ID</small>
                            <span class="fw-bold font-monospace">${d.BookingConfirmationId || d.HotelConfirmationNumber || 'Pending'}</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-2 border rounded bg-light">
                            <small class="text-muted d-block">Transaction ID</small>
                            <span class="font-monospace">${d.TransactionId || d.TransactionID || 'N/A'}</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-2 border rounded bg-light">
                            <small class="text-muted d-block">TUI</small>
                            <span class="font-monospace small text-truncate d-block">${d.TUI || 'N/A'}</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-2 border rounded bg-light">
                            <small class="text-muted d-block">Gross / Net Fare</small>
                            <strong>₹ ${d.GrossFare || 0}</strong> <span class="text-muted small">(Net: ₹ ${d.NetFare || 0})</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-2 border rounded bg-light">
                            <small class="text-muted d-block">Check-In & Check-Out</small>
                            <span>${d.CheckInDate || 'N/A'} &rarr; ${d.CheckOutDate || 'N/A'}</span>
                        </div>
                    </div>
                </div>
                <h6 class="fw-bold mt-3 mb-2 text-secondary">Raw Supplier JSON Response:</h6>
                <pre class="p-3 bg-dark text-white rounded small" style="max-height: 250px; overflow-y: auto;"><code>${JSON.stringify(d, null, 2)}</code></pre>
            `;
            document.getElementById('retrieveModalBody').innerHTML = html;
        } else {
            document.getElementById('retrieveModalBody').innerHTML = `
                <div class="alert alert-danger">
                    <i class="fa-solid fa-triangle-exclamation me-2"></i>
                    ${data.message || (data.api_data ? JSON.stringify(data.api_data) : 'Failed to retrieve booking from supplier.')}
                </div>
                ${data.raw ? `<pre class="p-3 bg-light border rounded small mt-2"><code>${data.raw}</code></pre>` : ''}
            `;
        }
    })
    .catch(err => {
        document.getElementById('retrieveModalBody').innerHTML = `
            <div class="alert alert-danger">
                <i class="fa-solid fa-triangle-exclamation me-2"></i> Network error while contacting server: ${err.message}
            </div>
        `;
    });
}
</script>
