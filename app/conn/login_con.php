<?php
session_start();
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
    $password = $_POST['password'];
    $username = htmlspecialchars($username, ENT_QUOTES, 'UTF-8');

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
    $stmt->bindParam(':username', $username, PDO::PARAM_STR);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);


    if ($user && password_verify($password, $user['password_hash'])) {

        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['email'] = $user['email'];

        if ($user['admin'] == 1) {
            header("Location: /bd2/app/adminhome.php");
            exit();
        } else {
            header("Location:/bd2/app/home.php");
            exit();
        }
    } else {

        echo "Invalid username or password";
    }
}

