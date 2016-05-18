<?php defined('BASEPATH') OR exit('No direct script access allowed');

/** 
* 根据给定时间戳得到天/时/分/秒
*/
if ( ! function_exists('timestamp2dhis'))
{
	/**
	 * @param	$format string 返回的格式
	 * @param	$timestamp int 时间戳
	 * @return $dhis string 包含天/时/分/秒等的字符串, 目前只支持有前导0的格式
	 */
	function timestamp2dhis($format, $timestamp)
	{
		 $day = floor($timestamp / (24*3600));
		 $timestamp = $timestamp % (24*3600);
        $hour = floor($timestamp / 3600);
        $timestamp = $timestamp % 3600;
        $minute = floor($timestamp / 60);
        $second = $timestamp % 60;

        $day = $day<10 ? '0'.$day : $day;
        $hour = $hour<10 ? '0'.$hour : $hour;
        $minute = $minute<10 ? '0'.$minute : $minute;
        $second = $second<10 ? '0'.$second : $second;

        $dhis = $format;
        $dhis = str_replace('d', $day, $dhis);
        $dhis = str_replace('h', $hour, $dhis);
        $dhis = str_replace('i', $minute, $dhis);
        $dhis = str_replace('s', $second, $dhis);

        return $dhis;
	}
}