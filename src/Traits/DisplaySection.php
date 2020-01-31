<?php
namespace Stephane888\HtmlBootstrap\Traits;

trait DisplaySection {

  // \\ to update in defaultTheme.
  /**
   *
   * @param object $LoaderDrupal
   * @param object $variables
   */
  protected static function getHeaders($LoaderDrupal, &$variables)
  {
    $group = 'header';
    $theme_name = 'themeconsultant';
    $i = 0;
    $value = theme_get_setting($theme_name . '_' . $group, 'themeconsultant');
    $values = [
      'displays' => $value
    ];
    /**
     * Gestion de l'affichage.
     */
    if (isset($values['displays']))
      foreach ($values['displays'] as $display) {
        $i ++;
        $options = [
          'type' => $display['model']
        ];
        /**
         * get datas and put it in options.
         */
        $LoaderDrupal->loadHeaderDatas($options, $group, $theme_name, $display, $variables);
        $variables['page'][$display['region']][$theme_name . '_' . $group][$i] = $LoaderDrupal->getSectionHeaders($options);
        $variables['page'][$display['region']][$theme_name . '_' . $group]["#weight"] = $display['weight'];
      }
  }

  /**
   * Charge le slider.
   *
   * @param object $LoaderDrupal
   * @param object $variables
   */
  protected static function getSliders($LoaderDrupal, &$variables)
  {
    $theme_name = 'themeconsultant';
    $value = theme_get_setting($theme_name . '_slide_value', 'themeconsultant');
    $theme_name = 'themeconsultant';
    $options = [
      'type' => $value
    ];
    /**
     * get datas and put it in options.
     */
    $LoaderDrupal->loadSlideDatas($options, $theme_name . '_slide', $theme_name);
    $variables['page']['before_content'][$theme_name . '_sliders'] = $LoaderDrupal->getSectionSliders($options);
    $variables['page']['before_content'][$theme_name . '_sliders']["#weight"] = - 100;
  }

  protected static function getImageTextRightLeft($LoaderDrupal, &$variables)
  {
    $group = 'imagetextrightleft';
    $theme_name = 'themeconsultant';
    $i = 0;
    $values = theme_get_setting($theme_name . '_' . $group, 'themeconsultant');
    /**
     * Gestion de l'affichage.
     */
    if (isset($values['displays']))
      foreach ($values['displays'] as $display) {
        $i ++;
        /**
         * Filtre de l'affichage
         */
        $parameter = (empty($display['nid'])) ? '' : $display['nid'];
        if ($LoaderDrupal->filterByRouteName($display['route'], $parameter)) {

          $options = [
            'type' => $display['model']
          ];
          $LoaderDrupal->loadImageTextRightLeftDatas($options, $group, $theme_name, $display);
          $variables['page'][$display['region']][$theme_name . '_' . $group][$i] = $LoaderDrupal->getImageTextRightLeft($options);
          $variables['page'][$display['region']][$theme_name . '_' . $group][$i]["#weight"] = $display['weight'];
          $variables['page'][$display['region']][$theme_name . '_' . $group]["#weight"] = $display['weight'];
        }
      }
  }

  // \\ to update in defaultTheme.
  /**
   * load CallActions.
   *
   * @param object $LoaderDrupal
   * @param object $variables
   */
  protected static function getCallActions($LoaderDrupal, &$variables)
  {
    $group = 'callactions';
    $theme_name = 'themeconsultant';
    $i = 0;
    $values = theme_get_setting($theme_name . '_' . $group, 'themeconsultant');
    /**
     * Gestion de l'affichage.
     */
    if (isset($values['displays']))
      foreach ($values['displays'] as $display) {
        $i ++;
        /**
         * Filtre de l'affichage
         */
        $parameter = (empty($display['nid'])) ? '' : $display['nid'];
        if ($LoaderDrupal->filterByRouteName($display['route'], $parameter)) {

          $options = [
            'type' => $display['model']
          ];
          $LoaderDrupal->loadCallActionsDatas($options, $group, $theme_name, $display);
          $variables['page'][$display['region']][$theme_name . '_' . $group][$i] = $LoaderDrupal->getCallActions($options);
          $variables['page'][$display['region']][$theme_name . '_' . $group][$i]["#weight"] = $display['weight'];
          $variables['page'][$display['region']][$theme_name . '_' . $group]["#weight"] = $display['weight'];
        }
      }
  }

