<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once "./config/database.php";

echo "<h1>Booking Status Update Test</h1>";

try {
    // Create database connection
    $connection = new Connection();
    $pdo = $connection->connect();
    
    echo "<h2>1. Database Connection Test</h2>";
    echo "✅ Database connected successfully<br><br>";
    
    // Check if bookings table exists and show its structure
    echo "<h2>2. Bookings Table Structure</h2>";
    $sql = "DESCRIBE bookings";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<table border='1' style='border-collapse: collapse;'>";
    echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
    
    foreach ($columns as $column) {
        echo "<tr>";
        echo "<td>{$column['Field']}</td>";
        echo "<td>{$column['Type']}</td>";
        echo "<td>{$column['Null']}</td>";
        echo "<td>{$column['Key']}</td>";
        echo "<td>{$column['Default']}</td>";
        echo "<td>{$column['Extra']}</td>";
        echo "</tr>";
    }
    echo "</table><br>";
    
    // Check if status column exists
    $statusColumnExists = false;
    foreach ($columns as $column) {
        if ($column['Field'] === 'status') {
            $statusColumnExists = true;
            break;
        }
    }
    
    if (!$statusColumnExists) {
        echo "❌ <strong>ERROR: 'status' column does not exist in bookings table!</strong><br>";
        echo "Available columns: " . implode(', ', array_column($columns, 'Field')) . "<br><br>";
        
        // Try to find similar columns
        echo "<h3>Looking for similar columns:</h3>";
        foreach ($columns as $column) {
            if (stripos($column['Field'], 'status') !== false || 
                stripos($column['Field'], 'state') !== false ||
                stripos($column['Field'], 'booking') !== false) {
                echo "Found similar column: <strong>{$column['Field']}</strong><br>";
            }
        }
    } else {
        echo "✅ 'status' column exists<br><br>";
    }
    
    // Show sample data from bookings table
    echo "<h2>3. Sample Bookings Data</h2>";
    $sql = "SELECT * FROM bookings LIMIT 5";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (count($bookings) > 0) {
        echo "<table border='1' style='border-collapse: collapse;'>";
        echo "<tr>";
        foreach (array_keys($bookings[0]) as $header) {
            echo "<th>{$header}</th>";
        }
        echo "</tr>";
        
        foreach ($bookings as $booking) {
            echo "<tr>";
            foreach ($booking as $value) {
                echo "<td>" . htmlspecialchars($value ?? 'NULL') . "</td>";
            }
            echo "</tr>";
        }
        echo "</table><br>";
    } else {
        echo "No bookings found in the table<br><br>";
    }
    
    // Test the update function
    if ($statusColumnExists && count($bookings) > 0) {
        echo "<h2>4. Test Status Update</h2>";
        
        // Get first booking ID
        $firstBooking = $bookings[0];
        $testId = $firstBooking['id'];
        $currentStatus = $firstBooking['status'] ?? 'unknown';
        
        echo "Testing update for Booking ID: {$testId}<br>";
        echo "Current status: {$currentStatus}<br>";
        
        // Try to update status
        $newStatus = 'cancelled';
        $sql = "UPDATE bookings SET status = ? WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $result = $stmt->execute([$newStatus, $testId]);
        
        if ($result) {
            echo "✅ Status update query executed successfully<br>";
            
            // Check if it actually updated
            $sql = "SELECT status FROM bookings WHERE id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$testId]);
            $updatedStatus = $stmt->fetchColumn();
            
            echo "Updated status: {$updatedStatus}<br>";
            
            if ($updatedStatus === $newStatus) {
                echo "✅ Status was successfully updated in database<br>";
            } else {
                echo "❌ Status was not updated correctly<br>";
            }
            
            // Revert the change for testing
            $sql = "UPDATE bookings SET status = ? WHERE id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$currentStatus, $testId]);
            echo "✅ Reverted status back to: {$currentStatus}<br>";
            
        } else {
            echo "❌ Status update query failed<br>";
            print_r($stmt->errorInfo());
        }
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "<br>";
    echo "Stack trace: <pre>" . $e->getTraceAsString() . "</pre>";
}
?>
