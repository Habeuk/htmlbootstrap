<?php
namespace Stephane888\HtmlBootstrap;

use Stephane888\HtmlBootstrap\Traits\DisplaySection;
use Symfony\Component\HttpFoundation\Session\Session;
use ScssPhp\ScssPhp\Compiler;

class PreprocessPage {
  use DisplaySection;

  public function createTemplates($theme_name, $displays = null, $force = false)
  {
    if ((isset($_GET['build']) && $_GET['build'] == 'template') || $force) {
      if (! $displays) {
        $displays = theme_get_setting($theme_name . '_pagenodesdisplay', $theme_name);
      }
      $url_theme = \drupal_get_path('theme', $theme_name);
      $LoaderDrupal = new LoaderDrupal();
      $LoaderDrupal->createFiles($displays, $url_theme);
    }
  }

  public function loadSection($theme_name, &$variables)
  {
    $LoaderDrupal = new LoaderDrupal();
    /**
     * get style for pages
     */
    if (theme_get_setting($theme_name . '_stylepage_status', $theme_name)) {
      static::getStylePage($LoaderDrupal, $variables);
    }

    /**
     * get top headers
     */
    if (theme_get_setting($theme_name . '_topheader_status', $theme_name)) {
      // MdbootstrapWbu::getTopHeaders($LoaderDrupal, $variables);
    }
    /**
     * get headers
     */
    if (theme_get_setting($theme_name . '_header_status', $theme_name)) {
      static::getHeaders($LoaderDrupal, $variables);
    }
    /**
     * get sliders
     */
    if (theme_get_setting($theme_name . '_slide_status', $theme_name) && $LoaderDrupal->filterByRouteName(theme_get_setting($theme_name . '_slide_routes', 'themeconsultant'))) {
      static::getSliders($LoaderDrupal, $variables);
    }
    /**
     * get card
     */
    if (theme_get_setting($theme_name . '_cards_status', $theme_name)) {
      static::getCards($LoaderDrupal, $variables);
    }

    /**
     * get PriceLists
     */
    if (theme_get_setting($theme_name . '_pricelists_status', $theme_name)) {
      static::getPriceLists($LoaderDrupal, $variables);
    }

    /**
     * Get CallActions
     */
    if (theme_get_setting($theme_name . '_callactions_status', $theme_name)) {
      static::getCallActions($LoaderDrupal, $variables);
    }

    /**
     * Get carouselcards
     */
    if (theme_get_setting($theme_name . '_carouselcards_status', $theme_name)) {
      static::getCarouselCards($LoaderDrupal, $variables);
    }

    /**
     * Get Comments
     */
    if (theme_get_setting($theme_name . '_comments_status', $theme_name)) {
      static::getComments($LoaderDrupal, $variables);
    }

    /**
     */
    if (theme_get_setting($theme_name . '_imagetextrightleft_status', $theme_name)) {
      static::getImageTextRightLeft($LoaderDrupal, $variables);
    }

    /**
     * Get footers
     */
    if (theme_get_setting($theme_name . '_footers_status', $theme_name)) {
      static::getFooters($LoaderDrupal, $variables);
    }
  }

  public function ApplyActions(&$variables)
  {
    /**
     * Remove system page in front.
     */
    if (isset($variables['is_front']) && $variables['is_front']) {
      unset($variables['page']['content']['system_main']);
    }
    /**
     * remove default message in fornt
     */
    if ($variables['is_front']) {
      // dump($variables['page']['content']);
      unset($variables['page']['content']['themeconsultant_page_title']);
      unset($variables['page']['content']['themeconsultant_content']);
    }

    /**
     * remove edit for all user except admibistrator
     */
    if (! \Drupal\user\Entity\User::load(\Drupal::currentUser()->id())->hasRole('administrator')) {
      unset($variables['page']['content']['themeconsultant_local_tasks']);
    }
  }

  public function AddLibrary(&$variables)
  {
    /**
     * Ajout les fichiers de style et Scripts.
     *
     * @var Ambiguous $node
     */
    $node = \Drupal::routeMatch()->getParameter('node');
    if ($node) {
      $variables['page']['content']['#attached']['library'][] = 'themeconsultant/page-node';
    }
  }

