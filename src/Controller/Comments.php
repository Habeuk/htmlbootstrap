<?php
namespace Stephane888\HtmlBootstrap\Controller;

use Stephane888\HtmlBootstrap\LoaderDrupal;
use Stephane888\HtmlBootstrap\Traits\Portions;

class Comments {
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
    if (isset($options['comments'])) {
      $cards = $options['comments'];
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
        'img_bg' => drupal_get_path('theme', $this->themeObject->getName()) . '/defaultfile/Comments/CarouselM1/testimonial-quote.png'
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
}
