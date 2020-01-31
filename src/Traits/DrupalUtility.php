<?php
namespace Stephane888\HtmlBootstrap\Traits;

// use Drupal\Core\Template\Attribute;
use Drupal\debug_log\debugLog;

trait DrupalUtility {

  use Portions;

  public function loadHeaderDatas(&$options, $group, $theme_name, $display, &$variables)
  {
    if ('logo_center' == $options['type']) {
      /**
       * On recupere le tableau du branding.
       */
      if (isset($variables['page']['header'][$theme_name . '_branding'])) {
        $options['branding'] = $variables['page']['header'][$theme_name . '_branding'];
        unset($variables['page']['header'][$theme_name . '_branding']);
      }
      /**
       * On recupere le menu des liens.
       */
      if (isset($variables['page']['header'][$theme_name . '_account_menu'])) {
        $options['account_menu'] = $variables['page']['header'][$theme_name . '_account_menu'];
        unset($variables['page']['header'][$theme_name . '_account_menu']);
      }
    } elseif ($options['type'] == 'LogoLeftMenu') {

      /**
       * On recupere le tableau du branding.
       */
      if (isset($variables['page']['header'][$theme_name . '_branding'])) {
        $options['branding'] = $variables['page']['header'][$theme_name . '_branding'];
        unset($variables['page']['header'][$theme_name . '_branding']);
      }
      /**
       * Get main menu
       */
      if (isset($variables['page']['header'][$theme_name . '_main_menu'])) {
        $options['main_menu'] = $variables['page']['header'][$theme_name . '_main_menu'];
        unset($variables['page']['header'][$theme_name . '_main_menu']);
      }
      /**
       * get search
       */
      if (isset($variables['page']['header'][$theme_name . '_search'])) {
        $options['search'] = $variables['page']['header'][$theme_name . '_search'];
        unset($variables['page']['header'][$theme_name . '_search']);
      }
    } elseif ($options['type'] == 'LogoLeftMenuRight_M1') {
      /**
       * On recupere le tableau du branding.
       */
      if (isset($variables['page']['header'][$theme_name . '_branding'])) {
        $options['branding'] = $variables['page']['header'][$theme_name . '_branding'];
        unset($variables['page']['header'][$theme_name . '_branding']);
      }
      /**
       * Get main menu
       */
      if (isset($variables['page']['header'][$theme_name . '_main_menu'])) {
        $options['main_menu'] = $variables['page']['header'][$theme_name . '_main_menu'];
        unset($variables['page']['header'][$theme_name . '_main_menu']);
      }
    } //
    elseif ($options['type'] == 'RxLeftMenuRight_M1') {
      /**
       * On recupere le tableau du branding.
       */
      if (isset($variables['page']['header'][$theme_name . '_branding'])) {
        $options['branding'] = $this->template_rx_logos($display['options'], 'circle_animate');
        unset($variables['page']['header'][$theme_name . '_branding']);
      }
      /**
       * Get main menu
       */
      if (isset($variables['page']['header'][$theme_name . '_main_menu'])) {
        $options['main_menu'] = $variables['page']['header'][$theme_name . '_main_menu'];
        unset($variables['page']['header'][$theme_name . '_main_menu']);
      }
    }
  }

  public function loadFootersDatas(&$options, $group, $theme_name, $display)
  {
    $provider = $display['provider'];
    if ($provider == 'theme') {
      return;
    } elseif ($provider == 'custom') {
      $options['cards'] = [];
      $options['card_class_block'] = $display['card_class_block'];
      foreach ($display['cards'] as $card) {
        if ($card['model'] == 'texte' && $card['provider'] == 'custom') {
          $options['cards'][] = [
            'title' => $card['options']['title'],
            'text' => $card['options']['description']
          ];
        } elseif ($card['model'] == 'fb-page-plugin') {
          $options['cards'][] = [
            'title' => $card['options']['title'],
            'text' => $this->template_fb_page_plugin($card['options'])
          ];
        }
      }
      // $options = \array_merge($options, $display['options']);
    }
  }

  public function loadStylePage(&$options, $group, $theme_name, $display)
  {
    if ($display['provider']) {
      // $options = \array_merge($options, $display['options']);
      ;
    }
  }

  public function loadComments(&$options, $group, $theme_name, $display)
  {
    if ($display['provider'] == 'custom') {
      $options = \array_merge($options, $display['options']);
    }
  }

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
  public function loadCarouselCardsDatas(&$options, $group, $theme_name, $display)
  {
    $provider = $display['provider'];
    if ($provider == 'theme') {
      return;
    } elseif ($provider == 'custom') {
      $options = \array_merge($options, $display['options']);
    } elseif ($provider == 'node') {

      if ($options['type'] == 'Modele1') {
        $options['title'] = $display['options']['title'];
        $options['txt_link'] = $display['options']['txt_link'];
        $options['link'] = $display['options']['link'];
        $options['nombre_item'] = $nombre_item = $display['options']['nombre_item'];
        $bundle = $display['options']['content_type'];
        $nodes = $this->getNodes($bundle, $nombre_item);
        $options['cards'] = [];
        $i = 0;
        foreach ($nodes as $node) {
          // debugLog::logs($node, 'loadCardsDatas ' . $options['type'], 'kint', true);
          foreach ($display['options']['cards'] as $key => $field) {
            if ($node->hasField($field)) {
              $options['cards'][$i][$key] = $node->{$field}->view('carousel');
            } else {
              $options['cards'][$i][$key] = $field;
            }
          }
          $options['cards'][$i]['link'] = $node->toUrl()->toString();
          $options['cards'][$i]['date'] = $node->get('created')->view();
          // ->get('created')->value;
          $i ++;
        }
      }
    }
  }

  public function loadCallActionsDatas(&$options, $group, $theme_name, $display)
  {
    $provider = $display['provider'];
    if ($provider == 'custom') {
      $options = \array_merge($options, $display['options']);
    }
  }

  public function loadCardsDatas(&$options, $group, $theme_name, $display)
  {
    $results = [];
    $provider = $display['provider'];
    if ($provider == 'theme') {
      return false;
    } elseif ($provider == 'node') {
      if ($options['type'] == 'CardsModel3') {
        $options['title'] = $display['options']['title'];
        $options['description'] = $display['options']['description'];
        $options['message'] = $display['options']['message'];
        $bundle = $display['options']['content_type'];
        $nombre_item = $display['options']['nombre_item'];
        $nodes = $this->getNodes($bundle, $nombre_item);
        $options['cards'] = [];
        // dump($display['options']['cards']);
        $i = 0;
        foreach ($nodes as $node) {
          // debugLog::logs($node, 'loadCardsDatas ' . $options['type'], 'kint', true);
          foreach ($display['options']['cards'] as $key => $field) {
            if ($node->hasField($field)) {
              // debugLog::logs($node->get($field), 'getfield__loadCardsDatas ' . $options['type'], 'kint', true);
              // $options['cards'][$i][$key] = $node->get($field)->view([]);
              $options['cards'][$i][$key] = $node->{$field}->view('carousel');
            } else {
              $options['cards'][$i][$key] = $field;
            }
          }

          $i ++;
        }
      } elseif (! empty($display['content_type'])) {
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