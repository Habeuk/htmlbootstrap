<?php
namespace Stephane888\HtmlBootstrap\Controller;

use Stephane888\HtmlBootstrap\LoaderDrupal;
use Stephane888\HtmlBootstrap\Traits\Portions;
use Stephane888\HtmlBootstrap\ThemeUtility;

class Comments implements ControllerInterface {
  use Portions;

  protected $BasePath = '';

  protected $themeObject = null;

  function __construct($path = null)
  {
    $this->BasePath = $path;
    $this->themeObject = \Drupal::theme()->getActiveTheme();
  }

  /**
   * Using default template 'inline_template'
   */
  public function loadFile($options)
  {
    /**
     * Get title
     */
    if (isset($options['title'])) {
      $title = $options['title'];
    } else {
      $title = 'What Student Say?';
    }

    /**
     * Get title
     */
    if (isset($options['cards'])) {
      $cards = $options['cards'];
    } else {
      $cards = $this->loadDefaultData();
    }

    /**
     * Get type
     */
    if (isset($options['type'])) {
      if ($options['type'] == 'Comments-CarouselM1') {
        $fileName = \file_get_contents($this->BasePath . '/Sections/Comments/CarouselM1/Drupal.html.twig');
        LoaderDrupal::addStyle(\file_get_contents($this->BasePath . '/Sections/Comments/CarouselM1/style.scss'), 'Comments-CarouselM1');
        LoaderDrupal::addScript(\file_get_contents($this->BasePath . '/Sections/Comments/CarouselM1/script.js'), 'Comments-CarouselM1');
      }
    } else {
      $fileName = \file_get_contents($this->BasePath . '/Sections/Comments/CarouselM1/Drupal.html.twig');
      LoaderDrupal::addStyle(\file_get_contents($this->BasePath . '/Sections/Comments/CarouselM1/style.scss'), 'Comments-CarouselM1');
      LoaderDrupal::addScript(\file_get_contents($this->BasePath . '/Sections/Comments/CarouselM1/script.js'), 'Comments-CarouselM1');
    }

    return [
      '#type' => 'inline_template',
      '#template' => $fileName,
      '#context' => [
        'cards' => $cards,
        'title' => $title,
        'img_bg' => '/' . drupal_get_path('theme', $this->themeObject->getName()) . '/defaultfile/Comments/CarouselM1/testimonial-quote.png' // quate
      ]
    ];
  }

  /**
   *
   * @param number $number
   */
  protected function loadDefaultData($number = 8)
  {
    $cards = [];
    $faker = \Faker\Factory::create();
    $faker->seed(12548512475); // permet de generer le meme texte durant une session.
    for ($i = 0; $i < $number; $i ++) {
      $cards[] = [
        'title' => $faker->realText(rand(50, 70)),
        'text' => $faker->text,
        'name' => $faker->unique()->name,
        'function' => $faker->companySuffix,
        'link_user' => '#'
      ];
    }
    return $cards;
  }

  public static function loadFields($model, &$form, $options)
  {
    $ThemeUtility = new ThemeUtility();

    if ($model == 'Comments-CarouselM1') {

      /**
       * Le champs titre
       */
      $name = 'title';
      $FieldValue = (! empty($options[$name])) ? $options[$name] : '';
      $ThemeUtility->addTextfieldTree($name, $form, 'Titre', $FieldValue);

      /**
       * Le champs nombre_item
       */
      $name = 'nombre_item';
      $nombre_item = $FieldValue = (! empty($options[$name])) ? $options[$name] : 4;
      $ThemeUtility->addTextfieldTree($name, $form, 'Nombre de commentaires', $FieldValue);
      $container = 'cards';

      $container = 'cards';

      for ($i = 0; $i < $nombre_item; $i ++) {
        $form[$container][$i] = [
          '#type' => 'details',
          '#title' => 'Blocs : ' . ($i + 1),
          '#open' => false
        ];
        /**
         * Le champs titre
         */
        $name = 'title';
        $FieldValue = (! empty($options[$container][$i][$name])) ? $options[$container][$i][$name] : '';
        $ThemeUtility->addTextfieldTree($name, $form[$container][$i], 'Titre', $FieldValue);
        /**
         * Le champs text
         */
        $name = 'text';
        $FieldValue = (! empty($options[$container][$i][$name])) ? $options[$container][$i][$name] : '';
        $ThemeUtility->addTextareaSimpleTree($name, $form[$container][$i], 'Titre', $FieldValue);
        /**
         * Le champs titre
         */
        $name = 'name';
        $FieldValue = (! empty($options[$container][$i][$name])) ? $options[$container][$i][$name] : '';
        $ThemeUtility->addTextfieldTree($name, $form[$container][$i], 'Name', $FieldValue);
        /**
         * Le champs titre
         */
        $name = 'function';
        $FieldValue = (! empty($options[$container][$i][$name])) ? $options[$container][$i][$name] : '';
        $ThemeUtility->addTextfieldTree($name, $form[$container][$i], 'Fonction', $FieldValue);
        /**
         * Le champs titre
         */
        $name = 'link_user';
        $FieldValue = (! empty($options[$container][$i][$name])) ? $options[$container][$i][$name] : '';
        $ThemeUtility->addTextfieldTree($name, $form[$container][$i], 'link_user', $FieldValue);
      }
    }
  }

  public static function listModels()
  {
    return [
      'Comments-CarouselM1' => 'Comments-CarouselM1'
    ];
  }
}
