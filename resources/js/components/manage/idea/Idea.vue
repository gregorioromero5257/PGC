<template>
  <div class="companies">
    <Loading v-if="loading" />
    <div class="row justify-content-md-center" v-else>
      <div class="col-md-10">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><router-link class="text-orange" to="/home">Inicio</router-link></li>
            <li class="breadcrumb-item"><router-link class="text-orange" to="/manage/home">Administración</router-link></li>
            <li class="breadcrumb-item active" aria-current="page">Publicación web</li>
          </ol>
        </nav>
      </div>
      <div class="col-md-10">
        <h1 class="h2 text-center font-weight-bold">Publicaciones IDEA</h1>
        <button type="button" class="btn btn-outline-orange float-right font-weight-bold pointer" @click="newModal">
          <i class="fas fa-plus mr-1"></i>
          Agregar
        </button>
      </div>
      <div class="col-md-10 mt-3">
        <b-table id="my-table"
        :per-page="perPage"
        :current-page="currentPage"
        responsive
        hover
        :sticky-header = "sizeTable"
        :head-variant="'light'"
        :fields="fields"
        :select-mode="selectMode"
        selectable
        :items="codes">
        <!-- @row-selected="onRowSelected" -->




        <template #cell(title_es)="data">
          {{ data.item.title_es}}
        </template>

        <template #cell(public)="data">

          <template v-if="data.item.public == 'true'">
            <span class="badge badge-info">Publicación</span>
          </template>
          <template v-if="data.item.public == 'false'">
            <span class="badge badge-success">Prensa</span>
          </template>
          <template v-if="data.item.public == '' || data.item.public == null">
            <span class="badge badge-danger">No activo</span>
          </template>
        </template>

        <template #cell(id)="data">
          <div class="btn-group w-100" role="group" aria-label="">

            <a @click.stop="editModal(data.item)" class="btn" data-toggle="tooltip" data-placement="top" title="Editar">
              <i class="fas fa-pencil-alt pointer"></i>
            </a>
            <button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="Eliminar" @click.stop="deleteCompany(data.item.id)">
              <i class="far fa-trash-alt pointer"></i>
            </button>
            <button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="Agregar recursos" @click.stop="AddResource(data.item)">
              <i class="fas fa-archive pointer"></i>
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


    </div>
  </div>
  <div class="modal fade" id="Modal" tabindex="-1" role="dialog" aria-labelledby="ModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
      <div class="modal-content ">
        <div class="modal-header">
          <h5 class="modal-title" id="ModalTitle" v-show="!editmode">Agregar</h5>
          <h5 class="modal-title" id="ModalTitle" v-show="editmode">Editar</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body text-center" v-show="loading_modal == true">
            <p>
                <i class="fas fa-spinner fa-pulse fa-4x"></i>
            </p>
            <p class="h4">Guardando...</p>
        </div>
        <div class="modal-body" v-show="loading_modal == false">
          <div class="form-group">
          </div>

          <div class="form-inline" v-if="form.thumbnail != '' && editmode">
            <div class="form-group mb-2">
              <label >Imagen</label>
            </div>
            <div class="form-group mx-sm-3 mb-2">
              <a target="_blank" :href="'https://fundacionidea.org.mx/storage/thumbnails/' + form.thumbnail">{{form.thumbnail}}</a>
            </div>
            <button type="button" @click="form.thumbnail =''" class="btn btn-danger mb-2">Eliminar</button>
          </div>

          <div class="form-group" v-if="form.thumbnail == '' || !editmode">
            <label for="exampleFormControlFile1">Agregar imagen</label>
            <input type="file" id="files_web" accept="image/x-png,image/gif,image/jpeg"
            class="form-control" @change="onFileWIChange">
          </div>
          <div class="form-group">
            <label >Título</label>
            <textarea class="form-control" v-model="form.title_es" rows="3"></textarea>
          </div>
          <div class="form-row">
            <div class="form-group col-md-3">
              <label for="inputCity">Fecha de publicación</label>
              <input type="date" v-model="form.publication_date" class="form-control" id="inputCity">
            </div>
            <div class="form-group col-md-3">
              <label for="inputState">Tipo</label>
              <select id="inputState" class="form-control" v-model="form.public">
                <option selected>Seleccione...</option>
                <option value="true">Publicación</option>
                <option value="false">Prensa</option>
                <option value="">No activo</option>
              </select>
            </div>
            <div class="form-group col-md-6">
              <label>Temática(s)</label>
              <v-select multiple :options="tematics_web" label="name" v-model="form.tags" ></v-select>
            </div>
          </div>
          <div class="form-group">
            <label >Contenido</label>
            <vue-editor v-model="form.description_es"  ></vue-editor>
          </div>
          <div class="form-group" v-show="editmode">
            <label for="name">Proyecto vinculado</label>
            <v-select
            v-model="form.link_pgc"
            :options="filteredProyectos"
            placeholder= "Seleccione una opción"
            label="name_es">
          </v-select>
          </div>
          <div class="modal-footer">
            <div class="form-group text-center">

              <button type="button" class="btn btn-orange btn-rounded" @click="createCompany()" v-show="!editmode">Guardar</button>
              <button type="button" class="btn btn-orange btn-rounded" @click="updateCompany()" v-show="editmode">Editar</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="ModalFiles" tabindex="-1" role="dialog" aria-labelledby="ModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
      <div class="modal-content ">
        <div class="modal-header">
          <h5 class="modal-title" id="ModalTitle" >Agregar Recursos <br> {{publication_name}} </h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div v-show="loading_modal == false">
          <div class="modal-body">
            <div v-show="!file">
              <table class="table table-hover table-responsive">
                <thead>
                  <tr>
                    <th scope="col">&nbsp;</th>
                    <th scope="col">Archivo</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="t in resources" >
                    <th scope="row">
                      <div class="btn-group w-100" role="group" aria-label="">
                        <a type="button" class="btn" data-toggle="tooltip" data-placement="top" title="Eliminar" @click.stop="deleteResource(t.id, t.table)">
                          <i class="far fa-trash-alt pointer"></i>
                        </a>

                        <a type="button" target="_blank" class="btn" data-toggle="tooltip" data-placement="top" title="Vista previa"
                        :href="'https://fundacionidea.org.mx/storage/IDEA/files/'+t.file"
                        >
                        <i class="fas fa-file-export"></i>
                      </a>

                    </div>
                  </th>
                  <td>{{t.file}}</td>
                </tr>
              </tbody>
            </table>
          </div>
          <div v-show="file">
            <div class="form-group">
              <label >Agregar recurso</label>
              <input type="file" id="files_web_resource" accept="application/pdf, application/vnd.ms-excel"
              class="form-control" @change="onFileChangePdf">
            </div>
          </div>


        </div>
        <div class="modal-footer">
          <div class="form-group text-center">
            <button type="button" class="btn btn-orange btn-rounded" @click="file = true" v-show="!file">Agregar</button>
            <button type="button" class="btn btn-orange btn-rounded" @click="saveResource()" v-show="file">Guardar</button>
            <button type="button" class="btn btn-orange btn-rounded" @click="cancelarResorce()" v-show="file">Cancelar</button>
          </div>
        </div>
        </div>
        <div class="modal-body text-center" v-show="loading_modal == true">
          <p>
              <i class="fas fa-spinner fa-pulse fa-4x"></i>
          </p>
          <p class="h4">Guardando...</p>
        </div>
  </div>
