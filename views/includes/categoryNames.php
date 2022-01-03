<?php

   $categories = $container->get("category")->get("");
   

   foreach($categories["results"] as $category){
       $match = false;
       foreach($categories["results"] as $sub){

            if($category->category_id == $sub->parent_id){
                $match = true;
            }

        }

        if(!$match)echo "<a href='shop?category_id=" . $category->category_id . "' style='flex: 0 0 auto;' class='text-center category-names bg-light'><span class='bg-white text-dark p-3 border border-primary shadows rounded'>" . $category->name . "</span></a>";
       
    }

?>