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
                <i class="fa-solid fa-clock-rotate-left text-success"></i> API Activity & Response Logs
            </h3>
            <p class="text-muted small mb-0">Real-time recording, debugging, and JSON payload inspection for Flight, Hotel, and Payment APIs</p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <!-- Clear Logs Dropdown -->
            <div class="dropdown">
                <button class="btn btn-outline-danger btn-sm dropdown-toggle fw-semibold rounded-pill px-3 shadow-sm" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fa-solid fa-trash-can me-1"></i> Manage Logs
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                    <li>
                        <form action="<?php echo site_url('admin/api_logs/clear'); ?>" method="POST" onsubmit="return confirm('Clear logs older than 30 days?');">
                            <input type="hidden" name="type" value="old">
                            <button type="submit" class="dropdown-item py-2 small"><i class="fa-solid fa-calendar-xmark text-warning me-2"></i> Clear Logs &gt; 30 Days</button>
                        </form>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form action="<?php echo site_url('admin/api_logs/clear'); ?>" method="POST" onsubmit="return confirm('WARNING: Are you sure you want to delete ALL API logs?');">
                            <input type="hidden" name="type" value="all">
                            <button type="submit" class="dropdown-item py-2 text-danger small"><i class="fa-solid fa-fire me-2"></i> Clear All API Logs</button>
                        </form>
                    </li>
                </ul>
            </div>
            
            <a href="<?php echo site_url('admin/api_logs'); ?>" class="btn btn-sm btn-light border fw-semibold rounded-pill px-3 shadow-sm">
                <i class="fa-solid fa-rotate me-1"></i> Refresh
            </a>
        </div>
    </div>

    <!-- Quick Stats Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-3 d-flex align-items-center gap-3">
                    <div class="rounded-3 p-3 text-white d-flex align-items-center justify-content-center" style="background: linear-gradient(135deg, #2563eb, #1d4ed8); width: 48px; height: 48px;">
                        <i class="fa-solid fa-server fs-5"></i>
                    </div>
                    <div>
                        <div class="text-muted small fw-semibold">TOTAL API CALLS</div>
                        <div class="fs-4 fw-bold text-dark"><?php echo number_format($stats['total_calls']); ?></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-3 d-flex align-items-center gap-3">
                    <div class="rounded-3 p-3 text-white d-flex align-items-center justify-content-center" style="background: linear-gradient(135deg, #0284c7, #0369a1); width: 48px; height: 48px;">
                        <i class="fa-solid fa-plane-departure fs-5"></i>
                    </div>
                    <div>
                        <div class="text-muted small fw-semibold">FLIGHT API CALLS</div>
                        <div class="fs-4 fw-bold text-dark"><?php echo number_format($stats['flight_calls']); ?></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-3 d-flex align-items-center gap-3">
                    <div class="rounded-3 p-3 text-white d-flex align-items-center justify-content-center" style="background: linear-gradient(135deg, #ea580c, #c2410c); width: 48px; height: 48px;">
                        <i class="fa-solid fa-hotel fs-5"></i>
                    </div>
                    <div>
                        <div class="text-muted small fw-semibold">HOTEL API CALLS</div>
                        <div class="fs-4 fw-bold text-dark"><?php echo number_format($stats['hotel_calls']); ?></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-3 d-flex align-items-center gap-3">
                    <div class="rounded-3 p-3 text-white d-flex align-items-center justify-content-center" style="background: linear-gradient(135deg, #16a34a, #15803d); width: 48px; height: 48px;">
                        <i class="fa-solid fa-gauge-high fs-5"></i>
                    </div>
                    <div>
                        <div class="text-muted small fw-semibold">AVG LATENCY / SUCCESS</div>
                        <div class="fs-5 fw-bold text-dark">
                            <?php echo $stats['avg_latency_ms']; ?><span class="fs-6 text-muted">ms</span> 
                            <span class="badge bg-success-subtle text-success border border-success-subtle fs-7 ms-1"><?php echo $stats['success_rate']; ?>% OK</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters & Search Toolbar -->
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-3">
            <form method="GET" action="<?php echo site_url('admin/api_logs'); ?>" class="row g-2 align-items-center">
                
                <!-- Service Filter Pills -->
                <div class="col-12 col-md-auto">
                    <div class="btn-group btn-group-sm" role="group">
                        <a href="<?php echo site_url('admin/api_logs?service=all&status='.$status.'&search='.urlencode($search)); ?>" class="btn <?php echo ($service === 'all') ? 'btn-primary' : 'btn-outline-secondary'; ?>">All</a>
                        <a href="<?php echo site_url('admin/api_logs?service=flight&status='.$status.'&search='.urlencode($search)); ?>" class="btn <?php echo ($service === 'flight') ? 'btn-primary' : 'btn-outline-secondary'; ?>">Flights</a>
                        <a href="<?php echo site_url('admin/api_logs?service=hotel&status='.$status.'&search='.urlencode($search)); ?>" class="btn <?php echo ($service === 'hotel') ? 'btn-primary' : 'btn-outline-secondary'; ?>">Hotels</a>
                        <a href="<?php echo site_url('admin/api_logs?service=razorpay&status='.$status.'&search='.urlencode($search)); ?>" class="btn <?php echo ($service === 'razorpay') ? 'btn-primary' : 'btn-outline-secondary'; ?>">Razorpay</a>
                    </div>
                </div>

                <!-- Status Filter -->
                <div class="col-6 col-md-auto">
                    <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="all" <?php echo ($status === 'all') ? 'selected' : ''; ?>>All Statuses</option>
                        <option value="success" <?php echo ($status === 'success') ? 'selected' : ''; ?>>200 Success Only</option>
                        <option value="error" <?php echo ($status === 'error') ? 'selected' : ''; ?>>Errors & Failures</option>
                    </select>
                </div>

                <!-- Search Input -->
                <div class="col-12 col-md">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-light text-muted"><i class="fa-solid fa-magnifying-glass"></i></span>
                        <input type="text" name="search" class="form-control form-control-sm" placeholder="Search by Action, URL, IP, or Keyword..." value="<?php echo htmlspecialchars($search); ?>">
                        <?php if (!empty($search)): ?>
                            <a href="<?php echo site_url('admin/api_logs?service='.$service.'&status='.$status); ?>" class="btn btn-outline-secondary btn-sm"><i class="fa-solid fa-xmark"></i></a>
                        <?php endif; ?>
                        <button type="submit" class="btn btn-primary btn-sm px-3">Filter</button>
                    </div>
                </div>

            </form>
        </div>
    </div>

    <!-- Logs Table Card -->
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
            <h5 class="fw-bold text-dark mb-0 d-flex align-items-center gap-2">
                <i class="fa-solid fa-list-check text-primary"></i> API Request Entries
            </h5>
            <span class="text-muted small">Showing <strong><?php echo count($logs); ?></strong> of <strong><?php echo number_format($total_rows); ?></strong> requests</span>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr class="small text-muted text-uppercase">
                        <th class="ps-4">ID</th>
                        <th>Timestamp</th>
                        <th>Service</th>
                        <th>Action / Endpoint</th>
                        <th>HTTP Status</th>
                        <th>Latency</th>
                        <th>Client IP</th>
                        <th class="text-end pe-4">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($logs)): ?>
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">
                                <i class="fa-solid fa-inbox fs-1 mb-2 d-block text-secondary opacity-50"></i>
                                <strong>No API logs found matching your filters.</strong>
                                <div class="small">Perform a search on flight or hotel booking to see live captured payloads here.</div>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($logs as $l): ?>
                            <tr>
                                <td class="ps-4 font-monospace small text-muted">#<?php echo $l['id']; ?></td>
                                
                                <td class="small">
                                    <div class="fw-semibold text-dark"><?php echo date('H:i:s', strtotime($l['created_at'])); ?></div>
                                    <div class="text-muted" style="font-size: 11px;"><?php echo date('M d, Y', strtotime($l['created_at'])); ?></div>
                                </td>

                                <td>
                                    <?php if ($l['service_type'] === 'flight'): ?>
                                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1"><i class="fa-solid fa-plane me-1"></i> Flight</span>
                                    <?php elseif ($l['service_type'] === 'hotel'): ?>
                                        <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle px-2 py-1"><i class="fa-solid fa-hotel me-1"></i> Hotel</span>
                                    <?php else: ?>
                                        <span class="badge bg-info-subtle text-info-emphasis border border-info-subtle px-2 py-1"><i class="fa-solid fa-credit-card me-1"></i> <?php echo ucfirst($l['service_type']); ?></span>
                                    <?php endif; ?>
                                </td>

                                <td>
                                    <div class="fw-bold text-dark font-monospace small"><?php echo htmlspecialchars($l['action_name']); ?></div>
                                    <div class="text-muted text-truncate small" style="max-width: 250px; font-size: 11px;" title="<?php echo htmlspecialchars($l['endpoint_url']); ?>">
                                        <?php echo htmlspecialchars($l['endpoint_url']); ?>
                                    </div>
                                </td>

                                <td>
                                    <?php if ($l['http_code'] >= 200 && $l['http_code'] < 300): ?>
                                        <span class="badge bg-success px-2 py-1"><i class="fa-solid fa-check me-1"></i> <?php echo $l['http_code']; ?> OK</span>
                                    <?php elseif ($l['http_code'] === 0): ?>
                                        <span class="badge bg-danger px-2 py-1"><i class="fa-solid fa-triangle-exclamation me-1"></i> Offline</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger px-2 py-1"><i class="fa-solid fa-circle-xmark me-1"></i> <?php echo $l['http_code']; ?> Error</span>
                                    <?php endif; ?>
                                </td>

                                <td>
                                    <?php 
                                        $ms = $l['execution_time_ms']; 
                                        $color = ($ms < 800) ? 'text-success' : (($ms < 2000) ? 'text-warning-emphasis' : 'text-danger');
                                    ?>
                                    <span class="fw-bold font-monospace small <?php echo $color; ?>">
                                        <i class="fa-regular fa-clock me-1"></i> <?php echo $ms; ?>ms
                                    </span>
                                </td>

                                <td class="font-monospace small text-muted">
                                    <?php echo htmlspecialchars($l['ip_address'] ?: '-'); ?>
                                </td>

                                <td class="text-end pe-4">
                                    <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-3 fw-bold" onclick="inspectLog(<?php echo $l['id']; ?>)">
                                        <i class="fa-solid fa-code me-1"></i> View JSON
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
            <div class="card-footer bg-white py-3 border-0 d-flex justify-content-between align-items-center">
                <span class="text-muted small">Page <strong><?php echo $current_page; ?></strong> of <strong><?php echo $total_pages; ?></strong></span>
                <nav>
                    <ul class="pagination pagination-sm mb-0">
                        <?php if ($current_page > 1): ?>
                            <li class="page-item"><a class="page-link" href="<?php echo site_url('admin/api_logs?service='.$service.'&status='.$status.'&search='.urlencode($search).'&page='.($current_page - 1)); ?>">Previous</a></li>
                        <?php endif; ?>

                        <?php for ($p = max(1, $current_page - 2); $p <= min($total_pages, $current_page + 2); $p++): ?>
                            <li class="page-item <?php echo ($p == $current_page) ? 'active' : ''; ?>">
                                <a class="page-link" href="<?php echo site_url('admin/api_logs?service='.$service.'&status='.$status.'&search='.urlencode($search).'&page='.$p); ?>"><?php echo $p; ?></a>
                            </li>
                        <?php endfor; ?>

                        <?php if ($current_page < $total_pages): ?>
                            <li class="page-item"><a class="page-link" href="<?php echo site_url('admin/api_logs?service='.$service.'&status='.$status.'&search='.urlencode($search).'&page='.($current_page + 1)); ?>">Next</a></li>
                        <?php endif; ?>
                    </ul>
                </nav>
            </div>
        <?php endif; ?>

    </div>

