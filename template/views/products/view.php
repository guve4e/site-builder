<?php

$productObject = "";

try {
    // get info from web-api
    $rc = new PhpHttpAdapter(new RestCall("Curl", new File()));
    $rc->setServiceName('cart')
        ->setMethod('GET')
        ->setMock();

    $productObject = $rc->send();

} catch (Exception $e) {
    echo $e->getMessage();
}

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