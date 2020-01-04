<?php
namespace Stephane888\HtmlBootstrap\Traits;

use Drupal\Core\Template\Attribute;

trait DrupalUtility {

  use Portions;

  public function loadCardsDatas(&$options, $group, $theme)
  {
    $results = [];
    $provider = theme_get_setting($group . '_provider', $theme);
    if ($provider == 'theme') {
      return false;
    } elseif ($provider == 'node') {
      $bundle = theme_get_setting($group . '__content_type', $theme);
      $nombre_item = theme_get_setting($group . '_nombre_item', $theme);
      $query = \Drupal::entityQuery('node');
      $query->sort('nid', 'DESC');
      $query->range(0, $nombre_item);
      $query->condition('status', 1);
      $query->condition('type', $bundle);
      $ids = $query->execute();
      $nodes = \Drupal::entityTypeManager()->getStorage('node')->loadMultiple($ids);
      $fieldicone = theme_get_setting($group . '__fieldicone', $theme);
      $fieldtitle = theme_get_setting($group . '__fieldtitle', $theme);
      $fielddescription = theme_get_setting($group . '__fielddescription', $theme);
      foreach ($nodes as $node) {
        // kint($node->toLink());
        if ($node->hasField($fieldicone) && $node->hasField($fielddescription)) {
          $fielddescription = $node->get($fielddescription)->getValue();
          $fieldicone = $node->get($fieldicone)->getValue();
          $results[] = [
            'title' => $node->getTitle(),
            'text' => (! empty($fielddescription)) ? reset($fielddescription)["value"] : '',
            'icone' => (! empty($fieldicone)) ? reset($fieldicone)["value"] : '',
            'link' => $node->toLink()->getUrl()
          ];
        }
      }
      if (! empty($results)) {
        $options['cards'] = $results;
      }
    }
  }

  /**
   *
   * @param object $variables
   * @param string $group
   */
  public function loadSlideDatas(&$options, $group, $theme)
  {
    $results = [];
    $provider = theme_get_setting($group . '_provider', $theme);
    if ($provider == 'theme') {
      return false;
    }
    $index = intval(theme_get_setting($group . "nombre_slide", $theme));
    for ($i = 1; $i <= $index; $i ++) {
      $value = theme_get_setting($group . $i . 'texte', $theme);
      if (! empty($value)) {
        $value = $this->template_htmltag($value, 'div');
        $results[$i]['content'] = $this->templateCenterVertHori($value, 'bg-cover');
      }
      $fid = theme_get_setting($group . $i . 'image_bg', $theme);
      if (! empty($fid) && $fid[0] > 0) {
        $image_style = $theme . '_1620x1080';
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