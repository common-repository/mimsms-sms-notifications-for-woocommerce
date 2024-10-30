<?php
//Envía el mensaje SMS
function mim_sms_envia_sms( $mimsms_settings, $telefono, $mensaje ) {
	switch ( $mimsms_settings['servicio'] ) {
		case "mimsms_esms":
			$respuesta = wp_remote_get( "http://brandsms.mimsms.com/smsapi?api_key=" . $mimsms_settings['clave_mimsms_esms'] . "&type=text&contacts=" . $telefono . "&senderid=" . $mimsms_settings['identificador_mimsms_esms'] . "&msg=" . mim_sms_codifica_el_mensaje( $mensaje ) );
			break;
		case "mimsms_dotbd":
			$respuesta = wp_remote_get( "http://mimsms.com.bd/smsAPI?sendsms&apikey=" . $mimsms_settings['apikey_mimsms_dotbd'] . "&apitoken=" . $mimsms_settings['apitoken_mimsms_dotbd'] . "&type=" . $mimsms_settings['sms_type_mimsms_dotbd'] . "&from=" . $mimsms_settings['senderid_mimsms_dotbd'] . "&to=" . $telefono . "&text=" . mim_sms_codifica_el_mensaje( $mensaje ) . "&route=0" );
			break;
	}

	if ( isset( $mimsms_settings['debug'] ) && $mimsms_settings['debug'] == "1" && isset( $mimsms_settings['campo_debug'] ) ) {
		$correo	= __( 'Mobile number:', 'mimsms-sms-notifications-for-woocommerce' ) . "\r\n" . $telefono . "\r\n\r\n";
		$correo	.= __( 'Message: ', 'mimsms-sms-notifications-for-woocommerce' ) . "\r\n" . $mensaje . "\r\n\r\n"; 
		$correo	.= __( 'Gateway answer: ', 'mimsms-sms-notifications-for-woocommerce' ) . "\r\n" . print_r( $respuesta, true );
		wp_mail( $mimsms_settings['campo_debug'], 'WC - MiMSMS SMS Notifications', $correo, 'charset=UTF-8' . "\r\n" ); 
	}
}