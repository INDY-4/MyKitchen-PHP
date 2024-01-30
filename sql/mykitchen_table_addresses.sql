
-- --------------------------------------------------------

--
-- Table structure for table `addresses`
--

CREATE TABLE `addresses` (
  `address_id` int(11) NOT NULL,
  `address_owner` int(11) NOT NULL COMMENT 'FK to user or kitchen',
  `address_type` varchar(10) NOT NULL COMMENT 'user | kitchen',
  `address_line1` varchar(100) NOT NULL,
  `address_line2` varchar(100) NOT NULL,
  `address_city` varchar(50) NOT NULL,
  `address_state` varchar(30) NOT NULL,
  `address_zip` varchar(15) NOT NULL,
  `address_phone` varchar(30) NOT NULL COMMENT 'if user',
  `address_created_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
