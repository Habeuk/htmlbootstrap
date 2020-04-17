<?php
namespace Stephane888\HtmlBootstrap\Controller;

use Stephane888\HtmlBootstrap\LoaderDrupal;
use Stephane888\HtmlBootstrap\Traits\Portions;
use Stephane888\HtmlBootstrap\ThemeUtility;
use Drupal\Core\Template\Attribute;
use Drupal\debug_log\debugLog;
use Drupal\Core\StringTranslation\TranslatableMarkup;
use Drupal\Core\StringTranslation\StringTranslationTrait;

class Footers implements ControllerInterface {
  use Portions;
  use StringTranslationTrait;

  protected $BasePath = '';

  protected $themeName = null;

  function __construct($path = null)
  {
    $this->BasePath = $path;
    $this->themeName = \Drupal::theme()->getActiveTheme();
  }

  /**
   * Using default template 'inline_template'
   */
  public function loadFile($options)
  {

    /**
     * Get type
     */
    if (isset($options['type'])) {
      if ($options['type'] == 'footerm1') {
        /**
         * get datas
         */
        if (isset($options['cards'])) {

          $cards = $options['cards'];
        } else {
          $cards = $this->loadDefaultData();
        }
        /**
         * Get class of blocs
         */
        if (isset($options['card_class_block'])) {
          $card_class_block = $options['card_class_block'];
        } else {
          $card_class_block = "col-md-6 col-lg-3";
        }
        /**
         * Get text_left
         */
        if (isset($options['text_left'])) {
          $text_left = $options['text_left'];
        } else {
          $text_left = '© WB-Universe.online' . date('Y') . ', All rights reserved';
        }
        /**
         * Get text_right
         */
        if (isset($options['text_right'])) {
          $text_right = $options['text_right'];
        } else {
          $text_right = 'Made by <a href="http://wb-universe.com" target="_blanck"> <b>WB-Universe </b></a>';
        }
        $fileName = \file_get_contents($this->BasePath . '/Sections/Footers/Modele1/Drupal.html.twig');
        LoaderDrupal::addStyle(\file_get_contents($this->BasePath . '/Sections/Footers/Modele1/style.scss'), 'footerm1');
        return [
          '#type' => 'inline_template',
          '#template' => $fileName,
          '#context' => [
            'cards' => $cards,
            'card_class_block' => $card_class_block,
            'text_left' => $text_left,
            'text_right' => $text_right
          ]
        ];
      } elseif ($options['type'] == 'FooterMenuRx') {
        return $this->load_FooterMenuRx($options);
      }
    }
  }

