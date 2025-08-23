<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once "./config/database.php";

echo "<h1>Check Bookings Table Structure</h1>";

try {
    // Create database connection
    $connection = new Connection();
    $pdo = $connection->connect();
    
    echo "✅ Database connected successfully<br><br>";
    
    // Check if bookings table exists
    $sql = "SHOW TABLES LIKE 'bookings'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $tableExists = $stmt->fetch();
    
    if ($tableExists) {
        echo "✅ Bookings table exists<br><br>";
        
        // Show table structure
        echo "<h2>Table Structure</h2>";
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
        
        // Check specific columns
        $hasStatus = false;
        $hasUpdatedAt = false;
        
        foreach ($columns as $column) {
            if ($column['Field'] === 'status') {
                $hasStatus = true;
            }
            if ($column['Field'] === 'updated_at') {
                $hasUpdatedAt = true;
            }
        }
        
        echo "<h2>Column Check</h2>";
        if ($hasStatus) {
            echo "✅ 'status' column exists<br>";
        } else {
            echo "❌ 'status' column does NOT exist<br>";
        }
        
        if ($hasUpdatedAt) {
            echo "✅ 'updated_at' column exists<br>";
        } else {
            echo "❌ 'updated_at' column does NOT exist<br>";
        }
        
        // Show sample data
        echo "<h2>Sample Data</h2>";
        $sql = "SELECT * FROM bookings LIMIT 2";
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
            echo "No bookings found<br>";
        }
        
    } else {
        echo "❌ Bookings table does NOT exist<br>";
        
        // Show all tables
        echo "<h2>Available Tables</h2>";
        $sql = "SHOW TABLES";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
        
        foreach ($tables as $table) {
            echo "- {$table}<br>";
        }
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "<br>";
    echo "Stack trace: <pre>" . $e->getTraceAsString() . "</pre>";
}
?>
