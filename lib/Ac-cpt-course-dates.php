<?php

class AC_MDC_Course_dates {
  public function __construct(){

    add_action('init', array($this, 'cpt_course_dates'));
    add_action( 'wp_insert_post', array($this, 'set_course_dates_title') );

    add_shortcode('ac_mdc', array($this, 'sc_print_course'));
    add_shortcode('ac_mdc_dates', array($this, 'sc_print_course_dates'));

    // filter for a specific field based on it's name
    add_filter('acf/fields/post_object/result/name=course_name', array($this, 'my_post_object_result'), 10, 4);

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

    $course_level = get_field('course_level', $course_obj->ID);

    $date = ac_format_date($dates_obj[0]['course_dates_start'], 'd, M Y');


    $new_title = ($course_level != '')? $course_obj->post_title .' (Level '. $course_level .') - ' . $date : $course_obj->post_title . ' - ' . $date;
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
  function sc_print_course_dates( $atts ) {
    global $post;
    $q = new WP_Query( array(
      'post_type'        => 'course_dates',
      'meta_key'	=> 'course_name',
      'meta_value'	=> $post->ID
    ) );
    $output = '';
    $output .= "<style>";
    $output .= ".current-course-dates__meta{display: block;}";
    $output .= ".current-course-dates__item{display: block;margin-bottom:0.2em;padding-bottom:0.2em; border-bottom:solid 1px #ddd;}";
    $output .= ".current-course-dates__date{font-weight: bold;}";
    $output .= "</style>";

    if ( $q->have_posts() ):
      $output .= '<div class="current-course-dates">';
      while ( $q->have_posts() ) : $q->the_post();

      $course_dates = get_field('course_dates');
      foreach ( $course_dates as $course_date) {
        $output .= "<span class=\"current-course-dates__item\"> ";
        $output .= "<span class=\"current-course-dates__date\"> ";
        $output .= ac_format_date($course_date['course_dates_start'], 'd/m/y');
        $output .= "</span> ";
        $output .= " to ";
        $output .= "<span class=\"current-course-dates__date\"> ";
        $output .= ac_format_date($course_date['course_dates_end'], 'd/m/y');
        $output .= "</span> ";
        $output .= "<small class=\"current-course-dates__meta\"> ";
        if($course_date['course_dates_module_name'] != ''){
          $output .= '(';
          $output .= $course_date['course_dates_module_name'];
          $output .= ') ';
        }

        $output .= $course_date['course_dates_location']->post_title;
        $output .= "</small>";
        $output .= "</span> ";
      }



    endwhile;
    $output .= '</div>';
    endif; wp_reset_postdata();

    return $output;
  }

  function sc_print_course( $atts ) {

    extract( shortcode_atts( array(
      'id'          => '',
      'class'       => '',
      'style'       => '',
    ), $atts, 'recent_posts' ) );


    $id            = ( $id          != ''          ) ? 'id="' . esc_attr( $id ) . '"' : '';
    $class         = ( $class       != ''          ) ? 'ac-mdc-course-list ' . esc_attr( $class ) : 'ac-mdc-course-list';
    $style         = ( $style       != ''          ) ? 'style="' . $style . '"' : '';



    $output = "";
    $output .= "<style> .ac-mdc-course-list__td--info{text-align: center} .ac-mdc-course-list__td--dates select{width:100%} </style>";
    $output .= "<div {$id} class=\"{$class}\" {$style} >";
    $output .= "<table>";
    $output .= "<thead>";
    $output .= "<tr>";
    $output .= "<th class=\"ac-mdc-course-list__th--course\" >Course</th>";
    $output .= "<th class=\"ac-mdc-course-list__th--location\">Location</th>";
    $output .= "<th class=\"ac-mdc-course-list__th--dates\">Dates</th>";
    $output .= "<th class=\"ac-mdc-course-list__th--price\">Price</th>";
    $output .= "<th class=\"ac-mdc-course-list__th--info\"></th>";



    $course_locations = array();
    $q = new WP_Query( array(
      'post_type'        => 'course_dates'
    ) );

    if ( $q->have_posts() ) : while ( $q->have_posts() ) : $q->the_post();
      $course = get_field('course_name');
      $course_id = $course->ID;
      $course_title = get_the_title($course_id);

      $course_title_words = explode(" ", $course_title);
      $course_title_acronym = "";
      foreach ($course_title_words as $w) {
        $course_title_acronym .= $w[0];
      }

      $course_title_short = (get_field('course_short_name', $course->ID) != '') ? get_field('course_short_name', $course->ID) : $course_title_acronym;

      $course_level = (get_field('course_level', $course->ID) == '')? '' :  '<small>(L' . get_field('course_level', $course->ID) . ')</small>';

      $course_dates = get_field('course_dates');

      $course_locations= array();
      foreach($course_dates as $key => $location){

        $location_id = $location['course_dates_location']->ID;
        $location_name = get_the_title($location_id);

        $course_locations[$location_name][] = array(
          'course_dates_start' => $location['course_dates_start'],
          'course_dates_end' => $location['course_dates_end'],
          'course_dates_price' => $location['course_dates_price'],
          'course_dates_module_name' => $location['course_dates_module_name'],
          );

      }


    foreach($course_locations as $location => $location_dates){

      $dates_options = '';
      foreach($location_dates as $course_date){
        $dates_options .= '<option data-js-price="'.$course_date['course_dates_price'].'">';
        $dates_options .= ac_format_date($course_date['course_dates_start'], 'd M Y') . ' to ' . ac_format_date($course_date['course_dates_end'], 'd M Y') ;
        $dates_options .= '</option>';
      }

      $output .= '<tr>';

      $output .= '<td class="ac-mdc-course-list__td--course">';
      $output .= $course_title_short . ' ' . $course_level;
      $output .= '</td>';

      $output .= '<td class="ac-mdc-course-list__td--location" >';
      $output .= $location;
      $output .= '</td>';

      $output .= '<td class="ac-mdc-course-list__td--dates" >';
      $output .= '<select>';
      $output .=  $dates_options;
      $output .= '</select>';
      //$output .= print_r($location_dates, true);
      $output .= '</td>';

      $output .= '<td class="ac-mdc-course-list__td--price" >';

      $output .= '£<span class="price__num"></span>';

      $output .= '</td>';

      $output .= '<td class="ac-mdc-course-list__td--info">';
      $output .= '<a class="x-btn x-btn-flat x-btn-square x-btn-regular" href="'.get_permalink($course->ID).'">More Info</a>';
      $output .= '</td>';

      $output .= '</tr>';
    }

    endwhile; endif; wp_reset_postdata();
    $output .= "</table>";
    $output .= '</div>';

    return $output;
  }

  function my_post_object_result( $result, $object, $field, $post ) {

    $course_level = get_field('course_level', $object->ID);
    return ($course_level != '' )? $result .= ' (Level ' . $course_level .  ')': $result;

  }



}