<div class="container-fluid p-4">

    <!-- Flash Messages -->
    <?php if ($this->session->flashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-3" role="alert">
            <i class="fa-solid fa-circle-check me-2"></i> <?php echo $this->session->flashdata('success'); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- Page Header -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
        <div>
            <h3 class="fw-bold text-dark mb-1 d-flex align-items-center gap-2">
                <i class="fa-solid fa-clock-rotate-left" style="color: #8b5cf6;"></i> Hotel API Activity & Response Logs
            </h3>
            <p class="text-muted small mb-0">Dedicated log inspector for all 14 Benzy / Akbar Hotel API services (Init, HotelRate, Pricing, Itinerary, StartPay)</p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <a href="<?php echo site_url('admin/hotel_api_settings'); ?>" class="btn btn-sm btn-outline-primary fw-semibold rounded-pill px-3 shadow-sm">
                <i class="fa-solid fa-gear me-1"></i> Hotel API Settings
            </a>

            <!-- Clear Hotel Logs Form -->
            <form action="<?php echo site_url('admin/hotel_api_logs/clear'); ?>" method="POST" onsubmit="return confirm('Clear all hotel API activity logs?');">
                <button type="submit" class="btn btn-outline-danger btn-sm fw-semibold rounded-pill px-3 shadow-sm">
                    <i class="fa-solid fa-trash-can me-1"></i> Clear Hotel Logs
                </button>
            </form>
            
            <a href="<?php echo site_url('admin/hotel_api_logs'); ?>" class="btn btn-sm btn-light border fw-semibold rounded-pill px-3 shadow-sm">
                <i class="fa-solid fa-rotate me-1"></i> Refresh
            </a>
        </div>
    </div>

    <!-- Quick Stats Cards (Hotel Specific) -->
    <div class="row g-3 mb-4">
        <div class="col-md-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-3 d-flex align-items-center gap-3">
                    <div class="rounded-3 p-3 text-white d-flex align-items-center justify-content-center" style="background: linear-gradient(135deg, #8b5cf6, #6d28d9); width: 48px; height: 48px;">
                        <i class="fa-solid fa-hotel fs-5"></i>
                    </div>
                    <div>
                        <div class="text-muted small fw-semibold">TOTAL HOTEL CALLS</div>
                        <div class="fs-4 fw-bold text-dark"><?php echo number_format($stats['total_calls']); ?></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-3 d-flex align-items-center gap-3">
                    <div class="rounded-3 p-3 text-white d-flex align-items-center justify-content-center" style="background: linear-gradient(135deg, #16a34a, #15803d); width: 48px; height: 48px;">
                        <i class="fa-solid fa-circle-check fs-5"></i>
                    </div>
                    <div>
                        <div class="text-muted small fw-semibold">SUCCESS (2xx)</div>
                        <div class="fs-4 fw-bold text-success"><?php echo number_format($stats['success_count']); ?></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-3 d-flex align-items-center gap-3">
                    <div class="rounded-3 p-3 text-white d-flex align-items-center justify-content-center" style="background: linear-gradient(135deg, #dc2626, #b91c1c); width: 48px; height: 48px;">
                        <i class="fa-solid fa-circle-xmark fs-5"></i>
                    </div>
                    <div>
                        <div class="text-muted small fw-semibold">ERRORS / FAILS</div>
                        <div class="fs-4 fw-bold text-danger"><?php echo number_format($stats['error_count']); ?></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-3 d-flex align-items-center gap-3">
                    <div class="rounded-3 p-3 text-white d-flex align-items-center justify-content-center" style="background: linear-gradient(135deg, #d97706, #b45309); width: 48px; height: 48px;">
                        <i class="fa-solid fa-stopwatch fs-5"></i>
                    </div>
                    <div>
                        <div class="text-muted small fw-semibold">AVG LATENCY</div>
                        <div class="fs-4 fw-bold text-dark"><?php echo $stats['avg_latency_ms']; ?> ms</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter & Search Bar -->
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-3">
            <form method="GET" action="<?php echo site_url('admin/hotel_api_logs'); ?>" class="row g-2 align-items-center">
                <div class="col-md-3">
                    <select name="status" class="form-select form-select-sm border-light-subtle rounded-3" onchange="this.form.submit()">
                        <option value="all" <?php echo ($status === 'all') ? 'selected' : ''; ?>>All Status Codes</option>
                        <option value="success" <?php echo ($status === 'success') ? 'selected' : ''; ?>>2xx Success Only</option>
                        <option value="error" <?php echo ($status === 'error') ? 'selected' : ''; ?>>4xx / 5xx / Failures Only</option>
                    </select>
                </div>

                <div class="col-md-7">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-white border-light-subtle"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
                        <input type="text" name="search" class="form-control border-light-subtle" placeholder="Search by Action (Signature, Init, HotelRate), URL, or Error Message..." value="<?php echo htmlspecialchars($search); ?>">
                    </div>
                </div>

                <div class="col-md-2 d-flex gap-1">
                    <button type="submit" class="btn btn-sm btn-primary w-100 fw-semibold rounded-3">Filter</button>
                    <?php if (!empty($search) || $status !== 'all'): ?>
                        <a href="<?php echo site_url('admin/hotel_api_logs'); ?>" class="btn btn-sm btn-light border text-secondary rounded-3" title="Clear Filters"><i class="fa-solid fa-xmark"></i></a>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    </div>

    <!-- Logs Table -->
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" style="font-size: 13px;">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3" style="width: 70px;">#ID</th>
                        <th style="width: 140px;">Action / Service</th>
                        <th>Endpoint URL</th>
                        <th style="width: 90px;">Method</th>
                        <th style="width: 90px;">HTTP Code</th>
                        <th style="width: 100px;">Latency</th>
                        <th style="width: 150px;">Timestamp</th>
                        <th class="text-center pe-3" style="width: 90px;">Payload</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($logs)): ?>
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">
                                <i class="fa-solid fa-circle-info fs-3 mb-2 d-block text-secondary opacity-50"></i>
                                No Hotel API logs recorded yet. Hotel API calls will appear here automatically.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($logs as $log): ?>
                            <?php 
                                $isSuccess = ($log['http_code'] >= 200 && $log['http_code'] < 300);
                                $badgeClass = $isSuccess ? 'bg-success-subtle text-success border border-success-subtle' : 'bg-danger-subtle text-danger border border-danger-subtle';
                            ?>
                            <tr>
                                <td class="ps-3 fw-bold text-secondary">#<?php echo $log['id']; ?></td>
                                <td>
                                    <span class="badge bg-purple-subtle text-purple border fw-bold text-dark px-2 py-1" style="background: #ede9fe; color: #6d28d9 !important;">
                                        <i class="fa-solid fa-hotel me-1"></i><?php echo htmlspecialchars($log['action_name']); ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="text-truncate" style="max-width: 320px;" title="<?php echo htmlspecialchars($log['endpoint_url']); ?>">
                                        <span class="font-monospace text-dark"><?php echo htmlspecialchars($log['endpoint_url']); ?></span>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-light text-secondary border font-monospace"><?php echo htmlspecialchars($log['request_method']); ?></span>
                                </td>
                                <td>
                                    <span class="badge <?php echo $badgeClass; ?> font-monospace fw-bold">
                                        <?php echo $log['http_code'] ? $log['http_code'] : 'ERR'; ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="text-muted font-monospace"><i class="fa-solid fa-stopwatch me-1 text-secondary opacity-75"></i><?php echo $log['execution_time_ms']; ?>ms</span>
                                </td>
                                <td class="text-muted small">
                                    <?php echo date('d M Y, H:i:s', strtotime($log['created_at'])); ?>
                                </td>
                                <td class="text-center pe-3">
                                    <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-2 py-1" onclick="viewLogDetail(<?php echo $log['id']; ?>)" title="View JSON Payload">
                                        <i class="fa-solid fa-code me-1"></i> View
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <?php if ($total_pages > 1): ?>
            <div class="card-footer bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                <span class="text-muted small">Showing page <strong><?php echo $current_page; ?></strong> of <strong><?php echo $total_pages; ?></strong> (<?php echo $total_rows; ?> total logs)</span>
                <ul class="pagination pagination-sm mb-0">
                    <?php if ($current_page > 1): ?>
                        <li class="page-item"><a class="page-link" href="<?php echo site_url('admin/hotel_api_logs?page='.($current_page-1).'&status='.$status.'&search='.urlencode($search)); ?>">Previous</a></li>
                    <?php endif; ?>
                    <?php for ($p = max(1, $current_page - 2); $p <= min($total_pages, $current_page + 2); $p++): ?>
                        <li class="page-item <?php echo ($p === $current_page) ? 'active' : ''; ?>">
                            <a class="page-link" href="<?php echo site_url('admin/hotel_api_logs?page='.$p.'&status='.$status.'&search='.urlencode($search)); ?>"><?php echo $p; ?></a>
                        </li>
                    <?php endfor; ?>
                    <?php if ($current_page < $total_pages): ?>
                        <li class="page-item"><a class="page-link" href="<?php echo site_url('admin/hotel_api_logs?page='.($current_page+1).'&status='.$status.'&search='.urlencode($search)); ?>">Next</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Modal for Viewing JSON Payload -->
