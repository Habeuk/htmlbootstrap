<?php

namespace Stephane888\HtmlBootstrap;

/**
 *
 * @author stephane
 * @deprecated delete in 4x wb_universe
 */
class HelpMigrate {
  /**
   *
   * @var \Drupal\Core\Extension\ExtensionPathResolver
   */
  protected static $pathResolver;
  
  public static function getPath($type, $name) {
    if (!self::$pathResolver) {
      self::$pathResolver = \Drupal::service('extension.path.resolver');
    }
    return self::$pathResolver->getPath($type, $name);
  }
  
}
