<?php

namespace Stephane888\HtmlBootstrap;

use Stephane888\HtmlBootstrap\LoaderDrupal;

/**
 *
 * @author stephane
 * @deprecated delete in 4x wb_universe
 */
class SuggestionsMenus {
  
  public static function Suggestions($theme_hook_original, $options) {
    $suggestions = [];
    if ($theme_hook_original == 'menu__account') {
      $suggestions = SuggestionsMenus::Suggestions_menu__account($options);
    }
    elseif ($theme_hook_original == 'menu__main') {
      $suggestions = SuggestionsMenus::Suggestions_menu__main($options);
    }
    return $suggestions;
  }
  
  /**
   * Les themes disponible sont : ( ulisé par la variable TEMPLATE_menu__account
   * dans THEME/inc/config.inc ).
   * - 'menu__account_icon'
   *
   * @param array $options
   * @return string[]
   */
  protected static function Suggestions_menu__account($options) {
    $suggestions = [];
    if ($options['template'] == 'menu__account_icon') {
      $suggestions[] = 'menu__account_icon';
    }
    return $suggestions;
  }
  
  /**
   * Les themes disponible sont : ( ulisé par la variable TEMPLATE_menu__main
   * dans THEME/inc/config.inc ).
   * - 'menu__main_full'
   *
   * @param array $options
   * @return string[]
   */
  protected static function Suggestions_menu__main($options) {
    $suggestions = [];
    if ($options['template'] == 'menu__main_full') {
      // LoaderDrupal::addStyle(\file_get_contents(__DIR__ .
      // '/Sections/Menus/MenuCenter/style.scss'), 'menu__main_full');
      $suggestions[] = 'menu__main_full';
    }
    return $suggestions;
  }
  
}
