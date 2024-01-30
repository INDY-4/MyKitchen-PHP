<a href="../../"><p>Back</p></a>
<hr>
<h2>Update</h2>
<form action="../../orders/update.php" method="POST">
    <label for="id">Order ID:</label>
    <input type="text" id="id" name="id" placeholder="3">
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