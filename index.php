<?php
require __DIR__ . '/auth/auth.php';
$login = getUserLogin();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Главная страница</title>
    <link rel="stylesheet" href="CSS/style.css">
</head>
<body>
    <header>
        <h1>Тайский рай</h1>
        <div class="auth-block">
            <?php if ($login === null): ?>
                <a href="auth/login.php">Войти</a>
            <?php else: ?>
                <span class="welcome-text">Добро пожаловать, <?= htmlspecialchars($login) ?></span>
                <a href="auth/logout.php">Выйти</a>
            <?php endif; ?>
        </div>
    </header>
    <main>
        <div class="container">
            <h2>Добро пожаловать в наш спа-салон!</h2>
            <p>Забудьте о визах, багаже и утомительных перелетах, о языковых барьерах и акклиматизации, позвольте себе отпуск, не покидая своего города! Прямо здесь, в Томске, в холодной Сибири, расположен уголок райского наслаждения для души и тела – SPA-салон «Тайский рай». Это удивительное место, где восточные методики оздоровления и омоложения принесут Вам массу приятных впечатлений.</p>
            <?php if ($login !== null): ?>
                <div class="user-content">
                    <h3>Специальный контент для авторизованных пользователей</h3>
                    <p>Привет, <?= htmlspecialchars($login) ?>! Рады видеть вас снова!</p>
                    </div>
            <?php else: ?>
                <div class="guest-content">
                    <p>Для доступа ко всем возможностям сайта, пожалуйста, <a href="auth/login.php">авторизуйтесь</a>.</p>
                </div>
            <?php endif; ?>
        </div>
    </main>
    <footer>
        <div class="footer-content">
        <div class="footer-columns">
            <div class="footer-column contacts-column">
                    <div class="contacts-section">
            <h3>Контакты</h3>
            <p><strong>Адрес:</strong> г. Томск, ул. Учебная, 11111111</p>
            <p><strong>Телефон:</strong> +7 (3822) 11-11-11-11</p>
            <p><strong>Email:</strong> muay_thai@mail.ru</p>
        </div>
        </div>

        <div class="footer-column schedule-column">
            <div class="schedule-section">
            <h3>График работы</h3>
            <p>Ежедневно: 10:00–21:00</p>
        </div>
        </div>
        </footer>
</body>
</html>