  /**
   * load card.
   *
   * @param object $LoaderDrupal
   * @param object $variables
   */
  protected static function getCards($LoaderDrupal, &$variables)
  {
    $group = 'cards';
    $theme_name = 'themeconsultant';
    $i = 0;
    $values = theme_get_setting($theme_name . '_' . $group, 'themeconsultant');
    /**
     * Gestion de l'affichage.
     */
    if (isset($values['displays']))
      foreach ($values['displays'] as $display) {
        $i ++;
        /**
         * Filtre de l'affichage
         */
        $parameter = (empty($display['nid'])) ? '' : $display['nid'];
        if ($LoaderDrupal->filterByRouteName($display['route'], $parameter)) {
          $options = [
            'type' => $display['model']
          ];
          /**
           * Chage les options.
           */
          $LoaderDrupal->loadCardsDatas($options, $group, $theme_name, $display);
          $variables['page'][$display['region']][$theme_name . '_' . $group][$i] = $LoaderDrupal->getCards($options);
          $variables['page'][$display['region']][$theme_name . '_' . $group][$i]["#weight"] = $display['weight'];
          $variables['page'][$display['region']][$theme_name . '_' . $group]["#weight"] = $display['weight'];
        }
      }
  }

  /**
   * load PriceLists.
   *
   * @param object $LoaderDrupal
   * @param object $variables
   */
  protected static function getPriceLists($LoaderDrupal, &$variables)
  {
    $group = 'pricelists';
    $theme_name = 'themeconsultant';
    $i = 0;
    $values = theme_get_setting($theme_name . '_' . $group, 'themeconsultant');
    /**
     * Gestion de l'affichage.
     */
    if (isset($values['displays']))
      foreach ($values['displays'] as $display) {
        $i ++;
        /**
         * Filtre de l'affichage
         */
        $parameter = (empty($display['nid'])) ? '' : $display['nid'];
        if ($LoaderDrupal->filterByRouteName($display['route'], $parameter)) {
          $options = [
            'type' => $display['model']
          ];
          /**
           * Chage les options.
           */
          $LoaderDrupal->loadPriceLists($options, $group, $theme_name, $display);
          $variables['page'][$display['region']][$theme_name . '_' . $group][$i] = $LoaderDrupal->getPriceLists($options);
          $variables['page'][$display['region']][$theme_name . '_' . $group][$i]["#weight"] = $display['weight'];
          $variables['page'][$display['region']][$theme_name . '_' . $group]["#weight"] = $display['weight'];
        }
      }
  }

  // \\ to update in defaultTheme.
  /**
   * load card.
   *
   * @param object $LoaderDrupal
   * @param object $variables
   */
  protected static function getCarouselCards($LoaderDrupal, &$variables)
  {
    $group = 'carouselcards';
    $theme_name = 'themeconsultant';
    $i = 0;
    $add_libray = true;
    $values = theme_get_setting($theme_name . '_' . $group, 'themeconsultant');
    /**
     * Gestion de l'affichage.
     */
    if (isset($values['displays']))
      foreach ($values['displays'] as $display) {
        $i ++;
        /**
         * Filtre de l'affichage
         */
        $parameter = (empty($display['nid'])) ? '' : $display['nid'];
        if ($LoaderDrupal->filterByRouteName($display['route'], $parameter)) {
          $options = [
            'type' => $display['model']
          ];
          /**
           * Chage les options.
           */
          $LoaderDrupal->loadCarouselCardsDatas($options, $group, $theme_name, $display);
          $variables['page'][$display['region']][$theme_name . '_' . $group][$i] = $LoaderDrupal->getCarouselCards($options);
          $variables['page'][$display['region']][$theme_name . '_' . $group][$i]["#weight"] = $display['weight'];
          $variables['page'][$display['region']][$theme_name . '_' . $group]["#weight"] = $display['weight'];
          if ($add_libray) {
            /**
             * Add library
             */
            $variables['page'][$display['region']][$theme_name . '_' . $group]['#attached']['library'][] = 'themeconsultant/owlcarousel';
            $add_libray = false;
          }
        }
      }
  }

