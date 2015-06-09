<?php

class AC_MDC_Courses {

  public function __construct() {

    //add_shortcode('Dribbble', array($this, 'shortcode'));
    add_action('init', array($this, 'cpt_courses'));
    //add_action('init', array($this, 'mpk9_tax'));

  }

  function cpt_courses() {

    //course
    $labels = array('name' => _x('Courses', 'post type general name'),
      'singular_name' => _x('Course', 'post type singular name'),
      'add_new' => _x('Add New', 'Course'),
      'add_new_item' => __('Add New Course'),
      'edit_item' => __('Edit Course'),
      'new_item' => __('New Course'), 'all_items' => __('All Courses'),
      'view_item' => __('View Course'),
      'search_items' => __('Search Courses'),
      'not_found' => __('No course found'),
      'not_found_in_trash' => __('No courses found in the Trash'),
      'parent_item_colon' => '',
      'menu_name' => 'Courses');
    $args = array('labels' => $labels,
      'description' => 'Courses details',
      'public' => true, 'menu_position' => 20,
      'supports' => array('title', 'editor', 'thumbnail', 'revisions'), 'has_archive' => true,);
    register_post_type('course', $args);
    //course

  }

}