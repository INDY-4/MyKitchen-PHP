<a href="../../"><p>Back</p></a>
<hr>
<h2>Update</h2>
<form action="../../products/update.php" method="POST" enctype="multipart/form-data">
    <label for="id">Product ID:</label>
    <input type="text" id="id" name="id" placeholder="3">
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
    
    <label for="product_image">Choose an image: (jpg, jpeg, png)</label>
    <input type="file" id="product_image" name="product_image">
    <br>

    <input type="submit" value="Submit">
</form>