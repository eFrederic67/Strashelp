<?php
session_start();

//setcookie('pseudo', 'M@teo21', time() + 365*24*3600, null, null, false, true); // On écrit un cookie
//setcookie('pays', 'France', time() + 365*24*3600, null, null, false, true); // On écrit un autre cookie...
//require_once __DIR__ . '/../src/Custom/Auth.php';
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/debug.php';
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../src/routing.php';

