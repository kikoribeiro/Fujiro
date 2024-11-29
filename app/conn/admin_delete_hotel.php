<?php

session_start();
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['hotel_id'])) {
    $hotel_id = $_GET['hotel_id'];

    try {
        $stmt = $pdo->prepare("CALL DeleteHotelById(:hotel_id)");
        $stmt->bindParam(':hotel_id', $hotel_id, PDO::PARAM_INT);
        $stmt->execute();

        header("Location: /bd2/app/adminhome.php");
        exit();
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
