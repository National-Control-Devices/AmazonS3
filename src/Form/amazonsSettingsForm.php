<?php
/**
 * @file
 * Contains \Drupal\hello\helloSettingsForm
 */
namespace Drupal\amazons\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Configure hello settings for this site.
 */
class amazonsSettingsForm extends ConfigFormBase {
  /** 
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'amazons_admin_settings';
  }

  /** 
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'amazons.settings',
    ];
  }

  /** 
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('amazons.settings');

    $form['aws_AccessKeyId'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Access Key Id'),
      '#default_value' => $config->get('AccessKeyId'),
    ); 
	$form['aws_SecretAccessKey'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Secret Access Key'),
      '#default_value' => $config->get('SecretAccessKey'),
    );
	$form['aws_bucketname'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Bucket Name'),
      '#default_value' => $config->get('bucketname'),
    ); 
	$form['aws_region'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Region'),
      '#default_value' => $config->get('region'),
    ); 

    return parent::buildForm($form, $form_state);
  }

  /** 
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $config = $this->config('amazons.settings');
	$config->set('AccessKeyId', trim($form_state->getValue('aws_AccessKeyId')))->save();
	$config->set('SecretAccessKey', trim($form_state->getValue('aws_SecretAccessKey')))->save();
	$config->set('region', trim($form_state->getValue('aws_region')))->save();
	$config->set('bucketname', trim($form_state->getValue('aws_bucketname')))->save();
    parent::submitForm($form, $form_state);
  }
}
?>