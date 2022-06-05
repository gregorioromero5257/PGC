<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str as Str;
use Illuminate\Support\Facades\File;
use App\ProjectFile;
use App\Project;
use Response;
use Zip;

class ProjectFileController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return ProjectFile::latest()->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'name' => 'required|string',
            'file' => 'required'
        ]);

        $project = Project::findOrFail($request->project_id);
        $project_slug = Str::slug($project->code);

        $docName = Str::slug($project->code).'-'.Str::slug($request['name']).'.'.$request->file->getClientOriginalExtension();
        $ext = '.'.$request->file->getClientOriginalExtension();

        $fileData = File::get($request->file);

        $dir = '/';
        $recursive = false; // Get subdirectories also?
        $contents = collect(Storage::cloud()->listContents($dir, $recursive));
        $dir = $contents->where('type', '=', 'dir')
            ->where('filename', '=', 'projects')
            ->first(); // There could be duplicate directory names!
        if ( ! $dir) {
            return 'Directory does not exist!';
        } else {
            Storage::cloud()->put($dir['path'].'/'.$docName, $fileData);
        }

        return ProjectFile::create([
            'name' => $request['name'],
            'type' => $ext,
            'file' => $docName,
            'project_id' => $request['project_id']
        ]);
    }

    public function dropbox(Request $request)
    {
      $project_file = new ProjectFile();
      $project_file->project_id = $request->project_id;
      $project_file->name = $request->name;
      $project_file->type = $request->type;
      $project_file->file = $request->file;
      $project_file->save();

      return response()->json(['status' => true]);
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
        $project_file = ProjectFile::findOrFail($id);
        if ($project_file->type != 'Dropbox') {
          $filename = $project_file->file;


          $dir = '/';
          $recursive = true; // Get subdirectories also?
          $contents = collect(Storage::cloud()->listContents($dir, $recursive));

          $file = $contents
              ->where('type', '=', 'file')
              ->where('filename', '=', pathinfo($filename, PATHINFO_FILENAME))
              ->where('extension', '=', pathinfo($filename, PATHINFO_EXTENSION))
              ->first(); // there can be duplicate file names!
          //return $file; // array with file info

          Storage::cloud()->delete($file['path']);
        }

        $project_file->delete();

        return ['message' => 'File Deleted'];
    }

    public function downloadFile($id)
    {
        $project_file = ProjectFile::findOrFail($id);

        $filename = $project_file->file;

        $dir = '/';
        $recursive = true; // Get subdirectories also?
        $contents = collect(Storage::cloud()->listContents($dir, $recursive));

        $file = $contents
            ->where('type', '=', 'file')
            ->where('filename', '=', pathinfo($filename, PATHINFO_FILENAME))
            ->where('extension', '=', pathinfo($filename, PATHINFO_EXTENSION))
            ->first(); // there can be duplicate file names!
        //return $file; // array with file info
        $rawData = Storage::cloud()->get($file['path']);
        return response($rawData, 200)
            ->header('ContentType', $file['mimetype'])
            ->header('Content-Disposition', "attachment; filename='$filename'");

        //return Response::download(public_path('files/projects/').$project_file->file, $project_file->file);
    }

    public function downloadFilePublication($id)
    {
        $project_file = \App\Publication::findOrFail($id);

        $filename = $project_file->file;

        // Se obtiene el archivo del  serve
        $archivo = Storage::disk('local')->get('Archivos/' . $filename);
        // Se coloca el archivo en una ruta local
        Storage::disk('descarga')->put($filename, $archivo);
        //--Devuelve la respuesta y descarga el archivo--//
        return response()->download(storage_path().'/app/descargas/'.$filename);

        // $dir = '/';
        // $recursive = true; // Get subdirectories also?
        // $contents = collect(Storage::disk('local')->listContents($dir, $recursive));
        //
        // $file = $contents
        //     ->where('type', '=', 'file')
        //     ->where('filename', '=', pathinfo($filename, PATHINFO_FILENAME))
        //     ->where('extension', '=', pathinfo($filename, PATHINFO_EXTENSION))
        //     ->first(); // there can be duplicate file names!
        // //return $file; // array with file info
        // $rawData = Storage::disk('local')->get($file['path']);
        // return response($rawData, 200)
        //     ->header('ContentType', $file['mimetype'])
        //     ->header('Content-Disposition', "attachment; filename='$filename'");

        //return Response::download(public_path('files/projects/').$project_file->file, $project_file->file);
    }

    public function clearFilePublication($id)
    {
      $project_file = \App\Publication::findOrFail($id);

      $filename = $project_file->file;
      //elimina de la ruta local el archivo descargado
      Storage::disk('descarga')->delete($filename);
    }
}
