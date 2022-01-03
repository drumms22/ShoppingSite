<?php 


if(isset($product['results'])){
    foreach($product['results'] as $i => $product){
        $bg = "";
        if($i % 2 == 0) $bg = "bg-light";
        echo "<div class='col pt-5 pb-5 $border' style='flex: 0 0 auto;width:$width;overflow: hidden;'>
        <div style='height:$height;' class='card " . $bg . "'>
        <img class='card-img-top' src='views/assets/images/" . $product->product_image .".jpg' alt='Card image cap'>
        <div class='card-body'>
          <h5 class='card-title'>" . $product->name . "</h5>
          <h5 class='card-subTitle'>" . $product->brand . "</h5>
          <p class='card-text'>" . $product->description . "</p>
          <p class='card-text'>$" . $product->price . "</p>
        </div>

        <div class='card-footer bg-primary text-center'>
          <a href='details?pid="  . $product->product_id . "' alt='Product item'><small class='text-white'>View item!</small></a>
        </div>
      </div></div>";
    }

}


?>