  /**
   * load scss csss
   */
  public function _load_scss()
  {
    if (isset($_GET['build']) && $_GET['build'] == 'scss') {
      require_once __DIR__ . '/../vendor/autoload.php';
      $theme_name = 'themeconsultant';
      // convertie un fichier scss en css.
      require_once DRUPAL_ROOT . '/themes/' . $theme_name . '/scssphp-master/scss.inc.php';
      // convert bootstrap scss to css
      // new ScssPhp\ScssPhp\Compiler();
      $parser = new Compiler();
      // build bootstrap end default style theme

      $result = $parser->compile('@import "' . DRUPAL_ROOT . '/themes/' . $theme_name . '/scss/bootstrap-overlay.scss"; body{height:3em;}');
      $filename = DRUPAL_ROOT . '/themes/' . $theme_name . '/css/bootstrap-overlay.css';
      $monfichier = fopen($filename, 'w+');
      fputs($monfichier, $result);
      fclose($monfichier);

      // build custom style
      if (LOAD_SCSS_BY_SESSION) {
        // dump('_load_scss');
        $Session = new Session();
        $styles = $Session->get('theme_style', []);
        if (! empty($styles)) {
          // dump($styles);
          $style = '';
          if (isset($styles['init'])) {
            $style .= $styles['init'];
            $style .= "\n";
            unset($styles['init']);
          }
          foreach ($styles as $key => $sty) {
            if (strstr($key, 'init_-_header')) {
              $style .= $styles[$key];
              $style .= "\n";
              unset($styles[$key]);
            }
          }
          $style .= implode("\n", $styles);
          // $Session->remove('theme_style');
          // kint($style);
          /**
           * on enregistre le fichier generere en scss.
           */
          $filename = DRUPAL_ROOT . '/themes/' . $theme_name . '/scss/style-auto.scss';
          $monfichier = fopen($filename, 'w+');
          fputs($monfichier, $style);
          fclose($monfichier);
          /**
           * compilation du fichier.
           */
          $result = $parser->compile('@import "' . $filename . '";');
          /**
           * on sauvegarde le fichier css generé.
           */
          $filename = DRUPAL_ROOT . '/themes/' . $theme_name . '/css/style-auto.css';
          $monfichier = fopen($filename, 'w+');
          fputs($monfichier, $result);
          fclose($monfichier);
          /**
           * Get script
           */
          $scripts = $Session->get('theme_script', []);
          $script = implode("\n", $scripts);
          /**
           * On enregistre le fichier generere en js.
           */
          $filename = DRUPAL_ROOT . '/themes/' . $theme_name . '/js/script-auto.js';
          $monfichier = fopen($filename, 'w+');
          fputs($monfichier, $script);
          fclose($monfichier);
        }
      }

      // build custom style
      $result = $parser->compile('@import "' . DRUPAL_ROOT . '/themes/' . $theme_name . '/scss/style.scss";');
      $filename = DRUPAL_ROOT . '/themes/' . $theme_name . '/css/style.css';
      $monfichier = fopen($filename, 'w+');
      fputs($monfichier, $result);
      fclose($monfichier);

      // build custom style
      $result = $parser->compile('@import "' . DRUPAL_ROOT . '/themes/' . $theme_name . '/scss/accueill.scss";');
      $filename = DRUPAL_ROOT . '/themes/' . $theme_name . '/css/accueill.css';
      $monfichier = fopen($filename, 'w+');
      fputs($monfichier, $result);
      fclose($monfichier);
      /* */
      // build custom style
      $result = $parser->compile('@import "' . DRUPAL_ROOT . '/themes/' . $theme_name . '/scss/article.scss";');
      $filename = DRUPAL_ROOT . '/themes/' . $theme_name . '/css/article.css';
      $monfichier = fopen($filename, 'w+');
      fputs($monfichier, $result);
      fclose($monfichier);

      // build custom style
      $result = $parser->compile('@import "' . DRUPAL_ROOT . '/themes/' . $theme_name . '/scss/article-teaser.scss";');
      $filename = DRUPAL_ROOT . '/themes/' . $theme_name . '/css/article-teaser.css';
      $monfichier = fopen($filename, 'w+');
      fputs($monfichier, $result);
      fclose($monfichier);

      // build custom style
      $result = $parser->compile('@import "' . DRUPAL_ROOT . '/themes/' . $theme_name . '/scss/sign-in.scss";');
      $filename = DRUPAL_ROOT . '/themes/' . $theme_name . '/css/sign-in.css';
      $monfichier = fopen($filename, 'w+');
      fputs($monfichier, $result);
      fclose($monfichier);

      // build custom style
      $result = $parser->compile('@import "' . DRUPAL_ROOT . '/themes/' . $theme_name . '/scss/style-admin.scss";');
      $filename = DRUPAL_ROOT . '/themes/' . $theme_name . '/css/style-admin.css';
      $monfichier = fopen($filename, 'w+');
      fputs($monfichier, $result);
      fclose($monfichier);

      // build custom style
      $result = $parser->compile('@import "' . DRUPAL_ROOT . '/themes/' . $theme_name . '/scss/ckeditor_custom.scss";');
      $filename = DRUPAL_ROOT . '/themes/' . $theme_name . '/css/ckeditor_custom.css';
      $monfichier = fopen($filename, 'w+');
      fputs($monfichier, $result);
      fclose($monfichier);

      // build custom member-ship
      $result = $parser->compile('@import "' . DRUPAL_ROOT . '/themes/' . $theme_name . '/scss/member-ship.scss";');
      $filename = DRUPAL_ROOT . '/themes/' . $theme_name . '/css/member-ship.css';
      $monfichier = fopen($filename, 'w+');
      fputs($monfichier, $result);
      fclose($monfichier);

      // build custom member-ship
      $result = $parser->compile('@import "' . DRUPAL_ROOT . '/themes/' . $theme_name . '/scss/generates/page_node_scss.scss";');
      $filename = DRUPAL_ROOT . '/themes/' . $theme_name . '/css/page-node.css';
      $monfichier = fopen($filename, 'w+');
      fputs($monfichier, $result);
      fclose($monfichier);

      // build custom member-ship
      $result = $parser->compile('@import "' . DRUPAL_ROOT . '/themes/' . $theme_name . '/scss/generates/node_scss.scss";');
      $filename = DRUPAL_ROOT . '/themes/' . $theme_name . '/css/node.css';
      $monfichier = fopen($filename, 'w+');
      fputs($monfichier, $result);
      fclose($monfichier);

      /**
       * delete session
       */
      $this->_delete_scss();
    }
  }

  protected function _delete_scss()
  {
    $Session = new Session();
    $Session->remove('theme_style');
    $Session->remove('theme_script');
  }
}