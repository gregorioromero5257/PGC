<template>

  <div class="grid-rn">

    <div class="row">
      <div class="col-md-11">
        <span class="color-title" ><img src="/img/icono- recursos.png" style="width : 2rem;">&nbsp;Recursos</span>
      </div>
    </div>
    <div class="grid-rnd">

      <div class="grid-rnb">
        <v-select label="name" v-model="resources_dos" :options="options" @search="onSearch" @input="setResorcesFind()">
          <template slot="no-options">
            Escriba para buscar..
          </template>
          <template slot="option" slot-scope="option">
            <div class="d-center">
              <template v-if="option.hasOwnProperty('type')">
                <i
                class="fas fa-lg align-self-center mr-2"
                :class="{
                  'fas fa-video text-danger' : option.type == '.mp4' || option.type == '.mkv' || option.type == '.mov' || option.type == '.wnv' || option.type == '.flv' || option.type == '.avi',
                  'fas fa-images' : option.type == '.jpg' || option.type == '.png' || option.type == '.gif' || option.type == '.jpeg' || option.type == '.JPG' || option.type == '.PNG' || option.type == '.GIF' || option.type == '.JPEG',
                  'fa-file-pdf text-danger' : option.type == '.pdf' || option.type == '.PDF',
                  'fa-file-word text-primary' : option.type == '.doc' || option.type == '.docx' || option.type == '.DOC' || option.type == '.DOCX',
                  'fa-file-excel text-success' : option.type == '.xls' || option.type == '.xlsx',
                  'fa-file-csv text-success' : option.type == '.csv',
                  'fa-file-archive text-info' : option.type == '.zip',
                  'fa-file-powerpoint text-orange' : option.type == '.ppt' || option.type == '.pptx' || option.type == '.pps' || option.type == '.PPT' || option.type == '.PPTX' || option.type == '.PPS',
                  'fa-file-alt' : option.type != '.pdf' && option.type != '.doc' && option.type != '.docx' && option.type != '.xls'
                  && option.type != '.xlsx' && option.type != '.csv' && option.type != '.zip' &&  option.type != '.ppt'
                  && option.type != '.pptx' && option.type != '.pps'
                  }"
                  ></i>&nbsp;
                  {{option.name}}
                </template>
                <template v-else>
                  <img src="/img/Carpeta.png" class="align-self-center mr-2" style="width : 0.8rem;"/>&nbsp;
                  {{option.name}}
                </template>
              </div>
            </template>

            <template slot="selected-option" slot-scope="option">
              <div class="selected d-center">
                <template v-if="option.hasOwnProperty('type')">
                  <i
                  class="fas fa-lg align-self-center mr-2"
                  :class="{
                    'fas fa-video text-danger' : option.type == '.mp4' || option.type == '.mkv' || option.type == '.mov' || option.type == '.wnv' || option.type == '.flv' || option.type == '.avi',
                    'fas fa-images' : option.type == '.jpg' || option.type == '.png' || option.type == '.gif' || option.type == '.jpeg' || option.type == '.JPG' || option.type == '.PNG' || option.type == '.GIF' || option.type == '.JPEG',
                    'fa-file-pdf text-danger' : option.type == '.pdf' || option.type == '.PDF',
                    'fa-file-word text-primary' : option.type == '.doc' || option.type == '.docx' || option.type == '.DOC' || option.type == '.DOCX',
                    'fa-file-excel text-success' : option.type == '.xls' || option.type == '.xlsx',
                    'fa-file-csv text-success' : option.type == '.csv',
                    'fa-file-archive text-info' : option.type == '.zip',
                    'fa-file-powerpoint text-orange' : option.type == '.ppt' || option.type == '.pptx' || option.type == '.pps' || option.type == '.PPT' || option.type == '.PPTX' || option.type == '.PPS',
                    'fa-file-alt' : option.type != '.pdf' && option.type != '.doc' && option.type != '.docx' && option.type != '.xls'
                    && option.type != '.xlsx' && option.type != '.csv' && option.type != '.zip' &&  option.type != '.ppt'
                    && option.type != '.pptx' && option.type != '.pps'
                    }"
                    ></i>&nbsp;
                    {{option.name}}
                  </template>
                  <template v-else>
                    <img src="/img/Carpeta.png" class="align-self-center mr-2" style="width : 0.8rem;"/>&nbsp;
                    {{option.name}}
                  </template>
                </div>
              </template>
            </v-select>
            <!-- <v-select :options="resorces_list" multiple label="name" v-model="resources_dos"
            @input="setResorcesFind()"></v-select> -->
          </div>
          <div class="grid-rnb">
            <h5 class="mt-3 font-weight-bold" style="color : #F68E1D">{{ options.length }} archivos encontrados</h5>
            <div class="buttons">
              <template v-if="title != ''">
                <button class="btn btn-outline-blue float-right" @click.stop="addDocumentModal">
                  <img src="/img/nuevodocumento.png" style="width : 0.7rem;">
                  &nbsp;Nuevo Archivo
                </button>
              </template>
              <button class="btn btn-outline-blue float-right"  @click.stop="addFolderModal">
                <img src="/img/nuevacarpeta.png" style="width : 0.7rem;">
                &nbsp;Nueva Carpeta
              </button>

            </div>

          </div>
          <br>
          <div class="row">
            <div class="col-md-3" >
              <div class="header-divs">
                <h5 class="texts-header"><b> Carpetas</b></h5>
              </div>
              <RecursiveAccordion v-if="renderComponent"
              @set-data="setData($event)"
              @set-title="setTitle($event)"
              @set-id="setId($event)"
              @clear-component="clearComponent()"
              :folders="folders"
              :ids="folders['d']"
              :id_folder="id_folder"
              :track_folder="track_folder"
              :folder_id="folder_id"
              :clean_after_search="clean_after_search"
              />
            </div>
            <!-- <template v-if="vista">

          </template> -->
          <!-- <template v-else> -->
          <div class="col-md-9 mt-4 text-center" v-show="vista == true">
            <p>
              <i class="fas fa-spinner fa-pulse fa-4x"></i>
            </p>
            <p class="h4">Cargando vista previa...</p>
          </div>

          <div class="col-md-4" v-show="vista == false">
            <div class="header-divs">
              <h5 class="texts-header"><b> Archivos</b></h5>
            </div>
            <template v-if="title != ''">
              <div class="aling-items">
                <template v-if="edit_folder">
                  <input type="text" class="form-control" v-model="title" style="width: 85% !important;">
                  <h1>&nbsp;</h1>
                  <div class="options">
                    <i @click="saveNewNameFolder()" style="cursor: pointer" class="fas fa-save"></i>
                    <label>&nbsp;</label>
                    <label>&nbsp;</label>
                    <i @click="edit_folder = false;" style="cursor: pointer" class="fas fa-times"></i>
                  </div>
                </template>
                <template v-else>
                  <h5 class="texts-header-dos">
                    <img src="/img/Carpetaseleccionada.png" style="width : 0.8rem;">&nbsp;<b>{{title}}</b>
                  </h5>
                  <template v-if="$gate.ifHavePermisos([1, 2])">
                    <div class="options">
                      <i @click="deleteFolder()" style="cursor: pointer" class="fas fa-trash"></i>
                      <label>&nbsp;</label>
                      <label>&nbsp;</label>
                      <i @click="edit_folder = true;" style="cursor: pointer" class="fas fa-pen"></i>
                    </div>
                  </template>
                </template>
              </div>
            </template>

            <template v-for="t in resources"  >

              <div class="dropdown">
                <button class="btn btn-light" type="button" id="dropdownMenu2" data-toggle="dropdown"
                aria-haspopup="true" aria-expanded="false" @click="setPD(t.id); setNF(t)">
                <i
                class="fas fa-lg align-self-center mr-2"
                :class="{
                  'fas fa-video text-danger' : t.type == '.mp4' || t.type == '.mkv' || t.type == '.mov' || t.type == '.wnv' || t.type == '.flv' || t.type == '.avi',
                  'fas fa-images' : t.type == '.jpg' || t.type == '.png' || t.type == '.gif' || t.type == '.jpeg' || t.type == '.JPG' || t.type == '.PNG' || t.type == '.GIF' || t.type == '.JPEG',
                  'fa-file-pdf text-danger' : t.type == '.pdf' || t.type == '.PDF',
                  'fa-file-word text-primary' : t.type == '.doc' || t.type == '.docx' || t.type == '.DOC' || t.type == '.DOCX',
                  'fa-file-excel text-success' : t.type == '.xls' || t.type == '.xlsx',
                  'fa-file-csv text-success' : t.type == '.csv',
                  'fa-file-archive text-info' : t.type == '.zip',
                  'fa-file-powerpoint text-orange' : t.type == '.ppt' || t.type == '.pptx' || t.type == '.pps' || t.type == '.PPT' || t.type == '.PPTX' || t.type == '.PPS',
                  'fa-file-alt' : t.type != '.pdf' && t.type != '.doc' && t.type != '.docx' && t.type != '.xls'
                  && t.type != '.xlsx' && t.type != '.csv' && t.type != '.zip' &&  t.type != '.ppt'
                  && t.type != '.pptx' && t.type != '.pps'
                  }"
                  ></i>&nbsp;{{t.name}} ({{(t.updated_at).substr(0,10)}}) {{t.type}}
                </button>
              </div>

            </template>
          </div>
          <div class="col-md-5 mt-4 text-center" v-show="descargando_archivo == true">
            <p>
              <i class="fas fa-spinner fa-pulse fa-4x"></i>
            </p>
            <p class="h4">Descargando archivo...</p>
          </div>
          <div class="col-md-5" v-show="vista == false && descargando_archivo == false">
            <div class="header-divs">
              <h5 class="texts-header"><b> Vista previa</b></h5>
            </div>
            <template v-if="archivo.id != ''">
              <div class="dropdown">
                <button class="btn btn-light" type="button" id="dropdownMenu2" data-toggle="dropdown"
                aria-haspopup="true" aria-expanded="false" @click="setPD(t.id); setNF(t)">
                <i
                class="fas fa-lg align-self-center mr-2"
                :class="{
                  'fas fa-video text-danger' : archivo.type == '.mp4' || archivo.type == '.mkv' || archivo.type == '.mov' || archivo.type == '.wnv' || archivo.type == '.flv' || archivo.type == '.avi',
                  'fas fa-images' : archivo.type == '.jpg' || archivo.type == '.png' || archivo.type == '.gif' || archivo.type == '.jpeg',
                  'fa-file-pdf text-danger' : archivo.type == '.pdf',
                  'fa-file-word text-primary' : archivo.type == '.doc' || archivo.type == '.docx',
                  'fa-file-excel text-success' : archivo.type == '.xls' || archivo.type == '.xlsx',
                  'fa-file-csv text-success' : archivo.type == '.csv',
                  'fa-file-archive text-info' : archivo.type == '.zip',
                  'fa-file-powerpoint text-orange' : archivo.type == '.ppt' || archivo.type == '.pptx' || archivo.type == '.pps',
                  'fa-file-alt' : archivo.type != '.pdf' && archivo.type != '.doc' && archivo.type != '.docx' && archivo.type != '.xls'
                  && archivo.type != '.xlsx' && archivo.type != '.csv' && archivo.type != '.zip' &&
                  archivo.type != '.ppt'
                  && archivo.type != '.pptx' && archivo.type != '.pps'
                  }"
                  ></i>&nbsp;{{archivo.name}} ({{(archivo.updated_at).substr(0,10)}}) {{archivo.type}}
                </button>
              </div>

              <template v-if="embeds">
                <template v-if="archivo.type == '.doc' || archivo.type == '.docx' || archivo.type == '.xls' || archivo.type == '.xlsx' || archivo.type == '.ppt' || archivo.type == '.pptx' || archivo.type == '.pps'">
                  <iframe :src="'https://view.officeapps.live.com/op/embed.aspx?src=https://pgc.c-230.com/pgcf/' + archivo.id + archivo.name + archivo.type"
                  width='90%' height='500px' frameborder='0'>
                  This is an embedded
                  <a target='_blank' href='http://office.com'>Microsoft Office</a> document, powered by
                  <a target='_blank' href='http://office.com/webapps'>Office Online</a>.</iframe>
                </template>
                <template v-if="archivo.type == '.pdf'">
                  <iframe :src="'https://docs.google.com/viewerng/viewer?url=https://pgc.c-230.com/pgcf/' + archivo.id + archivo.name + archivo.type+'&embedded=true'"
                  frameborder="0" height="500px" width="90%">
                </iframe>
              </template>
              <template v-if="archivo.type == '.mp4' || archivo.type == '.mkv' || archivo.type == '.mov'
              || archivo.type == '.wnv' || archivo.type == '.flv' || archivo.type == '.avi'">
              <div style="padding:56.25% 0 0 0;position:relative;">
                <iframe :src="'https://player.vimeo.com/video/'+ (archivo.uri).substr(8) +'?badge=0&amp;autopause=0&amp;player_id=0&amp;app_id=58479'"
                frameborder="0" allow="autoplay; fullscreen; picture-in-picture"
                allowfullscreen style="position:absolute;top:0;left:0;width:90%;height:100%;" title="lia"></iframe></div>
              </template>
              <template v-if="archivo.type == '.cvs' || archivo.type == '.zip' || archivo.type == '.dotx'">
                <h5>No hay vista previa</h5>
              </template>
              <template v-if="archivo.type == '.jpg' || archivo.type == '.png' || archivo.type == '.gif' || archivo.type == '.jpeg'">
                <img :src="'https://pgc.c-230.com/pgcf/' + archivo.id + archivo.name + archivo.type" height="500px" width="90%">
              </template>
            </template>




            <div class="buttons">
              <button class="btn float-left"  @click.stop="deleteFile(archivo)">
                <img src="/img/eliminar.png" style="width : 0.7rem;">
                &nbsp;<span style="color: #e10506;">Eliminar</span>
              </button>
              <template>
                <button class="btn float-right" @click.stop="dowloadFile(archivo)">
                  <img src="/img/descargar.png" style="width : 0.7rem;">
                  &nbsp;<span style="color: #074383;">Descargar</span>
                </button>
              </template>
            </div>

          </template>


        </div>
        <!-- </template> -->
      </div>

    </div>

    <add-folder ref="AddFolder" :editmode="editmode" :id_folder="id_folder" :title="title"
    @saveFolder="refreshFolders()"/>
    <add-resource @refresh-folders="refreshResorces($event)" :id_folder="id_folder" />

  </div>



