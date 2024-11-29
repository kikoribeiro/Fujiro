<?php
session_start();

if (!isset($_SESSION['username'])) {

    header("Location: /bd2/app/login.html");
    exit();
}
