<?php
namespace Stephane888\HtmlBootstrap\Controller;

use Stephane888\HtmlBootstrap\LoaderDrupal;
use Stephane888\HtmlBootstrap\Traits\Portions;

class Cards {
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
      $number = (isset($options['nombre_item'])) ? $options['nombre_item'] : 8;
      $cards = $this->loadDefaultData($number);
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
      if ($options['type'] == 'IconeModelFlat') {
        $fileName = \file_get_contents($this->BasePath . '/Sections/Cards/IconeModelFlat/Drupal.html.twig');
        LoaderDrupal::addStyle(\file_get_contents($this->BasePath . '/Sections/Cards/IconeModelFlat/style.scss'), 'IconeModelFlat');
      }
    } else {
      $fileName = \file_get_contents($this->BasePath . '/Sections/Cards/IconeModelFlat/Drupal.html.twig');
      LoaderDrupal::addStyle(\file_get_contents($this->BasePath . '/Sections/Cards/IconeModelFlat/style.scss'), 'IconeModelFlat');
    }

    return [
      '#type' => 'inline_template',
      '#template' => $fileName,
      '#context' => [
        'cards' => $cards,
        'card_class_block' => $card_class_block
      ]
    ];
  }

  /**
   *
   * @param number $number
   */
  protected function loadDefaultData($number = 8)
  {
    $icones = $this->defaultIcone();
    $cards = [];
    $faker = \Faker\Factory::create();
    $faker->seed(12548512475); // permet de generer le meme texte durant une session.
    for ($i = 0; $i < $number; $i ++) {
      $cards[] = [
        'title' => $faker->unique()->realText(rand(15, 30)),
        'text' => $faker->text,
        'icone' => (isset($icones[$i])) ? $icones[$i] : '<i class="fas fa-chart-bar"></i>',
        'link' => '#' // optional
      ];
    }
    return $cards;
  }

  protected function defaultIcone()
  {
    return [
      '<i class="fab fa-accusoft"></i>',
      '<i class="fab fa-adn"></i>',
      '<i class="fab fa-apple"></i>',
      '<i class="fas fa-balance-scale"></i>',
      '<i class="fas fa-blender"></i>',
      '<i class="fas fa-box-open"></i>',
      '<i class="fas fa-bullhorn"></i>',
      '<i class="fas fa-camera-retro"></i>'
    ];
  }
}