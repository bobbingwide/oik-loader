=== oik-loader ===
Contributors: bobbingwide, vsgloik
Donate link: https://www.oik-plugins.com/oik/oik-donate/
Tags: oik, plugin, loader
Requires at least: 5.2.3
Tested up to: 6.4.2
Stable tag: 1.5.0

WordPress Must Use plugin to load required plugins.

== Description ==
Use the oik-loader plugin to load required plugins on demand.
Dynamically loading the required plugins allow the block catalog to show live examples of blocks implemented by a wide range of plugins.
Developed for use on blocks.wp-a2z.org and oik-plugins.com.
Extended in 2024 for use on wp-secrets.co.uk.

== Installation ==
1. Upload the contents of the oik-loader plugin to the `/wp-content/plugins/oik-loader' directory
1. Activate the oik-loader plugin through the 'Plugins' menu in WordPress
1. Visit oik-loader admin page
1. Click on the link to activate/update the Must Use ( MU ) plugin
1. Click on the link to Rebuild the index - oik-loader.site.csv file in the mu-plugins folder
1. Click on the link to Rebuild the plugin dependencies - oik-component-dependencies.site.csv file in the mu-plugins folder
1. If required provide an oik-loader-extras.csv file in the mu-plugins folder

Note: In a WordPress Multi Site installation
- There will only be one version of the Must Use plugin ( oik-loader-mu.php ) 
- There will be multiple index and component dependencies files; one of each per site.
- There may be one oik-loader-extras.csv file.


== Frequently Asked Questions ==

= What is this plugin for? =
It helps to reduce the number of activated plugins in blocks.wp-a2z.org
It dynamically loads the required plugins for a plugin, block or block_example.

= Which plugins can I deactivate? =
Once all the blocks for a plugin have been generated then the plugin can be deactivated.

With the following exceptions:
- oik-blocks - since these blocks are used in all content
- any other plugins that deliver blocks that are used throughout the site
- plugins which are required for other functionality


If not running an FSE the site should operate with/without Gutenberg being activated.
Currently Gutenberg is required for an FSE theme.
In v1.1.0 the logic has been changed to support different variants of the Gutenberg plugin being the activated plugin.


== Screenshots ==
1. None

== Upgrade Notice ==
= 1.5.0 =
Update for automatic loading of plugins associated with posts.


== Changelog ==
= 1.5.0 =
* Added: Support automatic loading of plugins referred to by component #22
* Tested: With WordPress 6.4.2
* Tested: With PHP 8.3


== Further reading ==
If you want to read more about oik plugins and themes then please visit 
[oik-plugins](https://www.oik-plugins.com/)