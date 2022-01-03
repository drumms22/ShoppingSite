<?php

class OrderedItemsService implements OrderedItemsServiceInterface {

  use Common_Methods;

  //Database service
  private $database;

  private $product;

  public function __construct(DatabaseService $database,ProductService $product){
    $this->database = $database;
    $this->product = $product;
  }

  public function get($values){

    $query = "SELECT * FROM ordered_items";

    $params = [];
    if(isset($values['item_id'])){

      $query .= " WHERE item_id = ?";
       $params = [$values['item_id']];

    }else if(isset($values['order_id'])){

      $query .= " WHERE order_id = ?";
       $params = [$values['order_id']];

    }else if(isset($values['product_id'])){

      $query .= " WHERE product_id = ?";
       $params = [$values['product_id']];

    }

    $items = $this->database->dbGet($query, $params);
    $id = null;
    $itemNumber = 1;

    foreach($items as $item){

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




    $this->responseMessage['results'] = $items;
    $this->responseMessage['message'] = "Success!";

    return $this->responseMessage;

  }

  public function save($values){


  }

}