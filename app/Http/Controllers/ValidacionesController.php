<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ValidacionesController extends Controller
{
    //
    public function validateValue($model, $input, $dataImput)
    {
        if($dataImput != '')
        {
            $model->$input = $dataImput;
        }
    }
}
