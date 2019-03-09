<?php

/**
 * Create the oik-loader map file for oik-loader-MU
 * in the mu-plugins folder.
 *
 * Syntax: oikwp oik-loader.php url=blocks.wp-a2z.org
 *
 *
 */

if ( !function_exists( "oik_loader_csv_file") ) {

	function oik_loader_csv_file() {
		$csv_file = WPMU_PLUGIN_DIR;
		$csv_file .= '/oik-loader.';
		global $blog_id;
		$csv_file .= $blog_id;
		$csv_file .= '.csv';
		return $csv_file;
	}
}
function oik_loader_update_map() {
	$csv = oik_loader_csv_file();
	e( "Updating " . $csv );
	br();
	$csvs = oik_loader_load_map();
	oik_loader_write_csv_file( $csvs );
}

function oik_loader_load_map() {
	oik_require( "includes/oik-loader-plugins.php", "oik-loader");
	$csvs = [];
	$csvs = oik_loader_map_oik_plugins_CPT( $csvs );
	$csvs = oik_loader_map_block_CPT( $csvs );
	$csvs = oik_loader_map_block_example_CPT( $csvs );
	return $csvs;
}

function oik_loader_get_scheme_host() {
	$siteurl = site_url( null, "https");
	$host = parse_url( $siteurl, PHP_URL_HOST );
	$scheme_host = "https://" . $host;
	return $scheme_host;
}

function oik_loader_report_siteurl() {
	$siteurl = site_url( null, "https");
	e( "Site URL: " . $siteurl );
	br();
}

function oik_loader_get_hostless_permalink( $post_id, $scheme_host ) {
	$permalink = get_permalink( $post_id );
	$permalink = str_replace( $scheme_host, "", $permalink );
	return $permalink;
}

function oik_loader_query_plugin_name( $post_id ) {
	$plugin_name = null;
	$plugin_id = get_post_meta( $post_id, "_oik_sc_plugin", true );
	if ( $plugin_id ) {
		$plugin_name = get_post_meta( $plugin_id, "_oikp_name", true );
	}
	return $plugin_name;
}

/**
 * Map block CPTs
 */
function oik_loader_map_block_CPT( $csvs ) {
	oik_require( "includes/bw_posts.php" );
	$atts        = array(
		"post_type"    => "block",
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
		$line[]   = oik_loader_query_plugin_name( $post->ID );
		$csv_line = implode( ",", $line );
		$csvs[]   = $csv_line . PHP_EOL;
	}

	return $csvs;
}
/**
 * Map block_example CPTs
 */
function oik_loader_map_block_example_CPT( $csvs ) {
	oik_require( "includes/bw_posts.php" );
	$atts        = array(
		"post_type"    => "block_example",
		"post_parent"  => 0,
		"number_posts" => - 1
	);
	$posts       = bw_get_posts( $atts );
	$scheme_host = oik_loader_get_scheme_host();
	foreach ( $posts as $post ) {
		$line     = [];
		$line[]   = oik_loader_get_hostless_permalink( $post->ID, $scheme_host );
		$line[]   = $post->ID;
		$line[]   = oik_loader_query_block_example_plugin_name( $post->ID );
		$csv_line = implode( ",", $line );
		$csvs[]   = $csv_line . PHP_EOL;
	}
	return $csvs;
}

/**
 * Returns the plugin name given block_example ID
 *
 */
function oik_loader_query_block_example_plugin_name( $post_id ) {
	$block_id = get_post_meta( $post_id, "_block_ref", true);
	$plugin_name = oik_loader_query_plugin_name( $block_id );
	return $plugin_name;
}

function oik_loader_write_csv_file( $csvs ) {
	$csv_file = oik_loader_csv_file();
	file_put_contents( $csv_file, $csvs );
}


