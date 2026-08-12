<!-- Manage Staff Content -->
<div class="p-4 p-md-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold fs-4 mb-1 text-dark">Manage Staff</h3>
            <p class="text-secondary mb-0 fs-6">View and manage all registered staff members in the system.</p>
        </div>
        <div>
            <button type="button" class="btn btn-primary d-inline-flex align-items-center gap-2 px-4 shadow-sm" data-bs-toggle="modal" data-bs-target="#addStaffModal">
                <i class="fa-solid fa-plus"></i> Add Staff
            </button>
        </div>
    </div>




    <!-- Data Table Card -->
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-4">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="text-secondary fw-semibold border-0 rounded-start px-3" style="width: 80px;">S.No</th>
                            <th class="text-secondary fw-semibold border-0">Staff Name</th>
                            <th class="text-secondary fw-semibold border-0">Email</th>
                            <th class="text-secondary fw-semibold border-0">Phone</th>
                            <th class="text-secondary fw-semibold border-0">Address</th>
                            <th class="text-secondary fw-semibold border-0 rounded-end text-end px-3" style="width: 150px;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(!empty($staff)): ?>
                            <?php $sn = 1; foreach($staff as $member): ?>
                            <tr>
                                <td class="fw-medium px-3 text-secondary"><?= sprintf("%02d", $sn++) ?></td>
                                <td class="fw-bold text-dark">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="bg-primary bg-opacity-10 text-primary rounded d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                            <i class="fa-solid fa-user-tie"></i>
                                        </div>
                                        <?= html_escape($member['staff_name']) ?>
                                    </div>
                                </td>
                                <td class="text-secondary"><?= html_escape($member['email']) ?></td>
                                <td class="text-secondary"><?= html_escape($member['phone']) ?></td>
                                <td class="text-secondary"><?= html_escape($member['address']) ?></td>
                                <td class="text-end px-3">
                                    <button class="btn btn-sm btn-outline-secondary border-0 text-dark hover-opacity me-1" title="View" data-bs-toggle="modal" data-bs-target="#viewStaffModal_<?= $member['id'] ?>"><i class="fa-solid fa-eye"></i></button>
                                    <button class="btn btn-sm btn-outline-primary border-0 me-1" title="Edit" data-bs-toggle="modal" data-bs-target="#editStaffModal_<?= $member['id'] ?>"><i class="fa-solid fa-pen-to-square"></i></button>
                                    <a href="<?= base_url('admin/delete_staff/'.$member['id']) ?>" class="btn btn-sm btn-outline-danger border-0" title="Delete" onclick="return confirm('Are you sure you want to delete this staff member? This action cannot be undone.');"><i class="fa-solid fa-trash"></i></a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center text-secondary py-4">No staff members found</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination (Mock) -->
            <div class="d-flex justify-content-between align-items-center mt-4">
                <span class="text-secondary fs-7">Showing 1 to <?= count($staff) ?> of <?= count($staff) ?> entries</span>
                <nav>
                    <ul class="pagination pagination-sm mb-0">
                        <li class="page-item disabled"><a class="page-link shadow-none" href="#">Previous</a></li>
                        <li class="page-item active"><a class="page-link shadow-none bg-primary border-primary" href="#">1</a></li>
                        <li class="page-item disabled"><a class="page-link shadow-none text-dark" href="#">Next</a></li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</div>

<!-- Add Staff Modal -->
<div class="modal fade" id="addStaffModal" tabindex="-1" aria-labelledby="addStaffModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-light border-0">
                <h5 class="modal-title fw-bold text-dark" id="addStaffModalLabel">Add New Staff</h5>
                <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= base_url('admin/add_staff') ?>" method="POST">
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-secondary small">Staff Name</label>
                        <input type="text" class="form-control shadow-none" name="staff_name" placeholder="Enter full name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-secondary small">Email</label>
                        <input type="email" class="form-control shadow-none" name="email" placeholder="Enter email address" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-secondary small">Phone Number</label>
                        <input type="text" class="form-control shadow-none" name="phone" placeholder="Enter phone number" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-secondary small">Address</label>
                        <textarea class="form-control shadow-none" name="address" rows="3" placeholder="Enter home address" required></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 bg-light">
                    <button type="button" class="btn btn-light border px-4 shadow-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary px-4 shadow-sm">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Success Modal -->
