<?php
namespace Stephane888\HtmlBootstrap\Controller;

use Stephane888\HtmlBootstrap\Traits\Portions;

class ImageTextRightLeft {
  use Portions;

  protected $BasePath = '';

  function __construct($path = null)
  {
    $this->BasePath = $path;
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
      $header = '';
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
    /**
     * Get type
     */
    if (isset($options['type'])) {
      if ($options['type'] == 'default') {
        $filename = \file_get_contents($this->BasePath . '/Sections/ImageTextRightLeft/Default/Drupal.html.twig');
      }
    } else {
      $filename = \file_get_contents($this->BasePath . '/Sections/ImageTextRightLeft/Default/Drupal.html.twig');
    }
    return [
      '#type' => 'inline_template',
      '#template' => $filename,
      '#context' => [
        'header' => $header,
        'ContentLeft' => $ContentLeft,
        'ContentRight' => $ContentRight
      ]
    ];
  }
}