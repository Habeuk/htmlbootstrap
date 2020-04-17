<?php
namespace Stephane888\HtmlBootstrap\Controller;

class ManageBlock {

  public static function getListBloks()
  {
    $list_blocks = [];
    $Blocks = \Drupal\block\Entity\Block::loadMultiple();
    foreach ($Blocks as $key => $Block) {
      $list_blocks[$key] = $Block->label();
    }
    return $list_blocks;
  }

  public static function loadBlock($plugin)
  {
    $block = \Drupal\block\Entity\Block::load($plugin);
    if ($block) {
      $render = \Drupal::entityTypeManager()->getViewBuilder('block')->view($block);
      return $render;
    }
    return false;
  }

  public static function getListWebform()
  {
    $results = [];
    $entites = \Drupal::entityTypeManager()->getStorage('webform')->loadMultiple(NULL);
    foreach ($entites as $entite) {
      $results[$entite->id()] = $entite->label();
    }
    return $results;
    ;
  }

  public static function loadWebform($id)
  {
    $webform = \Drupal::entityTypeManager()->getStorage('webform')->load($id);
    $webform = $webform->getSubmissionForm();
    return $webform;
  }
}