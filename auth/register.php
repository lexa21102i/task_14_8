<?php
define('SECURE_ACCESS', true);
require __DIR__ . '/auth.php';

$error = '';
$success = '';

if (!empty($_POST)) {
    $login = trim($_POST['login'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';
    
    if (strlen($login) < 3) {
        $error = 'Логин должен содержать не менее 3 символов';
    } elseif (strlen($password) < 6) {
        $error = 'Пароль должен содержать не менее 6 символов';
    } elseif ($password !== $confirmPassword) {
        $error = 'Пароли не совпадают';
    } elseif (!preg_match('/^[a-zA-Z0-9_]+$/', $login)) {
        $error = 'Логин может содержать только буквы, цифры и подчеркивание';
    } else {
        if (registerUser($login, $password)) {
            $success = 'Регистрация прошла успешно! Теперь вы можете войти.';
            // Автоматически авторизуем пользователя
            setcookie('login', $login, time() + 3600 * 24 * 30, '/');
            setcookie('password', $password, time() + 3600 * 24 * 30, '/');
            header('Refresh: 2; URL=/14_8/index.php');
        } else {
            $error = 'Пользователь с таким логином уже существует';
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Регистрация</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 500px; margin: 50px auto; padding: 20px; }
        .error { color: red; }
        .success { color: green; }
        input[type="text"], input[type="password"] { width: 100%; padding: 8px; margin: 5px 0 15px; }
        input[type="submit"] { background: #8b4513; color: white; padding: 10px 20px; border: none; cursor: pointer; }
    </style>
</head>
<body>
    <h2>Регистрация</h2>
    
    <?php if ($error): ?>
        <div class="error"><?= $error ?></div>
    <?php endif; ?>
    
    <?php if ($success): ?>
        <div class="success"><?= $success ?></div>
    <?php endif; ?>
    
    <form method="post">
        <label for="login">Логин:</label>
        <input type="text" name="login" id="login" required pattern="[a-zA-Z0-9_]+" title="Только буквы, цифры и подчеркивание">
        
        <label for="password">Пароль:</label>
        <input type="password" name="password" id="password" required minlength="6">
        
        <label for="confirm_password">Подтвердите пароль:</label>
        <input type="password" name="confirm_password" id="confirm_password" required>
        
        <input type="submit" value="Зарегистрироваться">
    </form>
    
    <p>Уже есть аккаунт? <a href="login.php">Войти</a></p>
</body>
</html>