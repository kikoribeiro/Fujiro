<?php
require 'config.php';

function showHotels() {

    global $pdo;

    try {

        $stmt = $pdo->query("SELECT * FROM hotels");
        $hotels = $stmt->fetchAll(PDO::FETCH_ASSOC);


    if ($hotels) {
        echo '<div id="hotelContainer" class="container mt-5">';
            foreach ($hotels as $hotel) {
                echo '<div class="card mb-4 hotel-card">';
                echo '<div class="row g-0">';

                echo '<div class="col-md-4">';
                echo '<img src="/bd2/assets/pexels-pixabay-258154.jpg" alt="' . $hotel['name'] . '" class="img-fluid rounded-start">';
                echo '</div>';

                echo '<div class="col-md-8">';
                echo '<div class="card-body">';
                echo '<h4 class="card-title">' . $hotel['destination'] . '</h4>';
                echo '<h5 class="card-title">' . $hotel['name'] . '</h5>';
                echo '<p class="card-text">Address: ' . $hotel['address'] . '</p>';
                echo '<p class="card-text">City: ' . $hotel['city'] . '</p>';
                echo '<p class="card-text">Price: ' . $hotel['price'] . '€ / per night</p>';
                echo '<p class="card-text">Rating: ';
                for ($i = 1; $i <= $hotel['evaluation']; $i++) {
                    echo '<i class="bi bi-star-fill" style="color: gold;"></i>';
                }
                echo '<a class="favorite-icon" href="toggle_favorite.php?hotel_id=' . $hotel['hotel_id'] . '"><i class="bi ' . ($hotel['is_favorite'] ? 'bi-heart-fill' : 'bi-heart') . '"></i></a>';
                echo '</p>';
                echo '<a href="reservationpage.php?hotel_id=' . $hotel['hotel_id'] . '" class="btn btn-primary">Make Reservation</a>';
                echo '</div>';
                echo '</div>';

                echo '</div>';
                echo '</div>';
            }
            echo '</div>';
        }
                else {
                            echo '<p>No hotels found.</p>';
                        }
    } catch (PDOException $e) {
        echo 'Error: ' . $e->getMessage();
    }
}
