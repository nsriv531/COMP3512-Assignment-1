<?php
// Include the database connection

ob_start();

include('../dbconnection.php');

ob_clean();
// Set the correct content type for JSON output
header('Content-Type: application/json');

// Check if 'ref' is provided in the query string
if (isset($_GET['ref'])) {
    // Fetch the specified constructor
    $ref = $_GET['ref'];
    $query = "SELECT * FROM constructors WHERE constructorRef = :ref";

    if ($db) {
        $stmt = $db->prepare($query);
        if ($stmt) {
            $stmt->bindValue(':ref', $ref, SQLITE3_TEXT);
            $result = $stmt->execute();

            if ($result) {
                $constructor = $result->fetchArray(SQLITE3_ASSOC);
                if ($constructor) {
                    // Fetch the drivers and race results for this constructor
                    $driver_query = "SELECT d.forename, d.surname, d.number, d.nationality, ra.round, ra.name AS raceName, r.grid, r.points
                                     FROM drivers d
                                     JOIN results r ON d.driverID = r.driverID
                                     JOIN races ra ON r.raceId = ra.raceId
                                     WHERE r.constructorId = :constructorId AND ra.year = 2022"; // Sorting by round

                    $stmt_drivers = $db->prepare($driver_query);
                    $stmt_drivers->bindValue(':constructorId', $constructor['constructorId'], SQLITE3_INTEGER);
                    $result_drivers = $stmt_drivers->execute();
                    $drivers = [];

                    while ($row = $result_drivers->fetchArray(SQLITE3_ASSOC)) {
                        $drivers[] = $row;
                    }

                    // Combine constructor details and drivers
                    $constructor['drivers'] = $drivers;

                    // Pretty print the JSON output for a single constructor with drivers and race results
                    echo json_encode($constructor, JSON_PRETTY_PRINT);
                } else {
                    echo json_encode(['error' => 'Constructor not found'], JSON_PRETTY_PRINT);
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
    // Fetch all constructors
    $query = "SELECT * FROM constructors";

    if ($db) {
        $result = $db->query($query);

        if ($result) {
            $constructors = [];
            while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
                $constructors[] = $row;
            }
            // Pretty print the JSON output for all constructors
            echo json_encode($constructors, JSON_PRETTY_PRINT);
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
