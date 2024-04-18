<?php

declare(strict_types=1);

namespace Drupal\sky\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Returns responses for sun routes.
 */
final class NodeListController extends ControllerBase {

  /**
   * Builds the response.
   */
  public function __invoke($node_type = ''): array {
//    $nodes = $this->entityTypeManager()->getStorage('node')->loadMultiple();
    $nodeStorage = $this->entityTypeManager()->getStorage('node');
    $query = $nodeStorage->getQuery();
    $query->condition('status', '1');
    if($node_type) {
      $query->controller('type', $node_type);
    }
    $query->sort('created', 'DESC');
    $query->pager(7);
    // sécurité : pour éviter que les utilisateurs lambda accèdent à la liste des noeuds
    $query->accessCheck('TRUE');
    $nids = $query->execute();
    $nodes = $nodeStorage->loadMultiple($nids);
    // Regarder dans le registry de drupal le détaille de l'objet (c pas dans l'inspector) et aprés on peux appeler dans la console le noeuds
    ksm($nodes);
    $items = [];
    foreach($nodes as $node) {
      /**
       * @var \Drupal\node\NodeInterface $node
       */
      $items[] = $node->getTitle();
      $items[] = $node->toLink();
    }
    $build['content'] = [
      '#theme' => 'item_list',
      '#items' => $items,
      '#type' => 'item',
      '#markup' => $this->t('It works!'),
      '#empty' => $this->t('No nodes found'),
      //      '#cache' => [
      //        'max-age' => 0,
      //      ]
    ];

    $build['pager'] = ['#type' => 'pager'];

    //    trop laxiste pour la prod
    //    $build['#cache']['max-age'] = 0;

    // création d'un tag node_list pour invalider ce tag : si je CRUD sur un noeud alors j'ai besoin de recharger le cache
    // Il est possible de préciser [node_list:article] pour invalider le cache des articles
    // voir sur drupal.org la liste des tags : https://www.drupal.org/node/20440
    $build['#cache']['tags'] = ['node_list'];
    return $build;
  }

}
