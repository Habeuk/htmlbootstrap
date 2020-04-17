<?php
namespace Stephane888\HtmlBootstrap;

use Stephane888\HtmlBootstrap\ThemeUtility;
use Stephane888\HtmlBootstrap\SortArray;
use Drupal\Core\Theme\ThemeSettings;
use Stephane888\HtmlBootstrap\Controller;
use Drupal\Component\Utility\Random;
use Drupal\debug_log\debugLog;
use Stephane888\HtmlBootstrap\Traits\Portions;

class DefineSetting {
  use Portions;

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

  protected $routes = [
    'view.frontpage.page_1' => "page d'accueil",
    'entity.node.canonical' => "page de node (article)",
    '' => 'Toutes les pages'
  ];

  public $group;

  function __construct($theme_name, $group = null)
  {
    $this->themeName = $theme_name;
    $this->group = $group;
  }

  public function form_topheader(&$form, $vertical_tabs_group, $form_state)
  {
    $group = $this->group;
    $theme_name = $this->themeName;
    $ThemeUtility = new ThemeUtility();
    $this->regions = $ThemeUtility->get_regions();
    $this->ListModels = Controller\TopHeaders::listModels();
    $this->addSection($form, $group, 'Top Entetes', $vertical_tabs_group, $theme_name, - 1);

    $this->addTopHeaderDisplay($form, $form_state);
  }

  public function form_header(&$form, $vertical_tabs_group, $form_state)
  {
    $group = $this->group;
    $theme_name = $this->themeName;
    $ThemeUtility = new ThemeUtility();
    $this->regions = $ThemeUtility->get_regions();
    $this->ListModels = Controller\Headers::listModels();
    $this->addSection($form, $group, 'Entetes', $vertical_tabs_group, $theme_name, - 1);

    $this->addHeaderDisplay($form, $form_state);
  }

  public function form_pagenodesdisplay(&$form, $vertical_tabs_group, $form_state)
  {
    $group = $this->group;
    $theme_name = $this->themeName;
    $ThemeUtility = new ThemeUtility();
    $this->regions = $ThemeUtility->get_regions();
    $this->ListModels = Controller\PageNodesDisplay::listModels();
    $this->addSection($form, $group, 'Page de contenu', $vertical_tabs_group, $theme_name, 99);

    // $this->addPagenodesdisplayDisplay($form, $form_state);
    $model = '';
    $values = theme_get_setting($theme_name . '_' . $group, $theme_name);
    Controller\PageNodesDisplay::loadFields($model, $form[$theme_name . '_' . $group], $values);
  }

