add_menu_page( 'Actualización de precios', 'Actualización de precios', 'manage_woocommerce', 'updatepriceswc', 'updatepriceswc_options','dashicons-clipboard' ); 


WP_User Object ( [data] => stdClass Object ( [ID] => 137235 [user_login] => CristianDix [user_pass] => $P$Bm.b4EwZm78S70clh2O92L.hO3TUuO. [user_nicename] => cristiandix [user_email] => contacto@cristiantait.com [user_url] => [user_registered] => 2023-08-08 18:16:59 [user_activation_key] => 1692792125:$P$BJua0tZ5lE4E.yzeiy1n3qCV/Rm0gI0 [user_status] => 0 [display_name] => Cristian Dix ) [ID] => 137235 [caps] => Array ( [administrator] => 1 ) [cap_key] => cbgw_capabilities [roles] => Array ( [0] => administrator ) [allcaps] => Array ( [switch_themes] => 1 [edit_themes] => 1 [activate_plugins] => 1 [edit_plugins] => 1 [edit_users] => 1 [edit_files] => 1 [manage_options] => 1 [moderate_comments] => 1 [manage_categories] => 1 [manage_links] => 1 [upload_files] => 1 [import] => 1 [unfiltered_html] => 1 [edit_posts] => 1 [edit_others_posts] => 1 [edit_published_posts] => 1 [publish_posts] => 1 [edit_pages] => 1 [read] => 1 [level_10] => 1 [level_9] => 1 [level_8] => 1 [level_7] => 1 [level_6] => 1 [level_5] => 1 [level_4] => 1 [level_3] => 1 [level_2] => 1 [level_1] => 1 [level_0] => 1 [edit_others_pages] => 1 [edit_published_pages] => 1 [publish_pages] => 1 [delete_pages] => 1 [delete_others_pages] => 1 [delete_published_pages] => 1 [delete_posts] => 1 [delete_others_posts] => 1 [delete_published_posts] => 1 [delete_private_posts]



// Verificar si el usuario tiene el rol 'administrator' en la matriz roles
if (in_array('administrator', $current_user->roles)) {
    echo 'El usuario tiene el rol de administrador.';
}

// Verificar si el usuario tiene la capacidad 'administrator' en la matriz caps
if (isset($current_user->caps['administrator']) && $current_user->caps['administrator'] == 1) {
    echo 'El usuario tiene la capacidad de administrador.';
}

// Verificar si el usuario tiene el rol 'administrator' en la matriz allcaps
if (isset($current_user->allcaps['administrator']) && $current_user->allcaps['administrator'] == 1) {
    echo 'El usuario tiene la capacidad de administrador en allcaps.';
}


Fatal error: Allowed memory size of 1073741824 bytes exhausted (tried to allocate 20480 bytes)
