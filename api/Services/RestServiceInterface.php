<?php


interface RestServiceInterface {

  /**
   * Creates a new instance of this class.
   *
   * @param Database
   *   The initialized database object.
   */
  public static function create($database);

  /**
   * Public method to access the REST API.
   */
  public function processApi();

}
