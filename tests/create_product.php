<a href="../"><p>Back</p></a>
<hr>
<form action="../products/create.php" method="POST">
    <label for="product_kitchen_id">Kitchen ID:</label>
    <input type="text" id="product_kitchen_id" name="product_kitchen_id" placeholder="3">
    <br>
    
    <label for="product_title">Product Title:</label>
    <input type="text" id="product_title" name="product_title">
    <br>

    <label for="product_desc">Description:</label>
    <input type="text" id="product_desc" name="product_desc">
    <br>
    
    <label for="product_price">Price:</label>
    $<input type="number" id="product_price" name="product_price" step="any">
    <br>

    <label for="product_category">Category:</label>
    <input type="text" id="product_category" name="product_category">
    <br>

    <label for="product_tags">Tags:</label>
    csv:<input type="text" id="product_tags" name="product_tags">
    <br>
    
    <label for="product_image_url">Image URL:</label>
    <input type="text" id="product_image_url" name="product_image_url">
    <br>

    <input type="submit" value="Submit">
</form>