<?php
/**
 * Plugin card template wp-rocket
 */
	$helper->set_title('WP Rocket');
	$helper->set_icon('<img src="'.HBC_PLUGIN_URL.'assets/img/logo-rocket.jpg" alt="">');
	$helper->set_description(
		sprintf(
			__( 'Integrate more than 80&#x25; of web performance good practices automatically to %1$sreduce your website\'s loading time.%2$s', 'heartbeat-control' ),
			'<strong>', '</strong>'
		)
	);
?>
<div class="card single-link wp-rocket">
	<div class="link-infos">
		<div class="link-infos-logo"><?php echo $helper->get_icon(); ?></div>
		<span class="link-infos-txt">
			<h3><?php echo $helper->get_title(); ?></h3>
			<p><?php printf( __( 'Status : %1$s', 'heartbeat-control' ), $helper->get_status_text() ); ?></p>
		</span>
	</div>
	<div class="link-content"><?php echo $helper->get_description(); ?></div>
	<?php if( 'actived' === $helper->get_status() ): ?>
		<span class="wrapper-infos-active"><span class="dashicons dashicons-yes"></span><span class="info-active"><?php echo $helper->get_button_text(); ?></span></span>
	<?php else: ?>
		<a class="link-btn button-primary <?php echo esc_attr( $helper->get_status() ); ?>" href="https://wp-rocket.me/?utm_source=wp_plugin&utm_medium=rocket_heartbeat"><?php _e( 'get WP Rocket', 'heartbeat-control' ); ?></a>
	<?php endif; ?>
</div>