<?php

namespace APP\Controllers;

use APP\Models\Arena;

include_once __DIR__ . '/../models/Arena.php';

class Game {

	/**
	 * Endpoint that initialises the game
	 */
	public function init() {

		// POST Data
		$post_data = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
		$arena_size = $post_data['arena_size']; // default 121
		$number_of_players = $post_data['number_of_players'];
		$maximum_number_of_players = $post_data['maximum_number_of_players'];
		$minimum_number_of_players = $post_data['minimum_number_of_players'];
		$players_flux = $post_data['players_flux'];

		// Initialize the Arena
		$gameArena = Arena::getInstance();

		// Set arena properties
		$gameArena->setArenaSize($arena_size);
		$gameArena->setArenaPlayers($number_of_players, $maximum_number_of_players, $minimum_number_of_players);
		$gameArena->setArenaPlayersFlux($players_flux);

		// Generate Players
		$gameArena->generateNewPlayers();

		// Activate Players
		$gameArena->addPlayersToTheArena();

		// Generate data to feed the UI
		$arena_matrix = $gameArena->generateArenaMatrix();

		// Serialize arena object and save it
		$arena_matrix_serialized = serialize($gameArena);
		file_put_contents( __DIR__ . '/../../temp/gameArena', $arena_matrix_serialized);

		// Display the Arena
		$this->displayArena($arena_matrix, $arena_size);
	}

	/**
	 * Endpoint that runs a play turn
	 */
	public function turn() {
		
		// Load and unserialize the arena object
		$arena_matrix_serialized = file_get_contents(__DIR__ . '/../../temp/gameArena');
		$gameArena = unserialize($arena_matrix_serialized);

		// Fight between friend and foe
		$dead_players_ids = $gameArena->fightPlayers();
		
		// Remove dead and activate inactive players on the queue
		if (count($dead_players_ids)>0) {
			// @TODO: replace dead players with inative according to $this->$players_flux
		}

		// Move	players (not us)	
		$gameArena->movePlayers();

		// Serialize arena object and save it
		$arena_matrix_serialized = serialize($gameArena);
		file_put_contents( __DIR__ . '/../../temp/gameArena', $arena_matrix_serialized);
		
		// Display the Arena
		$arena_matrix = $gameArena->generateArenaMatrix();
		$arena_size = $gameArena->getArenaSize();
		$this->displayArena($arena_matrix, $arena_size);
	}

	/**
	 * Call the view
	 * 
	 * @param array $arena_matrix
	 * @param int $arena_size
	 */
	public function displayArena($arena_matrix, $arena_size) {
		include_once __DIR__ . '/../views/Arena.php';
	}

}
