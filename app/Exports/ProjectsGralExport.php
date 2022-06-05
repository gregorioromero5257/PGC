<?php

namespace App\Exports;

use App\Project;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeExport;
use Maatwebsite\Excel\Events\AfterSheet;

class ProjectsGralExport implements FromView
{


  /**
  * @return \Illuminate\Support\Collection
  */
  // public function collection()
  // {
  //     return Project::whereIn('id', $this->projects)->with('clients')->orderBy('contract_start', 'desc')->get();
  // }

  public function view(): View
  {
    $arreglo = [];
    $projects = \App\Project::orderBy('id','DESC')->get();

    foreach ($projects as $key_p => $project) {
      $arreglo [] = [
        'id' => $project->id,
        'url' => "https://pgc.c-230.com/projects/".$project->id,
        'nombre' => $project->name_es,
        'codigo' => $project->code,
        'anio' => substr($project->contract_start,0,4).'-'.substr($project->contract_end,0,4),
        'empresa' => $this->getCompany($project->company_id),
        'cliente' => $this->getClients($project->id),
        'proyectos_viculados' => $this->getProjects($project->id),
        'nombre_contrato_proyecto' => $project->contract_name,
        'project_name_en' => $project->name_en,
        'locations' => $this->getLocations($project->id),
        'rol' => $project->rol, //pendiente
        'privacy' => $this->getPrivacyOptions($project->id),
        'comments_about_privacy' => $project->comments,
        'validity_of_confidentiality' => $project->validity_of_confidentiality,
        'description_es' => $project->description_es,
        'description_en' => $project->description_en,
        'short_description_es' => $project->short_description_es,
        'short_description_en' => $project->short_description_en,
        'type_project' => $this->getTypeP($project->id),
        'analisis_met_imp' => $this->getAnalisis($project->id),
        'tematic_enfo_pb' => $this->getTematic($project->id),
        'interviews' => $project->interviews,
        'databases' => $project->databases,
        'trainings' => $project->trainings,
        'study_cases' => $project->study_cases,
        'team_intern' => $this->getTeamIntern($project->id),
        'team_extern' => $this->getTeamExtern($project->id),
        'financiador' => $this->getFunder($project->id),
        'proveedor' => $this->getSupplier($project->id),
        'consorcio' => $this->getConsortion($project->id),
        'mechanism_contract' => $project->mechanism_contract,
        'tipo_contrato' => $this->getTypeContract($project->contract_id),
        'folio' => $project->folio,
        'documentos_satisfacción' => $this->getSatisfactionDocuments($project->id),
        'contract_creator' => $project->contract_creator,
        'objeto_contractual' => $project->contractual_object,
        'contract_start' => $project->contract_start,
        'contract_end' => $project->contract_end,
        'duration' => $project->duration,

        'date_contract' => $project->date_contract,
        'divisa' => $project->currency,
        'tipo_cambio_fecha_contrato' => $project->type_change,
        'amount_before_iva' => $project->amount_before_iva,
        'iva' => $project->iva,
        'currency_iva' => $project->currency_iva,
        'amount' => $project->amount,
        'amount_mxn' => $project->amount_mxn,
        'amount_usd' => $project->amount_usd,
      ];
    }
    // dd($arreglo);

    return view('exports.projectsgral', compact('arreglo'));
  }


      public function getCompany($id)
      {
        $company = \App\Company::where('id',$id)->select('name')->first();
        return isset($company) ? $company->name : '--';
      }

      public function getClients($id)
      {
        $clients = \App\ClientProject::
        join('stackeholders AS s','s.id','client_project.stackeholder_id')
        ->where('project_id',$id)
        ->select('s.name')
        ->get();
        return count($clients) > 0 ? $clients : '--';
      }

      public function getProjects($id)
      {
        $projects = \App\LinkedProject::where('project_id',$id)->get();
        return count($projects) > 0 ? $projects : '--';
      }

      public function getLocations($id)
      {
        $locations = \App\LocationProject::join('locations AS l','l.id','location_project.location_id')
        ->where('project_id',$id)->select('l.name')->get();
        return count($locations) > 0 ? $locations : '--';
      }

      public function getPrivacyOptions($id)
      {
        $privacy_options = \App\PrivacyOptionProject::join('privacy_options AS po','po.id','privacy_option_project.privacy_option_id')
        ->where('project_id',$id)->select('po.name')->get();
        return count($privacy_options) > 0 ? $privacy_options : '--';
      }

      public function getTypeP($id)
      {
        $type = \App\ProjectType::join('types AS t','t.id','project_type.type_id')->select('t.name')->where('project_id',$id)->get();
        return count($type) > 0 ? $type : '--';
      }

      public function getAnalisis($id)
      {
        $type = \App\MethodologyProject::join('methodologies AS t','t.id','methodology_project.methodology_id')->select('t.name')->where('project_id',$id)->get();
        return count($type) > 0 ? $type : '--';
      }

      public function getTematic($id)
      {
        $type = \App\TopicProject::join('topics AS t','t.id','project_topic.topic_id')->select('t.name')->where('project_id',$id)->get();
        return count($type) > 0 ? $type : '--';
      }

      public function getTeamIntern($id)
      {
        $type = \App\Members::join('users AS u','u.id','member_project.user_id')->select('u.name')->where('member_project.project_id',$id)->get();
        return count($type) > 0 ? $type : '--';
      }

      public function getTeamExtern($id)
      {
        $type = \App\Contact::select('name')->where('project_id',$id)->where('type','4')->get();
        return count($type) > 0 ? $type : '--';
      }

      public function getFunder($id)
      {
        $clients = \App\FunderProject::
        join('stackeholders AS s','s.id','funder_project.stackeholder_id')
        ->where('project_id',$id)
        ->select('s.name')
        ->get();
        return count($clients) > 0 ? $clients : '--';
      }

      public function getSupplier($id)
      {
        $clients = \App\SupplierProject::
        join('stackeholders AS s','s.id','project_supplier.stackeholder_id')
        ->where('project_id',$id)
        ->select('s.name')
        ->get();
        return count($clients) > 0 ? $clients : '--';
      }

      public function getConsortion($id)
      {
        $clients = \App\ConsortionProject::
        join('stackeholders AS s','s.id','project_consortion.stackeholder_id')
        ->where('project_id',$id)
        ->select('s.name')
        ->get();
        return count($clients) > 0 ? $clients : '--';
      }

      public function getTypeContract($id)
      {
        $company = \App\Contract::where('id',$id)->select('name')->first();
        return isset($company) ? $company->name : '--';
      }

      public function getSatisfactionDocuments($id)
      {
        $locations = \App\ProjectSatisfactionDocument::join('satisfaction_documents AS l','l.id','project_satisfaction_document.satisfaction_document_id')
        ->where('project_id',$id)->select('l.name')->get();
        return count($locations) > 0 ? $locations : '--';
      }



}
