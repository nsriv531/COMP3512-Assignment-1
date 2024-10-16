<?php
// Include the database connection
include('../db_connect.php');

// Check if 'ref' is provided in the query string
if (isset($_GET['ref'])) {
    // Fetch the qualifying results for the specified race
    $ref = $_GET['ref'];
    $query = "
        SELECT qualifying.position, drivers.driverRef, drivers.forename, drivers.surname, constructors.name as constructorName
        FROM qualifying
        JOIN drivers ON qualifying.driverId = drivers.driverId
        JOIN constructors ON qualifying.constructorId = constructors.constructorId
        WHERE qualifying.raceId = :ref
        ORDER BY qualifying.position ASC
    ";
    $stmt = $db->prepare($query);
    $stmt->bindValue(':ref', $ref, SQLITE3_INTEGER); // Assuming raceId is an integer
    $result = $stmt->execute();
    
    $qualifyingResults = [];
    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
        $qualifyingResults[] = $row;
    }
    
    if (!empty($qualifyingResults)) {
        echo json_encode($qualifyingResults);
    } else {
        echo json_encode(['error' => 'No qualifying results found for this race']);
    }
} else {
    echo json_encode(['error' => 'Race reference is required']);
}

// Close the database connection
$db->close();
?>
