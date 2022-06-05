<template>
  <div class="staff">
    <!-- <Loading v-if="loading" /> -->
    <vue-element-loading :active="loading" spinner="spinner" color="#FFA000" text="Cargando..." :is-full-screen="true"/>

    <div class="container" style="padding-left: 1px; padding-right: 1px;">
      <div class="col-md-12">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><router-link class="text-orange" to="/home">Inicio</router-link></li>
            <li class="breadcrumb-item"><router-link class="text-orange" to="/staff">Equipo de trabajo</router-link></li>
            <li class="breadcrumb-item active" aria-current="page">{{ user.name }}</li>
          </ol>
        </nav>
      </div>
      <div :class="container_class">
        <div class="row" v-if="!loading && $gate.isActive()">

          <div class="col-md-12" v-if="edit && !nuevo">
            <p class="float-center form-control-eigth"> ESTÁS EDITANDO ESTE PERFIL</p>
          </div>
          <div class="col-md-12" v-if="nuevo">
            <p class="float-center form-control-eigth"> AGREGANDO PERFIL</p>
          </div>

          <div class="col-md-12">
            <div class="card card-shadow-one bg-light-one pointer">
              <div class="card-body margin-profile" style="padding-left: 1px; padding-right: 1px; margin-left: 10px; margin-right: 10px;">
                <div class="form-row">
                  <div class="col-md-6">
                    <img v-if="user.avatar" class="d-block profile-avatar" :src="user.avatar" alt="avatar">
                    <i v-else class="far fa-user-circle profile-avatar-icon text-secondary"></i>
                  </div>
                </div>
                <div class="form-row" v-if="Object.keys(user).length > 0" >
                  <div class="col-md-6">
                    <a
                    @click="avatarModal(edit, nuevo)"
                    >
                    &nbsp;&nbsp;&nbsp;Editar imagen
                  </a>
                </div>
                <div class="col-md-6">
                  <p class="form-control-three mb-0">Información administrativa</p>
                </div>
              </div>
              <div class="form-row">
                <div class="col-md-6 mb-3">
                  <template v-if="edit && $gate.activeUser().role_id < 4">
                    <label>&nbsp;Nombre</label>
                    <input type="text" class="form-control" v-model="user.name">
                  </template>
                  <template v-else>
                    <p class="form-control-six mb-0">{{ user.name }} </p>
                  </template>
                </div>
                <div class="col-md-6 mb-1">
                  <label class="form-control-two mb-0">Tipo de personal (área)</label>
                  <v-select placeholder="Seleccione una opción" :options="filteredPersonal" v-model="user.personal" :disabled="!edit || $gate.activeUser().role_id > 3"></v-select>
                </div>
              </div>
              <div class="form-row">
                <div class="col-md-6">
                  <template v-if="edit && $gate.activeUser().role_id < 4">
                    <div class="mb-3">
                      <label>&nbsp;</label><br>
                      <label>&nbsp;Cargo</label>
                      <v-select label="name" placeholder="Seleccione una opción" :options="filteredPosition" v-model="user.position"></v-select>
                    </div>
                    <!-- <div class="mb-3">
                    <label>&nbsp;Organización</label>
                    <v-select multiple placeholder="Seleccione una opción" :options="filteredOrganizacion" v-model="user.organizacion"></v-select>
                  </div> -->
                </template>
                <template v-else>
                  <p class="form-control-four mb-0"><b>
                    <span v-if="user.position">{{ user.position.name }}</span>
                    <span v-else>Sin Cargo</span></b>
                    <span>/ </span>
                    <span v-for="t in user.organizacion">
                      {{t}},
                    </span>
                  </p>
                  <!-- <v-select multiple placeholder="Seleccione una opción" :options="filteredOrganizacion" v-model="user.organizacion" :disabled="!edit || $gate.activeUser().role_id > 3"></v-select> -->

                  <div class="mb-4">
                    <div class="row">
                      <div class="col-md-6">
                        <label class="form-control-two ">Pronombre</label>
                        <v-select label="name" placeholder="Seleccione una opción" :options="filteredPronombres" v-model="user.pronombre" :disabled="!edit || $gate.activeUser().role_id > 3"></v-select>
                      </div>
                      <div class="col-md-6">
                        <label class="form-control-two ">Fecha de ingreso</label>
                        <input type="date" class="form-control" v-model="user.date_in" :disabled="!edit">
                      </div>
                    </div>
                  </div>
                </template>
              </div>
              <div class="col-md-6 mb-4">
                <label>&nbsp;</label><br>
                <label class="form-control-two ">Supervisor(a)</label>
                <v-select multiple label="name" placeholder="Seleccione una opción" :options="filteredSupervisor" v-model="user.supervisores" :disabled="!edit || $gate.activeUser().role_id > 3"></v-select>
              </div>
            </div>

            <div class="form-row" v-if="edit || nuevo">
              <div class="col-md-3 mb-4">
                <label class="form-control-two ">Pronombre</label>
                <v-select label="name" placeholder="Seleccione una opción" :options="filteredPronombres" v-model="user.pronombre" :disabled="!edit || $gate.activeUser().role_id > 3"></v-select>
              </div>
              <div class="col-md-3 mb-4" >
                  <label class="form-control-two ">Fecha de ingreso</label>
                  <input type="date" class="form-control" v-model="user.date_in">
              </div>
              <div class="col-md-6">
                <label>&nbsp;Organización</label>
                <v-select multiple placeholder="Seleccione una opción" :options="filteredOrganizacion" v-model="user.organizacion" :disabled="!edit || $gate.activeUser().role_id > 3"></v-select>
              </div>
            </div>

            <div class="form-row">
              <div class="col-md-6">
                <template v-if="edit && $gate.activeUser().role_id < 4">
                  <label class="form-control-two mb-0">Email</label>
                  <input type="text" class="form-control form-control-one mb-0" v-model="user.email">
                </template>
                <template v-else>
                  <label class="form-control-two mb-0">Email</label>
                  <input type="text" class="form-control form-control-one mb-0" v-model="user.email" disabled>
                </template>
              </div>
              <div v-show="user.jornada == 'Tiempo parcial'" class="col-md-3">
                <label class="form-control-two mb-0">Tipo de jornada</label>
                <v-select taggable label="name" placeholder="Seleccione una opción o escriba la jornada requerida" :options="filteredJornada" v-model="user.jornada" :disabled="!edit || $gate.activeUser().role_id > 3"></v-select>
              </div>
              <div v-show="user.jornada == 'Tiempo parcial'" class="col-md-3">
                <label>Horas diarias</label>
                <input type="number" class="form-control form-control-one mb-0" style="margin-top : -8px;" v-model="user.hours_daily">
              </div>
              <div class="col-md-6" v-show="user.jornada == 'Tiempo completo' || user.jornada == '' || user.jornada == null">
                <label class="form-control-two mb-0">Tipo de jornada</label>
                <v-select taggable label="name" placeholder="Seleccione una opción o escriba la jornada requerida" :options="filteredJornada" v-model="user.jornada" :disabled="!edit || $gate.activeUser().role_id > 3"></v-select>
              </div>
            </div>

            <div class="form-row" v-if="!nuevo">
              <div class="col-md-6">
                <p class="mb-0">&nbsp;</p>
                <a   @click="activeSelect()" class="btn btn-outline-blue mb-0"
                v-if="$gate.activeUser().role_id == 1 || $gate.activeUser().role_id == 2 || $gate.activeUser().role_id == 3 || $gate.activeUser().role_id == 4">
                Editar perfil
              </a>
            </div>
          </div>

        </div>
      </div>
    </div>

    <br>
    <!-- v-if="user.bio_es || user.bio_en" -->
    <div class="col-md-12 mb-4">
      <div class="margin-profile" style="padding-left: 1px; padding-right: 1px; margin-left: 10px; margin-right: 10px;">

        <p class="form-control-three mt-2 d-inline-block">Semblanza</p>
        <ul class="nav nav-tabs mb-3" id="myTab" role="tablist">
          <li class="nav-item">
            <a class="nav-link active" id="spanish-tab" data-toggle="tab" href="#spanish" role="tab" aria-controls="spanish" aria-selected="true">Español</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" id="english-tab" data-toggle="tab" href="#english" role="tab" aria-controls="english" aria-selected="false">Inglés</a>
          </li>
        </ul>
        <div class="tab-content" id="myTabContent">
          <div class="tab-pane fade show active" id="spanish" role="tabpanel" aria-labelledby="spanish-tab">
            <template v-if="user.bio_es && !edit">
              <p class="mt-3">{{ user.bio_es }}</p>
            </template>
            <template v-if="edit && $gate.activeUser().role_id < 4">
              <textarea class="form-control" rows="4" cols="80" v-model="user.bio_es"></textarea>
            </template>
          </div>
          <div class="tab-pane fade" id="english" role="tabpanel" aria-labelledby="english-tab">
            <template v-if="user.bio_en && !edit">
              <p class="mt-3">{{ user.bio_en }}</p>
            </template>
            <template v-if="edit && $gate.activeUser().role_id < 4">
              <textarea class="form-control" rows="4" cols="80" v-model="user.bio_en"></textarea>
            </template>
          </div>
        </div>
      </div>
    </div>
    <br>
    <template v-if="!nuevo">

      <div class="col-md-12 mb-4" >
        <div class="margin-profile" style="padding-left: 1px; padding-right: 1px; margin-left: 10px; margin-right: 10px;">
          <!-- @click="newModal" -->
          <p class="form-control-three d-inline-block">Proyectos ({{ user.projects == null ? 0 : user.projects.length }})</p>
          <a href="#" class="btn btn-outline-blue float-right" @click="newModal" v-if="edit && $gate.activeUser().role_id < 4">
            <i class="fas fa-plus mr-1"></i>
            Agregar proyecto
          </a>
          <div class="d-none d-md-flex align-md-items-center flex-orientation table-head bar-color form-control-seven">
            <div class="p-2 w-md-20">Código</div>
            <div class="p-2 w-md-35">Nombre</div>
            <div class="p-2 w-md-30">Clientes</div>
            <div class="p-2 w-md-15 text-center-md">Fecha de inicio</div>
          </div>
          <div
          class="d-flex align-items-md-center flex-orientation table-row pointer"
          v-for="project in user.projects"
          :key="project.id"
          @click="goToProject(project)"
          >
          <div class="p-2 w-md-20">
            <span class="font-weight-bold d-md-none">Código: </span> {{ project.code }}
          </div>
          <div class="p-2 w-md-35">
            <span class="font-weight-bold d-md-none">Nombre: </span> {{ project.name_es }}
          </div>
          <div class="p-2 w-md-30">
            <span class="font-weight-bold d-md-none">Clientes: </span>
            <span v-if="project.clients.length > 0">
              <span
              v-for="(client, index) in project.clients"
              :key="index"
              >
              {{ client.name }}<span v-if="index !== (project.clients.length -1)">, </span>
            </span>
          </span>
          <span v-else>N/A</span>
        </div>
        <div class="p-2 w-md-15 text-center-md">
          <span class="font-weight-bold d-md-none">Fecha: </span>
          <span v-if="project.contract_start">
            {{ project.contract_start | myDate }}
          </span>
          <span v-else>
            N/A
          </span>
        </div>
      </div>
    </div>
  </div>

  <div class="col-md-12 mb-4">
    <div class="margin-profile" style="padding-left: 1px; padding-right: 1px; margin-left: 10px; margin-right: 10px;">
      <p class="form-control-three mt-2 d-inline-block ">Archivos</p>
      <button class="btn btn-outline-blue float-right" @click="newModalFile" v-if="edit && $gate.activeUser().role_id < 4">
        <i class="fas fa-plus mr-1"></i>
        Agregar archivo
      </button>
      <div class="table-responsive" v-if="user.files && user.files.length">
        <table class="table">
          <thead class="bar-color form-control-seven">
            <tr>
              <td>Nombre</td>
              <td class="text-center">Tipo</td>
              <td class="text-center">Fecha</td>
              <td class="text-center">Descargar</td>
              <td class="text-center">Eliminar</td>
            </tr>
          </thead>
          <tbody>
            <tr v-for="file in user.files" :key="file.id">
              <td>
                {{ file.name }}
              </td>
              <td class="text-center">
                {{ file.type }}
              </td>
              <td class="text-center">
                {{ file.created_at | myDate }}
              </td>
              <td class="text-center">

                <img src="/img/np_download.png" style="width : 1.5rem;" @click="downloadFile(file)">

                <!-- <i class="fas fa-download pointer" @click="downloadFile(file)"></i> -->
              </td>
              <td class="text-center">
                <template v-if="edit">
                  <img src="/img/np_delete.png" style="width : 1.5rem;" @click="deleteFile(file)">
                </template>
                <template v-else>
                  <button class="btn" disabled>
                    <img src="/img/np_delete.png" style="width : 1.5rem;">
                  </button>
                </template>


                <!-- <i class="far fa-trash-alt pointer" @click="deleteFile(file)"></i> -->
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      <div class="col-md-12 mt-4 text-center" v-else>
        <p>No hay archivos para este usuario.</p>
        <img src="/img/sin-archivos-cargados.png" width="40%">

      </div>

    </div>
  </div>

  <!-- <UserFiles
  @fileDeleted="fileDeleted"
  :user="user"
  /> -->
