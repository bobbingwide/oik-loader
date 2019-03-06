<?php
/*
Plugin Name: oik-loader-MU
Plugin URI: https://www.oik-plugins.com/oik-plugins/oik-loader-mu
Description: WordPress Must Use plugin to load required plugins
Version: 0.0.0
Author: bobbingwide
Author URI: https://www.oik-plugins.com/author/bobbingwide
License: GPL2

    Copyright 2019 Bobbing Wide (email : herb@bobbingwide.com )

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License version 2,
    as published by the Free Software Foundation.

    You may NOT assume that you can use any other version of the GPL.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    The license for this software can likely be found here:
    http://www.gnu.org/licenses/gpl-2.0.html

*/

$index = oik_loader_mu_build_index();

if ( $index ) {
	$uri    = $_SERVER['REQUEST_URI'];
	$path   = parse_url( $uri, PHP_URL_PATH );
	$plugin = oik_loader_mu_query_plugin( $index, $path );
	if ( null !== $plugin ) {
		oik_loader_load_plugin( $plugin );
		add_filter( "option_active_plugins", "oik_loader_option_active_plugins", 10, 2 );
	}
}

/**
 * Builds the lookup index from oik-loader.csv
 * @return array|null
 */
function oik_loader_mu_build_index() {
	$oik_loader_csv = dirname( __FILE__  ) . "/oik-loader.csv";
	$index = null;
	if ( file_exists( $oik_loader_csv) ) {
		//echo "File exists";
		$lines = file( $oik_loader_csv );
		if ( count( $lines ) ) {
			$index = oik_loader_build_index( $lines );
		}
	}
	return $index;
}

/**
 * Builds the lookup index for access by URI or post ID
 *
 * Post ID will be required when editing the post or server rendering in the REST API
 *
 * @param $lines
 *
 * @return array
 */
function oik_loader_build_index( $lines ) {
	$index = [];
	foreach ( $lines as $line ) {
		$csv = str_getcsv( $line);
		if ( count( $csv) === 3 ) {
			//echo $csv[0];
			if ( isset( $csv[2]) ) {
				$plugin = $csv[2];
			} else {
				$plugin = null;
			}
			$index[ $csv[0]] = $plugin;
			$index[ $csv[1]] = $plugin;
		}
	}
	//print_r( $index );
	return $index;
}

/**
 * Returns the plugin name for the current block CPT
 * @param $index
 * @param $page
 *
 * @return null
 */
function oik_loader_mu_query_plugin( $index, $page ) {
	$plugin = null;
	if ( isset( $index[ $page ])) {
		$plugin = $index[ $page ];
	}
	//echo "$" . $plugin;
	return $plugin;
}


/**
 * Implements 'option_active_plugins' filter
 *
 * Adds the missing plugin(s) to the list of plugins to load
 *
 * @param $active_plugins
 * @param $option
 *
 * @return array
 */
function oik_loader_option_active_plugins( $active_plugins, $option ) {
	//print_r( $active_plugins );
	$load_plugin = oik_loader_load_plugin();
	$active_plugins[] = $load_plugin;
	return $active_plugins;
	
}

/**
 * Sets / gets the name of the plugin(s) to load
 *
 * @param null $plugin
 *
 * @return null
 */
function oik_loader_load_plugin( $plugin=null ) {
	static $load_plugin = null;
	if ( $plugin !== null ) {
		$load_plugin = $plugin;
	}
	return $load_plugin;
}