<?php

namespace Drupal\recent_places\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;

/**
 * Provides a Block to display recent places you visited on the website.
 *
 * @Block(
 *   id = "recent_places_block",
 *   admin_label = @Translation("Recent Places")
 * )
 */
class RecentPlacesBlock extends BlockBase {

    /**
     * {@inheritdoc}
     */
    public function defaultConfiguration() {
      $this->configuration['num_places'] = 5;
      return ['label_display' => FALSE];
    }

    /**
     * {@inheritdoc}
     */
    public function build() {
        // Get Recently visited links
        $places = \Drupal::state()->get('recent.places') ?: array();

        // Flip the saved array to show newest pages first.
        $reverse_places = array_reverse($places);

        // Grab the number of items to display
        $num_items = $this->configuration['num_places'] ?: 5;

        // Output the latest items as a list
        $output = ''; // Initializing output variable.

        for ($i = 0; $i < $num_items; $i++) {
          if(isset($reverse_places[$i])) {
            if ($item = $reverse_places[$i]) {
              $url = Url::fromUri('base:' . $item['path'], array('absolute' => TRUE))->toString();
              $output .= '<li><a href="' . $url . '">' . $item['title'] . '</a> - | @ ' . format_date($item['timestamp'], 'short') . '. </li>
              ';
            }
          }
        }
        if (isset($output)) {
          $output = '<p>' . $this->t('Below are the links you visited recently.') . '</p>
          <ul>' . $output . '</ul>
          ';
        }
        return array('#markup' => $output);
    }

    /**
     * {@inheritdoc}
     */
    public function buildConfigurationForm(array $form, FormStateInterface $form_state) {
      $form = parent::buildConfigurationForm($form, $form_state);

      // Get the maximum allowed value from the configuration form.
      $max_places = \Drupal::config('recent_places.settings')->get('max_places');
      $default_places = isset($this->configuration['num_places']) ? $this->configuration['num_places'] : 5;

      // Add a select box of numbers form 1 to $max_to_display.
      $form['block_num_places_form'] = array(
        '#type' => 'select',
        '#title' => t('Number of items to show'),
        '#default_value' => $default_places,
        '#options' => array_combine(range(1,$max_places), range(1,$max_places)),
      );
      return $form;
    }

    /**
     * {@inheritdoc}
     */
    public function submitConfigurationForm(array &$form, FormStateInterface $form_state) {
      $this->configuration['num_places'] = $form_state->getValue('block_num_places_form');
    }

    /**
     * {@inheritdoc}
     *
     * disable block cache to keep it the Recent Places update.
     */
    public function getCacheMaxAge()
    {
      return 0;
    }
}
