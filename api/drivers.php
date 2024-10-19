<?php
ob_start();

include('../dbconnection.php');

ob_clean();

// Set the correct content type for JSON output
header('Content-Type: application/json');

// Check if 'ref' or 'race' is provided in the query string
if (isset($_GET['ref'])) {
    // Fetch the specified driver by driverRef
    $ref = $_GET['ref'];
    $query = "SELECT driverID, driverRef, number, code, forename, surname, dob, nationality, url 
              FROM drivers 
              WHERE driverRef = :ref";
    
    if ($db) {
        $stmt = $db->prepare($query);
        if ($stmt) {
            $stmt->bindValue(':ref', $ref, SQLITE3_TEXT); // driverRef is text
            $result = $stmt->execute();
    
            if ($result) {
                $driver = $result->fetchArray(SQLITE3_ASSOC);
                if ($driver) {
                    // Fetch race results for the driver
                    $query_results = "SELECT ra.round, ra.name, r.position, r.points 
                                      FROM results r 
                                      JOIN races ra ON r.raceId = ra.raceId 
                                      WHERE r.driverId = :driverId AND year = 2022";
                    
                    $stmt_results = $db->prepare($query_results);
                    $stmt_results->bindValue(':driverId', $driver['driverId'], SQLITE3_INTEGER);
                    $result_results = $stmt_results->execute();
                    $race_results = [];
                    while ($row = $result_results->fetchArray(SQLITE3_ASSOC)) {
                        $race_results[] = $row;
                    }
                    
                    // Combine driver details and race results
                    $response = [
                        'driver_details' => $driver,
                        'race_results' => $race_results
                    ];
                    
                    // Output the combined JSON
                    echo json_encode($response, JSON_PRETTY_PRINT);
                } else {
                    echo json_encode(['error' => 'Driver not found'], JSON_PRETTY_PRINT);
                }
            } else {
                echo json_encode(['error' => 'Query execution failed'], JSON_PRETTY_PRINT);
            }
        } else {
            echo json_encode(['error' => 'Failed to prepare the query'], JSON_PRETTY_PRINT);
        }
    } else {
        echo json_encode(['error' => 'Database connection is not valid'], JSON_PRETTY_PRINT);
    }
} elseif (isset($_GET['race'])) {
    // Fetch drivers for the specified race by raceId
    $race = $_GET['race'];
    $query = "SELECT d.driverID, d.driverRef, d.number, d.code, d.forename, d.surname, d.dob, d.nationality, d.url 
              FROM drivers d
              JOIN results r ON d.driverID = r.driverID 
              WHERE r.raceId = :race";
    
    if ($db) {
        $stmt = $db->prepare($query);
        if ($stmt) {
            $stmt->bindValue(':race', $race, SQLITE3_INTEGER); // raceId should be an integer
            $result = $stmt->execute();
    
            if ($result) {
                $drivers = [];
                while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
                    $drivers[] = $row;
                }
                if (!empty($drivers)) {
                    // Output the JSON for all drivers in the race
                    echo json_encode($drivers, JSON_PRETTY_PRINT);
                } else {
                    echo json_encode(['error' => 'No drivers found for the specified race'], JSON_PRETTY_PRINT);
                }
            } else {
                echo json_encode(['error' => 'Query execution failed'], JSON_PRETTY_PRINT);
            }
        } else {
            echo json_encode(['error' => 'Failed to prepare the query'], JSON_PRETTY_PRINT);
        }
    } else {
        echo json_encode(['error' => 'Database connection is not valid'], JSON_PRETTY_PRINT);
    }
} else {
    // Fetch all drivers if no specific driverRef or race is provided
    $query = "SELECT driverID, driverRef, number, code, forename, surname, dob, nationality, url FROM drivers";
    
    if ($db) {
        $result = $db->query($query);
        if ($result) {
            $drivers = [];
            while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
                $drivers[] = $row;
            }
            // Output the JSON for all drivers
            echo json_encode($drivers, JSON_PRETTY_PRINT);
        } else {
            echo json_encode(['error' => 'Query execution failed'], JSON_PRETTY_PRINT);
        }
    } else {
        echo json_encode(['error' => 'Database connection is not valid'], JSON_PRETTY_PRINT);
    }
}

// Close the database connection
if ($db) {
    $db->close();
}
