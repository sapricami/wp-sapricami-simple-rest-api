<?php 


/* POSTS */
add_action( 'rest_api_init', function () {
  register_rest_route( 'sap/v1', '/posts', array(
    'methods' => 'GET',
    'callback' => 'sap_get_posts',
  ) );
} );
/* SINGLE POST */
add_action( 'rest_api_init', function () {
  register_rest_route( 'sap/v1', '/post/(?P<id>\d+)', array(
    'methods' => 'GET',
    'callback' => 'sap_get_single_post',
  ) );
} );

/* POST CATEGORIES */
add_action( 'rest_api_init', function () {
  register_rest_route( 'sap/v1', '/categories', array(
    'methods' => 'GET',
    'callback' => 'sap_get_categories',
  ) );
} );

/* CATEGORIES AND SUBCATORIES IN HiERARCHICAL LAYOUT */
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
	$response['per_page'] = ($parameters['per_page'])?(int)$parameters['per_page']:10;
	$response['page_no'] = ($parameters['page_no'])?(int)$parameters['page_no']:1;
	$response['orderby'] = ($parameters['orderby'])?$parameters['orderby']:'date';
	$response['order'] = ($parameters['order'])?$parameters['order']:'DESC';
	$response['thumb'] = ($parameters['thumb'])?$parameters['thumb']:'thumbnail';

	$args = array(
		'post_type' => 'post',
		'posts_per_page' => $response['per_page'],
		'paged' => $response['page_no'],
		'orderby' => $response['orderby'],
		'order' => $response['order'],
	);

	if(isset($parameters['cat_id'])){
		$args['cat']=$parameters['cat_id'];
	}

	$query = new WP_Query( $args );

	if ( $query->have_posts() ) 
	{
		$posts = $query->posts;
		foreach($posts as $post) {

			$post->post_content = strip_shortcodes($post->post_content);

			$image = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID),$response['thumb']);

		    $response['results'][]= array(
				'post' => $post,
				'image' => $image
			);
		}
		wp_reset_postdata();
	}
	return rest_ensure_response($response);
}

function sap_get_single_post(WP_REST_Request $request)
{
	$parameters = $request->get_params();

	$response = array();	
	$response['results'] = array();	
	$response['post_id'] = (isset($parameters['id']))?$parameters['id']:0;
	$response['thumb'] = (isset($parameters['thumb']))?$parameters['thumb']:'thumbnail';
	$response['format'] = (isset($parameters['format']))?$parameters['format']:'html';

	$args = array(
		'p' => $response['post_id'],
	);
	$query = new WP_Query( $args );
	if ( $query->have_posts() ) 
	{
		$posts = $query->posts;
		foreach($posts as $post) {

			remove_all_shortcodes();

			$post->post_content = strip_shortcodes($post->post_content);
			if($response['format']=="text"){
				$post->post_content = strip_tags($post->post_content);
			}

			$image = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID),$response['thumb']);

			$comments = get_comments( array(
			    'post_id' => $post->ID,
			    'orderby' => 'comment_date_gmt',
			    'status' => 'approve',
			 ) );
			if(!empty($comments)){
			    $query->comments = $comments;
			    $query->comment_count = count($comments);
			  }
		    $response['results']= array(
				'post' => $post,
				'image' => $image,
				'comments' => $comments
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
	$new_array = array();
	foreach ($new_categories_array as $key => $value) {
		$new_array[]=$value;
	}
	$response['results'] = $new_array;

	return rest_ensure_response($response);
}

function sap_get_author_data(WP_REST_Request $request)
{
	$parameters = $request->get_params();

	$response = array();	
	$response['results'] = array();	
	$response['user_id'] = (isset($parameters['id']))?$parameters['id']:0;
	$response['results'] = get_userdata($parameters['id']);

	return rest_ensure_response($response);
}