</template>
<script src="https://player.vimeo.com/api/player.js"></script>

<script>
import RecursiveAccordion from '../folders/RecursiveAccordion'
import AddFolder from '../folders/AddFolder'
import AddResource from '../folders/AddResource'
import NoActive from '../complements/NoActive'
import Loading from '../complements/Loading'

import NoAccess from '../complements/NoAccess'

export default {
  components: {
    NoActive, 'add-folder': AddFolder, AddResource, Loading, 'RecursiveAccordion' : RecursiveAccordion,
  },
  props : {
  },
  data: () => ({
    di : '',
    ids : '',
    texto_edit : false,
    level: 1,
    ts : [1,2],
    ele_data : {},
    loading: false,
    editmode: false,
    viewMenu: false,
    folderSelected: '',
    folders: {},
    resources : '',
    resources_dos : '',
    title : '',
    currentItem: -1,
    nb:'',
    query:'',
    results:'',
    message: '',
    text : 'dda',
    docOff : '',
    docPdf : '',
    video : '',
    id_folder : 0,
    folder_id : 0,
    renderComponent : true,
    resorces_list : '',
    selectedResorces : '',
    archivo : {
      id : '',
    },
    vista : false,
    estadovp : true,
    descargando_archivo : false,
    embeds : true,
    options : [],
    track_folder : [],
    clean_after_search : 0,
    edit_folder : false,
  }),
  methods: {
    onSearch(search, loading) {
      let me = this;
      if(search.length && search.length > 1) {
        loading(true);

        setTimeout(function(){
          axios.post('/api/find-folder-file',{
            des : search,
          }).then(response => {
            me.options = response.data;
            loading(false);
          }).catch(e =>{
            console.error(e);
            loading(false);
          });
        }, 900);

        // console.log(loading);
        // console.log(search);
        // this.search(loading, search, this);
      }
    },

    setPD(id){
      this.vista = true;
      this.embeds = false;
      axios.get('/api/savelocalfile/' + id).then((response) =>{
        this.$Progress.finish();
        this.vista = false;
        this.embeds = true;
      }).catch(e => {
        this.vista = false;
        this.embeds = true;
        this.$Progress.finish();
      });
    },

    setNF(obj) {
      // this.$Progress.start();
      this.archivo = {
        created_at: obj.created_at,
        file: obj.file,
        folder_id: obj.folder_id,
        id: obj.id,
        name: obj.name,
        status: obj.status,
        type: obj.type,
        updated_at: obj.updated_at,
        uri: obj.uri
      };
      // axios.get('/api/savelocalfile/' + data.id).then((response) =>{
      //   this.$Progress.finish();
      // }).catch(e => {
      //   console.log(e);
      //   this.$Progress.finish();
      //
      // });
    },

    vaciar(){
      this.archivo = {
        created_at: '',
        file: '',
        folder_id: '',
        id: '',
        name: '',
        status: '',
        type: '',
        updated_at: '',
        uri: ''
      };
    },

    set(){
      this.estadovp = true;
    },

    setData(e){
      this.resources = e;
    },

    setResorcesFind(){
      if (this.resources_dos != '') {
        this.clean_after_search = 1;
        if ((this.resources_dos).hasOwnProperty('type')) {
          // alert('es un archivo');
          this.archivo = {};
          this.archivo = {
            created_at: this.resources_dos.created_at,
            file: this.resources_dos.file,
            folder_id: this.resources_dos.folder_id,
            id: this.resources_dos.id,
            name: this.resources_dos.name,
            status: this.resources_dos.status,
            type: this.resources_dos.type,
            updated_at: this.resources_dos.updated_at,
            uri: this.resources_dos.uri
          };
          this.setPD(this.resources_dos.id);
          this.title = '';
          this.getTrackFolder(this.resources_dos.folder_id);
          axios.get('/api/folder/' + this.resources_dos.folder_id).then(response => {
            this.resources = '';
            this.resources = response.data;
          }).catch(e => {
            console.error(e);
          });

        }else {
          this.archivo = {
            id : '',
          };
          this.title = this.resources_dos.name;
          axios.get('/api/folder/' + this.resources_dos.id).then(response => {
            this.resources = '';
            this.resources = response.data;
          }).catch(e => {
            console.error(e);
          });
          this.getTrackFolder(this.resources_dos.id);
          // alert('es un carpeta');
        }
      }
    },

    getTrackFolder(id){
      this.renderComponent = false;
      axios.get('/api/get-track-folder/' + id).then(response => {
        this.track_folder = response.data;
        this.folder_id = id;
        this.renderComponent = true;
      }).catch(e => {
        this.renderComponent = true;
        console.error(e);
      });
    },

    clearComponent(){
      this.clean_after_search = 0;
      this.track_folder = [];
      this.folder_id = 0;
      this.refreshFolders();
    },

    setTitle(e){
      this.title = e;
    },

    setId(e){
      this.id_folder = e;
    },

    editarCarpeta(){
      this.texto_edit = true;
    },

    escSaveTitle(){
      this.texto_edit = false;
    },

    SaveTitle(){
      axios.put('/api/folder/' + this.id_folder,{
        'id' : this.id_folder,
        'name' : this.title,
      }).then(response => {
        this.refreshFolders();
        this.escSaveTitle();
      }).catch(e => {
        console.error(e);
      })
    },

    dowloadFile(data){
      this.$Progress.start();

      this.descargando_archivo = true;
      axios({
        url: `/api/download-resource/${data.id}`,
        method: 'GET',
        responseType: 'blob', // important
      }).then((response) => {
        const url = window.URL.createObjectURL(new Blob([response.data]));
        const link = document.createElement('a');
        link.href = url;
        link.setAttribute('download', data.file);
        document.body.appendChild(link);
        link.click();
        this.descargando_archivo = false;
        this.$Progress.finish();
      }).catch((e) => {
        this.descargando_archivo = false;
        this.$Progress.finish();

        console.error(e);
      });

    },

    deleteFile(data) {
      swal.fire({
        title: 'Desea eliminar el archivo ?',
        showCancelButton: true,
        confirmButtonText: `Eliminar`,
        confirmButtonColor : '#dd6b55',
      }).then((result) => {
        if (result.value) {
          axios.get('/api/delete-resource/' + data.id).then(response => {
            this.resources = response.data;
            this.vaciar();
            toast.fire({
              type: 'success',
              title: `Eliminado correctamente.`
            });
          }).catch(e => {
            console.error(e);
          });
        }
      });

    },

    openTd(id){
      this.ele_data.push({
        data : this.id,
        s : true,
      });
    },

    addFolderModal(){

      $('#folderModal').modal('show')
      this.editmode = false;
      $(".dropdown-menu").dropdown('hide');
    },

    addDocumentModal(){

      $('#resourceModal').modal('show')
      $(".dropdown-menu").dropdown('hide');
    },

    refreshFolders(){
      this.renderComponent = false;
      this.getFolders();
      this.title = '';
      this.vaciar();
      // this.$refs.RecursiveAccordion.$forceUpdate();
    },

    refreshResorces(data) {
      this.resources = '';
      this.resources = data;
    },

    nextItem(resources){
      if (event.keyCode == 38 && this.currentItem > -1) {
        this.currentItem--
      } else if (event.keyCode == 40 && this.currentItem < this.nb-1) {
        this.currentItem++
      } else if (event.keyCode == 13 && this.currentItem >= 0) {
        window.location.href = `/resources/folder/${this.results[this.currentItem].folder_id}`;
        //this.$router.push(`/resources/folder/${this.results[this.currentItem].folder_id}`);
      } else {
        this.currentItem = -1
      }
    },
    goToFolder(folder){
      this.$router.push({ name: 'folder', params: { id: folder.id } })
    },
    goToFolderFromAutocomplete(folder_id){
      this.$router.push({ name: 'folder', params: { id: folder_id } })
    },
    saveNewNameFolder(){
      axios.post('/api/change-name-folder/',{
        'id' : this.id_folder,
        'name' : this.title,
      }).then(response => {
        this.refreshFolders();
        this.escSaveTitle();
        this.edit_folder = false;
      }).catch(e => {
        console.error(e);
      })
    },
    deleteFolder(){
      // console.log(this.id_folder,'folder');
      // console.log(this.title,'title');
      swal.fire({
        title: '¿Estás seguro?',
        text: "¡No podrás revertir esto y se borrara el contenido!",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, eliminar carpeta',
        cancelButtonText: 'Cancelar'
      }).then((result) => {
        if (result.value) {
          axios.get(`/api/delete-folder/${this.id_folder}`).then(()=>{
            swal.fire(
              'Eliminado',
              'La carpeta ha sido eliminada.',
              'success'
            )
            // Fire.$emit('AfterCreate');
            this.clearComponent();
          }).catch(()=> {
            swal("Error", "Algo salió mal", "warning");
          })

        }
      })
    },
    editFolderModal(folder){
      $('#folderModal').modal('show')
      this.editmode = true;
      this.$refs.AddFolder.form.fill(folder);
      $(".dropdown-menu").dropdown('hide');
    },

    selectFolder(folder){
      $(".dropdown-menu").dropdown('hide');
      this.folderSelected = folder;

    },
    closeDropdowns(){
      $(".dropdown-menu").dropdown('hide');
      this.folderSelected = '';
      this.query = '';
    },
    folderOptions(folder){
      $(".dropdown-menu").dropdown('hide');
      this.folderSelected = folder;
      $('#dropdown'+folder.id).dropdown('show')
    },

    getFolders(){
      this.loading = true;
      axios.get("/api/main-folders")
      .then(
        ({data}) => (
          this.folders = data,
          this.loading = false,
          this.renderComponent = true

        )
      )
    },

    getResources(){
      axios.get('/api/resource').then(response => {
        this.resorces_list = response.data;
      }).catch(e => {
        console.error(e);
      });
    },
  },
  created(){
    this.getResources();
    this.getFolders();
    Fire.$on('AfterCreate',() => {
      this.getFolders();
    });
  },
  mounted () {
    document.addEventListener("keyup", this.nextItem);
  },
}
</script>

