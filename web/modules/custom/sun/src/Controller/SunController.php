<?php

declare(strict_types=1);

namespace Drupal\sun\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Returns responses for Sky routes.
 */
final class SunController extends ControllerBase {

  /**
   * Builds the response.
   */
  public function __invoke(): array {

    $build['content'] = [
      '#type' => 'item',
      '#markup' => $this->t('It works!'),
    ];

    return $build;
  }

}
