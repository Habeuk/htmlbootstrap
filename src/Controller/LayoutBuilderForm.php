<?php
namespace Stephane888\HtmlBootstrap\Controller;

use Stephane888\HtmlBootstrap\ThemeUtility;

class LayoutBuilderForm {

  public static function loadFields(array $layoutRegions, array &$form, array $options)
  {
    $ThemeUtility = new ThemeUtility();
    foreach ($layoutRegions as $key => $region) {
      if(isset($region['fieldtype']))
      switch ($region['fieldtype']) {
        case 'string':
          $FieldValue = (isset($options[$key])) ? $options[$key] : '';
          $ThemeUtility->addTextfieldTree($key, $form, $region['label']->render(), $FieldValue);
          break;

        case 'button':
          $FieldValue = (isset($options[$key])) ? $options[$key] : [];
          $ThemeUtility->addButtonTree($key, $form, $region['label']->render(), $FieldValue);
          break;

        case 'links':
          $FieldValue = (isset($options[$key])) ? $options[$key] : [];
          $ThemeUtility->addLinksTree($key, $form, $region['label']->render(), $FieldValue);
          break;

        default:
          ;
          break;
      }
      //
    }
  }
}