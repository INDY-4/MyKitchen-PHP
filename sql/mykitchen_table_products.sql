
-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_id` int(11) NOT NULL,
  `product_kitchen_id` int(11) NOT NULL COMMENT 'FK to kitchen',
  `product_title` varchar(255) NOT NULL,
  `product_desc` varchar(1000) DEFAULT NULL,
  `product_price` float NOT NULL,
  `product_category` varchar(100) DEFAULT NULL,
  `product_tags` varchar(500) DEFAULT NULL,
  `product_image_url` varchar(255) DEFAULT NULL,
  `product_created_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
