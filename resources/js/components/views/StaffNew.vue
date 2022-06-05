<template>
  <div>

  <div class="grid-gral">
    <div class="uno-gdr">
      <span class="color-title" ><img src="/img/icono- equipo de trabajo.png" style="width : 2rem;">&nbsp;Equipo de trabajo</span>
    </div>
    <div class="dos-gdr">
      <div class="content-uno-gdr" :style="width_responsive">
        <div class="grid-buttons">

          <div class="inner-addon left-addon">
            <i class="glyphicon fas fa-search"></i>
            <input type="text" class="form-control" placeholder="Buscar colaboradora o colaborador" style="border-radius : 15px;" v-model="user_text" @keyup.enter="findUsers()"
            data-toggle="tooltip" data-placement="top" title="Presione tecla 'Enter' para buscar"/>
            <h5 class="mt-3 font-weight-bold" style="color : #F68E1D">{{ users.length }} colaboradoras y colaboradores encontrados</h5>
          </div>
          <div class="btns">
            <template v-if="$screen.width <= 768">
              <button class="btn btn-outline-orange float-right" @click="addClass()">
                <i class="fas fa-filter"></i>
              </button>
            </template>
            <button class="btn btn-outline-orange float-right font-weight-bold" @click="addNewUser()"><i class="fas fa-plus mr-1"></i> Agregar colaborador(a)</button>
          </div>

        </div>
        <b-table id="my-table"
        :per-page="perPage"
        :current-page="currentPage"
        responsive
        hover
        :sticky-header = "sizeTable"
        :head-variant="'light'"
        :fields="fields"
        @row-selected="onRowSelected"
        :select-mode="selectMode"
        selectable
        :busy="isBusy"
        :items="users">

        <template #table-busy>
      <div class="text-center text-success my-2">
        <b-spinner class="align-middle"></b-spinner>
        <strong>{{text_spinner}}</strong>
      </div>
    </template>

        <template #cell(avatar)="data">
          <div v-if="data.item.avatar" class="avatar-bg m-0 d-block" :style="{ 'background': 'url(' + data.item.avatar + ')' }"></div>
          <div v-else class="avatar-bg m-0 d-block" style="background-image: url('/img/user-circle-solid.svg');"></div>
        </template>
        <template #cell(name)="data">
          {{data.item.name}}
        </template>
        <template #cell(organizacion)="data">
          {{data.item.organizacion}}
        </template>
        <template #cell(email)="data">
          {{data.item.email}}
        </template>
        <template #cell(position)="data">
          <template v-if="data.item.position">
            {{data.item.position.name}}
          </template>
        </template>
        <template #cell(created_at)="data">
          {{ data.item.created_at | myDate }}
        </template>
        <template #cell(id)="data">
          <div class="btn-group w-100" role="group" aria-label="">
            <a class="btn" data-toggle="tooltip" data-placement="top" title="Cambiar estatus del usuario" @click.stop="changeStatus(data.item)">
              <i class="fas" :class="{ 'fa-toggle-off' : data.item.active == 0, 'fa-toggle-on text-orange' : data.item.active == 1 }"></i>
            </a>
            <a class="btn" :class="{ 'd-none' : data.item.active != 1 }" data-toggle="tooltip" data-placement="top" title="Editar colaborador" @click.stop="editUser(data.item.id)">
              <i class="fas fa-pencil-alt pointer"></i>
            </a>
            <a class="btn" :class="{ 'd-none' : data.item.active != 1 }" data-toggle="tooltip" data-placement="top" title="Eliminar colaborador" @click.stop="deleteUser(data.item)">
              <i class="far fa-trash-alt pointer"></i>
            </a>
          </div>
        </template>
      </b-table>
      <b-pagination
      v-model="currentPage"
      :total-rows="rows"
      :per-page="perPage"
      aria-controls="my-table"
      ></b-pagination>
      </div>
      <div class="content-dos-gdr" :style="display_resposive">
        <h4 style="color : #156CAB;"><b>Filtrar por:</b></h4>
        <div class="accordion-menu">
          <ul class="test">
            <li class="test-l">
              <input type="checkbox" class="test-c" checked>
              <i class="arrow"></i>
              <h2 class="title-accordion">Estatus</h2>
              <p class="testp">
                <ul style="padding-left: 1.4em !important;">
                  <li v-for="c in states">
                    <input class="form-check-input me-1" type="checkbox" @change="setDataFilters(c.id, 'estatus')">
                    {{c.name}}
                  </li>
                </ul>
              </p>
            </li>
            <li class="test-l">
              <input type="checkbox" class="test-c" checked>
              <i class="arrow"></i>
              <h2 class="title-accordion">Organización</h2>
              <p class="testp">
                <ul style="padding-left: 1.4em !important;">
                  <li v-for="o in organizacion">
                    <input class="form-check-input me-1" type="checkbox" @change="setDataFilters(o, 'org')">
                    {{o}}
                  </li>
                </ul>
              </p>
            </li>
            <li class="test-l">
              <input type="checkbox" class="test-c" checked>
              <i class="arrow"></i>
              <h2 class="title-accordion">Cargo</h2>
              <p class="testp">
                <ul style="padding-left: 1.4em !important;">
                  <li v-for="p in positions">
                    <input class="form-check-input me-1" type="checkbox" value="" aria-label="..." @change="setDataFilters(p.id, 'posiciones')">
                    {{p.name}}
                  </li>
                </ul>
              </p>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</div>

