<?php
require_once __DIR__ . '/base.php';
require_once __DIR__ . '/anchor.php';
require_once __DIR__ . '/../../lib/utils.php';

class Logo extends RawDataContainer implements Component {
  public function render(): Component {
    return HtmlElement::create('h1', [
      'id' => 'main-logo',
      'classes' => ['logo'],
      $this->getDefault('hidable-nav', false)
        ? HtmlElement::emmetTop('button#nav-hiding-button', [])
        : ''
      ,
      Anchor::withoutAttributes('.', $this->get('title')),
    ]);
  }
}
?>
