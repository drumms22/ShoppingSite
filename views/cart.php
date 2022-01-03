

<?php

    $order = [];

    if(isset($post_results['order'])){

        if(count($post_results['order']['errors']) == 0){

            $_SESSION['cart'] = $post_results['order']['results'];

            $order = $post_results['order']['results'];

        }

    }else if(isset($_SESSION['current_user']) || isset($_SESSION['guest_user']) || isset($_COOKIE['guest_user'])){

        $params = [];
        $params['status'] = "pending";

        if(isset($_SESSION['current_user'])){
            $params['user_id'] = $_SESSION['current_user']->user_id;
        }else if(isset($_SESSION['guest_user'])){
            $params['guest_id'] = $_SESSION['guest_user'];
        }else if(isset($_COOKIE['guest_user'])){
            $params['guest_id'] = $_COOKIE['guest_user'];
        }

        $getOrder = $container->get('order')->get($params);

        if(count($getOrder['results']) > 0){
            $order = $getOrder['results'];
        }

    }

?>

<div class="col">

    <div class="row">

        <div class="col-8 border-right border-dark pt-5">
            <div class='col border-bottom border-muted text-center'>
                <h1>Your cart!</h1>
            </div>
            <?php

                if(count($order) > 0){
                    
                    $cart = reset($order);

                    if(count($cart->cart) > 0){

                        foreach($cart->cart as $item){

                            $product = reset($item->product);

                            echo "<div class='col pt-3 pb-3 border-bottom border-muted'>";
                            echo "<div class='col-8-lg offset-4'>";
                            echo "<div class='row text-center pt-1 pb-1'><h2>#$item->itemNumber</h2><h2 class='ml-4'>$product->brand $product->name</h2></div>";
                            echo "<div class='row text-center'><h3 class='pr-4 border-right border-muted'>Quantity: $item->quantity</h3><h3 class='pl-4'>Price: $item->price</h3></div>";
                            echo "<div class='row text-center'>";
                            echo"<form method='POST' action='cart'>";
                            echo "<input type='hidden' name='request' value='order/deleteFromOrder'>";
                            echo "<input type='hidden' name='cart_id' value='$item->cart_id'>";
                            echo "<input type='hidden' name='order_id' value='$cart->order_id'>";
                            echo "<input type='submit' class='btn btn-danger p-2' value='Delete Item!'>";
                            echo"</form >";
                            echo"<form method='POST' action='cart'>";
                            echo "<input type='hidden' name='request' value='order/updateOrder'>";
                            echo "<input type='hidden' name='cart_id' value='$item->cart_id'>";
                            echo "<input type='hidden' name='order_id' value='$cart->order_id'>";
                            echo "<input class='w-25 p-2 border border-dark rounded' type='number' name='quantity' maxlength='2' size='2' value='0'>";
                            echo "<input type='submit' class='p-2 ml-2 btn btn-success' value='New quantity!'>";
                            echo"</form >";
                            echo"</div>";
                            echo"</div>";
                            echo "</div>";

                        }

                    }else{
                        echo "<div class='col-6 offset-3 text-center'>";
                        echo "<h3>No items in your cart!</h3>";
                        echo "</div>";
                    }

                }else{
                    echo "<div class='col-6 offset-4 text-center'>";
                    echo "<h3>No items in your cart!</h3>";
                    echo "</div>";
                }

            ?>        

        </div>
        
        <div class="col pt-5">
            <div class='col border-bottom border-muted text-center'>
                <h1>Order details!</h1>
            </div>
                <?php

                if(count($order) == 1){

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
                    echo "<div class='col text-center'>";
                    echo "<a href='checkout'><button class='btn btn-primary mt-3 pt-2 pb-2 pl-3 pr-3'>Checkout!</button></a>";
                    echo"</div>";
                    echo"</div>";

                    }else{

                        echo "<div class='col pt-3 pb-3 text-left'>";
                        echo "<div class='col pt-3 pb-3 border-bottom border-muted'>";
                        echo "<h2><u>Subtotal:</u> $0</h2>";
                        echo "<h2><u>Tax:</u> $0</h2>";
                        echo"</div>";
                        echo "<div class='col pt-3 pb-3 border-bottom border-muted'>";
                        echo "<h2><u>Total:</u> $0</h2>";
                        echo"</div>";
                        echo "<div class='col text-center'>";
                        echo"</div>";
                        echo"</div>";

                    }

                }else{

                    echo "<div class='col pt-3 pb-3 text-left'>";
                    echo "<div class='col pt-3 pb-3 border-bottom border-muted'>";
                    echo "<h2><u>Subtotal:</u> $0</h2>";
                    echo "<h2><u>Tax:</u> $0</h2>";
                    echo"</div>";
                    echo "<div class='col pt-3 pb-3 border-bottom border-muted'>";
                    echo "<h2><u>Total:</u> $0</h2>";
                    echo"</div>";
                    echo "<div class='col text-center'>";
                    echo"</div>";
                    echo"</div>";

                }

                ?>

        </div>

    </div>

</div>