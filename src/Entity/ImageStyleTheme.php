<?php
namespace Stephane888\HtmlBootstrap\Entity;

use Drupal\image\Entity\ImageStyle;

class ImageStyleTheme extends ImageStyle {

  public function buildUrlTeme($path, $clean_urls = NULL)
  {
    $file_url = file_create_url($path) . '?itok=_DGxyx-M';
    return $file_url;
  }
}
