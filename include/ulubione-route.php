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

	function createPost( $data ) {
		$original_post_id   = absint( $data->get_param( 'original_post_id' ) );
		$post               = get_post( $original_post_id );
		$favorite_list_type = absint( $data->get_param( 'list-type' ) );
		$favorite_post_id   = wp_insert_post( [ 
			'post_title' => $post->post_title,
			'post_content' => $post->post_content,
			'post_type' => 'ulubione',
			'post_status' => 'publish',

			'meta_input' => [ 
				'favorite_post_id' => $original_post_id,
				'favorite_list_type' => $favorite_list_type
			]
		] );
		return [ 
			'success' => true,
			'favorite_post_id' => (int) $favorite_post_id,
			'original_post_id' => (int) $original_post_id,
			'list_type' => $favorite_list_type,
		];
	}


	function deletePost( $data ) { { {
			}
			// todoDopisz funkcję, która usunie wp_delete post czy jakoś tak
		}
	}

}