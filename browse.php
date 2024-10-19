<?php
// Define the API endpoint for races, qualifying, and results
$api_race_url = 'http://localhost/COMP3512-Assignment-1/api/races.php';
$api_qualifying_url = 'http://localhost/COMP3512-Assignment-1/api/qualifying.php';
$api_results_url = 'http://localhost/COMP3512-Assignment-1/api/results.php';

// Initialize the variables for race, qualifying, and results
$race = [];
$qualifying = [];
$results = [];
$podium = [];
$qualifying_response = ''; // Initialize variable to prevent undefined warning
$results_response = '';    // Initialize variable to prevent undefined warning
$races = []; 

// Check if a specific raceId is provided
if (isset($_GET['raceId'])) {
    $raceId = $_GET['raceId'];

    // Fetch race details
    $race_response = file_get_contents($api_race_url . '?ref=' . $raceId);
    if ($race_response === FALSE) {
        echo "<p>Error: Could not fetch race data. Please check the API endpoint.</p>";
    } else {
        $race = json_decode($race_response, true);
    }

    // Fetch qualifying results
    $qualifying_response = file_get_contents($api_qualifying_url . '?ref=' . $raceId);
    if ($qualifying_response === FALSE) {
        echo "<p>Error: Could not fetch qualifying data. Please check the API endpoint.</p>";
    } else {
        $qualifying = json_decode($qualifying_response, true);
    }

    // Fetch race results
    $results_response = file_get_contents($api_results_url . '?ref=' . $raceId);
    if ($results_response === FALSE) {
        echo "<p>Error: Could not fetch race results. Please check the API endpoint.</p>";
    } else {
        $results = json_decode($results_response, true);
        // Extract the top 3 podium positions
        $podium = array_slice($results, 0, 3);
    }
} else {
    // Fetch all races for 2022 by default
    $race_response = file_get_contents($api_race_url);
    if ($race_response === FALSE) {
        echo "<p>Error: Could not fetch races data. Please check the API endpoint.</p>";
    } else {
        $races = json_decode($race_response, true);
    }
}

// Check for JSON errors or if the response was invalid
if (json_last_error() !== JSON_ERROR_NONE) {
    echo "<p>Error decoding JSON: " . json_last_error_msg() . "</p>";
    $races = []; // Ensure $races is an empty array if there is an error
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
    <link rel="stylesheet" href="css/browse.css"> <!-- Link to external CSS -->
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
        <?php if (isset($raceId) && !empty($race)): ?>
            <div class="race-details">
                <h2>Race Name: <?php echo htmlspecialchars($race['name']); ?></h2>
                <p><strong>Circuit: </strong><?php echo htmlspecialchars($race['circuitName']) . ", Round: " . htmlspecialchars($race['round']); ?></p>
                <p><strong>Circuit Location: </strong><?php echo htmlspecialchars($race['location']) . ", " . htmlspecialchars($race['country']); ?></p>
                <p><strong>Date of Race:  </strong><?php echo htmlspecialchars($race['date']); ?></p>
                <p><strong>URL:</strong> <a href="<?php echo htmlspecialchars($race['url']); ?>" target="_blank">Link</a></p>
                <!-- Qualifying Results -->
                <h3>Qualifying Results</h3>
                <table>
                    <thead>
                        <tr>
                            <th>Pos</th>
                            <th>Driver</th>
                            <th>Constructor</th>
                            <th>Q1</th>
                            <th>Q2</th>
                            <th>Q3</th>
                        </tr>
                    </thead>
                    <tbody>
    <?php if (is_array($qualifying) && count($qualifying) > 0): ?>
        <?php foreach ($qualifying as $q): ?>
            <tr>
                <td><?php echo htmlspecialchars($q['position']); ?></td>
                <td><a href="driverpage.php?driverRef=<?php echo htmlspecialchars($q['driverRef']); ?>"><?php echo htmlspecialchars($q['forename'] . " " . $q['surname']); ?></a></td>
                <td>
                    <a href="constructorpage.php?constructorRef=<?php echo urlencode($q['constructorRef']); ?>">
                        <?php echo htmlspecialchars($q['constructorName']); ?>
                    </a>
                </td>
                <!-- Remove first character from Q1, Q2, Q3 -->
                <td><?php echo htmlspecialchars(substr($q['q1'], 1)); ?></td>
                <td><?php echo htmlspecialchars(substr($q['q2'], 1)); ?></td>
                <td><?php echo htmlspecialchars(substr($q['q3'], 1)); ?></td>
            </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr><td colspan="6">No qualifying data available.</td></tr>
    <?php endif; ?>
</tbody>
                </table>

                <!-- Race Results -->
                 
                <!-- Podium Section (Now Above Qualifying) -->
                <h3>Race Results</h3>
                <div class="podium">
                    <?php if (!empty($podium)): ?>
                        <?php foreach ($podium as $index => $driver): ?>
                            <div class="position <?php echo ($index == 0) ? 'first' : (($index == 1) ? 'second' : 'third'); ?>">
                                <h4><?php echo ($index == 0) ? '1st' : (($index == 1) ? '2nd' : '3rd'); ?></h4>
                                <p><?php echo htmlspecialchars($driver['forename'] . " " . $driver['surname']); ?></p>
                                <p><?php echo htmlspecialchars($driver['constructorName']); ?></p>
                                <p>Laps: <?php echo htmlspecialchars($driver['laps']); ?></p>
                                <p>Pts: <?php echo htmlspecialchars($driver['points']); ?></p>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>No podium data available.</p>
                    <?php endif; ?>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>Pos</th>
                            <th>Driver</th>
                            <th>Constructor</th>
                            <th>Laps</th>
                            <th>Pts</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (is_array($results) && count($results) > 0): ?>
                            <?php foreach ($results as $r): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($r['grid']); ?></td>
                                    <td><a href="driverpage.php?driverRef=<?php echo htmlspecialchars($r['driverRef']); ?>"><?php echo htmlspecialchars($r['forename'] . " " . $r['surname']); ?></a></td>
                                    <td><a href="constructorpage.php?constructorRef=<?php echo htmlspecialchars($r['constructorRef']); ?>"><?php echo htmlspecialchars($r['constructorName']); ?></a></td>
                                    <td><?php echo htmlspecialchars($r['laps']); ?></td>
                                    <td><?php echo htmlspecialchars($r['points']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="5">No race results available.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <!-- If no race selected, show race list -->
            <div class="race-list">
                <h2>Races for 2022</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Round</th>
                            <th>Circuit</th>
                            <th>Date</th>
                            <th>Location</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (is_array($races) && count($races) > 0): ?>
                            <?php foreach ($races as $race): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($race['round']); ?></td>
                                    <td><?php echo htmlspecialchars($race['name']); ?></td>
                                    <td><?php echo htmlspecialchars($race['date']); ?></td>
                                    <td><?php echo htmlspecialchars($race['location'] . ', ' . $race['country']); ?></td>
                                    <td><a href="browse.php?raceId=<?php echo htmlspecialchars($race['raceId']); ?>" class="button">Results</a></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan='5'>No races available.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
