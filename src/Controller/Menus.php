<?php
namespace Stephane888\HtmlBootstrap\Controller;

class Menus {

  /**
   * Ajoute les icones sur les elements du menus.
   * il faut verifier le comportement pour les menus créer en admins. ( est que tout menu possede une route ? )
   */
  public static function MenuAddIcones($items, $icones = [])
  {
    foreach ($items as $key => $value) {
      // dump($value['url']->getRouteName());
      $routeName = $value['url']->getRouteName();
      if (! empty($icones[$routeName])) {
        $items[$key]['#icone'] = $icones[$routeName];
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
}