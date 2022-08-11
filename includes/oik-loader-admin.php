<?php
/**
 * Admin functions for oik-loader.php
 */

//oik_loader_activate_mu( true );

/**
 * Implement "oik_admin_menu" action for oik-loader
 *
 * Register the plugin as being supported from an oik-plugins server
 * Does this work for oik-wp as well?
 */
function oik_loader_lazy_admin_menu() {

	oik_register_plugin_server( oik_path( 'oik-loader.php', 'oik-loader') );
	//add_action( "oik_menu_box", "oik_batch_oik_menu_box" );
	//add_action( "oik_menu_box", "oik_batch_oik_menu_box" );
	//add_action( "admin_menu", "oik_batch_admin_menu" );
	add_submenu_page( 'oik_menu', __( 'oik loader', 'oik' ), __("oik loader", 'oik'), 'manage_options', 'oik_loader', "oik_loader_do_page" );


}

function oik_loader_do_page() {

	BW_::oik_menu_header( __( "oik loader", "oik" ), "w100pc" );
	BW_::oik_box( null, null, __( 'oik-loader-mu', 'oik' ), "oik_loader_oik_menu_box" );
	BW_::oik_box( null, null, __( 'plugins', 'oik-loader'), "oik_loader_plugins_box" );
	BW_::oik_box( null, null, __( 'extras', 'oik-loader'), 'oik_loader_extras_box' );
	oik_menu_footer();
	bw_flush();
}

/**
 * We can't rely on the presence of the oik_loader_build_index function since this
 * will still be loaded after uninstallation.
 * We have to check if the file exists.
 * @return bool
 */
function oik_loader_query_loader_mu() {
	$target = oik_loader_target_file();
	if ( $target && file_exists( $target ) ) {
		$installed = true;
	} else {
		$installed = false;
	}
	return $installed;
}

function oik_loader_query_loader_active() {
	$active = function_exists( "oik_loader_mu_build_index") ;
	return $active;

}

function oik_loader_oik_menu_box() {
	oik_loader_mu_maybe_activate();
	oik_loader_process_extras();

	$oik_loader_mu_installed = oik_loader_query_loader_mu();
	if ( $oik_loader_mu_installed ) {
		p( "oik-loader-mu is installed" );
		alink( null, admin_url( "admin.php?page=oik_loader&amp;mu=deactivate" ), __( "Click to deactivate MU", "oik-loader" ) );
	} else {
		p( "Click on the link to install oik-loader-mu logic" );
	}
	br();
	alink( null, admin_url( "admin.php?page=oik_loader&amp;mu=activate" ), __( "Click to activate/update MU", "oik-loader" ) );


	$oik_loader_mu_active = oik_loader_query_loader_active();
	if ( $oik_loader_mu_active ) {
		p( "oik-loader-mu is active");

	} else {
		p( "oik-loader-mu is not loaded");
	}

	if ( $oik_loader_mu_active ) {
		$index = oik_loader_mu_build_index();
		if ( null === $index ) {
			p( "Index not built or empty");
		} else {
			oik_loader_report_index( $index );
			//oik_loader_display_index( $index );
		}
		br();
		alink( null, admin_url( "admin.php?page=oik_loader&amp;mu=rebuild" ), __( "Click to rebuild index", "oik-loader" ) );
		br();
		alink( null, admin_url( "admin.php?page=oik_loader&amp;mu=rebuild-dependencies" ), __( "Click to rebuild plugin dependencies", "oik-loader" ) );
	}
}

/**
 * Activate / deactivate the oik-loader-mu plugin as required.
 *
 * MU plugins are activated as soon as they are installed.
 * Obviously they don't become active until the next page load.
 */
function oik_loader_mu_maybe_activate()
{
    $mu_parm = bw_array_get($_REQUEST, "mu", null);
    switch ($mu_parm) {
        case "activate":
            oik_loader_activate_mu();
            break;
        case "deactivate":
            oik_loader_activate_mu(false);
            break;

        case "rebuild":
            oik_loader_rebuild_index();
            break;
        case "rebuild-dependencies":
            oik_loader_rebuild_dependencies();
            break;

        default:
            break;
    }
}

function oik_loader_process_extras() {
    $save = bw_array_get( $_REQUEST, 'save-extras', null );
    if ( $save ) {
        oik_loader_save_extras();
        oik_loader_rebuild_index();
    }
}

/**
 * Returns fully qualified name for the oik-loader-mu target file
 *
 * @return string|null
 */
function oik_loader_target_file() {
	if ( defined( 'WPMU_PLUGIN_DIR' ) ) {
		$target = WPMU_PLUGIN_DIR;
	} else {
		$target = ABSPATH . '/wp-content/mu-plugins';
	}
	bw_trace2( $target, "target dir", true, BW_TRACE_DEBUG );
	if ( is_dir( $target ) ) {
		$target .= "/oik-loader-mu.php";
	} else {
		// Do we need to make this ourselves?
		bw_trace2( $target, "Not a dir?", true, BW_TRACE_ERROR );
		$target = null;
	}
	return $target;
}

/**
 * Activate / deactivate oik-loader-mu processing
 *
 * @param bool $activate true to activate, false to deactivate
 */
function oik_loader_activate_mu( $activate=true ) {
	$target = oik_loader_target_file();
	if ( $target ) {
		if ( $activate ) {
			$source = oik_path( 'includes/oik-loader-mu.php', "oik-loader" );
			if ( ! file_exists( $target ) || filemtime( $source) > filemtime( $target )) {
				copy( $source, $target );
			}

		} else {
			if ( file_exists( $target ) ) {
				unlink( $target );
			}
		}
	}
}

/**
 * Displays the index
 * Note: There are two entries per post. One for the permalink, the other for the post ID.
 *
 * @param $index
 */

function oik_loader_report_index( $index ) {
	oik_require( "includes/oik-loader-map.php", "oik-loader");
	p( "Index entries: " . count( $index ) );
	$csvs = oik_loader_load_map();
	$expected = count( $csvs) * 2;
	p( "Expected entries: " . $expected );

}

function oik_loader_display_index( $index ) {


	foreach ( $index as $key =>  $plugin ) {
		e( $key );
		e( $plugin );
		br();
	}
}

function oik_loader_rebuild_index() {
	oik_loader_run_oik_loader();
}

function oik_loader_plugins_box() {
	oik_require( "includes/oik-loader-plugins.php", "oik-loader");
	$csvs = [];
	$csvs = oik_loader_map_oik_plugins_CPT( $csvs );

	oik_loader_display_oik_plugins( $csvs );
}

function oik_loader_rebuild_dependencies() {
	oik_require( "includes/oik-loader-plugins.php", "oik-loader");
	oik_loader_lazy_rebuild_dependencies();

}


function oik_loader_extras_box() {
    //p( "Extras");
    oik_require( "includes/oik-loader-extras.php", "oik-loader");
    $extras = oik_loader_load_extras();
    //print_r( $extras );
    $extras_string = implode( "", $extras );
    $lines = max( 10, count( $extras ));
    bw_form();
    BW_::bw_textarea( 'extras', 190, '', $extras_string, $lines );
    //gob();
    br();
    BW_::p( isubmit( 'save-extras', __( "Save extras and rebuild index", "oik-loader" ), null, 'button-primary' ) );
}

function oik_loader_save_extras() {
    oik_require( "includes/oik-loader-extras.php", "oik-loader");
    oik_loader_lazy_save_extras();
}