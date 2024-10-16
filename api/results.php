<?php
// Include the database connection
include('../db_connect.php');

// Check if 'ref' is provided to fetch results for a specific race
if (isset($_GET['ref'])) {
    $ref = $_GET['ref'];
    $query = "
        SELECT results.grid, drivers.driverRef, drivers.code, drivers.forename, drivers.surname, 
               races.name as raceName, races.round, races.year, races.date, 
               constructors.name as constructorName, constructors.constructorRef, constructors.nationality
        FROM results
        JOIN drivers ON results.driverId = drivers.driverId
        JOIN races ON results.raceId = races.raceId
        JOIN constructors ON results.constructorId = constructors.constructorId
        WHERE results.raceId = :ref
        ORDER BY results.grid ASC
    ";
    $stmt = $db->prepare($query);
    $stmt->bindValue(':ref', $ref, SQLITE3_INTEGER); // Assuming raceId is an integer
    $result = $stmt->execute();
    
    $raceResults = [];
    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
        $raceResults[] = $row;
    }
    
    if (!empty($raceResults)) {
        echo json_encode($raceResults);
    } else {
        echo json_encode(['error' => 'No results found for this race']);
    }
} elseif (isset($_GET['driver'])) {
    // Fetch results for a specific driver
    $driver = $_GET['driver'];
    $query = "
        SELECT results.grid, drivers.driverRef, drivers.code, drivers.forename, drivers.surname, 
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
        echo json_encode($driverResults);
    } else {
        echo json_encode(['error' => 'No results found for this driver']);
    }
} else {
    echo json_encode(['error' => 'Race or driver reference is required']);
}

// Close the database connection
$db->close();
?>
