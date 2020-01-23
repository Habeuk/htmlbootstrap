<?php
namespace Stephane888\HtmlBootstrap;

use Drupal\Component\Utility\SortArray as DrupalSortArray;
use Drupal\debug_log\debugLog;

class SortArray {

  public static function sortByWeightPropertyCustom($a, $b)
  {
    return DrupalSortArray::sortByKeyInt($a, $b, 'weight');
  }

  public static function _sortFieldOfThemeAndSave(&$values, $form_state, $theme_name, $parent = true)
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
            static::_sortFieldOfThemeAndSave($value, $form_state, $theme_name, false);
            uasort($value, [
              SortArray::class,
              'sortByWeightPropertyCustom'
            ]);
            $values[$key] = $value;
            /**
             * Ne fonctionne pas pour les themes.
             */
            // $form_state->set($key, $value);
            $config = \Drupal::configFactory()->getEditable($theme_name . '.settings');
            $config->set($key, $value);
            $config->save();
            if ('theme_builder_pricelists' == $key) {
              debugLog::logs($config->get($key), 'Config_' . $key, 'dump', true);
            }
            // debugLog::logs($config->get($key), 'Config_' . $key, 'dump', true);
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
          static::_sortFieldOfThemeAndSave($value, $form_state, $theme_name, false);

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