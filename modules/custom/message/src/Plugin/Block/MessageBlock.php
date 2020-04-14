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
/**
  * Provides an 'message' list block
  * @Block(
  *   id = "message_block",
  *   admin_label = @Translation("message Block"),
  *   category = @Translation("Blocks"), 
  *   )
  */
class MessageBlock extends BlockBase {
    /**
     * {@inheritdoc}
     */
    protected $id;
    public function build() {
      // return [
      //   '#markup' => $this->t('This is a simple block!'),
      // ];
      return \Drupal::formBuilder()->getForm('Drupal\message\Form\messageForm');
    }
    // public function blockAccess(AccountInterface $account){
    //   /** 
    //    * @var \Drupal\node\Entity\Node $node
    //    * permission
    //   */
    //   $node = \Drupal::routeMatch()->getParameter('node');
    //   $nid = 1;//$node->id->value;
    //   if(is_numeric($nid)){
    //     return AccessResult::allowedIfHasPermission($account , 'view message');
    //   }
    //   return AccessResult::forbidden();
    // }
}
?>