<div class="modal fade" id="logDetailModal" tabindex="-1" aria-labelledby="logDetailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-bottom py-3">
                <h5 class="modal-title fw-bold text-dark d-flex align-items-center gap-2" id="logDetailModalLabel">
                    <i class="fa-solid fa-code text-primary"></i> Hotel API Payload Inspector
                    <span id="modalLogIdBadge" class="badge bg-secondary"></span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <!-- Meta Info Row -->
                <div class="row g-2 mb-3 p-3 bg-light rounded-3 font-monospace small">
                    <div class="col-md-3"><strong>Action:</strong> <span id="modalAction" class="text-primary fw-bold"></span></div>
                    <div class="col-md-3"><strong>Status:</strong> <span id="modalStatus"></span></div>
                    <div class="col-md-3"><strong>Latency:</strong> <span id="modalLatency"></span></div>
                    <div class="col-md-3"><strong>Timestamp:</strong> <span id="modalTimestamp"></span></div>
                    <div class="col-md-12 text-truncate"><strong>URL:</strong> <span id="modalUrl" class="text-dark"></span></div>
                    <div id="modalErrorWrap" class="col-md-12 text-danger mt-1" style="display:none;"><strong>Error:</strong> <span id="modalError"></span></div>
                </div>

                <!-- Tabs for Request vs Response -->
                <ul class="nav nav-pills mb-3" id="payloadTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active fw-bold px-4" id="response-tab" data-bs-toggle="pill" data-bs-target="#responseTabPane" type="button" role="tab">
                            <i class="fa-solid fa-arrow-down-left-and-up-right-to-center me-1 text-success"></i> API Response JSON
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link fw-bold px-4" id="request-tab" data-bs-toggle="pill" data-bs-target="#requestTabPane" type="button" role="tab">
                            <i class="fa-solid fa-paper-plane me-1 text-primary"></i> API Request JSON
                        </button>
                    </li>
                </ul>

                <div class="tab-content" id="payloadTabContent">
                    <div class="tab-pane fade show active" id="responseTabPane" role="tabpanel">
                        <div class="position-relative">
                            <button type="button" class="btn btn-sm btn-outline-secondary position-absolute top-0 end-0 m-2" onclick="copyModalCode('modalResponseContent')">
                                <i class="fa-solid fa-copy me-1"></i> Copy
                            </button>
                            <pre id="modalResponseContent" class="p-3 bg-dark text-light rounded-3 font-monospace small" style="max-height: 480px; overflow-y: auto;"></pre>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="requestTabPane" role="tabpanel">
                        <div class="position-relative">
                            <button type="button" class="btn btn-sm btn-outline-secondary position-absolute top-0 end-0 m-2" onclick="copyModalCode('modalRequestContent')">
                                <i class="fa-solid fa-copy me-1"></i> Copy
                            </button>
                            <pre id="modalRequestContent" class="p-3 bg-dark text-light rounded-3 font-monospace small" style="max-height: 480px; overflow-y: auto;"></pre>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-top py-2">
                <button type="button" class="btn btn-secondary btn-sm px-3" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