</template>

<div class="col-md-12 mb-4" >
  <div class="margin-profile" style="padding-left: 1px; padding-right: 1px; margin-left: 10px; margin-right: 10px;">
    <!-- @click="newModal" -->
    <p class="form-control-three d-inline-block">Plataformas internas</p>
    <div class="col-md-12 mb-4" v-if="edit">
      <label>Contraseña</label>
      <input type="password" class="form-control" v-model="user.password" autocomplete="off">
    </div>
    <div class="form-row">
      <div class="col-md-4 mb-3">
        <div class="card card-shadow-one bg-light-one pointer">
          <div class="card-header">
            <label class="form-control-eigth">PGC</label>
            <template v-if="edit && $gate.activeUser().role_id < 4">
              <label class="switch float-right">
                <input type="checkbox" v-model="user.active" >
                <span class="slider round"></span>
              </label>
            </template>
            <template v-else>
              <label class="switch-disable float-right">
                <input type="checkbox" v-model="user.active" disabled>
                <span class="slider round"></span>
              </label>
            </template>
          </div>
          <div class="card-body" v-if="user.active">
            <div class="form-row">
              <div class="col-md-12">
                <label>Tipo de perfil</label>
                <v-select multiple label="name" placeholder="Seleccione una opción" :options="filteredRoles" v-model="user.roles" :disabled="!edit || $gate.activeUser().role_id > 3"></v-select>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-4 mb-3">
        <div class="card card-shadow-one bg-light-one pointer">
          <div class="card-header">
            <label class="form-control-eigth">TimeWorks</label>
            <template v-if="edit && $gate.activeUser().role_id < 4">
              <label class="switch float-right">
                <input type="checkbox" v-model="user.role_time_id" >
                <span class="slider round"></span>
              </label>
            </template>
            <template v-else>
              <label class="switch-disable float-right">
                <input type="checkbox" v-model="user.role_time_id" disabled>
                <span class="slider round"></span>
              </label>
            </template>
          </div>
          <div class="card-body" v-if="user.role_time_id > 0">
            <div class="form-row">
              <div class="col-md-12">
                <label>Tipo de perfil</label>
                <v-select label="name" placeholder="Seleccione una opción" :options="filteredRolesTimes" v-model="user.role_time" :disabled="!edit || $gate.activeUser().role_id > 3"></v-select>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-4 mb-3">
        <div class="card card-shadow-one bg-light-one pointer">
          <div class="card-header">
            <label class="form-control-eigth">Radar C19</label>
            <template v-if="edit && $gate.activeUser().role_id < 4">
              <label class="switch float-right">
                <input type="checkbox" v-model="user.role_radar_id" >
                <span class="slider round"></span>
              </label>
            </template>
            <template v-else>
              <label class="switch-disable float-right">
                <input type="checkbox" v-model="user.role_radar_id" disabled>
                <span class="slider round"></span>
              </label>
            </template>
          </div>
          <div class="card-body" v-if="user.role_radar_id > 0">
            <div class="form-row">
              <div class="col-md-12">
                <label>Tipo de perfil</label>
                <v-select label="name" placeholder="Seleccione una opción" :options="filteredRolesRadar" v-model="user.role_radar" :disabled="!edit || $gate.activeUser().role_id > 3"></v-select>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>



  </div>
