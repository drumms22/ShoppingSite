<?php 
$post_results = [];

if(isset($_POST['request'])){

    switch($_POST['request']){
        case "user/register":
            $post_results['register'] = $container->get('utils')->router($_POST);
        break;
        case "user/login":
            $post_results['login'] = $container->get('utils')->router($_POST);
        break;
        case "user/logout":
            unset($_SESSION['current_user']);
        break;
        case "order/addToOrder":
        case "order/updateOrder":
        case "order/deleteFromOrder":
            $post_results['order'] = $container->get('utils')->router($_POST);
        break;
        case "order/processOrder":
            $post_results['orderProcessed'] = $container->get('utils')->router($_POST);
        break;
    }

}