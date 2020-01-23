<?php
namespace Stephane888\HtmlBootstrap\Controller;

use Stephane888\HtmlBootstrap\Traits\Portions;
use Stephane888\HtmlBootstrap\LoaderDrupal;
use Stephane888\HtmlBootstrap\ThemeUtility;
use Drupal\debug_log\debugLog;

class ImageTextRightLeft implements ControllerInterface {
  use Portions;

  protected $BasePath = '';

  protected $themeObject = null;

  function __construct($path = null)
  {
    $this->BasePath = $path;
    $this->themeObject = \Drupal::theme()->getActiveTheme();
  }

  /**
   * Load file headers and pass variable.
   * Using default template 'inline_template'
   */
  public function loadFile($options)
  {
    /**
     * Get type
     */
    if (isset($options['type'])) {
      if ($options['type'] == 'default') {
        /**
         * get header
         */
        if (isset($options['header'])) {
          $header = $options['header'];
        } else {
          $header = false;
        }
        /**
         * get content left
         */
        if (isset($options['ContentLeft'])) {
          $ContentLeft = $options['ContentLeft'];
        } else {
          $img = '/defaultfile/CarouselBootstrap/images/ab.jpg';
          $ContentLeft = $this->template_img($img);
        }

        /**
         * Get content right
         */
        if (isset($options['ContentRight'])) {
          $ContentRight = $options['ContentRight'];
        } else {
          $ContentRight = [];
          $ContentRight[] = $this->template_htmltag('We are the Best', 'h2');
          $ContentRight[] = $this->template_htmltag($this->getFauxTexte());
          $ContentRight = $this->template_inline_template($ContentRight);
          $ContentRight = $this->templateCenterVertHori($ContentRight, 'flex-column');
        }
        $filename = \file_get_contents($this->BasePath . '/Sections/ImageTextRightLeft/Default/Drupal.html.twig');
        return [
          '#type' => 'inline_template',
          '#template' => $filename,
          '#context' => [
            'header' => $header,
            'ContentLeft' => $ContentLeft,
            'ContentRight' => $ContentRight
          ]
        ];
      } elseif ($options['type'] == 'ModelM1') {
        return $this->loadModelM1($options);
      } elseif ($options['type'] == 'ModelM2') {
        return $this->loadModelM2($options);
      } elseif ($options['type'] == 'ModelM3') {
        return $this->loadModelM3($options);
      }
    }
  }

  public static function listModels()
  {
    return [
      'default' => 'default',
      'ModelM1' => 'ModelM1',
      'ModelM2' => 'ModelM2',
      'ModelM3' => 'ModelM3'
    ];
  }

  public static function loadFields($model, &$form, $options)
  {
    $ThemeUtility = new ThemeUtility();
    if ($model == 'ModelM1') {
      /**
       * le champs titre
       */
      $name = 'title';
      $FieldValue = (! empty($options[$name])) ? $options[$name] : '';
      $ThemeUtility->addTextfieldTree($name, $form, 'Titre', $FieldValue);
      /**
       * le champs description
       */
      $name = 'text';
      $FieldValue = (! empty($options[$name])) ? $options[$name] : '';
      $ThemeUtility->addTextareaTree($name, $form, 'Description', $FieldValue);
      // debugLog::logs($form, '_theme_builder_' . $model, 'dump', true);
    } elseif ($model == 'ModelM2') {
      ;
    } elseif ($model == 'ModelM3') {
      ;
    }
  }

