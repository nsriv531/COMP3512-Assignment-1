<?php
// Define the API endpoint for races
$api_url = 'http://localhost/COMP3512-Assignment-1/api/races.php';

// Check if a specific race year (ref) is requested
if (isset($_GET['raceId'])) {
    $raceId = $_GET['raceId'];
    // Fetch details for the selected race
    $response = file_get_contents($api_url . '?ref=' . $raceId);
} else {
    // Fetch all races for 2022 by default
    $response = file_get_contents($api_url);
}

// Decode the JSON response into a PHP array
$races = json_decode($response, true);

// Check if the response is valid and log it
if (json_last_error() !== JSON_ERROR_NONE) {
    echo "<p>Error decoding JSON: " . json_last_error_msg() . "</p>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>F1 Dashboard Project - Browse</title>
    <!-- Include Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&family=Lobster&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="browse.css"> <!-- Link to external CSS -->
    <link rel="icon" href="https://fav.farm/🏎️" />
</head>
<body>
    <div class="header">
        <h1>F1 Dashboard Project</h1>
        <div class="nav-links">
            <a href="index.php">Home</a>
            <a href="browse.php">Browse</a>
            <a href="apitester.php">APIs</a>
        </div>
    </div>

    <div class="container">
        <div class="race-list">
            <h2>2022 Races</h2>
            <table>
                <thead>
                    <tr>
                        <th>Rnd</th>
                        <th>Circuit</th>
                        <th>Date</th>
                        <th>Location</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Ensure the $races variable is an array and contains data
                    if (is_array($races) && count($races) > 0) {
                        // Display all races if no specific race is selected
                        foreach ($races as $race) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($race['round']) . "</td>";
                            echo "<td>" . htmlspecialchars($race['circuitName']) . "</td>";
                            echo "<td>" . htmlspecialchars($race['date']) . "</td>";
                            echo "<td>" . htmlspecialchars($race['location'] . ', ' . $race['country']) . "</td>";
                            echo "<td><a href='browse.php?raceId=" . htmlspecialchars($race['year']) . "' class='button'>Results</a></td>";
                            echo "</tr>";
                        }
                    } elseif (isset($races['name'])) {
                        // Display details of the selected race
                        echo "<h3>Race: " . htmlspecialchars($races['name']) . "</h3>";
                        echo "<p>Round: " . htmlspecialchars($races['round']) . "</p>";
                        echo "<p>Date: " . htmlspecialchars($races['date']) . "</p>";
                        echo "<p>Location: " . htmlspecialchars($races['location'] . ", " . $races['country']) . "</p>";
                    } else {
                        echo "<p>No races available or invalid race ID.</p>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
