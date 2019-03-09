<?php


function oik_loader_map_oik_plugins_CPT( $csvs ) {
	oik_require( "includes/bw_posts.php" );
	$atts        = array(
		"post_type"    => "oik-plugins",
		"post_parent"  => 0,
		"number_posts" => - 1
	);
	$posts       = bw_get_posts( $atts );
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
	if ( function_exists( "oik_loader_load_plugin") ) {
		$plugin_loaded = oik_loader_load_plugin();

	}
	if ( $plugin_loaded === $plugin_name ) {
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
