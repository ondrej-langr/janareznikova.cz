<?php

namespace App;

use function Roots\view;

/**
 * Get / set the specified configuration value.
 *
 * If an array is passed as the key, we will assume you want to set an array of values.
 *
 * @param array|string $key
 * @param mixed $default
 * @return mixed|\Roots\Sage\Config
 * @copyright Taylor Otwell
 * @link https://github.com/laravel/framework/blob/c0970285/src/Illuminate/Foundation/helpers.php#L254-L265
 */
function config($key = null, $default = null)
{
  if (is_null($key)) {
    return \app('config');
  }
  if (is_array($key)) {
    return \app('config')->set($key);
  }
  return \app('config')->get($key, $default);
}

/**
 * @param string $file
 * @param array $data
 * @return string
 */
function template($file, $data = [])
{
  return \view($file, $data);
}

/**
 * @param $asset
 * @return string
 */
function asset_path($asset)
{
  return \app('sage.assets')->uri($asset);
}

/**
 * @param string|string[] $templates Possible template files
 * @return array
 */
function filter_templates($templates)
{
  $paths = apply_filters('sage/filter_templates/paths', [
    'views',
    'resources/views'
  ]);
  $paths_pattern = "#^(" . implode('|', $paths) . ")/#";

  return collect($templates)
    ->map(function ($template) use ($paths_pattern) {
      /** Remove .blade.php/.blade/.php from template names */
      $template = preg_replace('#\.(blade\.?)?(php)?$#', '', ltrim($template));

      /** Remove partial $paths from the beginning of template names */
      if (strpos($template, '/')) {
        $template = preg_replace($paths_pattern, '', $template);
      }

      return $template;
    })
    ->flatMap(function ($template) use ($paths) {
      return collect($paths)
        ->flatMap(function ($path) use ($template) {
          return [
            "{$path}/{$template}.blade.php",
            "{$path}/{$template}.php",
          ];
        })
        ->concat([
          "{$template}.blade.php",
          "{$template}.php",
        ]);
    })
    ->filter()
    ->unique()
    ->all();
}

/**
 * @param string|string[] $templates Relative path to possible template files
 * @return string Location of the template
 */
function locate_template($templates)
{
  return \locate_template(filter_templates($templates));
}

/**
 * Determine whether to show the sidebar
 * @return bool
 */
function display_sidebar()
{
  static $display;
  isset($display) || $display = apply_filters('sage/display_sidebar', false);
  return $display;
}
