<?php
// Include the database connection
include('../dbconnection.php');

// Set the correct content type for JSON output
header('Content-Type: application/json');

// Check if 'ref' is provided in the query string
if (isset($_GET['ref'])) {
    $ref = $_GET['ref'];
    $query = "SELECT * FROM circuits WHERE circuitRef = :ref";

    if ($db) {
        // Prepare statement
        $stmt = $db->prepare($query);
        if ($stmt) {
            $stmt->bindValue(':ref', $ref, SQLITE3_TEXT);
            $result = $stmt->execute();

            if ($result) {
                $circuit = $result->fetchArray(SQLITE3_ASSOC);
                if ($circuit) {
                    // Pretty print the JSON output
                    echo json_encode($circuit, JSON_PRETTY_PRINT);
                } else {
                    echo json_encode(['error' => 'Circuit not found'], JSON_PRETTY_PRINT);
                }
            } else {
                echo json_encode(['error' => 'Query failed to execute'], JSON_PRETTY_PRINT);
            }
        } else {
            echo json_encode(['error' => 'Failed to prepare the query'], JSON_PRETTY_PRINT);
        }
    } else {
        echo json_encode(['error' => 'Database connection is not valid'], JSON_PRETTY_PRINT);
    }
} else {
    // Fetch all circuits
    $query = "SELECT * FROM circuits";

    if ($db) {
        $result = $db->query($query);
        if ($result) {
            $circuits = [];
            while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
                $circuits[] = $row;
            }
            // Pretty print the JSON output
            echo json_encode($circuits, JSON_PRETTY_PRINT);
        } else {
            echo json_encode(['error' => 'Query failed to execute'], JSON_PRETTY_PRINT);
        }
    } else {
        echo json_encode(['error' => 'Database connection is not valid'], JSON_PRETTY_PRINT);
    }
}

// Now it's safe to close the database connection
if ($db) {
    $db->close();
}
?>
