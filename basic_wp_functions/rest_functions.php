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
		    $response['results'][]= array(
				'post' => $post,
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
	$response['categories'] = $categories;
	return rest_ensure_response($response);
}
function sap_get_author_data(WP_REST_Request $request)
{

}