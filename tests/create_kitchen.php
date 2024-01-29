<a href="../"><p>Back</p></a>
<hr>
<form action="../kitchens/create.php" method="POST">
    <label for="kitchen_owner_id">User ID:</label>
    <input type="text" id="kitchen_owner_id" name="kitchen_owner_id" placeholder="4">
    <br>
    
    <label for="kitchen_name">Kitchen Name:</label>
    <input type="text" id="kitchen_name" name="kitchen_name">
    <br>

    <label for="kitchen_working_hours">Working Hours:</label>
    <input type="text" id="kitchen_working_hours" name="kitchen_working_hours">
    <br>
    
    <label for="kitchen_uses_cash">Take Cash?</label>
    <select name="kitchen_uses_cash" id="kitchen_uses_cash">
        <option value="true">Yes</option>
        <option value="false">No</option>
    </select>
    <br>

    <label for="kitchen_uses_card">Take Card?</label>
    <select name="kitchen_uses_card" id="kitchen_uses_card">
        <option value="yes">Yes</option>
        <option value="no">No</option>
    </select>
    <br>

    <input type="submit" value="Submit">
</form>