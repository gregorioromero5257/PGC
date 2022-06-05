<table>
  <tr>
    <th>ID</th>
    <th>URL</th>
    <th>NOMBRE</th>
    <th>CODIGO</th>
    <th>ANIO</th>
    <th>EMPRESA</th>
    <th>CLIENTES</th>
    <th>PROYECTOS VINCULADOS</th>
    <th>NOMBRE DEL CONTRATO</th>
    <th>NOMBRE PROYECTO INGLES</th>
    <th>LOCALIDADES</th>
    <th>ROL</th>
    <th>PRIVACIDAD</th>
    <th>COMENTARIOS SOBRE LA PRIVACIDAD</th>
    <th>VIGENCIA DE CONFIDENCIALIDAD</th>
    <th>DESCRIPCION LARGA ESPANOL</th>
    <th>DESCRIPCION LARGA INGLES</th>
    <th>DESCRIPCION CORTA ESPANOL</th>
    <th>DESCRIPCION CORTA INGLES</th>
    <th>TIPO DE PROYECTO</th>
    <th>ANAISIS, METODOLOGIAS E IMPLEMETACION</th>
    <th>TEMATICAS/ENFOQUE/POBLACION OBJETIVO</th>
    <th>ENTREVISTAS</th>
    <th>BASE DE DATOS CREADAS</th>
    <th>CAPACITACIONES</th>
    <th>CASOS DE ESTUDIO</th>
    <th>EQUIPO INTERNO</th>
    <th>EQUIPO EXTERNO</th>
    <th>FINANCIADORES</th>
    <th>PROVEEDORES</th>
    <th>CONSORCIO</th>
    <th>MECANISMO DE CONTRATACION</th>
    <th>TIPO DE CONTRATO</th>
    <th>FOLIO</th>
    <th>DOCUMENTOS SATISFACCION TIPOS</th>
    <th>NOMBRE DE QUIEN O QUE SE HIZO EL CONTRATO</th>
    <th>OBJETO CONTRACTUAL</th>
    <th>INICIO CONTRATO</th>
    <th>FIN CONTRATO</th>
    <th>DURACION</th>
    <th>FECHA DEL CONTRATO</th>
    <th>DIVISA</th>
    <th>TIPO DE CAMBIO FECHA DE CONTRATO</th>
    <th>MONTO ANTES DE IVA</th>
    <th>IVA</th>
    <th>DIVISA IVA</th>
    <th>MONTO DESPUES DE IVA</th>
    <th>MONTO TOTAL EN MXN</th>
    <th>MONTO TOTAL EN USD</th>
  </tr>
  @foreach ($arreglo as $key => $value)
  <tr>
    <td>{{$value['id']}}</td>
    <td>{{$value['url']}}</td>
    <td>{{$value['nombre']}}</td>
    <td>{{$value['codigo']}}</td>
    <td>{{$value['anio']}}</td>
    <td>{{$value['empresa']}}</td>
    <td>
      @if ($value['cliente'] != '--')
       @foreach ($value['cliente'] as $key_cli => $value_cli)
       {{($key_cli + 1).'.-'.$value_cli['name'].','}}
       @endforeach
      @else
      N/A
      @endif
    </td>
    <td>
      @if ($value['proyectos_viculados'] != '--')
       @foreach ($value['proyectos_viculados'] as $key_pv => $value_pv)
       {{($key_pv + 1).'.-'.$value_pv['code'].','}}
       @endforeach
      @else
      N/A
      @endif
    </td>
    <td>{{$value['nombre_contrato_proyecto']}}</td>
    <td>{{$value['project_name_en']}}</td>
    <td>
      @if ($value['locations'] != '--')
       @foreach ($value['locations'] as $key_loc => $value_loc)
       {{($key_loc + 1).'.-'.$value_loc['name'].','}}
       @endforeach
      @else
      N/A
      @endif
    </td>
    <td>
      @if($value['rol'] == 1)
      Prime
      @elseif($value['rol'] == 2)
      Consorcio
      @endif
    </td>
    <td>
      @if ($value['privacy'] != '--')
       @foreach ($value['privacy'] as $key_po => $value_po)
       {{($key_po + 1).'.-'.$value_po['name'].','}}
       @endforeach
      @else
      N/A
      @endif
    </td>
    <td>{{$value['comments_about_privacy']}}</td>
    <td>{{$value['validity_of_confidentiality']}}</td>
    <td>{{$value['description_es']}}</td>
    <td>{{$value['description_en']}}</td>
    <td>{{$value['short_description_es']}}</td>
    <td>{{$value['short_description_en']}}</td>
    <td>
      @if ($value['type_project'] != '--')
       @foreach ($value['type_project'] as $key_tp => $value_tp)
       {{($key_tp + 1).'.-'.$value_tp['name'].','}}
       @endforeach
      @else
      N/A
      @endif
    </td>
    <td>
      @if ($value['analisis_met_imp'] != '--')
       @foreach ($value['analisis_met_imp'] as $key_ami => $value_ami)
       {{($key_ami + 1).'.-'.$value_ami['name'].','}}
       @endforeach
      @else
      N/A
      @endif
    </td>
    <td>
      @if ($value['tematic_enfo_pb'] != '--')
       @foreach ($value['tematic_enfo_pb'] as $key_tepb => $value_tepb)
       {{($key_tepb + 1).'.-'.$value_tepb['name'].','}}
       @endforeach
      @else
      N/A
      @endif
    </td>
    <td>{{$value['interviews']}}</td>
    <td>{{$value['databases']}}</td>
    <td>{{$value['trainings']}}</td>
    <td>{{$value['study_cases']}}</td>
    <td>
      @if ($value['team_intern'] != '--')
       @foreach ($value['team_intern'] as $key_ti => $value_ti)
       {{($key_ti + 1).'.-'.$value_ti['name'].','}}
       @endforeach
      @else
      N/A
      @endif
    </td>
    <td>
      @if ($value['team_extern'] != '--')
       @foreach ($value['team_extern'] as $key_team_extern => $value_team_extern)
       {{($key_team_extern + 1).'.-'.$value_team_extern['name'].','}}
       @endforeach
      @else
      N/A
      @endif
    </td>
    <td>
      @if ($value['financiador'] != '--')
       @foreach ($value['financiador'] as $key_financiador => $value_financiador)
       {{($key_financiador + 1).'.-'.$value_financiador['name'].','}}
       @endforeach
      @else
      N/A
      @endif
    </td>
    <td>
      @if ($value['proveedor'] != '--')
       @foreach ($value['proveedor'] as $key_proveedor => $value_proveedor)
       {{($key_proveedor + 1).'.-'.$value_proveedor['name'].','}}
       @endforeach
      @else
      N/A
      @endif
    </td>
    <td>
      @if ($value['consorcio'] != '--')
       @foreach ($value['consorcio'] as $keyconsorcio => $valueconsorcio)
       {{($keyconsorcio + 1).'.-'.$valueconsorcio['name'].','}}
       @endforeach
      @else
      N/A
      @endif
    </td>
    <td>{{$value['mechanism_contract']}}</td>
    <td>
      @if ($value['tipo_contrato'] != '--')
       {{ $value['tipo_contrato'] }}
      @else
      N/A
      @endif
    </td>
    <td>{{$value['folio']}}</td>
    <td>
      @if ($value['documentos_satisfacción'] != '--')
       @foreach ($value['documentos_satisfacción'] as $key_documentos_satisfacción => $value_documentos_satisfacción)
       {{($key_documentos_satisfacción + 1).'.-'.$value_documentos_satisfacción['name'].','}}
       @endforeach
      @else
      N/A
      @endif
    </td>
    <td>{{$value['contract_creator']}}</td>
    <td>{{$value['objeto_contractual']}}</td>
    <td>{{$value['contract_start']}}</td>
    <td>{{$value['contract_end']}}</td>
    <td>{{$value['duration']}}</td>
    <td>{{$value['date_contract']}}</td>
    <td>{{$value['divisa']['name']}}</td>
    <td>{{$value['tipo_cambio_fecha_contrato']}}</td>
    <td>{{$value['amount_before_iva']}}</td>
    <td>{{$value['iva']}}</td>
    <td>{{$value['currency_iva']}}</td>
    <td>{{$value['amount']}}</td>
    <td>{{$value['amount_mxn']}}</td>
    <td>{{$value['amount_usd']}}</td>
  </tr>
  @endforeach
</table>
