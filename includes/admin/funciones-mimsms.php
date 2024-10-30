<?php
//Definimos las variables
$mim_sms = array( 	
	'plugin' 		=> 'MiMSMS SMS Notifications for WooCommerce', 
	'plugin_uri' 	=> 'mimsms-sms-notifications-for-woocommerce', 
	'donacion' 		=> 'https://www.mimsms.com/portal/clientarea.php?action=addfunds',
	'soporte' 		=> 'https://www.mimsms.com/portal/supporttickets.php',
	'plugin_url' 	=> 'https://www.mimsms.com', 
	'ajustes' 		=> 'admin.php?page=mim_sms', 
	'puntuacion' 	=> 'https://wordpress.org/support/view/plugin-reviews/mimsms-sms-notifications-for-woocommerce' 
);

//Carga el idioma
load_plugin_textdomain( 'mimsms-sms-notifications-for-woocommerce', null, dirname( DIRECCION_mim_sms ) . '/languages' );

//Carga la configuración del plugin
$mimsms_settings = get_option( 'mimsms_settings' );

//Enlaces adicionales personalizados
function mim_sms_enlaces( $enlaces, $archivo ) {
	global $mim_sms;

	if ( $archivo == DIRECCION_mim_sms ) {
		$enlaces[] = '<a href="' . $mim_sms['donacion'] . '" target="_blank" title="' . __( 'Make a donation by ', 'mimsms-sms-notifications-for-woocommerce' ) . 'MiMSMS"><span class="genericon genericon-cart"></span></a>';
		$enlaces[] = '<a href="'. $mim_sms['plugin_url'] . '" target="_blank" title="' . $mim_sms['plugin'] . '"><strong class="codeforhostinc">MiMSMS</strong></a>';
		$enlaces[] = '<a href="https://www.facebook.com/bulksms.mimsms" title="' . __( 'Follow us on ', 'mimsms-sms-notifications-for-woocommerce' ) . 'Facebook" target="_blank"><span class="genericon genericon-facebook-alt"></span></a> <a href="https://twitter.com/mim_sms" title="' . __( 'Follow us on ', 'mimsms-sms-notifications-for-woocommerce' ) . 'Twitter" target="_blank"><span class="genericon genericon-twitter"></span></a> <a href="https://github.com/mimsms" title="' . __( 'Follow us on ', 'mimsms-sms-notifications-for-woocommerce' ) . 'GitHub" target="_blank"><span class="genericon genericon-github"></span></a> <a href="https://www.linkedin.com/company/mim-sms/about/" title="' . __( 'Follow us on ', 'mimsms-sms-notifications-for-woocommerce' ) . 'LinkedIn" target="_blank"><span class="genericon genericon-linkedin"></span></a>';
		$enlaces[] = '<a href="https://profiles.wordpress.org/codeforhost/" title="' . __( 'More plugins on ', 'mimsms-sms-notifications-for-woocommerce' ) . 'WordPress" target="_blank"><span class="genericon genericon-wordpress"></span></a>';
		$enlaces[] = '<a href="mailto:support@mimsms.com" title="' . __( 'Contact with us by ', 'mimsms-sms-notifications-for-woocommerce' ) . 'e-mail"><span class="genericon genericon-mail"></span></a> <a href="skype:tms_masum" title="' . __( 'Contact with us by ', 'mimsms-sms-notifications-for-woocommerce' ) . 'Skype"><span class="genericon genericon-skype"></span></a>';
		$enlaces[] = mim_sms_plugin( $mim_sms['plugin_uri'] );
	}

	return $enlaces;
}
add_filter( 'plugin_row_meta', 'mim_sms_enlaces', 10, 2 );

//Añade el botón de configuración
function mim_sms_enlace_de_ajustes( $enlaces ) { 
	global $mim_sms;

	$enlaces_de_ajustes = array( 
		'<a href="' . $mim_sms['ajustes'] . '" title="' . __( 'Settings of ', 'mimsms-sms-notifications-for-woocommerce' ) . $mim_sms['plugin'] .'">' . __( 'Settings', 'mimsms-sms-notifications-for-woocommerce' ) . '</a>', 
		'<a href="' . $mim_sms['soporte'] . '" title="' . __( 'Support of ', 'mimsms-sms-notifications-for-woocommerce' ) . $mim_sms['plugin'] .'">' . __( 'Support', 'mimsms-sms-notifications-for-woocommerce' ) . '</a>' 
	);
	foreach( $enlaces_de_ajustes as $enlace_de_ajustes )	{
		array_unshift( $enlaces, $enlace_de_ajustes );
	}

	return $enlaces; 
}
$plugin = DIRECCION_mim_sms; 
add_filter( "plugin_action_links_$plugin", 'mim_sms_enlace_de_ajustes' );

//Obtiene toda la información sobre el plugin
function mim_sms_plugin( $nombre ) {
	global $mim_sms;
	
	$argumentos	= ( object ) array( 
		'slug'		=> $nombre 
	);
	$consulta	= array( 
		'action'	=> 'plugin_information', 
		'timeout'	=> 15, 
		'request'	=> serialize( $argumentos )
	);
	$respuesta	= get_transient( 'mim_sms_plugin' );
	if ( false === $respuesta ) {
		$respuesta = wp_remote_post( 'https://api.wordpress.org/plugins/info/1.0/', array( 'body'	=> $consulta ) );
		set_transient( 'mim_sms_plugin', $respuesta, 24 * HOUR_IN_SECONDS );
	}
	if ( !is_wp_error( $respuesta ) ) {
		$plugin = get_object_vars( unserialize( $respuesta['body'] ) );
	} else {
		$plugin['rating'] = 100;
	}
	$plugin['rating'] = 100;

	$rating = array(
	   'rating'		=> $plugin['rating'],
	   'type'		=> 'percent',
	   'number'		=> $plugin['num_ratings'],
	);
	ob_start();
	wp_star_rating( $rating );
	$estrellas = ob_get_contents();
	ob_end_clean();

	return '<a title="' . sprintf( __( 'Please, rate %s:', 'mimsms-sms-notifications-for-woocommerce' ), $mim_sms['plugin'] ) . '" href="' . $mim_sms['puntuacion'] . '?rate=5#postform" class="estrellas">' . $estrellas . '</a>';
}

//Hoja de estilo
function mim_sms_estilo() {
	wp_register_style( 'mim_sms_hoja_de_estilo', plugins_url( 'assets/css/style.css', DIRECCION_mim_sms ) ); //Carga la hoja de estilo
	wp_enqueue_style( 'mim_sms_hoja_de_estilo' ); //Carga la hoja de estilo
}
add_action( 'admin_enqueue_scripts', 'mim_sms_estilo' );

//Eliminamos todo rastro del plugin al desinstalarlo
function mim_sms_desinstalar() {
	delete_option( 'mimsms_settings' );
	delete_transient( 'mim_sms_plugin' );
}
register_uninstall_hook( __FILE__, 'mim_sms_desinstalar' );
