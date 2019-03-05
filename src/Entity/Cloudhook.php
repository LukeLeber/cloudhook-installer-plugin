<?php

namespace Drupal\cloudhooks\Entity;

use Drupal\cloudhooks\CloudhookInterface;
use Drupal\Core\Config\Entity\ConfigEntityBase;
use Drupal\Core\StringTranslation\StringTranslationTrait;

/**
 * Defines the cloudhook entity.
 *
 * @ConfigEntityType(
 *   id = "cloudhook",
 *   label = @Translation("Cloudhook"),
 *   handlers = {
 *     "list_builder" = "Drupal\cloudhooks\Controller\CloudhookListBuilder",
 *     "form" = {
 *       "add" = "Drupal\cloudhooks\Form\CloudhookForm",
 *       "edit" = "Drupal\cloudhooks\Form\CloudhookForm",
 *       "delete" = "Drupal\cloudhooks\Form\CloudhookDeleteForm",
 *     }
 *   },
 *   config_prefix = "cloudhooks",
 *   admin_permission = "administer cloudhooks",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label"
 *   },
 *   config_export = {
 *     "id",
 *     "label",
 *     "event",
 *     "plugin_id",
 *     "weight",
 *   },
 *   links = {
 *     "edit-form" = "/admin/config/system/cloudhooks/{cloudhook}",
 *     "delete-form" = "/admin/config/system/cloudhooks/{cloudhook}/delete",
 *   }
 * )
 */
class Cloudhook extends ConfigEntityBase implements CloudhookInterface {

  use StringTranslationTrait;

  protected $id;
  protected $label;
  protected $plugin_id;
  protected $event;
  protected $weight;

  public function getId() {
    return $this->id;
  }

  public function getLabel() {
    return $this->label;
  }

  public function getPluginId() {
    return $this->plugin_id;
  }
  public function getWeight() {
    return $this->weight;
  }
  public function getEvent() {
    return $this->event;
  }

  public function getEventLabel() {
    return $this->getEvent();
  }

}
