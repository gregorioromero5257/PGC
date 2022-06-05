<table style="height: 191px; width: 657px; margin-left: auto; margin-right: auto;">
<tbody>
<tr style="height: 22px;">
<td style="width: 50px; height: 30px; text-align: left; vertical-align: middle;background-color : #FFA000;" rowspan="2">
&nbsp;Proyecto<br>
&nbsp;Código de proyecto<br>
&nbsp;Cliente<br>
&nbsp;Duración&nbsp;&nbsp;</td>
<td style="width: 50px; height: 30px; text-align: center;background-color : #FFA000;" rowspan="2">&nbsp; Descripción</td>
<td style="width: 45px; height: 30px; text-align: center;background-color : #FFA000;" colspan="3">&nbsp; Experiencia relevante para los servicios <br> requeridos</td>
</tr>
<tr style="height: 95px;">
<td style="width: 15px; height: 30px; text-align: center;background-color : #FFA000;">A&nbsp;</td>
<td style="width: 15px; height: 30px; text-align: center;background-color : #FFA000;">B</td>
<td style="width: 15px; height: 30px; text-align: center;background-color : #FFA000;">C</td>
</tr>
@foreach ($projects as $key => $value)
<tr>
  <td style="height: 150px;">
    <br>
    {{$value->name_es}}<br>
    {{$value->code}}<br>
    <br>
    Clientes: @foreach ($value->clients as $key_c => $value_c)
    {{$value_c->name}}<br>
    @endforeach
    <br>
    Duración : {{substr($value->contract_start, 0, 4) .'-'. substr($value->contract_end, 0, 4)}}
  </td>
  <td style="text-align: justify;">
    {{$value->short_description_es}}
  </td>
</tr>
@endforeach
</tbody>
</table>
