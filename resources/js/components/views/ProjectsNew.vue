<template>

  <div>

    <!-- <div class="grid-gral" v-show="new_project == false"> -->
    <div class="grid-gral">
      <div class="uno-gdr">
        <span class="color-title" ><img src="/img/icono-proyectos.png" style="width : 2rem;">&nbsp;Proyectos</span>
      </div>
      <div class="dos-gdr">

        <div class="content-uno-gdr" :style="width_responsive">
          <div class="grid-buttons">

            <div class="inner-addon left-addon">
              <i class="glyphicon fas fa-search"></i>
              <input type="text" class="form-control" placeholder="Buscar proyecto"  style="border-radius : 15px;" v-model="projects_text" @keyup.enter="findProjects()"
              data-toggle="tooltip" data-placement="top" title="Presione tecla 'Enter' para buscar"/>
              <h5 class="mt-3 font-weight-bold" style="color : #F68E1D">{{ projects.length }} proyectos encontrados</h5>
            </div>
            <div class="btns">
              <template v-if="$screen.width <= 768">
                <button class="btn btn-outline-orange float-right" @click="addClass()">
                  <i class="fas fa-filter"></i>
                </button>
              </template>
              <button class="btn btn-outline-orange float-right font-weight-bold" @click="addNewProject()"><i class="fas fa-plus mr-1"></i> Agregar proyecto</button>
              <button class="btn btn-orange float-right" @click.stop="downloadSelectedProjects" v-if="projectSelect.length > 0">
                <i
                class="fas fa-download"
                data-toggle="tooltip"
                data-placement="bottom"
                title="Descargar proyectos seleccionados"
                ></i>&nbsp;Descargar tabla de experiencia
              </button>
            </div>

          </div>

          <!-- Vue table -->

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
          :busy="isBusy"
          selectable
          :items="projects">

          <template #table-busy>
        <div class="text-center text-success my-2">
          <b-spinner class="align-middle"></b-spinner>
          <strong>{{text_spinner}}</strong>
        </div>
      </template>

          <template #cell(code)="data">
            {{ data.item[0].code}}
          </template>
          <template #cell([0].id)="data">
            <input type="checkbox" @change="setProjects(data.item[0].id)">
          </template>
          <template #cell(name_es)="data">
            {{data.item[0].name_es}}
          </template>
          <template #cell(clients)="data">
            {{data.item.clients}}
          </template>
          <template #cell(year)="data">
                {{data.item.year}}
          </template>
          <template #cell(folio)="data">
            <div class="btn-group w-100" role="group" aria-label="">
              <a class="btn pointer" @click.stop="openNewTab(data.item[0].id)" data-toggle="tooltip" data-placement="top" title="Abrir proyecto en nueva pestaña">
                <i class="fas fa-external-link-alt"></i>
              </a>
              <!-- v-if="$gate.activeUser().role_id == 1 || $gate.activeUser().role_id == 2 || $gate.activeUser().role_id == 3 || $gate.activeUser().id == item.user_id"  -->
              <a @click.stop="editProject(data.item[0].id)" class="btn" data-toggle="tooltip" data-placement="top" title="Editar proyecto">
                <i class="fas fa-pencil-alt pointer"></i>
              </a>
              <!-- v-if="$gate.activeUser().role_id == 1 || $gate.activeUser().role_id == 2"  -->
              <button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="Eliminar proyecto" @click.stop="deleteProject(data.item[0].id)">
                <i class="far fa-trash-alt pointer"></i>
              </button>
            </div>
          </template>



        </b-table>
        <b-pagination
        v-model="currentPage"
        :total-rows="rows"
        :per-page="perPage"
        aria-controls="my-table"
        ></b-pagination>

          <!-- /table -->

        </div>
        <div class="content-dos-gdr" :style="display_resposive">
          <!-- class="btnn"  :style="display_btn" -->
          <!-- <div style="" > -->
            <h4 style="color : #156CAB;"><b>Filtrar por:</b></h4>
            <div class="accordion-menu">
              <ul class="test">
                <li class="test-l">
                  <input type="checkbox" class="test-c" checked>
                  <i class="arrow"></i>
                  <h2 class="title-accordion">Organización</h2>
                  <p class="testp">
                    <ul style="padding-left: 1.4em !important;">
                      <li v-for="c in companies">
                        <input class="form-check-input me-1" type="checkbox" @change="setDataFilters(c.id, 'organizacion')">
                        {{c.name}}
                      </li>
                    </ul>
                  </p>
                </li>
                <li class="test-l">
                  <input type="checkbox" class="test-c" checked>
                  <i class="arrow"></i>
                  <h2 class="title-accordion">Tiempo</h2>
                  <p class="testp">
                    <ul style="padding-left: 1.4em !important;">
                      <div class="input-group mb-3">
                        <select class="form-control" v-model="fecha_uno">
                          <option value="01">Enero</option>
                          <option value="02">Febrero</option>
                          <option value="03">Marzo</option>
                          <option value="04">Abril</option>
                          <option value="05">Mayo</option>
                          <option value="06">Junio</option>
                          <option value="07">Julio</option>
                          <option value="08">Agosto</option>
                          <option value="09">Septiembre</option>
                          <option value="10">Octubre</option>
                          <option value="11">Noviembre</option>
                          <option value="12">Diciembre</option>
                        </select>
                        <input type="text" class="form-control" placeholder="Año" aria-label="Año" v-model="fecha_dos" @input="changeYear('start')">

                      </div>
                      <div class="input-group mb-2">
                        <input type="text" class="form-control" disabled style="background: transparent; border : none;">
                        <span class="input-group-text">Al</span>
                        <input type="text" class="form-control" disabled style="background: transparent; border : none;">
                      </div>
                      <div class="input-group mb-3">
                        <select class="form-control" v-model="fecha_tres">
                          <option value="01">Enero</option>
                          <option value="02">Febrero</option>
                          <option value="03">Marzo</option>
                          <option value="04">Abril</option>
                          <option value="05">Mayo</option>
                          <option value="06">Junio</option>
                          <option value="07">Julio</option>
                          <option value="08">Agosto</option>
                          <option value="09">Septiembre</option>
                          <option value="10">Octubre</option>
                          <option value="11">Noviembre</option>
                          <option value="12">Diciembre</option>
                        </select>
                        <input type="text" class="form-control" placeholder="Año" aria-label="Año" v-model="fecha_cuatro" @input="changeYear('end')">
                      </div>
                    </ul>
                  </p>
                </li>
                <li class="test-l">
                  <input type="checkbox" class="test-c" checked>
                  <i class="arrow"></i>
                  <h2 class="title-accordion">Clientes</h2>
                  <p class="testp">
                    <ul style="padding-left: 1.4em !important;">
                      <div class="input-group-prepend">
                        <span class="input-group-text search-icon" id="basic-addon1">
                          <i class="fas fa-search text-secondary"></i>
                        </span>
                        <input type="text" class="form-control search-input" placeholder="Buscar..." @input="fetchFilters($event.currentTarget.value, 'clientes')">
                        <span class="input-group-text loading-icon" id="basic-addon1">
                        </span>
                      </div>
                    </ul>

                      <ul style="padding-left: 1.4em !important;">
                        <!-- <div style="height: 40rem;overflow: scroll;"> -->
                        <li v-for="item in stackeholders">
                          <input class="form-check-input me-1" type="checkbox"  @change="setDataFilters(item.id, 'clientes')">
                          {{item.name}}
                        </li>
                      <!-- </div> -->
                      </ul>
                  </p>
                </li>
                <li class="test-l">
                  <input type="checkbox" class="test-c" checked>
                  <i class="arrow"></i>
                  <h2 class="title-accordion">Tipos de proyecto</h2>
                  <p class="testp">
                    <ul style="padding-left: 1.4em !important;">
                      <div class="input-group-prepend">
                        <span class="input-group-text search-icon" id="basic-addon1">
                          <i class="fas fa-search text-secondary"></i>
                        </span>
                        <input type="text" class="form-control search-input" placeholder="Buscar..." @input="fetchFilters($event.currentTarget.value, 'tipos')">
                        <span class="input-group-text loading-icon" id="basic-addon1">
                        </span>
                      </div>
                      <ul>
                        <li v-for="item in tipos">
                          <input class="form-check-input me-1" type="checkbox" @change="setDataFilters(item.id, 'tipos')">
                          {{item.name}}
                        </li>
                      </ul>
                    </ul>
                  </p>
                </li>
                <li class="test-l">
                  <input type="checkbox" class="test-c" checked>
                  <i class="arrow"></i>
                  <h2 class="title-accordion">Análisis, metodologías e implementación</h2>
                  <p class="testp">
                    <ul style="padding-left: 1.4em !important;">
                      <div class="input-group-prepend">
                        <span class="input-group-text search-icon" id="basic-addon1">
                          <i class="fas fa-search text-secondary"></i>
                        </span>
                        <input type="text" class="form-control search-input" placeholder="Buscar..." @input="fetchFilters($event.currentTarget.value, 'metodologias')">
                        <span class="input-group-text loading-icon" id="basic-addon1">
                        </span>
                      </div>
                      <ul>
                        <li v-for="item in metodologias">
                          <input class="form-check-input me-1" type="checkbox" @change="setDataFilters(item.id, 'metodologias')">
                          {{item.name}}
                        </li>
                      </ul>
                    </ul>
                  </p>
                </li>
                <li class="test-l">
                  <input type="checkbox" class="test-c" checked>
                  <i class="arrow"></i>
                  <h2 class="title-accordion">Temáticas / Enfoque / Población objetivo</h2>
                  <p class="testp">
                    <ul style="padding-left: 1.4em !important;">
                      <div class="input-group-prepend">
                        <span class="input-group-text search-icon" id="basic-addon1">
                          <i class="fas fa-search text-secondary"></i>
                        </span>
                        <input type="text" class="form-control search-input" placeholder="Buscar..." @input="fetchFilters($event.currentTarget.value, 'tematicas')">
                        <span class="input-group-text loading-icon" id="basic-addon1">
                        </span>
                      </div>
                      <ul>
                        <li v-for="item in tematicas" >
                          <input class="form-check-input me-1" type="checkbox" @change="setDataFilters(item.id, 'tematicas')">
                          {{item.name}}
                        </li>
                      </ul>
                    </ul>
                  </p>
                </li>
                <li class="test-l">
                  <input type="checkbox" class="test-c" checked>
                  <i class="arrow"></i>
                  <h2 class="title-accordion">Colaboradores</h2>
                  <p class="testp">
                    <ul style="padding-left: 1.4em !important;">
                      <div class="input-group-prepend">
                        <span class="input-group-text search-icon" id="basic-addon1">
                          <i class="fas fa-search text-secondary"></i>
                        </span>
                        <input type="text" class="form-control search-input" placeholder="Buscar..." @input="fetchFilters($event.currentTarget.value, 'colaboradores')">
                        <span class="input-group-text loading-icon" id="basic-addon1">
                        </span>
                      </div>
                      <ul>
                        <li v-for="item in colaboradores">
                          <input class="form-check-input me-1" type="checkbox" @change="setDataFilters(item.id, 'colaboradores')">
                          {{item.name}}
                        </li>
                      </ul>
                    </ul>
                  </p>
                </li>
                <li class="test-l">
                  <input type="checkbox" class="test-c" checked>
                  <i class="arrow"></i>
                  <h2 class="title-accordion">Ubicaciones geográficas</h2>
                  <p class="testp">
                    <ul style="padding-left: 1.4em !important;">
                      <div class="input-group-prepend">
                        <span class="input-group-text search-icon" id="basic-addon1">
                          <i class="fas fa-search text-secondary"></i>
                        </span>
                        <input type="text" class="form-control search-input" placeholder="Buscar..." @input="fetchFilters($event.currentTarget.value, 'ubicaciones')">
                        <span class="input-group-text loading-icon" id="basic-addon1">
                        </span>
                      </div>
                      <ul>
                        <li v-for="item in ubicaciones">
                          <input class="form-check-input me-1" type="checkbox" @change="setDataFilters(item.id, 'ubicaciones')">
                          {{item.name}}
                        </li>
                      </ul>
                    </ul>
                  </p>
                </li>

              </ul>
            </div>
          <!-- </div> -->

        </div>
      </div>

    </div>

    <!-- end new conten -->

    <!-- <new-project v-show="new_project"/> -->

