<?php
/*
Plugin Name: Update Prices Woocommerce
Description: Plugin de actualización de precios masivamente.
Version:     1.7
Author:      Abraham Flores Cosme
*/

//Ruta de lanzador.php para automatizar el proceso al crear o actualizar un producto
$lanzador_path =plugin_dir_path(__FILE__) . 'lanzador.php';

if (file_exists($lanzador_path)){
	include_once $lanzador_path;
}


if( ! class_exists( 'WP_List_Table' ) ) {
    require_once( ABSPATH.'wp-admin/includes/class-wp-list-table.php' );
}

class Historial_Table extends WP_List_Table {
	function get_data(){
		$historial = get_option('updatepriceswc_historial',[]);
		return array_reverse($historial); // para mostrar el historial de forma DESC
	}

	function get_columns(){
	  $columns = array(
	  	'B' => 'Dividendo <b>B</b>',
	  	'C' => 'Divisor <b>C</b>',
		  'D' => 'Costo Fijo <b>D</b>',
	  	'cotizacion' => '<b>Cotización</b>',
      'precio_minimo' => '<b>Precio Mínimo</b>',
	  	'fecha' => 'Fecha'
	  );
	  return $columns;
	}

	function prepare_items() {
	  $columns = $this->get_columns();
	  $hidden = array();
	  $sortable = array('desc');
	  $this->_column_headers = array($columns, $hidden, $sortable);
	  $this->items = $this->get_data();
	}

	function column_default( $item, $column_name ) {
	  return $item[ $column_name ];
	}
}


function plugin_updatepriceswc() {
	add_menu_page( 'Actualización de precios', 'Actualización de precios','read', 'updatepriceswc', 'updatepriceswc_options','dashicons-clipboard' ); 
}
add_action('admin_menu', 'plugin_updatepriceswc');

//Paso Estas lineas de bajo de la declaracion de $authorized_users

