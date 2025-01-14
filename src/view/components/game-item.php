<?php
require_once __DIR__ . '/base.php';
require_once __DIR__ . '/../../lib/utils.php';

class GameItem extends RawDataContainer implements Component {
  static protected function requiredFieldSchema(): array {
    return array_merge(parent::requiredFieldSchema(), [
      'game-name' => 'string',
    ]);
  }

  public function render(): Component {
    $urlQuery = $this->get('url-query');
    $id = $this->get('game-id');
    $description = $this->getDefault('game-description', '');

    return HtmlElement::create('a', [
      'href' => $urlQuery->assign([
        'type' => 'html',
        'page' => 'play',
        'game-id' => $id,
      ])->getUrlQuery(),
      HtmlElement::create('article', [
        HtmlElement::create('figure', [
          HtmlElement::create('img', [
            'src' => $urlQuery->assign([
              'type' => 'file',
              'mime' => 'image/jpeg',
              'name' => $this->get('game-id'),
              'purpose' => 'game-img',
            ])->getUrlQuery(),
          ]),
          $description
            ? ''
            : HtmlElement::create('figcaption', [
              $this->get('game-name'),
            ])
          ,
        ]),
        $description
          ? HtmlElement::emmetBottom('.text-container>.text', [
            HtmlElement::emmetTop('.subtitle.game-name', $this->get('game-name')),
            HtmlElement::emmetTop('.description', MarkdownView::indented($description)),
          ])
          : ''
        ,
      ]),
    ]);
  }
}
?>