</div>

<div class="col-md-12 mb-3 float-center" v-if="edit && !nuevo">
  <button class="btn btn-outline-orange-one" @click="updateUser()">
    <i class="fas fa-plus mr-1"></i>
    Guardar cambios
  </button>
  <button type="button" class="btn btn-outline-gray-one" @click="cancelar()">Cancelar</button>
</div>

<div class="col-md-12 mb-3 float-center" v-if="nuevo">
  <button class="btn btn-outline-orange-one" @click="createUser()">
    <i class="fas fa-plus mr-1"></i>
    Guardar colaborador
  </button>
  <button type="button" class="btn btn-outline-gray-one" @click="cancelar()">Cancdelar</button>
</div>

</div>
</div>
<br>
</div>

<!-- Modal -->
<div class="modal fade" id="addNewProject" tabindex="-1" role="dialog" aria-labelledby="addNewLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addNewLabel">Agregar proyecto</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <label for="name">Proyecto</label>
          <multiselect
          v-model="user.proyecto"
          :options="filteredProyectos"
          :showLabels="false"
          placeholder= "Seleccione una opción"
          label="name_es"
          :disabled="!$gate.isDeveloper() && form.role && form.role.id == 1"
          track-by="name_es">
        </multiselect>
      </div>

    </div>
    <div class="modal-footer">
      <button class="btn btn-orange btn-round" @click="addNewProject()">Agregar</button>
      <button type="button" class="btn btn-secondary btn-round" data-dismiss="modal">Cancelar</button>
    </div>
  </div>
