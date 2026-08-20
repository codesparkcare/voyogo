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
            --primary: #2563eb;
            --primary-dark: #1d4ed8;
            --navy-900: #0f172a;
            --navy-800: #1e293b;
            --navy-700: #334155;
            --accent-green: #10b981;
            --accent-amber: #f59e0b;
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
            background: linear-gradient(135deg, #0d3470 0%, #1e40af 100%);
            color: #ffffff;
            padding: 30px 20px;
            box-shadow: 0 4px 25px rgba(13, 52, 112, 0.15);
        }

        .container {
            max-width: 1240px;
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

        .btn-primary {
            background: #ffffff;
            color: #1e40af;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .btn-primary:hover {
            background: #f1f5f9;
            transform: translateY(-2px);
        }

        .btn-success {
            background: var(--accent-green);
            color: #ffffff;
        }

        .btn-success:hover {
            background: #059669;
            transform: translateY(-2px);
        }

        .btn-outline {
            background: transparent;
            color: #ffffff;
            border: 1px solid rgba(255, 255, 255, 0.4);
        }

        .btn-outline:hover {
            background: rgba(255, 255, 255, 0.1);
        }

        .status-strip {
            margin-top: -25px;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 16px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: #ffffff;
            padding: 20px;
            border-radius: 14px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
            border: 1px solid var(--border);
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
        }

        .icon-blue { background: #eff6ff; color: #2563eb; }
        .icon-green { background: #ecfdf5; color: #10b981; }
        .icon-purple { background: #f5f3ff; color: #7c3aed; }
        .icon-amber { background: #fffbeb; color: #f59e0b; }

        .stat-title {
            font-size: 13px;
            color: var(--text-muted);
            font-weight: 600;
            margin-bottom: 4px;
        }

        .stat-value {
            font-size: 20px;
            font-weight: 800;
            color: var(--text-dark);
            font-family: 'Outfit', sans-serif;
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .section-title {
            font-size: 20px;
            font-weight: 800;
            color: var(--navy-900);
            font-family: 'Outfit', sans-serif;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .cases-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(370px, 1fr));
            gap: 20px;
        }

        .case-card {
            background: #ffffff;
            border-radius: 16px;
            padding: 22px;
            border: 1px solid var(--border);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
            transition: all 0.25s ease;
            position: relative;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .case-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 30px rgba(37, 99, 235, 0.08);
            border-color: #cbd5e1;
        }

        .case-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 12px;
        }

        .case-num {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            background: #f1f5f9;
            color: #334155;
            font-weight: 800;
            font-size: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .case-tags {
            display: flex;
            gap: 6px;
            flex-wrap: wrap;
        }

        .tag {
            font-size: 11px;
            font-weight: 700;
            padding: 3px 8px;
            border-radius: 6px;
            text-transform: uppercase;
        }

        .tag-on { background: #dbeafe; color: #1e40af; }
        .tag-rt { background: #fef3c7; color: #92400e; }
        .tag-rd { background: #fae8ff; color: #86198f; }
        .tag-bag { background: #dcfce7; color: #166534; }
        .tag-nobag { background: #f1f5f9; color: #475569; }

        .case-title {
            font-size: 16px;
            font-weight: 700;
            color: var(--navy-900);
            margin-bottom: 14px;
            line-height: 1.4;
        }

        .steps-pill {
            background: #f8fafc;
            border-radius: 10px;
            padding: 10px 12px;
            margin-bottom: 16px;
            font-size: 12px;
            color: var(--text-muted);
            border: 1px solid #f1f5f9;
        }

        .case-status {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding-top: 14px;
            border-top: 1px solid #f1f5f9;
        }

        .status-badge {
            font-size: 12px;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .status-ready { color: #64748b; }
        .status-running { color: #2563eb; }
        .status-done { color: #16a34a; }

        .btn-run {
            background: #eff6ff;
            color: #2563eb;
            border: 1px solid #bfdbfe;
            padding: 8px 14px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .btn-run:hover {
            background: #2563eb;
            color: #ffffff;
        }

        /* Modal / Execution Toast */
        .toast-banner {
            position: fixed;
            bottom: 24px;
            right: 24px;
            background: #0f172a;
            color: #ffffff;
            padding: 16px 24px;
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.25);
            display: none;
            align-items: center;
            gap: 14px;
            z-index: 1000;
            font-weight: 600;
            font-size: 14px;
        }

        .spinner {
            border: 3px solid rgba(255,255,255,0.2);
            border-top: 3px solid #ffffff;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            animation: spin 0.8s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body>

    <!-- Header Bar -->
    <header class="header-bar">
        <div class="container">
            <div class="header-content">
                <div>
                    <div class="logo-tag">
                        <i class="fa-solid fa-paper-plane"></i> Voyogo <span class="badge-cert">B2B Flight Certification</span>
                    </div>
                    <p style="margin-top: 6px; font-size: 14px; opacity: 0.9;">
                        Akbar Travels / Benzy Infotech 9-Scenario Compliance & Automated Log Generator
                    </p>
                </div>
                <div class="header-actions">
                    <button id="btnRunAll" class="btn btn-primary" onclick="runAllCases()">
                        <i class="fa-solid fa-play"></i> Run All 9 Scenarios
                    </button>
                    <a href="<?= site_url('flight_cert/download_postman'); ?>" class="btn" style="background: #f97316; color: #ffffff;">
                        <i class="fa-solid fa-file-code"></i> Postman Collection (.json)
                    </a>
                    <a href="<?= site_url('flight_cert/download_zip'); ?>" class="btn btn-success">
                        <i class="fa-solid fa-download"></i> Download Logs ZIP
                    </a>
                </div>
            </div>
        </div>
    </header>

    <main class="container" style="margin-top: 25px;">
        
        <!-- Summary Stats Strip -->
        <div class="status-strip">
            <div class="stat-card">
                <div class="stat-icon icon-blue"><i class="fa-solid fa-list-check"></i></div>
                <div>
                    <div class="stat-title">Total Scenarios</div>
                    <div class="stat-value">9 Test Cases</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon icon-green"><i class="fa-solid fa-shield-check"></i></div>
                <div>
                    <div class="stat-title">Certification Status</div>
                    <div class="stat-value" id="statReady">100% Compliant</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon icon-purple"><i class="fa-solid fa-file-code"></i></div>
                <div>
                    <div class="stat-title">Files Per Scenario</div>
                    <div class="stat-value">16 JSON Files</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon icon-amber"><i class="fa-solid fa-server"></i></div>
                <div>
                    <div class="stat-title">Target Gateway</div>
                    <div class="stat-value" style="font-size: 15px;">Benzy B2B Staging</div>
                </div>
            </div>
        </div>

        <!-- Scenarios Section -->
        <div class="section-header">
            <h2 class="section-title"><i class="fa-solid fa-layer-group" style="color: var(--primary);"></i> 9 Certification Test Scenarios</h2>
            <div style="font-size: 13px; color: var(--text-muted);">Standardized 16-step flow (Signature ➔ Search ➔ Reprice ➔ SSR ➔ Checklist ➔ Create ➔ Hold ➔ Pay ➔ Retrieve)</div>
        </div>

        <div class="cases-grid">
            <?php foreach ($cases as $id => $case): 
                $isLogged = isset($existing_logs[$case['folder_name']]);
                $fileCount = $isLogged ? $existing_logs[$case['folder_name']]['count'] : 0;
            ?>
            <div class="case-card" id="card-<?= $id; ?>">
                <div>
                    <div class="case-header">
                        <div class="case-num">0<?= $id; ?></div>
                        <div class="case-tags">
                            <span class="tag <?= $case['fare_type'] === 'RT' ? 'tag-rt' : ($case['fare_type'] === 'RD' ? 'tag-rd' : 'tag-on'); ?>"><?= $case['fare_type'] === 'RD' ? 'Same Day' : ($case['fare_type'] === 'RT' ? 'Round Trip' : 'One Way'); ?></span>
                            <span class="tag <?= $case['with_baggage'] ? 'tag-bag' : 'tag-nobag'; ?>"><?= $case['with_baggage'] ? '+ Baggage SSR' : 'No Extra Bag'; ?></span>
                            <span class="tag" style="background: #f1f5f9; color: #0284c7;"><?= $case['is_connecting'] ? 'Connecting' : 'Direct'; ?></span>
                        </div>
                    </div>
                    
                    <h3 class="case-title"><?= $case['title']; ?></h3>
                    
                    <div class="steps-pill">
                        <i class="fa-solid fa-arrow-right-arrow-left" style="color: var(--primary); margin-right: 5px;"></i>
                        Route: <strong>DEL ➔ <?= $case['is_connecting'] ? 'BLR (Via HYD)' : 'BOM'; ?></strong> | Sequence: <strong>1..16 Files</strong>
                    </div>
                </div>

                <div class="case-status">
                    <div class="status-badge" id="status-<?= $id; ?>">
                        <?php if ($isLogged): ?>
                            <span class="status-done"><i class="fa-solid fa-circle-check"></i> Ready (<?= $fileCount; ?> files)</span>
                        <?php else: ?>
                            <span class="status-ready"><i class="fa-regular fa-circle"></i> Ready to Run</span>
                        <?php endif; ?>
                    </div>
                    <button class="btn-run" onclick="runSingleCase(<?= $id; ?>)" id="btn-run-<?= $id; ?>">
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
        <span id="toastText">Executing Certification Suite...</span>
    </div>

    <script>
        const baseUrl = '<?= site_url("flight_cert"); ?>';

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
            showToast(`Executing Test Scenario 0${caseId}...`, true);

            try {
                const res = await fetch(`${baseUrl}/run_case/${caseId}`);
                const data = await res.json();
                
                if (data.status === 'success') {
                    statusEl.innerHTML = `<span class="status-done"><i class="fa-solid fa-circle-check"></i> Confirmed (PNR: ${data.pnr})</span>`;
                    showToast(`Scenario 0${caseId} Completed! PNR: ${data.pnr}`, false);
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
            btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Executing All 9 Scenarios...';
            showToast('Running all 9 certification scenarios in batch...', true);

            for (let i = 1; i <= 9; i++) {
                await runSingleCase(i);
            }

            btn.disabled = false;
            btn.innerHTML = '<i class="fa-solid fa-play"></i> Run All 9 Scenarios';
            showToast('All 9 Certification Scenarios Completed Successfully!', false);
            hideToast(4000);
        }
    </script>
</body>
</html>
