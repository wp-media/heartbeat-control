<?php
/**
 * Admin Page view
 */

defined('ABSPATH') || die('Cheatin\' uh?');
global $wp_version;
$heading_tag = version_compare( $wp_version, '4.3' ) >= 0 ? 'h1' : 'h2';
$notices->echoNotices( true, true );
?>
<div class="wrap">
	<div class="heartbeat-control-settings">
		<<?php echo $heading_tag; ?> class="screen-reader-text"><?php echo esc_html( get_admin_page_title() ); ?></<?php echo $heading_tag; ?>>
		<div class="header">
		    <div class="header-left">
		        <div class="visuel">
		            <img src="<?php echo $asset_image_url.'logo-heartbeat.svg' ?>" alt="">
		        </div>
		    </div>
		    <div class="header-right">
		        <div class="txt-1"><?php esc_html_e( 'Do you like this plugin ?', 'heartbeat-control' ); ?></div>
		        <div class="txt-2">
					<?php
						printf(
							__( 'Please, take a few seconds to %1$srate it on WordPress.org%2$s', 'heartbeat-control' ),
							'<a href="https://wordpress.org/support/plugin/heartbeat-control/reviews/?filter=5"><b>',
							'</b></a>'
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
				<a href="#tab1" class="nav-tab nav-tab-active"><?php esc_html_e( 'General settings', 'heartbeat-control' ); ?></a>
				<a href="#tab2" class="nav-tab"><?php esc_html_e( 'More optimization', 'heartbeat-control' ); ?></a>
				<a href="#tab3" class="nav-tab"><?php esc_html_e( 'About us', 'heartbeat-control' ); ?></a>
			</h2>		
		</div>
		<div id="tab1" class="tab tab-active"><?php  echo $cmb_form; ?></div>
		<div id="tab2" class="tab">
		    <div class="wrapper-content wrapper-intro">
		        <div class="wrapper-left">
		            <div class="wrapper-img">
		                <img src="<?php echo $asset_image_url.'logo-wprocket.svg'; ?>" alt="">
		            </div>
		            <div class="wrapper-txt">
		                <p>
							<?php
								printf(
									__( 'Looking for more optimization? %1$sThen you should use %2$sWP Rocket%3$s, and your site will be cached and optimized without you lifting a finger!', 'heartbeat-control' ),
									'<br>', '<b>', '</b>'
								);
							?>
		            </div>
		            <a href="https://wp-rocket.me/?utm_source=wp_plugin&utm_medium=rocket_heartbeat" class="btn" target="_blank">
						<?php esc_html_e( 'Get wp rocket', 'heartbeat-control' ); ?>
		            </a>
		            <div class="wrapper-img"></div>
		        </div>
		        <div class="wrapper-right">
		            <div class="wrapper-right-img"></div>
		        </div>
		    </div>
		    <div class="wrapper-content wrapper-numbers">
		        <div class="top-part">
					<?php
						echo printf(
							__( 'Recognized as the %1$smost powerful caching plugin%2$s by WordPress experts', 'heartbeat-control' ),
							'<b>', '</b>'
						);
					?>
				</div>
		        <div class="bottom-part">
		            <ul>
		                <li>
		                    <div class="visuel">80%</div>
		                    <div class="txt">
								<?php
									printf(
										__( 'Automatically apply more than %1$s80 %%2$s of web performance best practices', 'heartbeat-control' ),
										'<b>', '</b>'
									);
								?>
							</div>
		                </li>
		                <li>
		                    <div class="visuel">
		                        <img src="<?php echo $asset_image_url.'noun_performance_1221123.png'; ?>" alt="">
		                    </div>
		                    <div class="txt">
								<?php
									printf(
										__( 'Help improve your %1$sGoogle PageSpeed%2$s score', 'heartbeat-control' ),
										'<b>', '</b>'
									);
								?>
							</div>
		                </li>
		                <li>
		                    <div class="visuel">
		                        <img src="https://wp-media-plugins.whostaging.fr/wp-content/plugins/heartbeat-control/assets/img/noun_SEO_737036.png" alt="">
		                    </div>
		                    <div class="txt">
								<?php
									printf(
										__( '%1$sBoost your SEO%2$s by preloading your pages and make them faster for Google\'s bots', 'heartbeat-control' ),
										'<b>', '</b>'
									);
								?>
							</div>
		                </li>
		                <li>
		                    <div class="visuel">
		                        <img src="<?php echo $asset_image_url.'noun_revenue_949180.png'; ?>" alt="">
		                    </div>
		                    <div class="txt">
								<?php
									printf(
										__( 'Improve %1$sconversions and revenue%2$s thanks to a stunning web performance', 'heartbeat-control' ),
										'<b>', '</b>'
									);
								?>
							</div>
		                </li>
		                
		            </ul>
		        </div>
		    </div>
		    <div class="wrapper-content wrapper-video">
		        <div class="wrapper-iframe">
		            <script src="https://fast.wistia.com/embed/medias/s3jveyzr5h.json" async></script>
					<script src="https://fast.wistia.com/assets/external/E-v1.js" async></script>
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
						echo printf(
							__( 'Forget complicated settings and headaches, and %1$senjoy the fastest speed results%2$s your site has ever had!', 'heartbeat-control' ),
							'<b>', '</b>'
						);
					?>
		        </div>
		        <div class="contact-btn">
		            <a href="https://wp-rocket.me/?utm_source=wp_plugin&utm_medium=rocket_heartbeat" class=" btn" target="_blank"><?php esc_html_e( 'Get Wp Rocket', 'heartbeat-control' ); ?></a>
		        </div>
		    </div>
		</div>
		<div id="tab3" class="tab">
		    <div class="wrapper-top wrapper-info">
		        <div class="top-img">
		            <img src="<?php echo $asset_image_url.'team.jpg'; ?>" alt="">
		        </div>
		        <div class="top-txt">
		            <h2><?php esc_html_e( 'Welcome to WP Media!', 'heartbeat-control' ); ?></h2>
		            <p><?php esc_html_e( 'Founded in 2014 in beautiful Lyon (France), WP Media is now a distributed company of more than 20 WordPress lovers living in the four corners of the world.', 'heartbeat-control' ); ?></p>
					<p><?php esc_html_e( 'We develop plugins that make the web a better place - faster, lighter, and easier to use.', 'heartbeat-control' ); ?></p>
					<p><?php esc_html_e( 'Check out our other plugins : we built them all to give a boost to the performance of your website!', 'heartbeat-control' ); ?></p>
		        </div>
		    </div>
		    <div class="wrapper-bottom wrapper-link">
				<?php $plugins_block['wp-rocket']->helper(); ?>
				<?php $plugins_block['imagify']->helper(); ?>
				<?php $plugins_block['lazy']->helper(); ?>
			</div>
		</div>
	</div>
</div>
