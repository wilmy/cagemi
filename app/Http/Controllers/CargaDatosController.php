<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class CargaDatosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('/content/apps/cargaDatos/index');
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
        /*$spreadsheet = new Spreadsheet();

        $reader = new Xlsx($spreadsheet);

        $reader->setReadDataOnly(true);
        $spreadsheet = $reader->load("05featuredemo.xlsx");*/
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load("plantilla.csv");
        $sheetData   = $spreadsheet->getActiveSheet()->toArray();

        $array_data = array();
        if (!empty($sheetData)) 
        {
            for ($i=1; $i<count($sheetData); $i++) 
            { 
                for ($x=0; $x < count($sheetData[$i]); $x++) 
                {
                    if(!empty($sheetData[$i][$x])) 
                    {
                        array_push($array_data, $sheetData[$i][$x]);
                    }
                }
            }
        }


        return view('/content/apps/cargaDatos/index', ['array_data'=>$array_data]);
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
