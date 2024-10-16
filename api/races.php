<?php
// Include the database connection
include('../db_connect.php');

// Check if 'ref' is provided in the query string
if (isset($_GET['ref'])) {
    // Fetch the specified race
    $ref = $_GET['ref'];
    $query = "
        SELECT races.name, races.round, races.year, races.date, circuits.name as circuitName, circuits.location, circuits.country
        FROM races 
        JOIN circuits ON races.circuitId = circuits.circuitId
        WHERE races.raceRef = :ref
    ";
    $stmt = $db->prepare($query);
    $stmt->bindValue(':ref', $ref, SQLITE3_TEXT);
    $result = $stmt->execute();
    
    $race = $result->fetchArray(SQLITE3_ASSOC);
    if ($race) {
        echo json_encode($race);
    } else {
        echo json_encode(['error' => 'Race not found']);
    }
} else {
    // Fetch all races for 2022 season
    $query = "
        SELECT races.name, races.round, races.year, races.date, circuits.name as circuitName, circuits.location, circuits.country
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
    
    echo json_encode($races);
}

// Close the database connection
$db->close();
?>
