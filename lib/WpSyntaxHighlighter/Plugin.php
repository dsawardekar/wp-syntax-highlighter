<?php

namespace WpSyntaxHighlighter;

use Arrow\AssetManager\AssetManager;

class Plugin extends \Arrow\Plugin {

  function __construct($file) {
    parent::__construct($file);

    $this->container
      ->object('pluginMeta', new PluginMeta($file))
      ->object('assetManager', new AssetManager($this->container))
      ->object('optionsManager', new OptionsManager($this->container))
      ->factory('shortcode', 'WpSyntaxHighlighter\Shortcode')
      ->singleton('languageLoader', 'WpSyntaxHighlighter\LanguageLoader')
      ->singleton('shortcodeLinker', 'WpSyntaxHighlighter\ShortcodeLinker')
      ->singleton('languageDetector', 'WpSyntaxHighlighter\LanguageDetector');
  }

  function enable() {
    add_action('admin_init', array($this, 'initAdmin'));
    add_action('admin_menu', array($this, 'initAdminMenu'));
    add_action('init', array($this, 'initFrontEnd'));
  }

  function initAdmin() {
    $this->lookup('optionsPostHandler')->enable();
  }

  function initAdminMenu() {
    $this->lookup('optionsPage')->register();
  }

  function initFrontEnd() {
    $this->lookup('shortcodeLinker')->link();
    $this->lookup('languageDetector')->enable();

    add_action('wp_footer', array($this, 'loadLanguages'));
  }

  function loadLanguages() {
    $this->loadTheme();
    $this->lookup('languageLoader')->load(
      array($this, 'getPluginOptions')
    );
  }

  function loadTheme() {
    $theme      = $this->getTheme();
    $pluginMeta = $this->lookup('pluginMeta');
    $custom     = $pluginMeta->hasCustomStylesheet();

    if ($theme === 'custom' && $custom) {
      /* only load custom theme if present */
      $this->loadCustomTheme($options);
    } else {
      $this->lookup('stylesheetLoader')->stream($theme);

      /* overriding stylesheet so include */
      if ($custom) {
        $this->loadCustomTheme();
      }
    }
  }

  function loadCustomTheme() {
    $this->lookup('stylesheetLoader')->stream('theme-custom');
  }

  function getTheme() {
    return $this->lookup('optionsStore')->getOption('theme');
  }

  function getPluginOptions($script) {
    $options = $this->lookup('optionsStore')->getOptions();
    $options['languages'] = $this->lookup('languageLoader')->getLanguages();

    return $options;
  }

}
