<?php

namespace Stephane888\HtmlBootstrap\Controller;

use Stephane888\HtmlBootstrap\LoaderDrupal;
use Stephane888\HtmlBootstrap\Traits\Portions;
use Drupal\Component\Utility\Random;
use Stephane888\HtmlBootstrap\ThemeUtility;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Stephane888\HtmlBootstrap\PreprocessTemplate;
use Drupal\Core\Template\Attribute;
use Stephane888\HtmlBootstrap\HelpMigrate;

class Sliders implements ControllerInterface {
  use Portions;
  use StringTranslationTrait;

  protected $BasePath = '';

  protected $themeObject = null;

  function __construct($path = null) {
    $this->BasePath = $path;
    $this->themeObject = \Drupal::theme()->getActiveTheme();
  }

  public static function listModels() {
    return [
      'CarouselBootstrap' => 'Carousel Bootstrap',
      'slidecover' => 'slide cover screen'
    ];
  }

  public static function loadFields($model, &$form, $options) {
    $ThemeUtility = new ThemeUtility();
    if ('slidecover' == $model) {
      /**
       * show_control
       */
      $name = 'show_control';
      $FieldValue = (isset($options[$name])) ? $options[$name] : 1;
      $ThemeUtility->addCheckboxTree($name, $form, 'show_control', $FieldValue);
      /**
       * show_control
       */
      $name = 'show_indicators';
      $FieldValue = (isset($options[$name])) ? $options[$name] : 1;
      $ThemeUtility->addCheckboxTree($name, $form, 'show_indicators', $FieldValue);
      /**
       * list images styles.
       *
       * @var array $styles_images.
       */
      $styles_images = PreprocessTemplate::loadAllStyleMedia();
      $name = 'image_style';
      $FieldValue = (isset($options[$name])) ? $options[$name] : 'large';
      $ThemeUtility->addSelectTree($name, $form, $styles_images, "Selectionner le style d'image", $FieldValue);
      /**
       * show_control
       */
      $name = 'responsive_image';
      $FieldValue = (isset($options[$name])) ? $options[$name] : 0;
      $ThemeUtility->addCheckboxTree($name, $form, 'responsive_image', $FieldValue);

      /**
       * Affiche le block de contenu au dessus.
       */
      $name = 'display_content_bellow';
      $FieldValue = (isset($options[$name])) ? $options[$name] : 1;
      $ThemeUtility->addCheckboxTree($name, $form, 'Affiche le block de contenu au dessus', $FieldValue);

      /**
       * 'no_cover'
       */
      $name = 'no_cover';
      $FieldValue = (isset($options[$name])) ? $options[$name] : 0;
      $ThemeUtility->addCheckboxTree($name, $form, 'image cover', $FieldValue);

      /**
       * list provider content.
       *
       * @var array $styles_images.
       */
      $styles_images = [
        'block' => 'Contenus fourni par les blocks'
      ];
      $name = 'content_provider';
      $FieldValue = $content_provider = (isset($options[$name])) ? $options[$name] : '';
      $ThemeUtility->addSelectTree($name, $form, $styles_images, "Selectionner le provider", $FieldValue);

      /**
       * fornisseur de contenu.
       *
       * @var array $styles_images.
       */
      if ($content_provider == 'block') {
        ManageBlock::addSelectBlockTree($ThemeUtility, $form, $options);
      }

      /**
       */
      $name = 'interval';
      $FieldValue = (isset($options[$name])) ? $options[$name] : 10000;
      $ThemeUtility->addTextfieldTree($name, $form, 'interval entre deux slide', $FieldValue);

      /**
       * le champs description
       */
      $name = 'image_bg';
      $FieldValue = (isset($options[$name])) ? $options[$name] : 1;
      $ThemeUtility->addCheckboxTree($name, $form, 'Placer le slider en arriere plan', $FieldValue);
      /**
       * Le champs nombre de slide.
       */
      $name = 'nombre_slide';
      $FieldValue = $nombre_item = (!empty($options[$name])) ? $options[$name] : 3;
      $ThemeUtility->addTextfieldTree($name, $form, 'nombre_slide', $FieldValue);
      $container = 'carousels';
      for ($i = 0; $i < $nombre_item; $i++) {
        $form[$container][$i] = [
          '#type' => 'details',
          '#title' => 'Slide test : ' . ($i + 1),
          '#open' => false
        ];
        /**
         * le champs texte
         */
        $name = 'content';
        $FieldValue = (isset($options[$container][$i][$name])) ? $options[$container][$i][$name] : '';
        $ThemeUtility->addTextareaSimpleTree($name, $form[$container][$i], 'Titre', $FieldValue);
        /**
         * le champs texte
         */
        $name = 'sup_content';
        $FieldValue = (isset($options[$container][$i][$name])) ? $options[$container][$i][$name] : '';
        $ThemeUtility->addTextareaSimpleTree($name, $form[$container][$i], 'Sous Titre', $FieldValue);
        /**
         * le champs image
         */
        $name = 'image';
        $FieldValue = (isset($options[$container][$i][$name])) ? $options[$container][$i][$name] : '';
        $ThemeUtility->addImageTree($name, $form[$container][$i], 'Image', $FieldValue);
      }
    }
  }

