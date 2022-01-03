<?php


class CategoryService implements CategoryServiceInterface {
    use Common_Methods;
  /**
   * Stores the PDO object.
   *
   * @var \PDO $pdo
   */
  private $database;

  /**
   * {@inheritdoc}
   */

  /**
   * Initialize a new object.
   *
   * @param 
   *   The Database connection.
   */
  public function __construct(DatabaseService $database) {
    $this->database = $database;
  }

  /**
   * {@inheritdoc}
   */
  public function get($values){

    if($values){

        foreach($values as $index => $value){
            if($value == "None selected"){
                unset($values[$index]);
            }
        }

    }
  
      $query = "SELECT * FROM `categories`";
  
      $params = []; 
  
      $fieldNames = [
      "id",
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
      $params = $queryBuilder['values'];
    }
  
      $categories = $this->database->dbGet($query, $params); //Run our query
  
      $this->responseMessage['results'] = $categories; //Gives us our results in the array
      $this->responseMessage['message'] = "Success"; //Prints text
      return $this->responseMessage;

  }


  public function create($values){
      
        $parent_id = 0;
        if(isset($values["parent_id"])) $parent_id = intval($values["parent_id"]);

        $query = "INSERT INTO `categories` (`parent_id`, `name`, `description`) VALUES (:parent_id, :name, :description)";

        $stmt = $this->pdo->prepare($query);

        $stmt->bindParam(":name", $values["name"]);
        $stmt->bindParam(":parent_id", $parent_id);
        $stmt->bindParam(":description", $values["description"]);

        try {

            if($stmt->execute()){
                return array("success" => array(array("msg" => "Category created!")));
            }else{
                return array("errors" => array(array("msg" => "Category not created!")));
            }
        
            
        } catch (Exception $e) {
            return array("errors" => array(array("msg" => "Server error!")));
        }
  }

  public function set($values){

        $query = "UPDATE `categories` SET";
        $newValues = [];

        if(isset($values["name"])){
            $query .= " name = ?";
            array_push($newValues, $values["name"]);
        }else if(isset($values["parent_id"])){
            $query .= " parent_id = ?";
            array_push($newValues, $values["parent_id"]);
        }else if(isset($values["description"])){
            $query .= " description = ?";
            array_push($newValues, $values["description"]);
        }

        $query = $query . " WHERE `id` = ?";
        array_push($newValues, $values["category_id"]);
        $stmt = $this->pdo->prepare($query);

        try {

            if($stmt->execute($newValues)){
                return array("success" => array(array("msg" => "Update successful!")));
            }else{
                return array("errors" => array(array("msg" => "Update not successful!")));
            }
        
            
        } catch (Exception $e) {
            return array("errors" => array(array("msg" => "Server error $e!")));
        }
   
  }


}