function updatepriceswc_options() {	

	//MUEVO DE BAJO DE $authorized_users
//delete_option('updatepriceswc_historial');
/*
if (!current_user_can('manage_woocommerce') || !current_user_can('administrator') )  {
	wp_die( __('No tiene suficientes permisos para acceder a esta página.') );
}else{

}*/
	

	//FIN

//AGREGADO CRISTIAN COMIENZO

	//Voy a obtener una lista de Usuarios
	$all_users = get_users();

	// Voy a obtener una lista de Usuarios Autorizados
	$authorized_users = get_option('updatepriceswc_authorized_users', []);

	// Generar Formulario en el Frontend CRUD

	//Voy a comprobar si se agregaron usuarios por el formulario
	if (isset($_POST['add_user'])){
		$user_to_add = sanitize_text_field($_POST['user_to_add']);

		//Voy a agregar a los usuarios a la lista de usuarios actualizados si no esta
		if (!in_array($user_to_add, $authorized_users)){
			$authorized_users[] = $user_to_add;
			update_option('updatepriceswc_authorized_users', $authorized_users);
		}
	}

	//Voy a comprobar si se envio el formulario
	if (isset($_POST['remove_user'])){
		$user_to_remove = sanitize_text_field($_POST['user_to_remove']);

		//Voy a eliminar al usuarios de la lista de autorizados
		$index = array_search($user_to_remove, $authorized_users);
		if ($index !== false){
			unset($authorized_users[$index]);
				update_option('updatepriceswc_authorized_users', $authorized_users);
			}
		}

//Voy a verificar si el usuario agregado puede conectarse
$user_log = wp_get_current_user();
$user_to_check = $user_log->user_login;

//delete_option('updatepriceswc_historial');
if (in_array('administrator', $user_log->roles) or isset( $user_log->caps['administrator']) or $user_log->caps['administrator'] == 1 or in_array($user_log->user_login, $authorized_users)) {
	echo ('Tienes acceso a la Actualizacion de Precios');
}else{
	echo ('No tienes acceso a la Actualizacion de Precios');
}
		

//FIN AGREGADO CRISTIAN COMIENZO

	$B = get_option('updatepriceswc_B');
  $C = get_option('updatepriceswc_C');
	$D = get_option('updatepriceswc_D');
  $cot = get_option('updatepriceswc_cotizacion');
  $precio_minimo = get_option('updatepriceswc_precio_minimo');
  $fecha = get_option('updatepriceswc_fecha');
	$historial = get_option('updatepriceswc_historial',[]);
	if(count($historial)>0){
		$last = $historial[count($historial)-1];
		if($last['B']!=$B || $last['C']!=$C || $last['D']!=$D || $last['cotizacion']!=$cot || $last['precio_minimo']!=$precio_minimo){
			$historial[]=[
				'B'=>$B,
				'C'=>$C,
				'D'=>$D,
				'cotizacion'=>$cot,
        'precio_minimo'=>$precio_minimo,
				'fecha'=>date('Y-m-d')
			];
		}
	}else{
		$historial[]=[
			'B'=>'Vacio',
			'C'=>'Vacio',
			'D'=>'Vacio',
			'cotizacion'=>'Vacio',
      'precio_minimo'=>'Vacio',
			'fecha'=>'Vacio'
		];
	}

	if(count($historial)>15){
		array_splice($historial, 0, 1);
	}
	update_option('updatepriceswc_historial',$historial);

	function init_scripts() {
	    wp_deregister_script( 'jquery' );
	    wp_register_script( 'jquery', 'http://ajax.googleapis.com/ajax/libs/jquery/1.4/jquery.min.js');
	    // Register the script like this for a plugin:
	    wp_register_script( 'updatepriceswc', plugins_url( '/js/asyncupdates.js', __FILE__ ) );
	}     
	add_action('wp_enqueue_scripts', 'init_scripts');

	include( plugin_dir_path( __FILE__ ) . 'contenido.php');

	?>

	<div class="wrap">
		<h2>Gestion de Usuarios</h2>
		<form method="post">
			<label for="user_to_add">Agregar usuario habilitado: </label>
			<select name="user_to_add" id="user_to_add">
				<?php foreach ($all_users as $user) : ?>
					<option value="<?php echo esc_attr($user->user_login); ?>"><?php echo esc_html($user->user_login); ?></option>
				<?php endforeach?>
			</select>
			<input type="submit" name="add_user" value="Agregar">
		</form>

		<form method="post">
			<label for="user_to_remote">Eliminar usuario autorizado:</label>
			<select name="user_to_remove" id="user_to_remove">
				<?php foreach ($authorized_users as $user) : ?>
					<option value="<?php echo esc_attr($user); ?>"><?php echo esc_html($user); ?></option>
				<?php endforeach?>
			</select>
			<input type="submit" name="remove_user" value="eliminar">
		</form>

		<?php
		$user_log = wp_get_current_user();
		$user_to_check = $user_log->user_login;

		if (current_user_can('administrator') || in_array($user_to_check, $authorized_users)){
			echo 'Usuario autorizado';
			$users_string = implode(',', $authorized_users);
			echo '<p>Listado de Usuarios autorizados: ' . $users_string. '</p>';
			echo '<p>Valor de User Login: ' . $user_log->user_login . '</p>';
		}else{
			echo 'Valor de $user_log: ';
			echo '<pre>';
			var_dump($user_log);
			echo '</pre>';
			echo 'Valor de $user_to_check: ';
			echo $user_to_check;
			echo 'usuario no autorizado';
			
		}
	?>
	</div>
<?php
}

add_action( 'admin_init', 'update_updatepriceswc' );
if( !function_exists("update_updatepriceswc") ) {
	function update_updatepriceswc() {
	  register_setting( 'updatepriceswc-settings', 'updatepriceswc_B' );
	  register_setting( 'updatepriceswc-settings', 'updatepriceswc_C' );
	  register_setting( 'updatepriceswc-settings', 'updatepriceswc_D' );
	  register_setting( 'updatepriceswc-settings', 'updatepriceswc_cotizacion' );
    register_setting( 'updatepriceswc-settings', 'updatepriceswc_precio_minimo' );
	  register_setting( 'updatepriceswc-settings', 'updatepriceswc_fecha' );
	  register_setting( 'updatepriceswc-metas', 'updatepriceswc_historial' );
	}
}
?>
