<?php global $mimsms_settings, $mim_sms; ?>

<div class="wrap woocommerce">
	<h2>
		<?php _e( 'MiMSMS SMS Notifications Options.', 'mimsms-sms-notifications-for-woocommerce' ); ?>
	</h2>
	<h3><a href="<?php echo $mim_sms['plugin_url']; ?>" title="MiMSMS | A part of Code For Host Inc"><?php echo $mim_sms['plugin']; ?></a></h3>
	<p>
		<?php _e( 'Add to WooCommerce the possibility to send <abbr title="Short Message Service" lang="en">SMS</abbr> notifications to the client each time you change the order status. Notifies the owner, if desired, when the store has a new order. You can also send customer notes.', 'mimsms-sms-notifications-for-woocommerce' ); ?>
	</p>
	<?php include( 'cuadro-informacion.php' ); ?>
	<form method="post" action="options.php">
		<?php settings_fields( 'mimsms_settings_group' ); ?>
		<div class="cabecera"> <a href="<?php echo $mim_sms['plugin_url']; ?>" title="<?php echo $mim_sms['plugin']; ?>" target="_blank"><img src="<?php echo plugins_url( 'assets/images/cabecera.jpg', DIRECCION_mim_sms ); ?>" class="imagen" alt="<?php echo $mim_sms['plugin']; ?>" /></a> </div>
		<table class="form-table mimsms-table">
			<tr valign="top">
				<th scope="row" class="titledesc">
					<label for="mimsms_settings[servicio]">
						<?php _e( '<abbr title="Short Message Service" lang="en">SMS</abbr> gateway:', 'mimsms-sms-notifications-for-woocommerce' ); ?>
						<span class="woocommerce-help-tip" data-tip="<?php _e( 'Select your SMS gateway', 'mimsms-sms-notifications-for-woocommerce' ); ?>"></span>
					</label>
				</th>
				<td class="forminp forminp-number">
					<select class="wc-enhanced-select servicio" id="mimsms_settings[servicio]" name="mimsms_settings[servicio]" tabindex="<?php echo $tab++; ?>">
						<?php mim_sms_listado_de_proveedores( $listado_de_proveedores ); ?>
					</select>
				</td>
			</tr>
			<?php mim_sms_campos_de_proveedores( $listado_de_proveedores, $campos_de_proveedores, $opciones_de_proveedores ); ?>
			<tr valign="top">
				<th scope="row" class="titledesc">
					<label for="mimsms_settings[telefono]">
						<?php _e( 'Your mobile number:', 'mimsms-sms-notifications-for-woocommerce' ); ?>
						<span class="woocommerce-help-tip" data-tip="<?php _e( 'The mobile number registered in your SMS gateway account and where you receive the SMS messages. You can add multiple mobile numbers separeted by | character. Example: xxxxxxxxx|yyyyyyyyy', 'mimsms-sms-notifications-for-woocommerce' ); ?>"></span> </label>
				</th>
				<td class="forminp forminp-number"><input type="text" id="mimsms_settings[telefono]" name="mimsms_settings[telefono]" size="50" value="<?php echo ( isset( $mimsms_settings['telefono'] ) ) ? $mimsms_settings['telefono'] : ''; ?>" tabindex="<?php echo $tab++; ?>"/>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row" class="titledesc">
					<label for="mimsms_settings[notificacion]">
						<?php _e( 'New order notification:', 'mimsms-sms-notifications-for-woocommerce' ); ?>
						<span class="woocommerce-help-tip" data-tip="<?php _e( " Check if you want to receive a SMS message when there 's a new order", 'mimsms-sms-notifications-for-woocommerce ' ); ?>"></span> </label> </th>
        <td class="forminp forminp-number"><input id="mimsms_settings[notificacion]" name="mimsms_settings[notificacion]" type="checkbox" value="1" <?php echo ( isset( $mimsms_settings['notificacion'] ) && $mimsms_settings['notificacion'] == "1" ) ? 'checked="checked" ' : ' '; ?> tabindex="<?php echo $tab++; ?>" /></td>
      </tr>
      <tr valign="top">
        <th scope="row" class="titledesc"> <label for="mimsms_settings[internacional]">
            <?php _e( 'Send international <abbr title="Short Message Service" lang="en">SMS</abbr>?:', 'mimsms-sms-notifications-for-woocommerce' ); ?>
						<span class="woocommerce-help-tip" data-tip="<?php _e( 'Check if you want to send international SMS messages', 'mimsms-sms-notifications-for-woocommerce' ); ?>"></span> </label>
				</th>
				<td class="forminp forminp-number"><input id="mimsms_settings[internacional]" name="mimsms_settings[internacional]" type="checkbox" value="1" <?php echo ( isset( $mimsms_settings['internacional'] ) && $mimsms_settings['internacional'] == "1" ) ? 'checked="checked"' : ''; ?> tabindex="
					<?php echo $tab++; ?>" /></td>
			</tr>
			<tr valign="top">
				<th scope="row" class="titledesc">
					<label for="mimsms_settings[envio]">
						<?php _e( 'Send <abbr title="Short Message Service" lang="en">SMS</abbr> to shipping mobile?:', 'mimsms-sms-notifications-for-woocommerce' ); ?>
						<span class="woocommerce-help-tip" data-tip="<?php _e( 'Check if you want to send SMS messages to shipping mobile numbers, only if it is different from billing mobile number', 'mimsms-sms-notifications-for-woocommerce' ); ?>"></span>
					</label>
				</th>
				<td class="forminp forminp-number"><input id="mimsms_settings[envio]" name="mimsms_settings[envio]" type="checkbox" value="1" <?php echo ( isset( $mimsms_settings['envio'] ) && $mimsms_settings['envio'] == "1" ) ? 'checked="checked"' : ''; ?> tabindex="
					<?php echo $tab++; ?>" class="envio" /></td>
			</tr>
			<tr valign="top" class="campo_envio">
				<th scope="row" class="titledesc">
					<label for="mimsms_settings[campo_envio]">
						<?php _e( 'Shipping mobile field:', 'mimsms-sms-notifications-for-woocommerce' ); ?>
						<span class="woocommerce-help-tip" data-tip="<?php _e( 'Select the shipping mobile field', 'mimsms-sms-notifications-for-woocommerce' ); ?>"></span>
					</label>
				</th>
				<td class="forminp forminp-number">
					<select id="mimsms_settings[campo_envio]" name="mimsms_settings[campo_envio]" class="wc-enhanced-select" tabindex="<?php echo $tab++; ?>">
						<?php mim_sms_campos_de_envio(); ?>
					</select>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row" class="titledesc">
					<label for="mimsms_settings[productos]">
						<?php _e( 'order_product variable full details:', 'mimsms-sms-notifications-for-woocommerce' ); ?>
						<span class="woocommerce-help-tip" data-tip="<?php _e( 'Check if you want to send the SMS messages with full order product information', 'mimsms-sms-notifications-for-woocommerce' ); ?>"></span>
					</label>
				</th>
				<td class="forminp forminp-number"><input id="mimsms_settings[productos]" name="mimsms_settings[productos]" type="checkbox" value="1" <?php echo ( isset( $mimsms_settings['productos'] ) && $mimsms_settings['productos'] == "1" ) ? 'checked="checked"' : ''; ?> tabindex="
					<?php echo $tab++; ?>" /></td>
			</tr>
			<?php if ( !empty( $listado_de_estados ) ) : //Comprueba la existencia de estados personalizados ?>
			<tr valign="top">
				<th scope="row" class="titledesc">
					<label for="mimsms_settings[estados_personalizados]">
						<?php _e( 'Custom Order Statuses & Actions:', 'mimsms-sms-notifications-for-woocommerce' ); ?>
						<span class="woocommerce-help-tip" data-tip="<?php _e( 'Select your own statuses.', 'mimsms-sms-notifications-for-woocommerce' ); ?>"></span>
					</label>
				</th>
				<td class="forminp forminp-number">
					<select multiple="multiple" class="wc-enhanced-select multiselect estados_personalizados" id="mimsms_settings[estados_personalizados]" name="mimsms_settings[estados_personalizados][]" tabindex="<?php echo $tab++; ?>">
						<?php mim_sms_listado_de_estados( $listado_de_estados ); ?>
					</select>
				</td>
			</tr>
			<?php foreach ( $listado_de_estados as $nombre_de_estado => $estado_personalizado ) : ?>
			<tr valign="top" class="<?php echo $estado_personalizado; ?>">
				<!-- <?php echo $nombre_de_estado; ?> -->
				<th scope="row" class="titledesc">
					<label for="mimsms_settings[<?php echo $estado_personalizado; ?>]">
						<?php echo sprintf( __( '%s state custom message:', 'mimsms-sms-notifications-for-woocommerce' ), $nombre_de_estado ); ?>
						<span class="woocommerce-help-tip" data-tip="<?php _e( 'You can customize your message. Remember that you can use this variables: %id%, %order_key%, %billing_first_name%, %billing_last_name%, %billing_company%, %billing_address_1%, %billing_address_2%, %billing_city%, %billing_postcode%, %billing_country%, %billing_state%, %billing_email%, %billing_phone%, %shipping_first_name%, %shipping_last_name%, %shipping_company%, %shipping_address_1%, %shipping_address_2%, %shipping_city%, %shipping_postcode%, %shipping_country%, %shipping_state%, %shipping_method%, %shipping_method_title%, %payment_method%, %payment_method_title%, %order_discount%, %cart_discount%, %order_tax%, %order_shipping%, %order_shipping_tax%, %order_total%, %status%, %prices_include_tax%, %tax_display_cart%, %display_totals_ex_tax%, %display_cart_ex_tax%, %order_date%, %modified_date%, %customer_message%, %customer_note%, %post_status%, %shop_name%, %order_product% and %note%.', 'mimsms-sms-notifications-for-woocommerce' ); ?>"></span>
					</label>
				</th>
				<td class="forminp forminp-number"><textarea id="mimsms_settings[<?php echo $estado_personalizado; ?>]" name="mimsms_settings[<?php echo $estado_personalizado; ?>]" cols="50" rows="5" tabindex="<?php echo $tab++; ?>"><?php echo stripcslashes( isset( $mimsms_settings[$estado_personalizado] ) ? $mimsms_settings[$estado_personalizado] : "" ); ?></textarea>
				</td>
			</tr>
			<?php endforeach; ?>
			<?php endif; ?>
			<tr valign="top">
				<th scope="row" class="titledesc">
					<label for="mimsms_settings[variables]">
						<?php _e( 'Custom variables:', 'mimsms-sms-notifications-for-woocommerce' ); ?>
						<span class="woocommerce-help-tip" data-tip="<?php _e( 'You can add your own variables. Each variable must be entered onto a new line without percentage character ( % ). Example: <code>_custom_variable_name</code><br /><code>_another_variable_name</code>.', 'mimsms-sms-notifications-for-woocommerce' ); ?>"></span>
					</label>
				</th>
				<td class="forminp forminp-number"><textarea id="mimsms_settings[variables]" name="mimsms_settings[variables]" cols="50" rows="5" tabindex="<?php echo $tab++; ?>"><?php echo stripcslashes( isset( $mimsms_settings['variables'] ) ? $mimsms_settings['variables'] : '' ); ?></textarea>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row" class="titledesc">
					<label for="mimsms_settings[productos]">
						<?php _e( 'Send only this messages:', 'mimsms-sms-notifications-for-woocommerce' ); ?>
						<span class="woocommerce-help-tip" data-tip="<?php _e( 'Select what messages do you want to send', 'mimsms-sms-notifications-for-woocommerce' ); ?>"></span>
					</label>
				</th>
				<td class="forminp forminp-number">
					<select multiple="multiple" class="wc-enhanced-select multiselect mensajes" id="mimsms_settings[mensajes]" name="mimsms_settings[mensajes][]" tabindex="<?php echo $tab++; ?>">
						<?php mim_sms_listado_de_mensajes( $listado_de_mensajes ); ?>
					</select>
			</tr>
			<tr valign="top" class="mensaje_pedido">
				<th scope="row" class="titledesc">
					<label for="mimsms_settings[mensaje_pedido]">
						<?php _e( 'Owner custom message', 'mimsms-sms-notifications-for-woocommerce' ); ?>:
						<span class="woocommerce-help-tip" data-tip="<?php _e( 'You can customize your message. Remember that you can use this variables: %id%, %order_key%, %billing_first_name%, %billing_last_name%, %billing_company%, %billing_address_1%, %billing_address_2%, %billing_city%, %billing_postcode%, %billing_country%, %billing_state%, %billing_email%, %billing_phone%, %shipping_first_name%, %shipping_last_name%, %shipping_company%, %shipping_address_1%, %shipping_address_2%, %shipping_city%, %shipping_postcode%, %shipping_country%, %shipping_state%, %shipping_method%, %shipping_method_title%, %payment_method%, %payment_method_title%, %order_discount%, %cart_discount%, %order_tax%, %order_shipping%, %order_shipping_tax%, %order_total%, %status%, %prices_include_tax%, %tax_display_cart%, %display_totals_ex_tax%, %display_cart_ex_tax%, %order_date%, %modified_date%, %customer_message%, %customer_note%, %post_status%, %shop_name%, %order_product% and %note%.', 'mimsms-sms-notifications-for-woocommerce' ); ?>"></span>
					</label>
				</th>
				<td class="forminp forminp-number"><textarea id="mimsms_settings[mensaje_pedido]" name="mimsms_settings[mensaje_pedido]" cols="50" rows="5" tabindex="<?php echo $tab++; ?>"><?php echo stripcslashes( !empty( $mensaje_pedido ) ? $mensaje_pedido : sprintf( __( "Order No. %s received on ", 'mimsms-sms-notifications-for-woocommerce' ), "%id%" ) . "%shop_name%" . "." ); ?></textarea>
				</td>
			</tr>
			<tr valign="top" class="mensaje_recibido">
				<th scope="row" class="titledesc">
					<label for="mimsms_settings[mensaje_recibido]">
						<?php _e( 'Order on-hold custom message', 'mimsms-sms-notifications-for-woocommerce' ); ?>:
						<span class="woocommerce-help-tip" data-tip="<?php _e( 'You can customize your message. Remember that you can use this variables: %id%, %order_key%, %billing_first_name%, %billing_last_name%, %billing_company%, %billing_address_1%, %billing_address_2%, %billing_city%, %billing_postcode%, %billing_country%, %billing_state%, %billing_email%, %billing_phone%, %shipping_first_name%, %shipping_last_name%, %shipping_company%, %shipping_address_1%, %shipping_address_2%, %shipping_city%, %shipping_postcode%, %shipping_country%, %shipping_state%, %shipping_method%, %shipping_method_title%, %payment_method%, %payment_method_title%, %order_discount%, %cart_discount%, %order_tax%, %order_shipping%, %order_shipping_tax%, %order_total%, %status%, %prices_include_tax%, %tax_display_cart%, %display_totals_ex_tax%, %display_cart_ex_tax%, %order_date%, %modified_date%, %customer_message%, %customer_note%, %post_status%, %shop_name%, %order_product% and %note%.', 'mimsms-sms-notifications-for-woocommerce' ); ?>"></span>
					</label>
				</th>
				<td class="forminp forminp-number"><textarea id="mimsms_settings[mensaje_recibido]" name="mimsms_settings[mensaje_recibido]" cols="50" rows="5" tabindex="<?php echo $tab++; ?>"><?php echo stripcslashes( !empty( $mensaje_recibido ) ? $mensaje_recibido : sprintf( __( 'Your order No. %s is received on %s. Thank you for shopping with us!', 'mimsms-sms-notifications-for-woocommerce' ), "%id%", "%shop_name%" ) ); ?></textarea>
				</td>
			</tr>
			<tr valign="top" class="mensaje_recibido">
				<th scope="row" class="titledesc">
					<label for="mimsms_settings[temporizador]">
						<?php _e( 'Order on-hold timer', 'mimsms-sms-notifications-for-woocommerce' ); ?>:
						<span class="woocommerce-help-tip" data-tip="<?php _e( 'You can timer this message every X hours. Leave blank to disable.', 'mimsms-sms-notifications-for-woocommerce' ); ?>"/> </th>
				<td class="forminp forminp-number"><input type="text" id="mimsms_settings[temporizador]" name="mimsms_settings[temporizador]" size="50" value="<?php echo ( isset( $mimsms_settings['temporizador'] ) ) ? $mimsms_settings['temporizador'] : ''; ?>" tabindex="<?php echo $tab++; ?>"/>
				</td>
			</tr>
			<tr valign="top" class="mensaje_procesando">
				<th scope="row" class="titledesc">
					<label for="mimsms_settings[mensaje_procesando]">
						<?php _e( 'Order processing custom message', 'mimsms-sms-notifications-for-woocommerce' ); ?>:
						<span class="woocommerce-help-tip" data-tip="<?php _e( 'You can customize your message. Remember that you can use this variables: %id%, %order_key%, %billing_first_name%, %billing_last_name%, %billing_company%, %billing_address_1%, %billing_address_2%, %billing_city%, %billing_postcode%, %billing_country%, %billing_state%, %billing_email%, %billing_phone%, %shipping_first_name%, %shipping_last_name%, %shipping_company%, %shipping_address_1%, %shipping_address_2%, %shipping_city%, %shipping_postcode%, %shipping_country%, %shipping_state%, %shipping_method%, %shipping_method_title%, %payment_method%, %payment_method_title%, %order_discount%, %cart_discount%, %order_tax%, %order_shipping%, %order_shipping_tax%, %order_total%, %status%, %prices_include_tax%, %tax_display_cart%, %display_totals_ex_tax%, %display_cart_ex_tax%, %order_date%, %modified_date%, %customer_message%, %customer_note%, %post_status%, %shop_name%, %order_product% and %note%.', 'mimsms-sms-notifications-for-woocommerce' ); ?>"></span>
					</label>
				</th>
				<td class="forminp forminp-number"><textarea id="mimsms_settings[mensaje_procesando]" name="mimsms_settings[mensaje_procesando]" cols="50" rows="5" tabindex="<?php echo $tab++; ?>"><?php echo stripcslashes( !empty( $mensaje_procesando ) ? $mensaje_procesando : sprintf( __( 'Thank you for shopping with us! Your order No. %s is now: ', 'mimsms-sms-notifications-for-woocommerce' ), "%id%" ) . __( 'Processing', 'mimsms-sms-notifications-for-woocommerce' ) . "." ); ?></textarea>
				</td>
			</tr>
			<tr valign="top" class="mensaje_completado">
				<th scope="row" class="titledesc">
					<label for="mimsms_settings[mensaje_completado]">
						<?php _e( 'Order completed custom message', 'mimsms-sms-notifications-for-woocommerce' ); ?>:
						<span class="woocommerce-help-tip" data-tip="<?php _e( 'You can customize your message. Remember that you can use this variables: %id%, %order_key%, %billing_first_name%, %billing_last_name%, %billing_company%, %billing_address_1%, %billing_address_2%, %billing_city%, %billing_postcode%, %billing_country%, %billing_state%, %billing_email%, %billing_phone%, %shipping_first_name%, %shipping_last_name%, %shipping_company%, %shipping_address_1%, %shipping_address_2%, %shipping_city%, %shipping_postcode%, %shipping_country%, %shipping_state%, %shipping_method%, %shipping_method_title%, %payment_method%, %payment_method_title%, %order_discount%, %cart_discount%, %order_tax%, %order_shipping%, %order_shipping_tax%, %order_total%, %status%, %prices_include_tax%, %tax_display_cart%, %display_totals_ex_tax%, %display_cart_ex_tax%, %order_date%, %modified_date%, %customer_message%, %customer_note%, %post_status%, %shop_name%, %order_product% and %note%.', 'mimsms-sms-notifications-for-woocommerce' ); ?>"></span>
					</label>
				</th>
				<td class="forminp forminp-number"><textarea id="mimsms_settings[mensaje_completado]" name="mimsms_settings[mensaje_completado]" cols="50" rows="5" tabindex="<?php echo $tab++; ?>"><?php echo stripcslashes( !empty( $mensaje_completado ) ? $mensaje_completado : sprintf( __( 'Thank you for shopping with us! Your order No. %s is now: ', 'mimsms-sms-notifications-for-woocommerce' ), "%id%" ) . __( 'Completed', 'mimsms-sms-notifications-for-woocommerce' ) . "." ); ?></textarea>
				</td>
			</tr>
			<tr valign="top" class="mensaje_canceledado">
				<th scope="row" class="titledesc">
					<label for="mimsms_settings[mensaje_canceledado]">
						<?php _e( 'Order cancelled custom message', 'mimsms-sms-notifications-for-woocommerce' ); ?>:
						<span class="woocommerce-help-tip" data-tip="<?php _e( 'You can customize your message. Remember that you can use this variables: %id%, %order_key%, %billing_first_name%, %billing_last_name%, %billing_company%, %billing_address_1%, %billing_address_2%, %billing_city%, %billing_postcode%, %billing_country%, %billing_state%, %billing_email%, %billing_phone%, %shipping_first_name%, %shipping_last_name%, %shipping_company%, %shipping_address_1%, %shipping_address_2%, %shipping_city%, %shipping_postcode%, %shipping_country%, %shipping_state%, %shipping_method%, %shipping_method_title%, %payment_method%, %payment_method_title%, %order_discount%, %cart_discount%, %order_tax%, %order_shipping%, %order_shipping_tax%, %order_total%, %status%, %prices_include_tax%, %tax_display_cart%, %display_totals_ex_tax%, %display_cart_ex_tax%, %order_date%, %modified_date%, %customer_message%, %customer_note%, %post_status%, %shop_name%, %order_product% and %note%.', 'mimsms-sms-notifications-for-woocommerce' ); ?>"></span>
					</label>
				</th>
				<td class="forminp forminp-number"><textarea id="mimsms_settings[mensaje_canceledado]" name="mimsms_settings[mensaje_canceledado]" cols="50" rows="5" tabindex="<?php echo $tab++; ?>"><?php echo stripcslashes( !empty( $mensaje_canceledado ) ? $mensaje_canceledado : sprintf( __( 'Thank you for stay with us! Your order No. %s is now: ', 'mimsms-sms-notifications-for-woocommerce' ), "%id%" ) . __( 'Cancelled', 'mimsms-sms-notifications-for-woocommerce' ) . "." ); ?></textarea>
				</td>
			</tr>
			<tr valign="top" class="mensaje_nota">
				<th scope="row" class="titledesc">
					<label for="mimsms_settings[mensaje_nota]">
						<?php _e( 'Notes custom message', 'mimsms-sms-notifications-for-woocommerce' ); ?>:
						<span class="woocommerce-help-tip" data-tip="<?php _e( 'You can customize your message. Remember that you can use this variables: %id%, %order_key%, %billing_first_name%, %billing_last_name%, %billing_company%, %billing_address_1%, %billing_address_2%, %billing_city%, %billing_postcode%, %billing_country%, %billing_state%, %billing_email%, %billing_phone%, %shipping_first_name%, %shipping_last_name%, %shipping_company%, %shipping_address_1%, %shipping_address_2%, %shipping_city%, %shipping_postcode%, %shipping_country%, %shipping_state%, %shipping_method%, %shipping_method_title%, %payment_method%, %payment_method_title%, %order_discount%, %cart_discount%, %order_tax%, %order_shipping%, %order_shipping_tax%, %order_total%, %status%, %prices_include_tax%, %tax_display_cart%, %display_totals_ex_tax%, %display_cart_ex_tax%, %order_date%, %modified_date%, %customer_message%, %customer_note%, %post_status%, %shop_name%, %order_product% and %note%.', 'mimsms-sms-notifications-for-woocommerce' ); ?>"></span>
					</label>
				</th>
				<td class="forminp forminp-number"><textarea id="mimsms_settings[mensaje_nota]" name="mimsms_settings[mensaje_nota]" cols="50" rows="5" tabindex="<?php echo $tab++; ?>"><?php echo stripcslashes( !empty( $mensaje_nota ) ? $mensaje_nota : sprintf( __( 'A note has just been added to your order No. %s: ', 'mimsms-sms-notifications-for-woocommerce' ), "%id%" ) . "%note%" ); ?></textarea>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row" class="titledesc">
					<label for="mimsms_settings[debug]">
						<?php _e( 'Send debug information?:', 'mimsms-sms-notifications-for-woocommerce' ); ?>
						<span class="woocommerce-help-tip" data-tip="<?php _e( 'Check if you want to receive debug information from your SMS gateway', 'mimsms-sms-notifications-for-woocommerce' ); ?>"></span>
					</label>
				</th>
				<td class="forminp forminp-number"><input id="mimsms_settings[debug]" name="mimsms_settings[debug]" type="checkbox" class="debug" value="1" <?php echo ( isset( $mimsms_settings['debug'] ) && $mimsms_settings['debug'] == "1" ) ? 'checked="checked"' : ''; ?> tabindex="
					<?php echo $tab++; ?>" /></td>
			</tr>
			<tr valign="top" class="campo_debug">
				<th scope="row" class="titledesc">
					<label for="mimsms_settings[campo_debug]">
						<?php _e( 'email address:', 'mimsms-sms-notifications-for-woocommerce' ); ?>
						<span class="woocommerce-help-tip" data-tip="<?php _e( 'Add an email address where you want to receive the debug information', 'mimsms-sms-notifications-for-woocommerce' ); ?>"></span>
					</label>
				</th>
				<td class="forminp forminp-number"><input type="text" id="mimsms_settings[campo_debug]" name="mimsms_settings[campo_debug]" size="50" value="<?php echo ( isset( $mimsms_settings['campo_debug'] ) ) ? $mimsms_settings['campo_debug'] : ''; ?>" tabindex="<?php echo $tab++; ?>"/>
				</td>
			</tr>
		</table>
		<p class="submit">
			<input class="button-primary" type="submit" value="<?php _e( 'Save Changes', 'mimsms-sms-notifications-for-woocommerce' ); ?>" name="submit" id="submit" tabindex="<?php echo $tab++; ?>"/>
		</p>
	</form>
