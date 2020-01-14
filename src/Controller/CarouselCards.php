<?php
namespace Stephane888\HtmlBootstrap\Controller;

use Stephane888\HtmlBootstrap\LoaderDrupal;
use Stephane888\HtmlBootstrap\Traits\Portions;

class CarouselCards {
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
     * Get datas
     */
    if (isset($options['CarouselCards'])) {
      $cards = $options['CarouselCards'];
    } else {
      $number = (isset($options['nombre_item'])) ? $options['nombre_item'] : 8;
      $cards = $this->loadDefaultData($number);
    }

    /**
     * Get title
     */
    if (isset($options['title'])) {
      $title = $options['title'];
    } else {
      $title = 'Recent Courses';
    }
    /**
     * Get title
     */
    if (isset($options['txt_link'])) {
      $txt_link = $options['txt_link'];
    } else {
      $txt_link = 'View All Courses';
    }

    /**
     * Get link
     */
    if (isset($options['link'])) {
      $link = $options['link'];
    } else {
      $link = '#';
    }

    /**
     * Get type
     */
    if (isset($options['type'])) {
      if ($options['type'] == 'Modele1') {
        $fileName = \file_get_contents($this->BasePath . '/Sections/CarouselCards/Modele1/Drupal.html.twig');
        LoaderDrupal::addStyle(\file_get_contents($this->BasePath . '/Sections/CarouselCards/Modele1/style.scss'), 'CarouselCards-Modele1');
        LoaderDrupal::addScript(\file_get_contents($this->BasePath . '/Sections/CarouselCards/Modele1/script.js'), 'CarouselCards-Modele1');
      }
    } else {
      $fileName = \file_get_contents($this->BasePath . '/Sections/CarouselCards/Modele1/Drupal.html.twig');
      LoaderDrupal::addStyle(\file_get_contents($this->BasePath . '/Sections/CarouselCards/Modele1/style.scss'), 'CarouselCards-Modele1');
      LoaderDrupal::addScript(\file_get_contents($this->BasePath . '/Sections/CarouselCards/Modele1/script.js'), 'CarouselCards-Modele1');
    }

    return [
      '#type' => 'inline_template',
      '#template' => $fileName,
      '#context' => [
        'cards' => $cards,
        'title' => $title,
        'txt_link' => $txt_link,
        'link' => $link
      ]
    ];
  }

  /**
   *
   * @param number $number
   */
  protected function loadDefaultData($number = 7)
  {
    $icones = $this->defaultImg();
    $cards = [];
    $faker = \Faker\Factory::create();
    $faker->seed(12548512475); // permet de generer le meme texte durant une session.
    for ($i = 0; $i < $number; $i ++) {
      $img_url = (isset($icones[$i])) ? $icones[$i] : '/defaultfile/CarouselCards/Modele1/photodune-6590781-product-launch-flat-illustration-2-700x400.jpg';
      $cards[] = [
        'title' => $faker->unique()->realText(rand(40, 50)),
        'date' => 'MAR 21, 2019',
        'img' => [
          'url' => '/' . drupal_get_path('theme', $this->themeObject->getName()) . $img_url
        ],
        'link' => '#' // optional
      ];
    }
    return $cards;
  }

  protected function defaultImg()
  {
    return [
      '/defaultfile/CarouselCards/Modele1/Fotolia_30806367_Subscription_Monthly_XL-700x400.jpg',
      '/defaultfile/CarouselCards/Modele1/Fotolia_32338952_Subscription_Monthly_XL-700x400.jpg',
      '/defaultfile/CarouselCards/Modele1/Fotolia_33064312_Subscription_Monthly_XXL-700x400.jpg',
      '/defaultfile/CarouselCards/Modele1/photodune-6147544-brainstorming-ideas-with-coffee-m-700x400.jpg',
      '/defaultfile/CarouselCards/Modele1/photodune-6243139-vintage-photography-m-700x400.jpg',
      '/defaultfile/CarouselCards/Modele1/photodune-6252039-web-and-seo-analytics-concept-m-700x400.jpg',
      '/defaultfile/CarouselCards/Modele1/photodune-6590781-product-launch-flat-illustration-2-700x400.jpg'
    ];
  }
}