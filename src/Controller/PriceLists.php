<?php

namespace Stephane888\HtmlBootstrap\Controller;

use Stephane888\HtmlBootstrap\Traits\Portions;
use Stephane888\HtmlBootstrap\LoaderDrupal;
use Stephane888\HtmlBootstrap\ThemeUtility;
use Stephane888\HtmlBootstrap\HelpMigrate;

class PriceLists implements ControllerInterface {
  use Portions;

  protected $BasePath = '';

  protected $themeObject = null;

  function __construct($path = null) {
    $this->BasePath = $path;
    $this->themeObject = \Drupal::theme()->getActiveTheme();
  }

  /**
   *
   * {@inheritdoc}
   * @see \Stephane888\HtmlBootstrap\Controller\ControllerInterface::loadFile()
   */
  public function loadFile($options) {
    /**
     * Get type
     */
    if (isset($options['type'])) {
      if ($options['type'] == 'Model1') {
        return $this->loadModelM1($options);
      }
    }
  }

  /**
   *
   * {@inheritdoc}
   * @see \Stephane888\HtmlBootstrap\Controller\ControllerInterface::listModels()
   */
  public static function listModels() {
    return [
      'Model1' => 'Model1'
    ];
  }

  /**
   *
   * {@inheritdoc}
   * @see \Stephane888\HtmlBootstrap\Controller\ControllerInterface::loadFields()
   */
  public static function loadFields($model, &$form, $options) {
    $ThemeUtility = new ThemeUtility();
    if ($model == 'Model1') {
      // dump($options);
      /**
       * le champs titre
       */
      $name = 'title';
      $FieldValue = (!empty($options[$name])) ? $options[$name] : 'Selectionner un pack';
      $ThemeUtility->addTextfieldTree($name, $form, 'Titre', $FieldValue);
      /**
       * class card_class_block
       */
      $name = 'card_class_block';
      $FieldValue = (!empty($options[$name])) ? $options[$name] : 'col-md-6 col-lg-4';
      $ThemeUtility->addTextfieldTree($name, $form, 'Class colonne bootstrap', $FieldValue);
      /**
       * le champs nombre_item
       */
      $name = 'nombre_item';
      $nombre_list = $FieldValue = (!empty($options[$name])) ? $options[$name] : 8;
      $ThemeUtility->addTextfieldTree($name, $form, 'Nombre d\'elements dans la liste', $FieldValue);
      /**
       * Nombre de bloc 3.
       */
      $nombre_item = 3;
      $container = 'cards';
      $lists = 'lists';
      for ($i = 0; $i < $nombre_item; $i++) {
        $form[$container][$i] = [
          '#type' => 'details',
          '#title' => 'Blocs : ' . ($i + 1),
          '#open' => false,
          '#attributes' => [
            'class' => [
              'sortable'
            ]
          ]
        ];
        /**
         * le champs show_price_promo
         */
        $name = 'show_price_promo';
        $FieldValue = (!empty($options[$container][$i][$name])) ? $options[$container][$i][$name] : 0;
        $ThemeUtility->addCheckboxTree($name, $form[$container][$i], 'Affiche le prix promo', $FieldValue);
        /**
         * le champs titre
         */
        $name = 'title_small';
        $FieldValue = (!empty($options[$container][$i][$name])) ? $options[$container][$i][$name] : 'Pack';
        $ThemeUtility->addTextfieldTree($name, $form[$container][$i], 'Petit titre', $FieldValue);
        /**
         * le champs titre
         */
        $name = 'title';
        $FieldValue = (!empty($options[$container][$i][$name])) ? $options[$container][$i][$name] : '';
        $ThemeUtility->addTextfieldTree($name, $form[$container][$i], 'Titre', $FieldValue);

        /**
         * le champs price_promo
         */
        $name = 'price_promo';
        $FieldValue = (!empty($options[$container][$i][$name])) ? $options[$container][$i][$name] : '50 900 Fcfa';
        $ThemeUtility->addTextfieldTree($name, $form[$container][$i], 'Prix promo', $FieldValue);
        /**
         * le champs price
         */
        $name = 'price';
        $FieldValue = (!empty($options[$container][$i][$name])) ? $options[$container][$i][$name] : '150 000 Fcfa';
        $ThemeUtility->addTextfieldTree($name, $form[$container][$i], 'Prix', $FieldValue);

        /**
         * le champs price_promo
         */
        $name = 'price_suffix';
        $FieldValue = (!empty($options[$container][$i][$name])) ? $options[$container][$i][$name] : 'par an';
        $ThemeUtility->addTextfieldTree($name, $form[$container][$i], 'suffixe ', $FieldValue);
        // dump($options[$container][$i][$lists]);
        $options[$container][$i][$lists] = static::RebuildIndexInt($options[$container][$i][$lists]);

        for ($j = 0; $j < $nombre_list; $j++) {
          $name = 'title';
          $label = (!empty($options[$container][$i][$lists][$j][$name])) ? $options[$container][$i][$lists][$j][$name] : '';
          $form[$container][$i][$lists][$j] = [
            '#type' => 'details',
            '#title' => 'Option : ' . $label,
            '#open' => false
          ];
          /**
           * Le champs titre
           */
          $name = 'title';
          $FieldValue = (!empty($options[$container][$i][$lists][$j][$name])) ? $options[$container][$i][$lists][$j][$name] : '';
          $ThemeUtility->addTextfieldTree($name, $form[$container][$i][$lists][$j], 'Titre', $FieldValue);
          /**
           * Le champs description
           */
          $name = 'text';
          $FieldValue = (!empty($options[$container][$i][$lists][$j][$name])) ? $options[$container][$i][$lists][$j][$name] : '';
          $ThemeUtility->addTextareaSimpleTree($name, $form[$container][$i][$lists][$j], 'Description', $FieldValue);
          /**
           * Le champs titre
           */
          $name = 'icone';
          $FieldValue = (!empty($options[$container][$i][$lists][$j][$name])) ? $options[$container][$i][$lists][$j][$name] : '<i class="fas fa-check active"></i>';
          $ThemeUtility->addTextfieldTree($name, $form[$container][$i][$lists][$j], 'Icone "fas fa-check active"> | "fas fa-times disable"', $FieldValue);
          /**
           * Le champs poid
           */
          $name = 'weight';
          $FieldValue = (!empty($options[$container][$i][$lists][$j][$name])) ? $options[$container][$i][$lists][$j][$name] : $j;
          $ThemeUtility->addTextfieldTree($name, $form[$container][$i][$lists][$j], 'poid', $FieldValue);
        }
      }
    }
  }

