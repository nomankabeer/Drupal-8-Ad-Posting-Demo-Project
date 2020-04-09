<?php
/**
 * @file
 * Contains \Drupal\message\Controller\ReportController.
 */
namespace Drupal\message\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Database\Database;

/**
 * Controller for message List Report
*/
class ReportController extends ControllerBase {

  /**
   * Gets all messages for all nodes.
   *
   * @return array
   */
  protected function load() {

    $current_user = \Drupal::currentUser()->id();
    $current_user_role = \Drupal::currentUser()->getRoles()[1];
    $current_user_role == 'moderator' ? $content_type = 'new_car' : $content_type = 'ad';
    $query = \Drupal::entityQuery('node');
    $query->condition('uid', $current_user);
    $query->condition('status', 1);
    $query->condition('type', $content_type);
    $entity_ids = $query->execute();
    // $nodes = Node::loadMultiple($entity_ids);
    if(count($entity_ids) == 0 ){
      return null;
    }
    $ids = array();
    foreach($entity_ids as $id){
      $ids[] = $id;
    }
    $ids = implode(',' , $ids);
    $database = \Drupal::database();
    $query = $database->query("SELECT * FROM {message} WHERE nid IN ($ids)");
    $rows = array();
    foreach ($query->fetchAll() as $entry) {
        $rows[] = array(
            'name' => $entry->name,
            'email' => $entry->mail,
            'message' => $entry->message
        );
      }
    return $rows;
  }

  /**
   * Creates the report page.
   *
   * @return array
   *  Render array for report output.
   */
  public function report() {
    $content = array();
    $content['message'] = array(
      '#markup' => $this->t('Below are the contact messages sended by users.'),
    );
    $headers = array(
      t('Name'),
      t('Email'),
      t('Message'),
    );
    $rows = array();
    $entries = $this->load();
    if($entries != null){
      foreach ( $entries as $entry) {
        // Sanitize each entry.
        $rows[] = array_map('Drupal\Component\Utility\SafeMarkup::checkPlain', $entry);
      }
    }
    
    $content['table'] = array(
      '#type' => 'table',
      '#header' => $headers,
      '#rows' => $rows,
      '#empty' => t('No entries available.'),
    );
    // Don't cache this page.
    $content['#cache']['max-age'] = 0;
    return $content;
  }

}
