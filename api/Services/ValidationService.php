<?php

class ValidationService implements ValidationServiceInterface {

    use Common_Methods;

    public $_inputs = [];
    public $_errors = [];
    public function __construct($data){
        $this->_inputs = $data;
    }

    public function validateInputs(){

        $errors = 0;

        if($this->required_input_check($this->_inputs) > 0){

            $errors += 1;

        }else{

            foreach($this->_inputs as $name => $data){

                if($this->validateInput($name, $data) > 0){

                    $errors += 1;

                }else if(in_array($name, ['start_date', 'end_date'])){
                     $this->_inputs[$name] = $this->RemoveSpecialChar(strip_tags(trim($this->parseDate($data))));
                }else{
                    $this->_inputs[$name] = $this->RemoveSpecialChar(strip_tags(trim($data)));
                }

            }

        }

        if($errors > 0) {
            return false;
        }
        return true;
    }

    private function validateInput($input, $data){

        $errors = 0;

        if($this->checkEmpty($input, $data) > 0){
            $errors += 1;
        }else{

            switch(strtolower($input)){
                case "state":
                    $errors += $this->checkLetter($input, $data);
                    $errors += $this->checkLength($input, $data, 2, 2);
                break;
                case "firstname":
                    $errors += $this->checkLetter("First name", $data);
                    $errors += $this->checkLength("First name", $data, 1, 30);
                break;
                case "lastname":
                    $errors += $this->checkLetter("Last name", $data);
                    $errors += $this->checkLength("Last name", $data, 1, 30);
                break;
                case "phone":
                    $errors += $this->checkNumber($input, $data);
                    $errors += $this->checkLength($input, $data, 10, 10);
                break;
                case "zipcode":
                    $errors += $this->checkNumber($input, $data);
                    $errors += $this->checkLength($input, $data, 5, 5);
                break;
                case "access_level":
                    $errors += $this->checkNumber($input, $data);
                    $errors += $this->checkAccessLevel($input, $data);
                break;
                case "email":
                    $errors += $this->checkEmail($input, $data);
                break;
                case "password":
                case "password_original":
                    $errors += $this->checkPassword($input, $data);
                break;
                case "password_confirm":
                    $errors += $this->checkPassword($input, $data);
                    $errors += $this->validatePasswordMatch($this->_inputs);
                break;
                case "birthday":
                    $errors += $this->checkDateFormat($input, $data);
                    $errors += $this->checkDateRange($input, $data, "1900-01-01", date('Y-m-d', strtotime('-18 years')));
                break;
                case "start_date":
                    $errors += $this->checkDateFormat($input, $data);
                    $errors += $this->checkDateRange($input, $data, date("Y-m-d"), date('Y-m-d', strtotime('6 months')));
                break;
                case "end_date":
                    $errors += $this->checkDateFormat($input, $data);
                    $errors += $this->checkDateRange($input, $data, date("Y-m-d"), date('Y-m-d', strtotime('6 months')));
                break;
            }

        }

        return $errors;

    }

    private function checkPassword($input, $password){

        $errors = 0;

        if(!preg_match("^\S*(?=\S{8,})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])(?=\S*[\W])\S*$^", $password)){
            $errors += 1;
            $this->createErrorMessage($input, "password");
        }

        return $errors;
    }

    private function validatePasswordMatch($values){

        $errors = 0;

        if($values['password'] != $values['password_confirm']){

            $errors += 1;
            $this->createErrorMessage("Passwords", "password_match");

        }

        return $errors;
    }

    private function checkEmpty($input, $data){

        $errors = 0;

        if(in_array($input, ['access_level', 'g-recaptcha-response','mailing_list', 'quantity','in_season','discontinued', 'current_discount','reorder_level'])){
            return $errors;
        }else
        if(empty($data)){
            $errors += 1;

            $this->createErrorMessage($input, '');
        }

        return $errors;
    }

    private function checkEmail($email, $value){

        $errors = 0;

        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $errors += 1;
            $this->createErrorMessage($email, "email");
        }

