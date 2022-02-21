<?php
/**
 * @copyright (C) Copyright Bobbing Wide 2019-2022
 */

function oik_loader_append_extras( $csvs ) {
    $extras = oik_loader_load_extras();
    foreach ( $extras as $extra ) {
        $csvs[]  = $extra; // . PHP_EOL;
    }
    return $csvs ;
}

function oik_loader_extras_file() {
    global $blog_id;
    $csv_file = WPMU_PLUGIN_DIR ;
    $csv_file .= '/';
    $csv_file .= 'oik-loader-extras';
    $csv_file .= '.';
    $csv_file .= $blog_id;
    $csv_file .= '.csv';
    return $csv_file;
}

function oik_loader_load_extras() {
    $filename = oik_loader_extras_file();
    $extras = [];
    if ( file_exists( $filename ) ) {
        $extras = file(oik_loader_extras_file());
    }
    return $extras;
}

function oik_loader_lazy_save_extras() {
    p( "Saving extras");
    $extras = bw_array_get( $_REQUEST, 'extras', null);
    if ( $extras ) {

        $file = oik_loader_extras_file();
        p( "Updating $file");
        file_put_contents( $file, $extras );
    }
}
