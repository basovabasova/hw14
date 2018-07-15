<?php

require_once 'config.php';   

function isRegister()
{
    return !empty($_SESSION['user']);
}

function showErrorMessage($data)
{
    $err = '<ul>'."\n"; 
    
    if(is_array($data)) {
        foreach($data as $val) {
            $err .= '<li style="color:red;">'. $val .'</li>'."\n";
        }
    } else {
        $err .= '<li style="color:red;">'. $data .'</li>'."\n";
    }
    
    $err .= '</ul>'."\n";
    
    return $err;
}

function redirect($page)
{
    header("Location: $page.php");
    die;
}