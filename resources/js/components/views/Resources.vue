<template>
    <ais-instant-search
        class="resources container py-2"
        :search-client="searchClient"
        index-name="resources"
    >
        <Loading v-if="loading" />
        <div class="row">
          <div class="col-md-11">
            <br>
            <span class="color-title" ><img src="/img/icono- recursos.png" style="width : 2rem;">&nbsp;Recursos</span>
          </div>
        </div>
<!--
        <div class="row">
          <div class="col-md-12">
            <h1>dd</h1>
            <table class="table table-responsive table-bordered">
              <tr v-for="folder in folders" :key="folder.id">
                <td>{{folder.name}}</td>
              </tr>
            </table>
          </div>
        </div> -->

        <div class="row" v-if="!loading && $gate.isActive()" @click="closeDropdowns">
            <!-- <div class="col-md-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><router-link class="text-orange" to="/home">Inicio</router-link></li>
                        <li class="breadcrumb-item active" aria-current="page">Recursos</li>
                    </ol>
                </nav>
            </div> -->
            <div v-if="!$gate.isUser()" class="col-md-12 mb-4">
                <div class="dropdown">
                    <a @click.stop="addFolderModal" href="#" class="btn btn-outline-orange float-right font-weight-bold pointer">
                        <i class="fas fa-folder-plus mr-1"></i>
                        Nueva carpeta
                    </a>
                </div>
            </div>
            <div class="col-md-12 mb-4">
                <ais-autocomplete>
                    <div slot-scope="{ currentRefinement, indices, refine }">
                        <div class="input-group-prepend main-search">
                            <span class="input-group-text search-icon" id="basic-addon1">
                                <i
                                    class="fas fa-search text-secondary"
                                ></i>
                            </span>
                            <input
                                v-model="query"
                                class="form-control search-input"
                                type="text"
                                placeholder="Buscar recursos"
                                @input="refine($event.currentTarget.value)"
                            >
                            <span class="input-group-text loading-icon" id="basic-addon1"></span>
                        </div>
                        <div
                            @keyup="nextItem(resources)"
                            class="list-group autocomplete-items"
                            v-if="currentRefinement"
                            v-for="resources in indices"
                            :key="resources.label"
                            :results="results=indices[0].hits"
                        >
                            <div
                                @click="goToFolderFromAutocomplete(resource.folder_id)"
                                :class="{ 'autocomplete-item-active' : currentItem == index }"
                                class="list-group-item list-group-item-action pointer"
                                v-for="(resource, index) in resources.hits" :key="resource.objectID"
                            >
                                <div class="media">
                                    <i
                                        class="fas fa-lg align-self-center mr-2"
                                        :class="{
                                            'fa-file-pdf text-danger' : resource.type == '.pdf',
                                            'fa-file-word text-primary' : resource.type == '.doc' || resource.type == '.docx',
                                            'fa-file-excel text-success' : resource.type == '.xls' || resource.type == '.xlsx',
                                            'fa-file-csv text-success' : resource.type == '.csv',
                                            'fa-file-archive text-info' : resource.type == '.zip',
                                            'fa-file-powerpoint text-orange' : resource.type == '.ppt' || resource.type == '.pptx' || resource.type == '.pps',
                                            'fa-file-alt' : resource.type != '.pdf' && resource.type != '.doc' && resource.type != '.docx' && resource.type != '.xls' && resource.type != '.xlsx' && resource.type != '.csv' && resource.type != '.zip' && resource.type != '.ppt' && resource.type != '.pptx' && resource.type != '.pps'
                                        }"
                                    ></i>
                                    <div class="media-body align-self-center">
                                        <p class="mb-0">

                                            <ais-highlight attribute="name" :hit="resource"/>
                                        </p>
                                        <p class="mb-0 text-muted">
                                            <ais-highlight attribute="folder" :hit="resource"/>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="list-group-item list-group-item-action">
                                <ais-stats>
                                    <p class="mb-0" slot-scope="{ hitsPerPage, nbPages, nbHits }" :nb="nb=nbHits">
                                        {{ nbHits }} <span v-if="nbHits == 1">resultado encontrado</span> <span v-else>resultados encontrados</span> para "{{query}}"
                                    </p>
                                </ais-stats>
                            </div>
                        </div>
                    </div>
                </ais-autocomplete>
            </div>
            <div class="col-md-12 mb-1 d-flex justify-content-between align-items-center">
                <p class="h3">
                    Recursos
                </p>
                <div v-if="!$gate.isUser()" class="float-right" v-show="folderSelected">
                    <button type="button" class="btn btn-options" @click.stop="editFolderModal(folderSelected)">
                        <i class="fas fa-pencil-alt"></i>
                    </button>
                    <button type="button" class="btn btn-options" @click.stop="deleteFolder(folderSelected)">
                        <i class="far fa-trash-alt"></i>
                    </button>
                </div>
            </div>
            <div class="col-md-12 mb-4">
                <hr class="separator mt-0">
            </div>

            <div class="col-md-4 pointer" v-for="folder in folders" :key="folder.id">
              <!-- <p>jjs</p> -->
                <!-- <div
                    :class="{ 'card-active' : folderSelected == folder }"
                    class="card card-shadow bg-light mb-3"
                    @dblclick.stop="goToFolder(folder)"
                    @click.stop="selectFolder(folder)"
                    @contextmenu.prevent.stop="folderOptions(folder)"
                > -->
                 <div
                    :class="{ 'card-active' : folderSelected == folder }"
                    class="card card-shadow bg-light mb-3"
                    @click="goToFolder(folder)"
                    @contextmenu.prevent.stop="folderOptions(folder)"
                >
                    <div class="card-body">
                        <p class="mb-0">
                            <i class="fas fa-folder fa-lg mr-2"></i> {{ folder.name }}
                        </p>
                    </div>
                </div>
                <div :id="'dropdown'+folder.id" class="dropdown-menu dropdown-menu-right-folder" aria-labelledby="dropdownMenuButton">
                    <a v-if="!$gate.isUser()" class="dropdown-item" href="#" @click.stop="editFolderModal(folder)">
                        <i class="fas fa-pencil-alt pointer mr-2"></i> Cambiar nombre
                    </a>
                    <a v-if="!$gate.isUser()" class="dropdown-item" href="#" @click.stop="deleteFolder(folder)">
                        <i class="far fa-trash-alt pointer mr-2"></i> Eliminar
                    </a>
                </div>
            </div>
        </div>
        <add-folder ref="AddFolder" :editmode="editmode" />
        <NoActive v-if="!$gate.isActive()" />
    </ais-instant-search>
