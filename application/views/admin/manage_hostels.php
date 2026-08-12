<!-- Manage Hostels Content -->
<div class="p-4 p-md-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold fs-4 mb-1 text-dark">Manage Hostels</h3>
            <p class="text-secondary mb-0 fs-6">View and manage all registered hostels in the system.</p>
        </div>
        <div>
            <button type="button" class="btn btn-primary d-inline-flex align-items-center gap-2 px-4 shadow-sm" data-bs-toggle="modal" data-bs-target="#addHostelModal">
                <i class="fa-solid fa-plus"></i> Add Hostel
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
                            <th class="text-secondary fw-semibold border-0">Hostel Name</th>
                            <th class="text-secondary fw-semibold border-0">Hostel Address</th>
                            <th class="text-secondary fw-semibold border-0 rounded-end text-end px-3" style="width: 150px;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(!empty($hostels)): ?>
                            <?php $sn = 1; foreach($hostels as $hostel): ?>
                            <tr>
                                <td class="fw-medium px-3 text-secondary"><?= sprintf("%02d", $sn++) ?></td>
                                <td class="fw-bold text-dark">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="bg-primary bg-opacity-10 text-primary rounded d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                            <i class="fa-solid fa-building"></i>
                                        </div>
                                        <?= html_escape($hostel['hostel_name']) ?>
                                    </div>
                                </td>
                                <td class="text-secondary"><?= html_escape($hostel['hostel_address']) ?></td>
                                <td class="text-end px-3">
                                    <button class="btn btn-sm btn-outline-secondary border-0 text-dark hover-opacity me-1" title="View" data-bs-toggle="modal" data-bs-target="#viewHostelModal_<?= $hostel['id'] ?>"><i class="fa-solid fa-eye"></i></button>
                                    <button class="btn btn-sm btn-outline-primary border-0 me-1" title="Edit" data-bs-toggle="modal" data-bs-target="#editHostelModal_<?= $hostel['id'] ?>"><i class="fa-solid fa-pen-to-square"></i></button>
                                    <a href="<?= base_url('admin/delete_hostel/'.$hostel['id']) ?>" class="btn btn-sm btn-outline-danger border-0" title="Delete" onclick="return confirm('Are you sure you want to delete this hostel? This action cannot be undone.');"><i class="fa-solid fa-trash"></i></a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="text-center text-secondary py-4">No hostels found</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination (Mock) -->
            <div class="d-flex justify-content-between align-items-center mt-4">
                <span class="text-secondary fs-7">Showing 1 to 3 of 12 entries</span>
                <nav>
                    <ul class="pagination pagination-sm mb-0">
                        <li class="page-item disabled"><a class="page-link shadow-none" href="#">Previous</a></li>
                        <li class="page-item active"><a class="page-link shadow-none bg-primary border-primary" href="#">1</a></li>
                        <li class="page-item"><a class="page-link shadow-none text-dark" href="#">2</a></li>
                        <li class="page-item"><a class="page-link shadow-none text-dark" href="#">3</a></li>
                        <li class="page-item"><a class="page-link shadow-none text-dark" href="#">Next</a></li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</div>

<!-- Add Hostel Modal -->
<div class="modal fade" id="addHostelModal" tabindex="-1" aria-labelledby="addHostelModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-light border-0">
                <h5 class="modal-title fw-bold text-dark" id="addHostelModalLabel">Add New Hostel</h5>
                <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= base_url('admin/add_hostel') ?>" method="POST">
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label for="hostelName" class="form-label fw-semibold text-secondary small">Hostel Name</label>
                        <input type="text" class="form-control shadow-none" id="hostelName" name="hostel_name" placeholder="Enter hostel name" required>
                    </div>
                    <div class="mb-3">
                        <label for="hostelAddress" class="form-label fw-semibold text-secondary small">Hostel Address</label>
                        <textarea class="form-control shadow-none" id="hostelAddress" name="hostel_address" rows="3" placeholder="Enter complete address" required></textarea>
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
<?php if(!empty($hostels)): ?>
    <?php foreach($hostels as $hostel): ?>
        
        <!-- View Hostel Modal -->
        <div class="modal fade" id="viewHostelModal_<?= $hostel['id'] ?>" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow">
                    <div class="modal-header bg-light border-0">
                        <h5 class="modal-title fw-bold text-dark">Hostel Details</h5>
                        <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <h6 class="text-secondary small fw-semibold mb-1">Hostel Name</h6>
                            <p class="text-dark fw-medium mb-0"><?= html_escape($hostel['hostel_name']) ?></p>
                        </div>
                        <div class="mb-3">
                            <h6 class="text-secondary small fw-semibold mb-1">Hostel Address</h6>
                            <p class="text-dark fw-medium mb-0"><?= nl2br(html_escape($hostel['hostel_address'])) ?></p>
                        </div>
                        <div>
                            <h6 class="text-secondary small fw-semibold mb-1">Added On</h6>
                            <p class="text-dark fw-medium mb-0"><?= date('F j, Y, g:i a', strtotime($hostel['created_at'])) ?></p>
                        </div>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-light border px-4 shadow-sm" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Hostel Modal -->
        <div class="modal fade" id="editHostelModal_<?= $hostel['id'] ?>" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow">
                    <div class="modal-header bg-light border-0">
                        <h5 class="modal-title fw-bold text-dark">Edit Hostel</h5>
                        <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="<?= base_url('admin/edit_hostel') ?>" method="POST">
                        <div class="modal-body p-4">
                            <input type="hidden" name="id" value="<?= $hostel['id'] ?>">
                            <div class="mb-3">
                                <label class="form-label fw-semibold text-secondary small">Hostel Name</label>
                                <input type="text" class="form-control shadow-none" name="hostel_name" value="<?= html_escape($hostel['hostel_name']) ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold text-secondary small">Hostel Address</label>
                                <textarea class="form-control shadow-none" name="hostel_address" rows="3" required><?= html_escape($hostel['hostel_address']) ?></textarea>
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
