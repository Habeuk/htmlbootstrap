<?php

namespace Stephane888\HtmlBootstrap;

use Drupal\Core\Template\Attribute;

/**
 *
 * @deprecated delete before 4x wb_universe.
 * @author stephane
 *        
 */
class PreprocessMenu {
  
  public function links(&$variables, $theme_name) {
    if ($variables['theme_hook_original'] == 'links__language_block') {
      $this->links__language_block($variables, $theme_name);
    }
  }
  
  protected function links__language_block(&$variables, $theme_name) {
    $variables['attributes']['class'][] = 'select-langue';
    $variables['attributes']['class'][] = 'nav';
    foreach ($variables['links'] as $key => $link) {
      if ($key == 'fr') {
        $variables['links'][$key]['link']["#title"] = 'Fr';
      }
      if ($key == 'en') {
        $variables['links'][$key]['link']["#title"] = 'En';
      }
      $variables['links'][$key]['attributes']->addClass('nav-item');
      $variables['links'][$key]['link']['#options']['attributes']['class'][] = 'nav-link';
    }
  }
  
}