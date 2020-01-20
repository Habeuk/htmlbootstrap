<?php
namespace Stephane888\HtmlBootstrap\Traits;

// use Drupal\Core\Template\Attribute;
trait DrupalUtility {

  use Portions;

  public function loadImageTextRightLeftDatas(&$options, $group, $theme_name, $display)
  {
    if ($display['provider'] == 'custom') {
      $options = \array_merge($options, $display['options']);
    }
  }

  public function loadPriceLists(&$options, $group, $theme_name, $display)
  {
    if ($display['provider'] == 'custom') {
      /**
       * on remplie les champs de liste vides avec le model de reference : le deux.
       */
      $display['options']['cards'][0] = $this->remplissageAuto($display['options']['cards'][1], $display['options']['cards'][0]);
      $display['options']['cards'][2] = $this->remplissageAuto($display['options']['cards'][1], $display['options']['cards'][2]);
      $options = \array_merge($options, $display['options']);
    }
  }

  /**
   *
   * @param array $options
   * @param string $group
   * @param string $theme_name
   * @return boolean
   */
  public function loadCommentsDatas(&$options, $group, $theme_name)
  {
    // $results = [];
    $provider = theme_get_setting($group . '_provider', $theme_name);
    if ($provider == 'theme') {
      return false;
    } elseif ($provider == 'node') {}
  }

  /**
   *
   * @param array $options
   * @param string $group
   * @param string $theme_name
   * @return boolean
   */
  public function loadCarouselCardsDatas(&$options, $group, $theme_name)
  {
    // $results = [];
    $provider = theme_get_setting($group . '_provider', $theme_name);
    if ($provider == 'theme') {
      return false;
    } elseif ($provider == 'node') {}
  }

  public function loadCallActionsDatas(&$options, $group, $theme_name)
  {
    ;
  }

  public function loadCardsDatas(&$options, $group, $theme_name, $display)
  {
    $results = [];
    $provider = $display['provider'];
    if ($provider == 'theme') {
      return false;
    } elseif ($provider == 'node') {
      $bundle = $display['content_type'];
      $nombre_item = $display['nombre_item'];
      $nodes = $this->getNodes($bundle, $nombre_item);
      $fieldicone = $display['fieldicone'];
      // $fieldtitle = theme_get_setting($group . '__fieldtitle', $theme_name);
      $fielddescription = $display['fielddescription'];
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
    } elseif ($provider == 'custom') {
      $options = \array_merge($options, $display['options']);
    }
  }

  protected function getNodes($bundle, $nombre_item = 8)
  {
    $query = \Drupal::entityQuery('node');
    $query->sort('nid', 'DESC');
    $query->range(0, $nombre_item);
    $query->condition('status', 1);
    $query->condition('type', $bundle);
    $ids = $query->execute();
    return \Drupal::entityTypeManager()->getStorage('node')->loadMultiple($ids);
  }

  /**
   *
   * @param object $variables
   * @param string $group
   */
  public function loadSlideDatas(&$options, $group, $theme_name)
  {
    $results = [];
    $provider = theme_get_setting($group . '_provider', $theme_name);
    if ($provider == 'theme') {
      return false;
    }
    $index = intval(theme_get_setting($group . "nombre_slide", $theme_name));
    for ($i = 1; $i <= $index; $i ++) {
      $value = theme_get_setting($group . $i . 'texte', $theme_name);
      if (! empty($value)) {
        $value = $this->template_htmltag($value, 'div');
        $results[$i]['content'] = $this->templateCenterVertHori($value, 'bg-cover');
      }
      $fid = theme_get_setting($group . $i . 'image_bg', $theme_name);
      if (! empty($fid) && $fid[0] > 0) {
        $image_style = $theme_name . '_1620x1080';
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

  protected function remplissageAuto($refs, $valeurs)
  {
    foreach ($valeurs['lists'] as $key => $list) {
      if ($list['title'] == "") {
        $valeurs['lists'][$key]['title'] = $refs['lists'][$key]['title'];
        $valeurs['lists'][$key]['text'] = $refs['lists'][$key]['text'];
      }
    }
    return $valeurs;
  }
}