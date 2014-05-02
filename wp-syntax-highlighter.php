<?php
/*
Plugin Name: wp-syntax-highlighter
Description: WordPress Syntax Highlighter using Highlight.js
Version: 0.1.3
Author: Darshan Sawardekar
Author URI: http://pressing-matters.io/
Plugin URI: http://wordpress.org/plugins/wp-syntax-highlighter
License: GPLv2
*/

require_once(__DIR__ .  '/lib/WordPress/Requirements.php');

use WordPress\MinRequirements;
use WordPress\FauxPlugin;

function wp_syntax_highlighter_load() {
  require_once(__DIR__ . '/vendor/autoload.php');
}

function wp_syntax_highlighter_faux_load($requirements) {
  $plugin = new FauxPlugin('wp-syntax-highlighter', $requirements->getResults());
  $plugin->activate(__FILE__);
}

function wp_syntax_highlighter_main() {
  $requirements = new MinRequirements();

  if ($requirements->satisfied()) {
    wp_syntax_highlighter_load();
  } else {
    wp_syntax_highlighter_faux_load($requirements);
  }
}

wp_syntax_highlighter_main();
