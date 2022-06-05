<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Vimeo\Laravel\VimeoManager;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str as Str;
use Illuminate\Support\Facades\File;
use App\Resource;
use App\Folder;
use Response;
use Zip;

class ResourceController extends Controller
{
  protected $vimeo;
  /**
  * Create a new controller instance.
  *
  * @return void
  */
  public function __construct(VimeoManager $vimeo)
  // public function __construct()
  {
    $this->middleware('auth:api');
    $this->vimeo = $vimeo;
  }

  /**
  * Display a listing of the resource.
  *
  * @return \Illuminate\Http\Response
  */
  public function index()
  {
    return Resource::latest()->get();
  }

  /**
  * Store a newly created resource in storage.
  *
  * @param  \Illuminate\Http\Request  $request
  * @return \Illuminate\Http\Response
  */
  public function store(Request $request)
  {
    // $this->validate($request,[
    //     'name' => 'required|string',
    //     'file' => 'required'
    // ]);
    $resource_count = Resource::get()->count();
    $uri = '';
    if ($request['video'] == 'true') {

      $uri = $this->vimeo->upload($request->file,
      [
        'name' => $request['name'].$resource_count,
        'description' => $request['name']
      ]);
    }


    // $resource = new Resource();
    // $resource->name = $request['name'];
    // $resource->type = '.video';
    // $resource->file = $uri;
    // $resource->folder_id = $request['folder_id'];
    // $resource->save();

    // Storage::disk('googletwo')->put($resource->id.$resource->name.$resource->type, $fileData);


    // $folder = Resource::where('folder_id',$request['folder_id'])->get();

    // return response()->json($folder);


    $folder = Folder::findOrFail($request->folder_id);
    $folder_slug = Str::slug($folder->name);

    $docName = Str::slug($folder->name)
    .'-'.Str::slug($request['name']).$resource_count
    .'.'.$request->file->getClientOriginalExtension();
    $ext = '.'.$request->file->getClientOriginalExtension();

    $fileData = File::get($request->file);

    $dir = '/';
    $recursive = false; // Get subdirectories also?
    $contents = collect(Storage::cloud()->listContents($dir, $recursive));
    $dir = $contents->where('type', '=', 'dir')
    // ->where('filename', '=', 'Files')
    ->where('filename', '=', 'resources')
    ->first(); // There could be duplicate directory names!
    if ( ! $dir) {
      return 'Directory does not exist!';
    } else {
      Storage::cloud()->put($dir['path'].'/'.$docName, $fileData);
    }

    $resource = new Resource();
    $resource->name = $request['name'];
    $resource->type = $ext;
    $resource->file = $docName;
    $resource->folder_id = $request['folder_id'];
    $resource->uri = $uri;
    $resource->save();

    // Storage::disk('pgcf')->put($resource->id.$resource->name.$resource->type, $fileData);
    // $r = Storage::disk('googletwo')->put($resource->id.$resource->name.$resource->type, $fileData);

    $folder = Resource::where('folder_id',$request['folder_id'])->get();


    return response()->json($folder);



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
    $resource = Resource::findOrFail($id);

    $filename = $resource->file;

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

    $resource->delete();

    return ['message' => 'File Deleted'];
  }

  public function saveLocaFile($id)
  {
    $resource = Resource::findOrFail($id);
    if ($resource->uri != '') {

      return response()->json(['status' => true]);
    }else {
      $checkin = Storage::disk('pgcf')->exists($resource->id.$resource->name.$resource->type);
      if ($checkin == true) {
        return response()->json(['status' => true]);
      }else {
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

        Storage::disk('pgcf')->put($resource->id.$resource->name.$resource->type, $rawData);
        // return response()->json(['status' => true]);
      }
    }
    return response()->json(['status' => true]);
  }

  public function downloadFile($id)
  {
    $resource = Resource::findOrFail($id);

    $filename = $resource->file;

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


  }

  public function deleteRFile($id)
  {
    $resource = Resource::where('id',$id)->first();

    $resource->status = 0;
    $resource->save();

    $folder = Resource::where('folder_id',$resource->folder_id)->where('status','1')->get();

    return response()->json($folder);
  }

  public function findRF(Request $request)
  {
    $resource = Resource::where('name','LIKE','%'.$request->des.'%')->get()->toArray();
    $folder = Folder::where('name','LIKE','%'.$request->des.'%')->get()->toArray();
    $result = array_merge($resource,$folder);
    return response()->json($result);
  }

  public function getTrackFolder($id)
  {
    $final = [];
    $folder = Folder::where('id',$id)->first();

    $result = $this->checkIteration($folder->upperfolder_id);
    $final = explode(',',$result);
    return response()->json(array_filter($final));
  }

  public function checkIteration($value)
  {
    $nivel = '';
    $nivel .= $value.',';

    if ($value != null) {
      $folder = Folder::where('id',$value)->first();
      if (isset($folder)) {
        $nivel .= $this->checkIteration($folder->upperfolder_id).',';
      }
    }else {
      return $nivel;
    }

    return $nivel;
  }


}
