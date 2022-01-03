<?php

class CartService implements CartServiceInterface {

  use Common_Methods;

  //Database service
  private $database;

  private $invoice;

  private $product;


  private $discounted = 0;
  private $total = 0.00;

  public function __construct(

    DatabaseService $database,

    ProductService $product


    ){

    $this->database = $database;
    $this->product = $product;

  }


  //Methods go below here

  public function get($values){

    $query = "SELECT * FROM `cart`";

    $params = [];
    if(isset($values['cart_id'])){

      $query .= " WHERE cart_id = ?";
       $params = [$values['cart_id']];

    }else if(isset($values['order_id'])){

      $query .= " WHERE order_id = ?";
       $params = [$values['order_id']];

    }else if(isset($values['product_id'])){

      $query .= " WHERE product_id = ?";
       $params = [$values['product_id']];

    }

    $cart = $this->database->dbGet($query, $params);
    $id = null;
    $itemNumber = 1;

    foreach($cart as $item){

      $item->itemNumber = $itemNumber;
      if($item->product_id != null){

        $product = $this->product->get(["product_id" => $item->product_id]);

        $unit_price = number_format($item->price, 2, '.', '');
        $item->price = $unit_price;
        $item->total = number_format($item->total, 2, '.', '');

        $item->product = $product['results'];

      }

      $itemNumber++;

    }




    $this->responseMessage['results'] = $cart;
    $this->responseMessage['message'] = "Success!";

    return $this->responseMessage;

  }

  public function save($values){

    $name = "";
    $id = null;

    $query = "INSERT INTO `cart` (order_id, product_id, quantity, price, discounted, total, created_date) VALUES(?,?,?,?,?,?,?)";

    $date = date('Y-m-d H:i:s');

    $params = [
      $values['order_id'],
      $values['product_id'],
      $values['quantity'],
      $values['price'],
      $values['discounted'],
      $values['total'],
      $date
    ];

    $item = $this->database->dbInsert($query, $params);

    if($item){

      $this->responseMessage['results'] = ["id" => $item];
      $this->responseMessage['message'] = "Item added to cart!";

    }else{

      $this->responseMessage['errors'] = ["Item not added!"];
      $this->responseMessage['message'] = "Invalid data!";

    }

    return $this->responseMessage;

  }

  public function update($values){

    $item = $this->get(["cart_id" => $values['cart_id']]);
   
    if(count($item['results']) > 0){

      $updated = false;
      $quantity = 0;
      $unit_price = 0.00;
      $discounted = 0.00;
      $total = 0.00;
      $deleted = false;
      $result = reset($item['results']);

      if($result->product_id != null){

        $product = $this->product->get(["product_id" => $result->product_id]);

        if(count($product['results']) == 1){

          if($product['results'][0]->quantity > 0){

            if($values['quantity'] <= $product['results'][0]->quantity){

              if($values['quantity'] == 0){

                $values['newQuantity'] = $product['results'][0]->quantity += $result->quantity;

                $this->delete(["cart_id" => $result->cart_id]);

                $deleted = true;

              }else if($values['quantity'] < $result->quantity){

                $values['newQuantity'] = $product['results'][0]->quantity += ($result->quantity - $values['quantity']);

              }else{

                $values['newQuantity'] = $product['results'][0]->quantity -= ($values['quantity'] - $result->quantity);

              }
              
              $quantity = $values['quantity'];
  
              $unit_price = $product['results'][0]->price;
              $total = $quantity * $unit_price;
              $values['product_id'] = $product['results'][0]->product_id;
              $updated = true;

            }else{

              $this->responseMessage["errors"] = ["Quantity can not be more than " . $product['results'][0]->quantity];
              $this->responseMessage["message"] = "Invalid data!";

            }


          }else{

            $this->responseMessage["errors"] = ["Product out of stock!"];
            $this->responseMessage["message"] = "Invalid data!";

          }


        }else{

          $this->responseMessage["errors"] = ["Product not found!"];
          $this->responseMessage["message"] = "Invalid data!";

        }

      }


      if($updated){

        $query = "UPDATE `cart` SET quantity = ?, price = ?, discounted = ?, total = ?, modified_date = ? WHERE cart_id = ?";

        $params = [
        $quantity,
        $unit_price,
        $discounted,
        $total,
        date('Y-m-d H:i:s'),
        $values['cart_id']
        ];

        $itemUpdate = $this->database->dbUpdate($query, $params);

        if($itemUpdate){

          $this->product->update(['product_id' => $values['product_id'], "quantity" => $values['newQuantity']]);

          $this->responseMessage['results'] = [];
          $this->responseMessage['message'] = "Update successful";

        }else{

          $this->responseMessage['message'] = "Update not successful";

        }

      }else if($deleted){

        $this->product->update(['product_id' => $values['product_id'], "quantity" => $values['newQuantity']]);
  
        $this->responseMessage['results'] = [];
        $this->responseMessage['message'] = "Item deleted successfully!";
  
        $this->responseMessage['errors'] = ["Item not found!"];
  
      }

    }else{

      $this->responseMessage['errors'] = ["Item not found!"];

    }

    return $this->responseMessage;

  }