  protected function load_FooterMenuRx($options)
  {
    /**
     * Bloc rx logo
     */
    if (isset($options['rx_logo'])) {
      $rx_logo = $options['rx_logo'];
    } else {
      $rx_logo = $this->getdefault_rx_logos();
    }
    /**
     * Bloc footer_menu
     */
    if (isset($options['footer_menu'])) {
      $footer_menu = static::loadMenu($options['footer_menu']['menu_select']);
      $footer_menu['#attributes']['class'][] = 'footer-menu';
      $footer_menu['#theme'] = 'menu';
      if ($options['footer_menu']['status_rx']) {
        if ($options['footer_menu']['status_rx']) {
          $rx_logos = $this->template_rx_logos($options['footer_menu']['rx_logos'], $options['footer_menu']['rx_model']);
          if ($options['footer_menu']['rx_position'] == 'end_items') {
            $array_keys = \array_keys($footer_menu['#items']);
            $last_key = end($array_keys);
            $last_item = end($footer_menu['#items']);
            $last_item['suffix'] = $rx_logos;
            $footer_menu['#items'][$last_key] = $last_item;
          }
        }
      }
    } else {
      $footer_menu = static::loadMenu('footer');
      $footer_menu['#attributes']['class'][] = 'footer-menu';
      // $footer_menu['#attributes']['class'][] = 'justify-content-center';
      $footer_menu['#theme'] = 'menu';
      $array_keys = \array_keys($footer_menu['#items']);
      $last_key = end($array_keys);
      $last_item = end($footer_menu['#items']);
      $last_item['suffix'] = [
        '#type' => 'inline_template',
        '#template' => '<div class="rx-test d-flex align-items-center justify-content-end">
          <a href=""><i class="fab fa-facebook-f"></i></a>
          <a href=""><i class="fab fa-instagram"></i></a>
        </div>'
      ];
      $footer_menu['#items'][$last_key] = $last_item;
    }

    /**
     * Bloc end_left
     */
    if (isset($options['end_left'])) {
      $end_left = t($options['end_left']);
    } else {
      $end_left = '© <span class="site-name">' . $this->themeName->getName() . '</span> ' . date('Y');
    }

    /**
     * Bloc end_left
     */
    if (isset($options['end_right'])) {
      $end_right = $this->t($options['end_right']);
    } else {
      $end_right = 'Mentions légales';
    }
    /**
     * 'end_right_link'
     *
     * @var \Drupal\Core\Template\Attribute $wrapper_attribute
     */
    if (isset($options['end_right_link'])) {
      $end_right_link = $options['end_right_link'];
    } else {
      $end_right_link = '#';
    }

    $wrapper_attribute = new Attribute();
    $wrapper_attribute->addClass([
      'section',
      'footer-menu-rx'
    ]);
    LoaderDrupal::addStyle(\file_get_contents($this->BasePath . '/Suggestions/sections/Footers/FooterMenuRx/style.scss'), 'footer-menu-rx');
    return [
      '#theme' => 'footer_menu_rx',
      '#footer_menu' => $footer_menu,
      '#rx_logo' => $rx_logo,
      '#end_left' => $end_left,
      '#end_right' => $end_right,
      '#end_right_link' => $end_right_link,
      '#attributes' => $wrapper_attribute
    ];
  }

  public static function listSousModels()
  {
    return [
      'texte' => 'Texte',
      'tag' => 'Tags',
      'menu' => 'Menus',
      'PostsVerticalM1' => 'PostsVerticalM1',
      'fb-page-plugin' => 'Facebook page plugin'
    ];
  }

  public static function loadFieldsSousModels($model, $provider, &$form, $options)
  {
    $ThemeUtility = new ThemeUtility();
    /**
     * le champs titre
     */
    $name = 'title';
    $FieldValue = (! empty($options[$name])) ? $options[$name] : '';
    $ThemeUtility->addTextfieldTree($name, $form, 'Titre', $FieldValue);

    if ($model == 'texte') {
      if ($provider == 'custom') {
        /**
         * le champs description
         */
        $name = 'description';
        $FieldValue = (! empty($options[$name])) ? $options[$name] : '';
        $ThemeUtility->addTextareaSimpleTree($name, $form, 'Description', $FieldValue);
      }
    } elseif ($model == 'menu') {
      $listsManu = Menus::getAllMenus();
      /**
       * le champs description
       */
      $name = 'description';
      $FieldValue = (! empty($options[$name])) ? $options[$name] : '';
      $ThemeUtility->addSelectTree($name, $form, $listsManu, 'Selectionner le menu', $FieldValue);
    } elseif ($model == 'PostsVerticalM1' && $provider == 'node') {
      $ManageNode = new ManageNode();
      $listNode = $ManageNode->getContentType();
      /**
       * le champs description
       */
      $name = 'contenttype';
      $FieldValue = $contenttype = (! empty($options[$name])) ? $options[$name] : '';
      $ThemeUtility->addSelectTree($name, $form, $listNode, 'Selectionner le menu', $FieldValue);
      /**
       * le champs url_page
       */
      $name = 'nombre_item';
      $FieldValue = (! empty($options[$name])) ? $options[$name] : 2;
      $ThemeUtility->addTextfieldTree($name, $form, " Nombre d'item ", $FieldValue);

      if (! empty($contenttype)) {
        $listFields = $ManageNode->getFieldsNode($contenttype);
        $container = 'cards';
        $form[$container] = [
          '#type' => 'details',
          '#title' => 'Champs ',
          '#open' => true
        ];

        /**
         * Le champs image
         */
        $name = 'image';
        $FieldValue = (! empty($options[$container][$name])) ? $options[$container][$name] : '';
        $ThemeUtility->addSelectTree($name, $form[$container], $listFields, 'Selectionner le champs image ', $FieldValue);

        /**
         * Le champs title
         */
        $name = 'title';
        $FieldValue = (! empty($options[$container][$name])) ? $options[$container][$name] : '';
        $ThemeUtility->addSelectTree($name, $form[$container], $listFields, 'Selectionner le champs title ', $FieldValue);
      }
    } elseif ($model == 'fb-page-plugin') {
      /**
       * le champs url_page
       */
      $name = 'url_page';
      $FieldValue = (! empty($options[$name])) ? $options[$name] : '';
      $ThemeUtility->addTextfieldTree($name, $form, 'Url de la page', $FieldValue);
      /**
       * le champs url_page
       */
      $name = 'name_page';
      $FieldValue = (! empty($options[$name])) ? $options[$name] : '';
      $ThemeUtility->addTextfieldTree($name, $form, 'nom de la page', $FieldValue);
      /**
       * le champs active_sdk
       */
      $name = 'active_sdk';
      $FieldValue = (! empty($options[$name])) ? $options[$name] : 0;
      $ThemeUtility->addCheckboxTree($name, $form, 'Active le sdk', $FieldValue);
      /**
       * Le champs id_app
       */
      $name = 'id_app';
      $FieldValue = (! empty($options[$name])) ? $options[$name] : '';
      $ThemeUtility->addTextfieldTree($name, $form, 'Id de l\'application ', $FieldValue);
      /**
       * height
       */
      $name = 'height';
      $FieldValue = (! empty($options[$name])) ? $options[$name] : 300;
      $ThemeUtility->addTextfieldTree($name, $form, 'Hauteur du bloc ', $FieldValue);
    }
  }

