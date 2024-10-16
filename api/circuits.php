<?php
// Include the database connection
include('../dbconnection.php');

// Check if 'ref' is provided in the query string
if (isset($_GET['ref'])) {
    // Fetch the specified circuit
    $ref = $_GET['ref'];
    $query = "SELECT * FROM circuits WHERE circuitRef = :ref";
    $stmt = $db->prepare($query);
    $stmt->bindValue(':ref', $ref, SQLITE3_TEXT);
    $result = $stmt->execute();
    
    $circuit = $result->fetchArray(SQLITE3_ASSOC);
    if ($circuit) {
        echo json_encode($circuit);
    } else {
        echo json_encode(['error' => 'Circuit not found']);
    }
} else {
    // Fetch all circuits
    $query = "SELECT * FROM circuits";
    $result = $db->query($query);
    
    $circuits = [];
    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
        $circuits[] = $row;
    }
    
    echo json_encode($circuits);
}

// Close the database connection
$db->close();
?>
