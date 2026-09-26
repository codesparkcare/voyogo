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

    <!-- Page Header & Action Button -->
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <div>
            <h3 class="fw-bold text-dark mb-1 d-flex align-items-center gap-2">
                <i class="fa-solid fa-fire text-danger"></i> Exclusive Deals & Offers
            </h3>
            <p class="text-muted small mb-0">Manage promotional banners, coupon codes, and category tabs displayed on the homepage</p>
        </div>
        <div>
            <button type="button" class="btn btn-primary fw-bold px-3 py-2 rounded-3 shadow-sm d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#dealModal" onclick="resetDealForm()">
                <i class="fa-solid fa-plus"></i> Add New Deal
            </button>
        </div>
    </div>

    <!-- Quick Stats Cards & Category Filters -->
    <div class="row g-3 mb-4">
        <div class="col-md-2 col-sm-4 col-6">
            <a href="<?php echo site_url('admin/deals'); ?>" class="text-decoration-none">
                <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100 <?php echo empty($category_filter) ? 'border-primary border-2' : ''; ?>">
                    <div class="text-muted small fw-bold">ALL DEALS</div>
                    <h3 class="fw-black mb-0 text-dark"><?php echo number_format($total_count); ?></h3>
                </div>
            </a>
        </div>
        <div class="col-md-2 col-sm-4 col-6">
            <a href="<?php echo site_url('admin/deals?category=HOT+DEALS'); ?>" class="text-decoration-none">
                <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100 <?php echo ($category_filter === 'HOT DEALS') ? 'border-danger border-2' : ''; ?>">
                    <div class="text-danger small fw-bold"><i class="fa-solid fa-bolt me-1"></i> HOT DEALS</div>
                    <h3 class="fw-black mb-0 text-danger"><?php echo number_format($hot_deals_count); ?></h3>
                </div>
            </a>
        </div>
        <div class="col-md-2 col-sm-4 col-6">
            <a href="<?php echo site_url('admin/deals?category=FLIGHT'); ?>" class="text-decoration-none">
                <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100 <?php echo ($category_filter === 'FLIGHT') ? 'border-primary border-2' : ''; ?>">
                    <div class="text-primary small fw-bold"><i class="fa-solid fa-plane me-1"></i> FLIGHT</div>
                    <h3 class="fw-black mb-0 text-primary"><?php echo number_format($flight_count); ?></h3>
                </div>
            </a>
        </div>
        <div class="col-md-2 col-sm-4 col-6">
            <a href="<?php echo site_url('admin/deals?category=HOTEL'); ?>" class="text-decoration-none">
                <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100 <?php echo ($category_filter === 'HOTEL') ? 'border-info border-2' : ''; ?>">
                    <div class="text-info small fw-bold"><i class="fa-solid fa-hotel me-1"></i> HOTEL</div>
                    <h3 class="fw-black mb-0 text-dark"><?php echo number_format($hotel_count); ?></h3>
                </div>
            </a>
        </div>
        <div class="col-md-2 col-sm-4 col-6">
            <a href="<?php echo site_url('admin/deals?category=HOLIDAYS'); ?>" class="text-decoration-none">
                <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100 <?php echo ($category_filter === 'HOLIDAYS') ? 'border-success border-2' : ''; ?>">
                    <div class="text-success small fw-bold"><i class="fa-solid fa-umbrella-beach me-1"></i> HOLIDAYS</div>
                    <h3 class="fw-black mb-0 text-success"><?php echo number_format($holidays_count); ?></h3>
                </div>
            </a>
        </div>
        <div class="col-md-2 col-sm-4 col-6">
            <a href="<?php echo site_url('admin/deals?category=VISA'); ?>" class="text-decoration-none">
                <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100 <?php echo ($category_filter === 'VISA') ? 'border-warning border-2' : ''; ?>">
                    <div class="text-warning small fw-bold"><i class="fa-solid fa-passport me-1"></i> VISA</div>
                    <h3 class="fw-black mb-0 text-dark"><?php echo number_format($visa_count); ?></h3>
                </div>
            </a>
        </div>
    </div>

    <!-- Search & Filters Bar -->
    <div class="card border-0 shadow-sm rounded-4 p-3 bg-white mb-4">
        <form method="GET" action="<?php echo site_url('admin/deals'); ?>" class="row g-2 align-items-center">
            <div class="col-md-5 col-sm-12">
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0 text-muted"><i class="fa-solid fa-magnifying-glass"></i></span>
                    <input type="text" name="search" class="form-control bg-light border-start-0" placeholder="Search by deal title, promo code, or discount..." value="<?php echo htmlspecialchars($search); ?>">
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <select name="category" class="form-select bg-light">
                    <option value="">All Categories</option>
                    <option value="HOT DEALS" <?php echo ($category_filter === 'HOT DEALS') ? 'selected' : ''; ?>>HOT DEALS</option>
                    <option value="FLIGHT" <?php echo ($category_filter === 'FLIGHT') ? 'selected' : ''; ?>>FLIGHT</option>
                    <option value="HOTEL" <?php echo ($category_filter === 'HOTEL') ? 'selected' : ''; ?>>HOTEL</option>
                    <option value="HOLIDAYS" <?php echo ($category_filter === 'HOLIDAYS') ? 'selected' : ''; ?>>HOLIDAYS</option>
                    <option value="VISA" <?php echo ($category_filter === 'VISA') ? 'selected' : ''; ?>>VISA</option>
                </select>
            </div>
            <div class="col-md-2 col-sm-6">
                <select name="status" class="form-select bg-light">
                    <option value="">All Statuses</option>
                    <option value="active" <?php echo ($status_filter === 'active') ? 'selected' : ''; ?>>Active Only</option>
                    <option value="inactive" <?php echo ($status_filter === 'inactive') ? 'selected' : ''; ?>>Inactive Only</option>
                </select>
            </div>
            <div class="col-md-2 col-sm-12 d-flex gap-2">
                <button type="submit" class="btn btn-dark w-100 fw-bold"><i class="fa-solid fa-filter me-1"></i> Filter</button>
                <a href="<?php echo site_url('admin/deals'); ?>" class="btn btn-outline-secondary" title="Reset Filters"><i class="fa-solid fa-rotate"></i></a>
            </div>
        </form>
    </div>

    <!-- Deals Data Table -->
    <div class="card border-0 shadow-sm rounded-4 bg-white overflow-hidden">
        <div class="table-responsive">
            <table class="table align-middle mb-0 table-hover">
                <thead style="background: #f8fafc; font-size: 12px; text-transform: uppercase; color: #64748b; letter-spacing: 0.5px;">
                    <tr>
                        <th class="ps-4">Banner Preview</th>
                        <th>Category</th>
                        <th>Title & Description</th>
                        <th>Promo Code</th>
                        <th>Discount Tag</th>
                        <th>Sort</th>
                        <th>Status</th>
                        <th class="text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody style="font-size: 13px;">
                    <?php if (empty($deals)): ?>
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">
                                <i class="fa-solid fa-tag fa-3x mb-3 text-secondary opacity-50"></i>
                                <p class="mb-0 fw-bold">No Exclusive Deals Found</p>
                                <small>Click the "Add New Deal" button above to publish promotions.</small>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($deals as $d): ?>
                            <tr>
                                <td class="ps-4">
                                    <div style="width: 90px; height: 50px; border-radius: 8px; overflow: hidden; background: #f1f5f9; border: 1px solid #e2e8f0; display: flex; align-items: center; justify-content: center;">
                                        <img src="<?php echo htmlspecialchars($d['image_url']); ?>" alt="Banner" style="width: 100%; height: 100%; object-fit: cover;" onerror="this.src='https://via.placeholder.com/90x50?text=Deal';">
                                    </div>
                                </td>
                                <td>
                                    <?php 
                                        $catColor = '#64748b'; $catBg = '#f1f5f9';
                                        if ($d['category'] === 'HOT DEALS') { $catColor = '#dc2626'; $catBg = '#fee2e2'; }
                                        elseif ($d['category'] === 'FLIGHT') { $catColor = '#0284c7'; $catBg = '#e0f2fe'; }
                                        elseif ($d['category'] === 'HOTEL') { $catColor = '#7c3aed'; $catBg = '#ede9fe'; }
                                        elseif ($d['category'] === 'HOLIDAYS') { $catColor = '#16a34a'; $catBg = '#dcfce7'; }
                                        elseif ($d['category'] === 'VISA') { $catColor = '#d97706'; $catBg = '#fef3c7'; }
                                    ?>
                                    <span class="badge" style="background: <?php echo $catBg; ?>; color: <?php echo $catColor; ?>; font-weight: 700; padding: 6px 10px; border-radius: 6px;">
                                        <?php echo htmlspecialchars($d['category']); ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="fw-bold text-dark"><?php echo htmlspecialchars($d['title']); ?></div>
                                    <div class="text-muted small text-truncate" style="max-width: 280px;"><?php echo htmlspecialchars($d['subtitle'] ?: '—'); ?></div>
                                </td>
                                <td>
                                    <?php if (!empty($d['promo_code'])): ?>
                                        <span class="badge bg-light text-dark border border-secondary border-opacity-25 px-2 py-1 font-monospace fw-bold">
                                            <i class="fa-solid fa-ticket text-primary me-1"></i><?php echo htmlspecialchars($d['promo_code']); ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="text-muted small">—</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if (!empty($d['discount_text'])): ?>
                                        <span class="badge bg-success bg-opacity-10 text-success fw-bold px-2 py-1">
                                            <?php echo htmlspecialchars($d['discount_text']); ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="text-muted small">—</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="text-muted fw-bold"><?php echo (int)$d['sort_order']; ?></span>
                                </td>
                                <td>
                                    <a href="<?php echo site_url('admin/deals/toggle/' . $d['id']); ?>" class="btn btn-sm <?php echo ($d['status'] === 'active') ? 'btn-success' : 'btn-secondary'; ?> rounded-pill px-3 py-1 fw-bold" style="font-size: 11px;">
                                        <i class="fa-solid <?php echo ($d['status'] === 'active') ? 'fa-check' : 'fa-ban'; ?> me-1"></i>
                                        <?php echo ucfirst($d['status']); ?>
                                    </a>
                                </td>
                                <td class="text-end pe-4">
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-sm btn-outline-primary rounded-3 me-1" onclick='editDeal(<?php echo json_encode($d); ?>)' title="Edit Deal">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </button>
                                        <a href="<?php echo site_url('admin/deals/delete/' . $d['id']); ?>" class="btn btn-sm btn-outline-danger rounded-3" onclick="return confirm('Are you sure you want to permanently delete this deal?');" title="Delete Deal">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <?php if (!empty($paging) && $paging['total_pages'] > 1): ?>
            <div class="p-3 border-top d-flex justify-content-between align-items-center">
                <span class="text-muted small">Showing page <?php echo $paging['current_page']; ?> of <?php echo $paging['total_pages']; ?> (<?php echo number_format($paging['total_items']); ?> total)</span>
                <nav>
                    <ul class="pagination pagination-sm mb-0">
                        <?php for ($p = 1; $p <= $paging['total_pages']; $p++): ?>
                            <li class="page-item <?php echo ($p == $paging['current_page']) ? 'active' : ''; ?>">
                                <a class="page-link" href="<?php echo site_url('admin/deals?page=' . $p . (!empty($category_filter) ? '&category=' . urlencode($category_filter) : '') . (!empty($search) ? '&search=' . urlencode($search) : '')); ?>"><?php echo $p; ?></a>
                            </li>
                        <?php endfor; ?>
                    </ul>
                </nav>
            </div>
        <?php endif; ?>
    </div>

