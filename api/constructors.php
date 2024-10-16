<?php
// Include the database connection
include('../db_connect.php');

// Check if 'ref' is provided in the query string
if (isset($_GET['ref'])) {
    // Fetch the specified constructor
    $ref = $_GET['ref'];
    $query = "SELECT * FROM constructors WHERE constructorRef = :ref";
    $stmt = $db->prepare($query);
    $stmt->bindValue(':ref', $ref, SQLITE3_TEXT);
    $result = $stmt->execute();
    
    $constructor = $result->fetchArray(SQLITE3_ASSOC);
    if ($constructor) {
        echo json_encode($constructor);
    } else {
        echo json_encode(['error' => 'Constructor not found']);
    }
} else {
    // Fetch all constructors
    $query = "SELECT * FROM constructors";
    $result = $db->query($query);
    
    $constructors = [];
    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
        $constructors[] = $row;
    }
    
    echo json_encode($constructors);
}

// Close the database connection
$db->close();
?>
