<?php

namespace APP\Models;

use APP\Models\Player;

include_once __DIR__ . '/../models/Player.php';

class Arena {

	private $_arena_size = 121;
	private $_number_of_players;
	private $_maximum_number_of_players;
	private $_minimum_number_of_players;
	private $_players_flux;
	// Single instance
	private static $_instance;
	private $_arena_players = [];
	private $_arena_players_keys = [];

	/**
	 * Instance of the Arena.
	 * @return Arena 
	 * 
	 */
	public static function getInstance() {
		if (!self::$_instance) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * Set arena size
	 * @param int $size
	 */
	public function setArenaSize($size) {
		$this->_arena_size = $size;
	}

	/**
	 * Get Arena size
	 * @return int
	 */
	public function getArenaSize() {
		return $this->_arena_size;
	}

	/**
	 * Set the 3 variables for the number of players
	 * 
	 * @param int $total
	 * @param int $max
	 * @param int $min
	 */
	public function setArenaPlayers($total, $max, $min) {
		$this->_number_of_players = $total;
		$this->_maximum_number_of_players = $max;
		$this->_minimum_number_of_players = $min;
	}

	/**
	 * Set the flux of enemy spawns per turn
	 * 
	 * @param int $flux
	 */
	public function setArenaPlayersFlux($flux) {
		$this->_players_flux = $flux;
	}

	/**
	 * Generate new players
	 */
	public function generateNewPlayers() {

		for ($i = 0; $i <= $this->_number_of_players; $i++) {

			$player = new Player;

			$player->setPlayerTypeId(($i == 0) ? Player::PLAYER_TYPE_FRIEND : Player::PLAYER_TYPE_FOE);
			$player->setSpeed(10);

			// Add player to the arena
			$this->addPlayer($player, $i);
		}
	}

	/**
	 * Add player to the arena
	 * 
	 * @param int $player
	 * @param imt $player_id
	 * @throws KeyHasUseException
	 */
	private function addPlayer($player, $player_id = null) {

		if ($player_id == null) {
			$this->_arena_players[] = $player;
		} else {
			if (isset($this->_arena_players[$player_id])) {
				throw new KeyHasUseException("Key $player_id already in use.");
			} else {
				$this->_arena_players[$player_id] = $player;
			}
		}
	}

	/**
	 * Remove player from the arena
	 * 
	 * @param int $player_id
	 * @throws KeyInvalidException
	 */
	private function removePlayer($player_id) {
		if (isset($this->_arena_players[$key])) {
			unset($this->_arena_players[$key]);
		} else {
			throw new KeyInvalidException("Invalid key $key.");
		}
	}

	/**
	 * Return the player object
	 * 
	 * @param int $player_id
	 * @return object
	 * @throws KeyInvalidException
	 */
	private function getPlayer($player_id) {
		if (isset($this->_arena_players[$player_id])) {
			return $this->_arena_players[$player_id];
		} else {
			throw new KeyInvalidException("Invalid key $player_id.");
		}
	}

	/**
	 * Add Players to the Arena by flagging them as active and setting coordinates
	 * 
	 * @return null
	 */
	public function addPlayersToTheArena() {

		$i = 0;
		foreach ($this->_arena_players as $player_key => $player) {

			$player->setPlayerStatusId(Player::PLAYER_STATUS_ACTIVE);

			if ($i == 0) {

				$position_x = ceil(sqrt($this->_arena_size) / 2);
				$position_y = ceil(sqrt($this->_arena_size) / 2);
			} else {

				$random_edge = rand(1, 4); // 4 edges
				switch ($random_edge) {

					case 1:

						$position_x = 0;
						$position_y = rand(1, ceil(sqrt($this->_arena_size)));

						break;

					case 2:

						$position_x = ceil(sqrt($this->_arena_size));
						$position_y = rand(1, ceil(sqrt($this->_arena_size)));

						break;

					case 3:

						$position_x = rand(1, ceil(sqrt($this->_arena_size)));
						$position_y = 0;

						break;

					case 4;

						$position_x = rand(1, ceil(sqrt($this->_arena_size)));
						$position_y = ceil(sqrt($this->_arena_size));

						break;
				}
			}
			// If 2 players end up with the same coordinates only the first is spawned
			$player->setPlayerPosition($position_x, $position_y);

			$this->arena_players_keys[] = $player_key;

			if ($i >= $this->_minimum_number_of_players) {
				return;
			}
			$i++;
		}
	}

	/**
	 * Get all the active players keys
	 * 
	 * @return array
	 */
	public function playerIds() {
		return $this->arena_players_keys;
	}

	/**
	 * Count all the players
	 * 
	 * @return int
	 */
	private function playerCount() {
		return count($this->_arena_players);
	}

	/**
	 * Check if a player exists
	 * 
	 * @param int $player_id
	 * @return Boolean
	 */	
	private function playerIdExists($player_id) {
		return isset($this->_arena_players[$player_id]);
	}

	/**
	 * Fight between players and return an array of causualties if any
	 * 
	 * @return array
	 */
	public function fightPlayers() {

		$dead_players_ids = [];

		foreach ($this->_arena_players as $player_id => $player) {

			// Hammer Fall...
			if (!in_array($player_id, $this->arena_players_keys)) {
				// Player not in arena
				continue;
			}

			if (in_array($player_id, $dead_players_ids)) {
				// Dead players don't fight!
				continue;
			}

			$player_type_id = $player->getPlayerTypeId();
			$enemy_id = $this->chooseEnemy($player_id, $player_type_id);

			// Fifty-fifty change of hitting
			if (rand(0, 1) == 0) {
				// Miss!
				continue;
			}

			echo "Player " . $player_id . " hit Player " . $enemy_id . " / ";
			// Hit!
			if (!$this->_arena_players[$enemy_id]->reduceHealth()) {
				$dead_players_ids[] = $enemy_id;
				if ($player_id == 0) {
					die('You\'re dead!');
				}
			}
		}
		return $dead_players_ids;
	}

	/**
	 * Randomise a valid enemy to a given player
	 * 
	 * @param int $player_id
	 * @param int $player_type_id
	 * @return int
	 */
	private function chooseEnemy($player_id, $player_type_id) {

		if ($player_type_id == Player::PLAYER_TYPE_FRIEND) {
			$other_players_keys = $this->arena_players_keys;
			unset($other_players_keys[$player_id]);

			$enemy_id = array_rand($other_players_keys);
		} else {

			$enemy_id = 0; // Massive shortcut! Or maybe not considering "YAGNI"
		}

		return $enemy_id;
	}

	/**
	 * Generate data to feed to the view
	 * 
	 * @return array
	 */
	public function generateArenaMatrix() {

		$arena_matrix = [];
		foreach ($this->_arena_players as $player_id => $player) {
			if ($player->getPlayerStatusId() === Player::PLAYER_STATUS_ACTIVE) {
				$arena_matrix[implode("_", $player->getPlayerPosition())] = [
					"colour" => $player->getPlayerHealthColour(),
					"player_id" => $player_id
				];
			}
		}
		return $arena_matrix;
	}

	/**
	 * Move all the players in the arena
	 */
	public function movePlayers() {

		// Our position
		$player0_position = $this->_arena_players[0]->getPlayerPosition();

		foreach ($this->arena_players_keys as $player_key) {

			if ($player_key == 0) {
				// "We" don't move
				continue;
			}
			if ($this->_arena_players[$player_key]->movePlayer($player0_position)) {
				// Enemy occupies the same place as the Player
				die('You\'re dead!');
			}
		}
	}
}
