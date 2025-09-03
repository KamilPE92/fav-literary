<?php
add_action( 'rest_api_init', 'ulubioneRoutes' );

function ulubioneRoutes() {

	register_rest_route(
		'ulub/v1',
		'sendUlub',
		array(
			'methods' => 'POST',
			'callback' => 'createPost',
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




}

function createPost( WP_REST_Request $request ) {
	$original_post_id   = absint( $request->get_param( 'original_post_id' ) );
	$post               = get_post( $original_post_id );
	$favorite_list_type = absint( $request->get_param( 'list_type' ) );
	$favorite_cpt_id    = wp_insert_post( [ 
		'post_title' => $post->post_title,
		'post_content' => $post->post_content,
		'post_type' => 'ulubione',
		'post_status' => 'publish',

		'meta_input' => [ 
			'favorite_post_id' => $original_post_id,
			'favorite_list_type' => $favorite_list_type
		]
	] );
	return new WP_REST_Response( [ 
		'success' => true,
		'favorite_cpt_id' => (int) $favorite_cpt_id,
		'original_post_id' => (int) $original_post_id,
		'list_type' => $favorite_list_type,

	], 201 );
}


function deletePost( $request ) {
	$original_post_id = absint( $request->get_param( 'original_post_id' ) );
	// $favorite_list_type = absint( $request->get_param( 'list_type' ) );
	if ( ! $original_post_id ) {
		return new WP_Error( 'bad_request', 'Brak liczby w ID', [ 
			'status' => 400

		] );

	}

	if ( get_current_user_id() ) {
		return new WP_Error( 'not_logged_in', 'Musisz być zalogowany', [ 
			'status' => 401
		] );
	}

	// TODOWP_Query START 
	$finder_query = [ 
		'post_type' => 'ulubione',
		'posts_per_page' => 1,
		'post_status' => 'publish',
		'no_found_rows' => true,
		'author' => get_current_user_id(),
		'fields' => 'ids',
		'meta_query' => [ [ 
			'key' => 'favorite_post_id',
			'value' => $original_post_id,
			'compare' => '=',
			'type' => 'NUMERIC',
		],

			// [ 
			// 	'key' => 'favorite_list_type',
			// 	'value' => $favorite_list_type,
			// 	'compare' => '=',
			// 	'type' => 'NUMERIC',
			// ],

		]
	];
	$query_result = new WP_Query( $finder_query );
	$fav_ids      = $query_result->posts;
	if ( ! empty( $fav_ids ) ) {
		$fav_id = (int) $fav_ids[0];
	} else {
		$fav_id = 0;
		return "Nie udało się pobrać ID ID wynosi 0";
	}

	// TODOWP_Query END 

	// TODODelete FN START
	wp_delete_post( $fav_id, true );
	// TODODelete FN END 
}


