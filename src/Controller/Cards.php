<?php
namespace Stephane888\HtmlBootstrap\Controller;

use Stephane888\HtmlBootstrap\LoaderDrupal;
use Stephane888\HtmlBootstrap\Traits\Portions;
use Stephane888\HtmlBootstrap\Entity\ImageStyleTheme;
use Stephane888\HtmlBootstrap\ThemeUtility;

class Cards implements ControllerInterface {
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
          $number = 8;
          $cards = $this->loadDefaultData($number);
        }
        $fileName = \file_get_contents($this->BasePath . '/Sections/Cards/IconeModelFlat/Drupal.html.twig');
        LoaderDrupal::addStyle(\file_get_contents($this->BasePath . '/Sections/Cards/IconeModelFlat/style.scss'), 'Cards-IconeModelFlat');
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
        LoaderDrupal::addStyle(\file_get_contents($this->BasePath . '/Sections/Cards/PostsVerticalM1/style.scss'), 'Cards-PostsVerticalM1');
        return [
          '#type' => 'inline_template',
          '#template' => $fileName,
          '#context' => [
            'cards' => $cards
          ]
        ];
      } elseif ($options['type'] == 'CardsModel2') {
        /**
         * get class of blocs
         */
        if (isset($options['title'])) {
          $title = $options['title'];
        } else {
          $title = "Future on the producty";
        }
        /**
         * get class of description
         */
        if (isset($options['description'])) {
          $description = $options['description'];
        } else {
          $faker = \Faker\Factory::create();
          $faker->seed(12956812258);
          $description = $faker->unique()->realText(rand(100, 110));
        }
        if (empty($cards)) {
          $number = (isset($options['nombre_item'])) ? $options['nombre_item'] : 4;
          $cards = $this->loadDefaultData($number);
          $card_class_block = "col-lg-6";
        }
        $fileName = \file_get_contents($this->BasePath . '/Sections/Cards/Model2/Drupal.html.twig');
        LoaderDrupal::addStyle(\file_get_contents($this->BasePath . '/Sections/Cards/Model2/style.scss'), 'Cards-Model2');
        return [
          '#type' => 'inline_template',
          '#template' => $fileName,
          '#context' => [
            'cards' => $cards,
            'title' => $title,
            'description' => $description,
            'card_class_block' => $card_class_block
          ]
        ];
      } elseif ($options['type'] == 'StepModel1') {
        /**
         * Get class of title
         */
        if (isset($options['title'])) {
          $title = $options['title'];
        } else {
          $title = "Imaginez, Créez, Vendez";
        }
        if (empty($cards)) {
          $number = (isset($options['nombre_item'])) ? $options['nombre_item'] : 4;
          $cards = $this->loadDefaultData_StepModel1($number);
          $card_class_block = "col-lg-3";
        }
        $fileName = \file_get_contents($this->BasePath . '/Sections/Cards/StepModel1/Drupal.html.twig');
        LoaderDrupal::addStyle(\file_get_contents($this->BasePath . '/Sections/Cards/StepModel1/style.scss'), 'Cards-StepModel1');
        return [
          '#type' => 'inline_template',
          '#template' => $fileName,
          '#context' => [
            'cards' => $cards,
            'title' => $title,
            'card_class_block' => $card_class_block
          ]
        ];
      }
    }
  }

  /**
   *
   * @param number $number
   * @return string[][]
   */
  protected function loadDefaultData_StepModel1($number = 3)
  {
    $cards = [];
    $faker = \Faker\Factory::create();
    for ($i = 0; $i < $number; $i ++) {
      $cards[$i] = [
        'text' => $faker->unique()->realText(rand(70, 90))
      ];
    }
    return $cards;
  }

  /**
   * Load defalut vertical PostsVerticalM1
   */
  protected function loadDefaultData__PostsVerticalM1($number = 3)
  {
    // $image_style = 'thumbnail';
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

  public static function listModels()
  {
    return [
      'IconeModelFlat' => 'IconeModelFlat',
      'PostsVerticalM1' => 'PostsVerticalM1',
      'CardsModel2' => 'CardsModel2',
      'StepModel1' => 'StepModel1'
    ];
  }

  public static function loadFields($model, &$form, $options)
  {
    $ThemeUtility = new ThemeUtility();

    if ($model == 'CardsModel2') {
      /**
       * le champs titre
       */
      $name = 'title';
      $FieldValue = (! empty($options[$name])) ? $options[$name] : '';
      $ThemeUtility->addTextfieldTree($name, $form, 'Titre', $FieldValue);
      /**
       * le champs description
       */
      $name = 'description';
      $FieldValue = (! empty($options[$name])) ? $options[$name] : '';
      $ThemeUtility->addTextareaTree($name, $form, 'Description', $FieldValue);
      /**
       * class card_class_block
       */
      $name = 'card_class_block';
      $FieldValue = (! empty($options[$name])) ? $options[$name] : 'col-lg-6';
      $ThemeUtility->addTextfieldTree($name, $form, 'Class colonne bootstrap', $FieldValue);
      /**
       * le champs nombre_item
       */
      $name = 'nombre_item';
      $nombre_item = $FieldValue = (! empty($options[$name])) ? $options[$name] : 4;
      $ThemeUtility->addTextfieldTree($name, $form, 'Nombre de blocs', $FieldValue);
      $container = 'cards';

      for ($i = 0; $i < $nombre_item; $i ++) {
        $form[$container][$i] = [
          '#type' => 'details',
          '#title' => 'Blocs : ' . ($i + 1),
          '#open' => false
        ];
        /**
         * le champs titre
         */
        $name = 'title';
        $FieldValue = (! empty($options[$container][$i][$name])) ? $options[$container][$i][$name] : '';
        $ThemeUtility->addTextfieldTree($name, $form[$container][$i], 'Titre', $FieldValue);
        /**
         * le champs text
         */
        $name = 'text';
        $FieldValue = (! empty($options[$container][$i][$name])) ? $options[$container][$i][$name] : '';
        $ThemeUtility->addTextareaTree($name, $form[$container][$i], 'Description ', $FieldValue);
        /**
         * le champs icone
         */
        $name = 'icone';
        $FieldValue = (! empty($options[$container][$i][$name])) ? $options[$container][$i][$name] : '';
        $ThemeUtility->addTextfieldTree($name, $form[$container][$i], 'Icone fontawesome', $FieldValue);
        /**
         * le champs link
         */
        $name = 'link';
        $FieldValue = (! empty($options[$container][$i][$name])) ? $options[$container][$i][$name] : '';
        $ThemeUtility->addTextfieldTree($name, $form[$container][$i], 'Lien vers le contenu', $FieldValue);
      }
    } elseif ($model == 'StepModel1') {
      /**
       * le champs titre
       */
      $name = 'title';
      $FieldValue = (! empty($options[$name])) ? $options[$name] : '';
      $ThemeUtility->addTextfieldTree($name, $form, 'Titre', $FieldValue);
      /**
       * class card_class_block
       */
      $name = 'card_class_block';
      $FieldValue = (! empty($options[$name])) ? $options[$name] : 'col-lg-3';
      $ThemeUtility->addTextfieldTree($name, $form, 'Class colonne bootstrap', $FieldValue);
      /**
       * Le champs nombre_item
       */
      $name = 'nombre_item';
      $nombre_item = $FieldValue = (! empty($options[$name])) ? $options[$name] : 4;
      $ThemeUtility->addTextfieldTree($name, $form, 'Nombre de blocs', $FieldValue);
      $container = 'cards';
      for ($i = 0; $i < $nombre_item; $i ++) {
        $form[$container][$i] = [
          '#type' => 'details',
          '#title' => 'Blocs : ' . ($i + 1),
          '#open' => false
        ];
        /**
         * Le champs text
         */
        $name = 'text';
        $FieldValue = (! empty($options[$container][$i][$name])) ? $options[$container][$i][$name] : '';
        $ThemeUtility->addTextareaSimpleTree($name, $form[$container][$i], 'Description ', $FieldValue);
      }
    }
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