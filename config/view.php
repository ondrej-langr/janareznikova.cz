<?php

return [

  /*
    |--------------------------------------------------------------------------
    | View Storage Paths
    |--------------------------------------------------------------------------
    |
    | Most template systems load templates from disk. Here you may specify
    | an array of paths that should be checked for your views.
    |
    */

  'paths' => [
    get_theme_file_path() . '/resources/views',
    get_parent_theme_file_path() . '/resources/views',
  ],


  /*
    |--------------------------------------------------------------------------
    | Compiled View Path
    |--------------------------------------------------------------------------
    |
    | This option determines where all the compiled Blade templates will be
    | stored for your application. Typically, this is within the uploads
    | directory. However, as usual, you are free to change this value.
    |
    */

  'compiled' => wp_upload_dir()['basedir'] . '/cache',

  'namespaces' => [
    // 'WC' => WP_PLUGIN_DIR.'/woocommerce/templates/',
  ],
];
