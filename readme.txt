=== Heartbeat Control ===
Contributors: JeffMatson
Donate link: http://jeffmatson.net/donate
Tags: heartbeat, admin-ajax, server resources
Requires at least: 2.8
Tested up to: 4.3
Stable tag: 1.0.3
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Allows you to easily manage the frequency of the WordPress heartbeat API.

== Description ==

Allows you to easily manage the frequency of the WordPress heartbeat API with just a few dropdowns.

The heartbeat API can be disabled entirely and allowed for only specific dashboard locations.

Heartbeat intervals can be modified between 15 and 60 seconds between requests, saving on server resources.

== Installation ==

1.  Upload the plugin folder to the /wp-content/plugins/ directory.
1.  Activate Heartbeat Control on the Plugins page.
1.  Adjust any settings within Settings > Heartbeat Control.

== Frequently Asked Questions ==

= Dow do I change my settings? =

All options are located within Tools > Heartbeat Control.

= Why would I want to change the default heartbeat intervals =

If you commonly leave your WordPress admin up for long periods of time, especially while writing or editing a post, the repeated POST requests can cause high resource usage.  To avoid this, the heartbeat can be modified or even disabled to lower your server resource usage.

== Changelog ==
= 1.0.2 =
* Bumped tested version
* Added donation button

= 1.0 =
*   Initial release.