  public function form_footers(&$form, $vertical_tabs_group, $form_state)
  {
    $group = $this->group;
    $theme_name = $this->themeName;
    $ThemeUtility = new ThemeUtility();
    $this->regions = $ThemeUtility->get_regions();
    $this->ListModels = Controller\Footers::listModels();
    $this->addSection($form, $group, 'Footers', $vertical_tabs_group, $theme_name, 100);

    $this->addFootersDisplay($form, $form_state);
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

  public function form_slide(&$form, $vertical_tabs_group, $form_state)
  {
    $group = $this->group;
    $theme_name = $this->themeName;
    $this->init_form_slide($theme_name, $group);
    $this->addSection($form, $group, 'Slides', $vertical_tabs_group, $theme_name);
    $this->addFormDisplay($form, $form_state);
  }

  protected function init_form_slide($theme_name, $group)
  {
    $ThemeUtility = new ThemeUtility();
    $this->regions = $ThemeUtility->get_regions();
    $this->ListModels = Controller\Sliders::listModels();
  }

  public function form_carouselcards(&$form, $vertical_tabs_group, $form_state)
  {
    $group = $this->group;
    $theme_name = $this->themeName;
    $this->init_form_carouselcards($theme_name, $group);
    $this->addSection($form, $group, 'Carousel cards', $vertical_tabs_group, $theme_name);
    $this->addFormDisplay($form, $form_state);
  }

  protected function init_form_carouselcards($theme_name, $group)
  {
    $ThemeUtility = new ThemeUtility();
    $this->regions = $ThemeUtility->get_regions();
    $this->ListModels = Controller\CarouselCards::listModels();
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
    // dump($form);
  }

  protected function addTopHeaderDisplay(&$form, $form_state, $label = 'Affichage')
  {
    $ThemeUtility = new ThemeUtility();
    $sous_group = 'display';
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

    foreach ($this->DisplaysFields as $k_field => $DefaultValue) {
      if ($k_field == 'model' || $k_field == 'region' || $k_field == 'weight' || $k_field == 'status' || $k_field == 'provider') {
        $this->AjaxWrapperProvider = $theme_name . '_' . $group . '_' . $sous_group;
        $this->AjaxCallbackProvider = '_' . $this->themeName . '_' . $this->group . '_provider';
        if (isset($values[$sous_group][$k_field])) {
          $this->addDisplayFields($k_field, $values[$sous_group][$k_field], $form[$theme_name . '_' . $group][$sous_group], $ThemeUtility);
        } else {
          $this->addDisplayFields($k_field, $DefaultValue, $form[$theme_name . '_' . $group][$sous_group], $ThemeUtility);
        }
      }
    }
    //
    if (isset($values['display']['model'])) {
      $model = $values['display']['model'];
      $values = theme_get_setting($theme_name . '_' . $group, $theme_name);
      Controller\TopHeaders::loadFields($model, $form[$theme_name . '_' . $group][$sous_group], $values);
    }
  }

  /**
   *
   * @param object $form
   * @param object $form_state
   * @param string $label
   */
  protected function addHeaderDisplay(&$form, $form_state, $label = 'Affichage')
  {
    $ThemeUtility = new ThemeUtility();
    $sous_group = 'display';
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

    foreach ($this->DisplaysFields as $k_field => $DefaultValue) {
      if ($k_field == 'model' || $k_field == 'region' || $k_field == 'weight' || $k_field == 'status') {
        $this->AjaxWrapperProvider = $theme_name . '_' . $group . '_' . $sous_group;
        $this->AjaxCallbackProvider = '_' . $this->themeName . '_' . $this->group . '_provider';
        if (isset($values[$sous_group][$k_field])) {
          $this->addDisplayFields($k_field, $values[$sous_group][$k_field], $form[$theme_name . '_' . $group][$sous_group], $ThemeUtility);
        } else {
          $this->addDisplayFields($k_field, $DefaultValue, $form[$theme_name . '_' . $group][$sous_group], $ThemeUtility);
        }
      }
    }
    if (isset($values[$sous_group]['model'])) {
      if ($values[$sous_group]['model'] == 'RxLeftMenuRight_M1') {
        $rx_logos = $this->getdefault_rx_logos();
        /**
         * Nombre de bloc
         */
        $name = "nombre_item";
        $FieldValue = $nombre_item = (! empty($values[$sous_group][$name])) ? $values[$sous_group][$name] : 4;
        $ThemeUtility->addTextfieldTree($name, $form[$theme_name . '_' . $group][$sous_group], 'Nombre de blocs', $FieldValue);
        /**
         * options
         */
        $form[$theme_name . '_' . $group][$sous_group]['options'] = array(
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

        for ($i = 0; $i < $nombre_item; $i ++) {
          $icone = (isset($rx_logos[$i]['icone'])) ? $rx_logos[$i]['icone'] : '';
          $url = (isset($rx_logos[$i]['url'])) ? $rx_logos[$i]['url'] : '';
          $type = (isset($rx_logos[$i]['type'])) ? $rx_logos[$i]['type'] : '';
          $form[$theme_name . '_' . $group][$sous_group]['options'][$i] = [
            '#type' => 'details',
            '#title' => $label . ' : ' . ($i + 1),
            '#open' => false,
            '#attributes' => [
              'class' => [
                'wbu-ui-state-default'
              ],
              'id' => $theme_name . '_' . $group . '_' . $sous_group . '-' . $i
            ]
          ];
          /**
           * Nombre de bloc
           */
          $name = "icone";
          $FieldValue = (! empty($values[$sous_group]['options'][$i][$name])) ? $values[$sous_group]['options'][$i][$name] : $icone;
          $ThemeUtility->addTextfieldTree($name, $form[$theme_name . '_' . $group][$sous_group]['options'][$i], 'Icone', $FieldValue);
          /**
           * Nombre de bloc
           */
          $name = "url";
          $FieldValue = (! empty($values[$sous_group]['options'][$i][$name])) ? $values[$sous_group]['options'][$i][$name] : $url;
          $ThemeUtility->addTextfieldTree($name, $form[$theme_name . '_' . $group][$sous_group]['options'][$i], 'Url', $FieldValue);
          /**
           * Nombre de bloc
           */
          $name = "type";
          $FieldValue = (! empty($values[$sous_group]['options'][$i][$name])) ? $values[$sous_group]['options'][$i][$name] : $type;
          $ThemeUtility->addTextfieldTree($name, $form[$theme_name . '_' . $group][$sous_group]['options'][$i], 'Type', $FieldValue);
        }
      } elseif ($values[$sous_group]['model'] == 'LogoLeftMenuRight_M2') {
        $options = (isset($values[$sous_group]['options'])) ? $values[$sous_group]['options'] : [];
        $form[$theme_name . '_' . $group][$sous_group]['options'] = [];
        $this->loadHeaderFieldsModels($values[$sous_group]['model'], $form[$theme_name . '_' . $group][$sous_group]['options'], $options);
      }
    }
    // Controller\Comments::loadFields($model, $form, $options);
  }

  protected function loadHeaderFieldsModels($model, &$form, $options)
  {
    Controller\Headers::loadFields($model, $form, $options);
  }

  /**
   *
   * @param object $form
   * @param object $form_state
   * @param string $label
   */
  protected function addFootersDisplay(&$form, $form_state, $label = 'Affichage')
  {
    $ThemeUtility = new ThemeUtility();
    $sous_group = 'display';
    $group = $this->group;
    $theme_name = $this->themeName;
    $values = theme_get_setting($theme_name . '_' . $group, $theme_name);

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

    foreach ($this->DisplaysFields as $k_field => $DefaultValue) {
      // if ($k_field == 'model' || $k_field == 'region' || $k_field == 'weight' || $k_field == 'provider') {
      $this->AjaxWrapperProvider = $theme_name . '_' . $group . '_' . $sous_group;
      $this->AjaxCallbackProvider = '_' . $this->themeName . '_' . $this->group . '_provider';
      if (isset($values[$sous_group][$k_field])) {
        $this->addDisplayFields($k_field, $values[$sous_group][$k_field], $form[$theme_name . '_' . $group][$sous_group], $ThemeUtility);
      } else {
        $this->addDisplayFields($k_field, $DefaultValue, $form[$theme_name . '_' . $group][$sous_group], $ThemeUtility);
      }
      // }
    }

    if (! empty($values[$sous_group]['model']) && ! empty($values[$sous_group]['provider']) && $values[$sous_group]['provider'] == 'custom') {
      if ($values[$sous_group]['model'] == 'footerm1') {

        $nombre_item = (isset($values['display']['nombre_item'])) ? $values['display']['nombre_item'] : 2;
        /**
         *
         * @var string $container
         */
        $container = 'cards';
        $this->ListModels = Controller\Footers::listSousModels();
        for ($i = 0; $i < $nombre_item; $i ++) {
          $form[$theme_name . '_' . $group][$sous_group][$container][$i] = [
            '#type' => 'details',
            '#title' => $label . ' : ' . ($i + 1),
            '#open' => false,
            '#attributes' => [
              'class' => [
                'wbu-ui-state-default'
              ],
              'id' => $theme_name . '_' . $group . '_' . $sous_group . '-' . $i
            ]
          ];
          foreach ($this->DisplaysFields as $k_field2 => $DefaultValue2) {
            if ($k_field2 == 'model' || $k_field2 == 'provider') {
              $this->AjaxWrapperProvider = $theme_name . '_' . $group . '_' . $sous_group . '-' . $i;
              $this->AjaxCallbackProvider = '_' . $this->themeName . '_' . $this->group . '_block_provider';
              if (isset($values[$sous_group][$container][$i][$k_field2])) {
                $this->addDisplayFields($k_field2, $values[$sous_group][$container][$i][$k_field2], $form[$theme_name . '_' . $group][$sous_group][$container][$i], $ThemeUtility);
              } else {
                $this->addDisplayFields($k_field2, $DefaultValue2, $form[$theme_name . '_' . $group][$sous_group][$container][$i], $ThemeUtility);
              }
            }
          }
          if (! empty($values[$sous_group][$container][$i]['model']) && ! empty($values[$sous_group][$container][$i]['provider'])) {
            /**
             * Champs pour les valeurs personnalisées.
             */
            $form[$theme_name . '_' . $group][$sous_group][$container][$i]['options'] = [
              '#type' => 'details',
              '#title' => 'Champs personalisées',
              '#open' => true,
              '#attributes' => [
                'class' => [
                  'sortable'
                ],
                'style' => 'display:none0;'
                // 'id' => $this->AjaxWrapperProvider
              ]
            ];
            $model = $values[$sous_group][$container][$i]['model'];
            $provider = $values[$sous_group][$container][$i]['provider'];
            $options = (isset($values[$sous_group][$container][$i]['options'])) ? $values[$sous_group][$container][$i]['options'] : [];
            $this->loadFieldsBlockFooters($model, $provider, $form[$theme_name . '_' . $group][$sous_group][$container][$i]['options'], $options);
          }
        }
        $this->ListModels = Controller\Footers::listModels();
      }
      if ($values[$sous_group]['model']) {
        $options = (isset($values[$sous_group])) ? $values[$sous_group] : [];
        $this->loadFooterFieldsModels($values[$sous_group]['model'], $form[$theme_name . '_' . $group][$sous_group], $options);
      }
    }
  }

  protected function loadFooterFieldsModels($model, &$form, $options)
  {
    Controller\Footers::loadFields($model, $form, $options);
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
      if ($nbre_displays === 0) {
        \Drupal::messenger()->addMessage(print_r($nbre_displays, true));
        $nbre_displays = $form_state->set('nbre_' . $group . '_displays', []);
      } else {
        $nbre_displays = $form_state->set('nbre_' . $group . '_displays', $values['displays']);
      }
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
        $titre_block = (! empty($value['label_block'])) ? $value['label_block'] : $i;
        /**
         * Conteneur des elements d'affichage.
         */
        $form[$theme_name . '_' . $group][$sous_group][$key] = [
          '#type' => 'details',
          '#title' => $label . ' : ' . $titre_block,
          '#open' => false,
          '#attributes' => [ // 'id' => $this->AjaxWrapperProvider
          ]
        ];

        /**
         * Add label for block
         */
        $FieldValue = (! empty($value['label_block'])) ? $value['label_block'] : $i;
        $ThemeUtility->addTextfieldTree('label_block', $form[$theme_name . '_' . $group][$sous_group][$key], 'Titre du block', $FieldValue);

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

        // /image test
        /*
         * $name = 'image_test';
         * $form[$theme_name . '_' . $group][$sous_group][$key]['options'][$name] = [
         * '#type' => 'managed_file',
         * '#title' => 'Image load TEST v3',
         * // '#default_value' => $default,
         * '#upload_location' => 'public://'
         * ];
         */

        /**
         * Cas d'execution en ajax
         */
        if ($AjaxValue) {
          /**
           * La methode utilisé par "manage_file" semble de tres bonne qualité.
           * Niveau fonctionnement.
           * on recupere la valeur encours de provider et model.
           */
          if (! empty($AjaxValue[$sous_group][$key]['provider'])) {
            $value['provider'] = $AjaxValue[$sous_group][$key]['provider'];
            $value['model'] = $AjaxValue[$sous_group][$key]['model'];
          }
        }

        /**
         * On charge les champs personnalisés via le model selectionné.
         * Le provider doit etre custom
         */
        if (isset($value['model']) && isset($value['provider'])) {

          /**
           *
           * @var array $options
           */
          $options = (empty($value['options'])) ? [] : $value['options'];
          if ($value['provider'] == 'custom') {
            $form[$theme_name . '_' . $group][$sous_group][$key]['options']['#attributes']['style'] = 'display:block;';
            $this->loadFieldsModels($value['model'], $group, $form[$theme_name . '_' . $group][$sous_group][$key]['options'], $ThemeUtility, $options);
          } elseif ($value['provider'] == 'node') {
            $form[$theme_name . '_' . $group][$sous_group][$key]['options']['#attributes']['style'] = 'display:block;';
            $this->loadNodeFieldsModels($value['model'], $group, $form[$theme_name . '_' . $group][$sous_group][$key]['options'], $ThemeUtility, $options);
          }
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

  protected function loadFieldsBlockFooters($model, $provider, &$form, $options)
  {
    Controller\Footers::loadFieldsSousModels($model, $provider, $form, $options);
  }

  protected function loadNodeFieldsModels($model, $group, &$form, $ThemeUtility, $options)
  {
    if ($group == 'cards') {
      Controller\Cards::loadFieldsNodes($model, $form, $options);
    } elseif ($group == 'carouselcards') {
      Controller\CarouselCards::loadFieldsNodes($model, $form, $options);
    } elseif ($group == 'imagetextrightleft') {
      Controller\ImageTextRightLeft::loadFieldsNodes($model, $form, $options);
    }
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
      Controller\ImageTextRightLeft::loadFields($model, $form, $options);
    } elseif ($group == 'cards') {
      Controller\Cards::loadFields($model, $form, $options);
    } elseif ($group == 'pricelists') {
      Controller\PriceLists::loadFields($model, $form, $options);
    } elseif ($group == 'comments') {
      Controller\Comments::loadFields($model, $form, $options);
    } elseif ($group == 'slide') {
      Controller\Sliders::loadFields($model, $form, $options);
      // creation de style
      Controller\Sliders::defineStyleMedia($model, $this->themeName);
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
      $ThemeUtility->addSelectTree($field, $form, $this->routes, 'Affiche sur cette route', $FieldValue);
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
    } elseif ($field == 'status') {
      $FieldValue = (empty($FieldValue)) ? 'before_content' : $FieldValue;
      $ThemeUtility->addCheckboxTree($field, $form, 'Actif', $FieldValue);
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

  protected function addPagenodesdisplayDisplay(&$form, $form_state)
  {
    $sous_group = 'displays';
    $group = $this->group;
    $theme_name = $this->themeName;
    $values = theme_get_setting($theme_name . '_' . $group, $theme_name);
    $model = '';
    /**
     * le champs titre
     */
    $name = 'title';
    $FieldValue = (! empty($options[$name])) ? $options[$name] : '';
    $ThemeUtility->addTextfieldTree($name, $form, 'Titre', $FieldValue);

    Controller\PageNodesDisplay::loadFields($model, $form, $values);
  }

  /**
   * La methode de sauvegarde des fichiers ( manage_file ) contient uniquement les données pour le remplacement.
   * ce qui peut conduire à
   * des erreurs, pour contournée cela, on met en cache en cas de modifiecation par la methode custom.
   */
  private function SaveTemporaryValue()
  {
    ;
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