<?php

namespace Drupal\cloudhooks\Controller;

use Drupal\cloudhooks\CloudhookInterface;
use Drupal\Core\Config\Entity\ConfigEntityListBuilder;
use Drupal\Core\Entity\EntityInterface;

class CloudhookListBuilder extends ConfigEntityListBuilder {

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['id'] = $this->t('Id');
    $header['label'] = $this->t('Label');
    $header['event'] = $this->t('Event');
    $header['plugin_id'] = $this->t('Plugin id');
    $header['weight'] = $this->t('Weight');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /* @var $entity \Drupal\cloudhooks\CloudhookInterface */

    $row['id'] = $entity->id();
    $row['label'] = $entity->label();
    $row['event'] = $entity->getEventLabel();
    $row['plugin_id'] = $entity->getPluginId();
    $row['weight'] = $entity->get('weight');
    return $row + parent::buildRow($entity);
  }

  /**
   * {@inheritdoc}
   */
  public function load() {
    $entities = parent::load();

    // Sort by event
    uasort($entities, function(CloudhookInterface $lhs, CloudhookInterface $rhs) {
      $result = strnatcmp($lhs->getEvent(), $rhs->getEvent());

      if ($result === 0) {
        $result = $lhs->getWeight() - $rhs->getWeight();
      }
      return $result;
    });

    return $entities;
  }
}
