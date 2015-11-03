<?php
/**
 * @file 
 * Contains \Drupal\amazons\Form\MyForm.
 */
 
namespace Drupal\amazons\Form;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Component\Utility\UrlHelper;

use Drupal\Core\Datetime\Entity\DateFormat;
use Drupal\Core\Field\FieldDefinitionInterface;
use Drupal\Core\Render\BubbleableMetadata;
use Drupal\Core\Render\Element;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\Core\Url;
use Drupal\file\Entity\File;
use Drupal\file\FileInterface;
use Drupal\Component\Utility\NestedArray;
use Drupal\Component\Utility\Unicode;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Template\Attribute;
use Drupal\file\FileUsage\FileUsageInterface;

require drupal_get_path('module', 'amazons') . '/src/aws/aws-autoloader.php';
use \Aws\S3\S3Client;

/**
 * My Form.
 */
class MyAwsForm extends FormBase {
  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'amazons_my_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
		
    	$form['category'] = array( 
			'#type' => 'textfield', 
			'#title' => t('Category'), 
		);
		$form['header_bg'] = array(
		    '#type' => 'managed_file',
		    '#title' => t('Block image'),
		    '#size' => 40,
		    '#description' => t("Image should be less than 400 pixels wide and in JPG format."),
		  	'#upload_location' => 'public://ncdfiles/'
		  ); 
		
		$form['actions'] = array(
			'#type' => 'actions'
		); 
		$form['actions']['submit'] = array( 
			'#type' => 'submit', 
			'#value' => t('Save'), 
		); 
		return $form; 
	} 
	
	/**
   * {@inheritdoc}
   */
	 public function validateForm(array &$form, FormStateInterface $form_state) {
		if (strlen($form_state->getValue('category')) == '') {
		   $form_state->setErrorByName('category', $this->t('Please enter category.'));
		}
		// if (strlen($form_state->getValue('message')) == '') {
		  // $form_state->setErrorByName('message', $this->t('Please enter message.'));
		// }
	  }
	
	
	public function submitForm(array &$form, FormStateInterface $form_state) {
			
		$category = $form_state->getValue('category'); 
		$header_bg = $form_state->getValue('header_bg')[0]; 
		
		$fid = $form_state->getValue('header_bg')[0];
		
		$filename = db_select('file_managed', 'f')
		    ->condition('f.fid', $fid, '=')
		    ->fields('f', array('filename'))
		    ->execute()->fetchField();
			
		$data = db_select('file_managed', 'f')
		    ->condition('f.fid', $fid, '=')
		    ->fields('f', array('uri'))
		    ->execute()->fetchField();
		
		$config = $this->config('amazons.settings');
		$AccessKeyId = $config->get('AccessKeyId');
		$SecretAccessKey = $config->get('SecretAccessKey');
		$region = $config->get('region');
		$bucket = $config->get('bucketname');
		
		$s3Client = new \Aws\S3\S3Client([
		    'version'     => 'latest',
		    'region'      => $region,
		    'credentials' => [
		        'key'    => $AccessKeyId,
		        'secret' => $SecretAccessKey,
		    ],
		]);	
		
		//Creating bucket dynamically
		// $result1 = $s3Client->createBucket(array('Bucket' => 'mybucket'));
		// 	    
		// echo "<pre>";
		// print_r($result1);
		// exit;
		
		
	    //$bucket = 'ncdfiles';
		$key = date('ymdhis') . time() . $filename ;
		//$data = 'public://ncdfiles/' . $filename;
		
		$result = $s3Client->putObject(array(
	    'Bucket'       => $bucket,
	    'Key'          => $key,
	    'SourceFile'   => $data,
	    //'ContentType'  => 'text/plain',
	    'ACL'          => 'public-read',
	    //'StorageClass' => 'REDUCED_REDUNDANCY',
	    // 'Metadata'     => array(    
	        // 'param1' => 'value 1',
	        // 'param2' => 'value 2'
	    // )
		));
		db_insert('amazon_s3_data')
			->fields(array('cat_name' => $category,'file_path' => $result['ObjectURL'],'fid' => $fid))
			->execute();
		
		drupal_set_message('File uploaded to AWS server');
		return;
	}
}
?>