</div>
</div>

<!-- Modal -->
<div class="modal fade" id="addNewFile" tabindex="-1" role="dialog" aria-labelledby="addNewLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addNewLabel">Agregar documento</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form @submit.prevent="addDocument()" enctype="multipart/form-data">
        <div class="modal-body">
          <div class="form-group">
            <label for="name">Nombre del archivo</label>
            <input v-model="form_file.name" type="text" name="name" placeholder="" class="form-control"
            :class="{ 'is-invalid': form.errors.has('name') }">
            <has-error :form="form" field="name"></has-error>
          </div>

          <div class="form-group">
            <label for="exampleFormControlFile1">Documento</label>
            <input @change="onImageChange" type="file" class="form-control-file" id="exampleFormControlFile1">
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-orange btn-round">Agregar</button>
          <button type="button" class="btn btn-secondary btn-round" data-dismiss="modal">Cancelar</button>
        </div>
      </form>
    </div>
  </div>
</div>
<avatar ref="Avatar" :user="user" :edit="edit" :nuevo="nuevo" v-if="!loading" @close-modal-avatar="closeModalAvatar($event)" />
<!-- :user="user" -->


<NoActive v-if="!loading && !$gate.isActive()" />

</div>
</template>

<script>
import NoActive from '../complements/NoActive'
import Loading from '../complements/Loading'
import UserFiles from '../user/Files'
import Multiselect from 'vue-multiselect'
import Avatar from '../user/Avatar'

