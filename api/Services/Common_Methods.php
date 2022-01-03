<?php

//This Trait holds all of our common functions that we need.
//Just put use Common_Methods; in the class you want to use it in.
trait Common_Methods {

  //Global response message
  public $responseMessage = [
    'results' => [],
    'errors' => [],
    'message' => ""
  ];

  public function get_api_route($num){

    $res = "";

    if(isset($_POST["request"])){

      $request = explode("/", $_POST["request"]);

      if(count($request) > 1){

        $res = $request[$num];

      }

    }

      return $res;
  }

  public function queryBuilder($type, $values, $fieldNames){

    $data = [];
    $data['values'] = [];
    $data['string'] = "";
    $dilemeter = "";

    switch($type){
      case "update":
        $dilemeter = ", ";
      break;
      case "get":
        $dilemeter = " AND ";
      break;
    }

    $strings = [];

    foreach($fieldNames as $field){

      $check = $this->checkFieldName($field, $values);

      if($check){

        array_push($strings, "$field = ?");
        array_push($data['values'], $check);

      }

    }

    $data['string'] .= "" . implode($dilemeter, $strings) . "";

    return $data;

  }

  public function checkFieldName($field, $values){

    $result = false;

    if(isset($values[$field])){
      $result = $values[$field];
    }

    return $result;

  }

  public function validate($values){

    $message = [];

    $validation = new ValidationService($values);

    if($validation->required_input_check($values) > 0){
      $message['errors'] = $validation->_errors;
    }else{
      $message['clean_inputs'] = $validation->_inputs;
    }

    return $message;
  }

  public function authRoute($values){

    $json = file_get_contents("http://localhost/cis-222/p2/api/json/routes.json");

    $routes = json_decode($json);

    $newRoute = null;

    foreach($routes->routes as $routeItem){

      if($values['request'] == $routeItem->route){

        $newRoute = $routeItem;

      }

    }

    return $newRoute;

  }

  public function parseDate($date){

    if(preg_match("^\S*(0[1-9]|1[012])[- /.](0[1-9]|[12][0-9]|3[01])[- /.](19|20)\d\d\S*$^", $date)){

        $dateArr = explode('/', $date);

        $date = trim($dateArr[2]) . "-" . trim($dateArr[0]) . "-" . trim($dateArr[1]);

      }

      return $date;

  }

  public function getNumDays($values){

    $days = 0;

    if(isset($values['start_date']) && isset($values['end_date'])){

      $date1 = date_create($values['start_date']);
      $date2 = date_create($values['end_date']);
      $date = date_diff($date1,$date2);

      $days = $date->d;

    }

    return $days;
  }

}//End of trait




?>
