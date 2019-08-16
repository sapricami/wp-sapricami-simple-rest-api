<?php 

class Sapricami_simple_rest
{
	
	function __construct()
	{
		
	}


	public static function plugin_activation()
	{
		add_action(
		    'admin_notices',
		    function () {
		      echo '<div class="notice notice-error is-dismissible"><p>Plugin Activated</p></div>';
		    }
		);
		return true;
	}
	public static function plugin_deactivation()
	{
		add_action(
		    'admin_notices',
		    function () {
		      echo '<div class="notice notice-error is-dismissible"><p>Plugin deactivated</p></div>';
		    }
		);
		return true;
	}
	public static function sapricami_init_plugin()
	{
		//set rest api paths

		return true;
	}

}


