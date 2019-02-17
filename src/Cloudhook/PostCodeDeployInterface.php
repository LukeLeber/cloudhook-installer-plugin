<?php

namespace Drupal\cloudhooks\Cloudhook;

use Drupal\cloudhooks\CloudhookInterface;

/**
 * Interface PostCodeDeployInterface.
 *
 * @package Drupal\cloudhooks\Cloudhook
 */
interface PostCodeDeployInterface extends CloudhookInterface {

  /**
   * Fires on post-code-deploy.
   *
   * @param string $application
   *   The application that the action has occurred on.
   * @param string $environment
   *   The environment that the action has occurred on.
   * @param string $source_branch
   *   The branch that has been deployed (if applicable).
   * @param string $deployed_tag
   *   The tag that has been deployed (if applicable).
   * @param string $repo_url
   *   The repository URL.
   * @param string $repo_type
   *   The repository type.
   */
  public function onPostCodeDeploy($application, $environment, $source_branch, $deployed_tag, $repo_url, $repo_type);

}
