<?php
namespace Stephane888\HtmlBootstrap\Controller;

use Stephane888\HtmlBootstrap\LoaderDrupal;
use Stephane888\HtmlBootstrap\Traits\Portions;

class Footers {
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
    if (isset($options['footers'])) {
      $cards = $options['footers'];
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
        $fileName = \file_get_contents($this->BasePath . '/Sections/Footers/Modele1/Drupal.html.twig');
        LoaderDrupal::addStyle(\file_get_contents($this->BasePath . '/Sections/Footers/Modele1/style.scss'), 'footerm1');
      }
    } else {
      $fileName = \file_get_contents($this->BasePath . '/Sections/Footers/Modele1/Drupal.html.twig');
      LoaderDrupal::addStyle(\file_get_contents($this->BasePath . '/Sections/Footers/Modele1/style.scss'), 'footerm1');
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