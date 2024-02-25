
-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `tr_id` int(11) NOT NULL,
  `tr_kitchen_id` int(11) NOT NULL COMMENT 'fk to kitchen_id',
  `tr_user_id` int(11) NOT NULL COMMENT 'fk to user_id',
  `tr_amount` float NOT NULL,
  `tr_status` varchar(20) NOT NULL COMMENT 'Paid | Waiting | Failed',
  `tr_stripe_id` varchar(40) NOT NULL,
  `tr_created_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
