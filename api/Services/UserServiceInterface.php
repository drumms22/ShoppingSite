<?php

interface UserServiceInterface {

  /**
   * Creates an instance of this class.
   *
   * @param \PDO $pdo
   *   The database connection object.
   */


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
  //public function get($values);
  public function login($values);
  public function save($values);
}
