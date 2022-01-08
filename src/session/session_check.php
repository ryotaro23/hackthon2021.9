<?php
session_start();
if (!isset($_SESSION['ID'])) {
    $url = "http://".$_SERVER['HTTP_HOST'].'/auth/login/';
    header("Location: $url");
    exit;
}
