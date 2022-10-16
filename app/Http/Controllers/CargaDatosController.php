<?php

namespace App\Http\Controllers;

use App\Models\CargaDatos;
use Illuminate\Http\Request;
use \PhpOffice\PhpSpreadsheet\IOFactory;
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
        $array_data = CargaDatos::paginate(10);
        return view('/content/apps/cargaDatos/index', ['array_data' => $array_data]);
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
        $file_archivo = $request->archivo;
        
        $nombre_archivo = $file_archivo->getPathname();
        $spreadsheet = IOFactory::load($nombre_archivo);

        $sheetData   = $spreadsheet->getActiveSheet()->toArray();
        $totalDeHojas = $spreadsheet->getSheetCount();
          
        $array_data = array();
        if (!empty($sheetData)) 
        {
            $hojaActual = $spreadsheet->getSheet(0);
            $numeroMayorDeFila = $hojaActual->getHighestRow(); // Numérico
            $letraMayorDeColumna = $hojaActual->getHighestColumn(); // Letra

            //$totalDeHojas = $documento->getSheetCount();

            # Iterar hoja por hoja
            for ($indiceHoja = 0; $indiceHoja < $totalDeHojas; $indiceHoja++) 
            {
                # Obtener hoja en el índice que vaya del ciclo
                $hojaActual = $spreadsheet->getSheet($indiceHoja);

                # Calcular el máximo valor de la fila como entero, es decir, el
                # límite de nuestro ciclo
                $numeroMayorDeFila = $hojaActual->getHighestRow(); // Numérico

                for ($indiceFila = 4; $indiceFila <= $numeroMayorDeFila; $indiceFila++) 
                {
                    $cargaDatos = new CargaDatos();
                    $cod_empresa        = $hojaActual->getCellByColumnAndRow(1, $indiceFila)->getValue();
                    $empresa	        = $hojaActual->getCellByColumnAndRow(2, $indiceFila)->getValue();
                    $cod_empleado	    = $hojaActual->getCellByColumnAndRow(3, $indiceFila)->getValue();
                    $nombres	        = $hojaActual->getCellByColumnAndRow(4, $indiceFila)->getValue();
                    $apellidos	        = $hojaActual->getCellByColumnAndRow(5, $indiceFila)->getValue();
                    $posición	        = $hojaActual->getCellByColumnAndRow(6, $indiceFila)->getValue();
                    $dirección_vicepresidencia	= $hojaActual->getCellByColumnAndRow(7, $indiceFila)->getValue();
                    $departamento	    = $hojaActual->getCellByColumnAndRow(8, $indiceFila)->getValue();
                    $correo	            = $hojaActual->getCellByColumnAndRow(9, $indiceFila)->getValue();
                    $celular	        = $hojaActual->getCellByColumnAndRow(10, $indiceFila)->getValue();
                    $numeroDocumento	= $hojaActual->getCellByColumnAndRow(11, $indiceFila)->getValue();
                    $fecha_nacimiento	= $hojaActual->getCellByColumnAndRow(12, $indiceFila)->getValue();
                    $cod_superviso      = $hojaActual->getCellByColumnAndRow(13, $indiceFila)->getValue();

                    if(empty(trim($cod_empresa)) || empty(trim($empresa)) ||
                        empty(trim($cod_empleado)) || empty(trim($cod_empleado)) ||
                        empty(trim($nombres)) || empty(trim($apellidos)) ||
                        empty(trim($posición)) || empty(trim($dirección_vicepresidencia)) ||
                        empty(trim($departamento)) || empty(trim($correo)) ||
                        empty(trim($celular)) || empty(trim($numeroDocumento)) ||
                        empty(trim($fecha_nacimiento)) || empty(trim($cod_superviso))) 
                    {
                        //return view('/content/apps/cargaDatos/index', ['errors'=> ['Lo sentimos existen datos vacios o incorrectos']]);
                        continue;
                    }

                    $cargaDatos->cod_grupo_empresarial  = (auth()->user()->cod_grupo_empresarial != '' ? auth()->user()->cod_grupo_empresarial : 0);    
                    $cargaDatos->cod_empresa            = $cod_empresa;
                    $cargaDatos->empresa                = $empresa;
                    $cargaDatos->cod_empleado           = $cod_empleado;
                    $cargaDatos->nombres                = $nombres;
                    $cargaDatos->apellidos              = $apellidos;
                    $cargaDatos->posicion               = $posición;
                    $cargaDatos->direccion_vp           = $dirección_vicepresidencia;
                    $cargaDatos->departamento           = $departamento;
                    $cargaDatos->telefono_movil         = $celular;
                    $cargaDatos->extencion              = '0000';
                    $cargaDatos->correo_instutucional   = $correo;
                    $cargaDatos->correo_personal        = $correo;
                    $cargaDatos->documento              = $numeroDocumento;
                    $cargaDatos->fecha_nacimiento       = $fecha_nacimiento;
                    $cargaDatos->codigo_superfisor      = $cod_superviso;

                    $cargaDatos->save();
                }
            }
        }

        return redirect('admin/app/cargaDatos/')
                    ->with(['message' => 'Datos cargados correctamente ', 
                            'alert' => 'success']);
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
