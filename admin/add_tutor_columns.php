<?php
require_once('config.php');

$sql = "ALTER TABLE tutors 
        ADD COLUMN IF NOT EXISTS achievements TEXT,
        ADD COLUMN IF NOT EXISTS experience TEXT,
        ADD COLUMN IF NOT EXISTS education TEXT,
        ADD COLUMN IF NOT EXISTS skills TEXT";

if ($conn->query($sql) === TRUE) {
    echo "Columns added successfully";
} else {
    echo "Error adding columns: " . $conn->error;
}

$conn->close();
?>
