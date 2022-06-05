<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\UserFile;
use App\UserAditionalData;
use App\SupervisorUsers;
use App\Position;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str as Str;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['partners']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // get all users in database
        return User::with('role')
            ->with('position')
            ->orderBy('active', 'desc')
            ->orderBy('name', 'ASC')
            ->get();
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
            'email' => 'required|string|unique:users',
            'password' => 'required|string|min:6',
        ]);

        $img_url = '';

        if($request->avatar){
            $name = str_slug($request->name).'-'.'avatar'.'.'. explode('/', explode(':', substr($request->avatar, 0, strpos($request->avatar, ';')))[1])[1];
            $img_url = url('/').'/img/avatars/'.str_slug($request->name).'-'.'avatar'.'.'. explode('/', explode(':', substr($request->avatar, 0, strpos($request->avatar, ';')))[1])[1];

            \Image::make($request->avatar)->save(public_path('img/avatars/').$name);
            $request->merge(['avatar' => $img_url]);
        }

        $user = new User([
            'name' => $request['name'],
            'email' => $request['email'],
            'role_id' => $request['role_id'],
            'position_id' => $request['position_id'],
            'bio_es' => $request['bio_es'],
            'bio_en' => $request['bio_en'],
            'password' => Hash::make($request['password']),
            'avatar' => $img_url,
            'active' => $request['active'],
            'organizacion' => $request['organizacion'],
            'pronombre' => $request['pronombre'],
            'date_in' => $request['date_in'],
            'hours_daily' => $request['hours_daily'],
            'roles' => json_encode($request->roles),
            'role_time_id' => $request->role_time_id,
            'role_radar_id' => $request->role_radar_id,
        ]);
        $user->save();

        foreach ($request->supervisores as $key_s => $value_s) {
            $supervisor_user = new SupervisorUsers();
            $supervisor_user->user_id = $user->id;
            $supervisor_user->supervisor_id = $value_s['id'];
            $supervisor_user->save();
        }

        $this->additionalDataStore($user, $request);

        return $user;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::with('files')
            ->with('projects')
            ->with('stackeholders')
            ->with('role')
            ->with('role_time')
            ->with('role_radar')
            ->with('position')
            ->with('supervisor')
            ->with('user_aditional_data')
            ->findOrFail($id)->toArray();

        $supervisor_user = SupervisorUsers::where('supervisor_users.user_id',$id)
        ->join('users AS u','u.id','supervisor_users.supervisor_id')
        ->select('supervisor_users.*','u.name')
        ->get()->toArray();
        $user_f = array_merge($user, ['supervisores' => $supervisor_user]);

        return $user_f;
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
        $user = User::with('role')
            ->with('position')
            ->findOrFail($id);

        $this->validate($request,[
            'name' => 'required|string|max:191',
            'email' => 'required|string|unique:users,email,'.$user->id,
            'password' => 'sometimes|string|min:6'
        ]);

        $currentAvatar = $user->avatar;

        if($request->avatar != $currentAvatar){
            $avatar_url_parts = explode("/", $user->avatar);
            $num = (count($avatar_url_parts) - 1);
            $userAvatar = public_path('img/avatars/').$avatar_url_parts[$num];
            if(file_exists($userAvatar)){
                @unlink($userAvatar);
            }

            $name = str_slug($request->name).'-'.'avatar'.'.'. explode('/', explode(':', substr($request->avatar, 0, strpos($request->avatar, ';')))[1])[1];
            $img_url = url('/').'/img/avatars/'.str_slug($request->name).'-'.'avatar'.'.'. explode('/', explode(':', substr($request->avatar, 0, strpos($request->avatar, ';')))[1])[1];
            \Image::make($request->avatar)->save(public_path('img/avatars/').$name);
            $request->merge(['avatar' => $img_url]);
        }

        if(!empty($request->password)){
            $request->merge(['password' => Hash::make($request['password'])]);
        }

        $user->update($request->all());

        return ['message' => 'Updated the user info'];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        $user->active = 1 - $user->active;
        $user->save();

        return ['message' => 'Status updated'];
    }
    /***
     *  Dowload image for user
     */
    public function dowloadAvatar($id){
        $avatarPath = User::findOrFail($id);
        $avatarPathCut = explode('/img/avatars/', $avatarPath->avatar);
        $pathtoFile = public_path().'/img/avatars/'.$avatarPathCut[1];
        try {
            return response()->download($pathtoFile);
        } catch (\Throwable $th) {
            abort(404);
        }

    }

    public function activeUser()
    {
        $user_id = auth('api')->user()->id;
        $user = User::with('role')
                    ->with('projects')
                    ->with('position')
                    ->with('files')
                    ->with('role_time')
                    ->with('role_radar')
                    ->with('supervisor')
                    ->with('user_aditional_data')
                    ->findOrFail($user_id);

        return $user;
    }

    public function activeUsers()
    {
        return User::where('active', 1)
            ->with('role')
            ->with('position')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function partners()
    {
        return User::where('active', 1)
            ->where('position_id', 1)
            ->with('role')
            ->with('position')
            ->orderBy('created_at', 'asc')
            ->get();
    }

    public function additionalData(Request $request)
    {
        try {
          DB::beginTransaction();
          $position = Position::where('id',$request->position_id)->first();

          $original = [' Consultores','Fundación ',','];
          $replace = ['','','/'];

          $organizations = str_replace($original,$replace,$request->organizacion);
          $user_supervisor = User::where('id',$request->supervisor_id)->first();
          $user_additional_data_search = UserAditionalData::where('user_id',$request->id)->first();
          if(isset($user_additional_data_search) == false){
            $user_additional_data = new UserAditionalData();
            $user_additional_data->user_id = $request->id;
            $user_additional_data->organization = $organizations;
            $user_additional_data->position = isset($position) == false ? '' : $position->name;
            $user_additional_data->type = $request->personal;//cambiar
            $user_additional_data->supervisor = isset($user_supervisor) == false ? '' : $user_supervisor->name;
            $user_additional_data->workday = $request->jornada;//cambiar
            $user_additional_data->save();
          }else{
            $user_additional_data_search->user_id = $request->id;
            $user_additional_data_search->organization = $organizations;
            $user_additional_data_search->position = isset($position) == false ? '' : $position->name;
            $user_additional_data_search->type = $request->personal;//cambiar
            $user_additional_data_search->supervisor = isset($user_supervisor) == false ? '' : $user_supervisor->name;
            $user_additional_data_search->workday = $request->jornada;//cambiar
            $user_additional_data_search->save();
          }

        $user = User::where('id',$request->id)->first();
        $user->supervisor_id = $request->supervisor_id;
        $user->personal = $request->personal;
        $user->jornada = $request->jornada;
        $user->hours_daily = $request->hours_daily;
        $user->role_time_id = $request->role_time_id;
        $user->role_radar_id = $request->role_radar_id;
        $user->active = $request->active;
        $user->role_id = $request->role_id;
        $user->name = $request->name;
        $user->position_id = $request->position_id;
        $user->organizacion = $request->organizacion;
        $user->email = $request->email;
        $user->bio_es = $request->bio_es;
        $user->bio_en = $request->bio_en;
        if (isset($request->password) == true) {
          $user->password = Hash::make($request->password);
        }

        $currentAvatar = $user->avatar;

        if($request->avatar != $currentAvatar){
            $avatar_url_parts = explode("/", $user->avatar);
            $num = (count($avatar_url_parts) - 1);
            $userAvatar = public_path('img/avatars/').$avatar_url_parts[$num];
            if(file_exists($userAvatar)){
                @unlink($userAvatar);
            }

            $name = str_slug($request->name).'-'.'avatar'.'.'. explode('/', explode(':', substr($request->avatar, 0, strpos($request->avatar, ';')))[1])[1];
            $img_url = url('/').'/img/avatars/'.str_slug($request->name).'-'.'avatar'.'.'. explode('/', explode(':', substr($request->avatar, 0, strpos($request->avatar, ';')))[1])[1];
            \Image::make($request->avatar)->save(public_path('img/avatars/').$name);
            $request->merge(['avatar' => $img_url]);
        }

        $user->avatar = $request->avatar;
        $user->roles = json_encode($request->roles);
        $user->pronombre = $request->pronombre;
        $user->date_in = $request->date_in;
        $user->save();

        $id_supervisor = [];
        foreach ($request->supervisores as $key_s => $value_s) {
          if(array_key_exists('avatar',$value_s)){
            $supervisor_user = new SupervisorUsers();
            $supervisor_user->user_id = $request->id;
            $supervisor_user->supervisor_id = $value_s['id'];
            $supervisor_user->save();

            $id_supervisor [] = $value_s['id'];
          }else {
            $id_supervisor [] = $value_s['supervisor_id'];
          }
        }

        $supervisor_user_refresh = SupervisorUsers::whereNotIn('supervisor_id',$id_supervisor)->where('user_id',$request->id)->delete();

          DB::commit();
          return response()->json(['status' => true]);
        } catch (\Throwable $e) {
          DB::rollBack();
          dd($e);
        }
    }

    public function additionalDataStore($user_store, $request)
    {


        $position = Position::where('id',$request->position_id)->first();

        $original = [' Consultores','Fundación ',','];
        $replace = ['','','/'];

        $organizations = str_replace($original,$replace,$request->organizacion);
        $user_supervisor = User::where('id',$request->supervisor_id)->first();
        $user_additional_data_search = UserAditionalData::where('user_id',$user_store->id)->first();
        if(isset($user_additional_data_search) == false){
          $user_additional_data = new UserAditionalData();
          $user_additional_data->user_id = $user_store->id;
          $user_additional_data->organization = $organizations;
          $user_additional_data->position = isset($position) == false ? '' : $position->name;
          $user_additional_data->type = $request->personal;//cambiar
          $user_additional_data->supervisor = isset($user_supervisor) == false ? '' : $user_supervisor->name;
          $user_additional_data->workday = $request->jornada;//cambiar
          $user_additional_data->save();
        }else{
          $user_additional_data_search->user_id = $user_store->id;
          $user_additional_data_search->organization = $organizations;
          $user_additional_data_search->position = isset($position) == false ? '' : $position->name;
          $user_additional_data_search->type = $request->personal;//cambiar
          $user_additional_data_search->supervisor = isset($user_supervisor) == false ? '' : $user_supervisor->name;
          $user_additional_data_search->workday = $request->jornada;//cambiar
          $user_additional_data_search->save();
        }

      $user = User::where('id',$user_store->id)->first();
      $user->supervisor_id = $request->supervisor_id;
      $user->personal = $request->personal;
      $user->jornada = $request->jornada;
      $user->role_time_id = $request->role_time_id;
      $user->role_radar_id = $request->role_radar_id;
      $user->active = $request->active;
      $user->role_id = $request->role_id;
      $user->name = $request->name;
      $user->position_id = $request->position_id;
      $user->organizacion = $request->organizacion;
      $user->email = $request->email;
      $user->bio_es = $request->bio_es;
      $user->bio_en = $request->bio_en;
      if (isset($request->password) == true) {
        $user->password = Hash::make($request->password);
      }

      $currentAvatar = $user->avatar;

      if($request->avatar != $currentAvatar){
          $avatar_url_parts = explode("/", $user->avatar);
          $num = (count($avatar_url_parts) - 1);
          $userAvatar = public_path('img/avatars/').$avatar_url_parts[$num];
          if(file_exists($userAvatar)){
              @unlink($userAvatar);
          }

          $name = str_slug($request->name).'-'.'avatar'.'.'. explode('/', explode(':', substr($request->avatar, 0, strpos($request->avatar, ';')))[1])[1];
          $img_url = url('/').'/img/avatars/'.str_slug($request->name).'-'.'avatar'.'.'. explode('/', explode(':', substr($request->avatar, 0, strpos($request->avatar, ';')))[1])[1];
          \Image::make($request->avatar)->save(public_path('img/avatars/').$name);
          $request->merge(['avatar' => $img_url]);
      }

      $user->avatar = $request->avatar;
      $user->date_in = $request->date_in;
      $user->save();



      return response()->json(['status' => true]);
    }

    public function deleteUser($id)
    {
      $user = User::find($id);
      $user->delete();

      return response()->json(['status' => true]);
    }

    public function members($id)
    {
      $members = \App\Members::join('users AS u','u.id','member_project.user_id')
      ->select('u.id','u.name','u.avatar','member_project.project_manager')->where('member_project.project_id',$id)->get();

      return response()->json($members);
    }

    public function savemembers(Request $request)
    {
      try {
        // $user = User::where('id',$request->user_id)->first();
        // $whitout = json_decode($user->roles);
        // $new =  array_merge($whitout ,[['id' => 8,
        //                 "name" => "Administrador(a) Proyecto",
        //                 "slug" => null,
        //                 "active" => 1,
        //                 "created_at" => null,
        //                 "updated_at" => null]]);
        // return response()->json(['status' => true, 'warning' => false, 'msn' => ($new)]);

        // foreach ($request->data as $key => $value) {
          $members_find = \App\Members::where('user_id',$request->user_id)
          ->where('project_id',$request->project_id)->first();
          if (isset($members_find) == false) {
            DB::beginTransaction();
            $members = new \App\Members();
            $members->user_id = $request->user_id;
            $members->project_id = $request->project_id;
            $members->project_manager = $request->project_manager;
            $members->save();
            if ($request->project_manager == 1) {
              $data_replace = ['[',']'];
              $user = User::where('id',$request->user_id)->first();
              $whitout = json_decode($user->roles);
              $new =  array_merge($whitout ,[['id' => 8,
                              "name" => "Administrador(a) Proyecto",
                              "slug" => null,
                              "active" => 1,
                              "created_at" => null,
                              "updated_at" => null]]);
              $user->roles = json_encode($new);
              $user->save();
            }
            DB::commit();
            return response()->json(['status' => true, 'warning' => false, 'msn' => 'Colaborar existente']);
          }else {
            return response()->json(['status' => true, 'warning' => true, 'msn' => 'Colaborar existente']);
          }
        // }
        // return response()->json($request);
      } catch (\Exception $e) {
        DB::rollBack();
        dd($e);
      }
    }

    public function deletemembers(Request $request)
    {
      try {
          $members_find = \App\Members::where('user_id',$request->user_id)
          ->where('project_id',$request->project_id)->delete();
        return response()->json(['status' => true]);
      } catch (\Exception $e) {
        dd($e);
      }
    }

    public function findUser(Request $request)
    {
      $user =  User::with('role')
          ->with('position')
          ->where('name','LIKE', '%'.$request->data.'%')
          ->orWhere('email','LIKE','%'.$request->data.'%')
          ->orderBy('active', 'desc')
          ->orderBy('created_at', 'desc')
          ->get();

      return response()->json($user);
    }

    public function getWithFilters(Request $request)
    {
      $keys = array_keys($request->data[0]);
      $a_estatus = [];
      $a_posiciones = [];
      $a_org = [];

      if (count($request->data[0]['estatus']) > 0) {
        $user_estatus = User::whereIn('active',$request->data[0]['estatus'])->select('id')->get();
        foreach ($user_estatus as $key_o => $value_estatus) {
          $a_estatus [] = $value_estatus->id;
        }
      }

      if (count($request->data[0]['posiciones']) > 0) {
        $projects_cli = User::whereIn('position_id',$request->data[0]['posiciones'])->select('id')->get();
        foreach ($projects_cli as $key_c => $value_cli) {
          $a_posiciones [] = $value_cli->id;
        }
      }

      if (count($request->data[0]['org']) > 0) {
        $query = User::query();
        foreach ($request->data[0]['org'] as $column => $values_q) {
          $query->orWhere('organizacion', 'LIKE', '%'.$values_q.'%');
        }
        $org = $query->select('id')->get();
        foreach ($org as $key_0 => $value_org) {
          $a_org [] = $value_org->id;
        }
      }


      $a_final = array_merge($a_estatus, $a_posiciones, $a_org);

      if (count($a_final) != 0) {
        $user_f =  User::with('role')
            ->with('position')
            ->whereIn('id',$a_final)
            ->orderBy('active', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();
      }else {
        if (count($request->data[0]['estatus']) == 0
        && count($request->data[0]['posiciones']) == 0
        && count($request->data[0]['org']) == 0) {
          $user_f =  User::with('role')
              ->with('position')
              ->orderBy('active', 'desc')
              ->orderBy('created_at', 'desc')
              ->get();
        }
      }


      return response()->json($user_f);
    }

    public function userActiveDesactive($id)
    {
      $new = [['id' => 4,
            "name" => "Usuario general",
            "slug" => "user",
            "active" => 1,
            "created_at" => null,
            "updated_at" => null]];

      $date = date('Y-m-d');
      $user = User::where('id',$id)->first();
      $user->active = $user->active == 0 ? 1 : 0;
      $user->role_time_id = 0;
      $user->role_radar_id = 0;
      $user->roles = $user->roles === '[]' ? json_encode($new) : '[]';
      $user->date_down = $user->active == 0 ? $date : null;
      $user->save();

      return response()->json(['status' => true]);
    }

    public function userDelete($id)
    {
      $user = User::where('id',$id)->delete();
      return response()->json(['status' => true]);
    }

    public function uploadAvatar(Request $request)
    {

      $user = User::where('id',$request->id)->first();

      if($request->hasFile('avatar')){
        $name = str_slug($request->name).'-'.'avatar'
        .'-'.date('is').'.'.$request->avatar->extension();
        $img_url = url('/').'/img/avatars/'.$name;
        \Image::make($request->avatar)->save(public_path('img/avatars/').$name);
        $user->avatar =$img_url;
        $user->save();
       }

      return response()->json($user->avatar);
    }
}
