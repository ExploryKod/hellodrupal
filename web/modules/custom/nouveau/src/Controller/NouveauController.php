<?php

declare(strict_types=1);

namespace Drupal\nouveau\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Returns responses for Nouveau routes.
 */
final class NouveauController extends ControllerBase {

  /**
   * Builds the response.
   */
  public function __invoke($param='world'): array {

    $build['content'] = [
      '#type' => 'item',
      '#markup' => $this->t('It works! @param', ['@param' => $param]),
    ];

    return $build;
  }

}
