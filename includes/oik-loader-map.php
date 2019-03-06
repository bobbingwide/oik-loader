<?php

//if ( PHP_SAPI !== "cli" ) {
//	die();
//}

/**
 * Create the oik-loader map file for oik-loader-MU
 * in the mu-plugins folder
 *
 * It can either be a PHP file or a CSV or something
 *
 *
 * Syntax: oikwp oik-loader.php url=blocks.wp-a2z.org
 *
 *
 */

function oik_loader_csv_file() {
	return WPMU_PLUGIN_DIR . '/oik-loader.csv';
}
function oik_loader_map() {
	$csv = oik_loader_csv_file();
	echo "Updating " . $csv;
	echo PHP_EOL;

	oik_loader_map_block_CPT();



}

function oik_loader_get_scheme_host() {

	$siteurl = site_url( null, "https");
	echo $siteurl;
	$host = parse_url( $siteurl, PHP_URL_HOST );
	$scheme_host = "https://" . $host;
	return $scheme_host;
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

function oik_loader_map_block_CPT() {
	oik_require( "includes/bw_posts.php");
	$atts = array( "post_type" => "block",
		"post_parent" => 0,
		"number_posts" => -1
	);
	$posts = bw_get_posts( $atts );
	$scheme_host = oik_loader_get_scheme_host();
	$csvs = [];
	foreach ( $posts as $post ) {
		$line = [];
		$line[] = oik_loader_get_hostless_permalink( $post->ID, $scheme_host );
		$line[] = $post->ID;
		$line[] = oik_loader_query_plugin_name( $post->ID );
		$csv_line = implode( ",", $line );
		$csvs[] = $csv_line . PHP_EOL;
	}
	oik_loader_write_csv_file( $csvs );
}

function oik_loader_write_csv_file( $csvs ) {
	$csv_file = oik_loader_csv_file();
	file_put_contents( $csv_file, $csvs );
}


