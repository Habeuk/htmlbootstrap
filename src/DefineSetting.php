<?php
namespace Stephane888\HtmlBootstrap;

use Stephane888\HtmlBootstrap\ThemeUtility;
use Stephane888\HtmlBootstrap\SortArray;
use Drupal\Core\Theme\ThemeSettings;
use Stephane888\HtmlBootstrap\Controller;
use Drupal\Component\Utility\Random;
use Drupal\debug_log\debugLog;

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

  protected $AjaxCallbackProvider;

  protected $AjaxWrapperProvider;

  protected $themeName;

  public $group;

  function __construct($themeName, $group = null)
  {
    $this->themeName = $themeName;
    $this->group = $group;
  }

  public function form_pricelists(&$form, $vertical_tabs_group, $form_state)
  {
    $group = $this->group;
    $themeName = $this->themeName;
    // $this->LoadConfigs($themeName);
    $this->init_form_pricelists($themeName, $group);
    $this->addSection($form, $group, 'Section price list', $vertical_tabs_group, $themeName);
    $this->addFormDisplay($form, $form_state);
  }

  protected function init_form_pricelists($themeName, $group)
  {
    $ThemeUtility = new ThemeUtility();
    $this->regions = $ThemeUtility->get_regions();
    $this->ListModels = Controller\PriceLists::listModels();
  }

  public function form_cards(&$form, $vertical_tabs_group, $form_state)
  {
    $group = $this->group;
    $themeName = $this->themeName;
    $this->init_form_cards($themeName, $group);
    $this->addSection($form, $group, 'Section cards ', $vertical_tabs_group, $themeName);
    $this->addFormDisplay($form, $form_state);
  }

  protected function init_form_cards($themeName, $group)
  {
    $ThemeUtility = new ThemeUtility();
    $this->regions = $ThemeUtility->get_regions();
    $this->ListModels = Controller\Cards::listModels();
  }

  protected function init_form_imagetextrightleft($themeName, $group)
  {
    $ThemeUtility = new ThemeUtility();
    $this->regions = $ThemeUtility->get_regions();
    $this->ListModels = Controller\ImageTextRightLeft::listModels();
  }

  public function form_imagetextrightleft(&$form, $vertical_tabs_group, $form_state)
  {
    // $this->LoadConfigs($themeName);
    /**
     * Les models pour l'affichage imagetextrightleft.
     *
     * @var \Stephane888\HtmlBootstrap\DefineSetting $ListModels
     */
    $group = $this->group;
    $themeName = $this->themeName;
    $this->init_form_imagetextrightleft($themeName, $group);
    $this->addSection($form, $group, 'Section Text Image Left/Right ', $vertical_tabs_group, $themeName);
    /**
     * build form to diplays.
     */
    $this->addFormDisplay($form, $form_state);
  }

  protected function addFormDisplay(&$form, $form_state, $label = 'Affichage')
  {
    $ThemeUtility = new ThemeUtility();
    $sous_group = 'displays';
    $group = $this->group;
    $themeName = $this->themeName;
    $values = theme_get_setting($themeName . '_' . $group, $themeName);
    // dump($values);
    $form[$themeName . '_' . $group][$sous_group] = array(
      '#type' => 'details',
      '#title' => $label,
      '#open' => true,
      '#attributes' => [
        'class' => [
          'wbu-ui-state-default'
        ],
        'id' => $themeName . '_' . $group . '_' . $sous_group
      ]
    );

    // debugLog::logs($form_state->getValue($themeName . '_' . $group), '_theme_builder_getValues_' . $group, 'dump', true);
    /**
     * Cas d'execution en ajax,
     * On recupere les valeurs en cours de l'enssemble des blocs.
     */
    $AjaxValue = $form_state->getValue($themeName . '_' . $group);

    $nbre_displays = $form_state->get('nbre_' . $group . '_displays');
    // debugLog::logs($nbre_imagetextrightleft_displays, 'theme_builder__nbre_imagetextrightleft_displays_interne', 'dump', true);
    if (empty($nbre_displays) && ! empty($values['displays'])) {
      $nbre_displays = $form_state->set('nbre_' . $group . '_displays', $values['displays']);
      $nbre_displays = $form_state->get('nbre_' . $group . '_displays');
    }

    /**
     * On parcours les affichages disponibles
     */
    $i = 0;
    if (! empty($nbre_displays))
      foreach ($nbre_displays as $key => $value) {
        $i ++;
        $this->AjaxWrapperProvider = $themeName . '_' . $group . '_' . $sous_group . $i;
        $this->AjaxCallbackProvider = '_' . $this->themeName . '_' . $this->group . '_provider';
        /**
         * Conteneur des elements d'affichage.
         */
        $form[$themeName . '_' . $group][$sous_group][$key] = [
          '#type' => 'details',
          '#title' => $label . ' : ' . $i,
          '#open' => false,
          '#attributes' => [ // 'id' => $this->AjaxWrapperProvider
          ]
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
            if ($k_field == 'route' && $value[$k_field] == 'entity.node.canonical') {
              // debugLog::logs($value, 'route_entity.node.canonical' . $i . '___', 'dump', true);
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
        /**
         * Champs pour les valeurs personnalisées
         */
        $form[$themeName . '_' . $group][$sous_group][$key]['options'] = [
          '#type' => 'details',
          '#title' => 'Champs personalisées',
          '#open' => true,
          '#attributes' => [
            'class' => [
              'sortable'
            ],
            'style' => 'display:none;',
            'id' => $this->AjaxWrapperProvider
          ]
        ];
        /**
         * Cas d'execution en ajax
         */
        if ($AjaxValue) {
          // on recupere la valeur encours de provider.
          $value['provider'] = $AjaxValue[$sous_group][$key]['provider'];
          $value['model'] = $AjaxValue[$sous_group][$key]['model'];
          // debugLog::logs([], '_theme_builder_' . $group . '__' . $sous_group . '-' . $key . '__' . $value['provider'], 'dump', true);
        }
        /**
         * On charge les champs personnalisés via le model selectionné.
         * Le provider doit etre custom
         */
        if (isset($value['model']) && isset($value['provider']) && $value['provider'] == 'custom') {
          $form[$themeName . '_' . $group][$sous_group][$key]['options']['#attributes']['style'] = 'display:block;';
          /**
           *
           * @var Ambiguous $options
           */
          $options = (empty($value['options'])) ? [] : $value['options'];
          $this->loadFieldsModels($value['model'], $group, $form[$themeName . '_' . $group][$sous_group][$key]['options'], $ThemeUtility, $options);
        }
        /**
         * Remove display
         */
        $form[$themeName . '_' . $group]['submit_remove'][$i]['submit'] = [
          '#type' => 'submit',
          '#value' => 'Retirer le bloc : ' . $i,
          '#weight' => 100,
          '#submit' => [
            '_' . $themeName . '_' . $group . '_' . $sous_group . '_ajax_submit_remove'
          ],
          '#ajax' => [
            'callback' => '_' . $themeName . '_' . $group . '_' . $sous_group . '_remove', // on va lire la fonction de return dans le THEMENAME.theme
            'disable-refocus' => FALSE, // Or TRUE to prevent re-focusing on the triggering element.
            'event' => 'click',
            'wrapper' => $themeName . '_' . $group . '_' . $sous_group, // This element is updated with this AJAX callback.
            'progress' => [
              'type' => 'throbber',
              'message' => 'Verifying entry...'
            ]
          ]
        ];
      }
    /**
     * On ajoute le boutons pour ajouter un nouveau bloc d'affichage.
     * NB: avec les button multiples, ils doivent avoir des noms differents('#value'), sinon c'est le systeme va buguer.
     */

    $form[$themeName . '_' . $group]['submit'] = [
      '#type' => 'submit',
      '#value' => 'Ajouter un bloc d\'affichage : ' . $group,
      // '#weight' => - 2,
      '#submit' => [
        '_' . $themeName . '_' . $group . '_' . $sous_group . '_ajax_submit'
      ],
      '#ajax' => [
        'callback' => '_' . $themeName . '_' . $group . '_' . $sous_group, // on va lire la fonction de return dans le THEMENAME.theme
        'disable-refocus' => FALSE, // Or TRUE to prevent re-focusing on the triggering element.
        'event' => 'click',
        'wrapper' => $themeName . '_' . $group . '_' . $sous_group, // This element is updated with this AJAX callback.
        'progress' => [
          'type' => 'throbber',
          'message' => 'Verifying entry...'
        ]
      ]
    ];
  }

  /**
   * Charge les champs pour le fournissuer custom.
   *
   * @param string $model
   * @param string $group
   * @param array $form
   */
  protected function loadFieldsModels($model, $group, &$form, $ThemeUtility, $options)
  {
    if ($group == 'imagetextrightleft') {
      // debugLog::logs([], '_theme_builder_' . $group . '__' . $model, 'dump', true);
      Controller\ImageTextRightLeft::loadFields($model, $form, $options);
    } elseif ($group == 'cards') {
      Controller\Cards::loadFields($model, $form, $options);
    } elseif ($group == 'pricelists') {
      Controller\PriceLists::loadFields($model, $form, $options);
    }
  }

  /**
   *
   * @param string $field
   * @param string $FieldValue
   * @param array $form
   * @param object $ThemeUtility
   */
  protected function addDisplayFields($field, $FieldValue, &$form, $ThemeUtility)
  {
    if ($field == 'route') {
      $ThemeUtility->addTextfieldTree($field, $form, 'Affiche sur cette route', $FieldValue);
    } elseif ($field == 'model') {
      $ThemeUtility->addSelectTree($field, $form, $this->ListModels, 'Model à utiliser', $FieldValue);
      $ThemeUtility->AddAjaxTree($field, $form, $this->AjaxCallbackProvider, $this->AjaxWrapperProvider);
    } elseif ($field == 'provider') {
      $ThemeUtility->addSelectTree($field, $form, $this->providers, 'Fournisseur de contenu', $FieldValue);
      $ThemeUtility->AddAjaxTree($field, $form, $this->AjaxCallbackProvider, $this->AjaxWrapperProvider);
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

  /**
   * Not work,
   * must deleded
   *
   * @param array $form
   */
  public function AjoutBlocAffichage(&$form, $group, $themeName, $sous_group, $label = 'Affichage')
  {
    $this->init_form_imagetextrightleft($themeName, $group);
    $ThemeUtility = new ThemeUtility();
    $rand = new Random();
    $i = $rand->name() . time();
    $form['theme_builder_' . $group][$sous_group][$i] = [
      '#type' => 'details',
      '#title' => $label . ' : ' . $i,
      '#open' => false
    ];
    foreach ($this->DisplaysFields as $k_field => $DefaultValue) {
      $this->addDisplayFields($k_field, $DefaultValue, $form['theme_builder_' . $group][$sous_group][$i], $ThemeUtility);
    }
  }

  public function AjoutBloc(&$displays)
  {
    // $group = $this->group;
    // $themeName = $this->themeName;
    // $this->init_form_imagetextrightleft($themeName, $group);
    $rand = new Random();
    $i = $rand->name() . time();
    $displays[$i] = $this->DisplaysFields;
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

  public function _sortFieldOfTheme(&$values, $parent = true)
  {
    foreach ($values as $key => $value) {
      /**
       * on verifie si le champs appartient au theme.
       */
      if ($parent) {
        if (strstr($key, 'theme_builder')) {
          /**
           * On ordonne si possible
           */
          if (is_array($value)) {
            /**
             * on odonne les enfants
             */
            _sortFieldOfTheme($value, false);
            uasort($value, [
              SortArray::class,
              'sortByWeightPropertyCustom'
            ]);
            $values[$key] = $value;
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
          _sortFieldOfTheme($value, false);

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