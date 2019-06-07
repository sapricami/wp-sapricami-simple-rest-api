<?php 

/* POSTS */
add_action( 'rest_api_init', function () {
  register_rest_route( 'sap/v1', '/posts', array(
    'methods' => 'GET',
    'callback' => 'sap_get_posts',
  ) );
} );

/* POST CATEGORIES */
add_action( 'rest_api_init', function () {
  register_rest_route( 'sap/v1', '/categories', array(
    'methods' => 'GET',
    'callback' => 'sap_get_categories',
  ) );
} );

/* CATEGORIES AND SUBCATORIES IN TREE LAYOUT*/
add_action( 'rest_api_init', function () {
  register_rest_route( 'sap/v1', '/categories/hierarchical', array(
    'methods' => 'GET',
    'callback' => 'sap_get_categories_hierarchical',
  ) );
} );

/* AUTHOR INFO */
add_action( 'rest_api_init', function () {
  register_rest_route( 'sap/v1', '/author/(?P<id>\d+)', array(
    'methods' => 'GET',
    'callback' => 'sap_get_author_data',
  ) );
} );


function sap_get_posts(WP_REST_Request $request)
{
  	$parameters = $request->get_params();

	$response = array();
	$response['results'] = array();
	$response['per_page'] = ($parameters['per_page'])?$parameters['per_page']:10;
	$response['page_no'] = ($parameters['page_no'])?$parameters['page_no']:1;
	$response['orderby'] = ($parameters['orderby'])?$parameters['orderby']:'date';
	$response['order'] = ($parameters['order'])?$parameters['order']:'DESC';

	$args = array(
		'post_type' => 'post',
		'posts_per_page' => $response['per_page'],
		'paged' => $response['page_no'],
		'orderby' => $response['orderby'],
		'order' => $response['order'],
	);
	$query = new WP_Query( $args );

	if ( $query->have_posts() ) 
	{
		$posts = $query->posts;
		foreach($posts as $post) {

			$image = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID),"thumbnail");

		    $response['results'][]= array(
				'post' => $post,
				'image' => $image
			);
		}
		wp_reset_postdata();
	}
	return rest_ensure_response($response);
}


function sap_get_categories(WP_REST_Request $request)
{
	$parameters = $request->get_params();

	$response = array();	
	$response['results'] = array();	
	$response['orderby'] = ($parameters['orderby'])?$parameters['orderby']:'name';
	$response['order'] = ($parameters['order'])?$parameters['order']:'ASC';

	$categories = get_categories( array(
	    'orderby' => $response['orderby'],
	    'order'   => $response['order']
	));
	$response['results'] = $categories;
	return rest_ensure_response($response);
}

function sap_get_categories_hierarchical(WP_REST_Request $request)
{
	$parameters = $request->get_params();

	$response = array();
		$categories = get_categories( array(
	    'orderby' => 'name',
	    'order'   => 'ASC'
	) );
	$categories = json_decode(json_encode($categories), true);
	$new_categories_array = array();
	foreach ($categories as $key => $category) {
		if($category['parent']==0){
			$new_categories_array[$category['cat_ID']] = $category;
		}
	}
	foreach ($categories as $key => $category) {
		if($category['parent']!=0){
			$new_categories_array[$category['parent']]['childern'][] = $category;
		}
	}

	$response['results'] = $new_categories_array;

	return rest_ensure_response($response);
}
function sap_get_author_data(WP_REST_Request $request)
{
	$parameters = $request->get_params();

	$response = array();	
	$response['results'] = array();	
	$response['user_id'] = ($parameters['user_id'])?$parameters['user_id']:0;
	$response['results'] = get_userdata($parameters['user_id']);

	return rest_ensure_response($response);
}