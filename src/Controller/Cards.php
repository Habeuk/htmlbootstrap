<?php
namespace Stephane888\HtmlBootstrap\Controller;

use Stephane888\HtmlBootstrap\LoaderDrupal;
use Stephane888\HtmlBootstrap\Traits\Portions;
use Stephane888\HtmlBootstrap\Entity\ImageStyleTheme;

class Cards {
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
     * get datas
     */
    if (isset($options['cards'])) {
      $cards = $options['cards'];
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
        if (empty($cards)) {
          $number = (isset($options['nombre_item'])) ? $options['nombre_item'] : 8;
          $cards = $this->loadDefaultData($number);
        }
        $fileName = \file_get_contents($this->BasePath . '/Sections/Cards/IconeModelFlat/Drupal.html.twig');
        LoaderDrupal::addStyle(\file_get_contents($this->BasePath . '/Sections/Cards/IconeModelFlat/style.scss'), 'IconeModelFlat');
        return [
          '#type' => 'inline_template',
          '#template' => $fileName,
          '#context' => [
            'cards' => $cards,
            'card_class_block' => $card_class_block
          ]
        ];
      } elseif ($options['type'] == 'PostsVerticalM1') {
        if (empty($cards)) {
          $number = (isset($options['nombre_item'])) ? $options['nombre_item'] : 4;
          $cards = $this->loadDefaultData__PostsVerticalM1($number);
        }
        $fileName = \file_get_contents($this->BasePath . '/Sections/Cards/PostsVerticalM1/Drupal.html.twig');
        LoaderDrupal::addStyle(\file_get_contents($this->BasePath . '/Sections/Cards/PostsVerticalM1/style.scss'), 'PostsVerticalM1');
        return [
          '#type' => 'inline_template',
          '#template' => $fileName,
          '#context' => [
            'cards' => $cards
          ]
        ];
      }
    }
  }

  /**
   * Load defalut vertical PostsVerticalM1
   */
  protected function loadDefaultData__PostsVerticalM1($number = 3)
  {
    $image_style = 'thumbnail';
    // $attributes = \Drupal\image\Entity\ImageStyle::load($image_style)->buildUrl();
    $imgs = $this->defaultImg();
    $cards = [];
    $faker = \Faker\Factory::create();
    $faker->seed(12956812258);
    for ($i = 0; $i < $number; $i ++) {
      $img_url = (isset($imgs[$i])) ? $imgs[$i] : 'themes://defaultfile/CarouselCards/Modele1/photodune-6590781-product-launch-flat-illustration-2-700x400.jpg';
      // $img_url0 = file_create_url(drupal_get_path('theme', $this->themeObject->getName()) . $img_url) . '?itok=_DGxyx-M';
      // dump($img_url0);
      // $img_url0 = \Drupal\image\Entity\ImageStyle::load($image_style)->buildUrl($img_url);
      // dump(file_uri_scheme('themes://mon-image.jpg'));
      $img_url = '/' . drupal_get_path('theme', $this->themeObject->getName()) . $img_url;
      // $img_url0 = ImageStyleTheme::load($image_style);

      $cards[] = [
        'link' => '#',
        'img' => [
          'alt' => '',
          'src' => $img_url
        ],
        'title' => $faker->unique()->realText(rand(30, 50)),
        'date' => '21 Mar 2014'
      ];
    }
    return $cards;
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

  protected function defaultImg()
  {
    return [
      // 'Peuple-Migrateur-Galatee-Films-3921.jpg',
      '/defaultfile/CarouselCards/Modele1/Fotolia_32338952_Subscription_Monthly_XL-700x400.jpg',
      // 'themes://defaultfile/CarouselCards/Modele1/Fotolia_32338952_Subscription_Monthly_XL-700x400.jpg',
      '/defaultfile/CarouselCards/Modele1/Fotolia_33064312_Subscription_Monthly_XXL-700x400.jpg',
      '/defaultfile/CarouselCards/Modele1/photodune-6147544-brainstorming-ideas-with-coffee-m-700x400.jpg',
      '/defaultfile/CarouselCards/Modele1/photodune-6243139-vintage-photography-m-700x400.jpg',
      '/defaultfile/CarouselCards/Modele1/photodune-6252039-web-and-seo-analytics-concept-m-700x400.jpg',
      '/defaultfile/CarouselCards/Modele1/photodune-6590781-product-launch-flat-illustration-2-700x400.jpg'
    ];
  }
}