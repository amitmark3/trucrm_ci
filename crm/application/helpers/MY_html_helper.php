<?php defined('BASEPATH') OR exit('No direct script access allowed');

// ------------------------------------------------------------------------
if ( ! function_exists('get_level') )
{
    function get_level($percentage)
    {
        if ($percentage <= 80)
        {
            $level = 'danger';
        }
        elseif ($percentage > 80 && $percentage <= 90)
        {
            $level = 'warning';
        }
        else
        {
            $level = 'success';
        }

        return $level;
    }
}

// ------------------------------------------------------------------------