</div>

<!-- JSON Inspector Modal -->
<div class="modal fade" id="jsonInspectorModal" tabindex="-1" aria-labelledby="jsonInspectorModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            
            <!-- Modal Header -->
            <div class="modal-header bg-dark text-white py-3 border-0">
                <div class="d-flex align-items-center gap-2">
                    <i class="fa-solid fa-code text-success fs-5"></i>
                    <div>
                        <h5 class="modal-title fw-bold mb-0" id="modalActionTitle">API Request & Response Inspector</h5>
                        <div class="small text-muted font-monospace" id="modalEndpointUrl" style="max-width: 600px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">-</div>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <span class="badge" id="modalHttpStatusBadge">200 OK</span>
                    <span class="badge bg-secondary font-monospace" id="modalLatencyBadge">0ms</span>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
            </div>

            <!-- Modal Nav Tabs -->
            <div class="bg-dark px-3 pt-2 border-bottom border-secondary">
                <ul class="nav nav-tabs border-0" id="inspectorTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active fw-bold small px-3 py-2" id="response-tab" data-bs-toggle="tab" data-bs-target="#response-tab-pane" type="button" role="tab" style="color: #4ade80;">
                            <i class="fa-solid fa-cloud-arrow-down me-1"></i> Response Payload (JSON)
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link fw-bold small px-3 py-2" id="request-tab" data-bs-toggle="tab" data-bs-target="#request-tab-pane" type="button" role="tab" style="color: #60a5fa;">
                            <i class="fa-solid fa-cloud-arrow-up me-1"></i> Request Payload (JSON)
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link fw-bold small px-3 py-2" id="details-tab" data-bs-toggle="tab" data-bs-target="#details-tab-pane" type="button" role="tab" style="color: #38bdf8;">
                            <i class="fa-solid fa-circle-info me-1"></i> Meta Details & Trace
                        </button>
                    </li>
                </ul>
            </div>

            <!-- Modal Body -->
            <div class="modal-body p-0 bg-black text-light" style="min-height: 450px;">
                <div class="tab-content h-100" id="inspectorTabContent">
                    
                    <!-- Response Pane -->
                    <div class="tab-pane fade show active p-3" id="response-tab-pane" role="tabpanel">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="badge bg-dark border border-secondary text-light font-monospace small">HTTP Response Body</span>
                            <button type="button" class="btn btn-sm btn-outline-light" onclick="copyJson('modalResponseBody')">
                                <i class="fa-solid fa-copy me-1"></i> Copy Response
                            </button>
                        </div>
                        <pre class="p-3 rounded-3 font-monospace small bg-dark text-light border border-secondary" id="modalResponseBody" style="max-height: 500px; overflow: auto; white-space: pre-wrap; word-break: break-all;">Loading response...</pre>
                    </div>

                    <!-- Request Pane -->
                    <div class="tab-pane fade p-3" id="request-tab-pane" role="tabpanel">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="badge bg-dark border border-secondary text-light font-monospace small">HTTP Request Payload</span>
                            <button type="button" class="btn btn-sm btn-outline-light" onclick="copyJson('modalRequestBody')">
                                <i class="fa-solid fa-copy me-1"></i> Copy Request
                            </button>
                        </div>
                        <pre class="p-3 rounded-3 font-monospace small bg-dark text-light border border-secondary" id="modalRequestBody" style="max-height: 500px; overflow: auto; white-space: pre-wrap; word-break: break-all;">Loading request...</pre>
                    </div>

                    <!-- Meta Details Pane -->
                    <div class="tab-pane fade p-4 bg-dark h-100" id="details-tab-pane" role="tabpanel">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="text-muted small fw-bold">ENDPOINT URL</label>
                                <div class="p-2 bg-black rounded font-monospace small text-break border border-secondary" id="metaUrl">-</div>
                            </div>
                            <div class="col-md-3">
                                <label class="text-muted small fw-bold">SERVICE / ACTION</label>
                                <div class="p-2 bg-black rounded font-monospace small text-white border border-secondary" id="metaServiceAction">-</div>
                            </div>
                            <div class="col-md-3">
                                <label class="text-muted small fw-bold">REQUEST METHOD</label>
                                <div class="p-2 bg-black rounded font-monospace small text-white border border-secondary" id="metaMethod">-</div>
                            </div>
                            <div class="col-md-4">
                                <label class="text-muted small fw-bold">CLIENT IP</label>
                                <div class="p-2 bg-black rounded font-monospace small text-white border border-secondary" id="metaIp">-</div>
                            </div>
                            <div class="col-md-4">
                                <label class="text-muted small fw-bold">EXECUTION TIME</label>
                                <div class="p-2 bg-black rounded font-monospace small text-white border border-secondary" id="metaLatency">-</div>
                            </div>
                            <div class="col-md-4">
                                <label class="text-muted small fw-bold">RECORDED TIMESTAMP</label>
                                <div class="p-2 bg-black rounded font-monospace small text-white border border-secondary" id="metaTimestamp">-</div>
                            </div>
                            <div class="col-12" id="metaErrorSection" style="display: none;">
                                <label class="text-danger small fw-bold">CURL / API ERROR MESSAGE</label>
                                <div class="p-3 bg-danger-subtle text-danger rounded font-monospace small border border-danger" id="metaError">-</div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Modal Footer -->
            <div class="modal-footer bg-dark border-0 py-2">
                <button type="button" class="btn btn-secondary btn-sm px-4 fw-semibold" data-bs-dismiss="modal">Close</button>
            </div>

        </div>
    </div>
