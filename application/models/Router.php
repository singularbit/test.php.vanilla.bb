<?php

namespace APP\Models;

// Routing system
// POST Data
$post_data = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
$uri = parse_url($post_data['url']);
$path = $uri["path"];
$prefix = '/application/controllers/';
$routing = preg_replace('/^' . preg_quote($prefix, '/') . '/', '', $path);

$routing_components = explode("/", rtrim($routing, '/'));
if ($routing_components[0] != '') {
	include_once '../controllers/' . $routing_components[0] . '.php';
	$class = "APP\\Controllers\\" . $routing_components[0];
	$controller = new $class;
	$method = !isset($routing_components[1]) ? "index" : $routing_components[1];

	$parameters = $routing_components;
	unset($parameters[0]);
	unset($parameters[1]);
	call_user_func_array([$controller, $method], array_values($parameters));
} else {
	die('Invalid route');
}
	
	
	