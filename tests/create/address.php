<a href="../../"><p>Back</p></a>
<hr>
<h2>Create</h2>
<form action="../../addresses/create.php" method="POST">
    <label for="address_owner">Owner ID:</label>
    <input type="text" id="address_owner" name="address_owner" placeholder="3">
    <br>
    
    <label for="address_type">Address Type:</label>
    <select name="address_type" id="address_type">
        <option value="kitchen">Kitchen</option>
        <option value="user">User</option>
    </select>
    <br><br>

    <label for="address_line1">Line 1:</label>
    <input type="text" id="address_line1" name="address_line1">
    <br>

    <label for="address_line2">Line 2:</label>
    <input type="text" id="address_line2" name="address_line2">
    <br>

    <label for="address_city">City:</label>
    <input type="text" id="address_city" name="address_city">
    <br>

    <label for="address_state">State:</label>
    <input type="text" id="address_state" name="address_state" placeholder="GA">
    <br>

    <label for="address_zip">Zip Code:</label>
    <input type="number" id="address_zip" name="address_zip">
    <br>

    <label for="address_phone">Phone:</label>
    <input type="tel" id="address_phone" name="address_phone" pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}" placeholder="123-456-7890">
    <br>

    <input type="submit" value="Submit">
</form>