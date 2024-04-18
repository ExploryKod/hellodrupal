<?php

declare(strict_types=1);

namespace Drupal\sun\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Database\Connection;
use Drupal\Core\Datetime\DateFormatterInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Returns responses for Sun routes.
 */
final class StatsController extends ControllerBase {

  /**
   * @var \Drupal\Core\Database\Connection
   */
  protected $connection;

  /**
   * The date formatter service.
   *
   * @var \Drupal\Core\Datetime\DateFormatterInterface
   */
  protected $dateFormatter;

  /**
   * The controller constructor.
   */
  public function __construct(Connection $connection, DateFormatterInterface $dateFormatter,
  ) {
    $this->connection = $connection;
    $this->dateFormatter = $dateFormatter;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container): self {
    return new self(
      $container->get('database'),
      $container->get('date.formatter'),
    );
  }

  /**
   * Builds the response.
   */
  public function __invoke(\Drupal\user\UserInterface $user): array {
    ksm($user);
    $results = $this->connection->select('sun_user_statistics')
    // hus is a table alias
      ->fields('timestamp')
      ->condition('uid', $user->id())
      ->execute();

    // Préparation des en-têtes de tableau
    //    $header = [
    //      'Action' => t('Action'),
    //      'Timestamp' => t('Timestamp'),
    //    ];

    // Préparation des lignes de tableau
    $rows = [];
    foreach ($results as $result) {
      $rows[] = [
        $this->dateFormatter->format($result->timestamp),
        $result->action,
      ];
    }

    $build['content'] = [
      '#type' => 'table',
      '#rows' => $rows,
      '#empty' => t('No statistics found for this user.'),
      '#header' => [
        'Action' => t('Action'),
        'Timestamp' => t('Timestamp'),
      ],
      '#cache' => ['max-age' => 0],

    ];

    return $build;
  }

}
