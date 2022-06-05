<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Vacancy;

class VacancyController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['activeVacancies', 'vacancyBySlug']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // get all vacancies
        return Vacancy::with('department')->orderBy('active', 'desc')->get();
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

        $slug = str_slug($request->name);

        $vacancy = new Vacancy([
            'name' => $request['name'],
            'short_description' => $request['short_description'],
            'requirements' => $request['requirements'],
            'description' => $request['description'],
            'department_id' => $request['department_id'],
            'slug' => $slug,
            'active' => $request['active'],
        ]);

        $vacancy->save();

        return $vacancy;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $vacancy = Vacancy::with('department')->findOrFail($id);

        return $vacancy;
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
        $vacancy = Vacancy::with('department')
            ->findOrFail($id);

        $this->validate($request,[
            'name' => 'required|string|max:191',
        ]);

        $slug = str_slug($request->name);
        $request->merge(['slug' => $slug]);

        $vacancy->update($request->all());

        return $vacancy;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $vacancy = Vacancy::findOrFail($id);

        $vacancy->delete();

        return ['message' => 'vacancy deleted'];
    }

    public function activeVacancies(){
        return Vacancy::with('department')->orderBy('active', 'desc')->get();
    }

    public function vacancyBySlug($slug){
        $vacancy = Vacancy::with('department')->where('id', $slug)->first();

        return $vacancy;
    }

    public function updatebyslug(Request $request, $slug)
    {
        $vacancy = Vacancy::with('department')->where('slug', $slug)->first();

        $this->validate($request,[
            'name' => 'required|string|max:191',
        ]);

        $slug = str_slug($request->name);
        $request->merge(['slug' => $slug]);

        $vacancy->update($request->all());

        return ['message' => 'Updated the vacancy info'];
    }
}
