<?php
namespace Stephane888\HtmlBootstrap;

use Symfony\Component\HttpFoundation\Session\Session;
use Stephane888\HtmlBootstrap\Controller\TopHeader;
use Stephane888\HtmlBootstrap\Controller\Headers;
use Stephane888\HtmlBootstrap\Controller\Sliders;
use Stephane888\HtmlBootstrap\Controller\ImageTextRightLeft;
use Stephane888\HtmlBootstrap\Controller\Cards;
use Stephane888\HtmlBootstrap\Controller\CarouselCards;
use Stephane888\HtmlBootstrap\Controller\CallActions;
use Stephane888\HtmlBootstrap\Controller\Comments;
use Stephane888\HtmlBootstrap\Controller\Footers;
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
   * get CarouselCards
   */
  public function getCarouselCards($options)
  {
    $Sliders = new CarouselCards($this->BasePath);
    return $Sliders->loadFile($options);
  }

  /**
   * get CallActions
   */
  public function getCallActions($options)
  {
    $Sliders = new CallActions($this->BasePath);
    return $Sliders->loadFile($options);
  }

  /**
   * get Comments
   */
  public function getComments($options)
  {
    $Sliders = new Comments($this->BasePath);
    return $Sliders->loadFile($options);
  }

  /**
   * get Footers
   */
  public function getFooters($options)
  {
    $Sliders = new Footers($this->BasePath);
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

  /**
   * Filtre l'affichage en function de la nom de la route.
   * les routes ou parametres sont separés par |
   *
   * @param string $route_lits
   * @param string $listes_parameters
   * @return boolean
   */
  public function filterByRouteName($route_lits = '', $listes_parameters = '', $list_type_node = '')
  {
    $RouteName = \Drupal::routeMatch()->getRouteName();
    $nid = \Drupal::routeMatch()->getParameter('node');
    $route_frontPage = "view.frontpage.page_1";
    $route_node = "entity.node.canonical";
    // strpos
    if ($route_lits == '' || ! $route_lits)
      return true;

    $routes = \explode('|', $route_lits);
    foreach ($routes as $route) {
      if ($route != '') {
        /**
         * page d'accuiel
         */
        if ($RouteName == $route_frontPage && $route_frontPage == $route) {
          return true;
        } /**
         * page de nodes
         */
        elseif ($RouteName == $route_frontPage && $route_node == $route) {
          /**
           * le type de node est prioritaire.
           * Si le type de node est definie, on ne regarde plus les paramettres.
           * les nodes de type page, doivent utiliser les parametres.
           * les nodes de type article, films ... doivent utiliser le type de node.
           */
          if ($listes_parameters != '') {
            $parameters = \explode("|", $listes_parameters);
            foreach ($parameters as $parameter) {
              if ($parameter == $nid) {
                return true;
              }
            }
          }
        }
      }
    }
    return false;
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