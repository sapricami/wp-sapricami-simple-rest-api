<?php 

add_action( 'rest_api_init', function () {
  register_rest_route( 'sap/v1', '/posts', array(
    'methods' => 'GET',
    'callback' => 'sap_get_posts',
  ) );
} );
add_action( 'rest_api_init', function () {
  register_rest_route( 'sap/v1', '/author/(?P<id>\d+)', array(
    'methods' => 'GET',
    'callback' => 'sap_get_author_data',
  ) );
} );