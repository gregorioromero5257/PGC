<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ClientProject;
use App\Contact;
use App\FunderProject;
use App\SupplierProject;
use App\Project;
use App\Company;
use App\LinkedProject;
use App\LocationProject;
use Illuminate\Support\Facades\DB;
use App\Exports\ProjectsGralExport;
use Maatwebsite\Excel\Facades\Excel;
use App\SupervisorUsers;
use App\User;
use App\Resource;
use App\Folder;
use Illuminate\Support\Facades\Storage;


class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    public function fixed()
    {

      try {
        DB::beginTransaction();

        // $project = Project::get();
        // foreach ($project as $key => $value) {
        //
        //   $v_pr = str_replace('"','\"',$value->project_resumen);
        //   $v_pt = str_replace('"','\"',$value->name_es);
        //   $v_ne = str_replace('"','\"',$value->name_en);
        //
        //
        //   $project_resumen = '{ "resumen_es" : "'.($value->project_resumen == null ? '' : $v_pr ).'" , "resumen_en" : "" }';
        //   $project_title = '{ "title_es" : "'.($value->name_es != "" ? $v_pt : '') .'", "title_en" : "'.($value->name_en != "" ? $v_ne : '' ).'" }';
        //
        //   $project_edit = Project::where('id',$value->id)->first();
        //   $project_edit->project_resumen = $project_resumen;
        //   $project_edit->project_title = $project_title;
        //   $project_edit->save();
        // }
        // $valores = explode('&',$id);
        $folder = Folder::get();
        foreach ($folder as $key => $value) {
          $nivel = $this->checkIteration($value->upperfolder_id);

          $folder_update = Folder::where('id',$value->id)->first();
          $folder_update->order = $nivel;
          $folder_update->save();
        }

        DB::commit();
        return response()->json(['status' => true]);
      } catch (\Throwable $e) {
        DB::rollBack();
        dd($e);
      }
    }

    public function checkIteration($value, $i = 1)
    {
      $nivel = 0;
      $nivel += $i;

      if ($value != null) {
        $folder = Folder::where('id',$value)->first();
        if (isset($folder)) {
          $nivel += $this->checkIteration($folder->upperfolder_id, 1);
        }
      }else {
        return $nivel;
      }

      return $nivel;
    }

    public function createJson()
    {
      try {
        DB::beginTransaction();

        $project = Project::get();

        foreach ($project as $key => $value) {

          $project_resumen = $value->project_resumen;
          $project_title = $value->project_title;

          $project_edit = Project::where('id',$value->id)->first();
          $project_edit->project_resumen = json_encode ($project_resumen);
          $project_edit->project_title = json_encode ($project_title);
          $project_edit->save();

        }

        DB::commit();
        return response()->json(['status' => true]);
      } catch (\Throwable $e) {
        DB::rollBack();
        dd($e);
      }
    }

    public function fileSave($id)
    {
      $valores = explode('&',$id);
      $resources = Resource::where('id','>=',$valores[0])->where('id','<',$valores[1])->get();

      foreach ($resources as $key => $value) {
        $resource = Resource::findOrFail($value->id);

        $filename = $resource->file;
        $filename_f = $resource->name;

        $dir = '/';
        $recursive = true; // Get subdirectories also?
        $contents = collect(Storage::cloud()->listContents($dir, $recursive));

        $file = $contents
            ->where('type', '=', 'file')
            ->where('filename', '=', pathinfo($filename, PATHINFO_FILENAME))
            ->where('extension', '=', pathinfo($filename, PATHINFO_EXTENSION))
            ->first(); // there can be duplicate file names!

        $rawData = Storage::cloud()->get($file['path']);

          Storage::disk('pgcf')->put($value->id.$value->name.$value->type, $rawData);
        // if (Storage::disk('pgcf')->missing($value->name.$value->type)) {
        // }
      }

      return response()->json(['status' => true]);
      // code...
    }

    public function download()
    {
      return Excel::download(new ProjectsGralExport(), 'projects.xlsx');
    }



}
