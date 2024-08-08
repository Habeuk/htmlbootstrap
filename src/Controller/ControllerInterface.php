<?php
namespace Stephane888\HtmlBootstrap\Controller;

interface ControllerInterface {

  /**
   *
   * @param array $options
   */
  public function loadFile($options);

  /**
   * Retourne la listes des models par default.
   *
   * @return array
   */
  public static function listModels();

  /**
   * Charge les champs par defaut pour la modification custom.
   *
   * @param string $model
   * @param array $form
   * @param array $options
   */
  public static function loadFields($model, &$form, $options);
}