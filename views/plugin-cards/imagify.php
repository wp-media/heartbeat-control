<?php
/**
 * Plugin card template imagify
 */
$status = $template_args['imagify_partner']::is_imagify_installed()?( $template_args['imagify_partner']::is_imagify_activated()?'actived':'installed' ):'not_installed';
$helper->set_button_text( array(
	'actived' => esc_html__( 'Already activated', 'heartbeat-control' ),
	'installed' =>  esc_html__( 'Activate Imagify', 'heartbeat-control' ),
	'not_installed' => esc_html__( 'Install Imagify', 'heartbeat-control' ),
) );
?>
<div class="card single-link imagify">
	<div class="link-infos">
		<div class="link-infos-logo"></div>
		<div class="link-infos-txt">
			<h3><?php esc_html_e( 'Imagify', 'heartbeat-control' ); ?></h3>
			<p><?php printf( __( 'Status : %1$s', 'heartbeat-control' ), $helper->get_status_text( $status ) ); ?></p>
		</div>
	</div>
	<div class="link-content">
		<?php echo sprintf(
				__( '%1$sReduces image file sizes%2$s  without loosing quality. By compressing your images our speed up your website and boost your SEO.', 'heartbeat-control' ),
				'<strong>', '</strong>'
			);
		?>
	</div>
<?php if( 'actived' === $status ): ?>
	<span class="wrapper-infos-active"><span class="dashicons dashicons-yes"></span><span class="info-active"><?php echo $helper->get_button_text( $status ); ?></span></span>
<?php else: ?>
	<a class="link-btn button-primary <?php echo esc_attr( $status ); ?>" href="<?php echo esc_url( $template_args['imagify_partner']->get_post_install_url() ); ?>">
		<?php echo $helper->get_button_text( $status ); ?>
	</a>
<?php endif; ?>
</div>
