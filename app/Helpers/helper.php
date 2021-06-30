<?php

/*
 *
 *  if  function return boolean  then name starts with is....
 *  if  function return value  then name starts with get...
 *
 */

use App\Company;
use Illuminate\Support\Facades\Auth;

if (!function_exists('checkIfUserAuthenticated')) {
    function checkIfUserAuthenticated()
    {
        if (!Auth::user()) {
            $result['success'] = false;
            $result['message'] = 'Seems like you have been logged Out, Please Refresh Your Page';
            return $result;
        }
    }
}

if (!function_exists('checkIfCollectionNotEmpty')) {
    function checkIfCollectionNotEmpty($query)
    {
        if (!$query) {
            $result['success'] = false;
            $result['message'] = 'Something Went Wrong while Inserting into Database';
            return $result;
        }
    }
}

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