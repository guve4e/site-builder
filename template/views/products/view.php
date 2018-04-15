<?php
require_once (DATA_RESOURCE_PATH . "/Product.php");

$product = new Product();
$productObject = $product->get(1234);

?>

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