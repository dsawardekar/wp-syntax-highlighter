<?php

namespace WordPress;

class AdminScriptLoader extends ScriptLoader {

  protected $didAdminLoad = false;

  function needs() {
    return array('pluginSlug');
  }

  function enqueueAction() {
    return 'admin_enqueue_scripts';
  }

  function load() {
    if ($this->didAdminLoad === true) {
      return parent::load();
    } else {
      add_action($this->startAction(), array($this, 'load'));
      $this->didAdminLoad = true;
    }
  }

  function startAction() {
    return 'load-settings_page_' . $this->pluginSlug;
  }

}