
--
-- Indexes for dumped tables
--

--
-- Indexes for table `kitchens`
--
ALTER TABLE `kitchens`
  ADD PRIMARY KEY (`kitchen_id`),
  ADD KEY `kitchen_owner` (`kitchen_owner`);

--
-- Indexes for table `kitchen_delivery_methods`
--
ALTER TABLE `kitchen_delivery_methods`
  ADD PRIMARY KEY (`kdm_id`),
  ADD KEY `kdm_owner` (`kdm_owner`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `kitchens`
--
ALTER TABLE `kitchens`
  MODIFY `kitchen_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kitchen_delivery_methods`
--
ALTER TABLE `kitchen_delivery_methods`
  MODIFY `kdm_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `kitchens`
--
ALTER TABLE `kitchens`
  ADD CONSTRAINT `kitchens_ibfk_1` FOREIGN KEY (`kitchen_owner`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `kitchen_delivery_methods`
--
ALTER TABLE `kitchen_delivery_methods`
  ADD CONSTRAINT `kitchen_delivery_methods_ibfk_1` FOREIGN KEY (`kdm_owner`) REFERENCES `kitchens` (`kitchen_id`) ON DELETE CASCADE ON UPDATE CASCADE;
