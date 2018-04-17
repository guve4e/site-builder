<?php

require_once(LIBRARY_PATH . "/form/FormHandler.php");
require_once (DATA_RESOURCE_PATH . "/Product.php");

// GET INFO
try {
    $product = new Product();
    $productObject = $product->get(1234);
} catch (Exception $e) {
    die($e->getMessage());
}

// UPDATE INFO
$productForm = new FormHandler();
try {
    $productForm->navigateTo("form-handler")
        ->setEntity("Product")
        ->setVerb("add")
        ->setParameter(12334)
        ->setNavigateAfterUpdate(true)
        ->setPathSuccess("formproducts")
        ->setPathFail("home");
} catch (Exception $e) {
}

?>

<div align="center">
    <h4 >Form Products View</h4>

    <form  id="product_form" method="post"
           action="<?php try {
               $productForm->printFormAction();
           } catch (Exception $e) {
               // Choose what to do here
           } ?>">

        <!--*** Address ***-->
        <div>
            <div >
                <label for="name">Product Name<span></span></label>
                <input type="text" id="name" name="name" />
            </div>
            <br>
            <div>
                <label for="price">Product Price<span></span></label>
                <input type="text" id="price" name="price" />
            </div>
            <br>
        </div>
        <br>
        <button type="submit" id="add_product">Add Product</button>
    </form>
</div>

<div>
    <h4 align="center">Products View</h4>
    <div align="center">
        <?php

        print "<h3>Product Count {$productObject->product_count}<h3>";

        foreach($productObject->products as $product)
        {
            echo "<h5>Product Name : {$product->name}</h5>";
            echo "<h5>Product Price: {$product->price}</h5>";
        }
        ?>
    </div>
</div>