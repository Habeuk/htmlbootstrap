<?php
namespace Stephane888\HtmlBootstrap\Controller;

class ManageNode {

  protected $entity_type = 'node';

  public function getContentType()
  {
    $node_types = \Drupal\node\Entity\NodeType::loadMultiple();
    $results = [];
    foreach ($node_types as $node_type) {
      $results[$node_type->id()] = $node_type->label();
    }
    return $results;
  }

  public function getFieldsNode($bundle)
  {
    $fields = [];
    $defaulsFileds = $this->defaultFields();
    // return \Drupal::entityTypeManager()->getStorage('entity_form_display')->load('node.' . $bundle . '.default');
    $entityManager = \Drupal::service('entity_field.manager');
    $Allfields = $entityManager->getFieldDefinitions($this->entity_type, $bundle);
    $entity_form_display = \Drupal::entityTypeManager()->getStorage('entity_form_display')
      ->load('node.' . $bundle . '.default')
      ->getComponents();
    foreach ($Allfields as $key => $field) {
      if (isset($entity_form_display[$key]) && empty($defaulsFileds[$key])) {
        $fields[$key] = $field->getLabel();
      }
    }
    return $fields;
  }

  public function defaultFields()
  {
    return [
      'langcode' => 'langcode',
      'revision_log' => 'revision_log',
      'status' => 'status',
      'uid' => 'uid',
      'created' => 'created',
      'promote' => 'promote',
      'sticky' => 'sticky',
      'path' => 'path'
    ];
  }
}