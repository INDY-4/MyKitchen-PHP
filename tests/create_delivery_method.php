<a href="../"><p>Back</p></a>
<hr>
<form action="../delivery_methods/create.php" method="POST">
    <label for="kdm_owner">Kitchen ID:</label>
    <input type="text" id="kdm_owner" name="kdm_owner" placeholder="3">
    <br>
    
    <label for="kdm_type">Type:</label>
    <select name="kdm_type" id="kdm_type">
        <option value="local_pickup">Local Pickup</option>
        <option value="delivery">Delivery</option>
    </select>
    <br>
    
    <label for="kdm_range">Range:</label>
    <input type="number" id="kdm_range" name="kdm_range" step="any">mi
    <br>

    <input type="submit" value="Submit">
</form>