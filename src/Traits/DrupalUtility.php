<?php
namespace Stephane888\HtmlBootstrap\Traits;

use Drupal\Core\Template\Attribute;

trait DrupalUtility {

  use Portions;

  public function loadCardsDataInTheme(&$options, $group)
  {
    $provider = theme_get_setting($group . '_provider', 'multiservicem1');
    if ($provider == 'theme') {
      return false;
    }
  }

  /**
   *
   * @param object $variables
   * @param string $group
   */
  public function loadSlideDataInTheme(&$options, $group)
  {
    $results = [];
    $provider = theme_get_setting($group . '_provider', 'multiservicem1');
    if ($provider == 'theme') {
      return false;
    }
    $name = "nombre_slide";
    $index = intval(theme_get_setting($group . $name, 'multiservicem1'));
    for ($i = 1; $i <= $index; $i ++) {
      $name = $i . 'texte';
      $value = theme_get_setting($group . $name, 'multiservicem1');
      if (! empty($value)) {
        $value = $this->template_htmltag($value, 'div');
        $results[$i]['content'] = $this->templateCenterVertHori($value, 'bg-cover');
      }
      $name = $i . 'image_bg';
      $fid = theme_get_setting($group . $name, 'multiservicem1');
      if (! empty($fid) && $fid[0] > 0) {
        $image_style = 'multiservicem1_1620x1080';
        $file = \Drupal\file\Entity\File::load($fid[0]);
        if ($file && \Drupal\image\Entity\ImageStyle::load($image_style)) {
          $results[$i]['image']['url'] = \Drupal\image\Entity\ImageStyle::load($image_style)->buildUrl($file->getFileUri());
        }
      }
    }
    if (! empty($results)) {
      $options['carousels'] = $results;
    }
  }
}