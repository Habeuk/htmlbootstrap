<?php
namespace Stephane888\HtmlBootstrap;

use Symfony\Component\HttpFoundation\Session\Session;
use Stephane888\HtmlBootstrap\Controller\TopHeader;
use Stephane888\HtmlBootstrap\Controller\Headers;
use Stephane888\HtmlBootstrap\Controller\Sliders;
use Stephane888\HtmlBootstrap\Controller\ImageTextRightLeft;
use Stephane888\HtmlBootstrap\Controller\Cards;
use Stephane888\HtmlBootstrap\Traits\Examples;
use Stephane888\HtmlBootstrap\Traits\DrupalUtility;

/**
 *
 * @author stephane
 *
 */
class LoaderDrupal {

  protected $BasePath = '';
  use Examples;
  use DrupalUtility;

  function __construct()
  {
    $this->BasePath = __DIR__;
    // $Session = new Session();
    // $Session->remove('theme_style');
    $this->getDefautStyle();
  }

  /**
   * ||___________________ SECTION HEADERS _____________________||
   */

  /**
   * get top headers
   */
  public function getSectionTopHeaders($options)
  {
    $Headers = new TopHeader($this->BasePath);
    return $Headers->loadFile($options);
  }

  /**
   * Section headers [logo center]
   */
  public function getSectionHeaders($options)
  {
    $Headers = new Headers($this->BasePath);
    return $Headers->loadHeaderFile($options);
  }

  /**
   * ||___________________ SECTION CONTENT _____________________||
   */

  /**
   * Section Sliders
   */
  public function getSectionSliders($options)
  {
    $Sliders = new Sliders($this->BasePath);
    return $Sliders->loadSliderFile($options);
  }

  /**
   * get cards
   */
  public function getCards($options)
  {
    $Sliders = new Cards($this->BasePath);
    return $Sliders->loadFile($options);
  }

  /**
   * load default slider
   */
  public function getImageTextRightLeft($options)
  {
    $ImageTextRightLeft = new ImageTextRightLeft($this->BasePath);
    return $ImageTextRightLeft->loadFile($options);
  }

  /**
   * ff
   */
  public static function addStyle($style, $key)
  {
    if (LOAD_SCSS_BY_SESSION) {
      // dump('addStyle');
      $Session = new Session();
      $styles = $Session->get('theme_style', []);
      // dump($key);
      $styles[$key] = $style;
      $Session->set('theme_style', $styles);
      // dump($styles);
    }
  }

  public static function addScript($style, $key)
  {
    if (LOAD_SCSS_BY_SESSION) {
      $Session = new Session();
      $styles = $Session->get('theme_script', []);
      $styles[$key] = $style;
      $Session->set('theme_script', $styles);
    }
  }

  private function getDefautStyle()
  {
    $Session = new Session();
    $styles = $Session->get('theme_style', []);
    $styles['init'] = '@import "../scss/defaut/models.scss";';
    $styles['init2'] = '@import "../scss/defaut/defaultStyle.scss";';
    $Session->set('theme_style', $styles);
  }
}