export default {
  components: {
    'avatar': Avatar,
    Multiselect,UserFiles, Loading, NoActive
  },
  data: () => ({
    filteredProyectos : [],
    filteredRoles : [],
    filteredRolesTimes : [],
    filteredRolesRadar : [],
    filteredSupervisor : [],
    filteredPosition : [],
    filteredJornada : ['Tiempo completo', 'Tiempo parcial'],
    filteredPersonal : ['Proyectos', 'Administración'],
    filteredPronombres : ['él','ella','elle'],
    filteredOrganizacion : ['C230 Consultores','C230 Consulting','Fundación IDEA','Supernova'],
    loading: false,
    // disabled : true,
    edit : false,
    nuevo : false,
    container_class : 'container',
    user: {
    },
    form_file: new Form({
      id:'',
      user_id: '',
      name : '',
      type: '',
      file: '',
    }),
    form: new Form({
      id:'',
      name_file : '',
      file_save: '',
      name:'',
      email:'',
      type:'',
      role_id:'',
      role:{},
      position_id:'',
      position:{},
      pronombre : '',
      bio_es:'',
      bio_en:'',
      avatar:'',
      password:'',
      active:1,
      files:[],
      organizacion : '',
      supervisor_id : '',
      personal : '',
      jornada : '',
      supervisores : '',
      roles : {},
      hours_daily : '',
      date_in : '',
    }),
  }),
  methods:{
    // New methods
    avatarModal(){
      $('#avatarModal').modal('show')
    },

    closeModalAvatar(data){
      console.log(data,'dd-psa');
      this.user.avatar = data;
      $('#avatarModal').modal('hide');
    },
    //End new methods
    getSupervisores(){
      axios.get("/api/user")
      .then(
        ({data}) => (
          this.filteredSupervisor = data
        )
      )
    },
    getProyectos(){
      axios.get("/api/project")
      .then(
        ({data}) => (
          this.filteredProyectos = data
        )
      )
    },
    getPositions(){
      axios.get("/api/position")
      .then(
        ({data}) => (
          this.filteredPosition = data
        )
      )
    },
    getRoles(){
      axios.get(`/api/role`)
      .then(
        ({data}) => (
          this.filteredRoles = data
        )
      )
    },
    getRolesTimes(){
      axios.get(`/api/role-times`)
      .then(
        ({data}) => (
          this.filteredRolesTimes = data
        )
      )
    },
    getRolesRadar(){
      axios.get(`/api/role-radar`)
      .then(
        ({data}) => (
          this.filteredRolesRadar = data
        )
      )
    },
    addTag (newTag) {
      const tag = {
        newTag,
      };
      this.filteredJornada.push(tag.newTag);
      this.user.jornada = tag.newTag;
    },

    dowloadAvatar(user){
      const avatarCut = user.avatar.split('img/avatars/');
      const idSend = user.id;
      if (avatarCut.length == 1) {
        toast.fire({
          type: 'error',
          title: `Avatar no disponible.`
        })
      } else {
        axios({
          method: 'get',
          url: '/api/dowload-avatar/'+idSend,
          data: {},
          responseType: 'blob', // important
        }).then((response) => {
          this.$Progress.finish();
          const url = window.URL.createObjectURL(new Blob([response.data]));
          const link = document.createElement('a');
          link.href = url;
          link.setAttribute('download', '"'+avatarCut[1] +'"');
          document.body.appendChild(link);
          link.click();
          toast.fire({
            type: 'success',
            title: `Descarga completa.`
          })
        }).catch(function (error) {
          toast.fire({
            type: 'error',
            title: `No se encontró imagen en el sistema, contacta al administrador.`
          })
        });
      }
    },
    goToProject(project){
      this.$router.push(`/projects/${project.id}`)
    },
    editUser(user){
      this.$router.push(`/staff/edit/${user.id}`)
    },
    fileDeleted(){
      Fire.$emit('AfterCreate');
    },
    getActiveUser(){
      this.loading = true;
      if(this.$gate.isActive()){
        axios.get("/api/active-user")
        .then(
          ({data}) => (
            this.loading = false,
            this.user = data,
            this.user.organizacion =(data.organizacion).split(",")
          )
        )
      } else {
        this.loading = false;
      }
      this.user.password = null;

    },
    updateAvatar(e){
      // console.log(e);
      let file = e.target.files[0];
      let reader = new FileReader();
      reader.onloadend = (file) => {
        this.user.avatar = reader.result;
      }
      reader.readAsDataURL(file);
    },
    getUser(){
      if (this.$route.params.id != 0) {
        this.loading = true;
        this.nuevo = false;
        if (this.$gate.isActive()) {
          axios.get(`/api/user/${this.$route.params.id}`).then(response => {
            this.loading = false;
            this.user = response.data;
            if (response.data.organizacion != null) {
              this.user.organizacion =(response.data.organizacion).split(",");
            }
            this.user.password = null;
            this.user.password = '';
            this.user.roles = JSON.parse(response.data.roles);
            this.user.supervisores = response.data.supervisores;
          });

        } else {
          this.loading = false;
        }
        this.user.password = null;
        this.user.password = '';

      }else {
        this.nuevo = true;
        this.user.password = null;
        this.user.password = '';
        this.activeSelect();
      }


    },
    newModal(){
      // this.form.reset();
      $('#addNewProject').modal('show');
    },
    newModalFile(){
      // this.form.reset();
      $('#addNewFile').modal('show');
    },
    addNewProject(){

      this.$Progress.start();

      axios.post(`/api/assigned-project`, {
        user_id : this.user.id,
        project_id : this.user.proyecto.id
      })
      .then(() => {
        Fire.$emit('AfterCreate');
        $('#addNewProject').modal('hide')
        toast.fire({
          type: 'success',
          title: 'Correcto'
        })
        this.$Progress.finish();
        this.form.reset();
      })
      .catch((e)=>{
        // console.log(e);

        toast.fire({
          type: 'error',
          title: 'Error al agregar documento'
        })
        this.$Progress.fail();
      })
    },
    addDocument(e){
      const config = {
        headers: { 'content-type': 'multipart/form-data' }
      }

      let formData = new FormData();
      formData.append('name', this.form_file.name);
      formData.append('file', this.form_file.file);
      formData.append('user_id', this.user.id);

      this.$Progress.start();

      axios.post(`/api/user-file`, formData, config)
      .then(() => {
        Fire.$emit('AfterCreate');
        $('#addNewFile').modal('hide')
        toast.fire({
          type: 'success',
          title: 'Documento creado con éxito'
        })
        this.$Progress.finish();
        this.form_file.reset();
      })
      .catch((e)=>{
        // console.log(e);

        toast.fire({
          type: 'error',
          title: 'Error al agregar documento'
        })
        this.$Progress.fail();
      })
    },
    onImageChange(e) {
      this.form_file.file = e.target.files[0];
    },
    downloadFile(user_file){
      this.$Progress.start();
      axios({
        url: `/api/download-user-file/${user_file.id}`,
        method: 'GET',
        responseType: 'blob', // important
      }).then((response) => {
        this.$Progress.finish();
        const url = window.URL.createObjectURL(new Blob([response.data]));
        const link = document.createElement('a');
        link.href = url;
        link.setAttribute('download', user_file.file);
        document.body.appendChild(link);
        link.click();
      });
    },
    deleteFile(file){
      this.$Progress.start();
      swal.fire({
        title: '¿Estás seguro?',
        text: "¡No podrás revertir esto!",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, ¡bórralo!',
        cancelButtonText: 'Cancelar'
      }).then((result) => {
        if (result.value) {
          this.form.delete(`/api/user-file/${file.id}`)
          .then(()=>{
            this.$Progress.finish();
            swal.fire(
              '¡Eliminado!',
              'El archivo ha sido eliminado.',
              'success'
            )
            // this.$emit('fileDeleted')
            Fire.$emit('AfterCreate');
          }).catch(()=> {
            this.$Progress.fail();
            swal.fire(
              'Error',
              'Algo salió mal',
              'warning'
            )
          });
        }
      })
    },
    //FUNCION PARA DATOS ADICIONALES
    updateUser(){
      this.loading = true;
      // console.log(this.user);
      this.form.personal = this.user.personal;
      this.form.jornada = this.user.jornada;
      this.form.hours_daily = this.user.jornada == 'Tiempo completo' ? 8 : this.user.hours_daily;
      this.form.supervisor_id = this.user.supervisor == null ? null : this.user.supervisor.id;
      this.form.supervisores = this.user.supervisores;
      this.form.avatar = this.user.avatar;
      this.form.id = this.user.id;
      this.form.role_time_id = this.user.role_time_id == false ? 0 : this.user.role_time == null ? 0 :this.user.role_time.id;
      this.form.role_radar_id = this.user.role_radar_id == false ? 0 : this.user.role_radar == null ? 0 :this.user.role_radar.id;
      this.form.active = this.user.active;
      this.form.role_id = this.user.role == null ? null : this.user.role.id;
      this.form.name = this.user.name;
      this.form.position_id = this.user.position == null ? null :this.user.position.id;
      this.form.organizacion = this.user.organizacion == null ? '' : (this.user.organizacion).toString();
      this.form.email = this.user.email;
      this.form.bio_es = this.user.bio_es;
      this.form.bio_en = this.user.bio_en;
      this.form.password = this.user.password;
      this.form.roles = this.user.roles;
      this.form.pronombre = this.user.pronombre;
      this.form.date_in = this.user.date_in;

      this.form.put(`/api/user-data-additonial/${this.user.id}`)
      .then(() => {
        this.$router.push(`/staff/${this.user.id}`)
        toast.fire({
          type: 'success',
          title: `Información de ${this.user.name} editada correctamente.`
        })
        this.$Progress.finish();
        this.loading = false;
      })
      .catch(() =>{
        toast.fire({
          type: 'success',
          title: `Información de ${this.user.name} editada correctamente.`
        })
        this.$Progress.fail();
        this.loading = false;
      });
      this.cancelar();

    },

    cancelar(){
      if (this.nuevo == true) {
        this.$router.push(`/staff`)
      }
      this.edit = false;
      this.container_class = 'container';
      this.nuevo = false;

    },

    activeSelect(){
      // :href="'/staff/edit/'+user.id"
      this.edit = true;
      this.container_class = 'container container-border-edit';
    },
    createUser(){
      this.loading = true;
      let user_files = this.form.files
      this.$Progress.start();
      this.form.personal = this.user.personal;
      this.form.jornada = this.user.jornada;
      this.form.hours_daily = this.user.hours_daily;
      this.form.supervisor_id = this.user.supervisor == null ? null :this.user.supervisor.id;
      this.form.supervisores = this.user.supervisores;
      this.form.avatar = this.user.avatar;
      this.form.id = this.user.id;
      this.form.role_time_id = this.user.role_time_id == false ? 0 : this.user.role_time == null ? 0 :this.user.role_time.id;
      this.form.role_radar_id = this.user.role_radar_id == false ? 0 : this.user.role_radar == null ? 0 :this.user.role_radar.id;
      this.form.active = this.user.active;
      this.form.role_id = this.user.role == false ? 0 : this.user.role == null ? 0 : this.user.role.id;
      this.form.name = this.user.name;
      this.form.position_id = this.user.position == null ? null :this.user.position.id;
      this.form.organizacion = this.user.organizacion == null ? '' : (this.user.organizacion).toString();
      this.form.email = this.user.email;
      this.form.bio_es = this.user.bio_es;
      this.form.bio_en = this.user.bio_en;
      this.form.password = this.user.password;
      this.form.pronombre = this.user.pronombre;
      this.form.date_in = this.user.date_in;
      this.form.roles = this.user.roles;
      // console.log(this.form, 'd');

      if (!this.user.name) {
        toast.fire({type: 'warning',title: 'Escriba un nombre'});
        this.loading = false;
      }else if (!this.user.personal) {
        toast.fire({type: 'warning',title: 'Seleccione el tipo de personal'});
        this.loading = false;
      }else if (!this.user.position) {
        toast.fire({type: 'warning',title: 'Seleccione el cargo del usuario'});
        this.loading = false;
      }else if (!this.user.supervisores) {
        toast.fire({type: 'warning',title: 'Seleccione el supervisor del usuario'});
        this.loading = false;
      }else if (!this.user.organizacion) {
        toast.fire({type: 'warning',title: 'Seleccione la organización del usuario'});
        this.loading = false;
      }else if (!this.user.email) {
        toast.fire({type: 'warning',title: 'Escriba el email del usuario'});
        this.loading = false;
      }else if (!this.user.jornada) {
        toast.fire({type: 'warning',title: 'Seleccione el tipo de jornada del usuario'});
        this.loading = false;
      }else if (!this.user.password) {
        toast.fire({type: 'warning',title: 'Escriba una contraseña'});
        this.loading = false;
      }else if (this.user.password && (this.user.password).length < 6) {
        toast.fire({type: 'warning',title: 'La contraseña debe contener al menos 6 caracteres'});
        this.loading = false;
      }else {


        this.form.post('/api/user')
        .then((response)=>{
          toast.fire({type: 'success',title: `${response.data.name} agregado/a correctamente.`})
          this.$router.push(`/staff/${response.data.id}`)
          this.$Progress.finish();
          this.cancelar();
          this.loading = false;
        })
        .catch(()=>{
          this.$Progress.fail();
          this.loading = false;
        });
      }
    },

  },
  created(){
    // (this.$route.path === '/profile') ? this.getActiveUser() : this.getUser();
    // Fire.$on('AfterCreate',() => {
    //   (this.$route.path === '/profile') ? this.getActiveUser() : this.getUser();
    // });
    this.getUser();
    this.getSupervisores();
    this.getProyectos();
    this.getRoles();
    this.getRolesTimes();
    this.getRolesRadar();
    this.getPositions();
  },
  // mounted(){
  // }
}
</script>