</div>

</div>
</div>
</template>

<script>
import { VueEditor } from "vue2-editor";
import Loading from '../../complements/Loading'
export default {
  components: {
    Loading, VueEditor
  },
  data: () => ({
    url : 'https://fundacionidea.org.mx/api/webcontent',
    // url : 'http://127.0.0.1:8000/api/webcontent',
    loading: false,
    editmode: false,
    codes: [],
    filteredProyectos : [],
    perPage: 8,
    currentPage: 1,
    sizeTable : '600px',
    fields: [
      {  key: 'title_es',  label : 'Título' },
      {  key: 'publication_date', label: 'Fecha publicación'},
      {  key: 'public', label: 'Estatus'},
      {  key: 'id', label: 'Acciones'}
    ],
    selectMode : 'single',

    categorias : ['Desarrollo de clientes','No laborado','Overhead','Ovearhead','Proyectos','Administración de proyectos'],
    organizaciones : ['C230 Consulting','C230 Colombia','C230 Méx','IDEA','Supernova'],
    form: '',
    file_img : '',
    file_pdf : '',
    tematics_web : [],
    tematics_web_complete : [],
    resources : [],
    publication_id : '',
    publication_name : '',
    file : false,
    datas : '',
    loading_modal : false,
  }),
  computed: {
    rows() {
      return this.codes.length
    }
  },
  methods: {


    getData(){
      axios.get('/api/tematics-idea/').then(response => {
            this.tematics_web = response.data;
      });
      axios.get("/api/project-idea")
      .then(
        ({data}) => (
          this.filteredProyectos = data
        )
      );
    },

    getCodes(){
      this.loading = true;
      axios.get(this.url)
      .then(
        ({data}) => (
          this.loading = false,
          this.codes = data
        )
      )
    },

    onFileWIChange(e) {
      this.file_img = e.target.files[0];
    },

    newModal(){
      this.form = {
        id : 0,
        title_es : '',
        description_es : '',
        publication_date : '',
        public : '',
        tags : '',
      };
      this.editmode = false;
      $('#Modal').modal('show');
    },
    createCompany(){
      this.$Progress.start();
      this.loading_modal = true;

      var post_tags = [];

      (this.form.tags).forEach((item, i) => {
        post_tags.push(item.name);
      });

      let formData = new FormData();
      formData.append('file', this.file_img);
      formData.append('id', this.form.id);
      formData.append('title_es', this.form.title_es);
      formData.append('description_es', this.form.description_es);
      formData.append('publication_date', this.form.publication_date);
      formData.append('public', this.form.public);
      formData.append('tags', post_tags);

      axios.post(this.url + '/create', formData).then(response => {
        toast.fire({
          type: 'success',
          title: `Agregado correctamente.`
        })
        this.getCodes();
        this.loading_modal = false;
        this.$Progress.finish();
        $('#Modal').modal('hide');
        this.$Progress.finish();
        this.file_img = '';
        this.form = '';
        $("#files_web").val('');
      }).catch(()=>{
        this.loading_modal = false;
        this.$Progress.fail();
        toast.fire({
          type: 'warning',
          title: `Ha ocurrido un error.`
        });
      })
    },
    // new a-t
    editModal(code){
      this.editmode = true;
      $('#Modal').modal('show');
      this.form = code;
      this.form.link_pgc = JSON.parse(code.link_pgc);
    },

    updateDataRespose(){
      toast.fire({
        type: 'success',
        title: `Editado correctamente.`
      });
      this.getCodes();
      this.loading_modal = false;
      this.$Progress.finish();
      $('#Modal').modal('hide');
      this.file_img = '';
      this.form = '';
      $("#files_web").val('');
    },

    updateCompany(){
      this.$Progress.start();
      this.loading_modal = true;
      var post_tags = [];

      (this.form.tags).forEach((item, i) => {
        post_tags.push(item.name);
      });

      var link_pgc_id = '';
      var link_pgc_name = '';
      if (this.form.link_pgc.id) {
        link_pgc_id = this.form.link_pgc.id;
        link_pgc_name = this.form.link_pgc.name_es;
      }


      let formData = new FormData();
      formData.append('file', this.file_img);
      formData.append('id', this.form.id);
      formData.append('title_es', this.form.title_es);
      formData.append('description_es', this.form.description_es);
      formData.append('publication_date', this.form.publication_date);
      formData.append('public', this.form.public);
      formData.append('link_pgc_id', link_pgc_id);
      formData.append('link_pgc_name', link_pgc_name);
      formData.append('tags', post_tags);
      // formData.append('id', this.form);


      axios.post(this.url + '/update', formData).then(response => {
        if (this.form.link_pgc != '') {

          var files = [];
          if (response.data[0].file != null) {
            files.push({'name' : response.data[0].file, 'path' : 'https://fundacionidea.org.mx/storage/IDEA/files/'+response.data[0].file });
            // files.push({'name' : response.data[0].file, 'path' : 'http://127.0.0.1:8000/storage/IDEA/files/'+response.data[0].file });
          }
          (response.data[1]).forEach((item, i) => {
            // files.push({'name' : response.data[0].file, 'path' : 'http://127.0.0.1:8000/storage/IDEA/files/'+item.file });
            files.push({'name' : item.file, 'path' : 'https://fundacionidea.org.mx/storage/IDEA/files/'+item.file});
          });


          axios.post('/api/save-publication-web-idea-pgc',{
            'project_id' : this.form.link_pgc.id,
            'title_es' : this.form.title_es,
            'publication_date' : this.form.publication_date,
            'description_es' : this.form.description_es,
            'public' : this.form.public,
            'tags' : post_tags,
            'name' : response.data[0].thumbnail,
            // 'path' : 'http://127.0.0.1:8000/storage/thumbnails/' + response.data[0].thumbnail,//cambiar
            'path' : 'https://fundacionidea.org.mx/storage/thumbnails/' + response.data[0].thumbnail,//cambiar
            'files' : files,
          }).then(response => {
            this.updateDataRespose();
          }).catch(e => {
            console.error(e);
          });
        }else {
          this.updateDataRespose();
        }
      }).catch(e => {
        console.log(e);
        this.loading_modal = false;
        this.$Progress.fail();
        toast.fire({
          type: 'warning',
          title: `Ha ocurrido un error.`
        });
      });


      // this.form.put('/api/project-codes/'+this.form.id)
      // .then(() => {
      //     $('#Modal').modal('hide');
      //     toast.fire({
      //         type: 'success',
      //         title: `Editado correctamente.`
      //     })
      //     this.$Progress.finish();
      //     Fire.$emit('AfterCreate');
      // }).catch(() => {
      //     this.$Progress.fail();
      // });

    },



    deleteCompany(code){
      swal.fire({
        title: '¿Estás seguro?',
        text: "¡No podrás revertir esto!",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
      }).then((result) => {
        if (result.value) {
          this.$Progress.start();
          axios.get(this.url + '/delete/' + code).then(()=>{
            swal.fire(
              'Eliminado',
              '-',
              'success'
            )
            this.getCodes();
            Fire.$emit('AfterCreate');
            this.$Progress.finish();
          }).catch(()=> {
            swal("Error", "Algo salió mal", "warning");
            this.$Progress.fail();
          })

        }
      })
    },

    AddResource(code){
      this.file = false;
      this.datas = code;
      this.publication_id = code.id;
      this.publication_name = code.title_es;
      $('#ModalFiles').modal('show');
      axios.get(this.url + '/resources/' + code.id).then(response => {
        this.resources = response.data;
      }).catch(e => {
        console.error(e);
      });
    },

    cancelarResorce(){
      this.file = false;
      $("#files_web_resource").val('');
    },

    onFileChangePdf(e){
      this.file_pdf = e.target.files[0];
    },

    saveResource(){
      this.$Progress.start();
      this.loading_modal = true;

      let formData = new FormData();
      formData.append('file', this.file_pdf);
      formData.append('id', this.publication_id);

      axios.post(this.url + '/saveresource', formData).then(response => {
        toast.fire({
          type: 'success',
          title: `Guardado correctamente.`
        });
        // $('#Modal').modal('hide');
        this.$Progress.finish();
        this.file_pdf = '';
        this.AddResource(this.datas);
        this.cancelarResorce();
        this.loading_modal = false;
      }).catch(e => {
        this.loading_modal = false;
        this.$Progress.fail();
        toast.fire({
          type: 'warning',
          title: `Ha ocurrido un error.`
        });
      });
    },

    deleteResource(id, table){
      swal.fire({
        title: '¿Estás seguro?',
        text: "¡No podrás revertir esto!",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
      }).then(result => {
        if (result.value) {
          axios.get(this.url + '/deleteresource/' + id + '&' + table + '&' + this.publication_id).then(response => {
            this.AddResource(this.datas);
          }).catch(e => {
            console.error(e);
          });
        }
      })

    }


  },
  created(){
    this.getCodes();
    this.getData();
  }
}
</script>

<style lang="scss" scoped>
.main {
  @media (min-width: 768px) {
    margin-left: 200px;
  }
}
</style>