  protected function loadModelM3($options)
  {
    /**
     * Get content img_before
     */
    if (isset($options['img_before'])) {
      $img_before = $options['img_before'];
    } else {
      $img_before = true;
    }

    /**
     * Get content img_before
     */
    if (isset($options['img'])) {
      $img = $options['img'];
    } else {
      $img = [
        'img_url' => '/' . drupal_get_path('theme', $this->themeObject->getName()) . '/defaultfile/ImageTextRightLeft/ModelM2/21205351-portrait-de-confiance-jeune-homme-d-affaires-avec-les-bras-croisés-dans-le-bureau.jpg',
        'img_alt' => '',
        'img_class' => ''
      ];
    }
    /**
     * Get content img_small
     */
    if (isset($options['img_small'])) {
      $img_small = $options['img_small'];
    } else {
      $img_small = [
        'img_url' => '/' . drupal_get_path('theme', $this->themeObject->getName()) . '/defaultfile/ImageTextRightLeft/ModelM2/portrait-homme-affaires-afro-americain-attrayant-souriant-exterieur_33839-1295.jpg',
        'img_alt' => '',
        'img_class' => ''
      ];
    }

    /**
     * Get content sup_title
     */
    if (isset($options['sup_title'])) {
      $sup_title = $options['sup_title'];
    } else {
      $sup_title = '<i class="fas fa-hammer"></i> <span>30 year term life insurance </span>';
    }

    /**
     * Get content header_title
     */
    if (isset($options['header_title'])) {
      $header_title = $options['header_title'];
    } else {
      $header_title = 'Welcome 30 year term life insurance ';
    }
    /**
     * Get content description
     */
    if (isset($options['header_description'])) {
      $header_description = $options['header_description'];
    } else {
      $faker = \Faker\Factory::create();
      $faker->seed(129888882258);
      $header_description = $faker->unique()->realText(rand(110, 130));
    }

    /**
     * Get content description
     */
    if (isset($options['description'])) {
      $description = $options['description'];
    } else {
      $faker = \Faker\Factory::create();
      $faker->seed(129888882258);
      $description = $faker->unique()->realText(rand(220, 250));
    }

    /**
     * Get content lists
     */
    if (isset($options['lists'])) {
      $lists = $options['lists'];
    } else {
      $lists = [];
      $faker = \Faker\Factory::create();
      $faker->seed(129888882258);
      for ($i = 0; $i <= 2; $i ++) {
        $lists[] = [
          'text' => $faker->unique()->realText(rand(15, 30))
        ];
      }
    }

    /**
     * Get content header_title
     */
    if (isset($options['title'])) {
      $title = $options['title'];
    } else {
      $title = 'Provider you with quality competitive coverage';
    }

    /**
     * Get content button
     */
    if (isset($options['button'])) {
      $button = $options['button'];
    } else {
      $button = 'ours services';
    }

    $filename = \file_get_contents($this->BasePath . '/Sections/ImageTextRightLeft/ModelM3/Drupal.html.twig');
    LoaderDrupal::addStyle(\file_get_contents($this->BasePath . '/Sections/ImageTextRightLeft/ModelM3/style.scss'), 'ImageTextRightLeft-ModelM3');
    return [
      '#type' => 'inline_template',
      '#template' => $filename,
      '#context' => [
        'img' => $img,
        'img_small' => $img_small,
        'sup_title' => $sup_title,
        'title' => $title,
        'button' => $button,
        'img_before' => $img_before,
        'header_title' => $header_title,
        'description' => $description,
        'lists' => $lists,
        'header_description' => $header_description
      ]
    ];
  }

