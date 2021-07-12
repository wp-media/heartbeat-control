=== Heartbeat Control ===
Contributors: wp_rocket, wp_media
Tags: heartbeat, admin-ajax, server resources, heartbeat control, heartbeat api, performance, debugging, javascript
Requires at least: 3.6
Requires PHP: 5.3
Tested up to: 5.8
Stable tag: 2.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Allows you to easily manage the frequency of the WordPress heartbeat API.

== Description ==

Heartbeat Control by WP Rocket allows you to manage the frequency of the WordPress heartbeat API in a few clicks.

The WordPress Heartbeat API is a great feature that provides real-time communication between the server and the browser when you are logged into your WordPress admin panel. It uses the file /wp-admin/admin-ajax.php to run AJAX calls from the browser. By default, AJAX requests are sent every 15 seconds on post edit pages, and every 60 seconds on the dashboard.

This is indeed helpful; but if you usually leave your WordPress admin open for long periods (for example when you write or edit posts), the AJAX requests from the API can pile up and generate high CPU usage, leading to server performance issues and even hosting account suspensions.

With Heartbeat Control by WP Rocket, you can easily choose to limit or completely stop the activity of the WordPress Heartbeat API. You can also add rules for specific locations only (Dashboard, Frontend or Post Editor).

To learn more about WordPress performance optimization and make your website faster, join our [WP Rocket Facebook Community](https://www.facebook.com/groups/WPRocketUsers/)!

= Related Plugins =

* [WP Rocket](https://wp-rocket.me/?utm_source=wordpress.org&utm_medium=referral&utm_campaign=HeartBeatPlugin): Best caching plugin to speed-up your WordPress website.
* [Lazy Load by WP Rocket](https://wordpress.org/plugins/rocket-lazy-load/): Best Lazy Load script to reduce the number of HTTP requests and improves the websites loading time.
* [Imagify](https://wordpress.org/plugins/imagify/): Best Image Optimizer to speed up your website with lighter images.

== Installation ==

1.  Upload the plugin folder to the /wp-content/plugins/ directory.
1.  Activate Heartbeat Control on the Plugins page.
1.  Adjust any settings within Settings > Heartbeat Control.

== Screenshots ==

1. Heartbeat Settings
2. Modify Heartbeat frequency precisely

== Frequently Asked Questions ==

= How do I change my settings? =

All options are located within Settings > Heartbeat Control.

= Why would I want to change the default heartbeat intervals? =

If you commonly leave your WordPress admin up for long periods of time, especially while writing or editing a post, the repeated POST requests can cause high resource usage.  To avoid this, the heartbeat can be modified or even disabled to lower your server resource usage.

== Changelog ==
= 2.0 - 2019/11/04 =
* Revamp the settings page.

= 1.2.5 =
* Fixed issue caused by previous version deployment.
* Added hbc_disable_notice hook to force dismissal of update notices.
* Additional documentation added.
* Minor standards adjustments.

= 1.2.4 =
* Updated CMB2 to 2.4.2.
* Bumpted "tested up to" version.
* Fixed a bug that occurred if no locations were selected.
* Minor standards adjustments.


= 1.2.3 =
* Added composer.json and composer.lock that were missing.
* Updated CMB2 to 2.3
* Translation files generated.
* Language path and text domain added to plugin header.
* Bumped compatible WP version.

= 1.2.2 =
* Minor bugfixes.

= 1.2.1 =
* Fixed issue that would cause some users to not see the modification slider.

= 1.2 =
* Added conditional logic.
* Multiple actions can now be performed.
* Scripts are bundled and minified.
* Changes to settings structure.
* Miscellaneous bugfixes.

= 1.1.3 =
* Readme updates.

= 1.1.2 =
* Bugfixes.
* Resolves potential fatal error mistakenly pushed to 1.1.

= 1.1.1 =
* Bugfixes.

= 1.1 =
* Rewritten from the ground up for future extensibility.
* Performance enhancements.
* Improved UI.
* Better handling for late calls to the Heartbeat API.
* New condition settings for filtering on the frontend.

= 1.0.2 =
* Bumped tested version
* Added donation button

= 1.0 =
*   Initial release.
