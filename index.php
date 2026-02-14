<?php
require __DIR__ . '/auth/auth.php'; 
require __DIR__ . '/data/birthdays.php';
$login = getUserLogin();
// Обработка сохранения даты рождения (в сессию)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['birthday']) && $login) {
    saveUserBirthday($login, $_POST['birthday']);
    header('Location: index.php');
    exit;
}

// Получаем информацию о дне рождения
$birthdayInfo = null;
$daysUntilBirthday = null;
$isBirthday = false;

if ($login) {
    $birthdayInfo = getUserBirthday($login);
    if ($birthdayInfo) {
        $daysUntilBirthday = getDaysUntilBirthday($birthdayInfo);
        $isBirthday = (date('m-d') === date('m-d', strtotime($birthdayInfo)));
    }
}

// Получаем информацию о персональной скидке
$promoInfo = null;
$timeLeft = null;
$discountPercent = 0;

if ($login) {
    $promoInfo = getUserPromo($login);
    $timeLeft = getTimeLeft($promoInfo);
    $discountPercent = 20;
    if ($isBirthday) {
        $discountPercent = 25;
    }
}
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
    <?php if ($login !== null && !$birthdayInfo): ?>
        <div id="birthdayModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Укажите вашу дату рождения</h2>
            <p>Чтобы мы могли поздравлять вас с днём рождения и дарить персональные скидки!</p>
            <form method="POST" action="">
                <input type="date" name="birthday" required>
                <div class="modal-buttons">
                <button type="submit" class="btn-submit">Сохранить</button>
                <button type="button" class="btn-later" onclick="document.getElementById('birthdayModal').style.display='none'">Напомнить позже</button>
                </div>
            </form>
        </div>
    </div>
    <?php endif; ?>

    <?php if ($login !== null && $isBirthday): ?>
        <div class="birthday-banner">
        <div class="birthday-content">
            <h2>🎉 С днём рождения, <?= htmlspecialchars($login) ?>! 🎉</h2>
            <p>В честь вашего праздника дарим <strong>дополнительную скидку 5%</strong>!</p>
            <p class="total-discount">Ваша персональная скидка сегодня: <strong>25%</strong> на все услуги!</p>
            <div class="birthday-gift">
                <span class="gift-icon">🎁</span>
                <span class="discount-code">Промокод: HAPPY25</span>
            </div>
        </div>
    </div>
    <?php elseif ($login !== null && $birthdayInfo): ?>
        <!-- Информация о дне рождения -->
    <div class="birthday-info">
        <?php if ($daysUntilBirthday > 0): ?>
            <p>🎂 До вашего дня рождения осталось: <strong><?= $daysUntilBirthday ?></strong> <?= getDayWord($daysUntilBirthday) ?>. В этот день вы получите +5% к вашей персональной скидке!</p>
        <?php endif; ?>
    </div>
    <?php endif; ?>

    <?php if ($login !== null && $promoInfo): ?>
    <!-- Персональная акция с таймером -->
    <div class="promo-banner">
        <div class="promo-content">
            <h2>🔥 Персональная скидка только для вас! 🔥</h2>
            <p>Ваша персональная скидка: <strong><?= $discountPercent ?>%</strong> на любую программу спа-салона!</p>
            <div class="countdown-timer">
                <div class="timer-label">До окончания акции осталось:</div>
                <div class="timer-display">
                    <div class="timer-block">
                        <span class="timer-number" id="hours"><?= $timeLeft['hours'] ?></span>
                        <span class="timer-text">часов</span>
                    </div>
                    <div class="timer-block">
                        <span class="timer-number" id="minutes"><?= $timeLeft['minutes'] ?></span>
                        <span class="timer-text">минут</span>
                    </div>
                    <div class="timer-block">
                        <span class="timer-number" id="seconds"><?= $timeLeft['seconds'] ?></span>
                        <span class="timer-text">секунд</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
    <main>
        <div class="container">
            <div class="spa-image">
                <img src="images/2.jpg" alt="Тайский спа-салон" style="width: 100%; max-height: 400px; object-fit: cover; border-radius: 10px; margin-bottom: 30px;">
            </div>
            <?php if ($login === null): ?>
            <!-- Акция для неавторизованных пользователей -->
            <div class="guest-promo">
                <h3>🎁 Специальное предложение!</h3>
                <p><a href="auth/login.php">Авторизуйтесь</a> и получите персональную скидку 20% на первое посещение!</p>
            </div>
            <?php endif; ?>
            <div class="spa-programs">
                <h2>Наши программы</h2>
                <div class="programs-grid">
                    <div class="program-card">
                        <div class="program-image">
                            <img src="images/3.jpg" alt="Фейслифтинг">
                        </div>
                        <h3>«Фейслифтинг» омоложение методикой углубленной проработки мышц лица, шеи и зоны декольте</h3>
                        <p>Программа «Фейслифтинг» — это специализированный массаж для лица, направленный на улучшение тонуса кожи, уменьшение морщин и подтяжку овала лица.В процессе процедуры используются техники глубокого и поверхностного массажа, которые активизируют кровообращение, способствует обновлению клеток, уменьшают отеки и укрепляют лицевые мышцы.</p>
                        <?php if ($login !== null): ?>
                        <div class="program-price-discount">
                            <div class="discount-info">
                            <?php if ($isBirthday): ?>
                            <span class="birthday-badge">🎂 +5%</span>
                            <?php endif; ?>
                            <span class="discount-badge">-<?= $discountPercent ?>%</span>
                            </div>
                            <div class="price-block">
                        <span class="old-price">3 500 руб.</span>
                        <span class="program-price discount"><?= number_format(3500 * (100 - $discountPercent) / 100, 0, '', ' ') ?> руб.</span>
                        </div>
                    </div>
                    <?php else: ?>
                        <span class="program-price">3 500 руб.</span>
                        <?php endif; ?>
                    </div>
                    <div class="program-card">
                        <div class="program-image">
                            <img src="images/4.jpg" alt="Интенсивное спортивное восстановление">
                        </div>
                        <h3>Интенсивное спортивное восстановление</h3>
                        <p>Программа спортивного массажа направлена на восстановление и поддержание физической формы, улучшение работы мышц. Эта процедура включает техники глубокого массажа, растяжки и расслабления мышц, что способствует снятию напряжения, ускорению кровообращения и улучшению обменных процессов. Углубленный с проработкой массаж поможет Вам уменьшить мышечную усталость, избавится от отеков, зажимов, уже после первого сеанса Вы почувствуете улучшение общего самочувствия.
