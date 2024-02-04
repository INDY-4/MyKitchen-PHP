<a href="../../"><p>Back</p></a>
<hr>
<h2>Update</h2>
<form action="../../kitchens/update.php" method="POST">
    <label for="id">Kitchen ID:</label>
    <input type="text" id="id" name="id" placeholder="4">
    <br>
    
    <label for="kitchen_name">Kitchen Name:</label>
    <input type="text" id="kitchen_name" name="kitchen_name">
    <br>

    <label for="kitchen_working_hours">Working Hours:</label>
    <input type="text" id="kitchen_working_hours" name="kitchen_working_hours">
    <br>
    
    <label for="kitchen_is_active">Is Active?</label>
    <select name="kitchen_is_active" id="kitchen_is_active">
        <option value="yes">Yes</option>
        <option value="no">No</option>
    </select>
    <br>

    <label for="kitchen_uses_cash">Take Cash?</label>
    <select name="kitchen_uses_cash" id="kitchen_uses_cash">
        <option value="yes">Yes</option>
        <option value="no">No</option>
    </select>
    <br>

    <label for="kitchen_uses_card">Take Card?</label>
    <select name="kitchen_uses_card" id="kitchen_uses_card">
        <option value="yes">Yes</option>
        <option value="no">No</option>
    </select>
    <br>

    <label for="kitchen_stripe_id">Stripe ID:</label>
    <input type="text" id="kitchen_stripe_id" name="kitchen_stripe_id">
    <br>

    <input type="submit" value="Submit">
</form>