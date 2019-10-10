<?php
/**
 * Plugin card template wp-rocket
 */
	$helper->set_title( 'WP Rocket' );
	$helper->set_icon( '<img src="' . HBC_PLUGIN_URL . 'assets/img/logo-rocket.jpg" alt="">' );
	$helper->set_description(
		sprintf(
			__( 'Integrate more than 80&#x25; of web performance good practices automatically to %1$sreduce your website\'s loading time.%2$s', 'heartbeat-control' ),
			'<strong>',
			'</strong>'
		)
	);
	$helper->set_button_text(
		array(
			'activated'     => esc_html__( 'Already activated', 'heartbeat-control' ),
			'installed'     => esc_html__( 'Activate WP Rocket', 'heartbeat-control' ),
			'not_installed' => esc_html__( 'get WP Rocket', 'heartbeat-control' ),
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
	<?php if ( 'activated' === $helper->get_status() ) : ?>
		<span class="wrapper-infos-active">
			<span class="dashicons dashicons-yes"></span>
			<span class="info-active">
				<?php echo $helper->get_button_text(); ?>
			</span>
		</span>
	<?php elseif ( 'installed' === $helper->get_status() ) : ?>
		<a class="link-btn button-primary referer-link <?php echo esc_attr( $helper->get_status() ); ?>" href="<?php echo $helper->get_install_url(); ?>">
			<?php echo $helper->get_button_text(); ?>
		</a>
	<?php else : ?>
		<a class="link-btn button-primary <?php echo esc_attr( $helper->get_status() ); ?>" href="https://wp-rocket.me/?utm_source=wp_plugin&utm_medium=rocket_heartbeat">
			<?php echo $helper->get_button_text(); ?>
		</a>
	<?php endif; ?>
</div>
