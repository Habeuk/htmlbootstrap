<?php
namespace Stephane888\HtmlBootstrap;

use Stephane888\HtmlBootstrap\ThemeUtility;
use Drupal\Core\Theme\ThemeSettings;
use Stephane888\HtmlBootstrap\Controller;

class DefineSetting {

  protected $DisplaysFields = [
    'route' => '',
    'model' => "",
    'provider' => 'theme',
    'region' => '',
    'weight' => 0,
    'status' => 1
  ];

  protected $ListModels;

  protected $providers = [
    'theme' => 'Theme (default)',
    'node' => 'Contenus',
    'custom' => 'Personnaliser'
  ];

  protected $regions;

  public function form_imagetextrightleft(&$form, $group, $vertical_tabs_group, $themeName)
  {
    // $this->LoadConfigs($themeName);
    /**
     * Les models pour l'affichage imagetextrightleft.
     *
     * @var \Stephane888\HtmlBootstrap\DefineSetting $ListModels
     */
    $this->ListModels = Controller\ImageTextRightLeft::listModels();
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
    $this->regions = $ThemeUtility->get_regions();
    $values = theme_get_setting($themeName . '_' . $group, $themeName);
    // dump($values);
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
    /**
     * on parcours les affichages disponibles
     */
    $i = 0;
    foreach ($values['displays'] as $key => $value) {
      $i ++;
      /**
       * Conteneur des elements d'affichage.
       */
      $form[$themeName . '_' . $group][$sous_group][$key] = [
        '#type' => 'details',
        '#title' => $label . ' : ' . $i,
        '#open' => true
      ];
      /**
       * Affichage des champs.
       */
      foreach ($this->DisplaysFields as $k_field => $DefaultValue) {
        if (isset($value[$k_field])) {
          $this->addDisplayFields($k_field, $value[$k_field], $form[$themeName . '_' . $group][$sous_group][$key], $ThemeUtility);
          /**
           * Cas specifique.
           * Si cest un node, on ajoute deux champs, pour le type et nid.
           */
          if ($value[$k_field] == 'entity.node.canonical') {
            /**
             * Nid field
             */
            $nid = (isset($value['nid'])) ? $value['nid'] : '';
            $this->addDisplayFields('nid', $nid, $form[$themeName . '_' . $group][$sous_group][$key], $ThemeUtility);
          }
        } else {
          $this->addDisplayFields($k_field, $DefaultValue, $form[$themeName . '_' . $group][$sous_group][$key], $ThemeUtility);
        }
      }
    }
  }

  protected function addDisplayFields($field, $FieldValue, &$form, $ThemeUtility)
  {
    if ($field == 'route') {
      $ThemeUtility->addTextfieldTree($field, $form, 'Affiche sur cette route', $FieldValue);
    } elseif ($field == 'model') {
      $ThemeUtility->addSelectTree($field, $form, $this->ListModels, 'Model à utiliser', $FieldValue);
    } elseif ($field == 'provider') {
      $ThemeUtility->addSelectTree($field, $form, $this->providers, 'Fournisseur de contenu', $FieldValue);
    } elseif ($field == 'weight') {
      $ThemeUtility->addTextfieldTree($field, $form, 'Position de la section', $FieldValue);
    } elseif ($field == 'nid') {
      $ThemeUtility->addTextfieldTree($field, $form, 'id du contenu', $FieldValue);
      $ThemeUtility->AddRequireTree($field, $form);
    } elseif ($field == 'region') {
      $FieldValue = (empty($FieldValue)) ? 'before_content' : $FieldValue;
      $ThemeUtility->addSelectTree($field, $form, $this->regions, 'Selectionner la region', $FieldValue);
      $ThemeUtility->AddRequireTree($field, $form);
    }
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

  private function LoadConfigs($themeName)
  {
    $cache = &drupal_static(__FUNCTION__, []);

    if (empty($cache[$themeName])) {
      // Create a theme settings object.
      $cache[$themeName] = new ThemeSettings($themeName);
      // Get the global settings from configuration.
      $cache[$themeName]->setData(\Drupal::config('system.theme.global')->get());
    }
    dump(\Drupal::config($themeName . '.settings')->get());
  }
}