</div>

<!-- Add / Edit Deal Modal -->
<div class="modal fade" id="dealModal" tabindex="-1" aria-labelledby="dealModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content rounded-4 border-0 shadow">
            <form action="<?php echo site_url('admin/deals/save'); ?>" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id" id="dealId" value="">
                
                <div class="modal-header border-bottom px-4 pt-4 pb-3">
                    <h5 class="modal-title fw-bold" id="dealModalLabel">
                        <i class="fa-solid fa-fire text-danger me-2"></i> Add New Exclusive Deal
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-muted">Category Tab <span class="text-danger">*</span></label>
                            <select name="category" id="dealCategory" class="form-select" required>
                                <option value="HOT DEALS">HOT DEALS</option>
                                <option value="FLIGHT">FLIGHT</option>
                                <option value="HOTEL">HOTEL</option>
                                <option value="HOLIDAYS">HOLIDAYS</option>
                                <option value="VISA">VISA</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-muted">Status</label>
                            <select name="status" id="dealStatus" class="form-select">
                                <option value="active">Active (Visible on Homepage)</option>
                                <option value="inactive">Inactive (Hidden)</option>
                            </select>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label fw-bold small text-muted">Deal Title / Headline <span class="text-danger">*</span></label>
                            <input type="text" name="title" id="dealTitle" class="form-control" placeholder="e.g. Get Flat ₹600 OFF ON FLIGHT BOOKINGS" required>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label fw-bold small text-muted">Subtitle / Description</label>
                            <input type="text" name="subtitle" id="dealSubtitle" class="form-control" placeholder="e.g. Valid on ICICI Bank Credit Cards. Minimum booking ₹5,000.">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-bold small text-muted">Promo Coupon Code</label>
                            <input type="text" name="promo_code" id="dealPromoCode" class="form-control font-monospace text-uppercase" placeholder="e.g. ATICICIEMI">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-bold small text-muted">Discount Text / Badge</label>
                            <input type="text" name="discount_text" id="dealDiscountText" class="form-control" placeholder="e.g. FLAT ₹600 OFF or 12% OFF">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-bold small text-muted">Sort Order</label>
                            <input type="number" name="sort_order" id="dealSortOrder" class="form-control" value="0" min="0">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-muted">Target Booking Link</label>
                            <input type="text" name="link_url" id="dealLinkUrl" class="form-control" placeholder="e.g. flight/search, hotels, holidays, visa or URL" value="flight/search">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-muted">Banner Image URL</label>
                            <input type="url" name="image_url" id="dealImageUrl" class="form-control" placeholder="https://example.com/banner.jpg">
                        </div>

                        <div class="col-md-12">
                            <label class="form-label fw-bold small text-muted">OR Upload Image Banner</label>
                            <input type="file" name="image_file" class="form-control" accept="image/*">
                            <div class="form-text small">Recommended banner aspect ratio: 16:9 or ~600x340 px.</div>
                        </div>
                    </div>
                </div>
                
                <div class="modal-footer border-top px-4 py-3 bg-light">
                    <button type="button" class="btn btn-secondary fw-bold" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary fw-bold px-4"><i class="fa-solid fa-cloud-arrow-up me-1"></i> Save Deal</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function resetDealForm() {
    document.getElementById('dealId').value = '';
    document.getElementById('dealModalLabel').innerHTML = '<i class="fa-solid fa-fire text-danger me-2"></i> Add New Exclusive Deal';
    document.getElementById('dealCategory').value = 'HOT DEALS';
    document.getElementById('dealStatus').value = 'active';
    document.getElementById('dealTitle').value = '';
    document.getElementById('dealSubtitle').value = '';
    document.getElementById('dealPromoCode').value = '';
    document.getElementById('dealDiscountText').value = '';
    document.getElementById('dealSortOrder').value = '0';
    document.getElementById('dealLinkUrl').value = 'flight/search';
    document.getElementById('dealImageUrl').value = '';
}

function editDeal(d) {
    document.getElementById('dealId').value = d.id || '';
    document.getElementById('dealModalLabel').innerHTML = '<i class="fa-solid fa-pen-to-square text-primary me-2"></i> Edit Exclusive Deal';
    document.getElementById('dealCategory').value = d.category || 'HOT DEALS';
    document.getElementById('dealStatus').value = d.status || 'active';
    document.getElementById('dealTitle').value = d.title || '';
    document.getElementById('dealSubtitle').value = d.subtitle || '';
    document.getElementById('dealPromoCode').value = d.promo_code || '';
    document.getElementById('dealDiscountText').value = d.discount_text || '';
    document.getElementById('dealSortOrder').value = d.sort_order || '0';
    document.getElementById('dealLinkUrl').value = d.link_url || 'flight/search';
    document.getElementById('dealImageUrl').value = d.image_url || '';

    const modal = new bootstrap.Modal(document.getElementById('dealModal'));
    modal.show();
}
</script>
