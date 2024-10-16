<?php
// Include the database connection

include('../dbconnection.php');

// Check if 'ref' is provided in the query string
if (isset($_GET['ref'])) {
    // Fetch the specified driver
    $ref = $_GET['ref'];
    $query = "SELECT * FROM drivers WHERE driverRef = :ref";
    $stmt = $db->prepare($query);
    $stmt->bindValue(':ref', $ref, SQLITE3_TEXT);
    $result = $stmt->execute();
    
    $driver = $result->fetchArray(SQLITE3_ASSOC);
    if ($driver) {
        echo json_encode($driver);
    } else {
        echo json_encode(['error' => 'Driver not found']);
    }
} else {
    // Fetch all drivers
    $query = "SELECT * FROM drivers";
    $result = $db->query($query);
    
    $drivers = [];
    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
        $drivers[] = $row;
    }
    
    echo json_encode($drivers);
}

// Close the database connection
$db->close();
?>
