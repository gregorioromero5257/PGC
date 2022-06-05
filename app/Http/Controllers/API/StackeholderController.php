<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Stackeholder;

class StackeholderController extends Controller
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
        // get all stackeholders
        return Stackeholder::with('sh_type')
            ->with('sh_description')
            ->with('contacts')
            ->orderBy('created_at','desc')->get();
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

        $stackeholder = new Stackeholder([
            'name' => $request['name'],
            'sh_description_id' => $request['sh_description_id'],
            'sh_type_id' => $request['sh_type_id'],
            'user_id' => auth('api')->user()->id,
        ]);
        $stackeholder->save();
        return $stackeholder;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $stackeholder = Stackeholder::findOrFail($id);
        return $stackeholder;
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
        $stackeholder = Stackeholder::findOrFail($id);

        $this->validate($request,[
            'name' => 'required|string|max:191',
        ]);

        $stackeholder->update($request->all());

        return $stackeholder;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $stackeholder = Stackeholder::findOrFail($id);

        $stackeholder->delete();

        return ['message' => 'stackeholder deleted'];
    }
}
