<?php
// Database connection
$conn = new mysqli("localhost", "root", "root", "bookmybed");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Number of rows and columns (e.g., like A1, A2... B1, B2...)
$rows = 11; // Number of rows (A-K)
$cols = 7;  // Number of columns (1-7)

// Check if beds already exist
$result = $conn->query("SELECT COUNT(*) AS count FROM beds");
$row = $result->fetch_assoc();
if ($row['count'] > 0) {
    echo "Beds already exist in the database!";
    $conn->close();
    exit;
}

// Prepare the insert query
$stmt = $conn->prepare("INSERT INTO beds (bed_number, status) VALUES (?, 'available')");

// Loop through rows (A, B, C, ...)
for ($i = 0; $i < $rows; $i++) {
    $rowLetter = chr(65 + $i); // Convert 0 -> A, 1 -> B, ...

    // Loop through columns (1, 2, 3, ...)
    for ($j = 1; $j <= $cols; $j++) {
        $bedNumber = "BED " . $rowLetter . $j; // Example: BED A1, BED A2, ...
        $stmt->bind_param("s", $bedNumber);

        // Execute the query
        $stmt->execute();
    }
}

echo "Beds generated and added to the database successfully!";

// Close connections
$stmt->close();
$conn->close();
?>
