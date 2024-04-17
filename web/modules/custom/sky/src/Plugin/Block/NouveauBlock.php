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
 *   id = "nouveau_nouveau",
 *   admin_label = @Translation("Nouveau Bloc"),
 *   category = @Translation("Nouveau"),
 * )
 */
final class NouveauBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * @var TimestampFormatter|DateFormatterInterface
   */
  protected TimestampFormatter $dateFormatter;

  /**
   * @var TimeInterface
   */
  protected TimeInterface $datetimeTime;

  /**
   * @var AccountProxyInterface
   */
  protected AccountProxyInterface $currentUser;

  /**
   * Constructs the plugin instance.
   */
  public function __construct(
    array $configuration,
    $plugin_id,
    $plugin_definition,
    $dateFormatter,
    $datetimeTime,
    $currentUser,
  ) {
    $this->dateFormatter = $dateFormatter;
    $this->datetimeTime = $datetimeTime;
    $this->currentUser = $currentUser;
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
      $container->get('date.formatter'),
      $container->get('datetime.time'),
      $container->get('current_user'),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function build(): array {
    $now = $this->datetimeTime->getRequestTime();
    $date = $this->dateFormatter->format($now, 'custom', 'H:i:s');
    $build['content'] = [
      '#markup' => $this->t('It is @date', ['@date' => $date, "@user" => $this->currentUser->getDisplayName()]),
      '#cache' => ['max-age' => 10]
    ];
    return $build;
  }

}
