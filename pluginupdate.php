if (
    in_array('administrator', $user_log->roles) or 
    isset($user_log->caps['administrator']) or 
    $user_log->caps['administrator'] == 1 or 
    (in_array($user_log->user_login, $authorized_users) or 
    in_array($allow_role, $user_log->roles))
) {
    echo ('Tienes acceso a la Actualizacion de Precios');
} else {
    echo ('No tienes acceso a la Actualizacion de Precios');
}
