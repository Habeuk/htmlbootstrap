<?php
namespace Stephane888\HtmlBootstrap\Controller;

use Drupal\Core\Menu\MenuTreeParameters;
use Drupal\system\Entity\Menu;

class Menus {

  /**
   * Ajoute les icones sur les elements du menus.
   * il faut verifier le comportement pour les menus créer en admins. ( est que tout menu possede une route ? ).
   */
  public static function MenuAddIcones($items, $icones = [])
  {
    foreach ($items as $key => $value) {
      $routeName = '';
      if ($value['url']->isRouted()) {
        $routeName = $value['url']->getRouteName();
      }
      if (! empty($icones[$routeName])) {
        $items[$key]['#icone'] = $icones[$routeName];
      }
      if (! empty($value['below'])) {
        $items[$key]['below'] = self::MenuAddIcones($value['below'], $icones);
      }
    }
    return $items;
  }

  /**
   * Permet de supprimer un item d'un menu à partir de la route
   */
  public static function MenuDeleteIemByRouteName($items, $route_name = null)
  {
    foreach ($items as $key => $value) {
      // dump($value['url']->getRouteName());
      if ($value['url']->getRouteName() == $route_name) {
        unset($items[$key]);
      }
    }
    return $items;
  }

  /**
   *
   * @param string $menu_name
   * @return array
   */
  public static function loadMenu($menu_name)
  {
    $MenuTreeParameters = new MenuTreeParameters();
    $menu_tree = \Drupal::menuTree();
    $menuTreeFooter = $menu_tree->load($menu_name, $MenuTreeParameters);
    return $menu_tree->build($menuTreeFooter);
  }

  /**
   * .
   */
  public static function getAllMenus()
  {
    $all_menus = Menu::loadMultiple();
    $menus = [];
    foreach ($all_menus as $id => $menu) {
      $menus[$id] = $menu->label();
    }
    asort($menus);
    return $menus;
  }
}