</div>


</template>

<script>
import VueSlider from 'vue-slider-component';
import 'vue-slider-component/theme/default.css'
import Loading from '../complements/Loading'
import NewProject from '../views/ProjectNew'

export default {
  components: {
    VueSlider, Loading, 'new-project' : NewProject
  },
  data: () => ({
    new_project : false,
    text_spinner : '',

    display_resposive : '',
    width_responsive : '',
    sizeTable : '600px',
    display_btn : 'display: none; max-width: 250px;',
    class_button : '',
    active_section : 1,
    loading: true,
    projects: [],
    projects_text: '',
    perPage: 20,
    currentPage: 1,
    fields: [
      {  key: '[0].id', label : '' },
      {  key: 'code',  label : 'Código' },
      {  key: 'name_es',  label : 'Nombre' },
      {  key: 'clients',  label : 'Clientes' },
      {  key: 'year',  label : 'Año(s)' },
      {  key: 'folio', label : 'Acciones'}

    ],
    projectSelect : [],
    companies : [],
    stackeholders : [],
    tipos : [],
    metodologias : [],
    tematicas : [],
    colaboradores : [],
    ubicaciones : [],
    hover : '',

    filtersOrganizacion : [],
    filtersClients : [],
    filtersTipos : [],
    filtersMetodologias : [],
    filtersTematicas : [],
    filtersColaboradores : [],
    filtersUbicaciones : [],

    filtersData : [],

    fecha_uno : '',
    fecha_dos : '',
    fecha_tres : '',
    fecha_cuatro : '',

    start : '',
    end : '',

    selectMode : 'single',

    isBusy : false,
  }),
  computed: {
    rows() {
      return this.projects.length
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
    onRowSelected(items){
      // console.log(items[0][0]['id']);
      this.editProject(items[0][0]['id']);
    },

    addClass() {
      if (this.display_resposive === 'display: none; max-width: 250px;') {
        this.display_resposive = "display: block; max-width: 250px; position:absolute; z-index : 2;";
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
      axios.get('/api/company').then(response => {
        this.companies = response.data;
      }).catch(e => {
        console.error(e);
      });

      axios.get('/api/type').then(response => {
        this.tipos = response.data;
      }).catch(e => {
        console.error(e);
      });

      axios.get('/api/methodology').then(response => {
        this.metodologias = response.data;
      }).catch(e => {
        console.error(e);
      });

      axios.get('/api/topic').then(response => {
        this.tematicas = response.data;
      }).catch(e => {
        console.error(e);
      });

      axios.get('/api/stackeholder').then(response => {
        this.stackeholders = response.data;
      }).catch(e => {
        console.error(e);
      });

      axios.get('/api/location').then(response => {
        this.ubicaciones = response.data;
      }).catch(e => {
        console.error(e);
      });

    },

    getProjects(){
      this.isBusy = true;
      this.text_spinner = 'Cargando...';
      axios.get("/api/get-projects").then(response => {
        this.projects = response.data;
        this.isBusy = false;
      });
    },

    addNewProject(){
      // this.new_project = true;
      // this.$router.push({ name: 'projects/new' })
      this.$router.push({ name: 'project', params: { id: 0, s_active: this.active_section } })
    },

    fullName(value) {
      return `${value.first} ${value.last}`
    },

    setProjects(data){
      if (event.target.checked == true) {
        this.projectSelect.push(data);
      }else if (event.target.checked == false) {
        this.removeItemFromArr(data);
      }
    },

    removeItemFromArr (  item ) {
      var i = this.projectSelect.indexOf( item );

      if ( i !== -1 ) {
        this.projectSelect.splice( i, 1 );
      }
    },

    openNewTab(id){
      //let routeData = this.$router.push({ name: 'project', params: { id: project.objectID, s_active: this.active_section }  })
      window.open(`/projects/${id}`, '_blank');
    },

    editProject(data){
      this.$router.push({ name: 'project', params: { id: data, s_active: 1 } })
    },

    deleteProject(project){
      swal.fire({
        title: '¿Estás seguro?',
        text: "¡No podrás revertir esto!",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, eliminar projecto',
        cancelButtonText: 'Cancelar'
      }).then((result) => {
        if (result.value) {
          axios.get(`api/delete-project/${project}`).then(()=>{
            swal.fire({
              title:'Eliminado',
              text:'El projecto ha sido eliminado.',
              type:'success'
            }).then((result) => {
              if(result.value){
                location.reload();
              }
            })
          }).catch(()=> {
            swal("Error", "Algo salió mal", "warning");
          })

        }
      })
    },

    downloadSelectedProjects(){
      axios({
        method: 'put',
        url: '/api/download-project',
        data: {
          projects: this.projectSelect
        },
        responseType: 'blob', // important
      }).then((response) => {
        // this.$Progress.finish();
        const url = window.URL.createObjectURL(new Blob([response.data]));
        const link = document.createElement('a');
        link.href = url;
        link.setAttribute('download', 'projects.xlsx');
        document.body.appendChild(link);
        link.click();
        toast.fire({
          type: 'success',
          title: `Proyectos descargados correctamente.`
        })
      });
    },

    findProjects(){
      this.isBusy = true;
      if (this.projects_text.length > 1) {
        // this.projects = [];
        axios.post('/api/find-project',{
          data : this.projects_text
        }).then(response => {
          this.projects = response.data;
          this.isBusy = false;
        }).catch(e => {
          this.isBusy = false;
          console.error(e);
        });
      }else if (this.projects_text.length == 0) {
        this.getProjects();
        this.isBusy = false;
      }
    },

    /**
    * Triggered when the search text changes.
    *
    * @param search  {String}    Current search text
    * @param loading {Function}	Toggle loading class
    */
    fetchFilters (search, type) {
      if (type === 'clientes') {
        if (search.length > 1) {
          this.stackeholders = [];
          this.filtersClients = [];
          this.getFilters(search, 'clients');
        }else if (search.length == 0) {
          this.stackeholders = [];
        }
      }else if (type === 'tipos') {
        if (search.length > 1) {
          this.tipos = [];
          this.filtersTipos = [];
          this.getFilters(search, 'tipos');
        }else if (search.length == 0) {
          this.tipos = [];
        }
      }else if (type === 'metodologias') {
        if (search.length > 1) {
          this.metodologias = [];
          this.filtersMetodologias = [];
          this.getFilters(search, 'metodologias');
        }else if (search.length == 0) {
          this.metodologias = [];
        }
      }else if (type === 'tematicas') {
        if (search.length > 1) {
          this.tematicas = [];
          this.filtersTematicas = [];
          this.getFilters(search, 'tematicas');
        }else if (search.length == 0) {
          this.tematicas = [];
        }
      }else if (type === 'colaboradores') {
        if (search.length > 1) {
          this.colaboradores = [];
          this.filtersColaboradores = [];
          this.getFilters(search, 'colaboradores');
        }else if (search.length == 0) {
          this.colaboradores = [];
        }
      }else if (type === 'ubicaciones') {
        if (search.length > 1) {
          this.ubicaciones = [];
          this.filtersUbicaciones = [];
          this.getFilters(search, 'ubicaciones');
        }else if (search.length == 0) {
          this.ubicaciones = [];
        }
      }
      this.constArray();
    },

    getFilters(search, types){
      var url = '/api/find-filters';
      axios.post(url,{
        data : search,
        type : types
      }).then(response => {
        if (types === 'clients') {
          this.stackeholders = response.data;
        }else if (types === 'tipos') {
          this.tipos = response.data;
        }else if (types === 'metodologias') {
          this.metodologias = response.data;
        }else if (types === 'tematicas') {
          this.tematicas = response.data;
        }else if (types === 'colaboradores') {
          this.colaboradores = response.data;
        }else if (types === 'ubicaciones') {
          this.ubicaciones = response.data;
        }
      }).catch(e => {
        console.error(e);
      });
    },

    setDataFilters(data, type){

      if (event.target.checked == true) {
        if (type === 'organizacion') {
          this.filtersOrganizacion.push(data);
        }else if (type === 'clientes') {
          this.filtersClients.push(data);
        }else if (type === 'tipos') {
          this.filtersTipos.push(data);
        }else if (type === 'metodologias') {
          this.filtersMetodologias.push(data);
        }else if (type === 'tematicas') {
          this.filtersTematicas.push(data);
        }else if (type === 'colaboradores') {
          this.filtersColaboradores.push(data);
        }else if (type === 'ubicaciones') {
          this.filtersUbicaciones.push(data);
        }
      }else if (event.target.checked == false) {
        this.removeItemFromArrFilters(data, type);
      }
      this.constArray();

    },

    constArray(){
      this.filtersData =[{
        'organizacion' : this.filtersOrganizacion,
        'clientes' : this.filtersClients,
        'tipos' : this.filtersTipos,
        'metodologias' : this.filtersMetodologias,
        'tematicas' : this.filtersTematicas,
        'colaboradores' : this.filtersColaboradores,
        'ubicaciones' : this.filtersUbicaciones,
        'start' : this.start,
        'end' : this.end,
      }];

      // this.getWithFilters();
      axios.post('/api/get-with-filters',{
        data : this.filtersData,
      }).then(response => {
        this.projects = response.data;
      }).catch(e => {
        console.error(e);
      });
    },

    removeItemFromArrFilters (item, type ) {
      if (type === 'organizacion') {
        var i = this.filtersOrganizacion.indexOf( item );
        if ( i !== -1 ) {
          this.filtersOrganizacion.splice( i, 1 );
        }
      }else if (type === 'clientes') {
        var i = this.filtersClients.indexOf( item );
        if ( i !== -1 ) {
          this.filtersClients.splice( i, 1 );
        }
      }else if (type === 'tipos') {
        var i = this.filtersTipos.indexOf( item );
        if ( i !== -1 ) {
          this.filtersTipos.splice( i, 1 );
        }
      }else if (type === 'metodologias') {
        var i = this.filtersMetodologias.indexOf( item );
        if ( i !== -1 ) {
          this.filtersMetodologias.splice( i, 1 );
        }
      }else if (type === 'tematicas') {
        var i = this.filtersTematicas.indexOf( item );
        if ( i !== -1 ) {
          this.filtersTematicas.splice( i, 1 );
        }
      }else if (type === 'colaboradores') {
        var i = this.filtersColaboradores.indexOf( item );
        if ( i !== -1 ) {
          this.filtersColaboradores.splice( i, 1 );
        }
      }else if (type === 'ubicaciones') {
        var i = this.filtersUbicaciones.indexOf( item );
        if ( i !== -1 ) {
          this.filtersUbicaciones.splice( i, 1 );
        }
      }
    },

    changeYear(time){
      this.constArray();

      if (time === 'start') {
        if (this.fecha_dos.length > 3) {
          this.start = this.fecha_dos + '-' + this.fecha_uno + '-01';
          this.constArray();
        }else {
          this.start = '';
          this.constArray();
        }
      }
      if (time === 'end') {
        if (this.fecha_cuatro.length > 3) {
          this.end = this.fecha_cuatro + '-' + this.fecha_tres + '-28';
          this.constArray();
        }else {
          this.end = '';
          this.constArray();
        }
      }
    }

  },
  created(){
    this.getProjects();
    this.getData();
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

.botonF1{
  right:0;
  bottom:0;
  /* position:absolute; */
  /* margin-right:16px; */
  /* margin-bottom:16px; */
  transition:.3s;
}

.btnn{
  /* width:40px; */
  /* height:40px; */
  /* border-radius:100%; */
  /* border:none; */
  /* color:#FFF; */
  /* box-shadow: 0 3px 6px rgba(0,0,0,0.16), 0 3px 6px rgba(0,0,0,0.23); */
  /* font-size:28px; */
  /* outline:none; */
  position:absolute;
  left:12px;
  z-index: 2;
  /* bottom:0; */
  /* margin-right:26px; */
}




</style>
