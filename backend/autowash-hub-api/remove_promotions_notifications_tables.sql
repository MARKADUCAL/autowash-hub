-- Database Migration to Remove Promotions and Notifications Tables
-- This script removes the booking_promotions, notifications, and promotions tables

-- Drop foreign key constraints first (if they exist)
-- Note: Adjust constraint names based on your actual database schema

-- Drop booking_promotions table
DROP TABLE IF EXISTS booking_promotions;

-- Drop notifications table
DROP TABLE IF EXISTS notifications;

-- Drop promotions table
DROP TABLE IF EXISTS promotions;

-- Verify the tables have been removed
SHOW TABLES;
