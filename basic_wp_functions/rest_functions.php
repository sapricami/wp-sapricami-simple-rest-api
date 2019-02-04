<?php 

function sap_get_posts()
{
	$response = array();
	$response['per_page'] = 10;
	$response['page_no'] = 1;
	return rest_ensure_response($response);
}
function sap_get_author_data()
{

}