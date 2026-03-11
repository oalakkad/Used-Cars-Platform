<?php $pageTitle = 'Login'; ?>
<div class="auth-page">
    <div class="auth-card fade-in">
        <div class="auth-card-header">
            <a href="<?= baseUrl() ?>" class="nav-logo" style="justify-content:center;margin-bottom:24px;">
                <i class="fas fa-car-side"></i> AutoMarket
            </a>
            <h1>Welcome Back</h1>
            <p>Sign in to your account to continue</p>
        </div>

        <?php if (!empty($error)): ?>
            <div class="alert alert-error"><i class="fas fa-exclamation-circle"></i>
                <?= sanitize($error) ?>
            </div>
        <?php endif; ?>
        <?php if (!empty($success)): ?>
            <div class="alert alert-success"><i class="fas fa-check-circle"></i>
                <?= sanitize($success) ?>
            </div>
        <?php endif; ?>

        <form action="<?= baseUrl('login') ?>" method="POST">
            <div class="form-group">
                <label class="form-label" for="email">Email Address</label>
                <input type="email" id="email" name="email" class="form-input" placeholder="you@example.com" required
                    autofocus>
            </div>
            <div class="form-group">
                <label class="form-label" for="password">Password</label>
                <input type="password" id="password" name="password" class="form-input" placeholder="••••••••" required>
            </div>
            <button type="submit" class="btn btn-primary btn-lg" style="width:100%;margin-top:8px;">
                <i class="fas fa-sign-in-alt"></i> Sign In
            </button>
        </form>

        <div class="auth-footer">
            Don't have an account? <a href="<?= baseUrl('register') ?>">Create one</a>
        </div>
    </div>
</div>

<link rel="stylesheet" href="<?= asset('css/style.css') ?>">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<link
    href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Outfit:wght@400;500;600;700;800&display=swap"
    rel="stylesheet">
<script src="<?= asset('js/app.js') ?>"></script>