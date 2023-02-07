<?php

namespace App\Exports;

use App\Models\EmpleadosXPosicion;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;

class EmpleadosExport implements FromCollection, WithMapping, WithHeadings
{
    
    
    protected $empleados;

    public function __construct($empleados)
    {
        $this->empleados =  $empleados;
           
    }
    
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        
        return $this->empleados;
    }

    public function map($empleados): array
    {
        return [
            base64_encode($empleados->cod_empleado),
            $empleados->cod_empleado_empresa,
            $empleados->nombres,
            $empleados->apellidos,
            $empleados->documento,
        ];
    }
    public function headings(): array
    {
        return [
            'ID',
            'Codigo Empleado',
            'Nombres',
            'Apellidos',
            'Documento'
        ];
    }
}