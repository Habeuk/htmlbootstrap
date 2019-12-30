<?php
namespace Stephane888\HtmlBootstrap;

class SuggestionsMenus {

  public static function Suggestions($theme_hook_original, $options)
  {
    $suggestions = [];
    if ($theme_hook_original == 'menu__account') {
      $suggestions = SuggestionsMenus::Suggestions_menu__account($options);
    }
    return $suggestions;
  }

  /**
   * Les themes disponible sont : ( ulisé par la variable TEMPLATE_menu__account dans THEME/inc/config.inc ).
   * - 'menu__account_icon'
   *
   * @param array $options
   * @return string[]
   */
  protected static function Suggestions_menu__account($options)
  {
    $suggestions = [];
    if ($options['template'] == 'menu__account_icon') {
      $suggestions[] = 'menu__account_icon';
    }
    return $suggestions;
  }
}
