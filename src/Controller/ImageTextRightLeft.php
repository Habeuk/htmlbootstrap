<?php
namespace Stephane888\HtmlBootstrap\Controller;

use Stephane888\HtmlBootstrap\Traits\Portions;
use Stephane888\HtmlBootstrap\LoaderDrupal;

class ImageTextRightLeft {
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
     * get header
     */
    if (isset($options['header'])) {
      $header = $options['header'];
    } else {
      $header = false;
    }

    /**
     * Get type
     */
    if (isset($options['type'])) {
      if ($options['type'] == 'default') {
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
      }
    }
  }

  public static function listModels()
  {
    return [
      'default' => 'default',
      'ModelM1' => 'ModelM1'
    ];
  }

  protected function loadModelM1($options)
  {
    /**
     * Get content img_url
     */
    if (isset($options['img_url'])) {
      $img_url = $options['img_url'];
    } else {
      $img_url = '/' . drupal_get_path('theme', $this->themeObject->getName()) . '/defaultfile/CarouselBootstrap/images/ab.jpg';
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
     * Get content title
     */
    if (isset($options['title'])) {
      $title = $options['title'];
    } else {
      $title = '';
    }
    /**
     * Get content title
     */
    if (isset($options['text'])) {
      $text = $options['text'];
    } else {
      $text = '';
    }
    /**
     * Get content button
     */
    if (isset($options['button'])) {
      $button = $options['button'];
    } else {
      $button = '';
    }

    $filename = \file_get_contents($this->BasePath . '/Sections/ImageTextRightLeft/ModelM1/Drupal.html.twig');
    LoaderDrupal::addStyle(\file_get_contents($this->BasePath . '/Sections/ImageTextRightLeft/ModelM1/style.scss'), 'ModelM1');
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
        'button' => $button
      ]
    ];
  }
}