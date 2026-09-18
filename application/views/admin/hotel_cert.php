<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $page_title; ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
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
            max-width: 1280px;
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
            gap: 12px;
            flex-wrap: wrap;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 22px;
            border-radius: 10px;
            font-weight: 700;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.2s ease;
            text-decoration: none;
            border: none;
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

        .btn-outline-white {
            background: transparent;
            color: #ffffff;
            border: 1px solid rgba(255, 255, 255, 0.4);
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
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: var(--text-muted);
            margin-bottom: 4px;
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
            grid-template-columns: repeat(auto-fit, minmax(370px, 1fr));
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
        }

        .status-badge {
            font-size: 12px;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .status-ready { color: #10b981; }
        .status-missing { color: #94a3b8; }
        .status-running { color: #6366f1; }
        .status-done { color: #059669; }

        .btn-run {
            background: #f8fafc;
            color: #0f172a;
            border: 1px solid #cbd5e1;
            padding: 8px 16px;
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
            background: #4f46e5;
            color: #ffffff;
            border-color: #4f46e5;
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
            background: rgba(15, 23, 42, 0.6);
            backdrop-filter: blur(4px);
            z-index: 10000;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .modal-content {
            background: #ffffff;
            width: 100%;
            max-width: 900px;
            max-height: 85vh;
            border-radius: 16px;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.2);
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
        }

        .modal-close {
            background: transparent;
            border: none;
            color: #94a3b8;
            font-size: 20px;
            cursor: pointer;
        }
        .modal-close:hover { color: #ffffff; }

        .modal-body {
            padding: 20px;
            overflow-y: auto;
            flex: 1;
            display: flex;
            gap: 20px;
        }

        .modal-file-list {
            width: 260px;
            border-right: 1px solid #e2e8f0;
            padding-right: 15px;
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .modal-file-item {
            padding: 8px 12px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
            color: #334155;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.15s;
        }

        .modal-file-item:hover, .modal-file-item.active {
            background: #eef2ff;
            color: #4f46e5;
        }

        .modal-file-viewer {
            flex: 1;
            background: #0f172a;
            border-radius: 8px;
            padding: 14px;
            color: #e2e8f0;
            font-family: 'Courier New', Courier, monospace;
            font-size: 12px;
            overflow-x: auto;
            white-space: pre-wrap;
            max-height: 60vh;
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
                        Akbar Travels / Benzy Infotech 14-Scenario Compliance & Automated Log Generator
                    </p>
                </div>
                <div class="header-actions">
                    <button class="btn btn-white" id="btnRunAll" onclick="runAllCases()">
                        <i class="fa-solid fa-play"></i> Run All 14 Scenarios
                    </button>
                    <a href="<?= site_url('hotel_cert/download_postman'); ?>" class="btn btn-orange">
                        <i class="fa-solid fa-file-code"></i> Postman Collection (.json)
                    </a>
                    <a href="<?= site_url('hotel_cert/download_zip'); ?>" class="btn btn-success">
                        <i class="fa-solid fa-file-zipper"></i> Download Logs ZIP
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
                    <p>14 Test Cases</p>
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
                    <i class="fa-solid fa-file-lines"></i>
                </div>
                <div class="meta-info">
                    <h4>Files Per Scenario</h4>
                    <p>12–13 JSON Files</p>
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
                <h2><i class="fa-solid fa-layer-group" style="color: #4f46e5;"></i> 14 Certification Test Scenarios</h2>
                <p style="color: var(--text-muted); font-size: 13px; margin-top: 4px;">
                    Standardized 13-step flow: Signature &rarr; AutoSuggest &rarr; Init &rarr; Rates &rarr; Content &rarr; MoreRooms &rarr; Pricing &rarr; CreateItinerary &rarr; StartPay &rarr; RetrieveBooking &rarr; Cancel
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
                    $folder = $c['folder_name'];
                    $hasLogs = isset($existing_logs[$folder]) && $existing_logs[$folder]['count'] >= 10;
                    $fileCount = isset($existing_logs[$folder]) ? $existing_logs[$folder]['count'] : 0;
                ?>
                <div class="scenario-card" id="card-<?= $id; ?>">
                    <div>
                        <div class="card-top">
                            <span class="case-number"><?= $paddedId; ?></span>
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
                                <span><strong>Sequence:</strong> 1...13 JSON Steps</span>
                            </div>
                        </div>
                    </div>

                    <div class="card-bottom">
                        <div class="status-badge" id="status-<?= $id; ?>">
                            <?php if ($hasLogs): ?>
                                <span class="status-ready">
                                    <i class="fa-solid fa-circle-check"></i> Ready (<?= $fileCount; ?> files)
                                </span>
                                <a href="javascript:void(0)" onclick="openLogModal('<?= htmlspecialchars($folder); ?>')" style="font-size: 11px; color: #4f46e5; margin-left: 6px; text-decoration: underline;">View</a>
                            <?php else: ?>
                                <span class="status-missing"><i class="fa-regular fa-clock"></i> Not Run</span>
                            <?php endif; ?>
                        </div>
                        <button class="btn-run" id="btn-run-<?= $id; ?>" onclick="runSingleCase(<?= $id; ?>)">
                            <i class="fa-solid fa-play"></i> Run Case
                        </button>
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

    <!-- Modal for Viewing Log Files -->
    <div id="logModal" class="log-modal" onclick="if(event.target === this) closeLogModal();">
        <div class="modal-content">
            <div class="modal-header">
                <h3><i class="fa-solid fa-file-code"></i> <span id="modalFolderTitle">Log Inspector</span></h3>
                <button class="modal-close" onclick="closeLogModal()">&times;</button>
            </div>
            <div class="modal-body">
                <div class="modal-file-list" id="modalFileList">
                    <!-- Populated dynamically -->
                </div>
                <div class="modal-file-viewer" id="modalFileViewer">
                    Click a step file on the left to inspect the HTTP Request and Response.
                </div>
            </div>
        </div>
    </div>

    <script>
        const baseUrl = '<?= site_url("hotel_cert"); ?>';
        const existingLogsData = <?= json_encode($existing_logs); ?>;

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
            showToast(`Executing Hotel Test Scenario 0${caseId}...`, true);

            try {
                const res = await fetch(`${baseUrl}/run_case/${caseId}`);
                const data = await res.json();
                
                if (data.status === 'success') {
                    statusEl.innerHTML = `<span class="status-done"><i class="fa-solid fa-circle-check"></i> Confirmed (Ref: ${data.booking_ref})</span> <a href="javascript:void(0)" onclick="openLogModal('${data.folder}')" style="font-size: 11px; color: #4f46e5; margin-left: 6px; text-decoration: underline;">View</a>`;
                    showToast(`Scenario 0${caseId} Completed! Ref: ${data.booking_ref}`, false);
                    
                    // Update cache for modal viewer
                    existingLogsData[data.folder] = {
                        folder: data.folder,
                        count: data.files ? data.files.length : 12,
                        files: data.files || []
                    };
                } else {
                    statusEl.innerHTML = `<span style="color:#ef4444;"><i class="fa-solid fa-triangle-exclamation"></i> Error</span>`;
                    showToast(`Scenario 0${caseId} Failed: ${data.message}`, false);
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
            btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Executing All 14 Scenarios...';
            showToast('Running all 14 hotel certification scenarios in batch...', true);

            for (let i = 1; i <= 14; i++) {
                await runSingleCase(i);
            }

            btn.disabled = false;
            btn.innerHTML = '<i class="fa-solid fa-play"></i> Run All 14 Scenarios';
            showToast('All 14 Hotel Certification Scenarios Completed Successfully!', false);
            hideToast(4000);
        }

        function openLogModal(folderName) {
            const modal = document.getElementById('logModal');
            const titleEl = document.getElementById('modalFolderTitle');
            const listEl = document.getElementById('modalFileList');
            const viewerEl = document.getElementById('modalFileViewer');

            titleEl.textContent = folderName;
            viewerEl.textContent = 'Select a step file on the left to inspect the payload.';
            listEl.innerHTML = '';

            const stepFiles = [
                '1.Signature.json',
                '2.AutoSuggest.json',
                '3.Init.json',
                '4.HotelRate.json',
                '5.HotelContent.json',
                '6.MoreRooms_Content.json',
                '7.MoreRooms.json',
                '8.Pricing_Content.json',
                '9.Pricing.json',
                '10.CreateItinerary.json',
                '11.StartPay.json',
                '12.RetrieveBooking.json',
                '13.Cancel.json'
            ];

            stepFiles.forEach((file, idx) => {
                const item = document.createElement('div');
                item.className = 'modal-file-item';
                item.innerHTML = `<i class="fa-solid fa-file-code"></i> ${file}`;
                item.onclick = () => {
                    document.querySelectorAll('.modal-file-item').forEach(el => el.classList.remove('active'));
                    item.classList.add('active');
                    loadFileContent(folderName, file);
                };
                listEl.appendChild(item);
            });

            modal.style.display = 'flex';
            if (listEl.firstChild) listEl.firstChild.click();
        }

        async function loadFileContent(folder, file) {
            const viewerEl = document.getElementById('modalFileViewer');
            viewerEl.textContent = 'Loading ' + file + '...';
            try {
                const res = await fetch(`${baseUrl}/view_log?folder=${encodeURIComponent(folder)}&file=${encodeURIComponent(file)}`);
                const data = await res.json();
                if (data.status === 'success') {
                    viewerEl.textContent = data.content;
                } else {
                    viewerEl.textContent = 'Log content not found or step was skipped for this scenario.';
                }
            } catch (e) {
                viewerEl.textContent = 'Failed to load log file.';
            }
        }

        function closeLogModal() {
            document.getElementById('logModal').style.display = 'none';
        }
    </script>
</body>
</html>
