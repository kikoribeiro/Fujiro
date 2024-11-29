<?php
session_start();
require 'conn/config.php';

function getReservationDetails($pdo, $reservation_id) {
    $stmt = $pdo->prepare("SELECT * FROM reservations WHERE id = :reservation_id");
    $stmt->bindParam(':reservation_id', $reservation_id, PDO::PARAM_INT);
    $stmt->execute();

    $reservationDetails = $stmt->fetch(PDO::FETCH_ASSOC);

    return $reservationDetails;
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['reservation_id'])) {
    $reservation_id = $_GET['reservation_id'];
    $reservationDetails = getReservationDetails($pdo, $reservation_id);

    if ($reservationDetails) {
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png+xml" href="../assets/logo.png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">
    <title>Edit Reservation</title>
            <style>
        @import url('https://fonts.googleapis.com/css2?family=Kanit:wght@400;500;700&display=swap');
        body {
            font-family: 'Kanit', sans-serif;
            background-color: #353935;
            margin: 0;
        }
        h1,h2 {
            color: #435585;
            margin-bottom: 20px;
            text-align: center;
        }
        .container {
            max-width: 400px;
            margin: 50px auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        label {
            margin-top: 10px;
            display: block;
            font-weight: bold;
        }

        input {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        button {
            display: block;
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 5px;
            background-color: #363062;
            color: white;
            cursor: pointer;
        }

        button:hover {
            background-color: #1c1744;
        }
     </style>
</head>
<body>
    <h1>Edit Reservation</h1>
    <div class="container mt-5">
    <h1 class="fujiro">Fu<span style="color: #0056b3;">jiro</span></h1>
                <form action="conn/edit_reservation.php" method="post">
                    <input type="hidden" name="reservation_id" value="<?php echo $reservation_id; ?>">
                    <label for="check_in">Check-in:</label>
                    <input type="date" name="check_in" value="<?php echo $reservationDetails['check_in']; ?>" required><br>
                    <label for="check_out">Check-out:</label>
                    <input type="date" name="check_out" value="<?php echo $reservationDetails['check_out']; ?>" required><br>
                    <button type="submit" name="update_reservation">Update Reservation</button>
                </form>

    </div>
</body>
</html>
<?php
exit();
}
}
header("Location:/bd2/app/reservations.php");
exit();
?>
