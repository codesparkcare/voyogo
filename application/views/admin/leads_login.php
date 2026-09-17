<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leads & Services Staff Login - Voyogo Travels</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 50%, #312e81 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            padding: 20px;
        }
        .login-card {
            background: #ffffff;
            border-radius: 20px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.4);
            width: 100%;
            max-width: 440px;
            padding: 40px;
        }
        .brand-header {
            text-align: center;
            margin-bottom: 28px;
        }
        .brand-icon {
            width: 64px;
            height: 64px;
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            color: #ffffff;
            border-radius: 18px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            margin-bottom: 14px;
            box-shadow: 0 10px 20px rgba(99, 102, 241, 0.35);
        }
        .service-pills {
            display: flex;
            justify-content: center;
            gap: 6px;
            flex-wrap: wrap;
            margin-bottom: 20px;
        }
        .service-pill {
            font-size: 11px;
            font-weight: 700;
            padding: 4px 10px;
            border-radius: 20px;
            background: #f1f5f9;
            color: #475569;
        }
        .form-control {
            border-radius: 10px;
            padding: 12px 14px;
            border: 1.5px solid #e2e8f0;
            font-weight: 500;
        }
        .form-control:focus {
            border-color: #6366f1;
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.15);
        }
        .btn-login-submit {
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            color: #ffffff;
            font-weight: 700;
            padding: 13px;
            border-radius: 10px;
            border: none;
            width: 100%;
            transition: all 0.2s;
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);
        }
        .btn-login-submit:hover {
            opacity: 0.95;
            color: #ffffff;
            transform: translateY(-1px);
        }
    </style>
</head>
<body>

<div class="login-card">
    <div class="brand-header">
        <div class="brand-icon">
            <i class="fa-solid fa-layer-group"></i>
        </div>
        <h3 class="fw-black text-dark mb-1">Voyogo Leads Portal</h3>
        <p class="text-muted small mb-3">Sign in to manage Customer Leads & Enquiries</p>
        
        <div class="service-pills">
            <span class="service-pill"><i class="fa-solid fa-passport text-primary me-1"></i> Visa</span>
            <span class="service-pill"><i class="fa-solid fa-taxi text-warning me-1"></i> Cabs</span>
            <span class="service-pill"><i class="fa-solid fa-umbrella-beach text-success me-1"></i> Holidays</span>
            <span class="service-pill"><i class="fa-solid fa-coins text-warning me-1"></i> Forex</span>
            <span class="service-pill"><i class="fa-solid fa-ship text-info me-1"></i> Cruises</span>
        </div>
    </div>

    <?php if (isset($error)): ?>
        <div class="alert alert-danger py-2 small rounded-3 border-0" role="alert" style="background: #fef2f2; color: #991b1b;">
            <i class="fa-solid fa-circle-exclamation me-1"></i> <?php echo $error; ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="<?php echo site_url('leads/login'); ?>">
        <div class="mb-3">
            <label class="form-label text-dark fw-bold small">Username</label>
            <div class="input-group">
                <span class="input-group-text bg-white border-end-0 text-muted"><i class="fa-solid fa-user"></i></span>
                <input type="text" name="username" class="form-control border-start-0 ps-0" placeholder="Enter username (e.g. leads)" required autofocus autocomplete="username">
            </div>
        </div>

        <div class="mb-4">
            <label class="form-label text-dark fw-bold small">Password</label>
            <div class="input-group">
                <span class="input-group-text bg-white border-end-0 text-muted"><i class="fa-solid fa-lock"></i></span>
                <input type="password" name="password" class="form-control border-start-0 ps-0" placeholder="••••••••" required autocomplete="current-password">
            </div>
        </div>

        <button type="submit" class="btn btn-login-submit">
            <i class="fa-solid fa-arrow-right-to-bracket me-2"></i> Sign In to Leads Portal
        </button>
    </form>

    <div class="text-center mt-4 pt-3 border-top">
        <a href="<?php echo site_url('admin/login'); ?>" class="text-muted small text-decoration-none">
            <i class="fa-solid fa-shield-halved me-1"></i> Switch to Super Admin Login
        </a>
    </div>
</div>

</body>
</html>
