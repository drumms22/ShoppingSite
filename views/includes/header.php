
<?php

/**
* comment.php
*
* I forgot to push this when I wrote it. Sorry about that.
*
* @category   PHP
* @package    CIS-222
*
* @author     Nicholas Drummonds <ndrummonds@hawkmail.hfcc.edu>
* @version    2020.09.19
* @link       https://cislinux.hfcc.edu/~ndrummonds/cis222/homework2/
*/

?>

<div class="row header">
    <div class="col-lg-2 text-center"><h1 class="logo">Lx</h1></div>
    <?php require('headerForm.php');?>
    <div class="navsbar">
        <p class="nav-logo"><a class="nav-logo-link" href="home">Lx</a></p>
        <a class="nav-li-link text-center" href="home">Home</a>
        <a class="nav-li-link text-center" href="contact">Contact</a>
        <a class="nav-li-link text-center" href="shop">Shop</a>
        <p class="nav-icons account"><a class="nav-logo-link" href="cart"><i class="fas fa-shopping-cart"></i></a>
            <?php  if(!isset($post_results['orderProcessed']) && isset($_SESSION['cart']) || isset($post_results['order'])){
                $count = 0;
                if(isset($_SESSION['cart']) && !isset($post_results['order'])){

                    foreach($_SESSION['cart'][0]->cart as $item){

                        $count += $item->quantity;

                    }
                }
                if(isset($post_results['order'])){

                    foreach($post_results['order']['results'][0]->cart as $item){

                        $count += $item->quantity;

                    }

                }
                echo "<span class='badge badge-primary'>$count</span>";
            }else{
                echo "<span class='badge badge-primary'>0</span>";
            } 
            ?>
            </p> 
        <?php if(isset($_SESSION['current_user'])){
        ?>
        <form method="POST" class="nav-icons mt-1" action='index.php' id="loginForm">

            <input type="hidden" name="request" value="user/logout" class="form-control">
            <div class="col pt-1">
                <input class="btn-light border rounded" type="submit" value="Logout!">
            </div>
        </form>
        <?php
        }?>   
    </div>
</div>


