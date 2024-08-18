<?php
add_action( 'rest_api_init', 'ulubioneRoutes' );

function ulubioneRoutes() {

	register_rest_route(
		'ulub/v1',
		'sendUlub',
		array(
			'methods' => 'POST',
			'callback' => 'addPost',
		)
	);
	register_rest_route(
		'ulub/v1',
		'sendUlub',
		array(
			'methods' => 'DELETE',
			'callback' => 'deletePost'
		)
	);

	function addPost() {
		return 'Dzięki za próbę dodania wpisu z OGP z pliku';

	}
	function deletePost() {
		return 'Dzięki za próbę usunięcia wpisu z OGP z pliku';

	}
}