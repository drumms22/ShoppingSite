<?php

    $order = [];

    if(isset($_SESSION['cart'])){

        $order = $_SESSION['cart'];

    }

?>


<div class="col">

    <div class="row">

        <div class="col-8 border-right border-dark pt-5">
            <?php

                if(isset($post_results['orderProcessed']) && count($post_results['orderProcessed']['errors']) == 0){
                
                    unset($_SESSION['cart']);
                    unset($_SESSION['page_from']);
                    unset($_SESSION['page_search']);

                    $order = [];
                
                    $processedOrder = $post_results['orderProcessed']['results'];
                
                    echo "<div class='col-8 offset-2 text-center'>";
                    echo "<h2 class='alert-success'>Success!</h2>";
                    echo "<h3>Order number: #" . $processedOrder[0]->order_id . "</h3>";
                    echo "<h3>Order subtotal: #" . $processedOrder[0]->subtotal . "</h3>";
                    echo "<h3>Order tax: #" . $processedOrder[0]->tax . "</h3>";
                    echo "<h3>Order total: #" . $processedOrder[0]->total . "</h3>";
                    echo "<h5 class='mt-5'>Thank you for shopping with use and we will notify you when we ship your order!</h5>";
                    echo "<a href='shop'><h4 class='text-primary mt-5'>Continue Shopping!</h4></a>";
                    echo "</div>";
                
                }else{

                    echo "<div class='col border-bottom border-muted text-center'>";
                    echo "<h1>Payment details!</h1>";
                    echo "</div>";
                    
                    if(isset($post_results['orderProcessed']) && count($post_results['orderProcessed']['errors']) > 0){
                        echo "<div class='col text-center mt-5'>";
                    foreach($post_results['orderProcessed']['errors'] as $error){

                        echo "<p class='alert-danger'>$error</p>";

                    }
                    echo "</div>";
                }
                
            ?>

        <form method="POST" action='checkout' class="col-8 offset-2">
            <div class="form-row pt-5">
                <div class="form-group col-md-6">
                <label for="inputEmail4">First name</label>
                <input type="text" name="firstname" class="form-control" value="<?php if(isset($_SESSION['current_user'])) echo $_SESSION['current_user']->firstname ?>" placeholder="John">
                </div>
                <div class="form-group col-md-6">
                <label for="inputPassword4">Last name</label>
                <input type="text" name="lastname" class="form-control" value="<?php if(isset($_SESSION['current_user'])) echo $_SESSION['current_user']->lastname ?>" placeholder="Doe">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                <label for="inputEmail4">Birthday</label>
                <input type="text" name="birthday" class="form-control" placeholder="mm/dd/yyyy">
                </div>
                <div class="form-group col-md-6">
                <label for="inputPassword4">Phone</label>
                <input type="number" name="phone" class="form-control" value="<?php if(isset($_SESSION['current_user'])) echo $_SESSION['current_user']->phone ?>" placeholder="1234567890">
                </div>
            </div>
            <div class="form-group">
                <label for="inputAddress">Street</label>
                <input type="text"  name="street" class="form-control" value="<?php if(isset($_SESSION['current_user'])) echo $_SESSION['current_user']->street ?>" placeholder="1234 Main St">
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                <label for="inputCity">City</label>
                <input type="text"  name="city" value="<?php if(isset($_SESSION['current_user'])) echo $_SESSION['current_user']->city ?>" class="form-control">
                </div>
                <div class="form-group col-md-4">
                <label for="inputState">State</label>
                <select name="state" class="form-control">
                <option value="" selected="selected">Select a State</option>
                <option value="AL">Alabama</option>
                <option value="AK">Alaska</option>
                <option value="AZ">Arizona</option>
                <option value="AR">Arkansas</option>
                <option value="CA">California</option>
                <option value="CO">Colorado</option>
                <option value="CT">Connecticut</option>
                <option value="DE">Delaware</option>
                <option value="DC">District Of Columbia</option>
                <option value="FL">Florida</option>
                <option value="GA">Georgia</option>
                <option value="HI">Hawaii</option>
                <option value="ID">Idaho</option>
                <option value="IL">Illinois</option>
                <option value="IN">Indiana</option>
                <option value="IA">Iowa</option>
                <option value="KS">Kansas</option>
                <option value="KY">Kentucky</option>
                <option value="LA">Louisiana</option>
                <option value="ME">Maine</option>
                <option value="MD">Maryland</option>
                <option value="MA">Massachusetts</option>
                <option value="MI">Michigan</option>
                <option value="MN">Minnesota</option>
                <option value="MS">Mississippi</option>
                <option value="MO">Missouri</option>
                <option value="MT">Montana</option>
                <option value="NE">Nebraska</option>
                <option value="NV">Nevada</option>
                <option value="NH">New Hampshire</option>
                <option value="NJ">New Jersey</option>
                <option value="NM">New Mexico</option>
                <option value="NY">New York</option>
                <option value="NC">North Carolina</option>
                <option value="ND">North Dakota</option>
                <option value="OH">Ohio</option>
                <option value="OK">Oklahoma</option>
                <option value="OR">Oregon</option>
                <option value="PA">Pennsylvania</option>
                <option value="RI">Rhode Island</option>
                <option value="SC">South Carolina</option>
                <option value="SD">South Dakota</option>
                <option value="TN">Tennessee</option>
                <option value="TX">Texas</option>
                <option value="UT">Utah</option>
                <option value="VT">Vermont</option>
                <option value="VA">Virginia</option>
                <option value="WA">Washington</option>
                <option value="WV">West Virginia</option>
                <option value="WI">Wisconsin</option>
                <option value="WY">Wyoming</option>
                </select>
                </div>
                <div class="form-group col-md-2">
                <label for="inputZip">Zip</label>
                <input type="text" class="form-control" value="<?php if(isset($_SESSION['current_user'])) echo $_SESSION['current_user']->zipcode ?>" name="zipcode">
                </div>
            </div>
            <div class="form-group">
                <label>Card number</label>
                <input type="number"  name="card_number" class="form-control" maxlength="16" minlength="13" placeholder="1234567891">
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                <label for="inputEmail4">Expiration</label>
                <input type="number" name="card_expiration" class="form-control" maxlength="4" minlength="4" placeholder="mmyy">
                </div>
                <div class="form-group col-md-6">
                <label for="inputPassword4">CVV</label>
                <input type="number" name="cvv" class="form-control" maxlength="3" minlength="3" placeholder="123">
                </div>
            </div>
            <input type="hidden" name="order_id" value="<?php echo $order[0]->order_id; ?>">
            <input type="hidden" name="request" value="order/processOrder">
            <div class="col text-center">
                <input type="submit" class="btn btn-primary" value="Complete order!">
            </div>
            </form>

        
<?php 
}

