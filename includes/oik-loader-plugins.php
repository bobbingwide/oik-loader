<?php

function oik_loader_get_oik_plugins_CPT() {
	oik_require( "includes/bw_posts.php" );
	$atts        = array(
		"post_type"    => "oik-plugins",
		"post_parent"  => 0,
		"number_posts" => - 1
	);
	$posts = bw_get_posts( $atts );
	return $posts;
}

function oik_loader_map_oik_plugins_CPT( $csvs ) {
	$posts       = oik_loader_get_oik_plugins_CPT();
	$scheme_host = oik_loader_get_scheme_host();
	//$csvs = [];
	foreach ( $posts as $post ) {
		$line     = [];
		$line[]   = oik_loader_get_hostless_permalink( $post->ID, $scheme_host );
		$line[]   = $post->ID;
		$line[]   = get_post_meta( $post->ID, '_oikp_name', true );
		$csv_line = implode( ",", $line );
		$csvs[]   = $csv_line . PHP_EOL;
	}

	return $csvs;
}

function oik_loader_display_oik_plugins( $csvs ) {
	//print_r( $csvs );
	oik_loader_display_oik_plugins_header();
	foreach ( $csvs as $csv ) {
		$csv = trim( $csv );
		$row = bw_as_array( $csv );
		$row[1] = retlink( null, get_edit_post_link( $row[1] ), $row[1] );
		$row[] = oik_loader_plugin_status( $row[2]);
		bw_tablerow( $row );
	}
	etag( "tbody" );
	etag( "table" );
}

/**
 * Returns the Plugin status for a plugin
 * @param $plugin_name
 *
 * @return string
 *
 */
function oik_loader_plugin_status( $plugin_name ) {
	//$included_files = get_included_files();
	//$status = '?';
	//echo "$plugin_name:$status:";
	//$plugins = get_option( 'active_plugins', array() );


//) || is_plugin_active_for_network( $plugin );
	if ( is_plugin_active_for_network( $plugin_name )) {
		$status = __( "Network active", "oik-loader" );
	} elseif ( is_plugin_active( $plugin_name ) ) {
		$status = __( "Active", "oik_loader" );

	} else {
		$status = "&nbsp;";
		//print_r( $plugins );
		//$in = in_array( $plugin_name, $plugins );
		//print_r( $in );

	}

	$status .= oik_loader_load_plugin_status( $plugin_name );

	return $status;
}

function oik_loader_load_plugin_status( $plugin_name ) {
	$plugins_loaded = null;
	if ( function_exists( "oik_loader_load_plugins") ) {
		$plugins_loaded = oik_loader_load_plugins();
	}
	if ( $plugins_loaded && in_array( $plugin_name, $plugins_loaded ) ) {
		$status = __( " - lazy loaded", "oik-loader" );
	} else {
		$status = null;
	}
	return $status;
}



function oik_loader_display_oik_plugins_header() {
	if ( "cli" == php_sapi_name() ) {
		gob();

		echo "Editor | Mandatory? | Level | Opinion | Notes " . PHP_EOL;
		echo "------ | ---------- | ----- | ------- | ----- " . PHP_EOL;
	} else {
		//oik_require( "shortcodes/oik-table.php" );
		//oik_require_lib( "bobbforms");
		//bw_table_header(
		$title_arr =  bw_as_array( "URL,ID,Plugin&nbsp;name,Status" );
		stag( "table", "bw_table" );
		stag( "thead" );
		bw_tablerow( $title_arr, "tr", "th" );
		etag( "thead" );
		stag( "tbody" );
	}
}

function oik_loader_lazy_rebuild_dependencies() {
	$posts = oik_loader_get_oik_plugins_CPT();
	$csvs = [];
	foreach ( $posts as $post ) {
		$ids = oik_loader_dependent_plugins( $post->ID);
		$line = [];
		foreach ( $ids as $key => $id) {
			if ( $id ) {
				$line[] = get_post_meta( $id, "_oikp_name", true );
			}
		}
		$csvs[] = implode( ',', $line ) . PHP_EOL;
	}

	//$csvfile = oik_loader_component_dependencies_csv_file();
	oik_loader_write_component_dependencies_csv_file( $csvs );
}

/**
 * _oikp_dependency values include 0
 * @param $post_id
 *
 * @return array|mixed
 */

function oik_loader_dependent_plugins( $post_id ) {
	$ids = [];
	$ids = get_post_meta( $post_id, '_oikp_dependency', false );
	array_unshift( $ids, $post_id );
	//print_r( $ids );
	return $ids;
}

function oik_loader_write_component_dependencies_csv_file( $csvs ) {
	$csv_file = oik_loader_component_dependencies_csv_file();
	file_put_contents( $csv_file, $csvs );
}

if ( !function_exists( "oik_loader_component_dependencies_csv_file ")) {
	function oik_loader_component_dependencies_csv_file() {
		$csv_file = WPMU_PLUGIN_DIR;
		$csv_file .= '/oik-component-dependencies.';
		global $blog_id;
		$csv_file .= $blog_id;
		$csv_file .= '.csv';
		return $csv_file;
	}
}

