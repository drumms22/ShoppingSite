<?php


interface DatabaseServiceInterface {

  /**
   * Creates an instance of this class.
   *
   * @param \PDO $pdo
   *   The database connection object.
   */
  public static function create(PDO $pdo);

  public function dbGet($query, $params);
  public function dbUpdate($query, $params);
  public function dbInsert($query, $params);
  public function dbDelete($query, $params);

  /**
   * Verifies user login credentials and returns matching user object.
   *
   * @param string $username
   *   The user's login name.
   * @param string $password
   *   The user's password.
   *
   * @return \StdClass|null
   *   Returns an object containing the users' information on success,
   *   or null on failure.
   */

}
