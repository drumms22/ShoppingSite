

<div id="windowPanel" class="window-panel">
    <form class="shop-side-panel text-center display-panel bg-light border-bottom border-left border-right border-dark" action="shop" method="POST">
        <label class="pt-2" for="category_id">Filter by category:</label>
        <div class="col">
            <select id="category_id" name="category_id" class="form-control">
                <option value="None selected">None selected</option>
                <?php

                    $categories = $container->get("category")->get([]);
                    //var_dump($result );
                    foreach($categories['results'] as $i => $category){
                        
                        echo "<option style='color: black;' value='" . $category->category_id . "'>" . $category->name  . "</option>";  
                        
                    }
                ?>
            </select>
        </div>
        <label class="pt-2" for="brand">Filter by brand:</label>
        <div class="col">
            <select id="brand" name="brand" class="form-control">
                <option value="None selected">None selected</option>
                <?php
                    $arr = [];
                    $arr['group_by'] = "brand";
                    $stuff = $container->get("product")->get($arr);
                    foreach($stuff['results'] as $i => $product){
                         echo "<option style='color: black;' value='" . $product->brand . "'>" . $product->brand  . "</option>";  
                    }

                ?>
            </select>
        </div>
        <label class="pt-2" for="sort">Sort by:</label>
        <div class="col">
            <select id="sort" name="sort" class="form-control">
                <option style='color: black;' >None selected</options>
                <option style='color: black;' value="Name">Name</options>
                <option style='color: black;' value="Price">Price</options>
                <option style='color: black;' value="Quantity">Quantity</options>
                <option style='color: black;' value="created">Date added</options>
            </select>
        </div>

        <label class="pt-2" for="order_by">Order:</label>
        <div class="col">
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="order_by" id="ascending" value="asc" disabled>
            <label class="form-check-label" for="inlineRadio1">ASC</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="order_by" id="inlineRadio2" value="desc" disabled>
            <label class="form-check-label" for="inlineRadio2">DESC</label>
        </div>
        <input type="hidden" name="request" value="product/get">
        <div class="col pt-3">
        <input class="btn btn-primary" type="submit" value="Submit">
        </div>
        </div>
        
    </form>
    <div class="screen">
        <span id="screenIcon" class="screen-icon">x</span>
    </div>
</div>

