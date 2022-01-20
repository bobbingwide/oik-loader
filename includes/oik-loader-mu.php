<?php
/*
Plugin Name: oik-loader-MU
Plugin URI: https://www.oik-plugins.com/oik-plugins/oik-loader-mu
Description: WordPress Must Use plugin to load required plugins
Version: 1.0.0
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

if ( PHP_SAPI !== "cli" ) {
	oik_loader_mu_loaded();
}

function oik_loader_mu_loaded() {
	$index = oik_loader_mu_build_index();
	//print_r( $index );

	if ( $index ) {
		$uri     = $_SERVER['REQUEST_URI'];
		$path    = parse_url( $uri, PHP_URL_PATH );
		$plugins = oik_loader_mu_query_plugins( $index, $path );
		if ( null === $plugins ) {
			$post_id = oik_loader_mu_determine_post_id( $uri );
			if ( $post_id ) {
				$plugins = oik_loader_mu_query_plugins( $index, $post_id );
			}
			if ( null === $plugins ) {
			    $plugins = oik_loader_mu_query_plugins_for_query( $index );
            }
		}
		//print_r( $plugins );
		//echo "cfd;";

		if ( null !== $plugins ) {
			$plugins = oik_loader_plugin_dependencies( $plugins );
			oik_loader_load_plugins( $plugins );
			add_filter( "option_active_plugins", "oik_loader_option_active_plugins", 10, 2 );
		}

	}
}

/**
 * Builds the lookup index from oik-loader.blog_id.csv
 *
 * @return array|null
 */
function oik_loader_mu_build_index() {
	$oik_loader_csv = oik_loader_csv_file();
	$index = null;
	if ( file_exists( $oik_loader_csv) ) {
		//echo "File exists";
		$lines = file( $oik_loader_csv );
		//echo count( $lines );
		//echo PHP_EOL;
		if ( count( $lines ) ) {
			$index = oik_loader_build_index( $lines );
		}
	}
	return $index;
}

	//$oik_loader_csv = dirname( __FILE__  ) . "/oik-loader." . $blog_id . ".csv";


function oik_loader_csv_file( $file='oik-loader') {
	global $blog_id;
	$csv_file = WPMU_PLUGIN_DIR ;
	$csv_file .= '/';
	$csv_file .= $file;
	$csv_file .= '.';
	$csv_file .= $blog_id;
	$csv_file .= '.csv';
	return $csv_file;
}

/**
 * Builds the lookup index for access by URI or post ID
 *
 * Post ID will be required when editing the post, server rendering in the REST API, or when previewing
 *
 * The format of the oik-loader CSV file is
 * `
 * URL,ID,plugin1,plugin2,...
 * e.g.
 * /block/uk-tides-oik-block-uk-tides/,551,oik-blocks/oik-blocks.php,uk-tides/uk-tides.php
 *
 * `
 *
 * @param $lines
 *
 * @return array
 */
function oik_loader_build_index( $lines ) {
	$index = [];
	foreach ( $lines as $line ) {
		$csv = str_getcsv( $line);
		if ( count( $csv) >= 3 ) {
			//echo $csv[0];
			$url = array_shift( $csv );
			$ID = array_shift( $csv );
			$index[ $url] = $csv;
			$index[ $ID] = $csv;
		}
	}
	//print_r( $index );
	return $index;
}

/**
 * Returns the plugin names for the current post
 *
 * @param $index
 * @param $page
 *
 * @return array of dependent plugin names
 */
function oik_loader_mu_query_plugins( $index, $page ) {
    //echo $page;
	$plugins = null;
	if ( isset( $index[ $page ])) {
	    //print_r( $index[ $page ]);
        if  ( 0 === strpos( $index[$page][0], 'gutenberg' ) ) {
        } else {
		$plugins = $index[ $page ];
        }
	}
	//echo "$" . count( $plugins ) . "£";
	return $plugins;
}


/**
 * Implements 'option_active_plugins' filter
 *
 * Adds the missing plugin(s) to the list of plugins to load
 * This filter may be called multiple times, but we should only need to add our plugins once.
 * @TODO Improve performance
 *
 * @param $active_plugins
 * @param $option
 *
 * @return array
 */
function oik_loader_option_active_plugins( $active_plugins, $option ) {
	//print_r( $active_plugins );
	//bw_backtrace();
	$load_plugins = oik_loader_load_plugins();
	// build plugin dependency list
	if ( $load_plugins ) {
		//print_r( $load_plugins );

		foreach ( $load_plugins as $load_plugin ) {
            $load_plugin = trim( $load_plugin );
			if ( !in_array( $load_plugin, $active_plugins) ) {
				//echo "adding $load_plugin";
                array_unshift( $active_plugins, $load_plugin );
				//$active_plugins[] = $load_plugin;
			}
		}
	}
	//print_r( $active_plugins );
	return $active_plugins;
	
}

/**
 * Sets / gets the names of the plugins to load
 *
 * @param null|array $plugins
 *
 * @return null|array
 */
function oik_loader_load_plugins( $plugins=null ) {
	static $load_plugins = null;
	if ( $plugins !== null ) {
		$load_plugins = $plugins;
	}
	//echo "Load plugins";
	//var_dump( debug_backtrace());
	//print_r( $load_plugins );
	return $load_plugins;
}

/**
 * Attempts to determine the post ID for the request
 *
 * @param $uri
 *
 * @return mixed|null
 */
function oik_loader_mu_determine_post_id( $uri ) {
	//$querystring = parse_url( $uri, PHP_URL_QUERY );
	$id = null;
	$querystring = $_SERVER[ 'QUERY_STRING'];
	$parms = [];
	if ( $querystring ) {
		parse_str( $querystring, $parms );
		$id = isset( $parms['post'] ) ? $parms['post'] : null;
		if ( !$id ) {
			$id = isset( $parms[ 'post_id' ]) ? $parms['post_id'] : null;
		}
		if ( !$id ) {
			$id = isset( $parms[ 'preview_id' ]) ? $parms['preview_id'] : null;
		}
		//print_r( $parms );


	} else {
		// No querystring is fine.
	}
	return $id;

}

function oik_loader_plugin_dependencies( $plugins ) {

	$dependencies = oik_loader_load_plugin_dependency_file();
	if ( $dependencies ) {
		//print_r( $dependencies );
		foreach ( $plugins as $plugin ) {
			if ( isset( $dependencies[ $plugin])) {
				$toadd = $dependencies[ $plugin];
				foreach ( $toadd as $dependent_upon ) {
					if  ( 0 === strpos( $dependent_upon, 'gutenberg' ) ) {

						continue;
					}
					if ( !isset( $plugins[ $dependent_upon ])) {
						$plugins[] = $dependent_upon;
					}
				}
			}

		}
	}
	return $plugins;
}

function oik_loader_load_plugin_dependency_file() {
	$dependencies_array = null;
	$csv_file = oik_loader_csv_file("oik-component-dependencies" );
	if ( file_exists( $csv_file ) ) {
		$dependencies = file( $csv_file );
		foreach ( $dependencies as $dependency ) {
			$depends = explode( ',', $dependency );
			if ( count( $depends ) > 1 ) {
				$key = array_shift( $depends );
				$dependencies_array[ $key ] = $depends;
			}

		}
	}
	return $dependencies_array;
}

function oik_loader_mu_query_plugins_for_query( $index ) {
    $plugins = null;
    $querystring = $_SERVER[ 'QUERY_STRING'];
    if ( $querystring ) {
        $plugins = oik_loader_mu_query_plugins($index, $querystring);
    }
    return $plugins;
}