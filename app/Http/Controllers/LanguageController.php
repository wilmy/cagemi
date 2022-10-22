<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LanguageController extends Controller
{
    //
    public function swap($locale){
         // available language in template array
        $availLocale=['en'=>'en', 'es'=>'es', 'fr'=>'fr','de'=>'de','pt'=>'pt'];
        // check for existing language
        if(array_key_exists($locale,$availLocale)){
            session()->put('locale',$locale);
            \App::setLocale($locale);
        }
         return redirect()->back();
    }
}
