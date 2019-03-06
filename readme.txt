=== oik-loader ===
Contributors: bobbingwide, vsgloik
Donate link: https://www.oik-plugins.com/oik/oik-donate/
Tags: oik, plugin, loader
Requires at least: 5.1
Tested up to: 5.1
Stable tag: 0.0.0

WordPress Must Use plugin to load required plugins.

== Description ==




== Installation ==
1. Upload the contents of the oik-loader plugin to the `/wp-content/plugins/oik-loader' directory
1. Activate the oik-loader plugin through the 'Plugins' menu in WordPress
1. Generate the oik-loader.csv file in the mu-plugins folder

Note: The includes/oik-loader-mu.php file is automatically copied to the mu-plugins folder



== Frequently Asked Questions ==

= What is this plugin for? =
It helps to reduce the number of activated plugins in blocks.wp-a2z.org
It dynamically loads the required plugin for a block.

= Which plugins can I deactivate? =
Once all the blocks for a plugin have been generated then the plugin can be deactivated.

With the following exceptions:
- oik-blocks - since these blocks are used in all content
- plugins upon which oik-blocks depends for server side rendering
- any other plugins that deliver blocks that are used throughout the site

The site should operate with/without Gutenberg being activated.


== Screenshots ==
1. None

== Upgrade Notice ==
= 0.0.0 =
Prototype version developed with oik-magnetic-poetry.
oik-blocks needs to be activated since these blocks are used by the block CPT.

== Changelog ==
= 0.0.0 =
* Added: oik-loader.php - the main plugin file
* Added: includes/oik-loader-mu.php to automatically load the required plugin
* Added: includes/oik-loader-map.php - to generate the oik-loader.csv file

== Further reading ==
If you want to read more about oik plugins and themes then please visit 
[oik-plugins](https://www.oik-plugins.com/)



