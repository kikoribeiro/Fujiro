<?php
session_start();
require 'config.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
    $hotel_id = isset($_SESSION['hotel_id']) ? $_SESSION['hotel_id'] : null;
    $check_in = isset($_POST['check_in']) ? $_POST['check_in'] : null;
    $check_out = isset($_POST['check_out']) ? $_POST['check_out'] : null;


    $stmt = $pdo->prepare("INSERT INTO reservations (user_id, hotel_id, check_in, check_out) VALUES (:user_id, :hotel_id, :check_in, :check_out)");
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->bindParam(':hotel_id', $hotel_id, PDO::PARAM_INT);
    $stmt->bindParam(':check_in', $check_in);
    $stmt->bindParam(':check_out', $check_out);

    try {
        $stmt->execute();
        header("Location: /bd2/app/home.php");
        exit();
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    header("Location: /bd2/app/home.php");
    exit();
}

