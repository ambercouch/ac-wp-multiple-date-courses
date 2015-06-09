<?php

class AC_MDC_Course_dates {
  public function __construct(){

    add_action('init', array($this, 'cpt_course_dates'));
    add_action( 'wp_insert_post', array($this, 'set_course_dates_title') );


  }

  function  cpt_course_dates() {
    //course dates
    $labels = array(
      'name' => _x('Course Dates', 'post type general name'),
      'singular_name' => _x('Course Dates', 'post type singular name'),
      'add_new' => _x('Add New', 'Course Dates'),
      'add_new_item' => __('Add New Course Dates'),
      'edit_item' => __('Edit Course Dates'),
      'new_item' => __('New Course Dates'),
      'all_items' => __('All Course Dates'),
      'view_item' => __('View Course Dates'),
      'search_items' => __('Search Course Dates'),
      'not_found' => __('No course dates found'),
      'not_found_in_trash' => __('No course dates found in the Trash'),
      'parent_item_colon' => '',
      'menu_name' => 'Course Dates'
    );
    $args = array(
      'labels' => $labels,
      'description' => 'Malpeet K9 Courses',
      'public' => true,
      'menu_position' => 20,
      'supports' => FALSE,
      'has_archive' => true,
    );
    register_post_type('course_dates', $args);
    //course_dates

  }

  function set_course_dates_title($post_id) {

    //prevent inf loop
    remove_action( 'wp_insert_post', array($this, 'set_course_dates_title') );

    $course_obj = get_field('course_name', $post_id);
    $dates_obj = get_field('course_dates', $post_id);

    if ( wp_is_post_revision( $post_id ) || $course_obj == FALSE){
      return;
    }

    if (get_post_type($post_id) != 'course_dates') {
      return;
    }
    $start_date = $dates_obj[0]['course_dates_start'];

    $phpdate = strtotime( $start_date );
    $date = date( 'd, M Y', $phpdate );

    $new_title = $course_obj->post_title . ' - ' . $date;
    $new_slug = sanitize_title( $new_title );

    $my_post = array(
      'ID'           => $post_id,
      'post_title'   => $new_title,
      'post_name'   => $new_slug,
    );

    // Update the post into the database
    wp_update_post( $my_post );

    //prevent inf loop - set, previously removed action
    add_action( 'wp_insert_post', array($this, 'set_course_dates_title') );

  }

}