<?php if($this->session->flashdata('success')): ?>
<div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content border-0 shadow text-center rounded-4">
            <div class="modal-body p-4 p-md-5">
                <div class="text-success mb-3">
                    <i class="fa-solid fa-circle-check" style="font-size: 4rem;"></i>
                </div>
                <h5 class="fw-bold mb-3 text-dark">Success!</h5>
                <p class="text-secondary mb-4"><?= $this->session->flashdata('success'); ?></p>
                <button type="button" class="btn btn-primary px-4 shadow-sm w-100 rounded-3" data-bs-dismiss="modal">Continue</button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        var successModal = new bootstrap.Modal(document.getElementById('successModal'));
        successModal.show();
    });
</script>
<?php endif; ?>

<!-- Dynamic View & Edit Modals -->
<?php if(!empty($staff)): ?>
    <?php foreach($staff as $member): ?>
        
        <!-- View Staff Modal -->
        <div class="modal fade" id="viewStaffModal_<?= $member['id'] ?>" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow">
                    <div class="modal-header bg-light border-0">
                        <h5 class="modal-title fw-bold text-dark">Staff Details</h5>
                        <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <h6 class="text-secondary small fw-semibold mb-1">Staff Name</h6>
                            <p class="text-dark fw-medium mb-0"><?= html_escape($member['staff_name']) ?></p>
                        </div>
                        <div class="mb-3">
                            <h6 class="text-secondary small fw-semibold mb-1">Email</h6>
                            <p class="text-dark fw-medium mb-0"><?= html_escape($member['email']) ?></p>
                        </div>
                        <div class="mb-3">
                            <h6 class="text-secondary small fw-semibold mb-1">Phone Number</h6>
                            <p class="text-dark fw-medium mb-0"><?= html_escape($member['phone']) ?></p>
                        </div>
                        <div class="mb-3">
                            <h6 class="text-secondary small fw-semibold mb-1">Address</h6>
                            <p class="text-dark fw-medium mb-0"><?= nl2br(html_escape($member['address'])) ?></p>
                        </div>
                        <div>
                            <h6 class="text-secondary small fw-semibold mb-1">Registered On</h6>
                            <p class="text-dark fw-medium mb-0"><?= date('F j, Y, g:i a', strtotime($member['created_at'])) ?></p>
                        </div>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-light border px-4 shadow-sm" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Staff Modal -->
        <div class="modal fade" id="editStaffModal_<?= $member['id'] ?>" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow">
                    <div class="modal-header bg-light border-0">
                        <h5 class="modal-title fw-bold text-dark">Edit Staff</h5>
                        <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="<?= base_url('admin/edit_staff') ?>" method="POST">
                        <div class="modal-body p-4">
                            <input type="hidden" name="id" value="<?= $member['id'] ?>">
                            <div class="mb-3">
                                <label class="form-label fw-semibold text-secondary small">Staff Name</label>
                                <input type="text" class="form-control shadow-none" name="staff_name" value="<?= html_escape($member['staff_name']) ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold text-secondary small">Email</label>
                                <input type="email" class="form-control shadow-none" name="email" value="<?= html_escape($member['email']) ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold text-secondary small">Phone Number</label>
                                <input type="text" class="form-control shadow-none" name="phone" value="<?= html_escape($member['phone']) ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold text-secondary small">Address</label>
                                <textarea class="form-control shadow-none" name="address" rows="3" required><?= html_escape($member['address']) ?></textarea>
                            </div>
                        </div>
                        <div class="modal-footer border-0 bg-light">
                            <button type="button" class="btn btn-light border px-4 shadow-sm" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary px-4 shadow-sm">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    <?php endforeach; ?>
<?php endif; ?>
