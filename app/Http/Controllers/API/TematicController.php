<?php
namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Tematic;

class TematicController extends Controller
{
    //
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Tematic::orderBy('id', 'ASC')->get();
    }

    public function tematicsIdea()
    {
      return Tematic::where('company_id','1')->orderBy('id', 'ASC')->get();
    }

}
