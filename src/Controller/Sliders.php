<?php
namespace Stephane888\HtmlBootstrap\Controller;

use Stephane888\HtmlBootstrap\LoaderDrupal;
use Stephane888\HtmlBootstrap\Traits\Portions;
use Drupal\Component\Utility\Random;

class Sliders {
  use Portions;

  protected $BasePath = '';

  function __construct($path = null)
  {
    $this->BasePath = $path;
  }

  /**
   * Using default template 'inline_template'
   */
  public function loadSliderFile($options)
  {
    $Random = new Random();
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

    if (isset($options['type']) && $options['type'] == 'CarouselBootstrap') {
      LoaderDrupal::addStyle(\file_get_contents($this->BasePath . '/Sections/Sliders/CarouselBootstrap/style.scss'));
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
  }

  public function getDefaultCarouselData()
  {
    return [
      [
        'content' => 'Slider 1',
        'image' => [
          'url' => drupal_get_path('theme', 'theme_builder') . '/defaultfile/CarouselBootstrap/images/banner1.jpg'
        ]
      ],
      [
        'content' => 'Slider 2',
        'image' => [
          'url' => drupal_get_path('theme', 'theme_builder') . '/defaultfile/CarouselBootstrap/images/banner2.jpg'
        ]
      ],
      [
        'content' => 'Slider 3',
        'image' => [
          'url' => drupal_get_path('theme', 'theme_builder') . '/defaultfile/CarouselBootstrap/images/banner3.jpg'
        ]
      ],
      [
        'content' => 'Slider 4',
        'image' => [
          'url' => drupal_get_path('theme', 'theme_builder') . '/defaultfile/CarouselBootstrap/images/banner4.jpg'
        ]
      ]
    ];
  }
}