<?php
// Specify the path to your SQLite database
$dbPath = 'C:\\Users\\sriva\\Documents\\COMP3512-Assignment-1\\f1';

try {
    // Create a new SQLite3 database connection
    $db = new SQLite3($dbPath);

    // Test the connection by querying the database (optional)
    $query = 'SELECT SQLITE_VERSION() AS version';
    $result = $db->query($query);

    // Fetch and display the result
    $row = $result->fetchArray();
    echo "SQLite version: " . $row['version'];

} catch (Exception $e) {
    // Handle connection errors
    echo "Failed to connect to the database: " . $e->getMessage();
}

// Close the connection (optional, as PHP automatically closes connections when script ends)
$db->close();
?>
