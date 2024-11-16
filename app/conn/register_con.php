<?php

require 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("CALL AddUser(:username, :email, :password)");
    $stmt->bindParam(':username', $username, PDO::PARAM_STR);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->bindParam(':password', $password, PDO::PARAM_STR);

    try {
        $stmt->execute();
        header("Location:/bd2/app/home.php");
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
