<?php

declare(strict_types=1);

namespace Drupal\nouveau\Plugin\Block;

use Drupal\Component\Datetime\TimeInterface;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Datetime\DateFormatterInterface;
use Drupal\Core\Field\Plugin\Field\FieldFormatter\TimestampFormatter;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Session\AccountProxyInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a nouveau bloc block.
 *
 * @Block(
 *   id = "sun_sun",
 *   admin_label = @Translation("Nouveau Bloc"),
 *   category = @Translation("Nouveau"),
 * )
 */
final class NouveauBlock extends BlockBase implements ContainerFactoryPluginInterface {


  /**
   * @var \Drupal\Core\Database\Connection
   */
  protected $database;

  /**
   * Constructs the plugin instance.
   */
  public function __construct(
    array $configuration,
    $plugin_id,
    $plugin_definition,
    $connexion,
  ) {
    $this->database = $connexion;

    parent::__construct($configuration, $plugin_id, $plugin_definition);
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition): self {
    return new self(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('database'),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function build(): array {
    $nbrSessions = $this->database->select('sessions')
      ->countQuery()
      ->execute()
      ->FetchField();
    $build['content'] = [
      '#markup' => $this->t('There are @nbr of sessions', ['@nbr' => $nbrSessions])
    ];
    return $build;
  }

}