</template>

<script>
    import algoliasearch from 'algoliasearch/lite';
    import 'instantsearch.css/themes/algolia-min.css';
    import AddFolder from '../folders/AddFolder'
    import NoActive from '../complements/NoActive'
    import Loading from '../complements/Loading'
    export default {
        components: {
            NoActive, 'add-folder': AddFolder, Loading
        },
        data: () => ({
            loading: false,
            editmode: false,
            viewMenu: false,
            folderSelected: '',
            folders: '',
            searchClient: algoliasearch(
                algolia_appid,
                algolia_apikey
            ),
            currentItem: -1,
            nb:'',
            query:'',
            results:'',
        }),
        methods: {
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
            deleteFolder(folder){
                swal.fire({
                    title: '¿Estás seguro?',
                    text: "¡No podrás revertir esto!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sí, eliminar carpeta',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.value) {
                        axios.delete(`/api/folder/${folder.id}`).then(()=>{
                            swal.fire(
                                'Eliminado',
                                'La carpeta ha sido eliminada.',
                                'success'
                            )
                            Fire.$emit('AfterCreate');
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
            addFolderModal(){
                $('#folderModal').modal('show')
                this.editmode = false;
                this.$refs.AddFolder.form.reset();
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
                        this.loading = false
                    )
                )
            }
        },
        created(){
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
</style>