function viewLogDetail(id) {
    const modal = new bootstrap.Modal(document.getElementById('logDetailModal'));
    fetch('<?php echo site_url("admin/api_log_detail/"); ?>' + id)
        .then(res => res.json())
        .then(res => {
            if (res.status === 'success') {
                const d = res.data;
                document.getElementById('modalLogIdBadge').textContent = '#' + d.id;
                document.getElementById('modalAction').textContent = d.action_name;
                document.getElementById('modalStatus').innerHTML = '<span class="badge ' + (d.http_code >= 200 && d.http_code < 300 ? 'bg-success' : 'bg-danger') + '">' + (d.http_code || 'ERR') + '</span>';
                document.getElementById('modalLatency').textContent = d.execution_time_ms + ' ms';
                document.getElementById('modalTimestamp').textContent = d.created_at;
                document.getElementById('modalUrl').textContent = d.endpoint_url;

                if (d.error_message) {
                    document.getElementById('modalErrorWrap').style.display = 'block';
                    document.getElementById('modalError').textContent = d.error_message;
                } else {
                    document.getElementById('modalErrorWrap').style.display = 'none';
                }

                document.getElementById('modalRequestContent').textContent = d.request_formatted || '{}';
                document.getElementById('modalResponseContent').textContent = d.response_formatted || '{}';

                modal.show();
            }
        });
}

function copyModalCode(id) {
    const text = document.getElementById(id).textContent;
    navigator.clipboard.writeText(text).then(() => {
        alert('Copied to clipboard!');
    });
}
</script>