  /**
   * Using default template 'inline_template'
   */
  public function loadFile($options) {
    if (isset($options['type'])) {
      if ($options['type'] == 'CarouselBootstrap') {
        return $this->CarouselBootstrap($options);
      } elseif ('slidecover' == $options['type']) {
        return $this->slidecover($options);
      }
    }
  }

  protected function slidecover($options) {
    /**
     * get id czrousel
     */
    if (isset($options['id_carousel'])) {
      $id_carousel = $options['id_carousel'];
    } else {
      $Random = new Random();
      $id_carousel = $Random->name();
    }

    /**
     * get content.
     */
    if (isset($options['carousels'])) {
      if ($options['provider'] == 'custom') {
        $carousels = $this->getImage($options);
      } else {
        $carousels = $options['carousels'];
      }
    } else {
      $carousels = $this->getDefaultSlideData();
    }
    /**
     * get show_control
     */
    if (isset($options['show_control'])) {
      $show_control = $options['show_control'];
    } else {
      $show_control = false;
    }
    /**
     * get show_control
     */
    if (isset($options['show_indicators'])) {
      $show_indicators = $options['show_indicators'];
    } else {
      $show_indicators = false;
    }
    /**
     * get interval
     */
    if (isset($options['interval'])) {
      $interval = $options['interval'];
    } else {
      $interval = 10000;
    }
    /**
     * Affiche un contenu au dessus
     */
    if (isset($options['display_content_bellow'])) {
      $display_content_bellow = $options['display_content_bellow'];
    } else {
      $display_content_bellow = true;
    }

    /**
     * Affiche un contenu au dessus
     */
    if (isset($options['content_bellow_titre'])) {
      $content_bellow_titre = $options['content_bellow_titre'];
    } else {
      $content_bellow_titre = 'Free work, millions of free quotes in France';
    }

    /**
     * Affiche un contenu au dessus
     */
    if (isset($options['content_bellow_sup_titre'])) {
      $content_bellow_sup_titre = $options['content_bellow_sup_titre'];
    } else {
      $content_bellow_sup_titre = 'If you want to realize your dream immediately click on Free Work or you may regret it one day.';
    }
    /**
     * Affiche un contenu au dessus
     */
    if (isset($options['content_bellow_button'])) {
      $content_bellow_button = $options['content_bellow_button'];
    } else {
      $content_bellow_button = 'Free Works <i class="fas fa-chevron-right pl-1"></i>';
    }
    /**
     * .
     */
    if (isset($options['no_cover'])) {
      $no_cover = $options['no_cover'];
    } else {
      $no_cover = 0;
    }
    /**
     * 'content_provider'
     */
    if (isset($options['content_provider'])) {
      $content_provider = $options['content_provider'];
    } else {
      $content_provider = '';
    }

    /**
     * image en cover en arriere plan ?
     */
    $image_bg = true;
    $slide = false;
    $Attribute = new Attribute();
    if (!$no_cover) {
      $Attribute->addClass('no_cover');
    }

    if ($content_provider == 'block') {
      if (!empty($options['blocks'])) {
        $blocks = [];
        $i = 0;
        foreach ($options['blocks'] as $block) {
          $blocks[$i]['block'] = ManageBlock::loadBlock($block['block']);
          $block_attribute = new Attribute();
          $block_attribute->addClass('kksa888_block');
          $blocks[$i]['attribute'] = $block_attribute;
          $i++;
        }
      }
    } else {
      $blocks = [];
    }
    // dump($blocks);

    LoaderDrupal::addStyle(\file_get_contents($this->BasePath . '/Sections/Sliders/slidecover/style.scss'), 'CarouselBootstrap');
    return [
      '#type' => 'inline_template',
      '#template' => \file_get_contents($this->BasePath . '/Sections/Sliders/slidecover/Drupal.html.twig'),
      '#context' => [
        'carousels' => $carousels,
        'id_carousel' => $id_carousel,
        'show_control' => $show_control,
        'show_indicators' => $show_indicators,
        'interval' => $interval,
        'image_bg' => $image_bg,
        'display_content_bellow' => $display_content_bellow,
        'content_bellow_titre' => $content_bellow_titre,
        'content_bellow_sup_titre' => $content_bellow_sup_titre,
        'slide' => $slide,
        'content_bellow_button' => $content_bellow_button,
        'attribute' => $Attribute,
        'content_provider' => $content_provider,
        'blocks' => $blocks
      ]
    ];
  }

