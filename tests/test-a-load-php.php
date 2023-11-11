<?php

/**
 * @package oik-loader
 * @copyright (C) Copyright Bobbing Wide 2023
 *
 * Unit tests to load all the PHP files for PHP 8.2
 */
class Tests_load_php extends BW_UnitTestCase
{

	/**
	 * set up logic
	 *
	 * - ensure any database updates are rolled back
	 * - we need oik-googlemap to load the functions we're testing
	 */
	function setUp(): void 	{
		parent::setUp();
	}

	function test_load_includes_php() {
		oik_require( 'includes/oik-loader-admin.php', 'oik-loader');
		oik_require( 'includes/oik-loader-extras.php', 'oik-loader');
		oik_require( 'includes/oik-loader-map.php', 'oik-loader');
		//oik_require( 'includes/oik-loader-mu.php', 'oik-loader');
		oik_require( 'includes/oik-loader-plugins.php', 'oik-loader');
		$this->assertTrue( true );
	}


	function test_load_plugin_php() {
		oik_require( 'oik-loader.php', 'oik-loader');
		$this->assertTrue( true );
	}
}


