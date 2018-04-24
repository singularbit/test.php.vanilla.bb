<?php

namespace APP\Models;

class Player {

	const PLAYER_STATUS_INACTIVE = 0;
	const PLAYER_STATUS_ACTIVE = 1;
	const PLAYER_STATUS_DEAD = -1;
	const PLAYER_TYPE_FRIEND = 0;
	const PLAYER_TYPE_FOE = 1;
	const PLAYER_COLOUR_GREEN = 1;
	const PLAYER_COLOUR_YELLOW = 2;
	const PLAYER_COLOUR_RED = 3;

	private $_initial_health = 10;
	private $_player_health = 10;
	private $_player_status = 0;
	private $_player_speed;
	private $_player_type;
	private $_player_health_colour = 1;
	private $_player_position_x = 0;
	private $_player_position_y = 0;
	// Player statuses.
	static public $valid_player_status = [
		Player::PLAYER_STATUS_INACTIVE => 'Inactive',
		Player::PLAYER_STATUS_ACTIVE => 'Active',
		Player::PLAYER_STATUS_DEAD => 'Dead',
	];
	// Player types.
	static public $valid_player_types = [
		Player::PLAYER_TYPE_FRIEND => 'Friend',
		Player::PLAYER_TYPE_FOE => 'Foe',
	];
	// Player colours.
	static public $valid_player_health_colours = [
		Player::PLAYER_COLOUR_GREEN => '#33cc33',
		Player::PLAYER_COLOUR_YELLOW => '#ffff00',
		Player::PLAYER_COLOUR_RED => '#ff0000'
	];

	/**
	 * Get the player's health
	 * 
	 * @return int
	 */
	public function getPlayerHealth() {
		$this->_setPlayerHealthColour($this->_player_health);
		return $this->_player_health;
	}

	/**
	 * Validate the player status id
	 * 
	 * @param int $player_status_id
	 * @return bool
	 */
	static public function isValidPlayerStatusId($player_status_id) {
		return array_key_exists($player_status_id, self::$valid_player_status);
	}

	/**
	 * Set the player status id
	 * 
	 * @param int $player_status_id
	 */
	public function setPlayerStatusId($player_status_id) {
		if (self::isValidPlayerStatusId($player_status_id)) {
			$this->_player_status = $player_status_id;
		}
	}

	/**
	 * Get the player status id
	 * 
	 * @return int
	 */
	public function getPlayerStatusId() {
		return $this->_player_status;
	}

	/**
	 * Set the player speed
	 * 
	 * @param int $speed
	 */
	public function setSpeed($speed) {
		$this->_player_speed = $speed;
	}

	/**
	 * Validate the player type id
	 * 
	 * @param int $player_type_id
	 * @return bool
	 */
	static public function isValidPlayerTypeId($player_type_id) {
		return array_key_exists($player_type_id, self::$valid_player_types);
	}

	/**
	 * Set the player type id
	 * 
	 * @param int $player_type_id
	 */
	public function setPlayerTypeId($player_type_id) {
		if (self::isValidPlayerTypeId($player_type_id)) {
			$this->_player_type = $player_type_id;
		}
	}

	/**
	 * Get the player type id
	 * 
	 * @return int
	 */
	public function getPlayerTypeId() {
		return $this->_player_type;
	}

	/**
	 * Set the player's health colour
	 * 
	 * @return string
	 */
	public function _setPlayerHealthColour() {

		if ($this->getPlayerHealth() > $this->_initial_health / 3) {
			$this->_player_health_colour = Player::PLAYER_COLOUR_RED;
			return;
		}
		if ($this->getPlayerHealth() > $this->_initial_health / 2) {
			$this->_player_health_colour = Player::PLAYER_COLOUR_YELLOW;
			return;
		}
		$this->_player_health_colour = Player::PLAYER_COLOUR_GREEN;
	}

	/**
	 * Get the player health colour
	 * 
	 * @return string
	 */
	public function getPlayerHealthColour() {
		return self::$valid_player_health_colours[$this->_player_health_colour];
	}

	/**
	 * Set the player position
	 * 
	 * @param int $x
	 * @param int $y
	 */
	public function setPlayerPosition($x, $y) {
		$this->_player_position_x = $x;
		$this->_player_position_y = $y;
	}

	/**
	 * Get the player position
	 * 
	 * @return array
	 */
	public function getPlayerPosition() {
		return [$this->_player_position_x, $this->_player_position_y];
	}

	/**
	 * Reduce the player's health
	 * 
	 * @return boolean
	 */
	public function reduceHealth() {

		$this->_player_health--;

		if ($this->_player_health <= 0) {
			$this->setPlayerStatusId(Player::PLAYER_STATUS_DEAD);
			return FALSE;
		}
//		$this->_setPlayerHealthColour($this->_player_health);
		return TRUE;
	}

	/**
	 * Move the player towards "us"
	 * 
	 * @param array $player0_position
	 * @return boolean
	 */
	public function movePlayer($player0_position) {

		$player_position = $this->getPlayerPosition();

		$x_distance = $player0_position[0] - $player_position[0];
		$y_distance = $player0_position[1] - $player_position[1];

		if (abs($x_distance) > abs($y_distance)) {
			//Move on the x-axis
			if ($x_distance > 0) {
				$this->setPlayerPosition($player_position[0] + 1, $player_position[1]);
			} else {
				$this->setPlayerPosition($player_position[0] - 1, $player_position[1]);
			}
		} else {
			//Move on the y-axis
			if ($y_distance > 0) {
				$this->setPlayerPosition($player_position[0], $player_position[1] + 1);
			} else {
				$this->setPlayerPosition($player_position[0], $player_position[1] - 1);
			}
		}

		$player_position = $this->getPlayerPosition();
		$x_distance = $player0_position[0] - $player_position[0];
		$y_distance = $player0_position[1] - $player_position[1];

		if ($x_distance == 0 && $y_distance == 0) {
			return TRUE;
		}
		return FALSE;
	}

}
