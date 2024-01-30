<a href="../../"><p>Back</p></a>
<hr>
<form action="../orders/create.php" method="POST">
    <label for="order_kitchen_id">Kitchen ID:</label>
    <input type="text" id="order_kitchen_id" name="order_kitchen_id" placeholder="3">
    <br>
    
    <label for="order_user_id">User ID:</label>
    <input type="text" id="order_user_id" name="order_user_id" placeholder="4">
    <br>

    <label for="order_products">Products:</label>
    <input type="text" id="order_products" name="order_products">
    <br>
    
    <label for="order_total">Order Total:</label>
    $<input type="number" id="order_total" name="order_total" step="any">
    <br>

    <label for="order_status">Order Status:</label>
    <select name="order_status" id="order_status">
        <option value="sent">Sent</option>
        <option value="payment_waiting">Payment Waiting</option>
        <option value="in_progress">In Progress</option>
        <option value="cooked">Cooked</option>
        <option value="done">Done</option>
    </select>
    <br>

    <input type="submit" value="Submit">
</form>