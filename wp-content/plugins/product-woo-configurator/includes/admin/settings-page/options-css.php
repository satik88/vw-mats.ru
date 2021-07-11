<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$settings = wp_enqueue_code_editor(
	array(
		'type'       => 'text/css',
		'codemirror' => array(
			'indentUnit' => 2,
			'tabSize'    => 4
		),
	)
);

if( isset( $_POST['pwc_update_css'] ) ) {
	update_option( 'pwc_custom_css', stripcslashes( $_POST['pwc_custom_css'] ) );
}

$custom_css = get_option( 'pwc_custom_css' );

?>

<div class="wrap">
	<h3><?php esc_html_e( 'Custom CSS', 'product-woo-configurator' ); ?></h3>
	<form method="post">

		<table class="form-table">
			<tbody>
				<tr>
					<th>
						<?php esc_html_e( 'Enter CSS', 'product-woo-configurator' ); ?>
					</th>
					<td>
						<textarea name="pwc_custom_css" id="css_editor" class="code" style="display: none;"><?php echo $custom_css; ?></textarea>
					</td>
				</tr>
				<tr>
					<td>
						<input name="pwc_update_css" type="submit" value="<?php esc_attr_e( 'Save', 'product-woo-configurator' ); ?>" class="button-primary submit-btn"/>
					</td>
				</tr>
			</tbody>
		</table>

	</form>
</div>