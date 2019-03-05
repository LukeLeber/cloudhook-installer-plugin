<?php

namespace Drupal\cloudhooks_drush\Plugin\Cloudhook;

use Drupal\cloudhooks\Plugin\Cloudhook\PostCodeDeployPluginInterface;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

/**
 * Provides a mechanism to clear the Drush cache.
 *
 * @Cloudhook(
 *   id = "clear_drush_cache",
 *   label = @Translation("Clears the Drush cache"),
 *   description = @Translation("Clears the Drush cache."),
 *   category = @Translation("Basic"),
 *   events = {
 *     "post-code-deploy",
 *     "post-code-update",
 *     "post-db-copy",
 *     "post-files-copy",
 *   }
 * )
 */
class ClearDrushCache extends CloudhookDrushPluginBase implements PostCodeDeployPluginInterface {

  /**
   * {@inheritdoc}
   */
  public function onPostCodeDeploy($application, $environment, $source_branch, $deployed_tag, $repo_url, $repo_type) {

    $this->logger->notice('Attempting to clear the Drush cache.');
    try {
      (new Process(['drush', 'cc', 'drush']))->mustRun()->wait();
      $this->logger->notice('Finished clearing the Drush cache.');
    }
    catch(ProcessFailedException $e) {
      $this->logger->critical('Failed to clear the Drush cache!');
    }
  }
}
