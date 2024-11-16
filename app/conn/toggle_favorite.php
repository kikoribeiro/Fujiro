<?php
session_start();
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['hotel_id'])) {
    $hotel_id = $_GET['hotel_id'];
    $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

    if ($user_id) {
        $stmt = $pdo->prepare("CALL ToggleHotelFavorite(:hotel_id)");
        $stmt->bindParam(':hotel_id', $hotel_id, PDO::PARAM_INT);
        $stmt->execute();
    }
}

header("Location: " . $_SERVER['HTTP_REFERER']);
exit();
