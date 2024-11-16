<?php
require 'conn/config.php';
require 'conn/require_login.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $hotel_id = $_POST['hotel_id'];
    $destination = $_POST['destination'];
    $name = $_POST['name'];
    $address = $_POST['address'];
    $city = $_POST['city'];
    $price = $_POST['price'];
    $evaluation = $_POST['evaluation'];

    $stmt = $pdo->prepare("CALL UpdateHotel(:hotel_id, :destination, :name, :address, :city, :price, :evaluation)");
    $stmt->bindParam(':hotel_id', $hotel_id, PDO::PARAM_INT);
    $stmt->bindParam(':destination', $destination);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':address', $address);
    $stmt->bindParam(':city', $city);
    $stmt->bindParam(':price', $price);
    $stmt->bindParam(':evaluation', $evaluation);
    $stmt->execute();

    header("Location: adminhome.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['hotel_id'])) {
    $hotel_id = $_GET['hotel_id'];

    $stmt = $pdo->prepare("CALL GetHotelDetails(:hotel_id)");
    $stmt->bindParam(':hotel_id', $hotel_id, PDO::PARAM_INT);
    $stmt->execute();
    $hotelDetails = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$hotelDetails) {
        echo "Hotel not found.";
        exit();
    }
} else {
    header("Location: adminhome.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">
    <link rel="icon" type="image/png+xml" href="../assets/logo.png">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Hotel</title>
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
    <h1>Edit Hotel</h1>
    <div class="container mt-5">
    <h1 class="fujiro">Fu<span style="color: #0056b3;">jiro</span></h1>
    <form action="" method="post">
            <input type="hidden" name="hotel_id" value="<?php echo $hotelDetails['hotel_id']; ?>">
            <label for="destination">Destination:</label>
            <input type="text" name="destination" value="<?php echo $hotelDetails['destination']; ?>" required><br>
            <label for="name">Hotel Name:</label>
            <input type="text" name="name" value="<?php echo $hotelDetails['name']; ?>" required><br>
            <label for="address">Address:</label>
            <input type="text" name="address" value="<?php echo $hotelDetails['address']; ?>" required><br>
            <label for="city">City:</label>
            <input type="text" name="city" value="<?php echo $hotelDetails['city']; ?>" required><br>
            <label for="price">Price:</label>
            <input type="number" name="price" value="<?php echo $hotelDetails['price']; ?>" required><br>
            <label for="evaluation">Evaluation:</label>
            <input type="number" name="evaluation" value="<?php echo $hotelDetails['evaluation']; ?>" required><br>
            <button type="submit" name="update_hotel">Update Hotel</button>
        </form>

    </div>
</body>
</html>