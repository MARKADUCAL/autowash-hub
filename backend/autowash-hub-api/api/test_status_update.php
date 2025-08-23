<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once "./config/database.php";

echo "<h1>Manual Status Update Test</h1>";

try {
    // Create database connection
    $connection = new Connection();
    $pdo = $connection->connect();
    
    echo "✅ Database connected successfully<br><br>";
    
    // Get a sample booking
    $sql = "SELECT id, status FROM bookings LIMIT 1";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $booking = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($booking) {
        $bookingId = $booking['id'];
        $currentStatus = $booking['status'] ?? 'NULL';
        
        echo "<h2>Testing Status Update</h2>";
        echo "Booking ID: <strong>{$bookingId}</strong><br>";
        echo "Current Status: <strong>{$currentStatus}</strong><br><br>";
        
        // Try to update status to 'cancelled'
        $newStatus = 'cancelled';
        echo "Attempting to update status to: <strong>{$newStatus}</strong><br><br>";
        
        $sql = "UPDATE bookings SET status = ? WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $result = $stmt->execute([$newStatus, $bookingId]);
        
        if ($result) {
            $affectedRows = $stmt->rowCount();
            echo "✅ UPDATE query executed successfully<br>";
            echo "Affected rows: {$affectedRows}<br><br>";
            
            // Verify the update
            $sql = "SELECT status FROM bookings WHERE id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$bookingId]);
            $updatedStatus = $stmt->fetchColumn();
            
            echo "Verification: New status = <strong>{$updatedStatus}</strong><br>";
            
            if ($updatedStatus === $newStatus) {
                echo "🎉 SUCCESS: Status was updated to '{$newStatus}'!<br>";
            } else {
                echo "❌ FAILED: Status was not updated correctly<br>";
                echo "Expected: {$newStatus}, Got: {$updatedStatus}<br>";
            }
            
            // Revert the change
            echo "<br>Reverting status back to original...<br>";
            $sql = "UPDATE bookings SET status = ? WHERE id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$currentStatus === 'NULL' ? null : $currentStatus, $bookingId]);
            
            echo "✅ Status reverted to: " . ($currentStatus === 'NULL' ? 'NULL' : $currentStatus) . "<br>";
            
        } else {
            echo "❌ UPDATE query failed<br>";
            print_r($stmt->errorInfo());
        }
        
    } else {
        echo "❌ No bookings found in the table<br>";
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "<br>";
    echo "Stack trace: <pre>" . $e->getTraceAsString() . "</pre>";
}
?>
