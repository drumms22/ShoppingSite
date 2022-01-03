<?php

/**
 * Defines the Shop Utilities Service API.
 */
class UtilitiesService {

  use Common_Methods;

  private $user;
  private $order;

  private $service;
  private $method;

  public function __construct(UserService $user, OrderService $order) {
    $this->user = $user;
    $this->order = $order;
  }

  public function router($values){

    $result = false;

    $validation = $this->validation($values);

    if(isset($validation['errors'])){

      $result = $validation;

    }else{

      $service = $this->authRoute($values);

      switch($service->service->name){
        case "user":
          $result = $this->userService($values, $service->method->name);
        break;
        case "order":
          $result = $this->orderService($values, $service->method->name);
        break;
      }

    }

    return $result;

  }

  private function userService($values, $methodName){

    $result = [];

    $check = true;

    if($methodName == "forgotPassword"){

      if(!$this->checkCaptcha($values)){

        $result['errors'] = ["Please verify you are not a robot!"];
        $check = false;

      }

    }

    if($check){

      if(method_exists($this->user, $methodName)){

        $result = $this->user->$methodName($values);

      }else{

        $result['errors'] = ["Request cannot be completed!"];

      }

    }

    return $result;

  }

  private function reservationService($values, $methodName){

    $result = [];

    if(method_exists($this->reservation, $methodName)){

      $result = $this->reservation->$methodName($values);

    }else{

      $result['errors'] = ["Request cannot be completed!"];

    }

    return $result;

  }

  private function campsiteService($values, $methodName){

    $result = [];

    if(method_exists($this->campsite, $methodName)){

      $result = $this->campsite->$methodName($values);

    }else{

      $result['errors'] = ["Request cannot be completed!"];

    }

    return $result;

  }

  private function orderService($values, $methodName){

    $result = [];

    if(method_exists($this->order, $methodName)){

      $result = $this->order->$methodName($values);

    }else{

      $result['errors'] = ["Request cannot be completed!"];

    }

    return $result;

  }

  private function validation($values){

    $validation = new ValidationService($values);

    $result = [];

    if(!$validation->validateInputs()){
      $result['errors'] = $validation->_errors;
    }else{
      $result = true;
    }

    return $result;

  }


  private function checkCaptcha($values){

  $isValid = false;

  if(isset($_POST['g-recaptcha-response']) && !empty($_POST['g-recaptcha-response']))
  {
    $secret = CAPTCHA_API_KEY;
    $verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secret.'&response='.$_POST['g-recaptcha-response']);
    $responseData = json_decode($verifyResponse);

    if($responseData->success)
    {
        $isValid = true;
    }
    else
    {
        $isValid = false;
    }

    return $isValid;
   }

}


}//end of class