  public static function RebuildIndexInt($options) {
    $new_options = [];
    foreach ($options as $val) {
      $new_options[] = $val;
    }
    return $new_options;
  }

  protected function loadModelM1($options) {
    /**
     * Get Datas
     */
    if (isset($options['cards'])) {
      $cards = $options['cards'];
    }

    /**
     * Get title
     */
    if (isset($options['title'])) {
      $title = $options['title'];
    } else {
      $title = "Selectionner un pack";
    }

    /**
     * Get card_class_block
     */
    if (isset($options['card_class_block'])) {
      $card_class_block = $options['card_class_block'];
    } else {
      $card_class_block = "col-md-6 col-lg-4";
    }

    if (empty($cards)) {
      $number = 3;
      $cards = $this->loadDefaultData_Model1($number);
    }
    $whatsapp_url = '/' . HelpMigrate::getPatch('theme', $this->themeObject->getName()) . '/defaultfile/logos/logo-whatsapp100x100.png';
    $mentor_url = '/' . HelpMigrate::getPatch('theme', $this->themeObject->getName()) . '/defaultfile/PriceLists/Model1/mentor-1.png';
    $filename = \file_get_contents($this->BasePath . '/Sections/PriceLists/Model1/Drupal.html.twig');
    LoaderDrupal::addStyle(\file_get_contents($this->BasePath . '/Sections/PriceLists/Model1/style.scss'), 'PriceLists-Model1');
    LoaderDrupal::addScript(\file_get_contents($this->BasePath . '/Sections/PriceLists/Model1/script.js'), 'PriceLists-Model1');
    return [
      '#type' => 'inline_template',
      '#template' => $filename,
      '#context' => [
        'cards' => $cards,
        'title' => $title,
        'card_class_block' => $card_class_block,
        'whatsapp_url' => $whatsapp_url,
        'mentor_url' => $mentor_url
      ]
    ];
  }

  protected function loadDefaultData_Model1($number = 3) {
    $cards = [];
    $faker = \Faker\Factory::create();
    $faker->seed(129888882258);
    for ($i = 0; $i < $number; $i++) {
      $cards[$i] = [
        'title_small' => 'Pack',
        'title' => $faker->unique()->word,
        'text' => $faker->unique()->realText(rand(70, 90)),
        'price_promo' => $faker->unique()->numberBetween(50000, 100000) . ' €',
        'price' => $faker->unique()->numberBetween(150000, 200000) . ' €',
        'show_price_promo' => $faker->boolean,
        'price_suffix' => 'per month',
        'lists' => $this->loadDefaultData_Model1_lists(8, $faker->unique()
          ->numberBetween(4, 8))
      ];
    }
    return $cards;
  }

  protected function loadDefaultData_Model1_lists($number = 5, $active = 5) {
    $lists = [];
    $faker = \Faker\Factory::create();
    $faker->seed(129888882258);
    for ($i = 0; $i < $number; $i++) {
      $lists[$i] = [
        'icone' => ($active >= $i) ? '<i class="fas fa-check active"></i>' : '<i class="fas fa-times disable"></i>',
        'title' => $faker->unique()->realText(rand(20, 60)),
        'text' => $faker->unique()->realText(rand(100, 150))
      ];
    }
    return $lists;
  }
}
