<?php $pageTitle = 'Register'; ?>
<div class="auth-page">
    <div class="auth-card fade-in">
        <div class="auth-card-header">
            <a href="<?= baseUrl() ?>" class="nav-logo" style="justify-content:center;margin-bottom:24px;">
                <i class="fas fa-car-side"></i> AutoMarket
            </a>
            <h1>Create Account</h1>
            <p>Join AutoMarket to start buying and selling cars</p>
        </div>

        <?php if (!empty($error)): ?>
            <div class="alert alert-error"><i class="fas fa-exclamation-circle"></i>
                <?= sanitize($error) ?>
            </div>
        <?php endif; ?>

        <form action="<?= baseUrl('register') ?>" method="POST">
            <div class="form-group">
                <label class="form-label" for="name">Full Name</label>
                <input type="text" id="name" name="name" class="form-input" placeholder="John Doe" required autofocus>
            </div>
            <div class="form-group">
                <label class="form-label" for="email">Email Address</label>
                <input type="email" id="email" name="email" class="form-input" placeholder="you@example.com" required>
            </div>
            <div class="form-group">
                <label class="form-label" for="password">Password</label>
                <input type="password" id="password" name="password" class="form-input"
                    placeholder="At least 6 characters" required minlength="6">
            </div>
            <div class="form-group">
                <label class="form-label" for="confirm_password">Confirm Password</label>
                <input type="password" id="confirm_password" name="confirm_password" class="form-input"
                    placeholder="Repeat your password" required>
            </div>
            <button type="submit" class="btn btn-primary btn-lg" style="width:100%;margin-top:8px;">
                <i class="fas fa-user-plus"></i> Create Account
            </button>
        </form>

        <div class="auth-footer">
            Already have an account? <a href="<?= baseUrl('login') ?>">Sign in</a>
        </div>
    </div>
</div>

<link rel="stylesheet" href="<?= asset('css/style.css') ?>">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<link
    href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700;800;900&family=Inter:wght@300;400;500;600;700;800&display=swap"
    rel="stylesheet">
<script src="<?= asset('js/app.js') ?>"></script>
<script>initPasswordStrength('password');</script>