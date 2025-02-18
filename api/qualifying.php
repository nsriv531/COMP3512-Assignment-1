<?php
// Include the database connection
ob_start();

include('..database/dbconnection.php');

// Clear any output before the header
ob_clean();
// Set the correct content type for JSON output
header('Content-Type: application/json');

// Check if 'ref' is provided in the query string
if (isset($_GET['ref'])) {
    // Fetch the qualifying results for the specified race
    $ref = $_GET['ref'];
    $query = "
        SELECT qualifying.position, qualifying.q1, qualifying.q2, qualifying.q3, qualifying.raceID,
               drivers.driverRef, drivers.forename, drivers.surname, 
               constructors.name as constructorName, constructors.constructorRef
        FROM qualifying
        JOIN drivers ON qualifying.driverId = drivers.driverId
        JOIN constructors ON qualifying.constructorId = constructors.constructorId
        WHERE qualifying.raceId = :ref
        ORDER BY qualifying.position ASC
    ";
    $stmt = $db->prepare($query);
    $stmt->bindValue(':ref', $ref, SQLITE3_INTEGER);
    $result = $stmt->execute();
    
    $qualifyingResults = [];
    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
        $qualifyingResults[] = $row;
    }
    
    if (!empty($qualifyingResults)) {
        // Pretty print the JSON output
        echo json_encode($qualifyingResults, JSON_PRETTY_PRINT);
    } else {
        echo json_encode(['error' => 'No qualifying results found for this race'], JSON_PRETTY_PRINT);
    }
} else {
    echo json_encode(['error' => 'Race reference is required'], JSON_PRETTY_PRINT);
}

// Close the database connection
$db->close();
?>