  protected function CarouselBootstrap($options) {

    /**
     * get content.
     */
    if (isset($options['carousels'])) {
      $carousels = $options['carousels'];
    } else {
      $carousels = $this->getDefaultCarouselData();
    }
    /**
     * get id czrousel
     */
    if (isset($options['id_carousel'])) {
      $id_carousel = $options['id_carousel'];
    } else {
      $Random = new Random();
      $id_carousel = $Random->name();
    }
    /**
     * get show_control
     */
    if (isset($options['show_control'])) {
      $show_control = $options['show_control'];
    } else {
      $show_control = true;
    }
    /**
     * get show_control
     */
    if (isset($options['show_indicators'])) {
      $show_indicators = $options['show_indicators'];
    } else {
      $show_indicators = true;
    }
    /**
     * get interval
     */
    if (isset($options['interval'])) {
      $interval = $options['interval'];
    } else {
      $interval = 10000;
    }
    /**
     * image in Bg ?
     */
    if (isset($options['image_bg'])) {
      $image_bg = $options['image_bg'];
    } else {
      $image_bg = true;
    }

    LoaderDrupal::addStyle(\file_get_contents($this->BasePath . '/Sections/Sliders/CarouselBootstrap/style.scss'), 'CarouselBootstrap');
    return [
      '#type' => 'inline_template',
      '#template' => \file_get_contents($this->BasePath . '/Sections/Sliders/CarouselBootstrap/Drupal.html.twig'),
      '#context' => [
        'carousels' => $carousels,
        'id_carousel' => $id_carousel,
        'show_control' => $show_control,
        'show_indicators' => $show_indicators,
        'interval' => $interval,
        'image_bg' => $image_bg
      ]
    ];
  }

  public function getDefaultSlideData() {
    return [
      [
        'content' => $this->templateCenterVertHori('Slider 1', 'bg-cover'),
        'image' => [
          'img_url' => '/' . HelpMigrate::getPatch('theme', $this->themeObject->getName()) . '/defaultfile/Sliders/slidecover/slider1-demo2.jpg'
        ]
      ],
      [
        'content' => $this->templateCenterVertHori('Slider 2', 'bg-cover'),
        'image' => [
          'img_url' => '/' . HelpMigrate::getPatch('theme', $this->themeObject->getName()) . '/defaultfile/Sliders/slidecover/Snowy_Owl_Tom_Ingram.jpg'
        ]
      ],
      [
        'content' => $this->templateCenterVertHori('Slider 3', 'bg-cover'),
        'image' => [
          'img_url' => '/' . HelpMigrate::getPatch('theme', $this->themeObject->getName()) . '/defaultfile/Sliders/slidecover/Ann_and_Chris_Short_Eared_Owl.jpg'
        ]
      ],
      [
        'content' => $this->templateCenterVertHori('Slider 4', 'bg-cover'),
        'image' => [
          'img_url' => '/' . HelpMigrate::getPatch('theme', $this->themeObject->getName()) . '/defaultfile/Sliders/slidecover/Jessica_Drossin_Balance.jpg'
        ]
      ]
    ];
  }

  public static function defineStyleMedia($model, $theme_name) {
    $styles = self::responsiveImage($model);
    $final_styles = [];
    foreach ($styles as $key => $value) {
      $new_key = $theme_name . '_' . $value['image_style'];
      $final_styles[$new_key] = $value;
      $final_styles[$new_key]['label'] = $theme_name . ' : ' . $styles[$key]['label'];
    }
    return PreprocessTemplate::CreateStyles($final_styles);
  }

