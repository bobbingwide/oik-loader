<?php
/**
 * Admin functions for oik-loader.php
 */

//oik_loader_activate_mu( true );


/**
 * Activate / deactivate oik-loader-mu processing
 *
 * @param bool $activate true to activate, false to deactivate
 */
function oik_loader_activate_mu( $activate=true ) {
	$source = oik_path( 'includes/oik-loader-mu.php', "oik-loader" );
	if ( defined( 'WPMU_PLUGIN_DIR' ) ) {
		$target = WPMU_PLUGIN_DIR;
	} else {
		$target = ABSPATH . '/wp-content/mu-plugins';
	}
	bw_trace2( $target, "target dir", true, BW_TRACE_DEBUG );
	//var_dump( debug_backtrace() );
	//echo "Target: $target";
	if ( is_dir( $target ) ) {
		$target .= "/oik-loader-mu.php";
		if ( $activate ) {
			if ( !file_exists( $target ) ) {
				copy( $source, $target );
			}
		} else {
			if ( file_exists( $target ) ) {
				unlink( $target );
			}
		}
	} else {
		// Do we need to make this ourselves?
		bw_trace2( $target, "Not a dir?", true, BW_TRACE_ERROR );
		//gobang();
	}
}
