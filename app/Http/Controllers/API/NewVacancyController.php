<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Vacancy;

class NewVacancyController extends Controller
{


    public function vacancySavePublic(Request $request)
    {
        if ($request->method === 'create') {
          // Vacancy
          $vacancy = new Vacancy();
          $vacancy->name = $request->name;
          $vacancy->slug = $request->name;
          $vacancy->short_description = $request->short_description;
          $vacancy->company = $request->company;
          $vacancy->type = $request->type;
          $vacancy->validity = $request->validity;
          $vacancy->requirements = $request->requirements;
          $vacancy->experience = $request->experience;
          $vacancy->offer = $request->offer;
          $vacancy->active = 1;
          $vacancy->save();

        }elseif ($request->method === 'edit') {
          $vacancy = Vacancy::where('id',$request->id)->first();
          $vacancy->name = $request->name;
          $vacancy->short_description = $request->short_description;
          $vacancy->company = $request->company;
          $vacancy->type = $request->type;
          $vacancy->validity = $request->validity;
          $vacancy->requirements = $request->requirements;
          $vacancy->experience = $request->experience;
          $vacancy->offer = $request->offer;
          $vacancy->active = 1;
          $vacancy->save();
        }
        return response()->json(['status' => true]);
    }

    public function changeStatus($id)
    {
      $valores = explode('&',$id);
      $vacancy = Vacancy::where('id',$valores[0])->first();
      $vacancy->active =$valores[1];
      $vacancy->save();
      return response()->json(['status' => true]);
    }

    public function findVacancies(Request $request)
    {
      $vacancy = Vacancy::where('name','LIKE','%'.$request->data.'%')->orderBy('active', 'desc')->get();

      return response()->json($vacancy);
    }

    public function getWithFilters(Request $request)
    {

      if (count($request->data[0]['tipo']) > 0 && count($request->data[0]['estatus']) == 0) {
        $user_f = Vacancy::whereIn('type',$request->data[0]['tipo'])->orderBy('active', 'desc')->get();
      }elseif (count($request->data[0]['tipo']) != 0 && count($request->data[0]['estatus']) > 0) {
        $user_f =  Vacancy::whereIn('id',$request->data[0]['tipo'])
                          ->whereIn('active',$request->data[0]['estatus'])
                          ->orderBy('active', 'desc')->get();
      }elseif (count($request->data[0]['tipo']) == 0 && count($request->data[0]['estatus']) != 0) {
        $user_f =  Vacancy::whereIn('active',$request->data[0]['estatus'])
                          ->orderBy('active', 'desc')->get();
      }elseif (count($request->data[0]['estatus']) == 0
        && count($request->data[0]['tipo']) == 0) {
          $user_f = Vacancy::orderBy('active', 'desc')->get();
      }

      return response()->json($user_f);
    }

    public function repetidos($array)
    {
      $nom_sim_repet = array_unique($array);    // Eliminamos los elementos repetidos
      $nombre2 = array_unique($array);

      $v_comunes1 = array_diff_assoc($array, $nombre2);
      $v_comunes2 = array_unique($v_comunes1);    // Eliminamos los elementos repetidos
      sort($v_comunes2);    // Orden ascendente en array

      $v_unicos1 = array_diff($array, $v_comunes2);
      sort($v_unicos1);    // Orden ascendente en array

      if (count($v_comunes2) != 0) {
        return $v_comunes2;
      }else {
        return $v_unicos1;
      }

    }

}
