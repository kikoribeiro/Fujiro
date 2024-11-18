<?php
require 'conn/config.php';
require 'conn/require_login.php';

$stmt = $pdo->prepare("CALL GetAllReservations()");
$stmt->execute();
$reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);
$stmt->closeCursor();

?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png+xml" href="../assets/logo.png">
    <title>FUJIRO</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <style>
           @import url('https://fonts.googleapis.com/css2?family=Kanit:wght@400;500;700&display=swap');
        body {
            font-family: 'Kanit', sans-serif;
            background-color: #353935;
            margin: 0;
        }
        .container {
            margin: 50px auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        .navbar-brand{
            color: #435585;
        }
        .btn {
            margin-left: 20px;
            color: #ffffff;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            cursor: pointer;
            background-color: #363062;
            transition: background-color 0.2s;
        }
        .btn:hover{
            color: #f5f5f5;
            background-color: #1c1744;
        }

        .navbar-custom{
          background-color: #f5f5f5;

        }
        h3{
            color:#f5f5f5
        }
        i{
          padding-right: 5px;
        }
        .favorite-icon {
        color: red;
        cursor: pointer;
        
    }
    </style>
</head>
<body>
  <nav class="navbar navbar-expand-lg border-bottom navbar-custom">
    <div class="container-fluid">
        <a class="navbar-brand" href="adminhome.php">Fu<span style="color: #0056b3;">jiro</span></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse ml-auto " id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="admin_reservations.php"><i class="bi bi-calendar-check"></i>Reservations</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="admin_add_hotel.php"><i class="bi bi-house-add"></i>Add Hotel</a>
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
<div class="mt-4">

</div>
 <!-- Section 1: View (Hotels With Average Price) -->
 <div class="mb-4">
        <h3>Hotels With Average Price (View)</h3>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>City</th>
                    <th>Average Price</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $stmt = $pdo->query("SELECT * FROM HotelsWithAveragePrice");
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo "<tr>
                            <td>{$row['city']}</td>
                            <td>\${$row['average_price']}</td>
                          </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <!-- Section 2: Function (Calculate Discount) -->
    <div class="mb-4">
        <h3>Discount Price Calculation (Function)</h3>
        <form method="post" class="row g-3">
            <div class="col-md-6">
                <input type="number" name="price" class="form-control" placeholder="Enter Price" required>
            </div>
            <div class="col-md-4">
                <input type="number" name="discount" class="form-control" placeholder="Enter Discount %" required>
            </div>
            <div class="col-md-2">
                <button type="submit" name="calculate" class="btn btn-primary">Calculate</button>
            </div>
        </form>
        <?php
        if (isset($_POST['calculate'])) {
            $price = $_POST['price'];
            $discount = $_POST['discount'];
            $stmt = $pdo->query("SELECT GetHotelDiscount($price, $discount) AS DiscountPrice");
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            echo "<p class='mt-3'>Discounted Price: <strong>\${$row['DiscountPrice']}</strong></p>";
        }
        ?>
    </div>

    <!-- Section 3: Join Query (User Favorite Hotels) -->
    <div class="mb-4">
        <h3>User's Favorite Hotels (Join Query)</h3>
        <form method="post" class="row g-3">
            <div class="col-md-10">
                <input type="number" name="user_id" class="form-control" placeholder="Enter User ID" required>
            </div>
            <div class="col-md-2">
                <button type="submit" name="fetchFavorites" class="btn btn-success">Fetch Favorites</button>
            </div>
        </form>
        <?php
        if (isset($_POST['fetchFavorites'])) {
            $userId = $_POST['user_id'];
            $stmt = $pdo->prepare("CALL GetUserFavoriteHotels(:userId)");
            $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
            $stmt->execute();
            echo "<table class='table table-striped mt-3'>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>City</th>
                            <th>Price</th>
                        </tr>
                    </thead>
                    <tbody>";
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "<tr>
                        <td>{$row['hotel_id']}</td>
                        <td>{$row['name']}</td>
                        <td>{$row['city']}</td>
                        <td>\${$row['price']}</td>
                      </tr>";
            }
            echo "</tbody></table>";
        }
        ?>
    </div>

    <!-- Section 4: Ranked Hotels (Window Query) -->
    <div class="mb-4">
        <h3>Ranking de hotels por avaliação (Window Query)</h3>
        <?php
        $stmt = $pdo->query("CALL GetRankedHotels()");
        echo "<table class='table table-striped'>
                <thead>
                    <tr>
                        <th>Rank</th>
                        <th>Hotel ID</th>
                        <th>Name</th>
                        <th>City</th>
                        <th>Evaluation</th>
                    </tr>
                </thead>
                <tbody>";
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<tr>
                    <td>{$row['rank']}</td>
                    <td>{$row['hotel_id']}</td>
                    <td>{$row['name']}</td>
                    <td>{$row['city']}</td>
                    <td>{$row['evaluation']}</td>
                  </tr>";
        }
        echo "</tbody></table>";
        ?>
    </div>

    <!-- Section 5: Cursor Query (Iterate Through Hotels) -->
    <div class="mb-4">
        <h3>Iterate Through Hotels (Cursor)</h3>
        <?php
        $stmt = $pdo->query("CALL IterateHotels()");
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<p>{$row['HotelDetails']}</p>";
        }
        ?>
    </div>
</div>




<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.21.0/font/bootstrap-icons.css"></script>
</body>
</html>