  public function delete($values){

    $item = $this->get(["cart_id" => $values['cart_id']]);

    $deleted = false;

    if(count($item['results']) == 1){

     if($item['results'][0]->product_id != null){

          $product = $this->product->get(["product_id" => $item['results'][0]->product_id]);

          $updatedquantity = $product['results'][0]->quantity + $item['results'][0]->quantity;

          $params = [];

          if(isset($values['status']) && $values['status'] == "processed"){

            $params = [
              "product_id" => $item['results'][0]->product_id,
              "quantity" => $product['results'][0]->quantity
            ];
  

          }else{

            $params = [
              "product_id" => $item['results'][0]->product_id,
              "quantity" => $updatedquantity
            ];
  

          }

          $this->product->update($params);

          $deleted = true;

      }

    }

    if($deleted){

      $query = "DELETE FROM `cart` WHERE cart_id = ?";

      $params = [$values['cart_id']];

      $itemDel = $this->database->dbDelete($query, $params);

      if($itemDel){

        $this->responseMessage["results"] = [];
        $this->responseMessage["message"] = "Item deleted!";

      }else{

        $this->responseMessage["errors"] = ['Item not deleted'];
        $this->responseMessage["message"] = "Invalid data!";

      }

    }else{

      $this->responseMessage["errors"] = ["Item not found!"];
      $this->responseMessage["message"] = "Invalid data!";

    }

    return $this->responseMessage;

  }

  public function addItem($values){

    if(!isset($values['order_id'])){

      $this->responseMessage["errors"] = ["Order not found!"];
      $this->responseMessage["message"] = "Invalid data!";
      return $this->responseMessage;

    }

    $quantity = 0;
    $price = 0.00;
    $discounted = 0.00;
    $total = 0.00;
    $inserted = false;
    $updated = false;

   if(isset($values['product_id'])){

      $product = $this->product->get(["product_id" => $values['product_id']]);

      if(count($product['results']) == 1){

        if($product['results'][0]->quantity > 0){

          if($values['quantity'] > 0 && $values['quantity'] <= $product['results'][0]->quantity){

          if($this->checkProduct($values)){

            $inserted = true;
            $quantity = $values['quantity'];
            $price = $product['results'][0]->price;
            $total = $quantity * $price;
            $values['product_id'] = $product['results'][0]->product_id;


            $updatedquantity = $product['results'][0]->quantity - $values['quantity'];

            $params = [
              "product_id" => $product['results'][0]->product_id,
              "quantity" => $updatedquantity
            ];

            $this->product->update($params);
          }else{

            $this->responseMessage["errors"] = ["Product already in cart!"];
            $this->responseMessage["message"] = "Invalid data!";


          }

          }else{

            $this->responseMessage["errors"] = ["Quantity must be equal to or less than " . $product['results'][0]->quantity];
            $this->responseMessage["message"] = "Invalid data!";

          }


        }else{

          $this->responseMessage["errors"] = ["Product out of stock!"];
          $this->responseMessage["message"] = "Invalid data!";

        }


      }else{

        $this->responseMessage["errors"] = ["Product not found!"];
        $this->responseMessage["message"] = "Invalid data!";

      }

    }

    if($inserted){

      $values['quantity'] = $quantity;
      $values['price'] = $price;
      $values['discounted'] = $discounted;
      $values['total'] = $total;

      $cart = $this->save($values);

      $this->responseMessage["results"] = $cart['results'];
      $this->responseMessage["message"] = "Success!";

    }else if($updated){

      $values['quantity'] = $quantity;
      $values['price'] = $price;
      $values['discounted'] = $discounted;
      $values['total'] = $total;

      $cart = $this->update($values);

      $this->responseMessage["results"] = $cart['results'];
      $this->responseMessage["message"] = "Success!";

    }

    return $this->responseMessage;

  }

  
  private function checkProduct($values){

    $query = "SELECT * FROM cart WHERE order_id = ? AND product_id = ?";

    $params = [$values['order_id'],$values['product_id']];

    $item = $this->database->dbGet($query, $params);

    $isValid = false;

    if(count($item) == 0){

      $isValid = true;

    }

    return $isValid;

  }

  public function checkBeforeProcessing($values){

    $isValid = true;
    $message = "";

    $items = $this->get(["order_id" => $values['order_id']]);

    if(count($items['results']) > 0){

      foreach($items['results'] as $item){

       if($item->product_id != null){

          $isValid = true;
          $message = "Success!";

        }

        }

      }

      $this->responseMessage['status'] = $isValid;
      $this->responseMessage['message'] = $message;
      return  $this->responseMessage;

    }

}
