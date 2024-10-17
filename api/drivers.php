<?php
// Include the database connection
include('../dbconnection.php');

// Set the correct content type for JSON output
header('Content-Type: application/json');

// Check if 'ref' or 'race' is provided in the query string
if (isset($_GET['ref'])) {
    // Fetch the specified driver
    $ref = $_GET['ref'];
    $query = "SELECT * FROM drivers WHERE driverRef = :ref";
    
    if ($db) {
        $stmt = $db->prepare($query);
        if ($stmt) {
            $stmt->bindValue(':ref', $ref, SQLITE3_TEXT);
            $result = $stmt->execute();
    
            if ($result) {
                $driver = $result->fetchArray(SQLITE3_ASSOC);
                if ($driver) {
                    // Pretty print the JSON output for the driver
                    echo json_encode($driver, JSON_PRETTY_PRINT);
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
    // Fetch drivers for the specified race
    $race = $_GET['race'];
    $query = "SELECT d.* FROM drivers d 
              JOIN results r ON d.driverId = r.driverId 
              WHERE r.raceId = :race";
    
    if ($db) {
        $stmt = $db->prepare($query);
        if ($stmt) {
            $stmt->bindValue(':race', $race, SQLITE3_TEXT);
            $result = $stmt->execute();
    
            if ($result) {
                $drivers = [];
                while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
                    $drivers[] = $row;
                }
                if ($drivers) {
                    // Pretty print the JSON output for all drivers in the race
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
    // Fetch all drivers if no query string is provided
    $query = "SELECT * FROM drivers";
    
    if ($db) {
        $result = $db->query($query);
        if ($result) {
            $drivers = [];
            while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
                $drivers[] = $row;
            }
            // Pretty print the JSON output for all drivers
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
?>
