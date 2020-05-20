<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
Use DB;
Use Session;
Use Auth;
Use Redirect;

class UtilsController extends Controller
{

	public function reversarFecha($fecha, $style)
    {
        switch ($style) {
            case 'DB':
                $date = implode("", array_reverse(explode("/", $fecha)));
            break;
        }
        return $date;
    }
}