</template>

<script>
import VueSlider from 'vue-slider-component';
import 'vue-slider-component/theme/default.css'
import Loading from '../complements/Loading'
import ToogleStatus from '../user/ToogleStatus';

export default {
  components: {
    VueSlider, Loading
  },
  data: () => ({
    text_spinner : '',
    display_resposive : '',
    width_responsive : '',
    sizeTable : '550px',
    display_btn : 'display: none; max-width: 250px;',
    positions : [],
    loading: true,
    users : [],
    users_temporal : [],
    user_text: '',
    perPage: 20,
    currentPage: 1,
    fields: [
      {  key: 'avatar', label: ''},
      {  key: 'name',  label : 'Nombre' },
      {  key: 'organizacion', label : 'Organización'},
      {  key: 'email', label : 'Correo electrónico' },
      {  key: 'position', label : 'Posición' },
      {  key: 'date_in', label : 'Fecha de ingreso' },
      {  key: 'id', label : 'Acciones'}
    ],
    selectMode : 'single',
    states : [
      { id : 0, name : 'Inactivo'},
      { id : 1, name : 'Activo'}
    ],
    organizacion : ['C230','Fundación IDEA','Supernova'],

    filtersEstatus : [],
    filtersPosiciones : [],
    filtersOrganizacion : [],

    filtersData : [],

    isBusy: false,

  }),
  computed: {
    rows() {
      return this.users.length
    }
  },
  watch: {
        '$screen.width'() {
          if (this.$screen.width <= 768) {
            this.width_responsive = 'width : ' + (this.$screen.width - 50) +'px;';
            this.display_resposive = "display: none; max-width: 250px;";
          }else {
            this.width_responsive = '';
            this.display_resposive = "";
          }
        }
    },
  methods: {
    changeStatus(data){
      swal.fire({
        title:  data.active ? '¿Estás seguro de desactivar?' : '¿Activar?',
        text: "¡No podrás revertir esto!",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, ' + (data.active ? 'desactivar' : 'activar'),
        cancelButtonText: 'Cancelar'
      }).then((result) => {
        if (result.value) {
          axios.get('/api/active-desactive-user/' + data.id).then(response => {
            this.getUsers();
          }).catch(e => {
            console.log(e);
          });
        }
      });
    },

    deleteUser(data){
      swal.fire({
        title: '¿Estás seguro de desactivar?',
        text: "¡No podrás revertir esto!",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
      }).then((result) => {
        if (result.value) {
          axios.get('/api/delete-user/' + data.id).then(response => {
            this.getUsers();
          }).catch(e => {
            console.log(e);
          });
        }
      });
    },

    addClass() {
      if (this.display_resposive === 'display: none; max-width: 250px;') {
        this.display_resposive = "display: block; max-width: 250px; position:absolute; z-index : 2;top: 320px;";
      }else {
        this.display_resposive = "display: none; max-width: 250px;";
      }
    },

    getData(){
      if (this.$screen.width <= 768) {
        this.width_responsive = 'width : ' + (this.$screen.width - 50) +'px;';
        this.display_resposive = "display: none; max-width: 250px;";
      }else {
        this.width_responsive = '';
        this.display_resposive = '';
      }

      this.positions = [];
      axios.get('/api/position').then(response => {
        this.positions = response.data;
      }).catch(e => {
        console.error(e);
      });
    },

    addNewUser(){
      this.$router.push(`/staff/0`);
    },

    onRowSelected(items){
      this.editUser(items[0]['id']);
    },

    editUser(data){
      this.$router.push(`/staff/${data}`);
    },

    getUsers(){
      this.isBusy = true;
      this.text_spinner = 'Cargando...';
      axios.get("/api/user")
      .then(
        ({data}) => (
          this.isBusy = false,
          this.users = data,
          this.users_temporal = this.users

        )
      )
    },

    findUsers(){
      this.isBusy = true;
      this.text_spinner = 'Buscando...';
      if (this.user_text.length > 0) {
        axios.post('/api/find-users',{
          data : this.user_text
        }).then(response => {
          this.users = response.data;
          this.isBusy = false;
        }).catch(e => {
          this.isBusy = false;
          console.error(e);
        });
      }else if (this.user_text.length == 0) {
        this.users = [];
        this.users = this.users_temporal;
        this.isBusy = false;
        // this.users_temporal = [];
      }
    },

    setDataFilters(data, type){
      if (event.target.checked == true) {
        if (type === 'estatus') {
          this.filtersEstatus.push(data);
        }else if (type === 'posiciones') {
          this.filtersPosiciones.push(data);
        }else if (type === 'org') {
          this.filtersOrganizacion.push(data);
        }
      }else if (event.target.checked == false) {
        this.removeItemFromArrFilters(data, type);
      }
      this.constArray();

    },

    constArray(){
      this.filtersData =[{
        'estatus' : this.filtersEstatus,
        'posiciones' : this.filtersPosiciones,
        'org' : this.filtersOrganizacion,
      }];

      // this.getWithFilters();
      axios.post('/api/get-with-filters-users',{
        data : this.filtersData,
        // text : this.user_text,
      }).then(response => {
        this.users = response.data;
      }).catch(e => {
        console.error(e);
      });
    },

    removeItemFromArrFilters (item, type ) {
      if (type === 'estatus') {
        var i = this.filtersEstatus.indexOf( item );
        if ( i !== -1 ) {
          this.filtersEstatus.splice( i, 1 );
        }
      }else if (type === 'posiciones') {
        var i = this.filtersPosiciones.indexOf( item );
        if ( i !== -1 ) {
          this.filtersPosiciones.splice( i, 1 );
        }
      }else if (type === 'org') {
        var i = this.filtersOrganizacion.indexOf( item );
        if ( i !== -1 ) {
          this.filtersOrganizacion.splice( i, 1 );
        }
      }
    },

  },
  created(){
    this.getData();
    this.getUsers();
  }
}
</script>
<style>
/* enable absolute positioning */
.inner-addon {
  position: relative;
}
/* style icon */
.inner-addon .glyphicon {
  position: absolute;
  padding: 10px;
  pointer-events: none;
}
/* align icon */
.left-addon .glyphicon  { left:  0px;}
/* add padding  */
.left-addon input  { padding-left:  30px; }

.adittional { padding-left: 0em !important;}

.list-group-item-one {
  background-color: #E2E8F0;
}
.list-group-item {
  /* position: relative; */
  /* display: block; */
  padding: 0.1rem 1.25rem !important;
  /* background-color: #ffff; */
  border: none !important;
}

</style>
<style >
.wrapper{
  width:70%;
}
@media(max-width:992px){
  .wrapper{
    width:100%;
  }
}
.panel-heading {
  padding: 0;
  border:0;
}

.panel-title>a, .panel-title>a:active{
  display:block;
  padding:15px;
  color:#555;
  font-size:16px;
  font-weight:bold;
  text-transform:uppercase;
  letter-spacing:1px;
  word-spacing:3px;
  text-decoration:none;
}
.panel-heading  a:before {
  font-family: 'Glyphicons Halflings';
  content: "\e114";
  float: right;
  transition: all 0.5s;
}
.panel-heading.active a:before {
  -webkit-transform: rotate(180deg);
  -moz-transform: rotate(180deg);
  transform: rotate(180deg);
}


</style>
