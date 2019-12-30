<?php
namespace Stephane888\HtmlBootstrap;

use Symfony\Component\HttpFoundation\Session\Session;
use Stephane888\HtmlBootstrap\Traits\Examples;
use Stephane888\HtmlBootstrap\Controller\Headers;
use Stephane888\HtmlBootstrap\Controller\Sliders;

/**
 *
 * @author stephane
 *
 */
class LoaderDrupal {

  protected $BasePath = '';
  use Examples;

  function __construct()
  {
    $this->BasePath = __DIR__;
    $Session = new Session();
    $Session->remove('theme_style');
  }

  /**
   * ||___________________ SECTION HEADERS _____________________||
   */

  /**
   * Section headers [logo center]
   */
  public function getSectionHeaders($options)
  {
    $Headers = new Headers($this->BasePath);
    return $Headers->loadHeaderFile($options);
  }

  /**
   * Section Sliders
   */
  public function getSectionSliders($options)
  {
    $Sliders = new Sliders($this->BasePath);
    return $Sliders->loadSliderFile($options);
  }

  /**
   */
  public function putInHtmlTag($datas, $tag = 'div')
  {
    return [
      '#type' => 'html_tag',
      '#tag' => $tag,
      '#attributes' => [
        'class' => []
      ],
      '#value' => $datas
    ];
  }

  /**
   * ff
   */
  public static function addStyle($style)
  {
    if (LOAD_SCSS_BY_SESSION) {
      // dump('addStyle');
      $Session = new Session();
      $styles = $Session->get('theme_style', []);
      // dump($styles);
      $styles[] = $style;
      $Session->set('theme_style', $styles);
      // dump($styles);
    }
  }
}