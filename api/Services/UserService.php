<?php

/**
 * Defines the Shop User Service.
 */
class UserService implements UserServiceInterface {
//Trait
   use Common_Methods;
  /**
   * Stores the PDO object.
   *
   * @var \PDO $pdo
   */
  //DatabaseService
  private $database;

  /**
   * {@inheritdoc}
   */
  /**
   * Initialize a new object.
   *
   * @param \PDO $pdo
   *   The Database connection.
   */
  public function __construct(DatabaseService $database) {
    $this->database = $database;
  }

  /**
   * {@inheritdoc}
   */

   //All methods and funtions go below here--------------------

  //User login
  public function login($values) {
    //We need to check and see if they user exists
    $query = 'SELECT * FROM user_accounts WHERE email = ?';
    //Set the parameters
    $params = [$values["email"]];
    //if there is aressult we check the password
    if($result = $this->database->dbGet($query, $params)){
      // Query should returns an array of one object. We only want the first element.
      $user = reset($result);

      if (password_verify($values['password'], $user->password)) {

        unset($user->password);

        $this->responseMessage['results'] = $user;

        $this->responseMessage['message'] = "Success!";

        } else {
          // If passwords do not match
          $this->responseMessage['errors'] = ["Bad credentals!"];
          $this->responseMessage['message'] = "Bad credentals!";
        }

    }else{
      //If user doesnt exist
      $this->responseMessage['errors'] = ["Bad credentals!"];
      $this->responseMessage['message'] = "Bad credentals!";
    }
    //Return response message
    return $this->responseMessage;
  }

  //Create a USER in the database
  public function save($values){

    //Write the query
      $query = 'SELECT * FROM user_accounts WHERE email = ?';
    //Set the perameter values in oreder of the "?" parameter
      $params = [$values["email"]];
    //If there is a result when we call the dbGet() method
    //Then we return the the response message.
    //Single messages should be wrapped in [ ].
      if($result = $this->database->dbGet($query, $params)){
        $this->responseMessage['errors'] = ["User already exists!"];
        return $this->responseMessage;
      }

      //Set the system generated parameters
      if(!isset($values["access_level"])) $values["access_level"] = 0;
      if(!isset($values["mailing_list"])) $values["mailing_list"] = 0;
      $values['birthday'] = date("Y-m-d", strtotime($values['birthday']));
      $values['created'] = date('Y-m-d H:i:s');
      $values['state'] = strtoupper($values["state"]);
      //Set ther parameters for the query
      $params = [
        $values["email"],
        $values["firstname"],
        $values["lastname"],
        $values["birthday"],
        $values["phone"],
        $values["street"],
        $values["city"],
        $values["state"],
        $values["zipcode"],
        $values["access_level"],
        $values["mailing_list"],
        $values["created"],
      ];
    ////Declare empty query
      $query = "";
      //If the user sends a password we insert the required fields plus the password
      //If it is not set we just save the required data
      if(isset($values['password'])){
        $values["password"] = password_hash($values["password"], PASSWORD_DEFAULT);
        array_splice( $params, 1, 0, $values["password"] );
        $query = "INSERT INTO `user_accounts` (email,password,firstname, lastname, birthday, phone,street,city,state,zipcode, access_level, mailing_list, created_date) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)";
      }else{
        $query = "INSERT INTO `user_accounts` (email,firstname, lastname, birthday, phone,street,city,state,zipcode,access_level, mailing_list, created_date) VALUES (?,?,?,?,?,?,?,?,?,?,?,?)";
      }
      //Call the database insert method
      $result = $this->database->dbInsert($query, $params);
      //If the result returns true we send a success message and the last inserted id
      //If not we send back an error message
      if($result){

        $this->responseMessage['results'] = ["id" => $result];
        $this->responseMessage['message'] = "Success";

      }else{

        $this->responseMessage['errors'] = ["User not created!"];

      }
      //Return the response message
      return $this->responseMessage;

    }