  /**
   *
   * @param string $options
   * @return array
   */
  protected function loadModelM2($options)
  {
    /**
     * Get content img_before
     */
    if (isset($options['img_before'])) {
      $img_before = $options['img_before'];
    } else {
      $img_before = false;
    }

    /**
     * Get content img_before
     */
    if (isset($options['img'])) {
      $img = $options['img'];
    } else {
      $img = [
        'img_url' => '/' . drupal_get_path('theme', $this->themeObject->getName()) . '/defaultfile/ImageTextRightLeft/ModelM2/portrait-homme-affaires-afro-americain-attrayant-souriant-exterieur_33839-1295.jpg',
        'img_alt' => '',
        'img_class' => ''
      ];
    }

    /**
     * Get content img_before
     */
    if (isset($options['sup_title'])) {
      $sup_title = $options['sup_title'];
    } else {
      $sup_title = 'Welcome to assurance company';
    }

    /**
     * Get content img_before
     */
    if (isset($options['button'])) {
      $button = $options['button'];
    } else {
      $button = 'Make appointment';
    }

    /**
     * Get content img_before
     */
    if (isset($options['title'])) {
      $title = $options['title'];
    } else {
      $title = 'Get insurance for your better future';
    }

    /**
     * Get content img_before
     */
    if (isset($options['background_url'])) {
      $background_url = $options['background_url'];
    } else {
      $background_url = '/' . drupal_get_path('theme', $this->themeObject->getName()) . '/defaultfile/bg/white-fence.jpg';
    }

    /**
     *
     * @var Ambiguous $filename
     */
    $filename = \file_get_contents($this->BasePath . '/Sections/ImageTextRightLeft/ModelM2/Drupal.html.twig');
    LoaderDrupal::addStyle(\file_get_contents($this->BasePath . '/Sections/ImageTextRightLeft/ModelM2/style.scss'), 'ImageTextRightLeft-ModelM2');
    return [
      '#type' => 'inline_template',
      '#template' => $filename,
      '#context' => [
        'img' => $img,
        'sup_title' => $sup_title,
        'title' => $title,
        'button' => $button,
        'img_before' => $img_before,
        'background_url' => $background_url
      ]
    ];
  }

  /**
   *
   * @param array $options
   * @return string[]|string[][]
   */
  protected function loadModelM1($options)
  {
    /**
     * Get content img_before
     */
    if (isset($options['img_before'])) {
      $img_before = $options['img_before'];
    } else {
      $img_before = false;
    }
    /**
     * Get content header
     */
    if (isset($options['header'])) {
      $header = $options['header'];
    } else {
      $header = '';
    }
    /**
     * Get content img_url
     */
    if (isset($options['img_url'])) {
      $img_url = $options['img_url'];
    } else {
      $img_url = '/' . drupal_get_path('theme', $this->themeObject->getName()) . '/defaultfile/ImageTextRightLeft/ModelM1/flash-screenshot.png';
    }
    /**
     * Get content img_alt
     */
    if (isset($options['img_alt'])) {
      $img_alt = $options['img_alt'];
    } else {
      $img_alt = '';
    }
    /**
     * Get content img_class
     */
    if (isset($options['img_class'])) {
      $img_class = $options['img_class'];
    } else {
      $img_class = '';
    }
    /**
     * Get content title.
     */
    if (isset($options['title'])) {
      $title = $options['title'];
    } else {
      $faker = \Faker\Factory::create();
      $faker->seed(129888882258);
      $title = $faker->realText(rand(30, 50));
    }
    /**
     * Get content title.
     */
    if (isset($options['text'])) {
      $text = $options['text'];
    } else {
      $faker = \Faker\Factory::create();
      $faker->seed(129888882258);
      $text = $faker->realText(rand(300, 320));
    }
    /**
     * Get content button.
     */
    if (isset($options['button'])) {
      $button = $options['button'];
    } else {
      $button = 'Make appointment';
    }

    $filename = \file_get_contents($this->BasePath . '/Sections/ImageTextRightLeft/ModelM1/Drupal.html.twig');
    LoaderDrupal::addStyle(\file_get_contents($this->BasePath . '/Sections/ImageTextRightLeft/ModelM1/style.scss'), 'ImageTextRightLeft-ModelM1');
    return [
      '#type' => 'inline_template',
      '#template' => $filename,
      '#context' => [
        'header' => $header,
        'img_url' => $img_url,
        'img_alt' => $img_alt,
        'img_class' => $img_class,
        'title' => $title,
        'text' => $text,
        'button' => $button,
        'img_before' => $img_before
      ]
    ];
  }
}