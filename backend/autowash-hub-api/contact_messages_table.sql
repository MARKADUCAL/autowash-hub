-- Create contact_messages table for storing contact form submissions
CREATE TABLE IF NOT EXISTS `contact_messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `subject` varchar(500) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` enum('new','read','replied','archived') NOT NULL DEFAULT 'new',
  PRIMARY KEY (`id`),
  KEY `idx_email` (`email`),
  KEY `idx_status` (`status`),
  KEY `idx_created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert sample data (optional)
INSERT INTO `contact_messages` (`name`, `email`, `subject`, `message`, `status`) VALUES
('John Doe', 'john.doe@gmail.com', 'General Inquiry', 'I would like to know more about your car wash services.', 'new'),
('Jane Smith', 'jane.smith@gmail.com', 'Pricing Question', 'What are your current rates for premium wash?', 'new');
