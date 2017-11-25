<?php

/**
 * @file
 * Contains \Drupal\recent_places\Controller\RecentPlacesController.
 */

namespace Drupal\recent_places\Controller;

Use Drupal\Core\Controller\ControllerBase;

/**
 * Controller routines for glue page routes.
 */
class RecentPlacesController extends ControllerBase{
    public function helloWorldPage() {
        return array(
            '#markup' => t('<p>Hello, world!</p>'),
        );
    }
}


