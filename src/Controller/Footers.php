<?php
namespace Stephane888\HtmlBootstrap\Controller;

use Stephane888\HtmlBootstrap\LoaderDrupal;
use Stephane888\HtmlBootstrap\Traits\Portions;
use Stephane888\HtmlBootstrap\ThemeUtility;

class Footers implements ControllerInterface {
  use Portions;

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
     * get datas
     */
    if (isset($options['cards'])) {
      $cards = $options['cards'];
    } else {
      $cards = $this->loadDefaultData();
    }

    /**
     * get class of blocs
     */
    if (isset($options['card_class_block'])) {
      $card_class_block = $options['card_class_block'];
    } else {
      $card_class_block = "col-md-6 col-lg-3";
    }

    /**
     * Get type
     */
    if (isset($options['type'])) {
      if ($options['type'] == 'footerm1') {
        /**
         * get text_left
         */
        if (isset($options['text_left'])) {
          $text_left = $options['text_left'];
        } else {
          $text_left = '© ' . date('Y') . ', All rights reserved';
        }
        /**
         * get text_right
         */
        if (isset($options['text_right'])) {
          $text_right = $options['text_right'];
        } else {
          $text_right = 'Made by <a href="http://wb-universe.com" target="_blanck"> <b>WB-Universe </b></a>';
        }
        $fileName = \file_get_contents($this->BasePath . '/Sections/Footers/Modele1/Drupal.html.twig');
        LoaderDrupal::addStyle(\file_get_contents($this->BasePath . '/Sections/Footers/Modele1/style.scss'), 'footerm1');
      }
    }

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
  }

  public static function listSousModels()
  {
    return [
      'texte' => 'Texte',
      'tag' => 'Tags',
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
      'footerm1' => 'footerm1'
    ];
  }

  public static function loadFields($model, &$form, $options)
  {
    ;
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