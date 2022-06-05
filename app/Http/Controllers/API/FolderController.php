<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Folder;
use App\Resource;

class FolderController extends Controller
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
        return Folder::orderBy('id','asc')->get();
    }

    public function hasChilden($id = null){
      $json_list = ['d' => 'accordionEx'];
      $empleados = $this->getEmpleados($id);
      // return $empleados;
      foreach ($empleados as $k => $e) {
        if ($this->isLast($e->id)) {
          $json_list[] =	[
            'id' => $e->id,
            'name' => $e->name,
            'order' => ($e->order == 1 ? 0 : $e->order * 2),
          ];
        }else{
          $json_list[] =
          [
            'id' => $e->id,
            'name' => $e->name,
            'order' => ($e->order == 1 ? 0 : $e->order * 2),
            'children' => $this->hasChilden($e->id),
            'd' => 'accordionEx'.$e->id
          ];
        }

      }
      return $json_list;
    }

    public function islast($id)
    {
      $empleados = \DB::table('folders')->where('upperfolder_id', $id)->count();
      if ($empleados==0) {
        return true;
      }
      return false;
    }

    public function getEmpleados($id = null)
    {
      $empleados = \DB::table('folders')
      ->where('upperfolder_id' , $id)
      ->where('active','1')
      ->get();
      return $empleados;
    }


    public function mainFolders(){

      // $json_list = array();
      //
      // $json_list[] = ;
      return response()->json($this->hasChilden());
        // return Folder::whereNull('upperfolder_id')->orderBy('id','asc')->get();
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
            'name' => 'required|string|max:191',
        ]);

        $folder = new Folder([
            'name' => $request['name'],
            'order' => $this->checkIteration($request['upperfolder_id']),
            'upperfolder_id' => $request['upperfolder_id'] == 0 ? null : $request['upperfolder_id']
        ]);
        $folder->save();
        return $folder;
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

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $folder = Resource::where('folder_id',$id)->where('status','1')->get();
        return $folder;
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
        $folder = Folder::findOrFail($id);

        $this->validate($request,[
            'name' => 'required|string|max:191',
        ]);

        $folder->update($request->all());

        return $folder;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $folder = Folder::findOrFail($id);

        $folder->delete();

        return ['message' => 'Folder deleted'];
    }

    public function deleteFolder($id)
    {
      $folder = Folder::where('id',$id)->first();
      $folder->active = 0;
      $folder->save();
      return response()->json(['status' => true]);
    }

    public function changeNameFolder(Request $request)
    {
      $folder = Folder::where('id',$request->id)->first();
      $folder->name = $request->name;
      $folder->save();
      return response()->json(['status' => true]);

    }
}
