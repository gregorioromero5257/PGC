<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Mail;

class RequestPublicController extends Controller
{
    //

    public function vacancy()
    {
      return \App\Vacancy::with('department')->where('active','1')
      ->where('company','2')
      ->orderBy('created_at', 'desc')->get();
    }

    public function vacancyI()
    {
      return \App\Vacancy::with('department')->where('active','1')
      ->where('company','1')
      ->orderBy('created_at', 'desc')->get();
    }

    public function getvacancy($id)
    {
      return \App\Vacancy::with('department')->where('id',$id)->where('company','2')->orderBy('active', 'desc')->get();
    }

    public function getvacancyI($id)
    {
      return \App\Vacancy::with('department')->where('id',$id)->where('company','1')->orderBy('active', 'desc')->get();
    }

    public function send($id)
      {
        try {
          $valores = explode('&',$id);
          $mot = str_replace("|", " ", $valores[2]);
          $data = [
            'correo_electronico' => $valores[1],
            'nombre' => $valores[0],
            'motivo' => $mot

          ];

          if ($valores[3] == 'idea') {

            Mail::send('emails.solicitud', $data, function($message) use ($data) {
              // $core= 'romerovelascogregorio@gmail.com';
            // $core= 'jcastillo@c-230.com';
            $core = 'vcuatecontzi@fundacionidea.org.mx';
            $message->to($core, 'Victoria Cuatecontzi Moreno')
                    ->subject('Solicitud de Política contra la trata de personas');
            $message->from('soluciones.digitales.c230@gmail.com','C-230');
          });

          }elseif ($valores[3] == 'c230') {
            Mail::send('emails.solicitud', $data, function($message) use ($data) {
              // $core= 'romerovelascogregorio@gmail.com';
            // $core= 'jcastillo@c-230.com';
            $core = 'vcuatecontzi@c-230.com';
            $message->to($core, 'Victoria Cuatecontzi Moreno')
                    ->subject('Solicitud de Política contra la trata de personas');
            $message->from('soluciones.digitales.c230@gmail.com','C-230');
          });
          }



          return response()->json(['status' => true]);
        } catch (\Exception $e) {
          return false;
        }
      }

      public function getProjects()
      {
        $project_u = \App\Project::where('data_web','LIKE','%'.'"public_state": true'.'%')
        ->where('data_web','LIKE','%'.'"publication_principal": true'.'%')->with('tematics_web')
        ->orderBy('id','DESC')
        ->get()->toArray();

        $project_d = \App\Project::where('data_web','LIKE','%'.'"public_state": true'.'%')
        ->where('data_web','LIKE','%'.'"publication_principal": false'.'%')->with('tematics_web')
        ->orderBy('id','DESC')
        ->get()->toArray();

        $f = array_merge($project_u, $project_d);


        return $f;
      }

      public function getsProject($id)
      {
        $project = \App\Project::where('id',$id)
        ->with('clients')
        ->with('tematics_web')->get();
        return $project;
      }

      public function getProjectsPrincipal()
      {
        $project_u = \App\Project::where('data_web','LIKE','%'.'"public_state": true'.'%')
        ->where('data_web','LIKE','%'.'"publication_principal": true'.'%')->with('tematics_web')
        ->orderBy('id','DESC')
        ->get();

        $f = $project_u;


        return $f;
      }

      public function getProjectsDiferent($id)
      {
        $project_u = \App\Project::where('data_web','LIKE','%'.'"public_state": true'.'%')
        ->whereNotIn('id',[$id])
        ->with('tematics_web')
        ->orderBy('id','DESC')
        ->limit(3)
        ->get();

        $f = $project_u;


        return $f;
      }

      public function fixedDateIn()
      {
        $users = \App\User::get();
        foreach ($users as $key => $value) {

          $date = substr($value->created_at, 0, 10);

          $user = \App\User::where('id',$value->id)->first();
          $user->date_in = $date;
          $user->save();
        }
        return true;
      }
}
