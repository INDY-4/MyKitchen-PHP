
-- --------------------------------------------------------

--
-- Table structure for table `kitchen_delivery_methods`
--

CREATE TABLE `kitchen_delivery_methods` (
  `kdm_id` int(11) NOT NULL,
  `kdm_owner` int(11) NOT NULL COMMENT 'fk to kitchen',
  `kdm_type` varchar(30) NOT NULL COMMENT 'delivery | local pickup',
  `kdm_range` tinyint(3) UNSIGNED NOT NULL COMMENT '0-255 miles'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
