<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\PrivacyOption;

class PrivacyOptionController extends Controller
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
        return PrivacyOption::orderBy('created_at', 'asc')->get();
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
        
        $privacy_option = new PrivacyOption([
            'name' => $request['name']
        ]);
        $privacy_option->save();
        return $privacy_option;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $privacy_option = PrivacyOption::findOrFail($id);
        return $privacy_option;
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
        $privacy_option = PrivacyOption::findOrFail($id);

        $this->validate($request,[
            'name' => 'required|string|max:191',
        ]);

        $privacy_option->update($request->all());

        return $privacy_option;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $privacy_option = PrivacyOption::findOrFail($id);

        $privacy_option->delete();

        return ['message' => 'Privacy option deleted'];
    }
}
