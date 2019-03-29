=== oik-loader ===
Contributors: bobbingwide, vsgloik
Donate link: https://www.oik-plugins.com/oik/oik-donate/
Tags: oik, plugin, loader
Requires at least: 5.1.1
Tested up to: 5.1.1
Stable tag: 0.1.1

WordPress Must Use plugin to load required plugins.

== Description ==
Use the oik-loader plugin to load required plugins on demand.
Dynamically loading the required plugins allow the block catalog to show live examples of blocks implemented by a wide range of plugins.
Developed for use on blocks.wp-a2z.org and oik-plugins.com

== Installation ==
1. Upload the contents of the oik-loader plugin to the `/wp-content/plugins/oik-loader' directory
1. Activate the oik-loader plugin through the 'Plugins' menu in WordPress
1. Visit oik-loader admin page
1. Click on the link to activate/update the Must Use ( MU ) plugin
1. Click on the link to Rebuild the index - oik-loader.site.csv file in the mu-plugins folder
1. Click on the link to Rebuild the plugin dependencies - oik-component-dependencies.site.csv file in the mu-plugins folder

Note: In a WordPress Multi Site installation
- There will only be one version of the Must Use plugin ( oik-loader-mu.php ) 
- There will be multiple index and component dependencies files; one of each per site.


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


The site should operate with/without Gutenberg being activated.


== Screenshots ==
1. None

== Upgrade Notice ==
= 0.1.1 =
Fixes a couple of deployment problems.

= 0.1.0= 
Now supports plugin dependencies for blocks and block examples.  

= 0.0.0 =
Prototype version developed with oik-magnetic-poetry.
oik-blocks needs to be activated since these blocks are used by the block CPT.

== Changelog ==
= 0.1.1 = 
* Fixed: Disable MU logic when running in batch
* Fixed: Avoid message from missing function when oik-loader-mu has not yet been deployed
* Tested: With WordPress 5.1.1 and WordPress Multi Site
* Tested: With Gutenberg 5.3
* Tested: With PHP 7.2 

= 0.1.0 =
* Added: Plugin dependency logic to dynamically load required plugins for a block / block example, [github bobbingwide oik-loader issue #2]
* Added: oik-loader admin to activate/deactivate the Must Use plugin ( oik-loader-mu.php )
* Added: oik-loader admin to build the index for oik-plugins, blocks and block examples
* Added: oik-loader admin to build the plugin dependency file
* Added: oik-loader admin to summarise oik-plugins
* Added: Support for editor invocation ( using post ), server side rendering ( using post_id ), and preview ( using preview_id )
* Added: Support for WordPress Multi Site

= 0.0.0 =
* Added: oik-loader.php - the main plugin file
* Added: includes/oik-loader-mu.php to automatically load the required plugin
* Added: includes/oik-loader-map.php - to generate the oik-loader.csv file

== Further reading ==
If you want to read more about oik plugins and themes then please visit 
[oik-plugins](https://www.oik-plugins.com/)



