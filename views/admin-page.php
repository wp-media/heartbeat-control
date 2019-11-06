<?php
/**
 * Admin Page view
 */

defined( 'ABSPATH' ) || die( 'Cheatin\' uh?' );

global $wp_version;
$heading_tag = version_compare( $wp_version, '4.3' ) >= 0 ? 'h1' : 'h2';
$notices->echo_notices();
?>
<div class="wrap">
	<<?php echo $heading_tag; // phpcs:ignore WordPress.Security.EscapeOutput ?> class="screen-reader-text"><?php echo esc_html( get_admin_page_title() ); ?></<?php echo $heading_tag; // phpcs:ignore WordPress.Security.EscapeOutput ?>>
	<div class="wrapper-settings">
		<div class="header">
			<div class="header-left">
				<div class="visuel">
					<img src="<?php echo esc_url( $asset_image_url . 'logo-heartbeat.svg' ); ?>" alt="">
				</div>
			</div>
			<div class="header-right">
				<div class="txt-1"><?php esc_html_e( 'Do you like this plugin ?', 'heartbeat-control' ); ?></div>
				<div class="txt-2">
					<?php
					printf(
						// translators: %1$s %2$s: link markup.
						esc_html__( 'Please, take a few seconds to %1$srate it on WordPress.org%2$s', 'heartbeat-control' ),
						'<a href="https://wordpress.org/support/plugin/heartbeat-control/reviews/?filter=5"><strong>',
						'</strong></a>'
					);
					?>
				</div>
				<div class="txt-3">
					<a href="https://wordpress.org/support/plugin/heartbeat-control/reviews/?filter=5">
						<span class="dashicons dashicons-star-filled"></span>
						<span class="dashicons dashicons-star-filled"></span>
						<span class="dashicons dashicons-star-filled"></span>
						<span class="dashicons dashicons-star-filled"></span>
						<span class="dashicons dashicons-star-filled"></span>
					</a>
				</div>
			</div>
		</div>
		<div class="wrapper-nav">
			<h2 class="nav-tab-wrapper">
				<span class="nav-tab nav-tab-active" data-tab="general-settings"><?php esc_html_e( 'General settings', 'heartbeat-control' ); ?></span>
<?php if ( ! $plugins_block['wp-rocket']->is_activated() ) : ?>
				<span class="nav-tab" data-tab="more-optimization"><?php esc_html_e( 'More optimization', 'heartbeat-control' ); ?></span>
<?php endif; ?>
				<span class="nav-tab" data-tab="about-us" ><?php esc_html_e( 'About us', 'heartbeat-control' ); ?></span>
			</h2>
		</div>
		<div id="tab_general-settings" class="tab tab-active"><?php echo $cmb_form; // phpcs:ignore WordPress.Security.EscapeOutput ?></div>
