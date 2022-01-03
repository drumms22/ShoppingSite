<?php


interface OrderServiceInterface {

  public function get($values);
  public function save($values);
  public function update($values);
  public function delete($values);

}
