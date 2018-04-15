<?php
    $user = new StdClass;
    $user->U_ID = 1;
?>

<div align="center">
    <h4 >Form View</h4>

    <form  id="checkout" method="post"
           action="form-handler.php?view=form&from=cart&subView=post_cart&paramName=<?php $user->U_ID ?>">

        <!--*** Address ***-->
        <div>
            <div >
                <label for="name">Name<span>*</span></label>
                <input type="text" id="name" name="name" />
            </div>
            <br>
            <div>
                <label for="phone_number">Phone Number<span>*</span></label>
                <input type="text" id="phone_number" name="phone_number" />
            </div>
            <br>
        </div>
        <div>
            <div>
                <label for="address1">Address<span>*</span></label>
                <input type="text" id="address1" name="address1" />
            </div>
            <br>
            <div>
                <label for="address2">Address Line 2</label>
                <input type="text" id="address2" name="address2" />
            </div>
            <br>
        </div>
        <div>
            <div>
                <label for="city">City<span>*</span></label>
                <input type="text" id="city" name="city" />
            </div>
            <br>
            <div>
                <label for="state">State<span>*</span></label>
                <input type="text" id="state" name="state" />
            </div>
            <br>
            <div>
                <label for="zip">ZIP<span>*</span></label>
                <input type="text" id="zip" name="zip" />
            </div>
            <br>
            <input type="hidden" name="amount" id="amount">
            <input type="hidden" name="shipping_option" id="shipping_option">
        </div>
        <br>
        <div>
            <div>
                <label for="email">Email<span>*</span></label>
                <input type="text" id="email" name="email" />
                <br>
            </div>

        </div>
        <br>
        <button type="submit" id="buy_button">Buy</button>
    </form>
</div>