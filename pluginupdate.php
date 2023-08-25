//Voy a verificar si el usuario agregado puede conectarse
$user_log = wp_get_current_user();
$user_to_check = $user_log->user_login;
$allow_role = 'vendedor';
//delete_option('updatepriceswc_historial');
if (
		in_array('administrator', $user_log->roles) or 
	isset( $user_log->caps['administrator']) or 
	$user_log->caps['administrator'] == 1 or 
	in_array($user_log->user_login, $authorized_users or 
	in_array( $allow_role, $user_log->roles))
	
	) {
	echo ('Tienes acceso a la Actualizacion de Precios');
}else{
	echo ('No tienes acceso a la Actualizacion de Precios');
}

Warning: Undefined array key "administrator" in /www/game24hs_309/public/wp-content/plugins/updatepriceswc/updatepriceswc.php on line 114

Fatal error: Uncaught TypeError: in_array(): Argument #2 ($haystack) must be of type array, bool given in /www/game24hs_309/public/wp-content/plugins/updatepriceswc/updatepriceswc.php:116 Stack trace: #0 /www/game24hs_309/public/wp-content/plugins/updatepriceswc/updatepriceswc.php(116): in_array('a', false) #1 /www/game24hs_309/public/wp-includes/class-wp-hook.php(310): updatepriceswc_options('') #2 /www/game24hs_309/public/wp-includes/class-wp-hook.php(334): WP_Hook->apply_filters('', Array) #3 /www/game24hs_309/public/wp-includes/plugin.php(517): WP_Hook->do_action(Array) #4 /www/game24hs_309/public/wp-admin/admin.php(259): do_action('toplevel_page_u...') #5 {main} thrown in /www/game24hs_309/public/wp-content/plugins/updatepriceswc/updatepriceswc.php on line 116
