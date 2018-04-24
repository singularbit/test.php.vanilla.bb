<?php

namespace APP\Models;

/**
 * Custom Exception handler. 
 */
class UserException extends \Exception {

	/**
	 * Magic __toString().
	 * @return string 
	 */
	public function __toString() {
		return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
	}

	/**
	 * Error Handler
	 * 
	 * @param type $message
	 * @param type $code
	 */
	public static function errorHandler($message, $code) {

		// @TODO: log this error

		header('X-PHP-Response-Code: ' . $code, true, $code);
		die(json_encode(["message" => $message, "code" => $code]));
	}

}
