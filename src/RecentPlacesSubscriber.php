<?php

/**
 * @file
 * Contains \Drupal\recent_places\RecentPlacesSubscriber.
 */

namespace Drupal\recent_places;

use Drupal\Console\Bootstrap\Drupal;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;

/**
 * Subscribes to the kernel request event to completely obliterate the default content.
 *
 * @param \Symfony\Component\HttpKernel\Event\GetResponseEvent $event
 *   The event to process.
 */
class RecentPlacesSubscriber implements EventSubscriberInterface {

  /**
   * Redirects the user when they're requesting our nearly blank page.
   *
   * @param \Symfony\Component\HttpKernel\Event\GetResponseEvent $event
   *   The response event.
   */
  public function checkForPlaces(GetResponseEvent $event) {
      // Grab the saved Recent Places.
      $places = \Drupal::state()->get('recent.places') ?: array();
      $request = \Drupal::request();
      $route_match = \Drupal::routeMatch();
      $title = \Drupal::service('title_resolver')->getTitle($request, $route_match->getRouteObject());

      if(is_array($title)) {
        $title = array_key_exists('#markup', $title) ? $title['#markup'] : $title;
      } elseif (!is_null($title)) {
        $title = strip_tags($title);
      }

      // Save Recent Places only for prominent destinations with titles.
      if(!is_null($title)) {
        $places[] = array(
          'title' => $title,
          'path' => \Drupal::service('path.current')->getPath(),
          'timestamp' => REQUEST_TIME,
        );
      }
      // Saving Recent Places result on State variable.
      \Drupal::state()->set('recent.places', $places);
  }

  /**
   * {@inheritdoc}
   */
  static function getSubscribedEvents(){
      $events[KernelEvents::REQUEST][] = array('checkForPlaces');
      return $events;
  }
}