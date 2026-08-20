<?php
/**
 * Voyogo - Benzy Infotech / Akbar Travels Live Static IP & API Connectivity Tester
 * 
 * Access via: http://your-server-ip-or-domain/voyogo/tools/test_benzy_live.php
 */

header('Content-Type: text/html; charset=utf-8');

// 1. Detect Outbound Public IP
$outboundIp = 'Unknown';
$ch = curl_init('https://api.ipify.org');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 5);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
$ipRes = curl_exec($ch);
if (!empty($ipRes)) {
    $outboundIp = trim($ipRes);
}
curl_close($ch);

// Credentials
$credentials = array(
    "MerchantID" => "300",
    "ApiKey"     => "kXAY9yHARK",
    "ClientID"   => "bitest",
    "Password"   => "staging@1",
    "AgentCode"  => "",
    "BrowserKey" => "caecd3cd30225512c1811070dce615c1",
    "Key"        => "ef20-925c-4489-bfeb-236c8b406f7e"
);

// 2. Test Signature Generation
$sigUrl = 'https://b2bapiutils.benzyinfotech.com/Utils/Signature';
$ch = curl_init($sigUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($credentials));
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
curl_setopt($ch, CURLOPT_TIMEOUT, 15);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

$sigStart = microtime(true);
$sigResponse = curl_exec($ch);
$sigTime = round(microtime(true) - $sigStart, 2);
$sigHttpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$sigErr = curl_error($ch);
curl_close($ch);

$sigData = json_decode($sigResponse, true);
$token = $sigData['Token'] ?? $sigData['TokenId'] ?? null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Benzy Infotech Static IP & API Connectivity Test - Voyogo</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background: #0f172a; color: #f8fafc; padding: 40px 20px; line-height: 1.6; }
        .container { max-width: 900px; margin: 0 auto; background: #1e293b; padding: 30px; border-radius: 14px; border: 1px solid #334155; }
        h1 { margin-top: 0; color: #38bdf8; font-size: 24px; }
        .badge { padding: 4px 10px; border-radius: 6px; font-weight: 700; font-size: 12px; }
        .badge-success { background: #065f46; color: #6ee7b7; }
        .badge-error { background: #991b1b; color: #fca5a5; }
        .box { background: #0f172a; padding: 18px; border-radius: 10px; border: 1px solid #334155; margin-bottom: 20px; word-break: break-all; }
        .code-pre { background: #020617; padding: 14px; border-radius: 8px; font-size: 12px; color: #a5f3fc; overflow-x: auto; max-height: 250px; }
        .btn { display: inline-block; background: #2563eb; color: #fff; padding: 10px 18px; border-radius: 8px; text-decoration: none; font-weight: 700; font-size: 14px; margin-top: 10px; }
        .btn:hover { background: #1d4ed8; }
    </style>
</head>
<body>
<div class="container">
    <h1><i class="fa-solid fa-server"></i> Benzy Infotech API Connectivity & Static IP Tester</h1>
    <p style="color: #94a3b8;">Use this diagnostic tool on your live server to verify if your server's Static IP is properly whitelisted by Benzy Infotech.</p>

    <!-- Outbound IP -->
    <div class="box">
        <h3 style="margin-top: 0; color: #f1f5f9;">1. Server Outbound Public IP</h3>
        <p style="font-size: 20px; font-weight: 800; color: #38bdf8; margin: 5px 0;">
            <?php echo htmlspecialchars($outboundIp); ?>
        </p>
        <p style="font-size: 13px; color: #94a3b8; margin: 0;">
            Share this exact IP address with the Akbar Travels / Benzy Infotech support team for IP Whitelisting.
        </p>
    </div>

    <!-- Signature Token Status -->
    <div class="box">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <h3 style="margin: 0; color: #f1f5f9;">2. Signature / Bearer Token Generation</h3>
            <?php if (!empty($token)): ?>
                <span class="badge badge-success">HTTP <?php echo $sigHttpCode; ?> OK (<?php echo $sigTime; ?>s)</span>
            <?php else: ?>
                <span class="badge badge-error">HTTP <?php echo $sigHttpCode; ?> Failed / Not Whitelisted</span>
            <?php endif; ?>
        </div>
        <p style="font-size: 13px; color: #94a3b8; margin-top: 6px;">Endpoint: <code><?php echo $sigUrl; ?></code></p>
        
        <?php if (!empty($token)): ?>
            <p style="color: #4ade80; font-weight: 700; font-size: 14px;">✓ Static IP is Whitelisted and Token generated successfully!</p>
            <div class="code-pre"><?php echo htmlspecialchars(substr($token, 0, 100) . '...'); ?></div>
        <?php else: ?>
            <p style="color: #f87171; font-weight: 700; font-size: 14px;">✗ Could not obtain token. If you get a connection timeout or 403/500, please ensure IP <strong><?php echo htmlspecialchars($outboundIp); ?></strong> is whitelisted in Benzy Infotech's firewall.</p>
            <div class="code-pre"><?php echo htmlspecialchars($sigResponse ?: $sigErr ?: 'No response received.'); ?></div>
        <?php endif; ?>
    </div>

    <!-- Endpoints Quick Reference -->
    <div class="box">
        <h3 style="margin-top: 0; color: #f1f5f9;">3. Configured API Endpoints Quick Reference</h3>
        <table style="width: 100%; border-collapse: collapse; font-size: 13px; color: #cbd5e1;">
            <tr style="border-bottom: 1px solid #334155;">
                <td style="padding: 8px 0; font-weight: 700; color: #93c5fd;">Flight Utils / Signature</td>
                <td><code>https://b2bapiutils.benzyinfotech.com/Utils/Signature</code></td>
            </tr>
            <tr style="border-bottom: 1px solid #334155;">
                <td style="padding: 8px 0; font-weight: 700; color: #93c5fd;">Flight Express Search</td>
                <td><code>https://b2bapiflights.benzyinfotech.com/flights/ExpressSearch</code></td>
            </tr>
            <tr style="border-bottom: 1px solid #334155;">
                <td style="padding: 8px 0; font-weight: 700; color: #93c5fd;">Flight Results (GetExpSearch)</td>
                <td><code>https://b2bapiflights.benzyinfotech.com/flights/GetExpSearch</code></td>
            </tr>
            <tr style="border-bottom: 1px solid #334155;">
                <td style="padding: 8px 0; font-weight: 700; color: #93c5fd;">Hotel Search</td>
                <td><code>https://travelportalapi.benzyinfotech.com/Hotels/Search</code></td>
            </tr>
            <tr>
                <td style="padding: 8px 0; font-weight: 700; color: #93c5fd;">Hotel Itinerary</td>
                <td><code>https://b2bapihotels.benzyinfotech.com/Hotels/Itinerary</code></td>
            </tr>
        </table>
    </div>

    <div style="text-align: center;">
        <a href="javascript:location.reload();" class="btn">Re-Test Connectivity</a>
        <a href="../index.php/flight_cert" class="btn" style="background: #059669; margin-left: 10px;">Open 9-Scenario Certification Suite</a>
    </div>
</div>
</body>
</html>
