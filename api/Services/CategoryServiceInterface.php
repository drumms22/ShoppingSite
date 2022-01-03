<?php
/**
 * Defines the REST Service Interface.
 */
interface CategoryServiceInterface {

  /**
   * Creates a new instance of this class.
   *
   * @param Database
   *   The initialized database object.
   */


  public function get($values);
  
  public function set($values);
  
  public function create($values);


}