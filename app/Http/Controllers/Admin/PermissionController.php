<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    //

    // Access permission App
    public function access_permission()
    {
        $pageConfigs = ['pageHeader' => false,];

        return view('/content/apps/rolesPermission/app-access-permission', ['pageConfigs' => $pageConfigs]);
    }

}
