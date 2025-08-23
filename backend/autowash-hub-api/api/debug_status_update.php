<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once "./config/database.php";

echo "<h1>Debug Status Update</h1>";

try {
    // Create database connection
    $connection = new Connection();
    $pdo = $connection->connect();
    
    echo "✅ Database connected successfully<br><br>";
    
    // Test data (simulate what frontend sends)
    $testData = new stdClass();
    $testData->id = 2; // Use the booking ID from your database
    $testData->status = 'Cancelled';
    
    echo "<h2>Test Data</h2>";
    echo "ID: {$testData->id}<br>";
    echo "Status: {$testData->status}<br><br>";
    
    // Step 1: Check if booking exists
    echo "<h2>Step 1: Check if booking exists</h2>";
    $sql = "SELECT id, status FROM bookings WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$testData->id]);
    $booking = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($booking) {
        echo "✅ Booking found<br>";
        echo "Current status: " . ($booking['status'] ?? 'NULL') . "<br><br>";
    } else {
        echo "❌ Booking not found<br>";
        exit;
    }
    
    // Step 2: Test the update logic step by step
    echo "<h2>Step 2: Test Update Logic</h2>";
    
    // Get current status
    $sql = "SELECT status FROM bookings WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$testData->id]);
    $currentStatus = $stmt->fetchColumn();
    
    echo "Current status from database: " . ($currentStatus ?? 'NULL') . "<br>";
    
    // Check if booking exists (status can be NULL, so we check if the row was found)
    if ($currentStatus === false) {
        echo "❌ Booking not found<br>";
        exit;
    }
    
    // Handle NULL status (set to 'pending' if NULL)
    if ($currentStatus === null) {
        $currentStatus = 'pending';
        echo "Current status was NULL, setting to 'pending'<br>";
    }
    
    echo "Processed current status: {$currentStatus}<br>";
    
    // Normalize status to lowercase for consistency
    $normalizedStatus = strtolower($testData->status);
    echo "Normalized status: {$normalizedStatus}<br><br>";
    
    // Step 3: Test the UPDATE query
    echo "<h2>Step 3: Test UPDATE Query</h2>";
    
    try {
        $sql = "UPDATE bookings SET status = ? WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $result = $stmt->execute([$normalizedStatus, $testData->id]);
        
        if ($result) {
            $affectedRows = $stmt->rowCount();
            echo "✅ UPDATE query executed successfully<br>";
            echo "Affected rows: {$affectedRows}<br>";
            
            if ($affectedRows === 0) {
                echo "❌ No rows were updated. Booking ID {$testData->id} may not exist.<br>";
            } else {
                echo "✅ Row updated successfully<br>";
            }
        } else {
            echo "❌ UPDATE query failed<br>";
            print_r($stmt->errorInfo());
        }
        
    } catch (Exception $updateError) {
        echo "❌ UPDATE query error: " . $updateError->getMessage() . "<br>";
    }
    
    // Step 4: Verify the update
    echo "<h2>Step 4: Verify Update</h2>";
    $sql = "SELECT status FROM bookings WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$testData->id]);
    $verifiedStatus = $stmt->fetchColumn();
    
    echo "New status in database: " . ($verifiedStatus ?? 'NULL') . "<br>";
    
    if ($verifiedStatus === $normalizedStatus) {
        echo "🎉 SUCCESS: Status was updated correctly!<br>";
    } else {
        echo "❌ FAILED: Status was not updated correctly<br>";
        echo "Expected: {$normalizedStatus}, Got: {$verifiedStatus}<br>";
    }
    
    // Step 5: Revert the change
    echo "<h2>Step 5: Revert Change</h2>";
    $sql = "UPDATE bookings SET status = ? WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$currentStatus === 'pending' ? null : $currentStatus, $testData->id]);
    
    echo "✅ Status reverted to: " . ($currentStatus === 'pending' ? 'NULL' : $currentStatus) . "<br>";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "<br>";
    echo "Stack trace: <pre>" . $e->getTraceAsString() . "</pre>";
}
?>