<?php if ( ! $plugins_block['wp-rocket']->is_activated() ) : ?>
		<div id="tab_more-optimization" class="tab">
			<div class="wrapper-content wrapper-intro">
				<div class="wrapper-left">
					<div class="wrapper-img">
						<img src="<?php echo esc_url( $asset_image_url . 'logo-wprocket.svg' ); ?>" alt="">
					</div>
					<div class="wrapper-txt">
						<p>
							<?php
							printf(
								// translators: %1$s: line break, %2$s %3$s: bold markup.
								esc_html__( 'Looking for more optimization?%1$sThen you should use %2$sWP Rocket%3$s, and your site will be cached and optimized without you lifting a finger!', 'heartbeat-control' ),
								'<br>',
								'<strong>',
								'</strong>'
							);
							?>
						</p>
					</div>
					<?php if ( 'installed' === $plugins_block['wp-rocket']->get_status() ) : ?>
					<a class="btn referer-link <?php echo esc_attr( $plugins_block['wp-rocket']->get_status() ); ?>" href="<?php echo esc_url( $plugins_block['wp-rocket']->get_install_url() ); ?>">
						<?php esc_html_e( 'Activate Now', 'heartbeat-control' ); ?>
					</a>
					<?php else : ?>
					<a href="https://wp-rocket.me/?utm_source=wp_plugin&utm_medium=heartbeat_control" class="btn" target="_blank" rel="noopener">
						<?php esc_html_e( 'Get WP Rocket', 'heartbeat-control' ); ?>
					</a>
					<?php endif; ?>
					<div class="wrapper-img"></div>
				</div>
				<div class="wrapper-right">
					<div class="wrapper-right-img"></div>
				</div>
			</div>
			<div class="wrapper-content wrapper-numbers">
				<div class="top-part">
					<?php
					printf(
						// translators: %1$s %2$s: bold markup.
						esc_html__( 'Recognized as the %1$smost powerful caching plugin%2$s by WordPress experts', 'heartbeat-control' ),
						'<strong>',
						'</strong>'
					);
					?>
				</div>
				<div class="bottom-part">
					<ul>
						<li>
							<div class="visuel visuel-chiffre"></div>
							<div class="txt">
								<?php
									printf(
										// translators: %1$s %2$s: bold markup.
										esc_html__( 'Automatically apply more than %1$s80&#x25;%2$s of web performance best practices', 'heartbeat-control' ),
										'<strong>',
										'</strong>'
									);
								?>
							</div>
						</li>
						<li>
							<div class="visuel">
								<img src="<?php echo esc_url( $asset_image_url . 'noun_performance_1221123.svg' ); ?>" alt="">
							</div>
							<div class="txt">
								<?php
								printf(
									// translators: %1$s %2$s: bold markup.
									esc_html__( 'Help improve your %1$sGoogle PageSpeed%2$s score', 'heartbeat-control' ),
									'<strong>',
									'</strong>'
								);
								?>
							</div>
						</li>
						<li>
							<div class="visuel">
								<img src="<?php echo esc_url( $asset_image_url . 'noun_SEO_737036.svg' ); ?>" alt="">
							</div>
							<div class="txt">
								<?php
								printf(
									// translators: %1$s %2$s: bold markup.
									esc_html__( '%1$sBoost your SEO%2$s by preloading your pages and make them faster for Google\'s bots', 'heartbeat-control' ),
									'<strong>',
									'</strong>'
								);
								?>
							</div>
						</li>
						<li>
							<div class="visuel">
								<img src="<?php echo esc_url( $asset_image_url . 'noun_revenue_949180.svg' ); ?>" alt="">
							</div>
							<div class="txt">
								<?php
									printf(
										// translators: %1$s %2$s: bold markup.
										esc_html__( 'Improve %1$sconversions and revenue%2$s thanks to a stunning web performance', 'heartbeat-control' ),
										'<strong>',
										'</strong>'
									);
								?>
							</div>
						</li>
					</ul>
				</div>
			</div>
			<div class="wrapper-content wrapper-video">
				<div class="wrapper-iframe">
					<script src="https://fast.wistia.com/embed/medias/s3jveyzr5h.json" async></script> <?php //phpcs:ignore WordPress.WP.EnqueuedResources ?>
					<script src="https://fast.wistia.com/assets/external/E-v1.js" async></script> <?php //phpcs:ignore WordPress.WP.EnqueuedResources ?>
					<div class="wistia_responsive_padding" style="padding:56.25% 0 0 0;position:relative;">
						<div class="wistia_responsive_wrapper" style="height:100%;left:0;position:absolute;top:0;width:100%;">
							<div class="wistia_embed wistia_async_s3jveyzr5h videoFoam=true" style="height:100%;position:relative;width:100%">
								<div class="wistia_swatch" style="height:100%;left:0;opacity:0;overflow:hidden;position:absolute;top:0;transition:opacity 200ms;width:100%;">
									<img src="https://fast.wistia.com/embed/medias/s3jveyzr5h/swatch"
										style="filter:blur(5px);height:100%;object-fit:contain;width:100%;" alt=""
										aria-hidden="true"
										onload="this.parentNode.style.opacity=1;"
									/>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="wrapper-content wrapper-contact">
				<div class="txt">
					<?php
						printf(
							// translators: %1$s %2$s: bold markup.
							esc_html__( 'Forget complicated settings and headaches, and %1$senjoy the fastest speed results%2$s your site has ever had!', 'heartbeat-control' ),
							'<strong>',
							'</strong>'
						);
					?>
				</div>
				<div class="contact-btn">
	<?php if ( 'installed' === $plugins_block['wp-rocket']->get_status() ) : ?>
					<a class="btn referer-link <?php echo esc_attr( $plugins_block['wp-rocket']->get_status() ); ?>" href="<?php echo esc_url( $plugins_block['wp-rocket']->get_install_url() ); ?>">
						<?php esc_html_e( 'Activate Now', 'heartbeat-control' ); ?>
					</a>
	<?php else : ?>
					<a href="https://wp-rocket.me/?utm_source=wp_plugin&utm_medium=heartbeat_control" class="btn" target="_blank" rel="noopener">
						<?php esc_html_e( 'Get WP Rocket', 'heartbeat-control' ); ?>
					</a>
	<?php endif; ?>
				</div>
			</div>
		</div>
<?php endif; ?>
		<div id="tab_about-us" class="tab">
			<div class="wrapper-top wrapper-info">
				<div class="top-img">
					<img src="<?php echo esc_url( $asset_image_url . 'team.jpg' ); ?>" alt="">
				</div>
				<div class="top-txt">
					<h2><?php esc_html_e( 'Welcome to WP Media!', 'heartbeat-control' ); ?></h2>
					<p><?php esc_html_e( 'Founded in 2014 in beautiful Lyon (France), WP Media is now a distributed company of more than 20 WordPress lovers living in the four corners of the world.', 'heartbeat-control' ); ?></p>
					<p><?php esc_html_e( 'We develop plugins that make the web a better place - faster, lighter, and easier to use.', 'heartbeat-control' ); ?></p>
					<p><?php esc_html_e( 'Check out our other plugins: we built them all to give a boost to the performance of your website!', 'heartbeat-control' ); ?></p>
				</div>
			</div>
			<div class="wrapper-bottom wrapper-link">
				<?php $plugins_block['wp-rocket']->helper(); ?>
				<?php $plugins_block['imagify']->helper(); ?>
				<?php $plugins_block['rocket-lazy-load']->helper(); ?>
			</div>
		</div>
	</div>
</div>