  /**
   * load CallActions.
   *
   * @param object $LoaderDrupal
   * @param object $variables
   */
  protected static function getComments($LoaderDrupal, &$variables)
  {
    $group = 'comments';
    $theme_name = 'themeconsultant';
    $i = 0;
    $values = theme_get_setting($theme_name . '_' . $group, 'themeconsultant');
    /**
     * Gestion de l'affichage.
     */
    if (isset($values['displays']))
      foreach ($values['displays'] as $display) {
        if (! $display['status'])
          continue;
        $i ++;
        /**
         * Filtre de l'affichage
         */
        $parameter = (empty($display['nid'])) ? '' : $display['nid'];
        if ($LoaderDrupal->filterByRouteName($display['route'], $parameter)) {
          $options = [
            'type' => $display['model']
          ];
          /**
           * Chage les options.
           */
          $LoaderDrupal->loadComments($options, $group, $theme_name, $display);
          $variables['page'][$display['region']][$theme_name . '_' . $group][$i] = $LoaderDrupal->getComments($options);
          $variables['page'][$display['region']][$theme_name . '_' . $group][$i]["#weight"] = $display['weight'];
          $variables['page'][$display['region']][$theme_name . '_' . $group]["#weight"] = $display['weight'];
          $variables['page'][$display['region']][$theme_name . '_' . $group][$i]['#attached']['library'][] = 'themeconsultant/owlcarousel';
        }
      }
  }

  protected static function getStylePage($LoaderDrupal, &$variables)
  {
    $group = 'stylepage';
    $theme_name = 'themeconsultant';
    $i = 0;
    $values = theme_get_setting($theme_name . '_' . $group, 'themeconsultant');
    /**
     * Gestion de l'affichage.
     */
    if (isset($values['displays']))
      foreach ($values['displays'] as $display) {
        $i ++;
        /**
         * Filtre de l'affichage
         */
        $parameter = (empty($display['nid'])) ? '' : $display['nid'];
        if ($LoaderDrupal->filterByRouteName($display['route'], $parameter)) {
          $options = [
            'type' => $display['model']
          ];
          /**
           * Chage les options.
           */
          $LoaderDrupal->loadStylePage($options, $group, $theme_name, $display);
          /**
           * on charge les css et JS.
           */
          $LoaderDrupal->getStylePage($options);
        }
      }
  }

  // \\ to update in defaultTheme.
  /**
   * Load getFooters
   */
  protected static function getFooters($LoaderDrupal, &$variables)
  {
    $group = 'footers';
    $theme_name = 'themeconsultant';
    $i = 0;
    $value = theme_get_setting($theme_name . '_' . $group, 'themeconsultant');
    $values = [
      'displays' => $value
    ];
    /**
     * Gestion de l'affichage.
     */
    if (isset($values['displays']))
      foreach ($values['displays'] as $display) {
        $i ++;
        /**
         * Filtre de l'affichage
         */
        $parameter = (empty($display['nid'])) ? '' : $display['nid'];
        if ($LoaderDrupal->filterByRouteName($display['route'], $parameter)) {
          $options = [
            'type' => $display['model']
          ];
          /**
           * Chage les options.
           */
          $LoaderDrupal->loadFootersDatas($options, $group, $theme_name, $display);
          $variables['page'][$display['region']][$theme_name . '_' . $group][$i] = $LoaderDrupal->getFooters($options);
          $variables['page'][$display['region']][$theme_name . '_' . $group][$i]["#weight"] = $display['weight'];
          $variables['page'][$display['region']][$theme_name . '_' . $group]["#weight"] = $display['weight'];
        }
      }
  }
}