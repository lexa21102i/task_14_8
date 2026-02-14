<?php
define('SECURE_ACCESS', true); 

function checkAuth(string $login, string $password): bool
{
    $users = require __DIR__ . '/usersDB.php';

    foreach ($users as $user){
        if ($user['login'] === $login) {
            if (password_verify($password, $user['password'])) {
                return true;
            }
        }
    }
    return false;
}

function getUserLogin(): ?string 
{
    $loginFromCookie = $_COOKIE['login'] ?? '';
    $passwordFromCookie = $_COOKIE['password'] ?? '';

    if (checkAuth($loginFromCookie, $passwordFromCookie)) {
        return $loginFromCookie;
    }
    return null;
}

// Функция для регистрации новых пользователей
function registerUser(string $login, string $password): bool
{
    $users = require __DIR__ . '/usersDB.php';
    
    // Проверяем, существует ли уже пользователь
    foreach ($users as $user) {
        if ($user['login'] === $login) {
            return false;
        }
    }
    
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    
    // Добавляем нового пользователя
    $users[] = ['login' => $login, 'password' => $hashedPassword];
    $content = "<?php\n\nreturn " . var_export($users, true) . ";\n";
    file_put_contents(__DIR__ . '/usersDB.php', $content);
    
    return true;
}