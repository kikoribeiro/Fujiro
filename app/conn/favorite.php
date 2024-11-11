<?php
require 'config.php';

function showFavoriteHotels($pdo, $user_id) {

    $stmt = $pdo->prepare("SELECT * FROM hotels WHERE hotel_id IN (SELECT hotel_id FROM favorites WHERE user_id = :user_id) ORDER BY is_favorite DESC");
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $favoriteHotels = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($favoriteHotels as &$hotel) {
        $hotel['is_favorite'] = $pdo->query("SELECT is_favorite FROM hotels WHERE hotel_id = " . $hotel['hotel_id'])->fetchColumn();
    }

    if (empty($favoriteHotels)) {
        echo '<p>No favorite hotels found.</p>';
    } else {
        echo '<ul class="list-group">';
        foreach ($favoriteHotels as $hotel) {
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
                echo '</p>';
                echo '<a class="favorite-icon" href="toggle_favorite.php?hotel_id=' . $hotel['hotel_id'] . '"><i class="bi ' . ($hotel['is_favorite'] ? 'bi-heart-fill' : 'bi-heart') . '"></i></a>';

                echo '<a href="reservationpage.php?hotel_id=' . $hotel['hotel_id'] . '" class="btn btn-primary">Make Reservation</a>';
                echo '</div>';
                echo '</div>';

                echo '</div>';
                echo '</div>';
            }
            echo '</div>';

        }
        echo '</ul>';
    }

