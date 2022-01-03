

<div class="col">


    <?php

    if(isset($_GET['pid'])){
        
        $products = $container->get('product')->get(["product_id" => $_GET['pid']]);

        if(count($products['results']) == 1){

            $product = reset($products['results']);

            echo '<div class="row">';
            echo '<div class="col">';
            echo "<img class='card-img-top' src='views/assets/images/" . $product->product_image .".jpg' alt='Card image cap'>";
            echo '</div>';
            echo '<div class="col">';
            echo "<h1>$product->brand $product->name</h1>";
            echo "<p>Brand: $product->brand</p>";
            echo "<p>Price: $$product->price</p>";
            echo "<p>Stock: $product->quantity</p>";
            echo "<h3>Description</h3>";
            echo "<p>$product->description</p>";
            echo "<form method='POST' action='cart'>";
            echo "<p>Quantity</p>";
            echo "<input type='number' name='quantity' value='0'>";
            if(isset($_SESSION['cart']))  echo "<input type='hidden' name='order_id' value='" . $_SESSION['cart'][0]->order_id . "'>";
            echo "<input type='hidden' name='request' value='order/addToOrder'>";
            echo "<input type='hidden' name='product_id' value='$product->product_id'>";
            if(isset($_SESSION['current_user'])){

                echo "<input type='hidden' name='user_id' value='" . $_SESSION['current_user']->user_id . "'>";

            }else if(isset($_SESSION['guest_user'])){

                echo "<input type='hidden' name='guest_id' value='" . $_SESSION['guest_user'] . "'>";

            }else if(isset($_COOKIE['guest_user'])){

                echo "<input type='hidden' name='guest_id' value='" . $_COOKIE['guest_user'] . "'>";

            }
            if(isset($_SESSION['cart'])){
                $match = false;
                foreach($_SESSION['cart'][0]->cart as $item){

                    if($item->product_id == $product->product_id){
                        $match = true;
                    }

                }

                if(!$match){
                    echo "<input class='btn btn-primary ml-3' type='submit' value='Add to cart!'>";
                }else{
                    echo "<input class='btn btn-primary ml-3' type='submit' value='Item in cart!' disabled>";
                }
            }else{
                echo "<input class='btn btn-primary ml-3' type='submit' value='Add to cart!'>";
            }

            echo "</form>";
            echo "<p class='mt-3'><a href='shop'>Go back!</a></p>";
            if(isset($_SESSION['product_search'])){
                $_SESSION['page_from'] = "details";
            } 
            echo '</div>';
            echo '</div>';

        }

    }
    ?>

</div>