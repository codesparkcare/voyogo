<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Franchise Admin Login - Voyogo</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Outfit:wght@600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Inter', sans-serif; }
        body {
            background: linear-gradient(135deg, #061a3a 0%, #09204b 50%, #0e2d5c 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .login-card {
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 20px 50px rgba(0,0,0,0.3);
            width: 100%;
            max-width: 440px;
            overflow: hidden;
        }
        .card-header {
            background: linear-gradient(135deg, #09204b, #0d3470);
            padding: 32px 24px;
            text-align: center;
            color: #ffffff;
        }
        .card-header img {
            max-height: 48px;
            margin-bottom: 12px;
        }
        .card-header h2 {
            font-family: 'Outfit', sans-serif;
            font-size: 20px;
            letter-spacing: -0.5px;
        }
        .card-header p {
            font-size: 13px;
            color: #94a3b8;
            margin-top: 4px;
        }
        .card-body {
            padding: 32px 28px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: #334155;
            margin-bottom: 8px;
        }
        .input-group {
            position: relative;
            display: flex;
            align-items: center;
        }
        .input-group i {
            position: absolute;
            left: 14px;
            color: #94a3b8;
            font-size: 16px;
        }
        .input-group input {
            width: 100%;
            padding: 12px 14px 12px 42px;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            font-size: 14px;
            outline: none;
            transition: border-color 0.2s;
        }
        .input-group input:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37,99,235,0.1);
        }
        .btn-submit {
            width: 100%;
            padding: 13px;
            background: linear-gradient(135deg, #78B722, #4a7a12);
            color: #ffffff;
            border: none;
            border-radius: 8px;
            font-weight: 700;
            font-size: 15px;
            cursor: pointer;
            box-shadow: 0 4px 14px rgba(120,183,34,0.4);
            transition: transform 0.15s;
        }
        .btn-submit:hover {
            transform: translateY(-1px);
        }
        .alert-error {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #991b1b;
            padding: 12px 16px;
            border-radius: 8px;
            font-size: 13px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .alert-success {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            color: #166534;
            padding: 12px 16px;
            border-radius: 8px;
            font-size: 13px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

<div class="login-card">
    <div class="card-header">
        <img src="<?php echo base_url('assets/images/logo.png'); ?>" alt="Voyogo Logo">
        <h2>Franchise Admin Portal</h2>
        <p>Master Administration & Store Control</p>
    </div>

    <div class="card-body">
        <?php if (!empty($error)): ?>
        <div class="alert-error">
            <i class="fa-solid fa-circle-exclamation"></i>
            <span><?php echo htmlspecialchars($error); ?></span>
        </div>
        <?php endif; ?>

        <?php if ($this->session->flashdata('success')): ?>
        <div class="alert-success">
            <?php echo htmlspecialchars($this->session->flashdata('success')); ?>
        </div>
        <?php endif; ?>

        <form action="<?php echo site_url('franchise-admin/login'); ?>" method="POST">
            <div class="form-group">
                <label>Administrator Username</label>
                <div class="input-group">
                    <i class="fa-solid fa-user-shield"></i>
                    <input type="text" name="username" placeholder="Enter admin username" required autofocus>
                </div>
            </div>

            <div class="form-group">
                <label>Password</label>
                <div class="input-group">
                    <i class="fa-solid fa-lock"></i>
                    <input type="password" name="password" placeholder="Enter password" required>
                </div>
            </div>

            <button type="submit" class="btn-submit">
                <i class="fa-solid fa-arrow-right-to-bracket" style="margin-right: 8px;"></i> Sign In to Franchise Panel
            </button>
        </form>

        <div style="text-align: center; margin-top: 24px; font-size: 12px; color: #94a3b8;">
            &copy; <?php echo date('Y'); ?> Voyogo Travel Pvt Ltd. All rights reserved.
        </div>
    </div>
</div>

</body>
</html>
