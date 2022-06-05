<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\ProjectCode;

class ProjectCodeController extends Controller
{
    public function index()
    {
      return ProjectCode::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $project_code = new ProjectCode();
        $project_code->codigo = $request->codigo;
        $project_code->organizacion = $request->organizacion;
        $project_code->categoria = $request->categoria;
        $project_code->comentarios = $request->comentarios;
        $project_code->save();
        return response()->json(['status' => true]);
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

        $project_code = ProjectCode::where('id',$id)->first();
        $project_code->codigo = $request->codigo;
        $project_code->organizacion = $request->organizacion;
        $project_code->categoria = $request->categoria;
        $project_code->comentarios = $request->comentarios;
        $project_code->save();
        return response()->json(['status' => true]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
      $project_code = ProjectCode::where('id',$id)->delete();

      return response()->json(['status' => true]);
    }
}