          return $errors;
    }

    private function checkLetter($input, $data){

        $errors = 0;
        if (!preg_match ("/^[a-zA-Z\s]+$/",$data)) {
            $errors += 1;
            $this->createErrorMessage($input, "letter");
        }

        return $errors;
    }

    private function RemoveSpecialChar($data) {

        return str_replace( array( '\'', '"',',' , ';', '<', '>' ), ' ', $data);

    }

    private function checkNumber($input, $data){

        $errors = 0;

        if(!is_numeric($data)){
            $errors += 1;
            $this->createErrorMessage($input, "number");
        }

        return $errors;
    }

    private function checkInt($input, $data){

        $errors = 0;

        if(!is_int((int)$data)){
            $errors += 1;
            $this->createErrorMessage($input, "int");
        }
        return $errors;
    }

    private function checkLength($input, $value, $min, $max){

        $errors = 0;

        if($min == "" || $min < 1) $min = 0;
        if($max == "") $max = 10;

        if(strlen($value) < $min || strlen($value) > $max){

            $errors += 1;
            $this->createErrorMessage($input, 'length');

        }

        return $errors;
    }

    private function checkDateFormat($input, $value){

        $errors = 0;

        $value = $this->parseDate($value);

        $dateTime = DateTime::createFromFormat("Y-m-d", $value);

        if($dateTime == false || array_sum($dateTime::getLastErrors())){

            $errors += 1;
            $this->createErrorMessage('', "date_format");

        }

        return $errors;
    }

    private function checkDateRange($input, $value, $dateMin, $dateMax){

        $errors = 0;

        $dateStr = date("Y-m-d", strtotime($value));

       if($dateStr < $dateMin || $dateStr > $dateMax){

            $errors += 1;
            $this->createErrorMessage($input, "date_range");
        }

        return $errors;
    }

    private function checkAccessLevel($input, $value){

        $errors = 0;

       if($value < 0 || $value > 4){

            $errors += 1;
            $this->createErrorMessage("access level", "access_level");

        }

        return $errors;
    }

    private function requiredInputs($inputs){

        $requiredInputs = [];

        $route = $this->authRoute($inputs);

        $requiredInputs = $route->requiredInputs;

        return $requiredInputs;

    }

    public function required_input_check($inputs){

        $errors = 0;

        $required_inputs = $this->requiredInputs($inputs);

        //Loop through all required inputs and check if they are set
        foreach($required_inputs as $input){

        //If the input is not set it will be added to the result array and returned
            if(!isset($inputs[$input])){
                $errors += 1;
                $this->createErrorMessage($input, "required");
            }

        }

        return $errors;

    }

    private function getErrorName($name){

        $msg = "";

        switch($name){
            case "letter":
                $msg = " must contain letters only!";
            break;
            case "int":
                $msg = " must contain whole numbers only!";
            break;
            case "number":
                $msg = " must contain numbers only!";
            break;
            case "email":
                $msg = " Invalid email address!";
            break;
            case "zipcode":
                $msg = " must be 5 whole numbers!";
            break;
            case "phone":
                $msg = " must be 10 whole numbers!";
            break;
            case "password_match":
                $msg = " do not match!";
            break;
            case "password":
                $msg = " must have all of the following: minimum 8 characters, 1 number, 1 special character, and 1 capitol letter!";
            break;
            case "date_format":
                $msg = "Date format must be yyyy-mm-dd or mm/dd/yyyy!";
            break;
            case "date_range":
                $msg = " out of range!";
            break;
            case "length":
                $msg = " incorrect length!";
            break;
            case "required":
                $msg = " is required!";
            break;
            case "access_level":
                $msg = " must be 0-4!";
            break;
            default:
                $msg = " cannot be empty!";
        }

        return $msg;
    }

    private function createErrorMessage($name, $errorName){

        $errorMessage = ucfirst($name) . $this->getErrorName($errorName);
        array_push($this->_errors, $errorMessage);

    }
}
