<?php
// Define the API endpoint for the constructor details and race results
$api_url_constructor = 'http://localhost/COMP3512-Assignment-1/api/constructors.php';

// Check if a specific constructorRef is requested
if (isset($_GET['constructorRef'])) {
    $constructorRef = $_GET['constructorRef'];
    
    // Fetch the constructor details from the API
    $constructor_response = file_get_contents($api_url_constructor . '?ref=' . urlencode($constructorRef));
    $constructor_data = json_decode($constructor_response, true);

    // Check if constructor details exist and assign them
    if (isset($constructor_data)) {
        $constructor_details = $constructor_data;
    } else {
        echo "<p>Error: Constructor details not found.</p>";
        exit;
    }
} else {
    echo "<p>No constructor selected.</p>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>F1 Dashboard - Constructor Details</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&family=Lobster&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/constructorpage.css"> <!-- Link to your custom CSS file -->
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
        <!-- Constructor Details Section -->
        <div class="constructor-details">
            <h2>Constructor Details</h2>
            <p><strong>Name:</strong> <?php echo htmlspecialchars($constructor_details['name']); ?></p>
            <p><strong>Nationality:</strong> <?php echo htmlspecialchars($constructor_details['nationality']); ?></p>
            <p><strong>Constructor URL:</strong> <a href="<?php echo htmlspecialchars($constructor_details['url']); ?>" target="_blank">Constructor Bio</a></p>
        </div>

        <!-- Race Results Section -->
        <div class="race-results">
            <h2>Race Results for Constructor Drivers</h2>
            <table>
                <thead>
                    <tr>
                        <th>Rnd</th>
                        <th>Circuit</th>
                        <th>Driver</th>
                        <th>Position</th>
                        <th>Points</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($constructor_details['drivers'])): ?>
                        <?php foreach ($constructor_details['drivers'] as $driver): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($driver['round']); ?></td>
                                <td><?php echo htmlspecialchars($driver['raceName']); ?></td>
                                <td><?php echo htmlspecialchars($driver['forename']) . " " . htmlspecialchars($driver['surname']); ?></td>
                                <td><?php echo htmlspecialchars($driver['position']); ?></td>
                                <td><?php echo htmlspecialchars($driver['points']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="5">No race results found for this constructor.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
