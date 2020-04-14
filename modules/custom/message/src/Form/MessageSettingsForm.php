<?php
/** 
 * @file
 * Contains \Drupal\message\Form\messageSettingsForm
*/
namespace Drupal\message\Form;
use Drupal\Core\Form\ConfigFormBase;
use Symfony\Conponent\HttpFoundation\Request;
use Drupal\Core\Form\FormStateInterface;
/** 
 * Defines a fomr to configure message list module settings
*/
class MessageSettingsForm extends ConfigFormBase {
    /** 
     * {@inheritdoc}
    */
    public function getFormID(){
        return 'message_admin_settings';
    }
    /** 
     * {@inheritdoc}
    */
    protected function getEditableConfigNames(){
        return ['message.settings'];
    }
    
    /**
    * {@inheritdoc}
    */
    public function buildForm(array $form, FormStateInterface $form_state, Request $request = NULL) {
        $types = node_type_get_names();
        $config = $this->config('message.settings');
        $form['message_types'] = array(
            '#type' => 'checkboxes',
            '#title' => $this->t('The content types to enable message collection for'),
            '#default_value' => $config->get('allowed_types'),
            '#options' => $types,
            '#description' => t('On the specified node types, an message option will be available and can be enabled while tht node is being edited.'),
        );
        $form['array_filter'] = array('#type' => 'value', '#value' => TRUE);
        return parent::buildForm($form,$form_state);    
  }
  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $allowed_types = array_filter($form_state->getValue('message_types'));
    sort($allowed_types);
    $this->config('message.settings')
      ->set('allowed_types', $allowed_types)
      ->save();
    parent::submitForm($form, $form_state);
  }
}

?>