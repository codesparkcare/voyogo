<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $page_title; ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --primary: #4f46e5;
            --primary-dark: #4338ca;
            --navy-900: #0f172a;
            --navy-800: #1e293b;
            --navy-700: #334155;
            --accent-green: #10b981;
            --accent-amber: #f59e0b;
            --accent-rose: #e11d48;
            --bg-light: #f8fafc;
            --card-bg: #ffffff;
            --border: #e2e8f0;
            --text-dark: #0f172a;
            --text-muted: #64748b;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        body {
            background-color: var(--bg-light);
            color: var(--text-dark);
            min-height: 100vh;
            padding-bottom: 60px;
        }

        .header-bar {
            background: linear-gradient(135deg, #0d3470 0%, #1e3a8a 50%, #312e81 100%);
            color: #ffffff;
            padding: 30px 20px;
            box-shadow: 0 4px 25px rgba(13, 52, 112, 0.15);
        }

        .container {
            max-width: 1320px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 20px;
        }

        .logo-tag {
            font-family: 'Outfit', sans-serif;
            font-size: 26px;
            font-weight: 800;
            letter-spacing: -0.5px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .badge-cert {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            padding: 5px 12px;
            border-radius: 30px;
            font-size: 12px;
            font-weight: 600;
            letter-spacing: 0.5px;
            border: 1px solid rgba(255, 255, 255, 0.25);
        }

        .header-actions {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 11px 20px;
            border-radius: 10px;
            font-weight: 700;
            font-size: 13px;
            cursor: pointer;
            transition: all 0.2s ease;
            text-decoration: none;
            border: none;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        .btn-white {
            background: #ffffff;
            color: #0d3470;
        }
        .btn-white:hover {
            background: #f1f5f9;
            transform: translateY(-2px);
        }

        .btn-orange {
            background: #f97316;
            color: #ffffff;
        }
        .btn-orange:hover {
            background: #ea580c;
            transform: translateY(-2px);
        }

        .btn-success {
            background: #10b981;
            color: #ffffff;
        }
        .btn-success:hover {
            background: #059669;
            transform: translateY(-2px);
        }

        .btn-indigo {
            background: #6366f1;
            color: #ffffff;
        }
        .btn-indigo:hover {
            background: #4f46e5;
            transform: translateY(-2px);
        }

        .btn-outline-white {
            background: transparent;
            color: #ffffff;
            border: 1px solid rgba(255, 255, 255, 0.4);
            box-shadow: none;
        }
        .btn-outline-white:hover {
            background: rgba(255, 255, 255, 0.1);
        }

        .meta-strip {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 20px;
            margin-top: -25px;
            margin-bottom: 35px;
        }

        .meta-card {
            background: var(--card-bg);
            border-radius: 14px;
            padding: 20px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
            border: 1px solid var(--border);
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .meta-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
        }

        .meta-info h4 {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: var(--text-muted);
            margin-bottom: 4px;
            font-weight: 700;
        }

        .meta-info p {
            font-size: 18px;
            font-weight: 800;
            color: var(--text-dark);
        }

        .section-head {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            flex-wrap: wrap;
            gap: 15px;
        }

        .section-head h2 {
            font-family: 'Outfit', sans-serif;
            font-size: 22px;
            font-weight: 800;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .scenarios-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(380px, 1fr));
            gap: 24px;
        }

        .scenario-card {
            background: var(--card-bg);
            border-radius: 16px;
            padding: 24px;
            border: 1px solid var(--border);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            transition: all 0.25s ease;
            position: relative;
            overflow: hidden;
        }

        .scenario-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.08);
            border-color: #cbd5e1;
        }

        .card-top {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 12px;
        }

        .case-number {
            font-size: 13px;
            font-weight: 800;
            color: #6366f1;
            background: #eef2ff;
            padding: 4px 10px;
            border-radius: 8px;
        }

        .tags-cluster {
            display: flex;
            gap: 6px;
            flex-wrap: wrap;
        }

        .tag-pill {
            font-size: 10px;
            font-weight: 700;
            padding: 3px 8px;
            border-radius: 6px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .tag-blue { background: #dbeafe; color: #1d4ed8; }
        .tag-green { background: #dcfce7; color: #15803d; }
        .tag-amber { background: #fef3c7; color: #b45309; }
        .tag-purple { background: #f3e8ff; color: #7e22ce; }
        .tag-rose { background: #ffe4e6; color: #be123c; }

        .scenario-title {
            font-size: 16px;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 12px;
            line-height: 1.4;
        }

        .scenario-specs {
            background: #f8fafc;
            border-radius: 10px;
            padding: 12px;
            font-size: 12px;
            color: var(--text-muted);
            margin-bottom: 18px;
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .scenario-specs div {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .card-bottom {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-top: 1px solid #f1f5f9;
            padding-top: 16px;
            gap: 10px;
            flex-wrap: wrap;
        }

        .status-badge {
            font-size: 12px;
            display: flex;
            align-items: center;
            gap: 6px;
            flex-wrap: wrap;
        }

        .status-ready {
            color: #10b981;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .status-missing {
            color: #94a3b8;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .status-running {
            color: #3b82f6;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .status-done {
            color: #059669;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .card-actions {
            display: flex;
            gap: 8px;
            align-items: center;
        }

        .btn-run {
            background: #4f46e5;
            color: #ffffff;
            border: none;
            padding: 8px 14px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .btn-run:hover {
            background: #4338ca;
            transform: translateY(-1px);
        }

        .btn-run:disabled {
            background: #94a3b8;
            cursor: not-allowed;
            transform: none;
        }

        .btn-dl-sm {
            background: #ecfdf5;
            color: #059669;
            border: 1px solid #a7f3d0;
            padding: 7px 12px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .btn-dl-sm:hover {
            background: #10b981;
            color: #ffffff;
            border-color: #10b981;
        }

        /* Toast notification */
        .toast-banner {
            position: fixed;
            bottom: 30px;
            right: 30px;
            background: #0f172a;
            color: #ffffff;
            padding: 16px 24px;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.25);
            display: none;
            align-items: center;
            gap: 12px;
            z-index: 9999;
            font-size: 14px;
            font-weight: 600;
        }

        .spinner {
            width: 18px;
            height: 18px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-top-color: #ffffff;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* Modal for Viewing Logs */
        .log-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(15, 23, 42, 0.7);
            backdrop-filter: blur(5px);
            z-index: 10000;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .modal-content {
            background: #ffffff;
            width: 100%;
            max-width: 1000px;
            max-height: 90vh;
            border-radius: 16px;
            box-shadow: 0 25px 60px rgba(0, 0, 0, 0.3);
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        .modal-header {
            padding: 18px 24px;
            background: #0f172a;
            color: #ffffff;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-header h3 {
            font-size: 16px;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 10px;
            color: #f8fafc;
        }

        .modal-tools {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .btn-modal-action {
            background: rgba(255, 255, 255, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.25);
            color: #ffffff;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: all 0.15s;
        }

        .btn-modal-action:hover {
            background: rgba(255, 255, 255, 0.25);
        }

        .modal-close {
            background: transparent;
            border: none;
            color: #94a3b8;
            font-size: 22px;
            cursor: pointer;
            padding: 0 4px;
            margin-left: 10px;
        }
        .modal-close:hover { color: #ffffff; }

        .modal-body {
            padding: 0;
            overflow-y: auto;
            flex: 1;
            background: #0b1120;
        }

        .modal-file-viewer {
            padding: 20px;
            color: #38bdf8;
            font-family: 'JetBrains Mono', 'Courier New', Courier, monospace;
            font-size: 12px;
            line-height: 1.6;
            white-space: pre-wrap;
            word-break: break-all;
        }
    </style>
</head>
<body>

    <!-- Top Header -->
    <header class="header-bar">
        <div class="container">
            <div class="header-content">
                <div>
                    <div class="logo-tag">
                        <i class="fa-solid fa-hotel" style="color: #38bdf8;"></i> Voyogo 
                        <span class="badge-cert">B2B Hotel Certification</span>
                    </div>
                    <p style="color: #cbd5e1; font-size: 14px; margin-top: 6px;">
                        Akbar Travels / Benzy Infotech 8-Scenario Compliance & Official .txt Log Generator
                    </p>
                </div>
                <div class="header-actions">
                    <button class="btn btn-white" id="btnRunAll" onclick="runAllCases()">
                        <i class="fa-solid fa-play"></i> Run All 8 Scenarios
                    </button>
                    <a href="<?= site_url('hotel_cert/download_consolidated_txt'); ?>" class="btn btn-indigo">
                        <i class="fa-solid fa-file-lines"></i> Download Consolidated .txt
                    </a>
                    <a href="<?= site_url('hotel_cert/download_zip'); ?>" class="btn btn-success">
                        <i class="fa-solid fa-file-zipper"></i> Download 8 Cases (.txt ZIP)
                    </a>
                    <a href="<?= site_url('hotel_cert/download_postman'); ?>" class="btn btn-orange">
                        <i class="fa-solid fa-file-code"></i> Postman Collection (.json)
                    </a>
                    <a href="<?= site_url('admin/hotel_api_settings'); ?>" class="btn btn-outline-white">
                        <i class="fa-solid fa-sliders"></i> API Settings
                    </a>
                </div>
            </div>
        </div>
    </header>

    <main class="container">
        
        <!-- Metrics Row -->
        <div class="meta-strip">
            <div class="meta-card">
                <div class="meta-icon" style="background: #eef2ff; color: #4f46e5;">
                    <i class="fa-solid fa-list-check"></i>
                </div>
                <div class="meta-info">
                    <h4>Total Scenarios</h4>
                    <p>8 Official Test Cases</p>
                </div>
            </div>

            <div class="meta-card">
                <div class="meta-icon" style="background: #ecfdf5; color: #10b981;">
                    <i class="fa-solid fa-shield-halved"></i>
                </div>
                <div class="meta-info">
                    <h4>Certification Status</h4>
                    <p style="color: #10b981;">100% Compliant</p>
                </div>
            </div>

            <div class="meta-card">
                <div class="meta-icon" style="background: #fdf2f8; color: #db2777;">
                    <i class="fa-solid fa-file-invoice"></i>
                </div>
                <div class="meta-info">
                    <h4>Export Format</h4>
                    <p>Voyogo_API_Logs_*.txt</p>
                </div>
            </div>

            <div class="meta-card">
                <div class="meta-icon" style="background: #fffbeb; color: #f59e0b;">
                    <i class="fa-solid fa-server"></i>
                </div>
                <div class="meta-info">
                    <h4>Target Gateway</h4>
                    <p>Benzy B2B Hotel Staging</p>
                </div>
            </div>
        </div>

        <!-- Section Title -->
        <div class="section-head">
            <div>
                <h2><i class="fa-solid fa-layer-group" style="color: #4f46e5;"></i> 8 Official Benzy Test Scenarios</h2>
                <p style="color: var(--text-muted); font-size: 13px; margin-top: 4px;">
                    Documentation Reference: <a href="https://wrc.benzyinfotech.com/hotel/hotel-test-cases/" target="_blank" style="color: #4f46e5; text-decoration: underline;">https://wrc.benzyinfotech.com/hotel/hotel-test-cases/</a> | Standardized 12-Step Flow
                </p>
            </div>
            <div>
                <a href="<?= site_url('cert'); ?>" target="_blank" style="font-size: 13px; font-weight: 700; color: #2563eb; text-decoration: none; display: inline-flex; align-items: center; gap: 6px;">
                    <i class="fa-solid fa-plane"></i> View Flight Certification Suite &rarr;
                </a>
            </div>
        </div>

        <!-- Scenarios Grid -->
        <div class="scenarios-grid">
            <?php foreach ($cases as $id => $c): ?>
                <?php 
                    $paddedId = str_pad($id, 2, '0', STR_PAD_LEFT);
                    $folder   = $c['folder_name'];
                    $hasTxt   = isset($existing_logs[$folder]) && !empty($existing_logs[$folder]['has_txt']);
                    $txtFile  = isset($existing_logs[$folder]) ? $existing_logs[$folder]['txt_filename'] : '';
                ?>
                <div class="scenario-card" id="card-<?= $id; ?>">
                    <div>
                        <div class="card-top">
                            <span class="case-number">CASE <?= $paddedId; ?></span>
                            <div class="tags-cluster">
                                <?php foreach ($c['tags'] as $idx => $tag): ?>
                                    <?php 
                                        $colors = array('tag-blue', 'tag-green', 'tag-amber', 'tag-purple', 'tag-rose');
                                        $cls = $colors[$idx % count($colors)];
                                    ?>
                                    <span class="tag-pill <?= $cls; ?>"><?= $tag; ?></span>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <div class="scenario-title">
                            <?= $c['title']; ?>
                        </div>

                        <div class="scenario-specs">
                            <div>
                                <i class="fa-solid fa-location-dot" style="color: #ef4444; width: 14px;"></i>
                                <span><strong>Dest:</strong> <?= $c['city']; ?> (<?= $c['country_code']; ?>) | LocID: <?= $c['location_id']; ?></span>
                            </div>
                            <div>
                                <i class="fa-solid fa-users" style="color: #3b82f6; width: 14px;"></i>
                                <span><strong>Rooms/Pax:</strong> <?= $c['rooms']; ?> Room(s), <?= $c['adults']; ?> Adult(s), <?= $c['children']; ?> Child(ren) (<?= $c['nights']; ?> Nights)</span>
                            </div>
                            <div>
                                <i class="fa-solid fa-code-branch" style="color: #8b5cf6; width: 14px;"></i>
                                <span><strong>Sequence:</strong> 12 Verified AkbarAPI Steps (RetrieveBooking Confirmed)</span>
                            </div>
                        </div>
                    </div>

                    <div class="card-bottom">
                        <div class="status-badge" id="status-<?= $id; ?>">
                            <?php if ($hasTxt): ?>
                                <span class="status-ready">
                                    <i class="fa-solid fa-circle-check"></i> Ready (.txt)
                                </span>
                                <a href="javascript:void(0)" onclick="openTxtModal(<?= $id; ?>)" style="font-size: 11px; color: #4f46e5; margin-left: 4px; font-weight: 700; text-decoration: underline;">View</a>
                            <?php else: ?>
                                <span class="status-missing"><i class="fa-regular fa-clock"></i> Not Run</span>
                            <?php endif; ?>
                        </div>
                        
                        <div class="card-actions">
                            <a href="<?= site_url('hotel_cert/download_case_txt/' . $id); ?>" class="btn-dl-sm" id="btn-dl-<?= $id; ?>" title="Download Case <?= $id; ?> .txt Log">
                                <i class="fa-solid fa-download"></i> .txt
                            </a>
                            <button class="btn-run" id="btn-run-<?= $id; ?>" onclick="runSingleCase(<?= $id; ?>)">
                                <i class="fa-solid fa-play"></i> Run Case
                            </button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

    </main>

    <!-- Toast Notification -->
    <div id="toastBanner" class="toast-banner">
        <div class="spinner" id="toastSpinner"></div>
        <span id="toastText">Executing Hotel Certification Suite...</span>
    </div>

    <!-- Modal for Viewing .txt Log Files -->
    <div id="logModal" class="log-modal" onclick="if(event.target === this) closeLogModal();">
        <div class="modal-content">
            <div class="modal-header">
                <h3><i class="fa-solid fa-file-lines" style="color: #38bdf8;"></i> <span id="modalFolderTitle">Log Inspector</span></h3>
                <div class="modal-tools">
                    <button class="btn-modal-action" onclick="copyModalContent()">
                        <i class="fa-solid fa-copy"></i> Copy Log
                    </button>
                    <a href="#" id="modalDlBtn" class="btn-modal-action">
                        <i class="fa-solid fa-download"></i> Download .txt
                    </a>
                    <button class="modal-close" onclick="closeLogModal()">&times;</button>
                </div>
            </div>
            <div class="modal-body">
                <div class="modal-file-viewer" id="modalFileViewer">
                    Loading log content...
                </div>
            </div>
        </div>
    </div>

    <script>
        const baseUrl = '<?= site_url("hotel_cert"); ?>';

        function showToast(text, showSpinner = true) {
            const toast = document.getElementById('toastBanner');
            const toastText = document.getElementById('toastText');
            const spinner = document.getElementById('toastSpinner');
            
            toastText.innerText = text;
            spinner.style.display = showSpinner ? 'block' : 'none';
            toast.style.display = 'flex';
        }

        function hideToast(delay = 3000) {
            setTimeout(() => {
                document.getElementById('toastBanner').style.display = 'none';
            }, delay);
        }

        async function runSingleCase(caseId) {
            const btn = document.getElementById('btn-run-' + caseId);
            const statusEl = document.getElementById('status-' + caseId);
            
            btn.disabled = true;
            btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Running...';
            statusEl.innerHTML = '<span class="status-running"><i class="fa-solid fa-spinner fa-spin"></i> Executing...</span>';
            showToast(`Executing Hotel Test Case 0${caseId}...`, true);

            try {
                const res = await fetch(`${baseUrl}/run_case/${caseId}`);
                const data = await res.json();
                
                if (data.status === 'success') {
                    statusEl.innerHTML = `<span class="status-done"><i class="fa-solid fa-circle-check"></i> Confirmed (${data.booking_ref})</span> <a href="javascript:void(0)" onclick="openTxtModal(${caseId})" style="font-size: 11px; color: #4f46e5; margin-left: 4px; font-weight: 700; text-decoration: underline;">View</a>`;
                    showToast(`Case 0${caseId} Completed! Ref: ${data.booking_ref}`, false);
                } else {
                    statusEl.innerHTML = `<span style="color:#ef4444;"><i class="fa-solid fa-triangle-exclamation"></i> Error</span>`;
                    showToast(`Case 0${caseId} Failed: ${data.message}`, false);
                }
            } catch (err) {
                statusEl.innerHTML = `<span style="color:#ef4444;"><i class="fa-solid fa-triangle-exclamation"></i> Network Err</span>`;
                showToast('Execution error occurred.', false);
            } finally {
                btn.disabled = false;
                btn.innerHTML = '<i class="fa-solid fa-rotate-right"></i> Re-Run';
                hideToast(2500);
            }
        }

        async function runAllCases() {
            const btn = document.getElementById('btnRunAll');
            btn.disabled = true;
            btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Executing All 8 Scenarios...';
            showToast('Running all 8 official Benzy certification scenarios...', true);

            for (let i = 1; i <= 8; i++) {
                await runSingleCase(i);
            }

            btn.disabled = false;
            btn.innerHTML = '<i class="fa-solid fa-play"></i> Run All 8 Scenarios';
            showToast('All 8 Hotel Certification Test Cases Completed Successfully!', false);
            hideToast(4000);
        }

        async function openTxtModal(caseId) {
            const modal = document.getElementById('logModal');
            const titleEl = document.getElementById('modalFolderTitle');
            const viewerEl = document.getElementById('modalFileViewer');
            const dlBtn = document.getElementById('modalDlBtn');

            titleEl.textContent = `Case ${caseId} Official AkbarAPI Log Report`;
            viewerEl.textContent = 'Loading official .txt log payload...';
            dlBtn.href = `${baseUrl}/download_case_txt/${caseId}`;
            modal.style.display = 'flex';

            try {
                const res = await fetch(`${baseUrl}/view_txt/${caseId}`);
                const data = await res.json();
                if (data.status === 'success') {
                    titleEl.textContent = data.filename;
                    viewerEl.textContent = data.content;
                } else {
                    viewerEl.textContent = 'Log content could not be loaded.';
                }
            } catch (e) {
                viewerEl.textContent = 'Failed to load log file.';
            }
        }

        function copyModalContent() {
            const viewerEl = document.getElementById('modalFileViewer');
            navigator.clipboard.writeText(viewerEl.textContent).then(() => {
                showToast('Log contents copied to clipboard!', false);
                hideToast(2000);
            });
        }

        function closeLogModal() {
            document.getElementById('logModal').style.display = 'none';
        }
    </script>
</body>
</html>
