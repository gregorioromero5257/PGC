<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Project;
use App\TematicWebProject;
use App\Tematic;

class IdeaWebController extends Controller
{
    public function updateWeb(Request $request)
    {
      // return response()->json($request['files']);
      $dta_web = [
          'description_es'=> $request->description_es,
          'description_en'=> '',
          'subtitles' => [ [
            'name_seccion_es' => '',
            'name_seccion_en' => '',
            'subtitle_es'=>'',
            'subtitle_en' => '',
          ]
          ],
          'public_state' => true,
          'publication_principal' => false,
          'publication_date' => $request->publication_date,
          'type' => $request->public,
          'files' => true,
          'files_n' => $request['files']

        ];

        $file_web = [
          'name' => $request->name,
          'path' => $request->path
        ];
        $project_resumen = '{ "resumen_es" : "" , "resumen_en" : "" }';
        $project_title = '{ "title_es" : "'.$request->title_es.'", "title_en" : "" }';

      $project = Project::where('id',$request->project_id)->first();
      $project->data_web = json_encode($dta_web);
      $project->file_web_single = json_encode($file_web);
      $project->project_resumen = $project_resumen;
      $project->project_title = $project_title;
      $project->save();

      foreach ($request->tags as $tags) {
        $tematics_web_search = Tematic::where('name',$tags)->where('company_id','1')->first();
          $tematics_web_save = new TematicWebProject();
          $tematics_web_save->tematic_id =  $tematics_web_search->id;
          $tematics_web_save->project_id = $project->id;
          $tematics_web_save->name = $tags;
          $tematics_web_save->save();
      }

      return response()->json(['status' => true]);

    }
}
