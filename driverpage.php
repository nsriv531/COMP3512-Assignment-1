<?php
// Define the API endpoint for the driver and race results
$api_url_driver = 'http://localhost/COMP3512-Assignment-1/api/drivers.php';

// Check if a specific driverRef is requested
if (isset($_GET['driverRef'])) {
    $driverRef = $_GET['driverRef'];
    // Fetch the driver details and race results from the API
    $driver_response = file_get_contents($api_url_driver . '?ref=' . urlencode($driverRef));
    $driver_data = json_decode($driver_response, true);
    // Debugging: Output the structure of $driver_data to check if it's as expected

    // Check if the race results exist and assign them
    if (isset($driver_data['race_results'])) {
        $race_results = $driver_data['race_results']; // Assign race results
       
    } else {
        $race_results = []; // If race results are missing, make it an empty array
        echo "raa!";
    }

    if (isset($driver_data['driver_details'])) {
        $driver_details = $driver_data['driver_details']; // Assign driver details
    } else {
        echo "<p>Error: Driver details not found.</p>";
        exit;
    }

    

} else {
    echo "<p>No driver selected.</p>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>F1 Dashboard - Driver Details</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&family=Lobster&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/driverpage.css"> <!-- Link to your custom CSS file -->
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
        <!-- Driver Details Section -->
        <div class="driver-details">
            <h2>Driver Details</h2>
            <p><strong>Name:</strong> <?php echo htmlspecialchars($driver_details['forename']) . " " . htmlspecialchars($driver_details['surname']); ?></p>
            <p><strong>Driver Code:</strong> <?php echo htmlspecialchars($driver_details['code']); ?></p>
            <p><strong>Number:</strong> <?php echo htmlspecialchars($driver_details['number']); ?></p>
            <p><strong>Date of Birth:</strong> <?php echo htmlspecialchars($driver_details['dob']); ?></p>
            <p><strong>Age:</strong> <?php echo date_diff(date_create($driver_details['dob']), date_create('today'))->y; ?></p>
            <p><strong>Nationality:</strong> <?php echo htmlspecialchars($driver_details['nationality']); ?></p>
            <p><strong>URL:</strong> <a href="<?php echo htmlspecialchars($driver_details['url']); ?>" target="_blank">Driver Bio</a></p>
        </div>

        <!-- Race Results Section -->
        <div class="race-results">
            <h2>Race Results</h2>
            <table>
                <thead>
                    <tr>
                        <th>Rnd</th>
                        <th>Circuit</th>
                        <th>Position</th>
                        <th>Points</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($race_results)): ?>
                        <?php foreach ($race_results as $result): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($result['round']); ?></td>
                                <td><?php echo htmlspecialchars($result['name']); ?></td> <!-- 'name' is the correct key -->
                                <td><?php echo htmlspecialchars($result['position']); ?></td>
                                <td><?php echo htmlspecialchars($result['points']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="4">No race results available.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
