<?php

namespace App\Http\Controllers;

use App\Models\Pantallas;
use Illuminate\Http\Request;

class PantallaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public static function listMenu($id_padre = null)
    {
        if($id_padre == null)
        {
            $listMenu = Pantallas::where([['id_padre', null],['estatus', 'A']])
                                ->orderBy('orden', 'ASC')
                                ->get();
        }
        else if($id_padre == 'all')
        {
            $listMenu = Pantallas::where('estatus', 'A')
                                ->orderBy('orden', 'ASC')
                                ->get();
        }
        else
        {
            $listMenu = Pantallas::where([['id_padre', $id_padre],['estatus', 'A']])
                                ->orderBy('orden', 'ASC')
                                ->get();
        }

        return $listMenu;
    }

    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