</div>
<script type="text/javascript">
	jQuery( document ).ready( function ( $ ) {
		//Cambia los campos en función del proveedor de servicios SMS
		$( '.servicio' ).on( 'change', function () {
			control( $( this ).val() );
		} );
		var control = function ( capa ) {
			if ( capa == '' ) {
				capa = $( '.servicio option:selected' ).val();
			}
			var proveedores = new Array();
			<?php 
		foreach( $listado_de_proveedores as $indice => $valor ) {
			echo "proveedores['$indice'] = '$valor';" . PHP_EOL;
		}
		?>

			for ( var valor in proveedores ) {
				if ( valor == capa ) {
					$( '.' + capa ).show();
				} else {
					$( '.' + valor ).hide();
				}
			}
		};
		control( $( '.servicio' ).val() );

		//Cambia los campos en función de los mensajes seleccionados
		$( '.mensajes' ).on( 'change', function () {
			control_mensajes( $( this ).val() );
		} );
		var control_mensajes = function ( capa ) {
			if ( capa == '' ) {
				capa = $( '.mensajes option:selected' ).val();
			}

			var mensajes = new Array();
			<?php 
		foreach( $listado_de_mensajes as $indice => $valor ) {
			echo "mensajes['$indice'] = '$valor';" . PHP_EOL; 
		}
		?>

			for ( var valor in mensajes ) {
				$( '.' + valor ).hide();
				for ( var valor_capa in capa ) {
					if ( valor == capa[ valor_capa ] || capa[ valor_capa ] == 'todos' ) {
						$( '.' + valor ).show();
					}
				}
			}
		};

		$( '.mensajes' ).each( function ( i, selected ) {
			control_mensajes( $( selected ).val() );
		} );

		if ( typeof chosen !== 'undefined' && $.isFunction( chosen ) ) {
			jQuery( "select.chosen_select" ).chosen();
		}

		//Controla el campo de teléfono del formulario de envío
		$( '.campo_envio' ).hide();
		$( '.envio' ).on( 'change', function () {
			control_envio( '.envio' );
		} );
		var control_envio = function ( capa ) {
			if ( $( capa ).is( ':checked' ) ) {
				$( '.campo_envio' ).show();
			} else {
				$( '.campo_envio' ).hide();
			}
		};
		control_envio( '.envio' );

		//Controla el campo de correo electrónico del formulario de envío
		$( '.campo_debug' ).hide();
		$( '.debug' ).on( 'change', function () {
			control_debug( '.debug' );
		} );
		var control_debug = function ( capa ) {
			if ( $( capa ).is( ':checked' ) ) {
				$( '.campo_debug' ).show();
			} else {
				$( '.campo_debug' ).hide();
			}
		};
		control_debug( '.debug' );

		<?php if ( !empty( $listado_de_estados ) ) : //Comprueba la existencia de estados personalizados ?>
		//Cambia los campos en función de los estados personalizados seleccionados
		$( '.estados_personalizados' ).on( 'change', function () {
			control_personalizados( $( this ).val() );
		} );
		var control_personalizados = function ( capa ) {
			var estados = new Array();
			<?php 
		foreach( $listado_de_estados as $valor ) {
			echo "estados['$valor'] = '$valor';" . PHP_EOL; 
		}
		?>

			for ( var valor in estados ) {
				$( '.' + valor ).hide();
				for ( var valor_capa in capa ) {
					if ( valor == capa[ valor_capa ] ) {
						$( '.' + valor ).show();
					}
				}
			}
		};

		$( '.estados_personalizados' ).each( function ( i, selected ) {
			control_personalizados( $( selected ).val() );
		} );
		<?php endif; ?>
	} );
</script>