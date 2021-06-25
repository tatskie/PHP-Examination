<?php

/*
 *
 *  if  function return boolean  then name starts with is....
 *  if  function return value  then name starts with get...
 *
 */

use App\Company;

if (!function_exists('getCompany')) {
    function getCompany()
    {
        $array = Company::select('*');
        $array = $array->get()->toArray();

        foreach ($array as $ele) {
            $arr[$ele['id']] = ucwords($ele['name']);
        }
        return $arr;
    }
}

if (!function_exists('getSelectOptions')) {
    function getSelectOptions($arr, $selected = null, $default = null)
    {
        $options = array();
        if (is_array($arr)) {
            if ($default != '') {
                array_push($options, '<option value="0"  disabled selected>' . ucwords($default) . '</options>');
            }
            foreach ($arr as $key => $value) {
                if ($selected == $key || $selected == 'all') {
                    array_push($options, '<option value="' . $key . '" selected>' . ucwords($value) . '</options>');
                } else {
                    array_push($options, '<option value="' . $key . '" >' . ucwords($value) . '</options>');
                }
            }
            return implode('', $options);
        }
    }
}