  public function register($values){

    //we need to check if the user exists already
    //When the get method allows to select by email
    //We will just use $user = $this->get($params);
    $query = "SELECT * FROM `user_accounts` WHERE email = ?";

    $params = [$values['email']];

    $user = $this->database->dbGet($query, $params);
    //If the user exists and has an account we return
    if(count($user) > 0 && $values["email"] == $user[0]->email && $user[0]->password != null){

      $this->responseMessage['errors'] = ["Account already exisits!"];

    }else{
    //Set email object and dateTime
      $date = date('Y-m-d H:ia');
      $emailContents = [];
      $emailContents['subject'] = "Copper Country Account Registration!";
      $emailContents['date'] = $date;
    //Check if there is no user then we insert all the data
      if(count($user) == 0){

        $values['request'] = "user/save";

        $validate = $this->validate($values);

        if(isset($validate['errors'])){
          $this->responseMessage['errors'] = $validate['errors'];
          $this->responseMessage['message'] = "Invalid data!";
          return $this->responseMessage;

        }

        //Call the save method
        $account = $this->save($values);
        //Check if there were no errors
        //If none exist then send the email and set the response message
          if(count($account['errors']) == 0){
          
            $this->responseMessage['message'] = "Success!";

          }else{

            $this->responseMessage['errors'] = "Account not created!";

          }

        }else{

          $values['request'] = "user/update";
          $values['user_id'] = $user[0]->user_id;

          $validate = $this->validate($values);

          if(isset($validate['errors'])){
            $this->responseMessage['errors'] = $validate['errors'];
            $this->responseMessage['message'] = "Invalid data!";
            return $this->responseMessage;

          }
          //If the user exists but has not created an accout we hash the password
          //And we write query, parameters
          $values["password"] = password_hash($values["password"], PASSWORD_DEFAULT);

          $query = "UPDATE `user_accounts`SET password = ?, modified_date = ? WHERE user_id = ?";

          $params = [$values['password'],$date,$user[0]->user_id];

          $account = $this->database->dbUpdate($query, $params);
          //If $account is true we set a success message and send an email
          if($account){
        
            $this->responseMessage['message'] = "Success!";


          }else{


            $this->responseMessage['errors'] = "Account not created!";

          }


        }

      }
    //Return the response message
    return $this->responseMessage;

  }



  public function forgotPassword($values){

    $query = "SELECT * FROM `user_accounts` WHERE email = ?";

    $params = [$values['email']];

    $user = $this->database->dbGet($query, $params);
    //Check if user exists
    if(count($user) == 0){

      $this->responseMessage['errors'] = ["No account found!"];
      return $this->responseMessage;

    }
    //Get the first user of the array
    $user = reset($user);

    $params = [
      "user_id" => $user->user_id,
      "email" => $values['email']
    ];

    $token = $this->auth->getPasswordResetToken($params);

    if(!$token){

      $this->responseMessage['errors'] = ["Please check your email!"];
      return $this->responseMessage;

    }

    $msg = "Success! Please check your email!";
    $emailContents = [];
    $emailContents['email'] = $values['email'];
    $emailContents['subject'] = "Copper Country Password Reset!";
    $emailContents['auth_token'] = $token['token'];
    $email_send = $this->email->create("forgot_password", $emailContents);
    $email_send = $this->email->send();


    $this->responseMessage['message'] = $msg;
   // $this->responseMessage['auth_token'] = $token['token'];


    return $this->responseMessage;

  }

  public function passwordReset($values){


    if($values['password'] != $values['password_confirm']){

      $this->responseMessage['errors'] = ["Passwords do not match!"];
      return $this->responseMessage;

    }else if(!isset($values['auth_token'])){

      $this->responseMessage['errors'] = ['Invalid credentials!'];
      $this->responseMessage['message'] = 'Access denied!';
      return $this->responseMessage;

    }else if(!isset($values['email'])){

      $this->responseMessage['errors'] = ["Email required!"];
      return $this->responseMessage;

    }

    $token = $this->auth->authToken(["auth_token" => $values['auth_token']]);

    if(!$token){

      $this->responseMessage['errors'] = ['Invalid credentials!'];
      $this->responseMessage['message'] = 'Access denied!';
      return $this->responseMessage;

    }

    $values['password'] = password_hash($values["password"], PASSWORD_DEFAULT);

    $query = "UPDATE `user_accounts` SET password = ? WHERE user_id = ?";

    $params = [$values['password'],$token['uid']];

    $user = $this->database->dbUpdate($query, $params);

    $delete = $this->auth->deleteToken($values['auth_token']);

    if($user && $delete){

      $emailContents = [];
      $emailContents['email'] = $values['email'];
      $emailContents['subject'] = "Copper Country Password Reset success!";
      $email_send = $this->email->create("password_reset", $emailContents);
      $email_send = $this->email->send();

      $this->responseMessage['message'] = "Password reset successful!";

    }else{

      $this->responseMessage['errors'] = ["Password reset not successful!"];


    }

    return $this->responseMessage;

  }

