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
  {}
}