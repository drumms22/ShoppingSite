<?php

   $result = $container->get("product")->get($arr);

   foreach($result["results"] as $category){
        echo "<a href='http://localhost/cis-222/p1/shop?brand=". $category->brand . "' style='flex: 0 0 auto;' class='text-center category-names bg-light'><span class='bg-white text-dark p-3 border border-primary shadows rounded'>" . $category->brand . "</span></a>";
   }

?>