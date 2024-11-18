<?php
require 'conn/config.php';
require 'conn/require_login.php'; 


$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

$stmt = $pdo->prepare("SELECT * FROM reservations WHERE user_id = :user_id");
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png+xml" href="../assets/logo.png">
    <title>FUJIRO</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Kanit:wght@400;500;700&display=swap');
        body {
            font-family: 'Kanit', sans-serif;
            background-color: #353935;
            margin: 0;
        }
        h1 {
            color: #ffffff;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            margin-top: 20px;
        }

        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #dee2e6;
        }
        .navbar-brand{
            color: #435585;
        }
    
    
        .navbar-custom{
          background-color: #f5f5f5;
           
        }
    
        i{
          padding-right: 5px;
        }
     </style>
</head>
<body>
<nav class="navbar navbar-expand-lg border-bottom navbar-custom">
    <div class="container-fluid"> 
        <a class="navbar-brand" href="home.php">Fu<span style="color: #0056b3;">jiro</span></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse ml-auto " id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item "> 
                  <a class="nav-link" href="favoritespage.php"><i class="bi bi-heart"></i>Favorites</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="reservations.php"><i class="bi bi-calendar-check"></i>Reservations</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="special_queries.php"><i class="bi bi-filetype-sql"></i>Special Queries</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href=""><i class="bi bi-person"></i><?php echo isset($_SESSION['username']) ? $_SESSION['username'] : 'USERNAME'; ?></a>
                </li>
                <li>
                    <li class="nav-item">
                    <a class="nav-link" href="conn/logout.php"><i class="bi bi-box-arrow-right"></i>Logout</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
<div class="container">
        <h1 class="mt-4">My Reservations</h1>
        <?php if ($reservations) : ?>
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Reservation ID</th>
                        <th>Hotel Name</th>
                        <th>Check-in Date</th>
                        <th>Check-out Date</th>
                        <th>Remove</th>
                        <th>Edit</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($reservations as $reservation) : ?>
                        <tr>
                            <td><?php echo $reservation['id']; ?></td>
                            <td><?php echo getHotelName($pdo, $reservation['hotel_id']); ?></td>
                            <td><?php echo $reservation['check_in']; ?></td>
                            <td><?php echo $reservation['check_out']; ?></td>
                            <td><form action="conn/remove_reservation.php" method="POST">
                                <input type="hidden" name="reservation_id" value="<?php echo $reservation['id']; ?>">
                                <button type="submit" class="btn btn-danger">Cancel</button>
                                </form>
                            </td>
                            <td>
                                <form action="reservationedit.php">
                                <input type="hidden" name="reservation_id" value="<?php echo $reservation['id']; ?>">
                                <button type="submit" class="btn btn-warning">Edit</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else : ?>
            <p>No reservations found.</p>
        <?php endif; ?>
    </div>
</body>
</html>

<?php
function getHotelName($pdo, $hotel_id) {
    $stmt = $pdo->prepare("SELECT name FROM hotels WHERE hotel_id = :hotel_id");
    $stmt->bindParam(':hotel_id', $hotel_id, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result ? $result['name'] : 'Unknown Hotel';
}
?>
