<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Franchise Store Login - Voyogo B2B</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Outfit:wght@600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Inter', sans-serif; }
        body {
            background: linear-gradient(135deg, #09204b 0%, #0d3470 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .login-card {
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            width: 100%;
            max-width: 440px;
            overflow: hidden;
        }
        .login-header {
            background: #09204b;
            padding: 32px 30px;
            text-align: center;
            color: #ffffff;
            position: relative;
        }
        .login-header img {
            max-height: 44px;
            margin-bottom: 12px;
        }
        .login-header h2 {
            font-family: 'Outfit', sans-serif;
            font-size: 20px;
            font-weight: 700;
            letter-spacing: -0.5px;
        }
        .login-header p {
            font-size: 13px;
            color: #93c5fd;
            margin-top: 4px;
        }
        .badge-b2b {
            display: inline-block;
            background: #78B722;
            color: #ffffff;
            padding: 3px 10px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            margin-top: 6px;
            letter-spacing: 0.5px;
        }
        .login-body {
            padding: 32px 30px;
        }
        .alert {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #991b1b;
            padding: 12px 14px;
            border-radius: 8px;
            font-size: 13px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .alert-success {
            background: #f0fdf4;
            border-color: #bbf7d0;
            color: #166534;
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
        }
        .input-group i {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 14px;
        }
        .input-group input {
            width: 100%;
            padding: 12px 14px 12px 42px;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            font-size: 14px;
            color: #0f172a;
            outline: none;
            transition: all 0.2s;
        }
        .input-group input:focus {
            border-color: #09204b;
            box-shadow: 0 0 0 3px rgba(9, 32, 75, 0.1);
        }
        .btn-submit {
            width: 100%;
            background: #78B722;
            color: #ffffff;
            border: none;
            padding: 13px;
            border-radius: 8px;
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        .btn-submit:hover {
            background: #6aa31e;
        }
        .login-footer {
            padding: 18px 30px;
            background: #f8fafc;
            border-top: 1px solid #e2e8f0;
            text-align: center;
            font-size: 12px;
            color: #64748b;
        }
    </style>
</head>
<body>

<div class="login-card">
    <div class="login-header">
        <img src="<?php echo base_url('assets/images/logo.png'); ?>" alt="Voyogo">
        <h2>Franchise Store Portal</h2>
        <p>B2B Flight & Hotel Booking Engine</p>
        <span class="badge-b2b">Partner Access</span>
    </div>

    <div class="login-body">
        <?php if (!empty($error)): ?>
        <div class="alert">
            <i class="fa-solid fa-triangle-exclamation"></i>
            <span><?php echo htmlspecialchars($error); ?></span>
        </div>
        <?php endif; ?>

        <?php if ($this->session->flashdata('error')): ?>
        <div class="alert">
            <i class="fa-solid fa-triangle-exclamation"></i>
            <span><?php echo htmlspecialchars($this->session->flashdata('error')); ?></span>
        </div>
        <?php endif; ?>

        <?php if ($this->session->flashdata('success')): ?>
        <div class="alert alert-success">
            <i class="fa-solid fa-circle-check"></i>
            <span><?php echo htmlspecialchars($this->session->flashdata('success')); ?></span>
        </div>
        <?php endif; ?>

        <form method="post" action="<?php echo site_url('franchise/login'); ?>">
            <div class="form-group">
                <label>Store Username</label>
                <div class="input-group">
                    <i class="fa-solid fa-user"></i>
                    <input type="text" name="username" required placeholder="Enter assigned username" autofocus>
                </div>
            </div>

            <div class="form-group">
                <label>Store Password</label>
                <div class="input-group">
                    <i class="fa-solid fa-lock"></i>
                    <input type="password" name="password" required placeholder="Enter store password">
                </div>
            </div>

            <button type="submit" class="btn-submit">
                <i class="fa-solid fa-arrow-right-to-bracket"></i> Sign In to Store Portal
            </button>
        </form>
    </div>

    <div class="login-footer">
        Account assigned by Franchise Admin &bull; Powered by Voyogo B2B
    </div>
</div>

</body>
</html>
