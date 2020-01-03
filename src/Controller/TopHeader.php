<?php
namespace Stephane888\HtmlBootstrap\Controller;

use Stephane888\HtmlBootstrap\Traits\Portions;
use Stephane888\HtmlBootstrap\LoaderDrupal;

class TopHeader {
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
     * get left content
     */
    if (isset($options['ContentLeft'])) {
      $ContentLeft = $options['ContentLeft'];
    } else {
      $ContentLeft = [];
      $ContentLeft[] = $this->template_htmltag('<i class="fas fa-phone "></i> 1800-222-222', 'span');
      $ContentLeft[] = $this->template_htmltag('<i class="fas fa-envelope "></i> contact@clevercoursewptheme.com', 'span');
      $ContentLeft = $this->template_inline_template($ContentLeft, "d-flex flex-wrap header-info h-100 align-items-center");
    }

    /**
     * get content right
     */
    if (isset($options['ContentRight'])) {
      $ContentRight = $options['ContentRight'];
    } else {
      $rx_logo = $this->getdefault_rx_logos();
      $rx_logo = $this->template_rx_logos($rx_logo, 'flat');
      $ContentRight = $rx_logo;
    }
    if (isset($options['account_menu'])) {
      $ContentRight["#weight"] = - 100;
      $ContentRight = [
        $ContentRight
      ];
      $ContentRight[] = $options['account_menu'];
      $ContentRight = $this->template_inline_template($ContentRight, "d-flex flex-wrap header-info h-100 align-items-center justify-content-end");
    }

    /**
     * Get type
     */
    if (isset($options['type'])) {
      if ($options['type'] == 'default') {
        $filename = \file_get_contents($this->BasePath . '/Sections/Headers/TopHeader/Drupal.html.twig');
        LoaderDrupal::addStyle(\file_get_contents($this->BasePath . '/Sections/Headers/TopHeader/style.scss'), 'TopHeader');
      }
    } else {
      $filename = \file_get_contents($this->BasePath . '/Sections/Headers/TopHeader/Drupal.html.twig');
      LoaderDrupal::addStyle(\file_get_contents($this->BasePath . '/Sections/Headers/TopHeader/style.scss'), 'TopHeader');
    }
    return [
      '#type' => 'inline_template',
      '#template' => $filename,
      '#context' => [
        'ContentLeft' => $ContentLeft,
        'ContentRight' => $ContentRight
      ]
    ];
  }
}