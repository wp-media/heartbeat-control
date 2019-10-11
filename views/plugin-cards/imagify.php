<?php
/**
 * Plugin card template imagify
 */

$status = $template_args['imagify_partner']::is_imagify_installed() ? ( $template_args['imagify_partner']::is_imagify_activated() ? 'activated' : 'installed' ) : 'not_installed';
$helper->set_title( 'Imagify' );
$helper->set_button_text(
	array(
		'activated'     => esc_html__( 'Already activated', 'heartbeat-control' ),
		'installed'     => esc_html__( 'Activate Imagify', 'heartbeat-control' ),
		'not_installed' => esc_html__( 'Install Imagify', 'heartbeat-control' ),
	)
);
$helper->set_description(
	sprintf(
		// translators: %1$s: line break, %2$s %3$s: bold markup.
		__( '%2$sReduces image file sizes%3$s without loosing quality. %1$sBy compressing your images our speed up your website and boost your SEO.', 'heartbeat-control' ),
		'<br>',
		'<strong>',
		'</strong>'
	)
);
?>
<div class="card single-link imagify">
	<div class="link-infos">
		<div class="link-infos-logo"></div>
		<span class="link-infos-txt">
			<h3><?php echo $helper->get_title(); ?></h3>
			<p>
			<?php
			printf(
				// translators: %1$s: status (not installed, installed or activated).
				__( 'Status : %1$s', 'heartbeat-control' ),
				$helper->get_status_text()
			);
			?>
			</p>
		</span>
	</div>
	<div class="link-content"><?php echo $helper->get_description(); ?></div>
<?php if ( 'activated' === $status ) : ?>
	<span class="wrapper-infos-active"><span class="dashicons dashicons-yes"></span><span class="info-active"><?php echo $helper->get_button_text( $status ); ?></span></span>
<?php else : ?>
	<a class="link-btn button-primary referer-link <?php echo esc_attr( $status ); ?>" href="<?php echo esc_url( $template_args['imagify_partner']->get_post_install_url() ); ?>">
		<?php echo $helper->get_button_text( $status ); ?>
	</a>
<?php endif; ?>
</div>
