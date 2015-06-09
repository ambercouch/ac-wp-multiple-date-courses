<?php

class AC_MDC_Course_locations {

  public function __construct() {

    //add_shortcode('Dribbble', array($this, 'shortcode'));
    add_action('init', array($this, 'cpt_course_locations'));
    //add_action('init', array($this, 'mpk9_tax'));

  }

  function cpt_course_locations() {
    //course locations
    $labels = array(
      'name' => _x('Course Locations', 'post type general name'),
      'singular_name' => _x('Course Location', 'post type singular name'),
      'add_new' => _x('Add New', 'Course Locations'),
      'add_new_item' => __('Add New Course Location'),
      'edit_item' => __('Edit Course Location'),
      'new_item' => __('New Course Location'),
      'all_items' => __('All Course Locations'),
      'view_item' => __('View Course Location'),
      'search_items' => __('Search Course Locations'),
      'not_found' => __('No course locations found'),
      'not_found_in_trash' => __('No course locations found in the Trash'),
      'parent_item_colon' => '',
      'menu_name' => 'Course Locations'
    );
    $args = array(
      'labels' => $labels,
      'description' => 'Malpeet K9 Course Locations',
      'public' => true,
      'menu_position' => 20,
      'supports' => array(),
      'has_archive' => true,
    );
    register_post_type('course_location', $args);
    //course_location
  }

}