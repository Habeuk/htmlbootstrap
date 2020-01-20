<?php
namespace Stephane888\HtmlBootstrap;

use Drupal\Component\Utility\SortArray as DrupalSortArray;

class SortArray {

  public static function sortByWeightPropertyCustom($a, $b)
  {
    return DrupalSortArray::sortByKeyInt($a, $b, 'weight');
  }

  public static function _sortFieldOfTheme(&$values, $form_state, $theme_name, $parent = true)
  {
    foreach ($values as $key => $value) {
      /**
       * on verifie si le champs appartient au theme.
       */
      if ($parent) {
        if (strstr($key, $theme_name)) {
          /**
           * On ordonne si possible
           */
          if (is_array($value)) {
            /**
             * on odonne les enfants
             */
            static::_sortFieldOfTheme($value, $form_state, $theme_name, false);
            uasort($value, [
              SortArray::class,
              'sortByWeightPropertyCustom'
            ]);
            $values[$key] = $value;
            /**
             * Ne fonctionne pas pour les themes.
             */
            // $form_state->set($key, $value);
            $config = \Drupal::service('config.factory')->getEditable($theme_name . '.settings');
            $config->set($key, $value);
            $config->save();
            // dump($config->get($key));
          }
        }
      } else {
        /**
         * On ordonne si possible
         */
        if (is_array($value)) {
          /**
           * on ordonne les enfants
           */
          static::_sortFieldOfTheme($value, $form_state, $theme_name, false);

          uasort($value, [
            SortArray::class,
            'sortByWeightPropertyCustom'
          ]);
          $values[$key] = $value;
          if ('displays' == $key) {
            // dump($value);
          }
        }
      }
    }
  }
}