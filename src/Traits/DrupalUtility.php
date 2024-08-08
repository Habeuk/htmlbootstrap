<?php
namespace Stephane888\HtmlBootstrap\Traits;

// use Drupal\Core\Template\Attribute;
use Stephane888\Debug\debugLog;
use Stephane888\HtmlBootstrap\Controller\Cards;

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
    } elseif ($options['type'] == 'LogoLeftMenuRight_M2') {
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
       * Get orther datas:
       */
      if (isset($display['options'])) {
        if (! empty($display['options']['block_right'])) {
          $options['data_right'] = $display['options']['block_right'];
        }
        $options['static_menu'] = (isset($display['options']['static_menu'])) ? $display['options']['static_menu'] : 1;
        $options['menu_bellow_content'] = (isset($display['options']['menu_bellow_content'])) ? $display['options']['menu_bellow_content'] : 0;
        $options['show_right'] = (isset($display['options']['show_right'])) ? $display['options']['show_right'] : 1;
      }
      // dump($display, $options);
    }
  }

  public function loadTopHeaderDatas(&$options, $group, $theme_name, $display)
  {
    // debugLog::kintDebugDrupal($display, "DrupalUtility::loadTopHeaderDatas");
    /**
     * $Options doit correspondre.
     */
    if (! empty($display['layout']['fields']) && ! empty($display['provider']) && $display['provider'] == 'layout') {
      $options = $this->renderLayout($display['layout']['typelayout'], $display['layout']['fields']);
    }
  }

  /**
   * Genere le rendu du layout à partir des informations stockes dans le theme.
   */
  public function renderLayout(String $typelayout, array $regionsValues)
  {
    $render = [];
    $layoutPluginManager = \Drupal::service('plugin.manager.core.layout');
    if ($layoutPluginManager->hasDefinition($typelayout)) {
      $layoutInstance = $layoutPluginManager->createInstance($typelayout);
      $regions = $layoutInstance->getPluginDefinition()->getRegions();
      foreach ($regions as $region => $infos) {
        if (isset($regionsValues[$region])) {
          if ($infos['fieldtype'] == 'string') {
            $render[$region] = [
              '#markup' => $regionsValues[$region]
            ];
          } else {
            $render[$region] = $regionsValues[$region];
          }
        }
      }
      return $layoutInstance->build($render);
    }
    return $render;
  }

  /**
   *
   * @param array $options
   * @param string $group
   * @param string $theme_name
   * @param array $display
   */
  public function loadFootersDatas(&$options, $group, $theme_name, $display)
  {
    $provider = $display['provider'];
    if ($provider == 'theme') {
      return;
    } elseif ($provider == 'custom') {
      if ($display['model'] == 'footerm1') {
        $options['cards'] = [];
        $options['card_class_block'] = $display['card_class_block'];
        $options = \array_merge($options, $display);

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
          } elseif ($card['model'] == 'menu') {
            $options['cards'][] = [
              'title' => $card['options']['title'],
              'text' => self::loadMenu($card['options']['description'])
            ];
          } elseif ($card['model'] == 'PostsVerticalM1' && $card['provider'] == 'node') {
            // $Cards = new ;
            // new \Stephane888\HtmlBootstrap\Controller\Cards Cards();
            // \Stephane888\HtmlBootstrap\Controller\Cards::

            $Cards = new \Stephane888\HtmlBootstrap\Controller\Cards($this->BasePath);
            $new_option = [
              'cards' => $this->loadFieldsDatas($this->getNodes($card['options']['contenttype'], $card['options']['nombre_item']), $card['options']['cards'])
            ];
            $new_option['type'] = 'PostsVerticalM1';

            $options['cards'][] = [
              'title' => $card['options']['title'],
              'text' => $Cards->loadFile($new_option)
            ];
          }
        }
        // $options = \array_merge($options, $display['options']);
      } elseif ($display['model'] == 'FooterMenuRx') {
        $options['footer_menu'] = [
          'menu_select' => $display['options']['menu_select'],
          'status_rx' => $display['options']['status_rx'],
          'rx_position' => $display['options']['rx_position'],
          'rx_model' => $display['options']['rx_model'],
          'rx_logos' => $display['options']['rx_logos']
        ];
        $options['end_left'] = $display['options']['end_left'];
        $options['end_right'] = $display['options']['end_right'];
        $languagecode = \Drupal::languageManager()->getCurrentLanguage()->getId();
        if ($languagecode) {
          $options['end_right_link'] = '/' . $languagecode . $display['options']['end_right_link'];
          ;
        } else {
          $options['end_right_link'] = $display['options']['end_right_link'];
        }
      }
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
    $provider = $display['provider'];
    if ($provider == 'custom') {
      $options = \array_merge($options, $display['options']);
    } elseif ($provider == 'node') {
      if ($display['model'] == 'content_text') {
        $bundle = $display['options']['content_type'];
        $bundle = $display['options']['content_type'];
        $node = \Drupal\node\Entity\Node::load($display['options']['nid']); // \Drupal::entityTypeManager()->getStorage('node')->load($display['options']['nid']);
        $field = $display['options']['text'];
        if ($node && $bundle == $node->bundle() && ($node->hasField($field))) {
          $language = \Drupal::languageManager()->getCurrentLanguage()->getId();
          if ($language) {
            $translation = $node->getTranslation($language);
            $options['text'] = $translation->{$field}->view([
              'label' => 'hidden'
            ]);
          } else {
            $options['text'] = $node->{$field}->view([
              'label' => 'hidden'
            ]);
          }
        }
      }
    }
  }

  protected function loadFieldsDatas($nodes, &$options)
  {
    $results = [];
    foreach ($nodes as $node) {
      $results[] = $this->loadFieldsNode($node, $options);
    }
    return $results;
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

  public function loadSlideDatas(&$options, $group, $theme_name, $display)
  {
    $provider = $display['provider'];
    if ($provider == 'theme') {
      return false;
    } elseif ($provider == 'custom') {
      $options['provider'] = $display['provider'];
      $options = \array_merge($options, $display['options']);
    }
  }

  /**
   *
   * @param object $variables
   * @param string $group
   */
  public function loadSlideDatas_0(&$options, $group, $theme_name)
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