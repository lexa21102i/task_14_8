<?php
require __DIR__ . '/auth.php';

setcookie('login', '', time() - 3600, '/');
setcookie('password', '', time() - 3600, '/');

unset($_COOKIE['login']);
unset($_COOKIE['password']);

session_start();
session_destroy();

header('Location: /14_8/index.php');