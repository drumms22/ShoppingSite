<?php


interface ContainerInterface {

  /**
   * Creates an instance of this class.
   *
   * 
   *   The database connection object.
   */
  public static function create(PDO $pdo);

  /**
   * @param string $service_name
   *  The shortcut name of the service
   *
   * @return object
   *  The requested service object
   */
  public function get($service_name);

}
