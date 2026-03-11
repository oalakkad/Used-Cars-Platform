<?php
/**
 * AuthController - Handles user registration, login, and logout
 */
class AuthController
{

    private UserModel $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function loginForm(): void
    {
        $error = flash('error');
        $success = flash('success');
        require __DIR__ . '/../views/auth/login.php';
    }

    public function loginPost(): void
    {
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if (empty($email) || empty($password)) {
            flash('error', 'Please fill in all fields.');
            redirect(baseUrl('login'));
        }

        $user = $this->userModel->findByEmail($email);
        if (!$user || !$this->userModel->verifyPassword($password, $user['password_hash'])) {
            flash('error', 'Invalid email or password.');
            redirect(baseUrl('login'));
        }

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_email'] = $user['email'];

        flash('success', 'Welcome back, ' . $user['name'] . '!');
        redirect(baseUrl('dashboard'));
    }

    public function registerForm(): void
    {
        $error = flash('error');
        require __DIR__ . '/../views/auth/register.php';
    }

    public function registerPost(): void
    {
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';

        if (empty($name) || empty($email) || empty($password)) {
            flash('error', 'Please fill in all fields.');
            redirect(baseUrl('register'));
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            flash('error', 'Please enter a valid email address.');
            redirect(baseUrl('register'));
        }

        if (strlen($password) < 6) {
            flash('error', 'Password must be at least 6 characters.');
            redirect(baseUrl('register'));
        }

        if ($password !== $confirmPassword) {
            flash('error', 'Passwords do not match.');
            redirect(baseUrl('register'));
        }

        if ($this->userModel->emailExists($email)) {
            flash('error', 'An account with this email already exists.');
            redirect(baseUrl('register'));
        }

        $userId = $this->userModel->create($name, $email, $password);

        $_SESSION['user_id'] = $userId;
        $_SESSION['user_name'] = $name;
        $_SESSION['user_email'] = $email;

        flash('success', 'Account created successfully!');
        redirect(baseUrl('dashboard'));
    }

    public function logout(): void
    {
        session_destroy();
        redirect(baseUrl('login'));
    }
}
