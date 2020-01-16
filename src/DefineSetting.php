<?php
namespace Stephane888\HtmlBootstrap;

use Stephane888\HtmlBootstrap\ThemeUtility;
use Drupal\Core\Theme\ThemeSettings;

class DefineSetting {

  public function form_imagetextrightleft(&$form, $group, $vertical_tabs_group, $themeName)
  {
    $cache = &drupal_static(__FUNCTION__, []);

    if (empty($cache[$themeName])) {
      // Create a theme settings object.
      $cache[$themeName] = new ThemeSettings($themeName);
      // Get the global settings from configuration.
      $cache[$themeName]->setData(\Drupal::config('system.theme.global')->get());
    }
    dump(\Drupal::config($themeName . '.settings')->get());
    $this->addSection($form, $group, 'Section Text Image Left/Right ', $vertical_tabs_group, $themeName);
    /**
     * build form to diplays.
     */
    $this->addFormDisplay($form, $group, $themeName);
  }

  protected function addFormDisplay(&$form, $group, $themeName, $label = 'Affichage')
  {
    $sous_group = 'displays';
    $ThemeUtility = new ThemeUtility();
    theme_get_setting($themeName . '_imagetextrightleft_status', $themeName);
    $form[$themeName . '_' . $group][$sous_group] = array(
      '#type' => 'details',
      '#title' => $label,
      '#open' => false,
      '#attributes' => [
        'class' => [
          'wbu-ui-state-default'
        ]
      ]
    );
    $name = 'testdff';
    $ThemeUtility->add_textfield2($name, $themeName . '_' . $group, $form[$themeName . '_' . $group][$sous_group], 'Nombre de slide', 5);
  }

  protected function addSection(&$form, $group, $label, $vertical_tabs_group, $themeName)
  {
    /**
     * add section
     */
    $form[$themeName . '_' . $group] = array(
      '#type' => 'details',
      '#title' => $label,
      "#tree" => true,
      '#group' => $vertical_tabs_group
    );
  }
}