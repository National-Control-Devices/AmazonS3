<?php
/**
 * @file
 * @author Gurvindra
 * Contains \Drupal\amazons\Controller\MainController.
 */
namespace Drupal\amazons\Controller;


//use Drupal\Core\Form\FormInterface;

use Drupal\comment\CommentInterface;
use Drupal\comment\CommentStorageInterface;
use Drupal\Component\Utility\Unicode;
use Drupal\Core\Datetime\DateFormatter;
use Drupal\Core\Entity\EntityManagerInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;


/**
 * Provides route responses for the Example module.
 */
class AwsController {
  /**
   * Returns a simple page.
   *
   * @return array
   *   A simple renderable array.
   */

   public function demo() {
	    $element = array(
	      '#markup' => 'Test Amazon',
	    );
	    return $element;
	}
  }

?>