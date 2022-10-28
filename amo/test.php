<?php
use AmoCRM\{AmoAPI, AmoAPIException};
use AmoCRM\TokenStorage\TokenStorageException;
require_once 'vendor/autoload.php';
$subdomain    = 'jysaninvest';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// Первичная авторизация
AmoAPI::oAuth2($subdomain);
print_r(AmoAPI::getAccount());