<style lang="scss" scoped>
.flex-orientation{
  @media (max-width: 767.98px) {
    flex-direction: column!important;
  }
}
.table-row{
  @media (max-width: 767.98px) {
    border: solid 1px #dee2e6;
    margin-bottom: 1.5em;
    box-shadow: 2px 2px 2px 0px #dee2e6;
    padding: 1em;
    &:hover{
      box-shadow: 5px 5px 5px 0px #dee2e6;
    }
  }
  @media (min-width: 768px) {
    border-bottom: 1px solid #dee2e6;
    &:hover{
      box-shadow: 2px 2px 2px 0px #dee2e6;
    }
  }
}
.table-head{
  border-bottom: 2px solid #dee2e6;
}

.switch {
  position: relative;
  display: inline-block;
  width: 45px;
  height: 20px;
}

.switch input {
  opacity: 0;
  width: 0;
  height: 0;
}

.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ccc;
  -webkit-transition: .4s;
  transition: .4s;
}

.slider:before {
  position: absolute;
  content: "";
  height: 15px;
  width: 15px;
  left: 1px;
  bottom: 2px;
  background-color: white;
  transition: 0.4s;
}

input:checked + .slider {
  background-color: #FFA000;
}

input:focus + .slider {
  box-shadow: 0 0 1px #2196F3;
}