  public function get($values) //Brandon Sokolowski & Nicholas Drummonds
  {
    $query = "SELECT * FROM `user_accounts`"; //what it says on the tin

    $params = []; //Its an array. An array for user information.

    if (isset($values['search'])) {

      switch($values['search']){
        case "employees":
          $query .= " WHERE access_level > 0";
        break;
        case "customers":
          $query .= " WHERE access_level = 0";
        break;
      }

    }else{

        $fieldNames = [
        "user_id",
        "email",
        "firstname",
        "lastname",
        "birthday",
        "phone",
        "street",
        "city",
        "state",
        "zipcode",
        "access_level",
        "mailing_list"
      ];

      $queryBuilder = $this->queryBuilder("get", $values, $fieldNames);

      if(!empty($queryBuilder['string'])){

        $query .= " WHERE " . $queryBuilder['string'];
        $params = $queryBuilder['values'];

      }

    }


    $result = $this->database->dbGet($query, $params); //Run our query.

    //Unset the password before returning the data
    foreach($result as $user){
      unset($user->password);
    }

    $this->responseMessage['results'] = $result; //Gives us our results in the array
    $this->responseMessage['message'] = "Success"; //Prints text
    return $this->responseMessage;
  }

  public function update($values){

    //We need to make sure that the user_id or email is set before allowing them to proceed
    if(!isset($values['user_id']) && !isset($values['email'])){


      $this->responseMessage['errors'] = ["User cannot be found!"];
      return  $this->responseMessage;

    }

    $query = "UPDATE `user_accounts` SET ";

    $fieldNames = [
      "firstname",
      "lastname",
      "birthday",
      "phone",
      "street",
      "city",
      "state",
      "zipcode",
      "access_level",
      "mailing_list"
    ];

    $queryBuilder = $this->queryBuilder("update", $values, $fieldNames);

    $query .= "" . $queryBuilder['string'] . "";

    $query .= ", modified_date = ?";
    array_push($queryBuilder['values'], date('Y-m-d H:i:s'));

  //Since we are updating we need to know where to update
  // So, we check to see if user_id is set first
  // If user_id is not then we check for email.
    if(isset($values['user_id'])){
      $query .= " WHERE user_id = ?";
      array_push($queryBuilder['values'], $values['user_id']);
    }else if(isset($values['email'])){
      $query .= " WHERE email = ?";
      array_push($queryBuilder['values'], $values['email']);
    }else{
      return $this->responseMessage['errors'] = ["Update not successful!"];
    }

    $update = $this->database->dbUpdate($query, $queryBuilder['values']);

    if($update){

      if(isset($values['client']) && $values['client'] == "main_site"){

        $emailContents = [];
        $emailContents['email'] = $values['email'];
        $emailContents['subject'] = "Copper Country Account Change!";
        $email_send = $this->email->create("user_updated", $emailContents);
        $email_send = $this->email->send();

      }

      $user = $this->get(["user_id" => $values['user_id']]);

      $this->responseMessage['results'] = $user['results'];

      $this->responseMessage['message'] = "Update successful!";

    }else{

      $this->responseMessage['errors'] = ["Update not successful!"];

    }

    return $this->responseMessage;

  }

  public function test($values){

    if(isset($values['auth_token'])){

      $result = $this->get($values);

      $this->responseMessage['results'] = $result['results'];

      $this->responseMessage['message'] = "Route successful!";

    }else{

      $this->responseMessage['errors'] = ["Route not successful!"];

    }

    return $this->responseMessage;

  }

  public function logout($values){

    $deleted = $this->auth->deleteToken($values['auth_token']);

    if($deleted){

      $this->responseMessage['message'] = "Logout successful!";

    }else{

      $this->responseMessage['errors'] = ["Logout not successful!"];

    }

    return $this->responseMessage;

  }

  public function contact($values){

    $query = "INSERT INTO `contact` (contact_email, contact_firstname, contact_lastname, contact_message, date_created) VALUES (?, ?, ?, ?, ?)";

    $params = [
      $values['contact_email'],
      $values['contact_firstname'],
      $values['contact_lastname'],
      $values['contact_message'],
      date('Y-m-d H:ia')
    ];

    $contact = $this->database->dbInsert($query, $params);

    if($contact){

      $this->responseMessage['message'] = "Success! message sent!";

    }else{

      $this->responseMessage['message'] = "Error! Message not sent!";

    }

    return $this->responseMessage;

  }


}//End of class
