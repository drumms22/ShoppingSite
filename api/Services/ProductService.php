<?php




class ProductService implements ProductServiceInterface {

  use Common_Methods;

  //Database service
  private $database;


  public function __construct(DatabaseService $database){
    $this->database = $database;
  }

  //Methods go below here

  public function get($values){

    foreach($values as $index => $value){
      if($value == "None selected"){
        unset($values[$index]);
      }
    }

    $query = "SELECT * FROM `products`";

    $params = []; 

    $fieldNames = [
    "product_id",
    "name",
    "description",
    "category_id",
    "brand",
    "discounted",
    "price",
    "quantity",
  ];

    $queryBuilder = $this->queryBuilder("get", $values, $fieldNames);

    if(!empty($queryBuilder['string'])){

    $query .= " WHERE " . $queryBuilder['string'];
    $params = $queryBuilder['values'];}

    if(isset($values["sort"]) && $values["sort"] != "None selected"){
      $query .= " ORDER BY " . $values["sort"];
    }
    if(isset($values["order_by"])){
            
        if($values["order_by"] == "desc"){
            $query .= " DESC";
        }
    }
    if(isset($values["group_by"])){
        $query .= "GROUP BY " . $values["group_by"];
    }

    $products = $this->database->dbGet($query, $params); //Run our query

    $this->responseMessage['results'] = $products; //Gives us our results in the array
    $this->responseMessage['message'] = "Success"; //Prints text
    return $this->responseMessage;
  }

  public function save($values){ //Base code by unknown, repurposed by Brandon Sokolowski
    $query = 'SELECT * FROM product_info WHERE product_name = ?'; //What it says on the tin

    //Set the parameter values in order of the "?" parameter
    $params = [$values["product_name"]];

    //If there is a result when we call the dbGet() method
    //Then we return the the response message.
    //Single messages should be wrapped in [ ].
    if (count($result = $this->database->dbGet($query, $params)) > 0) {
      $this->responseMessage['errors'] = ["Product already exists!"];
      return $this->responseMessage;
    }

    if(!isset($values["unit_size"])){
      $values["unit_size"] = 0;
    }
    if(!isset($values["reorder_level"])){
      $values["reorder_level"] = 0;
    }
    if(!isset($values["units_on_order"])){
      $values["units_on_order"] = 0;
    }
    if(!isset($values["discontinued"])){
      $values["discontinued"] = 0;
    }
    if(!isset($values["unit_size"])){
      $values["unit_size"] = "Not applicable";
    }
    if(!isset($values["current_discount"])){
      $values["current_discount"] = 0;
    }
    if(!isset($values["expiration"])){
      $values["expiration"] = NULL;
    }
    if(!isset($values["item_cost"])){
      $values["item_cost"] = NULL;
    }
    //Set the parameters for the query
    $params = [
      $values["category"],
      $values["product_name"],
      $values["sales_price"],
      $values["current_discount"],
      $values["vendor"],
      $values["expiration"],
      $values["unit_size"],
      $values["item_cost"],
      $values["stock"],
      $values["reorder_level"],
      $values["units_on_order"],
      $values["discontinued"]];

    //Declare empty query
    $query = "INSERT INTO `product_info` (category, product_name, sales_price, current_discount, vendor, expiration, unit_size, item_cost, stock, reorder_level, units_on_order, discontinued) VALUES (?,?,?,?,?,?,?,?,?,?,?,?)";

    //Call the database insert method
    $save = $this->database->dbInsert($query, $params);

    //If the result returns true we send a success message
    //If not we send back an error message
    if ($save) {$this->responseMessage['status_message'] = "Product creation Successful";}
    else {$this->responseMessage['errors'] = ["Product not created!"];}

    //Return the response message
    return $this->responseMessage;
  }

  public function update($values){  //Base code by Nicolas Drummonds(?), repurposed by Brandon Sokolowski

    $query = "UPDATE `product_info` SET "; //What it says on the tin

    $fieldNames = [
      "name",
      "description",
      "category_id",
      "brand",
      "discounted",
      "price",
      "quantity",
    ];

    $queryBuilder = $this->queryBuilder("update", $values, $fieldNames);

    $query .= "" . $queryBuilder['string'] . "";

    // Since we are updating we need to know where to update
    // So, we check to see if product_id is set first
    // If product_id is not then we check for product_name.
    if (isset($values['product_id'])) {
      $query .= " WHERE product_id = ?";
      array_push($queryBuilder['values'], $values['product_id']);
    }else{
      return $this->responseMessage['errors'] = ["Update not successful!"];
    }

    $update = $this->database->dbUpdate($query, $queryBuilder['values']); //Updates

    if($update){$this->responseMessage['message'] = "Update successful!";}
      else{$this->responseMessage['errors'] = ["Update not created!"];}

     //Return the response message
    return $this->responseMessage;
  }

  public function delete($values) //this will just toggle if the product is discontinued. named delete just for consistency's sake
  {

    if(isset($values['product_id'])){

      $query = "UPDATE `product_info` SET discontinued = ? WHERE product_id = ?"; //What it says on the tin

      $delete = $this->database->dbUpdate($query, [1, $values['product_id']]); //Updates

      if ($delete) {
        $this->responseMessage['message'] = "Product discontinued!";
      } else {
        $this->responseMessage['errors'] = ["Product not discontinued!"];
      }

    }else{

      $this->responseMessage['errors'] = ["Product Id required!"];
      $this->responseMessage['message'] = "Invalid data!";

    }

     //Return the response message
    return $this->responseMessage;
  }
}
