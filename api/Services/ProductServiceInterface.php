<?php

interface ProductServiceInterface {

  /**
   * @return object
   *  The requested service object
   */

  public function get($values);
  public function save($values);
  public function update($values);
  public function delete($values);

}
