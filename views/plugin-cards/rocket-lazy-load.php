<?php
/**
 * Plugin card template for lazyload
 */

?>
<div class="card single-link">
	<div class="link-infos">
		<div class="link-infos-logo"><?php echo $helper->get_icon(); ?></div>
		<span class="link-infos-txt">
			<h3><?php esc_html_e( 'Lazyload', 'heartbeat-control' ); ?></h3>
			<p><?php printf( __( 'Status : %1$s', 'heartbeat-control' ), $helper->get_status_text() ); ?></p>
		</span>
	</div>
	<div class="link-content"><?php echo $helper->get_description(); ?></div>
	<?php if( 'actived' === $helper->get_status() ): ?>
		<span class="wrapper-infos-active"><span class="dashicons dashicons-yes"></span><span class="info-active"><?php echo $helper->get_button_text(); ?></span></span>
	<?php else: ?>
		<a class="link-btn button-primary <?php echo esc_attr( $helper->get_status() ); ?>" href="<?php echo $helper->get_install_url(); ?>"><?php echo $helper->get_button_text(); ?></a>
	<?php endif; ?>
</div>
