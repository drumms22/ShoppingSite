
<?php

/**
* comment.php
*
* I forgot to push this when I wrote it. Sorry about that.
*
* @category   PHP
* @package    CIS-222
* @author     Nicholas Drummonds <ndrummonds@hawkmail.hfcc.edu>
* @version    2020.09.19
* @link       https://cislinux.hfcc.edu/~ndrummonds/cis222/p1/
*/
?>

    <?php require('includes/mainBanner.php');?>
    
    <div class="col pt-5 pb-5 border border-notes  bg-light ">

    <?php require('includes/carousel.php');?>

    <div class="col pt-5 pb-5">
        <div class="col pt-5 pb-5 text-center">
        <h2><u>Shop by category</u></h3>
        </div>
        <div class="row pt-5 pb-5 border-top border-bottom border-notes d-flex flex-nowrap overflow-auto">

            <?php require('includes/categoryNames.php');?>
            
        </div>
    </div>
    <div class="col pt-5 p-5 border border-notes bg-light">
        <div class="row">

            <div class="col-lg-7 pt-5 pb-5 text-center banner-blue">
                <div class='p-4 banner-header-red text-white'>
                    <h1>New Customer Special!!!</h1>
                </div>
                
                <h3  class='p-4'>All new customers get 10% off thier first order!</h3>
            </div>
            <div class="col text-center banner-light text-dark">
                <div class="col pt-4 pb-4">
                    <h1>Join now!</h1>
                </div>
                <div class="col pt-4 pb-4">
                    <h3>It's as easy as 1, 2, 3!</h3>
                </div>
                <div class="col pt-4 pb-4">
                <a href="register"><button class="btn btn-primary">Sign up</button></a>
                </div>
            </div>

        </div>
    </div>
    <div class="col pt-5 pb-5 border-bottom border-notes">
        <div class="col pt-5 pb-5 text-center">
        <h2><u>Our favorites products</u></h3>
        </div>
        <div class="row pt-5 pb-5 border-top border-bottom border-notes d-flex flex-nowrap overflow-auto">

            <?php

                $product = $container->get('product')->get(["brand" => "sony"]);
                $width = "25%";
                $height = "450px";
                $border = "";
                require('includes/productCards.php');
            ?>
            
        </div>
    </div>
    <div class="col pt-5 p-5 border border-notes bg-light">
        <div class="row">

            <div class="col-lg-7 pt-5 pb-5 text-center banner-blue">
                <div class='p-4 banner-header-red text-white'>
                    <h1>New Customer Special!!!</h1>
                </div>
                
                <h3  class='p-4'>All new customers get 10% off thier first order!</h3>
            </div>
            <div class="col text-center banner-light text-dark">
                <div class="col pt-4 pb-4">
                    <h1>Join now!</h1>
                </div>
                <div class="col pt-4 pb-4">
                    <h3>It's as easy as 1, 2, 3!</h3>
                </div>
                <div class="col pt-4 pb-4">
                    <a href="register"><button class="btn btn-primary">Sign up</button></a>
                </div>
            </div>
        </div>
    </div>
    <div class="col pt-5 pb-5">
                <div class="col pt-5 pb-5 text-center">
                    <h2><u>Shop by Brand</u></h3>
                </div>
                <div class="row pt-5 pb-5 border-top border-bottom border-notes d-flex flex-nowrap overflow-auto">

                    <?php 
                       $arr = [];
                       $arr['group_by'] = "brand";
                       
                       $product_res = $container->get('product')->get($arr);
                       require('includes/productNames.php');?>
                
                </div>
            </div>




