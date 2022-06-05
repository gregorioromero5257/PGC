<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Project;
use App\Publication;
use App\ClientProject;
use App\Contact;
use App\FunderProject;
use App\SupplierProject;
use App\ConsortionProject;
use App\Stackeholder;
use App\Type;
use App\Methodology;
use App\Topic;
use App\User;
use App\Location;
use App\DocumentTypesNamesProject;
use App\Exports\ProjectsExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\TematicWebProject;

class ProjectController extends Controller
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
    // get all projects in database
    return Project::orderBy('contract_start', 'desc')->get();
  }

  public function getProjectIdea()
  {
    return Project::where('company_id','1')->where('file_web_single','LIKE','{"name": "", "path": ""}')->orderBy('contract_start', 'desc')->get();
  }

  public function findProject(Request $request)
  {
    $projects = DB::table('projects')->where('code', 'LIKE', '%'.$request->data.'%')
    ->orWhere('name_es', 'LIKE', '%'.$request->data.'%')
    ->orderBy('contract_start', 'desc')->get();

    $result = [];
    foreach ($projects as $key => $project) {
      $clients = ClientProject::join('stackeholders AS s','s.id','client_project.stackeholder_id')
      ->where('project_id',$project->id)
      ->select('s.name')
      ->get();

      $client_text= '';
      foreach ($clients as $key_c => $client) {
        $client_text .= $client->name.', ';
      }
      $year_text = '';
      if ($project->contract_start != '') {
        $year_text .= substr($project->contract_start, 0, 4);
      }
      if ($project->contract_end != '') {
        $year_text .= ' - '.substr($project->contract_end, 0, 4);
      }
      if ($project->contract_start == null && $project->contract_end == null) {
        $year_text = '-';
      }
      $result [] = [$project, 'clients' => $client_text, 'year' => $year_text];
    }

    return response()->json($result);
  }

  public function getProjects()
  {
    // get all projects in database with clients
    $projects = Project::orderBy('contract_start', 'DESC')->get();
    $result = [];
    foreach ($projects as $key => $project) {
      $clients = ClientProject::join('stackeholders AS s','s.id','client_project.stackeholder_id')
      ->where('project_id',$project->id)
      ->select('s.name')
      ->get();

      $client_text= '';
      foreach ($clients as $key_c => $client) {
        $client_text .= $client->name.', ';
      }
      $year_text = '';
      if ($project->contract_start != '') {
        $year_text .= substr($project->contract_start, 0, 4);
      }
      if ($project->contract_end != '') {
        $year_text .= ' - '.substr($project->contract_end, 0, 4);
      }
      if ($project->contract_start == null && $project->contract_end == null) {
        $year_text = '-';
      }
      $result [] = [$project, 'clients' => $client_text, 'year' => $year_text];
    }

    return response()->json($result);
  }

  public function deleteProject($id)
  {
    $projects = Project::where('id', $id)->delete();
    return response()->json(['status' => true]);
  }

  public function findFilters(Request $request)
  {
    if ($request->type === 'clients') {
      $data = Stackeholder::where('name','LIKE','%'.$request->data.'%')->get();
    }
    if ($request->type === 'tipos') {
      $data = Type::where('name','LIKE','%'.$request->data.'%')->get();
    }
    if ($request->type === 'metodologias') {
      $data = Methodology::where('name','LIKE','%'.$request->data.'%')->get();
    }
    if ($request->type === 'tematicas') {
      $data = Topic::where('name','LIKE','%'.$request->data.'%')->get();
    }
    if ($request->type === 'colaboradores') {
      $data = User::where('name','LIKE','%'.$request->data.'%')->get();
    }
    if ($request->type === 'ubicaciones') {
      $data = Location::where('name','LIKE','%'.$request->data.'%')->get();
    }
    return response()->json($data);
  }

  public function getWithFilters(Request $request)
  {
    $keys = array_keys($request->data[0]);
    $a_org = [];
    $a_cli = [];
    $a_typ = [];
    $a_meth = [];
    $a_top = [];
    $a_mem = [];
    $a_loc = [];
    $a_tim = [];
    if ($request->data[0]['start'] != '' && $request->data[0]['end'] != '') {
      $projects_tim = Project::whereBetween('contract_start',[$request->data[0]['start'], $request->data[0]['end']])
      ->select('id')->get();
      foreach ($projects_tim as $key_t => $value_tim) {
        $a_tim [] = $value_tim->id;
      }
    }


    if (count($request->data[0]['organizacion']) > 0) {
      $projects_org = Project::whereIn('company_id',$request->data[0]['organizacion'])->select('id')->get();
      foreach ($projects_org as $key_o => $value_org) {
        $a_org [] = $value_org->id;
      }
    }

    if (count($request->data[0]['clientes']) > 0) {
      $projects_cli = ClientProject::whereIn('stackeholder_id',$request->data[0]['clientes'])->select('project_id')->get();
      foreach ($projects_cli as $key_c => $value_cli) {
        $a_cli [] = $value_cli->project_id;
      }
    }

    if (count($request->data[0]['tipos']) > 0) {
      $projects_typ = \App\ProjectType::whereIn('type_id',$request->data[0]['tipos'])->select('project_id')->get();
      foreach ($projects_typ as $key_ty => $value_typ) {
        $a_typ [] = $value_typ->project_id;
      }
    }

    if (count($request->data[0]['metodologias']) > 0) {
      $projects_meth = \App\MethodologyProject::whereIn('methodology_id',$request->data[0]['metodologias'])->select('project_id')->get();
      foreach ($projects_meth as $key_me => $value_meth) {
        $a_meth [] = $value_meth->project_id;
      }
    }

    if (count($request->data[0]['tematicas']) > 0) {
      $projects_top = \App\TopicProject::whereIn('topic_id',$request->data[0]['tematicas'])->select('project_id')->get();
      foreach ($projects_top as $key_to => $value_top) {
        $a_top [] = $value_top->project_id;
      }
    }

    if (count($request->data[0]['colaboradores']) > 0) {
      $projects_mem = \App\Members::whereIn('user_id',$request->data[0]['colaboradores'])->select('project_id')->get();
      foreach ($projects_mem as $key_mem => $value_mem) {
        $a_mem [] = $value_mem->project_id;
      }
    }

    if (count($request->data[0]['ubicaciones']) > 0) {
      $projects_loc = \App\LocationProject::whereIn('location_id',$request->data[0]['ubicaciones'])->select('project_id')->get();
      foreach ($projects_loc as $key_loc => $value_loc) {
        $a_loc [] = $value_loc->project_id;
      }
    }
    $a_final = array_merge($a_org, $a_cli, $a_typ, $a_meth, $a_top, $a_mem, $a_loc, $a_tim);
    if (count($a_final) != 0) {
      $projects = Project::whereIn('id',$a_final)->orderBy('contract_start', 'DESC')->get();
    }else {
      if (count($request->data[0]['organizacion']) == 0
      && count($request->data[0]['clientes']) == 0
      && count($request->data[0]['tipos']) == 0
      && count($request->data[0]['metodologias']) == 0
      && count($request->data[0]['tematicas']) == 0
      && count($request->data[0]['colaboradores']) == 0
      && count($request->data[0]['ubicaciones']) == 0) {
        $projects = Project::orderBy('contract_start', 'DESC')->get();
      }
    }

    $result = [];
    foreach ($projects as $key => $project) {
      $clients = ClientProject::join('stackeholders AS s','s.id','client_project.stackeholder_id')
      ->where('project_id',$project->id)
      ->select('s.name')
      ->get();

      $client_text= '';
      foreach ($clients as $key_c => $client) {
        $client_text .= $client->name.', ';
      }
      $year_text = '';
      if ($project->contract_start != '') {
        $year_text .= substr($project->contract_start, 0, 4);
      }
      if ($project->contract_end != '') {
        $year_text .= ' - '.substr($project->contract_end, 0, 4);
      }
      if ($project->contract_start == null && $project->contract_end == null) {
        $year_text = '-';
      }
      $result [] = [$project, 'clients' => $client_text, 'year' => $year_text];
    }

    return response()->json($result);
  }

  public function addProject(Request $request)
  {

    $dta_web = [
        'description_es'=> '',
        'description_en'=> '',
        'subtitles' => [ [
          'name_seccion_es' => '',
          'name_seccion_en' => '',
          'subtitle_es'=>'',
          'subtitle_en' => '',
        ]
        ],
        'public_state' => false,
        'publication_principal' => false,
        'files' => false,
        'files_n' => [
        ]
      ];

      $file_web = [
        'name' => '',
        'path' => ''
      ];
      $project_resumen = '{ "resumen_es" : "" , "resumen_en" : "" }';
      $project_title = '{ "title_es" : "", "title_en" : "" }';

    $project = new Project();
    $project->user_id = auth('api')->user()->id;
    $project->name_es = $request->name_es;
    $project->company_id = $request->company_id;
    $project->code = $request->code;
    $project->amount = 0;
    $project->amount_mxn = 0;
    $project->amount_usd = 0;
    $project->contract_id = 1;
    $project->currency_id = 1;
    $project->contract_name = '--';
    $project->data_web = json_encode($dta_web);
    $project->file_web_single = json_encode($file_web);
    $project->project_resumen = $project_resumen;
    $project->project_title = $project_title;
    $project->save();

    foreach ($request->clients as $client_new) {
      $client_project_new = new \App\ClientProject();
      $client_project_new->stackeholder_id =  $client_new['id'];
      $client_project_new->project_id = $project->id;
      $client_project_new->save();
    }

    if ($request->linkeds) {
      // code...
      foreach ($request->linkeds as $linked_new) {
        $linked_new_save = new \App\LinkedProject();
        $linked_new_save->project_link_id =  $linked_new['id'];
        $linked_new_save->project_id = $project->id;
        $linked_new_save->code = $linked_new['code'];
        $linked_new_save->save();
      }
    }

    return response()->json($project->id);
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
      'code' => 'required|string|unique:projects',
      'contract_name' => 'required|string',
      'contract_name' => 'required|string',
    ]);

    $privacy_options = array();
    foreach ($request->privacy_options as $option){
      $privacy_options[] = $option['id'];
    }

    $members = array();
    foreach ($request->members as $member){
      $members[] = $member['id'];
    }

    $types = array();
    foreach ($request->types as $type){
      $types[] = $type['id'];
    }

    $methodologies = array();
    foreach ($request->methodologies as $methodology){
      $methodologies[] = $methodology['id'];
    }

    $topics = array();
    foreach ($request->topics as $topic){
      $topics[] = $topic['id'];
    }

    $clients = array();
    foreach ($request->clients as $client) {
      $clients[] = $client['id'];
    }

    $funders = array();
    foreach ($request->funders as $funder) {
      $funders[] = $funder['id'];
    }

    $suppliers = array();
    foreach ($request->suppliers as $supplier) {
      $suppliers[] = $supplier['id'];
    }

    $contract_backed_staff = array();
    foreach ($request->contract_backed_staff as $contract_backed_member) {
      $contract_backed_staff[] = $contract_backed_member['id'];
    }

    $satisfaction_documents = array();
    foreach ($request->satisfaction_documents as $satisfaction_document) {
      $satisfaction_documents[] = $satisfaction_document['id'];
    }

    $satisfactiondoc_backed_staff = array();
    foreach ($request->satisfactiondoc_backed_staff as $satisfactiondoc_backed_member) {
      $satisfactiondoc_backed_staff[] = $satisfactiondoc_backed_member['id'];
    }

    $locations = array();
    foreach ($request->locations as $location) {
      $locations[] = $location['id'];
    }

    $project = new Project([
      'user_id' => auth('api')->user()->id,
      'code' => $request['code'],
      'name_es' => $request['name_es'],
      'name_en' => $request['name_en'],
      'contract_name' => $request['contract_name'],
      'company_id' => $request['company_id'],
      'duration' => $request['duration'],
      'project_id' => $request['project_id'],
      'validity_of_confidentiality' => $request['validity_of_confidentiality'],
      'comments' => $request['comments'],
      'contract_type' => $request['contract_type'],
      'contract_creator' => $request['contract_creator'],
      'contractual_object' => $request['contractual_object'],
      'currency_id' => $request['currency_id'],
      'amount' => $request['amount'],
      'amount_mxn' => $request['amount_mxn'],
      'amount_usd' => $request['amount_usd'],
      'contract_start' => $request['contract_start'],
      'contract_end' => $request['contract_end'],
      'description_es' => $request['description_es'],
      'description_en' => $request['description_en'],
      'short_description_es' => $request['short_description_es'],
      'short_description_en' => $request['short_description_en'],
      'interviews' => $request['interviews'],
      'databases' => $request['databases'],
      'trainings' => $request['trainings'],
      'study_cases' => $request['study_cases'],
    ]);

    $project->save();

    foreach ($request->publications as $publication){
      $publication = new Publication([
        'project_id' => $project->id,
        'name' => $publication['name'],
        'link' => $publication['link'],
        'description' => $publication['description'],
      ]);
      $project->publications()->save($publication);
    }

    $project->privacy_options()->attach($privacy_options);
    $project->members()->attach($members);
    $project->types()->attach($types);
    $project->methodologies()->attach($methodologies);
    $project->topics()->attach($topics);
    $project->clients()->attach($clients);
    $project->funders()->attach($funders);
    $project->suppliers()->attach($suppliers);
    $project->contract_backed_staff()->attach($contract_backed_staff);
    $project->satisfaction_documents()->attach($satisfaction_documents);
    $project->satisfactiondoc_backed_staff()->attach($satisfactiondoc_backed_staff);
    $project->locations()->attach($locations);

    return $project;
  }

  /**
  * Display the specified resource.
  *
  * @param  int  $id
  * @return \Illuminate\Http\Response
  */
  public function show($id)
  {
    $project = Project::with('privacy_options')
    ->with('topics')
    ->with('methodologies')
    ->with('types')
    ->with('members')
    ->with('locations')
    ->with('company')
    ->with('user')
    ->with('clients')
    ->with('funders')
    ->with('suppliers')
    ->with('currency')
    ->with('contract')
    ->with('satisfaction_documents')
    ->with('contract_backed_staff')
    ->with('satisfactiondoc_backed_staff')
    ->with('publications')
    ->with('project')
    ->with('files')
    ->with('linkeds')
    ->with('consortiums')
    ->with('project_codes')
    ->with('project_document_names')
    ->with('tematics_web')
    ->findOrFail($id);
    return $project;
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
    // dd($request->time_sheets ? 1 : 0);
    $project = Project::with('privacy_options')
    ->with('topics')
    ->with('methodologies')
    ->with('types')
    ->with('members')
    ->with('locations')
    ->with('company')
    ->with('user')
    ->with('clients')
    ->with('funders')
    ->with('suppliers')
    ->with('currency')
    ->with('contract')
    ->with('satisfaction_documents')
    ->with('contract_backed_staff')
    ->with('satisfactiondoc_backed_staff')
    ->with('publications')
    ->with('project')
    ->with('files')
    ->with('linkeds')
    ->with('consortiums')
    ->with('tematics_web')
    ->findOrFail($id);

    $this->validate($request,[
      'code' => 'required|string',
      'contract_name' => 'required|string',
      'contract_name' => 'required|string',
    ]);

    $privacy_options = array();
    foreach ($request->privacy_options as $option){
      $privacy_options[] = $option['id'];
    }
    $privacy_options_project = \App\PrivacyOptionProject::whereNotIn('privacy_option_id',$privacy_options)->where('project_id',$request->id)->delete();
    foreach ($request->privacy_options as $privacy_option_new) {
      $privacy_options_search = \App\PrivacyOptionProject::where('privacy_option_id',$privacy_option_new['id'])->where('project_id',$request->id)->first();
      if(isset($privacy_options_search) == false){
        $privacy_options_new = new \App\PrivacyOptionProject();
        $privacy_options_new->privacy_option_id =  $privacy_option_new['id'];
        $privacy_options_new->project_id = $request->id;
        $privacy_options_new->save();
      }
    }

    $members = array();
    foreach ($request->members as $member){
      $members[] = $member['id'];
    }

    $types = array();
    foreach ($request->types as $type){
      $types[] = $type['id'];
    }
    $types_project = \App\ProjectType::whereNotIn('type_id',$types)->where('project_id',$request->id)->delete();
    foreach ($request->types as $types_new) {
      $types_search = \App\ProjectType::where('type_id',$types_new['id'])->where('project_id',$request->id)->first();
      if(isset($types_search) == false){
        $type_options_new = new \App\ProjectType();
        $type_options_new->type_id =  $types_new['id'];
        $type_options_new->project_id = $request->id;
        $type_options_new->save();
      }
    }

    $methodologies = array();
    foreach ($request->methodologies as $methodology){
      $methodologies[] = $methodology['id'];
    }
    $methodologies_project = \App\MethodologyProject::whereNotIn('methodology_id',$methodologies)->where('project_id',$request->id)->delete();
    foreach ($request->methodologies as $methodologies_new) {
      $methodologies_search = \App\MethodologyProject::where('methodology_id',$methodologies_new['id'])->where('project_id',$request->id)->first();
      if(isset($methodologies_search) == false){
        $methodology_new = new \App\MethodologyProject();
        $methodology_new->methodology_id = $methodologies_new['id'];
        $methodology_new->project_id = $request->id;
        $methodology_new->save();
      }
    }

    $topics = array();
    foreach ($request->topics as $topic){
      $topics[] = $topic['id'];
    }
    $topics_project = \App\TopicProject::whereNotIn('topic_id',$topics)->where('project_id',$request->id)->delete();
    foreach ($request->topics as $topics_new) {
      $topics_search = \App\TopicProject::where('topic_id',$topics_new['id'])->where('project_id',$request->id)->first();
      if(isset($topics_search) == false){
        $topic_new = new \App\TopicProject();
        $topic_new->topic_id =  $topics_new['id'];
        $topic_new->project_id = $request->id;
        $topic_new->save();
      }
    }


    $clients = array();
    $contacs = array();
    foreach ($request->clients as $client) {
      $contacs [] = $client['id'];
    }

    $client_project = \App\ClientProject::whereNotIn('stackeholder_id',$contacs)->where('project_id',$request->id)->delete();
    foreach ($request->clients as $client_new) {
      $client_search = \App\ClientProject::where('stackeholder_id',$client_new['id'])->where('project_id',$request->id)->first();
      if(isset($client_search) == false){
        $client_project_new = new \App\ClientProject();
        $client_project_new->stackeholder_id =  $client_new['id'];
        $client_project_new->project_id = $request->id;
        $client_project_new->save();
      }
    }

    // $clients = array();
    $linkeds_id = array();
    foreach ($request->linkeds as $linked) {
      $linkeds_id [] = $linked['id'];
    }
    $linked_project = \App\LinkedProject::whereNotIn('project_link_id',$linkeds_id)->where('project_id',$request->id)->delete();
    foreach ($request->linkeds as $linked_new) {
      $linked_search = \App\LinkedProject::where('project_link_id',$linked_new['id'])->where('project_id',$request->id)->first();
      if(isset($linked_search) == false){
        $linked_new_save = new \App\LinkedProject();
        $linked_new_save->project_link_id =  $linked_new['id'];
        $linked_new_save->project_id = $request->id;
        $linked_new_save->code = $linked_new['code'];
        $linked_new_save->save();
      }
    }

    $tematics_web_id = array();
    if ($request->tematics_web != "") {
      foreach ($request->tematics_web as $tematics_web) {
        $tematics_web_id [] = $tematics_web['id'];
      }
      $linked_project_sa = TematicWebProject::whereNotIn('tematic_id',$tematics_web_id)->where('project_id',$request->id)->delete();
      foreach ($request->tematics_web as $tematics_web_new) {
        $tematics_web_search = TematicWebProject::where('tematic_id',$tematics_web_new['id'])->where('project_id',$request->id)->first();
        if(isset($tematics_web_search) == false){
          $tematics_web_save = new TematicWebProject();
          $tematics_web_save->tematic_id =  $tematics_web_new['id'];
          $tematics_web_save->project_id = $request->id;
          $tematics_web_save->name = $tematics_web_new['name'];
          $tematics_web_save->save();
        }
      }
    }

    $funders = array();
    foreach ($request->funders as $funder) {
      $funders[] = $funder['id'];
    }

    $suppliers = array();
    foreach ($request->suppliers as $supplier) {
      $suppliers[] = $supplier['id'];
    }

    $contract_backed_staff = array();
    foreach ($request->contract_backed_staff as $contract_backed_member) {
      $contract_backed_staff[] = $contract_backed_member['id'];
    }

    $satisfaction_documents = array();
    foreach ($request->satisfaction_documents as $satisfaction_document) {
      $satisfaction_documents[] = $satisfaction_document['id'];
    }
    $satisfaction_project = \App\ProjectSatisfactionDocument::whereNotIn('satisfaction_document_id',$satisfaction_documents)->where('project_id',$request->id)->delete();
    foreach ($request->satisfaction_documents as $satisfaction_new) {
      $satisfaction_search = \App\ProjectSatisfactionDocument::where('satisfaction_document_id',$satisfaction_new['id'])->where('project_id',$request->id)->first();
      if(isset($satisfaction_search) == false){
        $satisfaction_new_save = new \App\ProjectSatisfactionDocument();
        $satisfaction_new_save->satisfaction_document_id =  $satisfaction_new['id'];
        $satisfaction_new_save->project_id = $request->id;
        $satisfaction_new_save->save();
      }
    }

    $satisfactiondoc_backed_staff = array();
    foreach ($request->satisfactiondoc_backed_staff as $satisfactiondoc_backed_member) {
      $satisfactiondoc_backed_staff[] = $satisfactiondoc_backed_member['id'];
    }

    $locations = array();
    foreach ($request->locations as $location) {
      $locations[] = $location['id'];
    }

    $locations_id = array();
    foreach ($request->locations as $location) {
      $locations_id [] = $location['id'];
    }
    $location_project = \App\LocationProject::whereNotIn('location_id',$locations_id)->where('project_id',$request->id)->delete();
    foreach ($request->locations as $location_new) {
      $location_search = \App\LocationProject::where('location_id',$location_new['id'])->where('project_id',$request->id)->first();
      if(isset($location_search) == false){
        $location_save = new \App\LocationProject();
        $location_save->location_id =  $location_new['id'];
        $location_save->project_id = $request->id;
        $location_save->save();
      }
    }

    // if($request->rol == 2){
    //     ConsortiumProject
    // }


    // $project->publications()->delete();
    $data_replace = ['$ ', ' MXN', ' USD', ','];
    $amount_mxn =  is_null($request->amount_mxn) ? 0 : str_replace($data_replace, "", $request->amount_mxn) ;
    $amount_usd =  is_null($request->amount_usd) ? 0 : str_replace($data_replace, "", $request->amount_usd);
    $currency_iva = json_encode($request->currency_iva);
    // $currency_iva = json_encode($request->data_web);
    $request->request->remove('data_web');
    $request->request->remove('file_web_single');
    // $request->merge(['time_sheets' => () ]);
    $request->merge(['project_resumen' => json_encode($request->project_resumen)]);
    $request->merge(['project_title' => json_encode($request->project_title)]);
    $request->merge(['amount_mxn' => $amount_mxn, 'amount_usd' => $amount_usd, 'currency_iva' => $currency_iva]);


    $project->update($request->all());

    // foreach ($request->publications as $publication){
    //     $publication = new Publication([
    //         'project_id' => $project->id,
    //         'name' => $publication['name'],
    //         'link' => $publication['link'],
    //         'description' => $publication['description'],
    //     ]);
    //     $project->publications()->save($publication);
    // }



    // $project->privacy_options()->sync($privacy_options);
    $project->members()->sync($members);
    // $project->types()->sync($types);
    // $project->methodologies()->sync($methodologies);
    // $project->topics()->sync($topics);
    // $project->clients()->sync($clients);
    $project->funders()->sync($funders);
    $project->suppliers()->sync($suppliers);
    $project->contract_backed_staff()->sync($contract_backed_staff);
    $project->satisfaction_documents()->sync($satisfaction_documents);
    $project->satisfactiondoc_backed_staff()->sync($satisfactiondoc_backed_staff);
    // $project->locations()->sync($locations);
    $project->time_sheets = $request->time_sheets ? 1 : 0;
    $project->save();

    return ['message' => 'Project updated'];
  }

  public function aditional(Request $request)
  {



    // dd(json_encode($request->data_web));

    // return response()->json($request['consortiums']['company']);
    if($request->rol == 2){
      $consortium =  \App\ConsortiumProject::where('project_id',$request->id)->first();
      if(isset($consortium) == false){
        $consortium_new = new \App\ConsortiumProject();
        $consortium_new->project_id = $request->id;
        $consortium_new->company = $request['consortiums']['company'];
        $consortium_new->company_represent = $request['consortiums']['company_represent'];
        $consortium_new->actions = $request['consortiums']['actions'];
        $consortium_new->final_client = $request['consortiums']['final_client'];
        $consortium_new->save();
      }else{
        $consortium->project_id = $request->id;
        $consortium->company = $request['consortiums']['company'];
        $consortium->company_represent = $request['consortiums']['company_represent'];
        $consortium->actions = $request['consortiums']['actions'];
        $consortium->final_client = $request['consortiums']['final_client'];
        $consortium->save();
      }
    }
    //
    $document_names_find = DocumentTypesNamesProject::where('project_id',$request->id)->first();
    if (isset($document_names_find) == true) {
      $document_names_find->data = json_encode($request->document_type_names);
      $document_names_find->project_id = $request->id;
      $document_names_find->save();
    }else {
      $document_names = new DocumentTypesNamesProject();
      $document_names->data = json_encode($request->document_type_names);
      $document_names->project_id = $request->id;
      $document_names->save();
    }
    //

    return response()->json(['status' => true]);
  }

  public function saveEditPublication(Request $request)
  {
    $FichaTecnicaStore = '';
    if ($request->hasFile('file')) {
      //obtiene el nombre del archivo y su extension
      $FichaTecnicaNE = $request->file('file')->getClientOriginalName();
      //Obtiene el nombre del archivo
      $FichaTecnicaNombre = pathinfo($FichaTecnicaNE, PATHINFO_FILENAME);
      //obtiene la extension
      $FichaTecnicaExt = $request->file('file')->getClientOriginalExtension();
      //nombre que se guarad en BD
      $FichaTecnicaStore = $FichaTecnicaNombre.$request->project_id.'_'.uniqid().'.'.$FichaTecnicaExt;
      //Subida del archivo al servidor local
      Storage::disk('local')->put('Archivos/'.$FichaTecnicaStore, fopen($request->file('file'), 'r+'));
    }
    if ($request->type === 'save') {
        $publication_new = new \App\Publication();
        $publication_new->project_id = $request->project_id;
        $publication_new->name = $request->name;
        $publication_new->description = $request->description;
        $publication_new->link = $request->link;
        $publication_new->file = $FichaTecnicaStore;
        $publication_new->save();
    }elseif ($request->type === 'edit') {
        $publication =  \App\Publication::where('id',$request->id)->first();
        // $publication->project_id = $request->project_id;
        $publication->name = $request->name;
        $publication->description = $request->description;
        $publication->link = $request->link;
        $publication->file = $FichaTecnicaStore;
        $publication->save();
    }

    $publications =  \App\Publication::where('project_id',$request->project_id)->get();

    // if ($request['publications']['id'] == 0) {
    //   $publication_new = new \App\Publication();
    //   $publication_new->project_id = $request->id;
    //   $publication_new->name = $request['publications']['name'];
    //   $publication_new->description = $request['publications']['description'];
    //   $publication_new->link = $request['publications']['link'];
    //   $publication_new->save();
    // }else {
    //   $publication =  \App\Publication::where('id',$request['publications']['id'])->first();
    //   $publication->project_id = $request->id;
    //   $publication->name = $request['publications']['name'];
    //   $publication->description = $request['publications']['description'];
    //   $publication->link = $request['publications']['link'];
    //   $publication->save();
    // }
    return response()->json($publications);

  }

  public function codes(Request $request)
  {
    try {
      if ($request->timeworks == true && $request->oh == false) {
        $project_code = \App\ProjectCode::where('codigo',$request->codigo.'OH')
        ->where('project_id',$request->project_id)->delete();
      }
      if ($request->timeworks == false && $request->oh == false) {
        $project_code = \App\ProjectCode::where('project_id',$request->project_id)->delete();
      }
      if ($request->timeworks == true && $request->categoria != null) {
        // return response()->json($request);
        $project_code_find = \App\ProjectCode::where('codigo',$request->codigo)
        ->where('project_id',$request->project_id)->first();
        if (isset($project_code_find) == false) {
          $project_code = new \App\ProjectCode();
          $project_code->codigo = $request->codigo;
          $project_code->organizacion = $this->nameShort($request->organizacion);
          $project_code->categoria = $request->categoria;
          $project_code->comentarios = "*";
          $project_code->project_id = $request->project_id;
          $project_code->save();
        }
        //solo se agregada un registro que es el proyecto sin OH
        if ($request->oh == true) {
          $project_code_oh_find = \App\ProjectCode::where('codigo', $request->codigo.'OH')
          ->where('project_id',$request->project_id)->first();
          if (isset($project_code_oh_find) == false) {
            $project_code_oh = new \App\ProjectCode();
            $project_code_oh->codigo = $request->codigo.'OH';
            $project_code_oh->organizacion = $this->nameShort($request->organizacion);
            $project_code_oh->categoria = 'Administración de proyectos';
            $project_code_oh->comentarios = "-";
            $project_code_oh->project_id = $request->project_id;
            $project_code_oh->save();
          }
        }
      }
      return response()->json(['status' => true]);
      // ProjectCode

    } catch (\Exception $e) {
      dd($e);
    }


  }

  public function nameShort($organizacion)
  {
    $name_short = '';

    switch ($organizacion) {
      case 'C230 Consultores México': $name_short = 'C230 Méx'; break;
      case 'Fundación IDEA México': $name_short = 'IDEA'; break;
      case 'C230 Consultores Colombia': $name_short = 'C230 Col'; break;
    }

    return $name_short;
  }

  /**
  * Remove the specified resource from storage.
  *
  * @param  int  $id
  * @return \Illuminate\Http\Response
  */
  public function destroy($id)
  {
    $project = Project::findOrFail($id);

    $project->privacy_options()->detach();
    $project->members()->detach();
    $project->types()->detach();
    $project->methodologies()->detach();
    $project->topics()->detach();
    $project->clients()->detach();
    $project->funders()->detach();
    $project->suppliers()->detach();
    $project->contract_backed_staff()->detach();
    $project->satisfaction_documents()->detach();
    $project->satisfactiondoc_backed_staff()->detach();
    $project->publications()->delete();
    $project->locations()->detach();

    $project->delete();

    return ['message' => 'Project deleted'];
  }

  public function download(Request $request)
  {
    $projects = $request->projects;
    //return Project::whereIn('id', $projects)->orderBy('contract_start', 'desc')->get();
    return Excel::download(new ProjectsExport($projects), 'projects.xlsx');
  }

  public function asingProjectUser(Request $request)
  {
    $members = new \App\Members();
    $members->user_id = $request->user_id;
    $members->project_id = $request->project_id;
    $members->save();

    return ['status' => true];
  }

  public function getClients($id)
  {
    $arreglo = [];

    $clients = ClientProject::join('stackeholders AS s','s.id','client_project.stackeholder_id')
    ->leftjoin('sh_descriptions AS shd','shd.id','s.sh_description_id')
    // ->join('sh_types AS sht','shd.id','s.sh_type_id')
    ->where('project_id',$id)
    ->select('client_project.id','client_project.stackeholder_id','s.name','shd.name AS sh_description')
    ->get();
    foreach ($clients as $key => $value) {
      $contacs = Contact::where('stackeholder_id',$value->stackeholder_id)->where('project_id',$id)->where('type','1')->get();
      $arreglo [] = [$value,'contacts' => $contacs];
    }
    return response()->json($arreglo);
  }

  public function saveClients(Request $request)
  {
    try {
      DB::beginTransaction();
      foreach ($request->data as $key => $value) {
        $clients_find = ClientProject::where('stackeholder_id',$value['id'])
        ->where('project_id',$request->project_id)->first();
        if (isset($clients_find) == false) {
          $clients = new ClientProject();
          $clients->stackeholder_id = $value['id'];
          $clients->project_id = $request->project_id;
          $clients->save();
        }
      }
      DB::commit();
      return response()->json(['status' => true]);
    } catch (\Exception $e) {
      DB::rollBack();
      dd($e);
    }
  }

  public function saveContact(Request $request)
  {
    try {
      DB::beginTransaction();
      $contacs = new Contact();
      $contacs->stackeholder_id = $request->stackeholder_id;
      $contacs->project_id = $request->project_id;
      $contacs->type = $request->type;
      $contacs->name = $request->data['name'];
      $contacs->email = $request->data['email'];
      $contacs->position = $request->data['position'];
      $contacs->phone = $request->data['phone'];
      $contacs->save();
      DB::commit();
      return response()->json(['status' => true]);
    } catch (\Exception $e) {
      DB::rollBack();
      dd($e);
    }
  }

  public function deleteClient($id)
  {
    $clients = ClientProject::where('id',$id)->delete();
    return response()->json(['status' => true]);
  }

  public function deleteFunder($id)
  {
    $clients = FunderProject::where('id',$id)->delete();
    return response()->json(['status' => true]);
  }

  public function deleteSupplier($id)
  {
    $clients = SupplierProject::where('id',$id)->delete();
    return response()->json(['status' => true]);
  }

  public function deleteConsortion($id)
  {
    $clients = ConsortionProject::where('id',$id)->delete();
    return response()->json(['status' => true]);
  }

  public function deleteMembersExtern($id)
  {
    $clients = Contact::where('id',$id)->delete();
    return response()->json(['status' => true]);
  }

  public function deleteContact($id)
  {
    $contacs = Contact::where('id',$id)->delete();
    return response()->json(['status' => true]);
  }

  public function editContact(Request $request)
  {
    try {
      DB::beginTransaction();
      $contacs = Contact::where('id',$request->data['id'])->first();
      // $contacs->stackeholder_id = $request->stackeholder_id;
      // $contacs->project_id = $request->project_id;
      $contacs->name = $request->data['name'];
      $contacs->email = $request->data['email'];
      $contacs->position = $request->data['position'];
      $contacs->phone = $request->data['phone'];
      $contacs->save();
      DB::commit();
      return response()->json(['status' => true]);
    } catch (\Exception $e) {
      DB::rollBack();
      dd($e);
    }
  }

  public function getFunders($id)
  {
    $arreglo = [];

    $clients = FunderProject::join('stackeholders AS s','s.id','funder_project.stackeholder_id')
    ->leftjoin('sh_descriptions AS shd','shd.id','s.sh_description_id')
    // ->join('sh_types AS sht','shd.id','s.sh_type_id')
    ->where('project_id',$id)
    ->select('funder_project.id','funder_project.stackeholder_id','s.name','shd.name AS sh_description')
    ->get();
    foreach ($clients as $key => $value) {
      $contacs = Contact::where('stackeholder_id',$value->stackeholder_id)->where('project_id',$id)->where('type','2')->get();
      $arreglo [] = [$value,'contacts' => $contacs];
    }
    return response()->json($arreglo);
  }

  public function saveFunders(Request $request)
  {
    try {
      DB::beginTransaction();
      foreach ($request->data as $key => $value) {
        $clients_find = FunderProject::where('stackeholder_id',$value['id'])
        ->where('project_id',$request->project_id)->first();
        if (isset($clients_find) == false) {
          $clients = new FunderProject();
          $clients->stackeholder_id = $value['id'];
          $clients->project_id = $request->project_id;
          $clients->save();
        }
      }
      DB::commit();
      return response()->json(['status' => true]);
    } catch (\Exception $e) {
      DB::rollBack();
      dd($e);
    }
  }

  public function getSuppliers($id)
  {
    $arreglo = [];

    $supplier = SupplierProject::join('stackeholders AS s','s.id','project_supplier.stackeholder_id')
    ->leftjoin('sh_descriptions AS shd','shd.id','s.sh_description_id')
    // ->join('sh_types AS sht','shd.id','s.sh_type_id')
    ->where('project_id',$id)
    ->select('project_supplier.id','project_supplier.stackeholder_id','s.name','shd.name AS sh_description')
    ->get();
    foreach ($supplier as $key => $value) {
      $contacs = Contact::where('stackeholder_id',$value->stackeholder_id)->where('project_id',$id)->where('type','3')->get();
      $arreglo [] = [$value,'contacts' => $contacs];
    }
    return response()->json($arreglo);
  }

  public function getConsortions($id)
  {
    $arreglo = [];

    $supplier = ConsortionProject::join('stackeholders AS s','s.id','project_consortion.stackeholder_id')
    ->leftjoin('sh_descriptions AS shd','shd.id','s.sh_description_id')
    // ->join('sh_types AS sht','shd.id','s.sh_type_id')
    ->where('project_id',$id)
    ->select('project_consortion.id','project_consortion.stackeholder_id','s.name','shd.name AS sh_description')
    ->get();
    foreach ($supplier as $key => $value) {
      $contacs = Contact::where('stackeholder_id',$value->stackeholder_id)->where('project_id',$id)->where('type','5')->get();
      $arreglo [] = [$value,'contacts' => $contacs];
    }
    return response()->json($arreglo);
  }

  public function saveSuppliers(Request $request)
  {
    try {
      DB::beginTransaction();
      foreach ($request->data as $key => $value) {
        $clients_find = SupplierProject::where('stackeholder_id',$value['id'])
        ->where('project_id',$request->project_id)->first();
        if (isset($clients_find) == false) {
          $clients = new SupplierProject();
          $clients->stackeholder_id = $value['id'];
          $clients->project_id = $request->project_id;
          $clients->save();
        }
      }
      DB::commit();
      return response()->json(['status' => true]);
    } catch (\Exception $e) {
      DB::rollBack();
      dd($e);
    }
  }

  public function saveConsortion(Request $request)
  {
    try {
      DB::beginTransaction();
      foreach ($request->data as $key => $value) {
        $clients_find = ConsortionProject::where('stackeholder_id',$value['id'])
        ->where('project_id',$request->project_id)->first();
        if (isset($clients_find) == false) {
          $clients = new ConsortionProject();
          $clients->stackeholder_id = $value['id'];
          $clients->project_id = $request->project_id;
          $clients->save();
        }
      }
      DB::commit();
      return response()->json(['status' => true]);
    } catch (\Exception $e) {
      DB::rollBack();
      dd($e);
    }
  }

  public function deletePublication($id)
  {
    $valores = explode('&',$id);

    $publication_d = Publication::where('id',$valores[0])->delete();
    $publication = Publication::where('project_id',$valores[1])->get();
    return response()->json($publication);
  }

  public function getMembersExtern($id)
  {
    $contacs = Contact::whereNull('stackeholder_id')->where('project_id',$id)->where('type','4')->get();
    return response()->json($contacs);
  }

  public function saveFileWeb(Request $request)
  {
    try {
      $file_web = [];
      if ($request->hasFile('file') ) {
        //obtiene el nombre del archivo y su extension
        $FichaTecnicaNE = $request->file('file')->getClientOriginalName();
        //Obtiene el nombre del archivo
        $FichaTecnicaNombre = pathinfo($FichaTecnicaNE, PATHINFO_FILENAME);
        //obtiene la extension
        $FichaTecnicaExt = $request->file('file')->getClientOriginalExtension();
        //nombre que se guarad en BD
        $FichaTecnicaStore = date("Ymdis").'.'.$FichaTecnicaExt;
        //Subida del archivo al servidor local
        Storage::disk('filesweb')->put($FichaTecnicaStore, fopen($request->file('file'), 'r+'));

        $path = url('/').'/files_web/'.date("Ymdis").'.'.$FichaTecnicaExt;
        $name = $FichaTecnicaNombre.'.'.$FichaTecnicaExt;

        return response()->json(['status' => true,'type' => 1, 'path' => $path, 'name' => $name]);

      }elseif ($request->hasFile('file_i')) {
        //obtiene el nombre del archivo y su extension
        $FichaTecnicaNEI = $request->file('file_i')->getClientOriginalName();
        //Obtiene el nombre del archivo
        $FichaTecnicaNombreI = pathinfo($FichaTecnicaNEI, PATHINFO_FILENAME);
        //obtiene la extension
        $FichaTecnicaExtI = $request->file('file_i')->getClientOriginalExtension();
        //nombre que se guarad en BD
        $FichaTecnicaStoreI = date("Ymdis").'.'.$FichaTecnicaExtI;
        //Subida del archivo al servidor local
        Storage::disk('filesweb')->put($FichaTecnicaStoreI, fopen($request->file('file_i'), 'r+'));

        $path = url('/').'/files_web/'.date("Ymdis").'.'.$FichaTecnicaExtI;
        $name = $FichaTecnicaNombreI.'.'.$FichaTecnicaExtI;

        $file_web = [
          'name' => $name,
          'path' => $path
        ];

        $project_web_f = Project::where('id',$request->id)->first();
        $project_web_f->file_web_single = json_encode($file_web);
        $project_web_f->save();

        return response()->json(['status' => true,'type' => 2, 'path' => $path, 'name' => $name, 'fw' => $file_web]);

      }else {
        return response()->json(['status' => false]);
      }

    } catch (\Exception $e) {
      return response()->json($e);
    }


  }

  public function dataWeb(Request $request)
  {
    try {
      $project_web = Project::where('id',$request->id)->first();
      $project_web->data_web = json_encode($request->data_web);
      $project_web->update();

      return response()->json($project_web);
    } catch (\Exception $e) {
      return response()->json($e);

    }


    // $project_web->data_web = json_encode($request->data_web);
    // $project_web->update();

  }

  public function dataWebFile(Request $request)
  {
    try {

      $project_web_f = Project::where('id',$request->id)->first();
      $project_web_f->file_web_single = json_encode($request->image_single);
      $project_web_f->update();

      return response()->json($project_web_f);
    } catch (\Exception $e) {
      return response()->json($e);

    }


    // $project_web->data_web = json_encode($request->data_web);
    // $project_web->update();

  }


  public function deleteImageSingle($id)
  {
    try {

    $project_web = Project::where('id',$id)->first();
    $project_web->file_web_single = null;
    $project_web->update();

    return response()->json(['status' => true]);
  } catch (\Exception $e) {
    return response()->json($e);

  }
  }


}
