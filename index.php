
<?php   

/**
 * index.php
 *
 * Project Part 2
 *
 * @category   	P2
 * @package    	CIS-222
 * @author     	Nicholas Drummonds <ndrummonds@hawkmail.hfcc.edu>
 * @version    	2020.12.14
 * @link       	https://cislinux.hfcc.edu/~ndrummonds/cis222/p2/index.php
 *
 */

include("commonFile.php"); ?>
<!DOCTYPE html>
<html>
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="views/css/mains.css">
<script
  src="https://code.jquery.com/jquery-3.5.1.min.js"
  integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0="
  crossorigin="anonymous"></script>
  <script
  src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"
  integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU="
  crossorigin="anonymous"></script>
<link href="https://fonts.googleapis.com/css2?family=Merriweather:ital,wght@1,900&display=swap" rel="stylesheet">
<script src="https://kit.fontawesome.com/f9a072afa5.js" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js" integrity="sha384-LtrjvnR4Twt/qOuYxE721u19sVFLVSA4hf/rRt6PrZTmiPltdZcI7q7PXQBYTKyf" crossorigin="anonymous"></script>
<script>
  $( function() {
    $('#birthdayDate').datepicker({changeYear: true, yearRange: "-100:+0",dateFormat: 'mm/dd/yy'});
  } );
  </script>
<title>The Shop</title>

</head>
<body>
<?php require('views/includes/shopSidePanel.php'); ?>
<div class="container-fluid main-content">
    <?php require('views/includes/header.php');?>

<?php

$x = parse_url($_SERVER['REQUEST_URI']);

switch ($x['path']) {
    
    case "/~ndrummonds/cis222/p2/" :
        require __DIR__ . '/views/home.php';
        break;
    case '/~ndrummonds/cis222/p2/home' :
        require __DIR__ . '/views/home.php';
        break;
    case '/~ndrummonds/cis222/p2/contact' :
        require __DIR__ . '/views/contact.php';
        break;
    case '/~ndrummonds/cis222/p2/shop' :
        require __DIR__ . '/views/shop.php';
        break;
    case '/~ndrummonds/cis222/p2/details' :
        require __DIR__ . '/views/details.php';
        break;
    case '/~ndrummonds/cis222/p2/register' :
        require __DIR__ . '/views/register.php';
        break;
    case '/~ndrummonds/cis222/p2/cart' :
        require __DIR__ . '/views/cart.php';
        break;
    case '/~ndrummonds/cis222/p2/checkout' :
        require __DIR__ . '/views/checkout.php';
        break;
    default:
        require __DIR__ . '/views/home.php';
        break;
}

?>

<?php  require('views/includes/footer.php');?>
</div>
<script type="text/javascript" src="views/scripts/scroll.js" ></script>


</body>
</html>
