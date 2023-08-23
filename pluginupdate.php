<?php
include '../../../wp-load.php';
header('Content-Type: application/json');

$action = $_POST['action'];

switch ($action) {
	case 'count_prods':
		$B = update_option('updatepriceswc_B', $_POST['updatepriceswc_B']);
		$C = update_option('updatepriceswc_C', $_POST['updatepriceswc_C']);
		$D = update_option('updatepriceswc_D', $_POST['updatepriceswc_D']);
		$cot = update_option('updatepriceswc_cotizacion', $_POST['updatepriceswc_cotizacion']);
		$precio_minimo = update_option('updatepriceswc_precio_minimo', $_POST['updatepriceswc_precio_minimo']);

	    $params = [
			'posts_per_page' => -1, 
			'post_type' => 'product',
			'post_status' => [
				'publish'
			],
			'orderby'=>'ID',
			'order'=>'ASC'
		];
		$wc_query = new WP_Query($params);
		$json = [
			'ids'=>[]
		];

		if ($wc_query->have_posts()){
			while ($wc_query->have_posts()){
				$wc_query->the_post(); 
				$id_prod = get_the_id();
				$json['ids'][]=$id_prod;
			}
		}
    	wp_reset_postdata();
    	echo json_encode($json);
		break;

	case 'update_prod':
		$B = get_option('updatepriceswc_B');
		$C = get_option('updatepriceswc_C');
		$D = get_option('updatepriceswc_D');
		$cot = get_option('updatepriceswc_cotizacion');
		$precio_minimo = get_option('updatepriceswc_precio_minimo');
			
	    $params = [
			'posts_per_page' => 1, 
			'post_type' => 'product',
			'post_status' => [
				'publish'
			],
			'p'=>$_POST['id']
		];
		$wc_query = new WP_Query($params);
		$json = [
			'id'=>$_POST['id']
		];
		

		if ($wc_query->have_posts()){
			while ($wc_query->have_posts()){
				$A = 1;
				$wc_query->the_post(); 
				$product = wc_get_product(get_the_id());
				$padre_id = get_the_id();
				$precio = get_post_meta($padre_id, 'costo', TRUE);
        $precio_sony = get_post_meta($padre_id, 'costo', TRUE);


				$excluido_automat = get_post_meta($padre_id, 'excluido_automat', TRUE);
				// 2023-08-03 quedó en desuso, no hay productos con este meta
				// $precio_aumentado = get_post_meta($padre_id, 'costo_aumentado', TRUE); // para productos que quiero aumentar precio
				$costo_desc_fijo = get_post_meta($padre_id, 'costo_desc_fijo', TRUE); // para productos que quiero hacer un descuento al precio base
				$costo_giftcard = get_post_meta($padre_id, 'costo_giftcard', TRUE); //para gift card que tienen menor costo en eG
				$costo_ars = get_post_meta($padre_id, 'costo_ars', TRUE); // para productos con costo en pesos

				$json['nombre']=get_the_title();
				$json['precio_sony']=$precio_sony;
				$json['excluido_automat']=false;

				if ($product->is_type( 'variable' )) {
					$json['tipo']='variable';

					if(empty($excluido_automat)){
						$json['excluido_automat']=false;
					} else {
            $json['excluido_automat']=true;
            wp_reset_postdata();
            continue;
					}
					
					if(empty($precio)){
						$json['costo']=false;
						wp_reset_postdata();
						continue;
					} else {
						$json['costo']=true;
					}

					$args = array(
						'post_type'     => 'product_variation',
						'post_status'   => array( 'private', 'publish' ),
						'numberposts'   => -1,
						'1by'       => 'menu_order',
						'order'         => 'asc',
						'post_parent'   => get_the_id() // get parent post-ID
					);
					$variations = get_posts( $args );

					foreach ( $variations as $variation ) {

						// get variation ID
						$variation_ID = $variation->ID;

						// get variations meta
						$product_variation = new WC_Product_Variation( $variation_ID );

						// get variation featured image
						$variation_image = $product_variation->get_image();


						// get variation price
						$variation_price = $product_variation->get_price_html();
						$variation_sale_price = $product_variation->get_sale_price();

						// to get variation meta, simply use get_post_meta() WP functions and you're done ðŸ™‚
						// ... do your thing here
						

						$slot = $product_variation->get_attribute('SLOT');
						
						if($slot=='Primario'){ // si es primario
							if($precio > 39){$A = 0.59;}else{$A = 0.60;} // si es costo usd > 39 va 59% para tener precio mas competitivo en los juegos mas caros, si es menos va 60%
						}elseif($slot=='Secundario'){
							if($precio > 39){$A = 0.365;}else{$A = 0.39;} // si es costo usd > 39 va 36,5%, si es menos va 39%
						}

						if(!empty($costo_desc_fijo)) {
							$precio = $precio - $costo_desc_fijo;
						}						
						
						$precio_nuevo = ((($precio * $cot * $A) * ( 1 + ($B/($precio + $C)))) / (0.77) ) + $D; // aumento 23 %
						// 2019-10-29 cambio para pasar a USD
						//$precio_nuevo = (round($precio_nuevo, 0)/5); //redondeo resultado
						//$precio_nuevo = (ceil($precio_nuevo)*5);
						$precio_nuevo = round($precio_nuevo, 1); // le doy un solo decimal 2019-10-30

            			$precio_nuevo = ($precio_nuevo > $precio_minimo) ? $precio_nuevo : $precio_minimo; // 2022-10-26 defino mínimo

						update_post_meta($variation_ID, '_regular_price', (float)$precio_nuevo);
						update_post_meta($variation_ID, '_precio_base', (float)$precio_nuevo);
						if($variation_sale_price && $variation_sale_price<$precio_nuevo){
							update_post_meta($variation_ID, '_price', (float)$variation_sale_price);
						}else{
							update_post_meta($variation_ID, '_price', (float)$precio_nuevo);
						}


						$product_variation->save();
					}
				}else{
					$json['tipo']='simple';

					$comision=0.81; // 19 % p gift card
					$category = get_term_by( 'slug', 'ps3', 'product_cat' );
					$category_id = $category?$category->term_id:null;
	 
					$product_categories = get_the_terms( $padre_id,'product_cat');
	 
					if(!empty($product_categories)) {
						foreach ($product_categories as $key => $categoria) {
							if($category_id!==null && $categoria->parent==$category_id){
								// 2020-07-16 no se pueden resetear cuentas ps3 asique cambio de 0.25 a 0.65
								// 2020-07-30 ya se puede resetear, vuelvo a 0.25
								// 2020-12-19 no se puede comprar mas desde ps store, subo a 0.40
								$A=0.40;
								$comision=0.78; // aumento 22 % para ps3
								break;
							}
						}
					}

					$sale_price = get_post_meta($padre_id, '_sale_price', TRUE);

					if(empty($precio)){
						$json['costo']=false;
						wp_reset_postdata();
						continue;
					} else {
						$json['costo']=true;
					}

					if(!empty($costo_desc_fijo)) {
						$precio = $precio - $costo_desc_fijo;
					} 
					
					// si las gift card tienen descuento en su costo lo traslado al precio
					if(!empty($costo_giftcard) && $costo_giftcard<$precio) {
						$precio = $costo_giftcard;
					}
					
					// si la gift card tiene costo en pesos ->> paso su costo a USD primero
					// caso de pavos de fortnite en 2018-2019
					if($costo_ars != ''){$precio = $costo_ars * 1.25 / $cot;}
					
					
					$precio_nuevo = ((($precio * $cot * $A) * ( 1 + ($B/($precio + $C)))) / $comision ) + $D;
					//2019-10-28 cambio para pasar a USD
					//$precio_nuevo = (round($precio_nuevo, 0)/5); //redondeo resultado
					//$precio_nuevo = (ceil($precio_nuevo)*5);
					$precio_nuevo = round($precio_nuevo, 1); //le doy un solo decimal 2019-10-30

          			$precio_nuevo = ($precio_nuevo > $precio_minimo) ? $precio_nuevo : $precio_minimo; // 2022-10-26 defino mínimo
									 
					//$precio_nuevo = ($precio * $A * $B) * ($cot) + $C;
					update_post_meta($padre_id, '_regular_price', (float)$precio_nuevo);
					update_post_meta($padre_id, '_precio_base', (float)$precio_nuevo);
					if($sale_price && $sale_price<$precio_nuevo){
						update_post_meta($padre_id, '_price', (float)$sale_price);
					}else{
						update_post_meta($padre_id, '_price', (float)$precio_nuevo);
					}
					$product->save();
				}
        $json['precio_nuevo']=(float)$precio_nuevo;
				wp_reset_postdata();
			}


		}else{
			header('HTTP/1.1 404 Not Found', true, 404);
			echo json_encode([
				'msg'=>'No existe el producto'
			]);
			exit();
		}
		echo json_encode($json);
		break;
	
	default:
		# code...
		break;
}