?>
</div>
 <div class="col pt-5">
            <div class='col border-bottom border-muted text-center'>
                <h1>Order details!</h1>
            </div>
                <?php
    
                    if(count($order) > 0){

                            $cart = reset($order);

                            if(count($cart->cart) > 0){

                            echo "<div class='col pt-3 pb-3 text-left'>";
                            echo "<div class='col pt-3 pb-3 border-bottom border-muted'>";
                            echo "<h2><u>Subtotal:</u> $$cart->subtotal</h2>";
                            echo "<h2><u>Tax:</u> $$cart->tax</h2>";
                            echo"</div>";
                            echo "<div class='col pt-3 pb-3 border-bottom border-muted'>";
                            echo "<h2><u>Total:</u> $$cart->total</h2>";

                            echo"</div>";
                            echo"</div>";

                        }
                    }else{

                        echo "<div class='col pt-3 pb-3 text-left'>";
                            echo "<div class='col pt-3 pb-3 border-bottom border-muted'>";
                            echo "<h2><u>Subtotal:</u>$0</h2>";
                            echo "<h2><u>Tax:</u> $0</h2>";
                            echo"</div>";
                            echo "<div class='col pt-3 pb-3 border-bottom border-muted'>";
                            echo "<h2><u>Total:</u> $0</h2>";
                            echo"</div>";
                            echo"</div>";

                    }

                ?>
        </div>

    </div>

</div>
