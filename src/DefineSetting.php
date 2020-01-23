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

  protected $theme_name;

  public $group;

  function __construct($theme_name, $group = null)
  {
    $this->themeName = $theme_name;
    $this->group = $group;
  }

  public function form_stylepage(&$form, $vertical_tabs_group, $form_state)
  {
    $group = $this->group;
    $theme_name = $this->themeName;
    $ThemeUtility = new ThemeUtility();
    $this->regions = $ThemeUtility->get_regions();
    $this->ListModels = Controller\StylePage::listModels();
    $this->addSection($form, $group, 'Styles CSS & JS', $vertical_tabs_group, $theme_name, 100);
    $this->addFormDisplay($form, $form_state);
  }

  public function form_comments(&$form, $vertical_tabs_group, $form_state)
  {
    $group = $this->group;
    $theme_name = $this->themeName;
    $this->init_form_comments($theme_name, $group);
    $this->addSection($form, $group, 'Comments', $vertical_tabs_group, $theme_name);
    $this->addFormDisplay($form, $form_state);
  }

  public function init_form_comments($theme_name, $group)
  {
    $ThemeUtility = new ThemeUtility();
    $this->regions = $ThemeUtility->get_regions();
    $this->ListModels = Controller\Comments::listModels();
  }

  public function form_pricelists(&$form, $vertical_tabs_group, $form_state)
  {
    $group = $this->group;
    $theme_name = $this->themeName;
    // $this->LoadConfigs($theme_name);
    $this->init_form_pricelists($theme_name, $group);
    $this->addSection($form, $group, 'Section price list', $vertical_tabs_group, $theme_name);
    $this->addFormDisplay($form, $form_state);
  }

  protected function init_form_pricelists($theme_name, $group)
  {
    $ThemeUtility = new ThemeUtility();
    $this->regions = $ThemeUtility->get_regions();
    $this->ListModels = Controller\PriceLists::listModels();
  }

  public function form_cards(&$form, $vertical_tabs_group, $form_state)
  {
    $group = $this->group;
    $theme_name = $this->themeName;
    $this->init_form_cards($theme_name, $group);
    $this->addSection($form, $group, 'Section cards ', $vertical_tabs_group, $theme_name);
    $this->addFormDisplay($form, $form_state);
  }

  protected function init_form_cards($theme_name, $group)
  {
    $ThemeUtility = new ThemeUtility();
    $this->regions = $ThemeUtility->get_regions();
    $this->ListModels = Controller\Cards::listModels();
  }

  protected function init_form_imagetextrightleft($theme_name, $group)
  {
    $ThemeUtility = new ThemeUtility();
    $this->regions = $ThemeUtility->get_regions();
    $this->ListModels = Controller\ImageTextRightLeft::listModels();
  }

  public function form_imagetextrightleft(&$form, $vertical_tabs_group, $form_state)
  {
    // $this->LoadConfigs($theme_name);
    /**
     * Les models pour l'affichage imagetextrightleft.
     *
     * @var \Stephane888\HtmlBootstrap\DefineSetting $ListModels
     */
    $group = $this->group;
    $theme_name = $this->themeName;
    $this->init_form_imagetextrightleft($theme_name, $group);
    $this->addSection($form, $group, 'Section Text Image Left/Right ', $vertical_tabs_group, $theme_name);
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
    $theme_name = $this->themeName;
    $values = theme_get_setting($theme_name . '_' . $group, $theme_name);
    // dump($values);
    $form[$theme_name . '_' . $group][$sous_group] = array(
      '#type' => 'details',
      '#title' => $label,
      '#open' => true,
      '#attributes' => [
        'class' => [
          'wbu-ui-state-default'
        ],
        'id' => $theme_name . '_' . $group . '_' . $sous_group
      ]
    );

    // debugLog::logs($form_state->getValue($theme_name . '_' . $group), '_theme_builder_getValues_' . $group, 'dump', true);
    /**
     * Cas d'execution en ajax,
     * On recupere les valeurs en cours de l'enssemble des blocs.
     */
    $AjaxValue = $form_state->getValue($theme_name . '_' . $group);

    /**
     * On vide les boutons de sumittions.
     * Cela permet en mode ajax de ne pas renvoyer les boutons dont les perents ont eté supprimée.
     */
    $form[$theme_name . '_' . $group]['submit_remove'] = [];

    /**
     *
     * @var $nbre_displays
     */
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
        $this->AjaxWrapperProvider = $theme_name . '_' . $group . '_' . $sous_group . $i;
        $this->AjaxCallbackProvider = '_' . $this->themeName . '_' . $this->group . '_provider';
        /**
         * Conteneur des elements d'affichage.
         */
        $form[$theme_name . '_' . $group][$sous_group][$key] = [
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
            $this->addDisplayFields($k_field, $value[$k_field], $form[$theme_name . '_' . $group][$sous_group][$key], $ThemeUtility);
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
              $this->addDisplayFields('nid', $nid, $form[$theme_name . '_' . $group][$sous_group][$key], $ThemeUtility);
            }
          } else {
            $this->addDisplayFields($k_field, $DefaultValue, $form[$theme_name . '_' . $group][$sous_group][$key], $ThemeUtility);
          }
        }
        /**
         * Champs pour les valeurs personnalisées.
         */
        $form[$theme_name . '_' . $group][$sous_group][$key]['options'] = [
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
          $form[$theme_name . '_' . $group][$sous_group][$key]['options']['#attributes']['style'] = 'display:block;';
          /**
           *
           * @var Ambiguous $options
           */
          $options = (empty($value['options'])) ? [] : $value['options'];
          $this->loadFieldsModels($value['model'], $group, $form[$theme_name . '_' . $group][$sous_group][$key]['options'], $ThemeUtility, $options);
        }
        /**
         * Remove display
         */
        $form[$theme_name . '_' . $group]['submit_remove'][$i]['submit'] = [
          '#type' => 'submit',
          '#value' => 'Retirer le bloc : ' . $group . ' : ' . $i,
          '#weight' => 100,
          '#custom_key' => $key,
          '#submit' => [
            '_' . $theme_name . '_' . $group . '_' . $sous_group . '_ajax_submit_remove'
          ],
          '#ajax' => [
            'callback' => '_' . $theme_name . '_' . $group . '_' . $sous_group . '_remove', // on va lire la fonction de return dans le THEMENAME.theme
            'disable-refocus' => FALSE, // Or TRUE to prevent re-focusing on the triggering element.
            'event' => 'click',
            'wrapper' => $theme_name . '_' . $group . '_' . $sous_group, // This element is updated with this AJAX callback.
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
    $form[$theme_name . '_' . $group]['submit'] = [
      '#type' => 'submit',
      '#value' => 'Ajouter un bloc d\'affichage : ' . $group,
      '#weight' => - 2,
      '#submit' => [
        '_' . $theme_name . '_' . $group . '_' . $sous_group . '_ajax_submit'
      ],
      '#ajax' => [
        'callback' => '_' . $theme_name . '_' . $group . '_' . $sous_group, // on va lire la fonction de return dans le THEMENAME.theme
        'disable-refocus' => FALSE, // Or TRUE to prevent re-focusing on the triggering element.
        'event' => 'click',
        'wrapper' => $theme_name . '_' . $group . '_' . $sous_group, // This element is updated with this AJAX callback.
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
    } elseif ($group == 'comments') {
      Controller\Comments::loadFields($model, $form, $options);
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
  public function AjoutBlocAffichage(&$form, $group, $theme_name, $sous_group, $label = 'Affichage')
  {
    $this->init_form_imagetextrightleft($theme_name, $group);
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
    // $theme_name = $this->themeName;
    // $this->init_form_imagetextrightleft($theme_name, $group);
    $rand = new Random();
    $i = $rand->name() . time();
    $displays[$i] = $this->DisplaysFields;
  }

  protected function addSection(&$form, $group, $label, $vertical_tabs_group, $theme_name, $weight = 0)
  {
    /**
     * add section
     */
    $form[$theme_name . '_' . $group] = array(
      '#type' => 'details',
      '#title' => $label,
      "#tree" => true,
      '#group' => $vertical_tabs_group,
      '#weight' => $weight
    );
  }

  private function LoadConfigs($theme_name)
  {
    $cache = &drupal_static(__FUNCTION__, []);
    if (empty($cache[$theme_name])) {
      // Create a theme settings object.
      $cache[$theme_name] = new ThemeSettings($theme_name);
      // Get the global settings from configuration.
      $cache[$theme_name]->setData(\Drupal::config('system.theme.global')->get());
    }
    // dump(\Drupal::config($theme_name . '.settings')->get());
    $config = \Drupal::service('config.factory')->getEditable($theme_name . '.settings');
    dump([
      theme_get_setting($theme_name . '_' . $this->group, $theme_name),
      $config->get($theme_name . '_' . $this->group),
      \Drupal::config('system.theme.global')->get(),
      \Drupal::service('theme_handler')->listInfo()
    ]);
  }
}