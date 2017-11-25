<?php

namespace Drupal\recent_places\Form;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Configures the Quantity of Recent Places displayed for Recent Places Block.
 */
class RecentPlacesSettingsForm extends ConfigFormBase {

  /**
   * Constructs a RecentPlacesSettingsForm object.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The factory for configuration objects.
   */
  public function __construct(ConfigFactoryInterface $config_factory) {
      parent::__construct($config_factory);
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'recent_places_settings';
  }

  /**
  * {@inheritdoc}
  */
  protected function getEditableConfigNames() {
      return ['recent_places.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['max_places'] = [
      '#type' => 'select',
      '#title' => $this->t('Maximum number of places to display'),
      '#options' => array_combine(range(1,20), range(1,20)),
      '#default_value' => $this->config('recent_places.settings')->get('max_places'),
      '#description' => $this->t('Maximum Links allowed in Recent Places Block'),
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    parent::validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->config('recent_places.settings')
      ->set('max_places', $form_state->getValue('max_places'))
      ->save();
    parent::submitForm($form, $form_state);
  }
}
