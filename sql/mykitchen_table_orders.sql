
-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `order_kitchen_id` int(11) NOT NULL COMMENT 'FK to kitchen',
  `order_user_id` int(11) NOT NULL COMMENT 'FK to user',
  `order_products` varchar(1000) NOT NULL,
  `order_total` float NOT NULL,
  `order_status` varchar(30) NOT NULL,
  `order_created_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
