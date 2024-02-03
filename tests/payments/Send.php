<a href="../../"><p>Back</p></a>
<hr>
<h2>Charge</h2>
<form action="../../Stripe/ChargeCard.php" method="POST">
    <label for="kitchen_id">Kitchen ID:</label>
    <input type="text" id="kitchen_id" name="kitchen_id">
    <br>

    <label for="user_id">User ID:</label>
    <input type="text" id="user_id" name="user_id">
    <br>

    <label for="card_number">Card Number:</label>
    <input type="text" id="card_number" name="card_number" maxlength="16" pattern="[0-9]*" inputmode="numeric" required>
    <br>

    <label for="amount">Order Total:</label>
    $<input type="number" id="amount" name="amount" step="any">
    <br>

    <input type="submit" value="Submit">
</form>