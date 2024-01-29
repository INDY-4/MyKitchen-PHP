
-- --------------------------------------------------------

--
-- Table structure for table `kitchens`
--

CREATE TABLE `kitchens` (
  `kitchen_id` int(11) NOT NULL,
  `kitchen_owner` int(11) NOT NULL COMMENT 'FK to user_id',
  `kitchen_name` varchar(100) NOT NULL,
  `kitchen_working_hours` varchar(100) DEFAULT NULL,
  `kitchen_is_active` tinyint(1) NOT NULL DEFAULT 1,
  `kitchen_uses_cash` tinyint(1) NOT NULL DEFAULT 1,
  `kitchen_uses_card` tinyint(1) NOT NULL DEFAULT 1,
  `kitchen_created_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='This holds data about the Kitchens in the site.';
