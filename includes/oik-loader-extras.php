<?php
/**
 * @copyright (C) Copyright Bobbing Wide 2019
 */


function oik_loader_append_extras( $csvs ) {
    $extras = oik_loader_load_extras();
    foreach ( $extras as $extra ) {
        $csvs[]  = $extra; // . PHP_EOL;
    }
    return $csvs ;
}

function oik_loader_extras_file() {
    $csv_file = WPMU_PLUGIN_DIR ;
    $csv_file .= '/';
    $csv_file .= 'oik-loader-extras';
    //$csv_file .= '.';
    //$csv_file .= $blog_id;
    $csv_file .= '.csv';
    return $csv_file;
}

function oik_loader_load_extras() {
    $extras = file( oik_loader_extras_file() );
    return $extras;
}
