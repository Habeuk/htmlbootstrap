<?php
/**
 * Search grep -rnw '/siteweb/PluginsModules/stephane888' -e 'MenuCenter/style.scss'
 *
 *
 */
namespace Stephane888\HtmlBootstrap;

use Symfony\Component\HttpFoundation\Session\Session;
use Stephane888\HtmlBootstrap\Controller\TopHeaders;
use Stephane888\HtmlBootstrap\Controller\Headers;
use Stephane888\HtmlBootstrap\Controller\Sliders;
use Stephane888\HtmlBootstrap\Controller\ImageTextRightLeft;
use Stephane888\HtmlBootstrap\Controller\Cards;
use Stephane888\HtmlBootstrap\Controller\CarouselCards;
use Stephane888\HtmlBootstrap\Controller\CallActions;
use Stephane888\HtmlBootstrap\Controller\Comments;
use Stephane888\HtmlBootstrap\Controller\Footers;
use Stephane888\HtmlBootstrap\Controller\PriceLists;
use Stephane888\HtmlBootstrap\Controller\StylePage;
use Stephane888\HtmlBootstrap\Controller\PageNodesDisplay;
use Stephane888\HtmlBootstrap\Traits\Examples;
use Stephane888\HtmlBootstrap\Traits\DrupalUtility;

/**
 *
 * @author stephane
 *
 */
class LoaderDrupal {

  protected $BasePath = '';

  private $loadScss = false;
  // use Examples;
  use DrupalUtility;

  function __construct()
  {
    $this->BasePath = __DIR__;
    // $Session = new Session();
    // $Session->remove('theme_style');
    $this->checkLoadScss()->getDefautStyle();
  }

  /**
   * ||___________________ SECTION HEADERS _____________________||
   */

  /**
   * get top headers
   */
  public function getSectionTopHeaders($options)
  {
    $Headers = new TopHeaders($this->BasePath);
    return $Headers->loadFile($options);
  }

  /**
   * Section headers [logo center]
   */
  public function getSectionHeaders($options)
  {
    $Headers = new Headers($this->BasePath);
    return $Headers->loadFile($options);
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
    return $Sliders->loadFile($options);
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
   * load default slider
   */
  public function getPriceLists($options)
  {
    $PriceLists = new PriceLists($this->BasePath);
    return $PriceLists->loadFile($options);
  }

  /**
   * load default StylePage
   */
  public function getStylePage($options)
  {
    $PriceLists = new StylePage($this->BasePath);
    return $PriceLists->loadFile($options);
  }

  public function createFiles($displays, $dir)
  {
    $PageNodesDisplay = new PageNodesDisplay($this->BasePath);
    $PageNodesDisplay->genereFiles($displays, $dir);
  }

  public function loadPagePlugins(&$variables, $displays, $node, $theme_name)
  {
    $PageNodesDisplay = new PageNodesDisplay($this->BasePath);
    $PageNodesDisplay->loadPagePlugins($variables, $displays, $node, $theme_name);
  }

  /**
   * Ajoute les styles.
   */
  public static function addStyle($style, $key)
  {
    if (LOAD_SCSS_BY_SESSION) {
      static::addData('theme_style', $style, $key);
    }
  }

  public static function addScript($script, $key)
  {
    if (LOAD_SCSS_BY_SESSION) {
      static::addData('theme_script', $script, $key);
    }
  }

  public static function addData($session_key, $data, $key)
  {
    $Session = new Session();
    $datas = $Session->get($session_key, []);
    $datas[$key] = $data;
    $Session->set($session_key, $datas);
  }

  public static function getSessionValue($session_key)
  {
    $Session = new Session();
    return $Session->get($session_key, null);
  }

  public static function DeleteSessionValue($session_key)
  {
    $Session = new Session();
    return $Session->remove($session_key);
  }

  public static function file_save($filename, $result)
  {
    $monfichier = fopen($filename, 'w+');
    fputs($monfichier, $result);
    fclose($monfichier);
  }

  public static function file_delete($filename)
  {
    return unlink($filename);
  }

  public static function deleteSession($key)
  {
    $Session = new Session();
    if (\is_array($key)) {
      foreach ($key as $k) {
        $Session->remove($k);
      }
    } else {
      $Session->remove($key);
    }
  }

  /**
   * Filtre l'affichage en function de la nom de la route.
   *
   *
   * @param string $route_lits
   * @param string $listes_parameters
   * @return boolean
   */
  public function filterByRouteName($route = '', $parameter = '', $type_node = '')
  {
    $RouteName = \Drupal::routeMatch()->getRouteName();
    $node = \Drupal::routeMatch()->getParameter('node');
    if ($node) {
      $nid = $node->id();
    } else {
      $nid = '';
    }
    $route_frontPage = "view.frontpage.page_1";
    $route_node = "entity.node.canonical";
    // strpos
    if ($route == '')
      return true;

    /**
     * page d'accuiel
     */
    if ($RouteName == $route_frontPage && $route_frontPage == $route) {
      return true;
    } //
    /**
     * page de nodes
     */
    elseif ($RouteName == $route_node && $route_node == $route) {
      /**
       * le type de node est prioritaire.
       * Si le type de node est definie, on ne regarde plus les paramettres.
       * les nodes de type page, doivent utiliser les parametres.
       * les nodes de type article, films ... doivent utiliser le type de node.
       */
      if ($parameter == $nid) {
        return true;
      }
    }
    return false;
  }

  private function checkLoadScss()
  {
    if (isset($_GET['build']) && $_GET['build'] == 'scss') {
      $this->loadScss = true;
    }
    return $this;
  }

  private function getDefautStyle()
  {
    if ($this->loadScss) {
      $Session = new Session();

      if (defined('KEY_LOAD_SCSS') && KEY_LOAD_SCSS == 'loarder2') {
        $styles = $Session->get('theme_style', []);
        $styles['init'] = '@import "defaut/loader_model1.scss";';
        $Session->set('theme_style', $styles);
      } else {
        $styles = $Session->get('theme_style', []);
        $styles['init'] = '@import "defaut/models.scss";';
        $Session->set('theme_style', $styles);
      }
    }
  }
}