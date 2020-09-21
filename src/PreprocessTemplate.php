<?php
namespace Stephane888\HtmlBootstrap;

use Drupal\Core\Template\Attribute;
use Drupal\debug_log\debugLog;

class PreprocessTemplate {

  public function Preprocess(&$variables, $hook, $theme_name)
  {
    if ($hook == 'static_image') {
      // dump($variables);
    } /**
     * Ce block est specifique au module views_bootstrap,
     * ce module n'offre pas la possibilité d'ajouter :
     * - les classes css
     * - on ne peut pas ajouter plusieurs champs dans une region,
     * - il ne lis pas les layputs disponibles dans les themes.
     * (de plus le template est mal concus, attributes n'est pas definie, d'ou obligation de creer un fichier template ).
     * Il faut creer un module pour palier à cela.
     */
    elseif ($hook == "views_bootstrap_cards" || $hook == 'view_formatter_layouts_default') {
      $variables['attributes']['class'][] = 'custom-card-group';
      // dump($variables);
    } elseif ($hook == 'maintenance_page') {
      $variables['page']['before_content']['static_image'][] = $this->buildMaintenancePage($theme_name);
      $variables['page']['before_content']['static_image']['#attached']['library'][] = $theme_name . '/maintenance_page';
      // dump($variables);
    } elseif ($hook == 'html' && isset($variables["page"]["#theme"]) && $variables["page"]["#theme"] == 'maintenance_page') {
      $variables['head_title']['title'] = t('Terremploi : opening in April 2020 !');
    } elseif ($hook == 'menu__main') {
      $values = theme_get_setting($theme_name . '_header', $theme_name);
      if (! empty($values['display']['options']['show_icone_home'])) {
        $variables['show_icone_home'] = true;
      } else {
        $variables['show_icone_home'] = false;
      }
    } elseif ($hook == 'block') {
      if ('webform_block' == $variables['base_plugin_id']) {
        if (isset($variables['label'])) {
          $variables['label'] = t($variables['label']);
        }
        // debugLog::logs($variables, 'block', 'kint_custom', true);
      }
    } elseif ($hook == 'form_terremploi') {
      $variables['content']['txt']['moreoption'] = t('More criteria');
    } elseif ($hook == 'ds_entity_view') {
      // dump($variables);
      // $variables['render_user'] = "Good";
    }
  }

  public static function CreateStyles($styles)
  {
    $ThemeUtility = new ThemeUtility();
    foreach ($styles as $key => $style) {
      if (! \Drupal\image\Entity\ImageStyle::load($key)) {
        $ThemeUtility->createStyleImage($key, $style['width'], $style['height'], $style['label']);
      }
    }
  }

  public static function loadAllStyleMedia()
  {
    return \Drupal::entityQuery('image_style')->execute();
  }

  protected function buildMaintenancePage($theme_name)
  {
    $wrapper_attribute = new Attribute();
    $img = [
      'img_url' => '/' . drupal_get_path('theme', $theme_name) . '/images/istock-172454785.jpg',
      'img_alt' => '',
      'img_class' => ''
    ];
    if (! empty($img['img_url'])) {
      $style = "background-image:url('" . $img['img_url'] . "')";
      $wrapper_attribute->setAttribute('style', $style);
      $wrapper_attribute->addClass([
        'lazyload'
      ]);
    }
    $sup_title = '';
    $sub_title = '<div class=""> <span class="d-inline-block pr-3"> ' . t('Follow us') . ' </span> ' . $this->textCustom() . '</div>';
    $sub_title = [
      '#type' => 'inline_template',
      '#template' => $sub_title
    ];
    $title = 'Terremploi :' . t(' opening in April 2020! ');
    return [
      '#theme' => 'static_image',
      '#img' => [],
      '#sup_title' => $sup_title,
      '#sub_title' => $sub_title,
      '#title' => $title,
      '#orther_vars' => [
        'type_tag' => 'h1'
      ],
      '#attributes' => $wrapper_attribute
    ];
  }

  protected function textCustom()
  {
    return '<div class="rx-test d-flex align-items-center justify-content-end">
    		<a href="https://linkedin.com/company/terremploi"><i class="fab fa-linkedin-in"></i></a>
    		<a href="https://fb.me/terremploi"><i class="fab fa-facebook-f"></i></a>
    </div>';
  }
}