<?php
require_once __DIR__ . '/../lib/utils.php';

class BlockSize extends RawDataContainer {
  static public function xy($width, $height): self {
    return new static([$width, $height]);
  }

  static public function sqr($size): self {
    return static::xy($size, $size);
  }

  public function width(): int {
    return static::get(0);
  }

  public function height(): int {
    return static::get(1);
  }

  public function switch(): self {
    return static::xy($this->height(), $this->width());
  }

  public function times(float $nx, float $ny): self {
    return static::xy($this->width() * $nx, $this->height() * $ny);
  }

  public function widthTimes(float $factor): self {
    return $this->times($factor, 1);
  }

  public function heightTimes(float $factor): self {
    return $this->times(1, $factor);
  }
}

class VectorSize extends RawDataContainer {}

class SizeSet extends LazyLoadedDataContainer {
  protected function load(): array {
    $unitSize = 60;
    $unitSquare = BlockSize::sqr($unitSize);
    $headerSquareButtonSize = $unitSize / 2;
    $headerSquareButton = BlockSize::sqr($headerSquareButtonSize);
    $headerVerticalPadding = ($unitSize - $headerSquareButtonSize) / 2;
    $cornerSegmentSize = $unitSquare->times(3, 1);
    $navigationEntryHeight = 2 * $unitSize / 3;
    $profileSettingWidth = 4 * $unitSize;
    $profileSettingAvatarSize = $profileSettingWidth / 4;
    $profileSettingIdentityWidth = $profileSettingWidth - $profileSettingAvatarSize;
    $textButtonWidth = 2 * $unitSize;
    $textButtonHeight = $headerSquareButtonSize;
    $textButton = BlockSize::xy($textButtonWidth, $textButtonHeight);
    $gameItemWidth = 4 * $unitSize;
    $gameItemHeight = 3 * $unitSize;
    $gameItemImageWidth = 3 * $unitSize;
    $gameItemImageHeight = 2 * $unitSize;
    $gameItemImageMargin = 0;
    $gameItemFigureMargin = ($gameItemWidth - $gameItemImageWidth) / 2;
    $gameItemFigcaptionHeight = $unitSize / 2;
    $footerHeight = 2 * $unitSize;
    $userprofileSettingWidth = 10 *$unitSize;
    $hidingSearchBoxWidth = 4 * 3 * $unitSize;
    $hidingNavigatorWidth = 4 * 3 * $unitSize;
    $hidingRightSegmentWidth = 2 * 3 * $unitSize;
    $hiddingDateWidth = 7 * 3 * $unitSize;
    $mainSectionMinWidth = 2 * 3 * $unitSize;
    $commentHeight = $unitSize;

    $begin = Tree::instance([
      'unit' => [
        'size' => $unitSize,
        'square' => $unitSquare,
      ],
      'header' => [
        'vertical-padding' => $headerVerticalPadding,
        'child-height' => $headerSquareButtonSize,
        'square-button' => $headerSquareButton,
      ],
      'corner-segment' => [
        '' => $cornerSegmentSize,
      ],
      'middle-segment' => [
        'padding' => $unitSize * 4,
      ],
      'right-segment' => [
        '' => $unitSquare->times(3, 1),
      ],
      'logo' => [
        '' => $unitSquare->times(3, 1),
        'line-height' => 5 * $unitSize / 6,
      ],
      'search-box' => [
        'height' => $unitSize,
        'button' => $headerSquareButton->times(2, 1),
        'input' => [
          'height' => $headerSquareButtonSize,
          'padding-left' => 0,
          'padding-right' => $headerSquareButtonSize,
        ],
      ],
      'profile-setting' => [
        'width' => $profileSettingWidth,
        'avatar' => [
          '' => BlockSize::sqr($profileSettingAvatarSize),
          'size' => $profileSettingAvatarSize,
        ],
        'identity' => BlockSize::xy($profileSettingIdentityWidth, $profileSettingAvatarSize),
      ],
      'user-profile-setting' => [
        'width' => $userprofileSettingWidth,
      ],
      'navigation' => [
        'entry-height' => $navigationEntryHeight,
        'entry-line-height' => $navigationEntryHeight,
      ],
      'text-button' => [
        '' => $textButton,
      ],
      'game-item' => [
        '' => BlockSize::xy($gameItemWidth, $gameItemHeight),
        'image' => [
          '' => BlockSize::xy($gameItemImageWidth, $gameItemImageHeight),
          'margin' => $gameItemImageMargin,
        ],
        'figure' => [
          'margin' => $gameItemFigureMargin,
        ],
        'figcaptionheight' => $gameItemFigcaptionHeight,
      ],
      'search-result' => [
        'item' => [
          'height' => 3 * $unitSize,
        ],
      ],
      'footer' => [
        'height' => $footerHeight,
        'margin' => $unitSize,
      ],
      'hiding' => [
        'search-box-width' => $hidingSearchBoxWidth,
        'navigator-width' => $hidingNavigatorWidth,
        'right-segment-width' => $hidingRightSegmentWidth,
        'date-width' => $hiddingDateWidth,
      ],
      'main-section' => [
        'min-width' => $mainSectionMinWidth,
      ],
      'comment' => [
        'avatar' => BlockSize::sqr($commentHeight),
      ],
    ])->flat('-', '');

    $middle = [];
    foreach ($begin as $prefix => $size) {
      if ($size instanceof BlockSize) {
        $middle["$prefix-width"] = $size->width();
        $middle["$prefix-height"] = $size->height();
        $middle["$prefix-size-block"] = $size;
      } else if ($size instanceof VectorSize) {
        foreach ($size->getData() as $index => $element) {
          $middle["$prefix-$index"] = $element;
        }
        $middle["$prefix-size-vector"] = $size;
      } else {
        $middle[$prefix] = $size;
      }
    }

    $final = [];
    foreach ($middle as $key => $value) {
      $final[$key] = self::transform($value);
    }

    return $final;
  }

  static private function transform($value): string {
    switch (gettype($value)) {
      case 'string':
        return $value;
      case 'integer':
      case 'double':
        return "{$value}px";
      case 'object':
        if ($value instanceof BlockSize) {
          $width = self::transform($value->width());
          $height = self::transform($value->height());
          return "width: $width; height: $height;";
        }
        if ($value instanceof VectorSize) {
          return implode(' ', $value->getData());
        }
        throw new TypeError('Cannot transform this type of object: ' . get_class($value));
      default:
        throw new TypeError('Cannot transform this type of value: ' . gettype($value));
    }
  }
}
?>
