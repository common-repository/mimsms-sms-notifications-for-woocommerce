<?php
/*
Plugin Name: MiMSMS SMS Notifications for WooCommerce
Version: 2.02.0
Plugin URI: https://wordpress.org/plugins/mimsms-sms-notifications-for-woocommerce/
Description: Add to WooCommerce SMS notifications to your clients for order status changes. Also you can receive an SMS message when the shop get a new order and select if you want to send international SMS. The plugin add the international dial code automatically to the client phone number.
Author URI: https://www.mimsms.com
Author: MiMSMS | A part of Code For Host Inc
Requires at least: 3.8
Tested up to: 5.4
WC requires at least: 2.1
WC tested up to: 4.0.1

Text Domain: mimsms-sms-notifications-for-woocommerce
Domain Path: /languages

@package MiMSMS SMS Notifications fot WooCommerce
@category Core
@author MiMSMS | A part of Code For Host Inc
*/

//Igual no deberías poder abrirme
defined( 'ABSPATH' ) || exit;

//Definimos constantes
define( 'DIRECCION_mim_sms', plugin_basename( __FILE__ ) );

//Funciones generales de MiMSMS
include_once( 'includes/admin/funciones-mimsms.php' );

//¿Está activo WooCommerce?
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
if ( is_plugin_active( 'woocommerce/woocommerce.php' ) || is_network_only_plugin( 'woocommerce/woocommerce.php' ) ) {
	//Cargamos funciones necesarias
	include_once( 'includes/admin/funciones.php' );

	//Comprobamos si está instalado y activo WPML
	$wpml_activo = function_exists( 'icl_object_id' );
	
	//Actualiza las traducciones de los mensajes SMS
	function mimsms_registra_wpml( $mimsms_settings ) {
		global $wpml_activo;
		
		//Registramos los textos en WPML
		if ( $wpml_activo && function_exists( 'icl_register_string' ) ) {
			icl_register_string( 'mim_sms', 'mensaje_pedido', $mimsms_settings['mensaje_pedido'] );
			icl_register_string( 'mim_sms', 'mensaje_recibido', $mimsms_settings['mensaje_recibido'] );
			icl_register_string( 'mim_sms', 'mensaje_procesando', $mimsms_settings['mensaje_procesando'] );
			icl_register_string( 'mim_sms', 'mensaje_completado', $mimsms_settings['mensaje_completado'] );
			icl_register_string( 'mim_sms', 'mensaje_canceledado', $mimsms_settings['mensaje_canceledado'] );
			icl_register_string( 'mim_sms', 'mensaje_nota', $mimsms_settings['mensaje_nota'] );
		} else if ( $wpml_activo ) {
			do_action( 'wpml_register_single_string', 'mim_sms', 'mensaje_pedido', $mimsms_settings['mensaje_pedido'] );
			do_action( 'wpml_register_single_string', 'mim_sms', 'mensaje_recibido', $mimsms_settings['mensaje_recibido'] );
			do_action( 'wpml_register_single_string', 'mim_sms', 'mensaje_procesando', $mimsms_settings['mensaje_procesando'] );
			do_action( 'wpml_register_single_string', 'mim_sms', 'mensaje_completado', $mimsms_settings['mensaje_completado'] );
			do_action( 'wpml_register_single_string', 'mim_sms', 'mensaje_canceledado', $mimsms_settings['mensaje_canceledado'] );
			do_action( 'wpml_register_single_string', 'mim_sms', 'mensaje_nota', $mimsms_settings['mensaje_nota'] );
		}
	}
	
	//Inicializamos las traducciones y los proveedores
	function mim_sms_inicializacion() {
		global $mimsms_settings;

		mimsms_registra_wpml( $mimsms_settings );
	}
	add_action( 'init', 'mim_sms_inicializacion' );

	//Pinta el formulario de configuración
	function mim_sms_tab() {
		include( 'includes/admin/funciones-formulario.php' );
		include( 'includes/formulario.php' );
	}

	//Añade en el menú a WooCommerce
	function mim_sms_admin_menu() {
		add_submenu_page( 'woocommerce', __( 'MiMSMS| WooCommerce SMS Notifications', 'mimsms-sms-notifications-for-woocommerce' ),  __( 'SMS Notifications', 'mimsms-sms-notifications-for-woocommerce' ) , 'manage_woocommerce', 'mim_sms', 'mim_sms_tab' );
	}
	add_action( 'admin_menu', 'mim_sms_admin_menu', 15 );

	//Carga los scripts y CSS de WooCommerce
	function mim_sms_screen_id( $woocommerce_screen_ids ) {
		$woocommerce_screen_ids[] = 'woocommerce_page_mim_sms';

		return $woocommerce_screen_ids;
	}
	add_filter( 'woocommerce_screen_ids', 'mim_sms_screen_id' );

	//Registra las opciones
	function mim_sms_registra_opciones() {
		global $mimsms_settings;
	
		register_setting( 'mimsms_settings_group', 'mimsms_settings', 'mim_sms_update' );
		$mimsms_settings = get_option( 'mimsms_settings' );

		if ( isset( $mimsms_settings['estados_personalizados'] ) && !empty( $mimsms_settings['estados_personalizados'] ) ) { //Comprueba la existencia de estados personalizados
			foreach ( $mimsms_settings['estados_personalizados'] as $estado ) {
				add_action( "woocommerce_order_status_{$estado}", 'mim_sms_procesa_estados', 10 );
			}
		}
	}
	add_action( 'admin_init', 'mim_sms_registra_opciones' );
	
	function mim_sms_update( $mimsms_settings ) {
		mimsms_registra_wpml( $mimsms_settings );
		
		return $mimsms_settings;
	}

	//Procesa el SMS
	function mim_sms_procesa_estados( $pedido, $notificacion = false ) {
		global $mimsms_settings, $wpml_activo;
		
		$numero_de_pedido	= $pedido;
		$pedido				= new WC_Order( $numero_de_pedido );
		$estado				= is_callable( array( $pedido, 'get_status' ) ) ? $pedido->get_status() : $pedido->status;

		//Comprobamos si se tiene que enviar el mensaje o no
		if ( isset( $mimsms_settings['mensajes'] ) ) {
			if ( $estado == 'on-hold' && !array_intersect( array( "todos", "mensaje_pedido", "mensaje_recibido" ), $mimsms_settings['mensajes'] ) ) {
				return;
			} else if ( $estado == 'processing' && !array_intersect( array( "todos", "mensaje_pedido", "mensaje_procesando" ), $mimsms_settings['mensajes'] ) ) {
				return;
			} else if ( $estado == 'completed' && !array_intersect( array( "todos", "mensaje_completado" ), $mimsms_settings['mensajes'] ) ) {
				return;
			} else if ( $estado == 'cancelled' && !array_intersect( array( "todos", "mensaje_canceledado" ), $mimsms_settings['mensajes'] ) ) {
				return;
			}
		} else {
			return;
		}
		//Permitir que otros plugins impidan que se envíe el SMS
		if ( !apply_filters( 'mim_sms_send_message', true, $pedido ) ) {
			return;
		}

		//Recoge datos del formulario de facturación
		$billing_country		= is_callable( array( $pedido, 'get_billing_country' ) ) ? $pedido->get_billing_country() : $pedido->billing_country;
		$billing_phone			= is_callable( array( $pedido, 'get_billing_phone' ) ) ? $pedido->get_billing_phone() : $pedido->billing_phone;
		$shipping_country		= is_callable( array( $pedido, 'get_shipping_country' ) ) ? $pedido->get_shipping_country() : $pedido->shipping_country;
		$campo_envio			= get_post_meta( $numero_de_pedido, $mimsms_settings['campo_envio'], false );
		$campo_envio			= ( isset( $campo_envio[0] ) ) ? $campo_envio[0] : '';
		$telefono				= mim_sms_procesa_el_telefono( $pedido, $billing_phone, $mimsms_settings['servicio'] );
		$telefono_envio			= mim_sms_procesa_el_telefono( $pedido, $campo_envio, $mimsms_settings['servicio'], false, true );
		$enviar_envio			= ( $telefono != $telefono_envio && isset( $mimsms_settings['envio'] ) && $mimsms_settings['envio'] == 1 ) ? true : false;
		$internacional			= ( $billing_country && ( WC()->countries->get_base_country() != $billing_country ) ) ? true : false;
		$internacional_envio	= ( $shipping_country && ( WC()->countries->get_base_country() != $shipping_country ) ) ? true : false;
		//Teléfono propietario
		if ( strpos( $mimsms_settings['telefono'], "|" ) ) {
			$administradores = explode( "|", $mimsms_settings['telefono'] ); //Existe más de uno
		}
		if ( isset( $administradores ) ) {
			foreach( $administradores as $administrador ) {
				$telefono_propietario[]	= mim_sms_procesa_el_telefono( $pedido, $administrador, $mimsms_settings['servicio'], true );
			}
		} else {
			$telefono_propietario = mim_sms_procesa_el_telefono( $pedido, $mimsms_settings['telefono'], $mimsms_settings['servicio'], true );	
		}
		
		//WPML
		if ( function_exists( 'icl_register_string' ) || !$wpml_activo ) { //Versión anterior a la 3.2
			$mensaje_pedido		= ( $wpml_activo ) ? icl_translate( 'mim_sms', 'mensaje_pedido', $mimsms_settings['mensaje_pedido'] ) : $mimsms_settings['mensaje_pedido'];
			$mensaje_recibido	= ( $wpml_activo ) ? icl_translate( 'mim_sms', 'mensaje_recibido', $mimsms_settings['mensaje_recibido'] ) : $mimsms_settings['mensaje_recibido'];
			$mensaje_procesando	= ( $wpml_activo ) ? icl_translate( 'mim_sms', 'mensaje_procesando', $mimsms_settings['mensaje_procesando'] ) : $mimsms_settings['mensaje_procesando'];
			$mensaje_completado	= ( $wpml_activo ) ? icl_translate( 'mim_sms', 'mensaje_completado', $mimsms_settings['mensaje_completado'] ) : $mimsms_settings['mensaje_completado'];
			$mensaje_canceledado	= ( $wpml_activo ) ? icl_translate( 'mim_sms', 'mensaje_canceledado', $mimsms_settings['mensaje_canceledado'] ) : $mimsms_settings['mensaje_canceledado'];
		} else if ( $wpml_activo ) { //Versión 3.2 o superior
			$mensaje_pedido		= apply_filters( 'wpml_translate_single_string', $mimsms_settings['mensaje_pedido'], 'mim_sms', 'mensaje_pedido' );
			$mensaje_recibido	= apply_filters( 'wpml_translate_single_string', $mimsms_settings['mensaje_recibido'], 'mim_sms', 'mensaje_recibido' );
			$mensaje_procesando	= apply_filters( 'wpml_translate_single_string', $mimsms_settings['mensaje_procesando'], 'mim_sms', 'mensaje_procesando' );
			$mensaje_completado	= apply_filters( 'wpml_translate_single_string', $mimsms_settings['mensaje_completado'], 'mim_sms', 'mensaje_completado' );
			$mensaje_canceledado	= apply_filters( 'wpml_translate_single_string', $mimsms_settings['mensaje_canceledado'], 'mim_sms', 'mensaje_canceledado' );
		}
		
		//Cargamos los proveedores SMS
		include_once( 'includes/admin/proveedores.php' );
		//Envía el SMS
		switch( $estado ) {
			case 'on-hold': //Pedido en espera
				if ( !!array_intersect( array( "todos", "mensaje_pedido" ), $mimsms_settings['mensajes'] ) && isset( $mimsms_settings['notificacion'] ) && $mimsms_settings['notificacion'] == 1 && !$notificacion ) {
					if ( !is_array( $telefono_propietario ) ) {
						mim_sms_envia_sms( $mimsms_settings, $telefono_propietario, mim_sms_procesa_variables( $mensaje_pedido, $pedido, $mimsms_settings['variables'] ) ); //Mensaje para el propietario
					} else {
						foreach( $telefono_propietario as $administrador ) {
							mim_sms_envia_sms( $mimsms_settings, $administrador, mim_sms_procesa_variables( $mensaje_pedido, $pedido, $mimsms_settings['variables'] ) ); //Mensaje para los propietarios
						}
					}
				}
						
				if ( !!array_intersect( array( "todos", "mensaje_recibido" ), $mimsms_settings['mensajes'] ) ) {
					//Limpia el temporizador para pedidos recibidos
					wp_clear_scheduled_hook( 'mim_sms_ejecuta_el_temporizador' );

					$mensaje = mim_sms_procesa_variables( $mensaje_recibido, $pedido, $mimsms_settings['variables'] ); //Mensaje para el cliente

					//Temporizador para pedidos recibidos
					if ( isset( $mimsms_settings['temporizador'] ) && $mimsms_settings['temporizador'] > 0 ) {
						wp_schedule_single_event( time() + ( absint( $mimsms_settings['temporizador'] ) * 60 * 60 ), 'mim_sms_ejecuta_el_temporizador' );
					}
				}
				break;
			case 'processing': //Pedido procesando
				if ( !!array_intersect( array( "todos", "mensaje_pedido" ), $mimsms_settings['mensajes'] ) && isset( $mimsms_settings['notificacion'] ) && $mimsms_settings['notificacion'] == 1 && $notificacion ) {
					if ( !is_array( $telefono_propietario ) ) {
						mim_sms_envia_sms( $mimsms_settings, $telefono_propietario, mim_sms_procesa_variables( $mensaje_pedido, $pedido, $mimsms_settings['variables'] ) ); //Mensaje para el propietario
					} else {
						foreach( $telefono_propietario as $administrador ) {
							mim_sms_envia_sms( $mimsms_settings, $administrador, mim_sms_procesa_variables( $mensaje_pedido, $pedido, $mimsms_settings['variables'] ) ); //Mensaje para los propietarios
						}
					}
				}
				if ( !!array_intersect( array( "todos", "mensaje_procesando" ), $mimsms_settings['mensajes'] ) ) {
					$mensaje = mim_sms_procesa_variables( $mensaje_procesando, $pedido, $mimsms_settings['variables'] );
				}
				break;
			case 'completed': //Pedido completado
				if ( !!array_intersect( array( "todos", "mensaje_completado" ), $mimsms_settings['mensajes'] ) ) {
					$mensaje = mim_sms_procesa_variables( $mensaje_completado, $pedido, $mimsms_settings['variables'] );
				}
				break;
			case 'cancelled': //Pedido completado
				if ( !!array_intersect( array( "todos", "mensaje_canceledado" ), $mimsms_settings['mensajes'] ) ) {
					$mensaje = mim_sms_procesa_variables( $mensaje_canceledado, $pedido, $mimsms_settings['variables'] );
				}
				break;
			default: //Pedido con estado personalizado
				$mensaje = mim_sms_procesa_variables( $mimsms_settings[$estado], $pedido, $mimsms_settings['variables'] );
		}

		if ( isset( $mensaje ) && ( !$internacional || ( isset( $mimsms_settings['internacional'] ) && $mimsms_settings['internacional'] == 1 ) ) && !$notificacion ) {
			if ( !is_array( $telefono ) ) {
				mim_sms_envia_sms( $mimsms_settings, $telefono, $mensaje ); //Mensaje para el teléfono de facturación
			} else {
				foreach( $telefono as $cliente ) {
					mim_sms_envia_sms( $mimsms_settings, $cliente, $mensaje ); //Mensaje para los teléfonos recibidos
				}
			}
			if ( $enviar_envio ) {
				mim_sms_envia_sms( $mimsms_settings, $telefono_envio, $mensaje ); //Mensaje para el teléfono de envío
			}
		}
	}
	add_action( 'woocommerce_order_status_pending_to_on-hold_notification', 'mim_sms_procesa_estados', 10 ); //Funciona cuando el pedido es marcado como recibido
	add_action( 'woocommerce_order_status_failed_to_on-hold_notification', 'mim_sms_procesa_estados', 10 );
	add_action( 'woocommerce_order_status_processing', 'mim_sms_procesa_estados', 10 ); //Funciona cuando el pedido es marcado como procesando
	add_action( 'woocommerce_order_status_completed', 'mim_sms_procesa_estados', 10 ); //Funciona cuando el pedido es marcado como completo
	add_action( 'woocommerce_order_status_cancelled', 'mim_sms_procesa_estados', 10 ); //Funciona cuando el pedido es marcado como completo

	function mim_sms_notificacion( $pedido ) {
		mim_sms_procesa_estados( $pedido, true );
	}
	add_action( 'woocommerce_order_status_pending_to_processing_notification', 'mim_sms_notificacion', 10 ); //Funciona cuando el pedido es marcado directamente como procesando
	
	//Temporizador
	function mim_sms_temporizador() {
		global $mimsms_settings;
		
		$pedidos = wc_get_orders( array(
			'limit'			=> -1,
			'date_created'	=> '<' . ( time() - ( absint( $mimsms_settings['temporizador'] ) * 60 * 60 ) - 1 ),
			'status'		=> 'on-hold',
		) );

		if ( $pedidos ) {
			foreach ( $pedidos as $pedido ) {
				mim_sms_procesa_estados( is_callable( array( $pedido, 'get_id' ) ) ? $pedido->get_id() : $pedido->id, false );
			}
		}
	}
	add_action( 'mim_sms_ejecuta_el_temporizador', 'mim_sms_temporizador' );

	//Envía las notas de cliente por SMS
	function mim_sms_procesa_notas( $datos ) {
		global $mimsms_settings, $wpml_activo;
		
		//Comprobamos si se tiene que enviar el mensaje
		if ( isset( $mimsms_settings['mensajes']) && !array_intersect( array( "todos", "mensaje_nota" ), $mimsms_settings['mensajes'] ) ) {
			return;
		}
	
		//Pedido
		$numero_de_pedido		= $datos['order_id'];
		$pedido					= new WC_Order( $numero_de_pedido );
		//Recoge datos del formulario de facturación
		$billing_country		= is_callable( array( $pedido, 'get_billing_country' ) ) ? $pedido->get_billing_country() : $pedido->billing_country;
		$billing_phone			= is_callable( array( $pedido, 'get_billing_phone' ) ) ? $pedido->get_billing_phone() : $pedido->billing_phone;
		$shipping_country		= is_callable( array( $pedido, 'get_shipping_country' ) ) ? $pedido->get_shipping_country() : $pedido->shipping_country;	
		$campo_envio			= get_post_meta( $numero_de_pedido, $mimsms_settings['campo_envio'], false );
		$campo_envio			= ( isset( $campo_envio[0] ) ) ? $campo_envio[0] : '';
		$telefono				= mim_sms_procesa_el_telefono( $pedido, $billing_phone, $mimsms_settings['servicio'] );
		$telefono_envio			= mim_sms_procesa_el_telefono( $pedido, $campo_envio, $mimsms_settings['servicio'], false, true );
		$enviar_envio			= ( isset( $mimsms_settings['envio'] ) && $telefono != $telefono_envio && $mimsms_settings['envio'] == 1 ) ? true : false;
		$internacional			= ( $billing_country && ( WC()->countries->get_base_country() != $billing_country ) ) ? true : false;
		$internacional_envio	= ( $shipping_country && ( WC()->countries->get_base_country() != $shipping_country ) ) ? true : false;
		//Recoge datos del formulario de facturación
		$billing_country		= is_callable( array( $pedido, 'get_billing_country' ) ) ? $pedido->get_billing_country() : $pedido->billing_country;
		$billing_phone			= is_callable( array( $pedido, 'get_billing_phone' ) ) ? $pedido->get_billing_phone() : $pedido->billing_phone;
		$shipping_country		= is_callable( array( $pedido, 'get_shipping_country' ) ) ? $pedido->get_shipping_country() : $pedido->shipping_country;
		$campo_envio			= get_post_meta( $numero_de_pedido, $mimsms_settings['campo_envio'], false );
		$campo_envio			= ( isset( $campo_envio[0] ) ) ? $campo_envio[0] : '';
		$telefono				= mim_sms_procesa_el_telefono( $pedido, $billing_phone, $mimsms_settings['servicio'] );
		$telefono_envio			= mim_sms_procesa_el_telefono( $pedido, $campo_envio, $mimsms_settings['servicio'], false, true );
		$enviar_envio			= ( $telefono != $telefono_envio && isset( $mimsms_settings['envio'] ) && $mimsms_settings['envio'] == 1 ) ? true : false;
		$internacional			= ( $billing_country && ( WC()->countries->get_base_country() != $billing_country ) ) ? true : false;
		$internacional_envio	= ( $shipping_country && ( WC()->countries->get_base_country() != $shipping_country ) ) ? true : false;

		//WPML
		if ( function_exists( 'icl_register_string' ) || !$wpml_activo ) { //Versión anterior a la 3.2
			$mensaje_nota		= ( $wpml_activo ) ? icl_translate( 'mim_sms', 'mensaje_nota', $mimsms_settings['mensaje_nota'] ) : $mimsms_settings['mensaje_nota'];
		} else if ( $wpml_activo ) { //Versión 3.2 o superior
			$mensaje_nota		= apply_filters( 'wpml_translate_single_string', $mimsms_settings['mensaje_nota'], 'mim_sms', 'mensaje_nota' );
		}
		
		//Cargamos los proveedores SMS
		include_once( 'includes/admin/proveedores.php' );		
		//Envía el SMS
		if ( !$internacional || ( isset( $mimsms_settings['internacional'] ) && $mimsms_settings['internacional'] == 1 ) ) {
			if ( !is_array( $telefono ) ) {
				mim_sms_envia_sms( $mimsms_settings, $telefono, mim_sms_procesa_variables( $mensaje_nota, $pedido, $mimsms_settings['variables'], wptexturize( $datos['customer_note'] ) ) ); //Mensaje para el teléfono de facturación
			} else {
				foreach( $telefono as $cliente ) {
					mim_sms_envia_sms( $mimsms_settings, $cliente, mim_sms_procesa_variables( $mensaje_nota, $pedido, $mimsms_settings['variables'], wptexturize( $datos['customer_note'] ) ) ); //Mensaje para los teléfonos recibidos
				}
			}
			if ( $enviar_envio ) {
				mim_sms_envia_sms( $mimsms_settings, $telefono_envio, mim_sms_procesa_variables( $mensaje_nota, $pedido, $mimsms_settings['variables'], wptexturize( $datos['customer_note'] ) ) ); //Mensaje para el teléfono de envío
			}
		}
	}
	add_action( 'woocommerce_new_customer_note', 'mim_sms_procesa_notas', 10 );
} else {
	add_action( 'admin_notices', 'mim_sms_requiere_wc' );
}

//Muestra el mensaje de activación de WooCommerce y desactiva el plugin
function mim_sms_requiere_wc() {
	global $mim_sms;
		
	echo '<div class="error fade" id="message"><h3>' . $mim_sms['plugin'] . '</h3><h4>' . __( "This plugin require WooCommerce active to run!", 'mimsms-sms-notifications-for-woocommerce' ) . '</h4></div>';
	deactivate_plugins( DIRECCION_mim_sms );
}
