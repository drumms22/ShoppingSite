<?php


class DatabaseService implements DatabaseServiceInterface {

  /**
   * Stores the PDO object.
   *
   * @var \PDO $pdo
   */
  private $pdo;

  /**
   * {@inheritdoc}
   */
  public static function create(PDO $pdo) {
    return new static($pdo);
  }

  /**
   * Initialize a new object.
   *
   * @param \PDO $pdo
   *   The Database connection.
   */
  public function __construct(PDO $pdo) {
    $this->pdo = $pdo;
  }

  /**
   * {@inheritdoc}
   */
  public function dbGet($query, $parameters = []){

     try{

      if(empty($query)){

        throw new Exception("Invalid arguments!");

      }

      $stmt = $this->pdo->prepare($query);

      if($stmt->execute($parameters)){

        $result = $stmt->fetchAll(\PDO::FETCH_CLASS, '\stdClass');

        return $result;

      }else{

        throw new Exception("An error has occured!");

      }



    }catch(Exception $e){

      return $e;

    }

  }

  public function dbInsert($query, $parameters = []){

    try{

      $stmt = $this->pdo->prepare($query);

      if($stmt->execute($parameters)){

        $last_id = $this->pdo->lastInsertId();

        return $last_id;

      }else{

        throw new Exception("An error has occured!");

      }



    }catch(Exception $e){

      return $e;

    }

  }

  public function dbUpdate($query, $parameters = []){


    try{

      if(count($parameters) == 0 || empty($query)){

        throw new Exception("Invalid arguments!");

      }

      $stmt = $this->pdo->prepare($query);

      if($stmt->execute($parameters)){

        return true;

      }else{

        throw new Exception("An error has occured!");

      }



    }catch(Exception $e){

      return $e;

    }

  }

  public function dbDelete($query, $parameters = []){


    try{

      if(count($parameters) == 0 || empty($query)){

        throw new Exception("Invalid arguments!");

      }

      $stmt = $this->pdo->prepare($query);

      if($stmt->execute($parameters)){

        return true;

      }else{

        throw new Exception("An error has occured!");

      }



    }catch(Exception $e){

      return $e;

    }

  }

}
