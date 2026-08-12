<div class="container-fluid p-4">

    <!-- Flash Success/Error Message -->
    <?php if ($this->session->flashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fa-solid fa-circle-check me-2"></i> <?php echo $this->session->flashdata('success'); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-dark mb-1">Super Admin Dashboard</h3>
            <p class="text-muted small mb-0">Overview of flight tickets, hotel reservations, revenue, and system status</p>
        </div>
        <div class="d-flex gap-2">
            <a href="<?php echo site_url('admin/flight_bookings'); ?>" class="btn btn-primary btn-sm"><i class="fa-solid fa-plane me-1"></i> Manage Flights</a>
            <a href="<?php echo site_url('admin/hotel_bookings'); ?>" class="btn btn-danger btn-sm"><i class="fa-solid fa-hotel me-1"></i> Manage Hotels</a>
        </div>
    </div>

    <!-- Stat Cards Grid -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="text-muted small fw-bold">Total Revenue</span>
                        <h3 class="fw-bold text-success mb-0 mt-1">₹ <?php echo number_format($stats['total_revenue'], 2); ?></h3>
                    </div>
                    <div class="bg-success-subtle text-success p-3 rounded-circle fs-4">
                        <i class="fa-solid fa-indian-rupee-sign"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="text-muted small fw-bold">Flight Bookings</span>
                        <h3 class="fw-bold text-primary mb-0 mt-1"><?php echo $stats['flight_bookings']; ?></h3>
                    </div>
                    <div class="bg-primary-subtle text-primary p-3 rounded-circle fs-4">
                        <i class="fa-solid fa-plane-up"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="text-muted small fw-bold">Hotel Bookings</span>
                        <h3 class="fw-bold text-danger mb-0 mt-1"><?php echo $stats['hotel_bookings']; ?></h3>
                    </div>
                    <div class="bg-danger-subtle text-danger p-3 rounded-circle fs-4">
                        <i class="fa-solid fa-hotel"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="text-muted small fw-bold">Customer Enquiries</span>
                        <h3 class="fw-bold text-warning mb-0 mt-1"><?php echo $stats['enquiries']; ?></h3>
                    </div>
                    <div class="bg-warning-subtle text-warning p-3 rounded-circle fs-4">
                        <i class="fa-solid fa-comments"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Tables Grid -->
    <div class="row g-4">
        <!-- Recent Flight Bookings -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold text-dark mb-0"><i class="fa-solid fa-plane text-primary me-2"></i> Recent Flight Bookings</h5>
                    <a href="<?php echo site_url('admin/flight_bookings'); ?>" class="btn btn-link btn-sm text-decoration-none">View All &rarr;</a>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light small">
                            <tr>
                                <th>Booking Ref</th>
                                <th>Customer</th>
                                <th>Route</th>
                                <th>Amount</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($recent_flights)): foreach ($recent_flights as $fb): ?>
                            <tr>
                                <td><a href="<?php echo site_url('welcome/flight_confirmation/' . $fb['booking_ref']); ?>" target="_blank" class="fw-bold text-primary text-decoration-none"><?php echo htmlspecialchars($fb['booking_ref']); ?></a></td>
                                <td><?php echo htmlspecialchars($fb['contact_name']); ?></td>
                                <td><span class="badge bg-secondary"><?php echo htmlspecialchars($fb['origin']); ?> &rarr; <?php echo htmlspecialchars($fb['destination']); ?></span></td>
                                <td class="fw-bold">₹ <?php echo number_format($fb['total_amount']); ?></td>
                                <td><span class="badge bg-success"><?php echo htmlspecialchars($fb['booking_status']); ?></span></td>
                            </tr>
                            <?php endforeach; else: ?>
                            <tr>
                                <td colspan="5" class="text-center text-muted py-3">No flight bookings recorded yet.</td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Recent Hotel Bookings -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold text-dark mb-0"><i class="fa-solid fa-hotel text-danger me-2"></i> Recent Hotel Bookings</h5>
                    <a href="<?php echo site_url('admin/hotel_bookings'); ?>" class="btn btn-link btn-sm text-decoration-none">View All &rarr;</a>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light small">
                            <tr>
                                <th>Voucher ID</th>
                                <th>Guest</th>
                                <th>Hotel</th>
                                <th>Amount</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($recent_hotels)): foreach ($recent_hotels as $hb): ?>
                            <tr>
                                <td><a href="<?php echo site_url('welcome/hotel_confirmation/' . $hb['booking_ref']); ?>" target="_blank" class="fw-bold text-danger text-decoration-none"><?php echo htmlspecialchars($hb['booking_ref']); ?></a></td>
                                <td><?php echo htmlspecialchars($hb['primary_guest_name']); ?></td>
                                <td><span class="fw-medium text-dark"><?php echo htmlspecialchars($hb['hotel_name']); ?></span></td>
                                <td class="fw-bold">₹ <?php echo number_format($hb['total_amount']); ?></td>
                                <td><span class="badge bg-success"><?php echo htmlspecialchars($hb['booking_status']); ?></span></td>
                            </tr>
                            <?php endforeach; else: ?>
                            <tr>
                                <td colspan="5" class="text-center text-muted py-3">No hotel bookings recorded yet.</td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>
