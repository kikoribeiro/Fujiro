<?php
session_start();
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_reservation'])) {
    $reservation_id = isset($_POST['reservation_id']) ? $_POST['reservation_id'] : null;
    $check_in = isset($_POST['check_in']) ? $_POST['check_in'] : null;
    $check_out = isset($_POST['check_out']) ? $_POST['check_out'] : null;

    if (is_numeric($reservation_id) && !empty($check_in) && !empty($check_out)) {
        $stmt = $pdo->prepare("UPDATE reservations SET check_in = :check_in, check_out = :check_out WHERE id = :reservation_id");
        $stmt->bindParam(':reservation_id', $reservation_id, PDO::PARAM_INT);
        $stmt->bindParam(':check_in', $check_in);
        $stmt->bindParam(':check_out', $check_out);
        $stmt->execute();


        header("Location:/bd2/app/reservations.php");
        exit();
    }
}

header("Location:/bd2/app/reservations.php");
exit();
