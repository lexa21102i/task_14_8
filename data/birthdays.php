<?php
session_start();
// Сохранение даты рождения в сессию
function saveUserBirthday($login, $birthday) {
    $_SESSION['birthday_' . $login] = $birthday;
}

// Получение даты рождения из сессии
function getUserBirthday($login) {
    return $_SESSION['birthday_' . $login] ?? null;
}

// Расчет дней до дня рождения
function getDaysUntilBirthday($birthday) {
    $today = new DateTime();
    $birthdayDate = new DateTime($birthday);
    
    $nextBirthday = new DateTime($today->format('Y') . '-' . $birthdayDate->format('m-d'));
    
    if ($nextBirthday < $today) {
        $nextBirthday->modify('+1 year');
    }
    
    $interval = $today->diff($nextBirthday);
    return $interval->days;
}

function getDayWord($days) {
    $days = abs($days);
    $lastDigit = $days % 10;
    $lastTwoDigits = $days % 100;
    
    if ($lastTwoDigits >= 11 && $lastTwoDigits <= 19) {
        return 'дней';
    }
    
    if ($lastDigit == 1) {
        return 'день';
    }
    
    if ($lastDigit >= 2 && $lastDigit <= 4) {
        return 'дня';
    }
    
    return 'дней';
}

// Создание персональной акции
function createUserPromo($login) {
    $_SESSION['promo_' . $login] = [
        'start_time' => time(),
        'end_time' => time() + 24 * 60 * 60,
        'discount' => 20
    ];
}

// Получение информации о промо-акции
function getUserPromo($login) {
    if (!isset($_SESSION['promo_' . $login])) {
        createUserPromo($login);
        return getUserPromo($login);
    }
    
    $promo = $_SESSION['promo_' . $login];
    
    if ($promo['end_time'] < time()) {
        createUserPromo($login);
        return getUserPromo($login);
    }
    
    return $promo;
}

// Получение оставшегося времени
function getTimeLeft($promoInfo) {
    $timeLeft = $promoInfo['end_time'] - time();
    
    if ($timeLeft < 0) {
        $timeLeft = 0;
    }
    
    $hours = floor($timeLeft / 3600);
    $minutes = floor(($timeLeft % 3600) / 60);
    $seconds = $timeLeft % 60;
    
    return [
        'hours' => str_pad($hours, 2, '0', STR_PAD_LEFT),
        'minutes' => str_pad($minutes, 2, '0', STR_PAD_LEFT),
        'seconds' => str_pad($seconds, 2, '0', STR_PAD_LEFT)
    ];
}
?>