</div>

<script>
var inspectorModal = null;

document.addEventListener('DOMContentLoaded', function() {
    inspectorModal = new bootstrap.Modal(document.getElementById('jsonInspectorModal'));
});

function inspectLog(logId) {
    document.getElementById('modalActionTitle').innerText = 'Loading Log #' + logId + '...';
    document.getElementById('modalResponseBody').innerText = 'Loading...';
    document.getElementById('modalRequestBody').innerText = 'Loading...';
    
    // Switch to first tab
    var firstTab = new bootstrap.Tab(document.getElementById('response-tab'));
    firstTab.show();

    inspectorModal.show();

    fetch('<?php echo site_url("admin/api_logs/detail/"); ?>' + logId)
        .then(function(res) { return res.json(); })
        .then(function(result) {
            if (result.status === 'success') {
                var d = result.data;
                document.getElementById('modalActionTitle').innerText = (d.service_type.toUpperCase()) + ' API: ' + d.action_name;
                document.getElementById('modalEndpointUrl').innerText = d.endpoint_url;

                // Status Badge
                var statusBadge = document.getElementById('modalHttpStatusBadge');
                if (d.http_code >= 200 && d.http_code < 300) {
                    statusBadge.className = 'badge bg-success';
                    statusBadge.innerText = d.http_code + ' OK';
                } else {
                    statusBadge.className = 'badge bg-danger';
                    statusBadge.innerText = (d.http_code || '0') + ' Error';
                }

                // Latency Badge
                document.getElementById('modalLatencyBadge').innerText = d.execution_time_ms + 'ms';

                // Payloads
                var respBody = d.response_formatted || d.response_payload;
                if (!respBody || respBody === 'null' || respBody === '0') {
                    if (d.http_code == 0 || d.error_message) {
                        respBody = '/* [cURL Connection Error / Timeout] */\n' + (d.error_message || 'The request timed out before receiving a response from the API server.');
                    } else {
                        respBody = '/* (Empty response body received from server) */';
                    }
                }
                document.getElementById('modalResponseBody').innerText = respBody;
                document.getElementById('modalRequestBody').innerText = d.request_formatted || d.request_payload || '/* (No request payload / GET) */';

                // Meta Details
                document.getElementById('metaUrl').innerText = d.endpoint_url;
                document.getElementById('metaServiceAction').innerText = d.service_type + ' / ' + d.action_name;
                document.getElementById('metaMethod').innerText = d.request_method;
                document.getElementById('metaIp').innerText = d.ip_address || '-';
                document.getElementById('metaLatency').innerText = d.execution_time_ms + ' ms';
                document.getElementById('metaTimestamp').innerText = d.created_at;

                if (d.error_message) {
                    document.getElementById('metaErrorSection').style.display = 'block';
                    document.getElementById('metaError').innerText = d.error_message;
                } else {
                    document.getElementById('metaErrorSection').style.display = 'none';
                }
            } else {
                alert('Error loading log details: ' + (result.message || 'Unknown error'));
            }
        })
        .catch(function(err) {
            document.getElementById('modalResponseBody').innerText = 'Failed to fetch log details.';
        });
}

function copyJson(elemId) {
    var text = document.getElementById(elemId).innerText;
    if (!text) return;
    navigator.clipboard.writeText(text).then(function() {
        alert('Payload copied to clipboard!');
    }).catch(function() {
        alert('Could not copy to clipboard.');
    });
}
</script>
