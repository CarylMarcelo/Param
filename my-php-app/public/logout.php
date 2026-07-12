<?php
require_once __DIR__ . '/../src/middleware/authentication.php';
logoutUser();
header('Location: login.php');
exit;
