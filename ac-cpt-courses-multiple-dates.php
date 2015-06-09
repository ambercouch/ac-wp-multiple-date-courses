<?php
/*

Plugin Name: ac &ndash; CPT &ndash; Multiple Date Courses
Plugin URI: http://ambercouch.co.uk
Description: Add a custom post type Courses
Version: 1
Author: Ambercouch
Author URI: http://ambercouch.co.uk
Text Domain: ac_
*/

define( 'ACMDC_VERSION', '4.1.2' );

define( 'ACMDC_PLUGIN', __FILE__ );

define( 'ACMDC_PLUGIN_BASENAME', plugin_basename( ACMDC_PLUGIN ) );

define( 'ACMDC_PLUGIN_NAME', trim( dirname( ACMDC_PLUGIN_BASENAME ), '/' ) );

define( 'ACMDC_PLUGIN_DIR', untrailingslashit( dirname( ACMDC_PLUGIN ) ) );

require_once ACMDC_PLUGIN_DIR . '/lib/Ac-cpt-courses.php';
require_once ACMDC_PLUGIN_DIR . '/lib/Ac-cpt-course-locations.php';
require_once ACMDC_PLUGIN_DIR . '/lib/Ac-cpt-course-dates.php';

$acCourses = new AC_MDC_Courses();
$acCourse_locations = new AC_MDC_Course_locations();
$acCourses_multiple_dates = new AC_MDC_Course_dates();