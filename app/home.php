
<!DOCTYPE html>
<html lang="en">
<?php
    require 'conn/hotels.php';
    require 'conn/require_login.php';
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
        <a class="navbar-brand" href="">Fu<span style="color: #0056b3;">jiro</span></a>
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
<div class="container mt-3">
    <form id="searchForm" class="row 8">
        <div class="col-2">
            <label for="destination" class="form-label">Destination</label>
            <input type="text" class="form-control" id="destination" name="destination" placeholder="Portugal">
        </div>
        <div class="col-1">
            <button type="button" class="btn" onclick="filterHotels()">Search</button>
        </div>
        <div class="col-2">
            <button type="button" class="btn" onclick="resetSearch()">Reset Search</button>
        </div>
    </form>
</div>
  </div>
  <div>
  <?php
    showHotels();
  ?>
  </div>

  <script>
      function filterHotels() {
          var destination = document.getElementById('destination').value.toLowerCase();
          var hotelCards = document.getElementsByClassName('card');

          for (var i = 0; i < hotelCards.length; i++) {
              var cardDestination = hotelCards[i].getElementsByClassName('card-title')[0].textContent.toLowerCase();
              if (cardDestination.includes(destination)) {
                  hotelCards[i].style.display = 'block';
              } else {
                  hotelCards[i].style.display = 'none';
              }
          }
      }
      function resetSearch() {
        document.getElementById('destination').value = '';
        var hotelCards = document.getElementsByClassName('hotel-card');

        for (var i = 0; i < hotelCards.length; i++) {
            hotelCards[i].style.display = 'block';
        }
    }

  </script>
  <script>
    $(document).ready(function () {
    $('.favorite-icon').click(function (e) {
        e.preventDefault();
        var hotelId = $(this).data('hotel-id');
        console.log('Clicked on hotel ID:', hotelId);

        $.get('conn/toggle_favorite.php', { hotel_id: hotelId }, function () {
            console.log('Toggle favorite success!');
            location.reload();
        }).fail(function (xhr, status, error) {
            console.error('Toggle favorite failed:', error);
        });
    });
});
</script>
</body>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.21.0/font/bootstrap-icons.css"></script>
</html>