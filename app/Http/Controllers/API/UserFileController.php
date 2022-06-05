<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str as Str;
use Illuminate\Support\Facades\File;
use App\UserFile;
use App\User;
use Response;
use Zip;

class UserFileController extends Controller
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
        return UserFile::latest()->get();
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

        $user = User::findOrFail($request->user_id);
        $user_slug = Str::slug($user->name);

        $docName = Str::slug($user->name).'-'.Str::slug($request['name']).'.'.$request->file->getClientOriginalExtension();
        $ext = '.'.$request->file->getClientOriginalExtension();

        //$request->file->move(public_path('files/'), $docName);
        $fileData = File::get($request->file);
        //SOLO PARA PRUEBAS EN LOCAL
        // Storage::disk('local')->put('file'.$docName, $fileData);

        $dir = '/';
        $recursive = false; // Get subdirectories also?
        $contents = collect(Storage::cloud()->listContents($dir, $recursive));
        $dir = $contents->where('type', '=', 'dir')
            ->where('filename', '=', 'staff')
            ->first(); // There could be duplicate directory names!
        if ( ! $dir) {
            return 'Directory does not exist!';
        } else {
          Storage::cloud()->put($dir['path'].'/'.$docName, $fileData);
        }

        $userfile = new UserFile();
        $userfile->name = $request['name'];
        $userfile->type = $ext;
        $userfile->file = $docName;
        $userfile->user_id = $request['user_id'];
        $userfile->save();

        return [
            'name' => $userfile->name,
            'type' => $userfile->type,
            'file' => $userfile->file,
            'user_id' => $userfile->user_id
        ];
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
        $user_file = UserFile::findOrFail($id);

        $filename = $user_file->file;
        //LINEA SOLO  PARA DESARROLO EN LOCAL
         // Storage::disk('local')->delete($filename);

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

        $user_file->delete();

        return ['message' => 'File Deleted'];
    }

    /**
     * download file from repository.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function downloadFile($id)
    {
        $user_file = UserFile::findOrFail($id);

        $filename = $user_file->file;

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
}
