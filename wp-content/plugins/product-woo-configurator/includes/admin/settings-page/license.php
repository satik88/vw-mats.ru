<?php

	$license = get_option( 'pwc_license_key' );
	$status  = get_option( 'pwc_license_status' );

	$license_hide = '';
	if( $license ) {
		$license_hide = substr($license, 0, -24) . str_repeat("*", 24);
	}

	?>
	<div class="wrap">
		<h3><?php _e('Active license for automatic update', 'product-woo-configurator'); ?></h3>
		<form method="post" action="options.php">

			<?php settings_fields('pwc_license'); ?>

			<table class="form-table">
				<tbody>
					<tr valign="top">
						<th scope="row" valign="top">
							<?php _e('License Key'); ?>
						</th>
						<td>
							<input id="pwc_license_key" name="pwc_license_key" type="text" class="regular-text" value="<?php esc_attr_e( $license_hide ); ?>" />
							<label class="description" for="pwc_license_key"><?php _e('Enter your license key', 'product-woo-configurator'); ?></label>
						</td>
					</tr>
					<?php // if( false !== $license ) { ?>
						<tr valign="top">
							<th scope="row" valign="top">
								<?php _e('Activate License', 'product-woo-configurator'); ?>
							</th>
							<td>
								<?php if( $status !== false && $status == 'valid' ) { ?>
									<span class="license-active-status"><?php _e('Active', 'product-woo-configurator'); ?></span>
									<?php wp_nonce_field( 'pwc_nonce', 'pwc_nonce' ); ?>
									<input type="submit" class="button-secondary" name="pwc_license_deactivate" value="<?php _e('Deactivate License'); ?>"/>
								<?php } else {
									wp_nonce_field( 'pwc_nonce', 'pwc_nonce' ); ?>
									<input type="submit" class="button-secondary" name="pwc_license_activate" value="<?php _e('Activate License'); ?>"/>
								<?php } ?>
							</td>
						</tr>
					<?php //} ?>
				</tbody>
			</table>

		</form>

		<h3><?php esc_html_e( 'Buy License', 'panorama' ); ?></h3>
		<?php if( $status !== false && $status == 'valid' ) : ?>
			<p><?php esc_html_e( 'If you need another license, Please purchase the license through below this link.', 'panorama' ); ?></p>
		<?php else: ?>
			<p><?php esc_html_e( 'Please purchase the license through below this link.', 'panorama' ); ?></p>
		<?php endif; ?>
		<a href="https://luminesthemes.com/items/configurator-plugin/" target="_blank" class="button-primary"><?php esc_html_e( 'Buy Product', 'panorama' ); ?></a>
	</div>
