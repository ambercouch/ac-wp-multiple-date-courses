<?php
/**
 * Created by PhpStorm.
 * User: Richard
 * Date: 09/06/2015
 * Time: 17:54
 */

if(! function_exists('ac_format_date')) {
  function ac_format_date($date, $format){
    $phpdate = strtotime( $date );
    $date = date( $format, $phpdate );

    return $date;
  }
}