input:checked + .slider:before {
  -webkit-transform: translateX(26px);
  -ms-transform: translateX(26px);
  transform: translateX(26px);
}

/* Rounded sliders */
.slider.round {
  border-radius: 12px;
}

.slider.round:before {
  border-radius: 50%;
}


/* disable */
.switch-disable {
  position: relative;
  display: inline-block;
  width: 45px;
  height: 20px;
}

.switch-disable input {
  opacity: 0;
  width: 0;
  height: 0;
}

.slider-disable {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ccc;
  -webkit-transition: .4s;
  transition: .4s;
}

.slider-disable:before {
  position: absolute;
  content: "";
  height: 15px;
  width: 15px;
  left: 1px;
  bottom: 2px;
  background-color: white;
  transition: 0.4s;
}

input:checked + .slider-disable {
  background-color: #BBBBBB;
}

input:focus + .slider-disable {
  box-shadow: 0 0 1px #2196F3;
}

input:checked + .slider-disable:before {
  -webkit-transform: translateX(26px);
  -ms-transform: translateX(26px);
  transform: translateX(26px);
}

/* Rounded sliders */
.slider-disable.round {
  border-radius: 12px;
}

.slider-disable.round:before {
  border-radius: 50%;
}

.container-border-edit {
  border : 1px solid;
  border-radius: 20px;
  background: #E2E8F0;
  border-color: #063772;
}

</style>
