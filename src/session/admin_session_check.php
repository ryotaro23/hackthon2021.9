<?php
session_start();
if (!$_SESSION['ADMIN']==1) {
    if (isset($_SESSION['ID'])) {
        $url = "http://".$_SERVER['HTTP_HOST'].'/';
        header("Location: $url");
        exit;
    }else{
        $url = "http://".$_SERVER['HTTP_HOST'].'/auth/login/';
        header("Location: $url");
        exit;
    }
}
