<?php

class OrderService implements OrderServiceInterface {

  use Common_Methods;

  //Database service
  private $database;
  //Cart service
  private $cart;
  //Payment service
  private $user;

  private $orderItems;

  public $subtotal = 0.00;
  public $tax = 0.06;
  public $fees = 0.00;
  public $total = 0.00;

  public function __construct(DatabaseService $database, UserService $user, CartService $cart, OrderedItemsService $orderItems){
    $this->database = $database;
    $this->user = $user;
    $this->cart = $cart;
    $this->orderItems = $orderItems;
  }

  //Methods go below here

  public function get($values){


    $query = "SELECT * FROM `order_info`";

    $params = [];

    if(isset($values['order_id'])){

      $query .= " WHERE order_id = ?";
      $params = [$values['order_id']];

    }else if(isset($values['user_id'])){

      $query .= " WHERE user_id = ?";
      $params = [$values['user_id']];

    }else if(isset($values['guest_id'])){

      $query .= " WHERE guest_id = ?";
      $params = [$values['guest_id']];

    }

    if(isset($values['status'])){

      $query .= " AND status = ?";
      array_push($params,$values['status']);

    }

    $invoice = $this->database->dbGet($query, $params);


    foreach($invoice as $item){

      if($item->user_id != null){

        $item->user = reset($this->user->get(["user_id" => $item->user_id])['results']);

      }

      $lineItems = $this->cart->get(["order_id" => $item->order_id]);

      foreach($lineItems['results'] as $lineItem){

        if(isset($lineItem->reservation)){

          unset($lineItem->reservation[0]->user);

        }

      }

      $item->cart = $lineItems['results'];

    }

    $this->responseMessage['results'] = $invoice;
    $this->responseMessage['message'] = "Success!";

    return $this->responseMessage;


  }

  private function calcOrder($values){

    $lineItems = $this->cart->get(["order_id" => $values['order_id']]);

    $subtotal = 0.00;

    if(count($lineItems['results']) > 0){

      foreach($lineItems['results'] as $item){

        $subtotal += $item->total;
  
      }

    }else{

      $subtotal = 0;

    }

    $tax = ($subtotal * $this->tax);
    (double)$total = $tax + $subtotal;

      $params = [];
      $params['order_id'] = $values['order_id'];

    if((int)$subtotal === 0){
      
      $params['tax'] = "0.00";
      $params['subtotal'] = "0.00";
      $params['total'] = "0.00";

    }else{

      $params['tax'] = $tax;
      $params['subtotal'] = $subtotal;
      $params['total'] = $total;

    }

    $update = $this->update($params);

    $updated = false;

    if($update){

      $updated = true;

    }

    return $updated;

  }

  public function save($values){

    $query = "INSERT INTO `order_info` (status, created_date) VALUES (?,?)";

    $params = ["pending", date('Y-m-d H:i:s')];

    $sale = $this->database->dbInsert($query, $params);

    if($sale){

      $this->responseMessage['results'] = ["order_id" => $sale];
      $this->responseMessage['message'] = ["Success!"];

    }else{

      $this->responseMessage['errors'] = ["Sale not complete!"];

    }

    return  $this->responseMessage;

  }

  public function update($values){

    if(!isset($values['order_id'])){


      $this->responseMessage['errors'] = ["Order cannot be found!"];
      return  $this->responseMessage;

    }

    $query = "UPDATE `order_info` SET ";

    $fieldNames = [
      "user_id",
      "guest_id",
      "subtotal",
      "tax",
      "fees",
      "total",
      "status"
    ];

    $queryBuilder = $this->queryBuilder("update", $values, $fieldNames);

    $query .= "" . $queryBuilder['string'] . "";

    $query .= ", modified_date = ?";
    array_push($queryBuilder['values'], date('Y-m-d H:i:s'));

    //Since we are updating we need to know where to update
  // So, we check to see if order_id is set first
    if(isset($values['order_id'])){
      $query .= " WHERE order_id = ?";
      array_push($queryBuilder['values'], $values['order_id']);
    }
    else{
      return $this->responseMessage['errors'] = ["Update not successful!"];
    }

    $update = $this->database->dbUpdate($query, $queryBuilder['values']);

    if($update){

      $this->responseMessage['message'] = "Update successful!";

    }else{

      $this->responseMessage['errors'] = ["Update not successful!"];

    }

    return $this->responseMessage;


  }
  public function delete($values){

      $query = "DELETE FROM order_info WHERE order_id = ?";

      $params = [$values['order_id']];

      $deleted = $this->database->dbDelete($query, $params);

      if($deleted){

        return true;

      }else{

        return false;

      }

  }

