<?php

    $product_res = $container->get('product')->get(["discounted" => 1]);
    
    if(isset($product_res['results'])){
        echo "<div class='col carousel-inner'>";
        foreach($product_res['results'] as $i => $product){

            $class = "";
            if($i == 0) 
                $class="col carousel-item banner-blue active";
            else
                $class="col carousel-item banner-blue";

            echo "<div class='" . $class . "'>
            <div class='row'>
            <div class='col-sm-2 p-4 banner-red'>
            <div class='p-4 banner-header-blue'>
            <h1><span class='sale-yellow'>Sale!</span></h1>
            </div>
            <h2>10% off!!</h2>
            <h2>FOUR<br> Days <br> Only!</h2>
            </div>
            <div class='col-sm-4 p-4 banner-light'>
            <img class='img-fluid' src='views/assets/images/" . $product->product_image . ".jpg' alt='Responsive image'>
            </div><div class='col p-4 banner-light'>
            <h3 class='text-dark'>" . $product->name . "</h3>
            <p class='text-dark'>" . $product->brand . "</p>

            <p class='text-dark'>$" . $product->price . "</p>
            <a href='details?pid=$product->product_id'><button class='btn btn-primary'>View!</button></a>
            </div>
            </div>
            </div>";
        }

        echo "</div></div><ol class='carousel-indicators'>";
        for($i = 0;$i < count($product_res['results']);$i++){
        echo "<li data-target='#carouselExampleIndicators' data-slide-to='" . $i . "'></li>";
        }
        echo "</ol></div>";

    }
?>