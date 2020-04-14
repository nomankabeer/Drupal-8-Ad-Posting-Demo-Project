<?php
/** 
 * @file
 * contains \Drupal\message\Plugin\Block\messageBlock
*/
namespace Drupal\message\Plugin\Block;
use Drupal\Core\Access\AccessResult;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\message\Controller\ReportController;
/**
  * Provides an 'message' list block
  * @Block(
  *   id = "message_list_block",
  *   admin_label = @Translation("message List Block"),
  *   category = @Translation("Blocks"), 
  *   )
  */
class MessageListBlock extends BlockBase {
    /**
     * {@inheritdoc}
     */
    protected $id;
    private function loadMessages() {
        $node = \Drupal::routeMatch()->getParameter('node');
        if($node == null)
        {
          return null;
        }
        $database = \Drupal::database();
        $query = $database->query("SELECT * FROM {message} WHERE nid = ".$node->id());
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

    public function build() {
        // return \Drupal::formBuilder()->getForm('Drupal\message\Form\messageForm');
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
        $entries = $this->loadMessages();
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
?>