  public static function responsiveImage($model) {
    $style = [];
    if ($model == 'slidecover') {
      $style = [
        [
          'image_style' => 'slider_home_small',
          'label' => 'slider small',
          'size' => '180w',
          'width' => 128,
          'height' => 72
        ],
        [
          'image_style' => 'slider_home_phone',
          'size' => '475w',
          'label' => 'slider phone',
          'width' => 480,
          'height' => 270
        ],
        [
          'image_style' => 'slider_home_tablette',
          'label' => 'slider tablette',
          'size' => '992w',
          'width' => 992,
          'height' => 55
        ],
        [
          'image_style' => 'slider_home_lg_desktop',
          'label' => 'slider_home_lg_desktop',
          'size' => '1920w',
          'width' => 1452,
          'height' => 817
        ],
        [
          'image_style' => 'slider_home_lx_desktop',
          'label' => 'slider_home_lx_desktop',
          'size' => '1920w',
          'width' => 1920,
          'height' => 1080
        ],
        [
          'image_style' => 'slider_home',
          'size' => '3840w',
          'label' => 'slider LXX home ',
          'width' => 3840,
          'height' => 2160
        ]
      ];
    }
    return $style;
  }

  public function getDefaultCarouselData() {
    return [
      [
        'content' => $this->templateCenterVertHori('Slider 1', 'bg-cover'),
        'image' => [
          'img_url' => '/' . HelpMigrate::getPatch('theme', $this->themeObject->getName()) . '/defaultfile/CarouselBootstrap/images/banner1.jpg'
        ]
      ],
      [
        'content' => $this->templateCenterVertHori('Slider 2', 'bg-cover'),
        'image' => [
          'img_url' => '/' . HelpMigrate::getPatch('theme', $this->themeObject->getName()) . '/defaultfile/CarouselBootstrap/images/banner2.jpg'
        ]
      ],
      [
        'content' => $this->templateCenterVertHori('Slider 3', 'bg-cover'),
        'image' => [
          'img_url' => '/' . HelpMigrate::getPatch('theme', $this->themeObject->getName()) . '/defaultfile/CarouselBootstrap/images/banner3.jpg'
        ]
      ],
      [
        'content' => $this->templateCenterVertHori('Slider 4', 'bg-cover'),
        'image' => [
          'img_url' => '/' . HelpMigrate::getPatch('theme', $this->themeObject->getName()) . '/defaultfile/CarouselBootstrap/images/banner4.jpg'
        ]
      ]
    ];
  }

  protected function getImage($options) {
    $first = true;
    if (isset($options['image_bg'])) {
      $image_bg = $options['image_bg'];
    } else {
      $image_bg = true;
    }
    $carousels = $options['carousels'];
    if ($options['responsive_image']) {
      $this->setSyleImage(self::responsiveImage($options['type']));
      $responsive_image = $options['responsive_image'];
    } else {
      $responsive_image = false;
    }
    foreach ($carousels as $key => $carousel) {
      if (!empty($carousel['image'])) {

        $Attribute = new Attribute();
        if ($first) {
          $Attribute->addClass('active');
        }
        if ($image_bg) {
          if ($responsive_image) {
            $preload_image = $this->getImageUrlByFid($carousel['image'], $this->themeObject->getName() . '_slider_home_small');
            $Attribute->setAttribute('style', 'background-image:url(' . $preload_image['img_url'] . ')');
            //
            $carousels[$key]['image'] = $this->getImagesSliderResponssive($carousel['image'], $this->themeObject->getName());
            $image_responsive = '';
            foreach ($carousels[$key]['image'] as $img_list) {
              $image_responsive .= $img_list['img_url'] . ' ' . $img_list['size'] . ',';
            }
            $Attribute->setAttribute('data-bgset', $image_responsive);
            $Attribute->setAttribute('data-sizes', 'auto');
          } else {
            $carousels[$key]['image'] = $this->getImageUrlByFid($carousel['image'], $options['image_style']);
            $Attribute->setAttribute('style', 'background-image:url(' . $carousels[$key]['image']['img_url'] . ')');
          }
        }
        $carousels[$key]['attribute'] = $Attribute;
      }
      $first = false;
    }
    return $carousels;
  }
}