  public function startOrder(){

    $results = [];

    $orderNumber = $this->save('');

    if(count($orderNumber['errors']) == 0){

      $results['results'] = ["order_id" => $orderNumber['results']['order_id']];
      $results['errors'] = [];
      $results['message'] = "Order created";

    }else{

      $results['errors'] = ["Order not created"];
      $results['message'] = "Order not created";

    }

    return $results;

  }

  public function addToOrder($values){

    $order_id = 0;

    $message = "";

    if(!isset($values['order_id'])){

      $newId = $this->startOrder();

      if(isset($values['user_id'])){

        $this->update(['order_id' => $newId['results']['order_id'], 'user_id' => $values['user_id']]);

      }else if(isset($values['guest_id'])){

        $this->update(['order_id' => $newId['results']['order_id'], 'guest_id' => $values['guest_id']]);

      }

      if(count($newId['errors']) == 0){

        $order_id = $newId['results']['order_id'];

      }

    }else{

      $order_id = $this->checkOrder(["order_id" => $values['order_id']]);

    }

    if($order_id > 0){

      $values['order_id'] = $order_id;

      $result = [];

      $result = $this->cart->addItem($values);


      if(count($result['errors']) == 0){

        $this->calcOrder(['cart_id' => $result['results']['id'], "order_id" => $values['order_id']]);

        $message = "Item added!";

      }

      $this->get(["order_id" => $values['order_id']]);

    }else if($order_id == 0){

      $this->responseMessage['errors'] = ["Order not found!"];

      $message = "Invalid data!!";

    }

    $this->responseMessage['message'] = $message;

    return $this->responseMessage;
   
  }

  public function updateOrder($values){

    $order_id = $this->checkOrder(["order_id" => $values['order_id']]);

    if($order_id > 0){

      $values['order_id'] = $order_id;

      $result = $this->cart->update($values);

      if(count($result['errors']) == 0){

          $this->calcOrder(["order_id" => $values['order_id']]);

          $this->responseMessage['message'] = "Item updated!";

        }else{

          $this->responseMessage['errors'] = $result['errors'];
          $this->responseMessage['message'] = $result['message'];

        }

        $this->get(["order_id" => $values['order_id']]);

    }else if($order_id == 0){

      $this->responseMessage['errors'] = ["Order not found!"];

    }else{

      $this->responseMessage['errors'] = ["Order is already processed!"];
      $this->responseMessage['message'] = "Invalid data!";

    }

    return $this->responseMessage;

  }

  public function deleteFromOrder($values){

    $order_id = $this->checkOrder(["order_id" => $values['order_id']]);

    if($order_id > 0){

      $values['order_id'] = $order_id;

      $result = $this->cart->delete($values);

      $this->calcOrder(["order_id" => $values['order_id']]);

      if(isset($result['results'])){

        if(count($result['errors']) > 0){

          $this->responseMessage['errors'] = $result['errors'];
          $this->responseMessage['message'] = $result['message'];

        }else{

          $this->responseMessage['message'] = "Item updated!";

        }

        $this->get(["order_id" => $values['order_id']]);


      }else{

        $this->responseMessage['errors'] = ["Order not found!"];

      }

    }else if($order_id == 0){

      $this->responseMessage['errors'] = ["Order not found!"];

    }

    return $this->responseMessage;

  }

  public function processOrder($values){

    $order_id = $this->checkOrder(["order_id" => $values['order_id']]);

    $responseMessage = [];
    $responseMessage['results'] = [];
    $responseMessage['errors'] = [];
    $responseMessage['message'] = "";

    if($order_id > 0){

      $values['order_id'] = $order_id;

      $result = $this->cart->checkBeforeProcessing(["order_id" => $values['order_id']]);

      if($result['status']){

        $order = $this->getCurrentOrder(["order_id" => $values['order_id']]);

        if(count($order['results']) == 1){

          $processed = false;

          $params = [];


          if(isset($values['card_number']) && isset($values['card_expiration']) && isset($values['cvv'])){

            $processed = true;

          }else{

            $responseMessage['errors'] = ["Card number required!", "Card expiration required!", "Card CVV required!"];
            $responseMessage['message'] = "Invalid data!";

          }


          if($processed){

            $params['subtotal'] = $order['results'][0]->subtotal;
            $params['tax'] = $order['results'][0]->tax;
            $params['order_id'] = $order['results'][0]->order_id;
            $params['status'] = "processed";

            $update = $this->update($params);

            $this->updateOrderedItems(['order_id' =>  $values['order_id']]);

            if(count($update['errors']) == 0){

              unset($order);

              $paid = $this->getProcessedOrders(["order_id" => $values['order_id']]);
              $responseMessage['results'] = $paid['results'];

              $responseMessage['message'] = "Success! Order has been processed!";

            }else{

              $responseMessage['errors'] = ["Payment was not processed!"];
              $responseMessage['message'] = "Payment not successful!";

            }

          }

        }else{

          $responseMessage['errors'] = ["Order not found!"];
          $responseMessage['message'] = "Invalid data!";

        }


      }else{

        $responseMessage['errors'] = [$result['message']];
        $responseMessage['message'] = "Invalid data!";

      }

    }else if($order_id == 0){

      $responseMessage['errors'] = ["Order not found!"];
      $responseMessage['message'] = "Invalid data!";

    }else if($order_id == -1){

      $responseMessage['errors'] = ["Order has already been processed!"];
      $responseMessage['message'] = "Invalid data!";

    }

    return $responseMessage;


  }