Спортивное восстановление подходит всем, кто стремиться быть здоровым, подтянутым и красивым.</p>
                        <?php if ($login !== null): ?>
                        <div class="program-price-discount">
                            <div class="discount-info">
                            <?php if ($isBirthday): ?>
                            <span class="birthday-badge">🎂 +5%</span>
                            <?php endif; ?>
                            <span class="discount-badge">-<?= $discountPercent ?>%</span>
                            </div>
                            <div class="price-block">
                        <span class="old-price">3 500 руб.</span>
                        <span class="program-price discount"><?= number_format(3500 * (100 - $discountPercent) / 100, 0, '', ' ') ?> руб.</span>
                    </div>
                    </div>
                    <?php else: ?>
                        <span class="program-price">3 500 руб.</span>
                        <?php endif; ?>
                    </div>
                    <div class="program-card">
                        <div class="program-image">
                            <img src="images/5.jpg" alt="«Антицеллюлитное» spa обертывание">
                        </div>
                        <h3>«Антицеллюлитное» spa обертывание</h3>
                        <p>Ощутите на себе эффективность глубокого лимфодренажного массажа в совокупности с вакуумными методиками. Антицеллюлитная SPA-программа направлена на коррекцию фигуры, улучшение обмена веществ, устранение отёков, нормализацию лимфотока. Особое внимание при проведении антицеллюлитного массажа уделяется таким областям, как бёдра, ягодицы, живот, руки и колени. Уже после первой процедуры Вы почувствуете видимый результат и убедитесь в эффективности массажа.</p>
                        <?php if ($login !== null): ?>
                        <div class="program-price-discount">
                            <div class="discount-info">
                            <?php if ($isBirthday): ?>
                            <span class="birthday-badge">🎂 +5%</span>
                            <?php endif; ?>
                            <span class="discount-badge">-<?= $discountPercent ?>%</span>
                            </div>
                            <div class="price-block">
                        <span class="old-price">4 700 руб.</span>
                        <span class="program-price discount"><?= number_format(4700 * (100 - $discountPercent) / 100, 0, '', ' ') ?> руб.</span>
                            </div>
                    </div>
                    <?php else: ?>
                        <span class="program-price">4 700 руб.</span>
                        <?php endif; ?>
                    </div>
                    <div class="program-card">
                        <div class="program-image">
                            <img src="images/6.jpg" alt="Прана спа-уход «Классический»">
                        </div>
                        <h3>Прана спа-уход «Классический»</h3>
                        <p>Незабываемая спа-программа для тела и лица. Ароматический массаж по теплому маслу расслабит напряженные мышцы, а уход для лица с использованием французской косметики класса “люкс” Yon-ka подарит незабываемое ощущение комфорта, особенно в сочетании полным релаксом в комнате отдыха</p>
                        <?php if ($login !== null): ?>
                        <div class="program-price-discount">
                            <div class="discount-info">
                            <?php if ($isBirthday): ?>
                            <span class="birthday-badge">🎂 +5%</span>
                            <?php endif; ?>
                            <span class="discount-badge">-<?= $discountPercent ?>%</span>
                            </div>
                            <div class="price-block">
                        <span class="old-price">5 790 руб.</span>
                        <span class="program-price discount"><?= number_format(5790 * (100 - $discountPercent) / 100, 0, '', ' ') ?> руб.</span>
                            </div>
                    </div>
                    <?php else: ?>
                        <span class="program-price">5 790 руб.</span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <h2>Добро пожаловать в наш спа-салон!</h2>
            <p>Забудьте о визах, багаже и утомительных перелетах, о языковых барьерах и акклиматизации, позвольте себе отпуск, не покидая своего города! Прямо здесь, в Томске, в холодной Сибири, расположен уголок райского наслаждения для души и тела – SPA-салон «Тайский рай». Это удивительное место, где восточные методики оздоровления и омоложения принесут Вам массу приятных впечатлений.</p>
            <?php if ($login !== null): ?>
                <div class="user-content">
                    <h3>Специальный контент для авторизованных пользователей</h3>
                    <p>Привет, <?= htmlspecialchars($login) ?>! Рады видеть вас снова!</p>
                    <?php if ($birthdayInfo): ?>
                    <p>📅 Ваша дата рождения: <?= date('d.m.Y', strtotime($birthdayInfo)) ?></p>
                    <p>💰 Ваша персональная скидка: <strong><?= $discountPercent ?>%</strong></p>
                    <?php endif; ?>
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
            </div>
        </div>
        </footer>
        <script>
            // Модальное окно для ввода даты рождения
        document.addEventListener('DOMContentLoaded', function() {
            const modal = document.getElementById('birthdayModal');
            if (!modal) return;
            
            const closeBtn = modal.querySelector('.close');
            modal.style.display = 'block';
            if (closeBtn) {
                closeBtn.onclick = function() {
                    modal.style.display = 'none';
                };
            }
            window.onclick = function(event) {
                if (event.target === modal) {
                    modal.style.display = 'none';
                }
            };
        });

        // Таймер для персональной акции
        document.addEventListener('DOMContentLoaded', function() {
            const timerDisplay = document.querySelector('.timer-display');
            if (!timerDisplay) return;
            
            function updateTimer() {
                const hoursElement = document.getElementById('hours');
                const minutesElement = document.getElementById('minutes');
                const secondsElement = document.getElementById('seconds');
                
                if (!hoursElement || !minutesElement || !secondsElement) return;
                
                let hours = parseInt(hoursElement.textContent);
                let minutes = parseInt(minutesElement.textContent);
                let seconds = parseInt(secondsElement.textContent);
                
                if (hours === 0 && minutes === 0 && seconds === 0) {
                    location.reload();
                    return;
                }
                
                seconds--;
                
                if (seconds < 0) {
                    seconds = 59;
                    minutes--;
                    
                    if (minutes < 0) {
                        minutes = 59;
                        hours--;
                    }
                }
                
                hoursElement.textContent = String(hours).padStart(2, '0');
                minutesElement.textContent = String(minutes).padStart(2, '0');
                secondsElement.textContent = String(seconds).padStart(2, '0');
            }
            
            setInterval(updateTimer, 1000);
        });
    </script>
</body>
</html>