<style lang="scss" scoped>
.dropdown-menu-right-folder {
  right: 0;
  top: 3em;
  left: auto;
}
.resources{
  min-height: 80vh;
}
.card-active{
  box-shadow:rgba(0, 0, 5, 0.35) 2px 2px 4px 0px;
}
.btn-options{
  border-radius: 50%;
  &:focus{
    background:#F7F8FA;
    box-shadow: rgba(0, 0, 5, 0.35) 0px 0px 0px 0px !important;
  }
  &:hover{
    background:#F7F8FA;
    box-shadow: rgba(0, 0, 5, 0.35) 0px 0px 0px 0px;
  }
}
.separator {
  color: #495057; border-bottom:0.20rem solid #dee2e6;
}
.autocomplete-items {
  position: absolute;
  z-index: 99;
  top: 100%;
  left: 1em;
  right: 1em;
}
.autocomplete-item-active{
  background-color: #f5f5f5;
}

.btn-light {
  margin: 0 6px 2px 6px;
  text-align: left !important;
}

.grid-rn {
  margin: 1% 3% 0 3%;
  display: grid;
}

.grid-rnd {
  border: 1px solid black;
  border-radius: 5px;
  padding: 12px;
}

.grid-rnb {
  display: grid;
  grid-template-columns: 50% 50%;
}

.header-divs {
  background: #e2e8f0;
  padding: 0.5px;
  color: #073779;
}

.texts-header {
  padding: 2% 2% 2% 6%;
  font-size: 14px;
}

.texts-header-dos {
  padding: 2% 2% 2% 6%;
  font-size: 14px;
  color: #f78f1e;
}

.aling-items {
  display: flex;
  justify-content: space-between;
}
</style>