  private function checkOrder($values){

    $order_id = false;

    $query = "SELECT * FROM order_info WHERE order_id = ?";

    $params = [$values['order_id']];

    $invoice = $this->database->dbGet($query, $params);

    if(count($invoice) > 0){

      if($invoice[0]->status == "pending"){

        $order_id = $invoice[0]->order_id;

      }else{

        $order_id = -1;

      }

    }

    return $order_id;

  }

  private function getCurrentOrder($values){

    $results = [];

    $query = "SELECT * FROM order_info WHERE order_id = ?";

    $params = [];
    $params = [$values['order_id']];

    $invoice = $this->database->dbGet($query, $params);

    $results['results'] = $invoice;

    return $results;


  }


  private function checkUser($values){

    $user = null;

    $result = [];

    if(isset($values['user_id'])){

      $user = $this->user->get(['user_id' => $values['user_id']]);

    }else if(isset($values['email'])){

      $user = $this->user->get(['email' => $values['email']]);

    }else{

      $user = -1;

    }

    if(!isset($result['errors']) && $user != -1){

      if(isset($user['results'][0]->user_id)){

        $result['id'] = $user['results'][0]->user_id;

      }else{

        $values['request'] = "user/save";

        $validation = $this->validate($values);

        if(!isset($validation['errors'])){

          $user = $this->user->save($values);

          $result['id'] = $user['results']['id'];

        }else{

          $result['errors'] = $validation['errors'];
          $result['message'] = "Invalid data!";

        }

      }

    }


    return $result;

  }

  private function updateOrderedItems($values){

    $cart = $this->cart->get(["order_id" => $values['order_id']]);

    foreach($cart['results'] as $cartItem){

      $params = [];
      $params['order_id'] = $cartItem->order_id;
      $params['product_id'] = $cartItem->product_id;
      $params['quantity'] = $cartItem->quantity;
      $params['price'] = $cartItem->price;
      $params['discounted'] = $cartItem->discounted;
      $params['total'] = $cartItem->total;

      $added = $this->addOrderedItems($params);

      if($added){
        $this->cart->delete(["cart_id" => $cartItem->cart_id, "status" => "processed"]);
      }

    }

  }

  private function addOrderedItems($values){

    $query = "INSERT INTO ordered_items (order_id, product_id, quantity, price, discounted, total, created_date) VALUES (?,?,?,?,?,?,?)";
      
      $params = [
        $values['order_id'],
        $values['product_id'],
        $values['quantity'],
        $values['price'],
        $values['discounted'],
        $values['total'],
        date('Y-m-d H:i:s')
      ];

      $added = $this->database->dbInsert($query, $params);

      if($added){
        return true;
      }else{
        return false;
      }

  }

  public function cancelOrder($values){

    $cart = $this->cart->get(["order_id" => $values['order_id']]);

    $order = $this->getCurrentOrder(["order_id" => $values['order_id']]);

    $deletedItems = false;

    if($order['results'][0]->status == "pending"){

      if(count($cart['results']) > 0){

        foreach($cart['results'] as $cartItem){

          $this->cart->delete(["cart_id" => $cartItem->cart_id]);

        }

      }

      $this->delete(['order_id' => $values['order_id']]);

      $this->responseMessage['message'] = "Order canceled!";

    }else if($order['results'][0]->status == "processed"){

      $this->update(['order_id' => $values['order_id'], "status" => "canceled"]);

      $this->responseMessage['message'] = "Order canceled!";
    }else{

      $this->responseMessage['errors'] = ["Order not canceled!"]; 

      $this->responseMessage['message'] = "Order not canceled!";

    }

    return $this->responseMessage;

  }

  public function getProcessedOrders($values){

    $responseMessage = [];
    $responseMessage['results'] = [];
    $responseMessage['errors'] = [];
    $responseMessage['message'] = "";

    $params = [];

    if(isset($values['order_id'])){

      $params['order_id'] = $values['order_id'];

    }

    $params['status'] = "processed";

    $orders = $this->get($params);

    if(count($orders['results']) > 0){

      foreach($orders['results'] as $order){

        if(isset($order->cart)) unset($order->cart);

        $items = $this->orderItems->get(["order_id" => $order->order_id]);

        $order->items = $items['results'];

      }

      $responseMessage['results'] = $orders['results'];

    }

    return $responseMessage;

  }

}
