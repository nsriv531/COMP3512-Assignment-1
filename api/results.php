<?php
// Include the database connection
ob_start();

// Include the database connection
include('../dbconnection.php');

// Clear any output before the header
ob_clean();
// Set the correct content type for JSON output
header('Content-Type: application/json');

// Check if 'ref' is provided to fetch results for a specific race
if (isset($_GET['ref'])) {
    $ref = $_GET['ref'];
    $query = "
        SELECT results.grid, results.laps, results.points, drivers.driverRef, drivers.code, drivers.forename, drivers.surname, 
               races.name as raceName, races.round, races.year, races.date, 
               constructors.name as constructorName, constructors.constructorRef, constructors.nationality
        FROM results
        JOIN drivers ON results.driverId = drivers.driverId
        JOIN races ON results.raceId = races.raceId
        JOIN constructors ON results.constructorId = constructors.constructorId
        WHERE results.raceId = :ref
        ORDER BY results.laps DESC, results.points DESC
    ";
    $stmt = $db->prepare($query);
    $stmt->bindValue(':ref', $ref, SQLITE3_INTEGER); // Assuming raceId is an integer
    $result = $stmt->execute();
    
    $raceResults = [];
    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
        $raceResults[] = $row;
    }
    
    if (!empty($raceResults)) {
        // Pretty print the JSON output for race results
        echo json_encode($raceResults, JSON_PRETTY_PRINT);
    } else {
        echo json_encode(['error' => 'No results found for this race'], JSON_PRETTY_PRINT);
    }
} elseif (isset($_GET['driver'])) {
    // Fetch results for a specific driver
    $driver = $_GET['driver'];
    $query = "
        SELECT results.laps, drivers.driverRef, drivers.code, drivers.forename, drivers.surname, 
               races.name as raceName, races.round, races.year, races.date, 
               constructors.name as constructorName, constructors.constructorRef, constructors.nationality
        FROM results
        JOIN drivers ON results.driverId = drivers.driverId
        JOIN races ON results.raceId = races.raceId
        JOIN constructors ON results.constructorId = constructors.constructorId
        WHERE drivers.driverRef = :driver
        ORDER BY results.grid ASC
    ";
    $stmt = $db->prepare($query);
    $stmt->bindValue(':driver', $driver, SQLITE3_TEXT);
    $result = $stmt->execute();
    
    $driverResults = [];
    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
        $driverResults[] = $row;
    }
    
    if (!empty($driverResults)) {
        // Pretty print the JSON output for driver results
        echo json_encode($driverResults, JSON_PRETTY_PRINT);
    } else {
        echo json_encode(['error' => 'No results found for this driver'], JSON_PRETTY_PRINT);
    }
} else {
    echo json_encode(['error' => 'Race or driver reference is required'], JSON_PRETTY_PRINT);
}

// Close the database connection
$db->close();
?>
