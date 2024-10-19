<?php
// Include the database connection
// Start output buffering to prevent any unintended output
ob_start();

// Include the database connection
include('../dbconnection.php');

// Clear any output before the header
ob_clean();

// Set the correct content type for JSON output
header('Content-Type: application/json');

// Check if 'ref' is provided in the query string
if (isset($_GET['ref'])) {
    // Fetch the specified race using raceId
    $ref = $_GET['ref'];
    $query = "
        SELECT races.raceId, races.name, races.round, races.year, races.date, races.url, circuits.name as circuitName, circuits.location, circuits.country
        FROM races 
        JOIN circuits ON races.circuitId = circuits.circuitId
        WHERE races.raceId = :ref
    ";
    $stmt = $db->prepare($query);
    $stmt->bindValue(':ref', $ref, SQLITE3_INTEGER); // Assuming raceId is an integer
    $result = $stmt->execute();
    
    $race = $result->fetchArray(SQLITE3_ASSOC);
    if ($race) {
        // Pretty print the JSON output for a single race, including raceId
        echo json_encode($race, JSON_PRETTY_PRINT);
    } else {
        echo json_encode(['error' => 'Race not found'], JSON_PRETTY_PRINT);
    }
} else {
    // Fetch all races for 2022 season, include raceId in the result
    $query = "
        SELECT races.raceId, races.name, races.round, races.year, races.date, races.url, circuits.name as circuitName, circuits.location, circuits.country
        FROM races 
        JOIN circuits ON races.circuitId = circuits.circuitId
        WHERE races.year = 2022
        ORDER BY races.round ASC
    ";
    $result = $db->query($query);
    
    $races = [];
    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
        $races[] = $row;
    }
    
    // Pretty print the JSON output for all races in the 2022 season, including raceId
    echo json_encode($races, JSON_PRETTY_PRINT);
}

// Close the database connection
$db->close();
?>
