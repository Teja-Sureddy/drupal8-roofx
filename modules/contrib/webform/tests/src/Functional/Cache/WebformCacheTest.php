<?php

namespace Drupal\Tests\webform\Functional\Cache;

use Drupal\Tests\webform\Functional\WebformBrowserTestBase;
use Drupal\webform\Entity\Webform;
use Drupal\webform\Entity\WebformSubmission;

/**
 * Tests for #cache properties.
 *
 * @group webform
 */
class WebformCacheTest extends WebformBrowserTestBase {

  /**
   * Test cache.
   */
  public function testCache() {
    /** @var \Drupal\Core\Entity\EntityFormBuilder $entity_form_builder */
    $entity_form_builder = \Drupal::service('entity.form_builder');

    $account = $this->createUser();

    /** @var \Drupal\webform\WebformInterface $webform */
    $webform = Webform::load('contact');
    /** @var \Drupal\webform\WebformSubmissionInterface $webform_submission */
    $webform_submission = WebformSubmission::create(['webform_id' => 'contact']);

    /**************************************************************************/

    $form = $entity_form_builder->getForm($webform_submission, 'add');

    // Check that the form includes 'user.roles:authenticated' because the
    // '[current-user:mail]' token.
    $this->assertEqual($form['#cache'], [
      'contexts' => [
        'user.roles:authenticated',
      ],
      'tags' => [
        'config:webform.webform.contact',
        'webform:contact',
        'config:webform.settings',
        'config:core.entity_form_display.webform_submission.contact.add',
      ],
      'max-age' => -1,
    ]);

    // Check that the name element does not have #cache because the
    // '[current-user:mail]' is set via
    // \Drupal\webform\WebformSubmissionForm::setEntity
    $this->assertFalse(isset($form['elements']['email']['#cache']));
    $this->assertEqual($form['elements']['email']['#default_value'], '');

    // Login and check the #cache property.
    $this->drupalLogin($account);
    $webform_submission->setOwnerId($account);
    \Drupal::currentUser()->setAccount($account);

    // Must create a new submission with new data which is set via
    // WebformSubmissionForm::setEntity.
    // @see \Drupal\webform\WebformSubmissionForm::setEntity
    $webform_submission = WebformSubmission::create(['webform_id' => 'contact']);

    $form = $entity_form_builder->getForm($webform_submission, 'add');

    // Check that the form includes 'user.roles:authenticated' because the
    // '[current-user:mail]' token.
    $form['#cache']['tags'] = array_values($form['#cache']['tags']);
    $this->assertEqual($form['#cache'], [
      'contexts' => [
        'user',
        'user.roles:authenticated',
      ],
      'tags' => [
        'config:webform.webform.contact',
        'webform:contact',
        'config:webform.settings',
        'config:core.entity_form_display.webform_submission.contact.add',
        'user:2',
      ],
      'max-age' => -1,
    ]);
    $this->assertFalse(isset($form['elements']['email']['#cache']));
    $this->assertEqual($form['elements']['email']['#default_value'], $account->getEmail());

    // Add the '[current-user:mail]' to the name elements' description.
    $element = $webform->getElementDecoded('email')
      + ['#description' => '[current-user:mail]'];
    $webform
      ->setElementProperties('email', $element)
      ->save();

    $form = $entity_form_builder->getForm($webform_submission, 'add');

    // Check that the 'email' element does have '#cache' property because the
    // '#description' is using the '[current-user:mail]' token.
    $this->assertEqual($form['elements']['email']['#cache'], [
      'contexts' => [
        'user',
      ],
      'tags' => [
        'config:webform.webform.contact',
        'webform:contact',
        'config:webform.settings',
        'user:2',
      ],
      'max-age' => -1,
    ]);
    $this->assertEqual($form['elements']['email']['#default_value'], $account->getEmail());
    $this->assertEqual($form['elements']['email']['#description']['#markup'], $account->getEmail());
  }

}
