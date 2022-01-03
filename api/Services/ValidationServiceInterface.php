<?php


interface ValidationServiceInterface {

  /**
   * @return object
   *  The requested service object
   */

  public function validateInputs();
  public function required_input_check($values);

}
