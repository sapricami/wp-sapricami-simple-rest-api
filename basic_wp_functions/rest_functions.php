<?php 

add_action( 'rest_api_init', function () {
  register_rest_route( 'sap/v1', '/posts', array(
    'methods' => 'GET',
    'callback' => 'sap_get_posts',
  ) );
} );
add_action( 'rest_api_init', function () {
  register_rest_route( 'sap/v1', '/categories', array(
    'methods' => 'GET',
    'callback' => 'sap_get_categories',
  ) );
} );
add_action( 'rest_api_init', function () {
  register_rest_route( 'sap/v1', '/categories/parents_n_child', array(
    'methods' => 'GET',
    'callback' => 'sap_get_categories_parents_n_child',
  ) );
} );
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

	$args = array(
		'post_type' => 'post',
		'posts_per_page' => $response['per_page'],
		'paged' => $response['page_no'],
		'orderby' => 'date',
		'order' => 'DESC',
	);
	$query = new WP_Query( $args );

	if ( $query->have_posts() ) 
	{
		$posts = $query->posts;
		foreach($posts as $post) {

			$image = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID),"full");

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
		$categories = get_categories( array(
	    'orderby' => 'name',
	    'order'   => 'ASC'
	) );
	$response['results'] = $categories;
	return rest_ensure_response($response);
}

function sap_get_categories_parents_n_child(WP_REST_Request $request)
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

}