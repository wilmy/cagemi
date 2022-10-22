<?php

return [
    'accepted'             => ':Attribute debe ser aceptado.',
    'accepted_if'          => ':Attribute debe ser aceptado cuando :other sea :value.',
    'active_url'           => ':Attribute no es una URL válida.',
    'after'                => ':Attribute debe ser una fecha posterior a :date.',
    'after_or_equal'       => ':Attribute debe ser una fecha posterior o igual a :date.',
    'alpha'                => ':Attribute sólo debe contener letras.',
    'alpha_dash'           => ':Attribute sólo debe contener letras, números, guiones y guiones bajos.',
    'alpha_num'            => ':Attribute sólo debe contener letras y números.',
    'array'                => ':Attribute debe ser un conjunto.',
    'before'               => ':Attribute debe ser una fecha anterior a :date.',
    'before_or_equal'      => ':Attribute debe ser una fecha anterior o igual a :date.',
    'between'              => [
        'array'   => ':Attribute tiene que tener entre :min - :max elementos.',
        'file'    => ':Attribute debe pesar entre :min - :max kilobytes.',
        'numeric' => ':Attribute tiene que estar entre :min - :max.',
        'string'  => ':Attribute tiene que tener entre :min - :max caracteres.',
    ],
    'boolean'              => 'El campo :attribute debe tener un valor verdadero o falso.',
    'confirmed'            => 'La confirmación de :attribute no coincide.',
    'current_password'     => 'La contraseña es incorrecta.',
    'date'                 => ':Attribute no es una fecha válida.',
    'date_equals'          => ':Attribute debe ser una fecha igual a :date.',
    'date_format'          => ':Attribute no corresponde al formato :format.',
    'declined'             => ':Attribute debe ser rechazado.',
    'declined_if'          => ':Attribute debe ser rechazado cuando :other sea :value.',
    'different'            => ':Attribute y :other deben ser diferentes.',
    'digits'               => ':Attribute debe tener :digits dígitos.',
    'digits_between'       => ':Attribute debe tener entre :min y :max dígitos.',
    'dimensions'           => 'Las dimensiones de la imagen :attribute no son válidas.',
    'distinct'             => 'El campo :attribute contiene un valor duplicado.',
    'doesnt_end_with'      => 'El campo :attribute no puede finalizar con uno de los siguientes: :values.',
    'doesnt_start_with'    => 'El campo :attribute no puede comenzar con uno de los siguientes: :values.',
    'email'                => ':Attribute no es un correo válido.',
    'ends_with'            => 'El campo :attribute debe finalizar con uno de los siguientes valores: :values',
    'enum'                 => 'El :attribute seleccionado es inválido.',
    'exists'               => 'El :attribute seleccionado es inválido.',
    'file'                 => 'El campo :attribute debe ser un archivo.',
    'filled'               => 'El campo :attribute es obligatorio.',
    'gt'                   => [
        'array'   => 'El campo :attribute debe tener más de :value elementos.',
        'file'    => 'El campo :attribute debe tener más de :value kilobytes.',
        'numeric' => 'El campo :attribute debe ser mayor que :value.',
        'string'  => 'El campo :attribute debe tener más de :value caracteres.',
    ],
    'gte'                  => [
        'array'   => 'El campo :attribute debe tener como mínimo :value elementos.',
        'file'    => 'El campo :attribute debe tener como mínimo :value kilobytes.',
        'numeric' => 'El campo :attribute debe ser como mínimo :value.',
        'string'  => 'El campo :attribute debe tener como mínimo :value caracteres.',
    ],
    'image'                => ':Attribute debe ser una imagen.',
    'in'                   => ':Attribute es inválido.',
    'in_array'             => 'El campo :attribute no existe en :other.',
    'integer'              => ':Attribute debe ser un número entero.',
    'ip'                   => ':Attribute debe ser una dirección IP válida.',
    'ipv4'                 => ':Attribute debe ser una dirección IPv4 válida.',
    'ipv6'                 => ':Attribute debe ser una dirección IPv6 válida.',
    'json'                 => 'El campo :attribute debe ser una cadena JSON válida.',
    'lt'                   => [
        'array'   => 'El campo :attribute debe tener menos de :value elementos.',
        'file'    => 'El campo :attribute debe tener menos de :value kilobytes.',
        'numeric' => 'El campo :attribute debe ser menor que :value.',
        'string'  => 'El campo :attribute debe tener menos de :value caracteres.',
    ],
    'lte'                  => [
        'array'   => 'El campo :attribute debe tener como máximo :value elementos.',
        'file'    => 'El campo :attribute debe tener como máximo :value kilobytes.',
        'numeric' => 'El campo :attribute debe ser como máximo :value.',
        'string'  => 'El campo :attribute debe tener como máximo :value caracteres.',
    ],
    'mac_address'          => 'El campo :attribute debe ser una dirección MAC válida.',
    'max'                  => [
        'array'   => ':Attribute no debe tener más de :max elementos.',
        'file'    => ':Attribute no debe ser mayor que :max kilobytes.',
        'numeric' => ':Attribute no debe ser mayor que :max.',
        'string'  => ':Attribute no debe ser mayor que :max caracteres.',
    ],
    'max_digits'           => 'El campo :attribute no debe tener más de :max dígitos.',
    'mimes'                => ':Attribute debe ser un archivo con formato: :values.',
    'mimetypes'            => ':Attribute debe ser un archivo con formato: :values.',
    'min'                  => [
        'array'   => ':Attribute debe tener al menos :min elementos.',
        'file'    => 'El tamaño de :attribute debe ser de al menos :min kilobytes.',
        'numeric' => 'El tamaño de :attribute debe ser de al menos :min.',
        'string'  => ':Attribute debe contener al menos :min caracteres.',
    ],
    'min_digits'           => 'El campo :attribute debe tener al menos :min dígitos.',
    'multiple_of'          => 'El campo :attribute debe ser múltiplo de :value',
    'not_in'               => ':Attribute es inválido.',
    'not_regex'            => 'El formato del campo :attribute no es válido.',
    'numeric'              => ':Attribute debe ser numérico.',
    'password'             => [
        'letters'       => 'La :attribute debe contener al menos una letra.',
        'mixed'         => 'La :attribute debe contener al menos una letra mayúscula y una minúscula.',
        'numbers'       => 'La :attribute debe contener al menos un número.',
        'symbols'       => 'La :attribute debe contener al menos un símbolo.',
        'uncompromised' => 'La :attribute proporcionada se ha visto comprometida en una filtración de datos (data leak). Elija una :attribute diferente.',
    ],
    'present'              => 'El campo :attribute debe estar presente.',
    'prohibited'           => 'El campo :attribute está prohibido.',
    'prohibited_if'        => 'El campo :attribute está prohibido cuando :other es :value.',
    'prohibited_unless'    => 'El campo :attribute está prohibido a menos que :other sea :values.',
    'prohibits'            => 'El campo :attribute prohibe que :other esté presente.',
    'regex'                => 'El formato de :attribute es inválido.',
    'required'             => 'El campo :attribute es obligatorio.',
    'required_array_keys'  => 'El campo :attribute debe contener entradas para: :values.',
    'required_if'          => 'El campo :attribute es obligatorio cuando :other es :value.',
    'required_if_accepted' => 'El campo :attribute es obligatorio si :other es aceptado.',
    'required_unless'      => 'El campo :attribute es obligatorio a menos que :other esté en :values.',
    'required_with'        => 'El campo :attribute es obligatorio cuando :values está presente.',
    'required_with_all'    => 'El campo :attribute es obligatorio cuando :values están presentes.',
    'required_without'     => 'El campo :attribute es obligatorio cuando :values no está presente.',
    'required_without_all' => 'El campo :attribute es obligatorio cuando ninguno de :values está presente.',
    'same'                 => ':Attribute y :other deben coincidir.',
    'size'                 => [
        'array'   => ':Attribute debe contener :size elementos.',
        'file'    => 'El tamaño de :attribute debe ser :size kilobytes.',
        'numeric' => 'El tamaño de :attribute debe ser :size.',
        'string'  => ':Attribute debe contener :size caracteres.',
    ],
    'starts_with'          => 'El campo :attribute debe comenzar con uno de los siguientes valores: :values',
    'string'               => 'El campo :attribute debe ser una cadena de caracteres.',
    'timezone'             => ':Attribute debe ser una zona horaria válida.',
    'unique'               => 'El campo :attribute ya ha sido registrado.',
    'uploaded'             => 'Subir :attribute ha fallado.',
    'url'                  => ':Attribute debe ser una URL válida.',
    'uuid'                 => 'El campo :attribute debe ser un UUID válido.',
    'attributes'           => [
        'address'                  => 'dirección',
        'age'                      => 'edad',
        'amount'                   => 'cantidad',
        'area'                     => 'área',
        'available'                => 'disponible',
        'birthday'                 => 'cumpleaños',
        'body'                     => 'contenido',
        'city'                     => 'ciudad',
        'content'                  => 'contenido',
        'country'                  => 'país',
        'created_at'               => 'creado el',
        'creator'                  => 'creador',
        'current_password'         => 'contraseña actual',
        'date'                     => 'fecha',
        'date_of_birth'            => 'fecha de nacimiento',
        'day'                      => 'día',
        'deleted_at'               => 'eliminado el',
        'description'              => 'descripción',
        'district'                 => 'distrito',
        'duration'                 => 'duración',
        'email'                    => 'correo electrónico',
        'excerpt'                  => 'extracto',
        'filter'                   => 'filtro',
        'first_name'               => 'nombre',
        'gender'                   => 'género',
        'group'                    => 'grupo',
        'hour'                     => 'hora',
        'image'                    => 'imagen',
        'last_name'                => 'apellido',
        'lesson'                   => 'lección',
        'line_address_1'           => 'dirección de la línea 1',
        'line_address_2'           => 'dirección de la línea 2',
        'message'                  => 'mensaje',
        'middle_name'              => 'segundo nombre',
        'minute'                   => 'minuto',
        'mobile'                   => 'móvil',
        'month'                    => 'mes',
        'name'                     => 'nombre',
        'national_code'            => 'código nacional',
        'number'                   => 'número',
        'password'                 => 'contraseña',
        'password_confirmation'    => 'confirmación de la contraseña',
        'phone'                    => 'teléfono',
        'photo'                    => 'foto',
        'postal_code'              => 'código postal',
        'price'                    => 'precio',
        'province'                 => 'provincia',
        'recaptcha_response_field' => 'respuesta del recaptcha',
        'remember'                 => 'recordar',
        'restored_at'              => 'restaurado el',
        'result_text_under_image'  => 'texto bajo la imagen',
        'role'                     => 'rol',
        'second'                   => 'segundo',
        'sex'                      => 'sexo',
        'short_text'               => 'texto corto',
        'size'                     => 'tamaño',
        'state'                    => 'estado',
        'street'                   => 'calle',
        'student'                  => 'estudiante',
        'subject'                  => 'asunto',
        'teacher'                  => 'profesor',
        'terms'                    => 'términos',
        'test_description'         => 'descripción de prueba',
        'test_locale'              => 'prueba local',
        'test_name'                => 'nombre de prueba',
        'text'                     => 'texto',
        'time'                     => 'hora',
        'title'                    => 'título',
        'updated_at'               => 'actualizado el',
        'username'                 => 'usuario',
        'year'                     => 'año',
    ],
];
