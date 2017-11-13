<?php
require_once __DIR__ . '/controller/index.php';
$renderer = new Renderer(false);
$element = HtmlElement::emmet(
  'level-0#lv0.lv0>level-1a*2+level-1b*3>level-2a*3>level-3',
  function (array $params) {
    return array_merge(
      [
        'X-DEEP' => $params['deep'],
      ],
      $params['at-top'] ? ['TOP'] : ['not top'],
      $params['at-bottom'] ? ['BOTTOM'] : ['not bottom']
    );
  }
);
echo $renderer->render($element) . "\n";
//echo main();
?>
