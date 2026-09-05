<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Super Admin Login - Voyogo Travels</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #09204b 0%, #0d3470 50%, #1e293b 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
        }
        .login-card {
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.3);
            width: 100%;
            max-width: 440px;
            padding: 40px;
        }
        .brand-header {
            text-align: center;
            margin-bottom: 30px;
        }
        .brand-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #0d3470, #fa3a3a);
            color: #ffffff;
            border-radius: 14px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 26px;
            margin-bottom: 12px;
        }
        .btn-login-submit {
            background: linear-gradient(135deg, #0d3470, #2563eb);
            color: #ffffff;
            font-weight: 700;
            padding: 12px;
            border-radius: 8px;
            border: none;
            width: 100%;
            transition: all 0.2s;
        }
        .btn-login-submit:hover {
            opacity: 0.95;
            color: #ffffff;
        }
    </style>
</head>
<body>

<div class="login-card">
    <div class="brand-header">
        <div class="brand-icon">
            <i class="fa-solid fa-plane-lock"></i>
        </div>
        <h3 class="fw-bold text-dark mb-1">Voyogo Admin Portal</h3>
        <p class="text-muted small">Sign in with Super Admin credentials</p>
    </div>

    <?php if (isset($error)): ?>
        <div class="alert alert-danger py-2 small" role="alert">
            <i class="fa-solid fa-circle-exclamation me-1"></i> <?php echo htmlspecialchars($error); ?>
        </div>
    <?php endif; ?>

    <form action="<?php echo site_url('admin/login'); ?>" method="POST">
        <div class="mb-3">
            <label class="form-label fw-bold small text-secondary">Username</label>
            <div class="input-group">
                <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-user text-muted"></i></span>
                <input type="text" name="username" class="form-control bg-light border-start-0" placeholder="Enter username" required value="">
            </div>
        </div>

        <div class="mb-4">
            <label class="form-label fw-bold small text-secondary">Password</label>
            <div class="input-group">
                <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-key text-muted"></i></span>
                <input type="password" name="password" class="form-control bg-light border-start-0" placeholder="Enter password" required value="">
            </div>
        </div>

        <button type="submit" class="btn-login-submit">
            <i class="fa-solid fa-right-to-bracket me-2"></i> Log In to Dashboard
        </button>
    </form>

    <div class="text-center mt-4">
        <a href="<?php echo site_url('/'); ?>" class="text-decoration-none small text-muted"><i class="fa-solid fa-arrow-left me-1"></i> Back to Voyogo Website</a>
    </div>
</div>

</body>
</html>
