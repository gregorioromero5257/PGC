<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\SatisfactionDocument;

class SatisfactionDocumentController extends Controller
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
        return SatisfactionDocument::orderBy('created_at', 'desc')->get();
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
        
        $satisfaction_document = new SatisfactionDocument([
            'name' => $request['name']
        ]);
        $satisfaction_document->save();
        return $satisfaction_document;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $satisfaction_document = SatisfactionDocument::findOrFail($id);
        return $satisfaction_document;
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
        $satisfaction_document = SatisfactionDocument::findOrFail($id);

        $this->validate($request,[
            'name' => 'required|string|max:191',
        ]);

        $satisfaction_document->update($request->all());

        return $satisfaction_document;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $satisfaction_document = SatisfactionDocument::findOrFail($id);

        $satisfaction_document->delete();

        return ['message' => 'Satisfaction document deleted'];
    }
}
