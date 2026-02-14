<?php
if (!empty($_POST)) {
    require __DIR__ . '/auth.php';

    $login = $_POST['login'] ?? '';
    $password = $_POST['password'] ?? '';

    if (checkAuth($login, $password)) {
        // Устанавливаем cookies с временем жизни 30 дней
        setcookie('login', $login, time() + 3600 * 24 * 30, '/');
        setcookie('password', $password, time() + 3600 * 24 * 30, '/');
        header('Location: /14_8/index.php');
    } else {
        $error = 'Ошибка авторизации';
    }
}
?>
<html>
<head>
    <title>Форма авторизации</title>
    <style>
        .register-link {
            margin-top: 20px;
            text-align: center;
        }
    </style>
</head>
<body>

<?php if (isset($error)): ?>
<span style="color: red;">
    <?= $error ?>
</span>
<?php endif; ?>

<form action="login.php" method="post">
    <label for="login">Имя пользователя: </label><input type="text" name="login" id="login" required>
    <br>
    <label for="password">Пароль: </label><input type="password" name="password" id="password" required>
    <br>
    <input type="submit" value="Войти">
</form>

<div class="register-link">
    <p>Нет аккаунта? <a href="register.php">Зарегистрироваться</a></p>
</div>

</body>
</html>