<?php
/*
Plugin Name: oik-loader
Plugin URI: https://www.oik-plugins.com/oik-plugins/oik-loader
Description: WordPress plugin to dynamically load required plugins for blocks
Version: 1.4.1
Author: bobbingwide
Author URI: https://bobbingwide.com/about-bobbing-wide/
Text Domain: oik-loader
Domain Path: /languages/
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

    Copyright 2019-2023 Bobbing Wide (email : herb@bobbingwide.com )

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

function oik_loader_loaded() {

	add_action( "run_oik-loader.php", "oik_loader_run_oik_loader");
	add_action( "oik_admin_menu", "oik_loader_oik_admin_menu");
	add_action( "oik_admin_loaded", "oik_loader_oik_admin_loaded");

}


/**
 * Generates/updates the oik-loader map file for oik-loader-MU
 *
 */
function oik_loader_run_oik_loader() {
	oik_require( "includes/oik-loader-map.php", "oik-loader");
	oik_loader_update_map();

}

function oik_loader_oik_admin_loaded() {

}

function oik_loader_oik_admin_menu() {
	oik_require( "includes/oik-loader-admin.php", "oik-loader");
	oik_loader_lazy_admin_menu();
}


oik_loader_loaded();