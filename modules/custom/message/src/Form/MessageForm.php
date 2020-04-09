<?php
/** 
 * @file
 * Contains \Drupal\message\Form\messageForm
*/
namespace Drupal\message\Form;

use Drupal\Core\Databse\Database;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/** 
 * Provides an message Email form.
 * every form in drula has an id
*/

class MessageForm extends FormBase {

    public function getFormId(){
        return 'message_email_form';
    }

    public function buildForm( array $form , FormStateInterface $form_state){
        $node = \Drupal::routeMatch()->getParameter('node');
        $nid = $node->nid->value;
        $form['name'] = array(
            '#title' => t('Your Name'),
            '#type' => 'textfield',
            '#size' => 25,
            '#description' => t("we will send update to user via email"),
            '#required' => true,            
        );
        $form['email'] = array(
            '#title' => t('Email Address'),
            '#type' => 'textfield',
            '#size' => 25,
            '#description' => t("we will send update to user via email"),
            '#required' => true,            
        );
        $form['message'] = array(
            '#title' => t('Your Message'),
            '#type' => 'textarea',
            '#size' => 25,
            '#description' => t("we will send update to user via email"),
            '#required' => true,            
        );
        $form['submit'] = array(
            '#type' => 'submit',    
            '#value' => t('message'),    
        );
        $form['nid'] = array(
            '#type' => 'hidden',    
            '#value' => $nid,    
        );
        return $form;
    }

    public function validateForm(array &$form , FormStateInterface $form_state){
        $value = $form_state->getValue('email');
        if($value == !\Drupal::service('email.validator')->isValid($value)){
            $form_state->setErrorByName('email' , t('Rhe email address %mail is not valid') , array('%mail' => $value));
        }
    }

    public function submitForm(array &$form , FormStateInterface $form_state){
        // $user = \Drupal\user\Entity\User::load(\Drupal::currentUser()->id());
        $user_id = \Drupal::currentUser()->id(); 
        $status = db_insert('message')->fields(array(
            'name' => $form_state->getValue('name'),
            'mail' => $form_state->getValue('email'),
            'message' => $form_state->getValue('message'),
            'nid' => $form_state->getValue('nid'),
            'uid' => $user_id,
            'created' => time(),
        ))->execute();
        drupal_set_message(t('Thank you for your message , you are on the list for the event.'));
    }
}
?>