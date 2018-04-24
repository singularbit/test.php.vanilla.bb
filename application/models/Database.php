<?php

namespace APP\Models;

use APP\Model\UserException;

include_once "../application/models/UserException.php";

/**
 * MySQLi database.
 */
class Database {

	private $_connection;
	// Single instance
	private static $_instance;

	/**
	 * Instance of the Database.
	 * @return Database 
	 * 
	 */
	public static function getInstance() {
		if (!self::$_instance) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * Constructor.
	 */
	public function __construct() {

		try {
			$this->_connection = new PDO("mysql:host=localhost;dbname=burkeandbest", "root", "my5ql%@f3");
		} catch (PDOException $e) {
			UserException::errorHandler('Failed to connect to MySQL: ' . $e->getMessage, 403);
		}
	}

	/**
	 * Empty clone magic method to prevent duplication. 
	 */
	private function __clone() {
		
	}

	/**
	 * Get the mysqli connection. 
	 */
	public function getConnection() {
		return $this->_connection;
	}

	/**
	 * Close connection
	 */
	public function disconnect() {
		$this->_connection = null;
	}

}
