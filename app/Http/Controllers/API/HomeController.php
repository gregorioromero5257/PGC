<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Role;
use App\User;
use App\Resource;
use App\Project;
use App\Vacancy;

class HomeController extends Controller
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
        $user = User::count();
        $project = Project::count();
        $resource = Resource::count();
        $vacancy = Vacancy::count();

        return response()->json(['totalUsers' => $user, 'totalProjects' => $project, 'totalResources' => $resource, 'totalVacancies' => $vacancy]);
    }



}