  public static function listModels()
  {
    return [
      'footerm1' => 'footerm1',
      'FooterMenuRx' => 'FooterMenuRx'
    ];
  }

  public static function loadFields($model, &$form, $options)
  {
    $ThemeUtility = new ThemeUtility();
    if ($model == 'FooterMenuRx') {

      /**
       * le champs nombre_item
       */
      $name = 'end_left';
      $FieldValue = $nombre_item = (! empty($options[$name])) ? $options[$name] : '© WB-Universe ' . date('Y');
      $ThemeUtility->addTextfieldTree($name, $form, "end_left", $FieldValue);

      /**
       * le champs nombre_item
       */
      $name = 'end_right';
      $FieldValue = $nombre_item = (! empty($options[$name])) ? $options[$name] : 'Mentions légales';
      $ThemeUtility->addTextfieldTree($name, $form, "end_right", $FieldValue);

      /**
       * le champs nombre_item
       */
      $name = 'end_right_link';
      $FieldValue = $nombre_item = (! empty($options[$name])) ? $options[$name] : '#';
      $ThemeUtility->addTextfieldTree($name, $form, "end_right_link", $FieldValue);

      /**
       * le champs titre
       */
      $name = 'status_rx';
      $FieldValue = $status_rx = (isset($options[$name])) ? $options[$name] : 1;
      $ThemeUtility->addCheckboxTree($name, $form, 'Affiche les bouttons de RX', $FieldValue);

      /**
       * le champs nombre_item
       */
      $name = 'nombre_item';
      $FieldValue = $nombre_item = (! empty($options[$name])) ? $options[$name] : 4;
      $ThemeUtility->addTextfieldTree($name, $form, "Nombre d'icones", $FieldValue);

      if ($status_rx) {
        $container = 'rx_logos';

        $defaultIcone = static::getdefault_rx_logos_static();
        for ($i = 0; $i < $nombre_item; $i ++) {
          if (! empty($defaultIcone[$i])) {
            $icone = $defaultIcone[$i]['icone'];
            $url = $defaultIcone[$i]['url'];
            $type = $defaultIcone[$i]['type'];
          } else {
            $icone = '';
            $url = '';
            $type = '';
          }

          $form[$container][$i] = [
            '#type' => 'details',
            '#title' => 'Listes d\'icones ',
            '#open' => false
          ];
          /**
           * le champs icone
           */
          $name = 'icone';
          $FieldValue = (! empty($options[$container][$i][$name])) ? $options[$container][$i][$name] : $icone;
          $ThemeUtility->addTextfieldTree($name, $form[$container][$i], 'Icone', $FieldValue);
          /**
           * le champs url
           */
          $name = 'url';
          $FieldValue = (! empty($options[$container][$i][$name])) ? $options[$container][$i][$name] : $url;
          $ThemeUtility->addTextfieldTree($name, $form[$container][$i], 'Url', $FieldValue);
          /**
           * le champs type
           */
          $name = 'type';
          $FieldValue = (! empty($options[$container][$i][$name])) ? $options[$container][$i][$name] : $type;
          $ThemeUtility->addTextfieldTree($name, $form[$container][$i], 'Type', $FieldValue);
        }
      }
      /**
       * model de boutons.
       */
      $name = 'rx_position';
      $rx_models = [
        'end_items' => 'Sous le dernier element du menu'
      ];
      $FieldValue = (isset($options[$name])) ? $options[$name] : '';
      $ThemeUtility->addSelectTree($name, $form, $rx_models, 'Position des bouttons de RX', $FieldValue);

      /**
       * list menus
       */
      $name = 'menu_select';
      $list_menus = [
        'main' => 'Menu principal',
        'footer' => 'Menu pied de page'
      ];
      $FieldValue = (isset($options[$name])) ? $options[$name] : 'footer';
      $ThemeUtility->addSelectTree($name, $form, $list_menus, 'Selectionner le menu', $FieldValue);
      /**
       * models RX
       */
      $name = 'rx_model';
      $FieldValue = (isset($options[$name])) ? $options[$name] : 'footer';
      $ThemeUtility->addSelectTree($name, $form, static::model_rx_logos(), 'Selectionner le model RX', $FieldValue);
    } elseif ($model == 'footerm1') {
      /**
       * card_class_block
       */
      $name = "card_class_block";
      $FieldValue = (! empty($options[$name])) ? $options[$name] : 'col-md-6 col-lg-3';
      $ThemeUtility->addTextfieldTree($name, $form, 'Classe block', $FieldValue);
      /**
       * Nombre de bloc
       */
      $name = "nombre_item";
      $FieldValue = $nombre_item = (! empty($options[$name])) ? $options[$name] : 3;
      $ThemeUtility->addTextfieldTree($name, $form, 'Nombre de blocs', $FieldValue);
      /**
       * le champs nombre_item
       */
      $name = 'text_left';
      $FieldValue = $nombre_item = (! empty($options[$name])) ? $options[$name] : '© WB-Universe ' . date('Y');
      $ThemeUtility->addTextareaSimpleTree($name, $form, "text_left", $FieldValue);

      /**
       * le champs nombre_item
       */
      $name = 'text_right';
      $FieldValue = $nombre_item = (! empty($options[$name])) ? $options[$name] : 'Made by <a href="http://wb-universe.com" target="_blanck"> <b>WB-Universe </b></a>' . date('Y');
      $ThemeUtility->addTextareaSimpleTree($name, $form, "text_right", $FieldValue);
    }
  }

  /**
   *
   * @return string[][]
   */
  protected function loadDefaultData()
  {
    $posts = new Cards($this->BasePath);

    $cards = [];
    $faker = \Faker\Factory::create();
    $cards[] = [
      'title' => 'About Us',
      'text' => $faker->text . ' ' . $faker->text . ' ' . $faker->unique()->realText(80)
    ];
    $cards[] = [
      'title' => 'Tag Cloud',
      'text' => $this->template_inline_template($this->getTags(19))
    ];
    $options = [
      'type' => 'PostsVerticalM1'
    ];
    $cards[] = [
      'title' => 'Recent Posts',
      'text' => $posts->loadFile($options)
    ];
    $cards[] = [
      'title' => 'Recent Posts',
      'text' => $posts->loadFile($options)
    ];
    return $cards;
  }

  /**
   * Return hello.
   */
  protected function getTags($nombre = 16)
  {
    $tags = [];
    $faker = \Faker\Factory::create();
    for ($i = 0; $i <= $nombre; $i ++) {
      $tags[] = [
        $this->template_htmltag($faker->unique()->word, 'span', 'btn btn-outline-primary tag-cloud')
      ];
    }
    return $tags;
  }
}