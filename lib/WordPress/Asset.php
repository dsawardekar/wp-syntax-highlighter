<?php

namespace WordPress;

class Asset {

  public $slug;
  public $options = array();
  public $dependencies = false;
  public $localizer = null;
  public $pluginFile;

  /* abstract */
  public function needs() {
    return array('pluginFile', 'pluginSlug');
  }

  public function dirname() {
    return 'assets';
  }

  public function extension() {
    return '.js';
  }

  public function register() {

  }

  public function enqueue() {

  }

  public function localize($data) {

  }

  function runLocalizer() {
    $data = call_user_func($this->localizer, $this);
    $this->localize($data);
    return $data;
  }

  function localizeSlug() {
    return str_replace('-', '_', $this->slug);
  }

  function option($key) {
    if (array_key_exists($key, $this->options)) {
      return $this->options[$key];
    } else {
      return false;
    }
  }

  function relpath() {
    return $this->dirname() . "/" . $this->slug . $this->extension();
  }

  function path() {
    if ($this->isCustomSlug()) {
      return $this->customPath();
    } else {
      return plugins_url($this->relpath(), $this->pluginFile);
    }
  }

  function isCustomSlug() {
    return preg_match('/^theme-/', $this->slug) === 1;
  }

  function underscorize($value) {
    return str_replace('_', '-', $value);
  }

  function customPath() {
    $slug = preg_replace('/^theme-/', '', $this->slug);
    $themeUrl = get_stylesheet_directory_uri();

    $path  = $themeUrl;
    $path .= '/';
    $path .= $this->underscorize($this->pluginSlug);
    $path .= '/';
    $path .= $slug;
    $path .= $this->extension();

    return $path;
  }

}
