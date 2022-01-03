
<div class='col pt-5 pb-5'>
<h3 id="filterIcon" class='filter-icon text-center pt-2 pb-2'>Filters</h3>
    <?php

        $values = [];
        if(isset($_GET) && count($_GET) > 0){
            $values = $_GET;
            $_SESSION['product_search'] = $_GET;
        }else 
        if(isset($_POST['request']) && $_POST['request'] = "product/get"){
            $values = $_POST;
            $_SESSION['product_search'] = $_POST;
        }else if(isset($_SESSION['product_search']) && isset($_SESSION['page_from']) && $_SESSION['page_from'] == "details"){
            $values = $_SESSION['product_search'];
            unset($_SESSION['page_from']);
        }else if(isset($_SESSION['product_search']) && !isset($_SESSION['page_from'])){
            $values = $_SESSION['product_search'];
            unset($_SESSION['product_search']);
        }

        echo "<div class='row dets pb-5 d-flex flex-wrap'>";

        $product = $container->get('product')->get($values);
        $width = "20%";
        $height = "450px";
        $border = "border-top border-notes";
        require('includes/productCards.php');
        echo "</div>";
    ?>
</div>
<script type="text/javascript" src="views/scripts/main.js" ></script>
