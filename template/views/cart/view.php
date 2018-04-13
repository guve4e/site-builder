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
    <h4 align="center">Cart View</h4>
    <div align="center">
        <?php

            echo "Product Count : {$productObject->product_count}\n";
            echo "-----------------------------------------------\n";

            foreach($productObject->products as $product)
            {
                echo "Product Name : {$product->name}";
                echo "Product Price: {$product->price}";
            }
         ?>
    </div>
</div>
