<?php
// Specify the path to your SQLite database
$dbPath = 'C:\xampp\htdocs\COMP3512-Assignment-1\f1.db';

try {
    // Create a new SQLite3 database connection
    $db = new SQLite3($dbPath);

    // Test the connection by querying the database (optional)
    $query = 'SELECT SQLITE_VERSION() AS version';
    $result = $db->query($query);

    // Fetch and display the result (for debugging purposes)
    $row = $result->fetchArray();
    echo "SQLite version: " . $row['version'];

} catch (Exception $e) {
    // Handle connection errors
    echo "Failed to connect to the database: " . $e->getMessage();
    exit(); // Stop further script execution on connection failure
}

// Do not close the connection here, as other scripts may need it
// $db->close(); // REMOVE this line
?>
