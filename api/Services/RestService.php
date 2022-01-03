<?php


class RestService extends Rest implements RestServiceInterface {
  use Common_Methods;
  /**
   * @var Database
   *
   * Contains the Database API object.
   */
  private $service;

  /**
   * @var string
   *
   * Contains the authentication method.
   */
  private $auth;

  /**
   * {@inheritdoc}
   */
  public static function create($service) {
    return new static($service);
  }

  /**
   * Initialize a new RestService object.
   *
   * @param service
   *   The initialized service.
   */
  public function __construct($service) {
    parent::__construct();
    $this->service = $service;
  }

  /**
   * {@inheritdoc}
   */
  public function processApi() {

    if (isset($this->_request["request"]) && !empty($this->_request["request"])) {

      //Validate data from the request
      $validation = new ValidationService($this->_request);

      if(!$validation->validateInputs()){
          $this->responseMessage['errors'] = $validation->_errors;
          $this->responseMessage['message'] = "Invalid data!";
          if(isset($request['auth_token'])) $this->responseMessage['auth_token'] = $request['auth_token'];
          return $this->response($this->json($this->responseMessage),200);
      }

      $requestName = $this->authRoute($validation->_inputs)->method->name;
      $values = $validation->_inputs;

      if (method_exists($this->service, $requestName)) {

        $result = $this->service->$requestName($values);

        if(count($result['errors']) == 0){

          if(isset($request['return_token'])) $result['auth_token'] = $request['return_token'];

          $this->response($this->json($result), 200);

        }else{
          $this->response($this->json($result), 406);
        }

      }else {
        // If the method not exist with in this class, response would be "Page not found".

        $this->response($this->json($this->responseMessage['errors'] = ["Page not found!"]),404);
      }
    }
      else {
        // If the method not exist with in this class, response would be "Page not found".

        $this->response($this->json($this->responseMessage['errors'] = ["Page not found!"]),404);
      }
  }


  /**
   * Encode to json if passed data is an array.
   */
  private function json($data) {
    if (is_array($data)) {
      return json_encode($data);
    }
  }
}
