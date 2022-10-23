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
        $array_data = CargaDatos::where('cod_grupo_empresarial', auth()->user()->cod_grupo_empresarial)->paginate(100);
        $mensaje_alert = $this->validacionesDataTmp();
        return view('/content/apps/cargaDatos/index', ['array_data' => $array_data, 'mensaje_alert' => $mensaje_alert]);
    }

    public static function validacionesDataTmp()
    {
        $array_data_all = CargaDatos::all();
        $mensaje_alert = '';
        foreach($array_data_all as $valores)
        {
            $fila = $valores->fila;
            $colorClass = '';
            $messa_parm = __('The row record').' @fila '. __('has the field').' <b>@campo</b> '. __('empty').'.';

            if($valores->empresa == '') 
            {
                $messs = str_replace('@fila', $fila, str_replace('@campo', 'empresa', $messa_parm));
                $mensaje_alert .= '<li>-'.$messs.'</li>';
            }

            if($valores->cod_empleado == '') 
            {
                $messs = str_replace('@fila', $fila, str_replace('@campo', 'codigo de empleado<', $messa_parm));
                $mensaje_alert .= '<li>-'.$messs.'</li>';
            }

            if($valores->nombres == '') 
            {
                $messs = str_replace('@fila', $fila, str_replace('@campo', 'nombre', $messa_parm));
                $mensaje_alert .= '<li>-'.$messs.'</li>';
            }

            if($valores->apellidos == '') 
            {
                $messs = str_replace('@fila', $fila, str_replace('@campo', 'apellido', $messa_parm));
                $mensaje_alert .= '<li>-'.$messs.'</li>';
            }

            if($valores->direccion_vp == '') 
            {
                $messs = str_replace('@fila', $fila, str_replace('@campo', 'direccion o vicepresidencia', $messa_parm));
                $mensaje_alert .= '<li>-'.$messs.'</li>';
            }

            if($valores->departamento == '') 
            {
                $messs = str_replace('@fila', $fila, str_replace('@campo', 'departamento', $messa_parm));
                $mensaje_alert .= '<li>-'.$messs.'</li>';
            }

            if($valores->documento == '') 
            {
                $messs = str_replace('@fila', $fila, str_replace('@campo', 'documento', $messa_parm));
                $mensaje_alert .= '<li>-'.$messs.'</li>';
            }

            $title = '';
            foreach($array_data_all as $valida)
            {
                if($valida->id != $valores->id)
                {
                    if(($valida->empresa == $valores->empresa && $valida->cod_empleado == $valores->cod_empleado) ||
                        ($valida->empresa == $valores->empresa && $valida->documento == $valores->documento))
                    {
                        $mensaje_alert .= '<li>-'.__('The row record').' '.$fila.' '.__('is with the same employee code').' '.$valida->cod_empleado.' '.__('or same document').' '.$valida->documento.' ' .__('in the same company.').'</li>';
                        //'<li>-El registro de la fila ('.$fila.'), esta con el mismo codigo de empleado ('.$valida->cod_empleado .'), o mismo documento ('.$valida->documento .') en la misma empresa.</li>';
                        break;
                    }
                }
            }
        }

        return $mensaje_alert;
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
        if(empty(auth()->user()->cod_grupo_empresarial))
        {
            return view('/content/apps/cargaDatos/index', 
                    ['message'=> [__('The user does not have a business group defined ').auth()->user()->cod_grupo_empresarial]]);
        }

        $file_archivo = $request->archivo;
        
        $nombre_archivo = $file_archivo->getPathname();
        $spreadsheet = IOFactory::load($nombre_archivo);

        $sheetData   = $spreadsheet->getActiveSheet()->toArray();
        $totalDeHojas = $spreadsheet->getSheetCount();
          
        $array_data = array();
        if (!empty($sheetData)) 
        {
            //Eiminamos los datos si es del mismo grupo empresarial 
            $delet = CargaDatos::where('cod_grupo_empresarial', auth()->user()->cod_grupo_empresarial)->delete();
            
            //Validamos y cargamos los datos
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
    
                for ($indiceFila = 2; $indiceFila <= $numeroMayorDeFila; $indiceFila++) 
                {
                    $cargaDatos = new CargaDatos();
                    $empresa	        = $hojaActual->getCellByColumnAndRow(1, $indiceFila)->getValue();
                    $cod_empleado	    = $hojaActual->getCellByColumnAndRow(2, $indiceFila)->getValue();
                    $nombres	        = $hojaActual->getCellByColumnAndRow(3, $indiceFila)->getValue();
                    $apellidos	        = $hojaActual->getCellByColumnAndRow(4, $indiceFila)->getValue();
                    $posición	        = $hojaActual->getCellByColumnAndRow(5, $indiceFila)->getValue();
                    $dirección_vicepresidencia	= $hojaActual->getCellByColumnAndRow(6, $indiceFila)->getValue();
                    $departamento	    = $hojaActual->getCellByColumnAndRow(7, $indiceFila)->getValue();
                    $numeroDocumento	= $hojaActual->getCellByColumnAndRow(8, $indiceFila)->getValue();
                    $correo	            = $hojaActual->getCellByColumnAndRow(9, $indiceFila)->getValue();
                    $celular	        = $hojaActual->getCellByColumnAndRow(10, $indiceFila)->getValue();
                    $fecha_nacimiento_no_formt	= $hojaActual->getCellByColumnAndRow(11, $indiceFila)->getValue();
                    $cod_superviso      = $hojaActual->getCellByColumnAndRow(12, $indiceFila)->getValue();
                    $extencion          = '';

                    $fecha_nacimiento = '';
                    if($fecha_nacimiento_no_formt != '')
                    {
                        $fecha_nacimiento = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($fecha_nacimiento_no_formt)->format('Y-m-d');
                    }

                    $cargaDatos->cod_grupo_empresarial  = auth()->user()->cod_grupo_empresarial;
                    $this->validateValue($cargaDatos, 'empresa', $empresa);
                    $this->validateValue($cargaDatos, 'cod_empleado', $cod_empleado);
                    $this->validateValue($cargaDatos, 'nombres', $nombres);
                    $this->validateValue($cargaDatos, 'apellidos', $apellidos);
                    $this->validateValue($cargaDatos, 'posicion', $posición);
                    $this->validateValue($cargaDatos, 'direccion_vp', $dirección_vicepresidencia);
                    $this->validateValue($cargaDatos, 'departamento', $departamento);
                    $this->validateValue($cargaDatos, 'telefono_movil', $celular);
                    $this->validateValue($cargaDatos, 'extencion', $extencion);
                    $this->validateValue($cargaDatos, 'correo_instutucional', '');
                    $this->validateValue($cargaDatos, 'correo_personal', $correo);
                    $this->validateValue($cargaDatos, 'documento', $numeroDocumento);
                    $this->validateValue($cargaDatos, 'fecha_nacimiento', $fecha_nacimiento);
                    $this->validateValue($cargaDatos, 'codigo_superfisor', $cod_superviso);
                    $this->validateValue($cargaDatos, 'fila', $indiceFila);

                    $cargaDatos->save();
                }
            }
        }

        return redirect('admin/app/cargaDatos/')
                    ->with(['message' => __('Data uploaded successfully'), 
                            'alert' => 'success']);
    }

    public function validateValue($model, $input, $dataImput)
    {
        if($dataImput != '')
        {
            $model->$input = $dataImput;
        }
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
