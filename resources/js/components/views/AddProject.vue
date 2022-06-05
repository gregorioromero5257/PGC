<template>
    <div class="addProject">
        <Loading v-if="loading" />
        <nav class="navbar navbar-light bg-orange  justify-content-center" v-if="$route.path == '/projects/edit/'+$route.params.id && !loading && $gate.isActive() && $gate.activeUser().role_id != 5">
            <span class="navbar-text">
                Estás editando la información de este proyecto
            </span>
        </nav>
        <div class="container" v-if="!loading && $gate.isActive()">
            <div class="row">
                <div class="col-md-12">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a class="text-orange" href="/home">Inicio</a></li>
                            <li class="breadcrumb-item"><a class="text-orange" href="/projects">Proyectos</a></li>
                            <li v-if="$route.path == '/projects/create'" class="breadcrumb-item active" aria-current="page">Agregar de  proyecto</li>
                            <li v-if="$route.path == '/projects/edit/'+$route.params.id" class="breadcrumb-item active" aria-current="page">
                                <router-link class="text-orange" :to="'/projects/'+project.id">{{ project.code }}</router-link>
                            </li>
                            <li v-if="$route.path == '/projects/edit/'+$route.params.id" class="breadcrumb-item active" aria-current="page">Editar</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <div class="row" v-if="$route.path == '/projects/create'">
                <div class="col-md-12">
                    <h1 class="h2 text-center font-weight-bold mb-4">Agregar proyectod</h1>
                </div>
            </div>
            <div class="row d-none d-lg-block">
                <div class="col-md-12">
                    <nav class="navbar navbar-expand-lg navbar-light bg-light mb-4 pt-3 pb-2 shadow-sm">
                        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <div class="collapse navbar-collapse" id="navbarNav">
                            <ul class="navbar-nav">
                                <li v-for="section in project_sections" :key="section.id" class="nav-item h5" :class="{ 'active' : active_section == section.id, 'font-weight-normal' : active_section != section.id }">
                                    <a class="nav-link pointer" @click="toSection(section.id)">{{ section.name }}</a>
                                </li>
                                <li class="nav-item h5" :class="{ 'active' : active_section == 5, 'font-weight-normal' : active_section != 5 }" v-if="$route.path == '/projects/edit/'+$route.params.id">
                                    <a class="nav-link pointer" @click="toSection(5)">Archivos</a>
                                </li>
                            </ul>
                        </div>
                    </nav>
                </div>
            </div>
            <GeneralData
                v-show="active_section == 1"
                @changeCompany="changeCompany"
                @selectPrivacyOption="selectPrivacyOption"
                :form="form"
                :privacy_options="privacy_options"
                :projects="projects"
            />
            <StackeholdersInfo
                v-show="active_section == 2"
                :form="form"
            />
            <ManagementData
                v-show="active_section == 3 && !$gate.isUser()"
                :form="form"
                :satisfaction_documents="satisfaction_documents"
                @selectSatisfactionDocument="selectSatisfactionDocument"
            />
            <NoAccess v-show="active_section == 3 && $gate.isUser()" />
            <Description
                v-show="active_section == 4"
                :form="form"
            />
            <File 
                v-show="active_section == 5 && $route.path == '/projects/edit/'+$route.params.id"
                :form="form"
                :project="project"
                
            />

            <div class="row mt-4">
                <div class="col-md-12">
                    <div class="form-group text-center">
                        <button v-if="$route.path == '/projects/create'" type="submit" class="btn btn-orange btn-rounded" @click.prevent="createProject">
                            Guardar proyecto
                        </button>
                        <button v-if="$route.path == '/projects/edit/'+$route.params.id" type="submit" class="btn btn-orange btn-rounded" @click.prevent="updateProject">
                            Guardar proyecto
                        </button>
                    </div>
                </div>
            </div>
            <div class="row mt-3 mb-3">
                <div class="col-4">
                    <a class="float-left text-decoration-none pointer" @click="toPrevious">
                        <i class="fas fa-chevron-circle-left fa-2-5x text-secondary"></i> <span class="ml-1 button-text text-dark d-none d-sm-inline-block">Anterior</span>
                    </a>
                </div>
                <div class="col-4">
                    <nav aria-label="Page navigation example">
                        <ul class="pagination justify-content-center">
                            <li class="page-item"><a class="page-link page-link-left text-dark pointer" :class="{ 'font-weight-bold' : active_section == 1 }" href="#" @click="toSection(1)">1</a></li>
                            <li class="page-item"><a class="page-link text-dark pointer" :class="{ 'font-weight-bold' : active_section == 2 }" href="#" @click="toSection(2)">2</a></li>
                            <li class="page-item"><a class="page-link text-dark pointer" :class="{ 'font-weight-bold' : active_section == 3 }" href="#" @click="toSection(3)">3</a></li>
                            <li class="page-item"><a class="page-link page-link-right text-dark pointer" :class="{ 'font-weight-bold' : active_section == 4 }" href="#" @click="toSection(4)">4</a></li>
                            <li class="page-item" v-if="$route.path == '/projects/edit/'+$route.params.id"><a class="page-link page-link-right text-dark pointer" :class="{ 'font-weight-bold' : active_section == 5 }" href="#" @click="toSection(5)">5</a></li>
                        </ul>
                    </nav>
                </div>
                <div class="col-4">
                    <a class="float-right text-decoration-none pointer" @click="toNext">
                        <span class="mr-1 button-text text-dark d-none d-sm-inline-block">Siguiente</span><i class="fas fa-chevron-circle-right fa-2-5x text-secondary"></i>
                    </a>
                </div>
            </div>
        </div>
        <div class="container" v-if="!loading && !$gate.isActive()">
            <NoActive />
        </div>
    </div>
</template>

<script>
    import NoActive from '../complements/NoActive'
    import NoAccess from '../complements/NoAccess'
    import Loading from '../complements/Loading'
    import GeneralData from '../project/GeneralData'
    import StackeholdersInfo from '../project/StakeholdersInfo'
    import ManagementData from '../project/ManagementData'
    import Description from '../project/Description'
    import File from '../project/File'
    export default {
        components: {
            GeneralData,
            StackeholdersInfo,
            ManagementData,
            Description,
            Loading, 
            NoActive,
            NoAccess,
            File
        },
        data: () => ({
            project_sections: [
                {
                    id: 1,
                    name: "Datos generales"
                },
                {
                    id: 2,
                    name: "Información de las partes interesadas"
                },
                {
                    id:3,
                    name:"Datos de administración"
                },
                {
                    id:4,
                    name:"Descripción del proyecto"
                }
            ],
            loading: false,
            active_section: 1,
            user: {},
            project:{},
            privacy_options: [],
            satisfaction_documents: [],
            projects: [],
            form: new Form({
                id:'',
                user_id:'',
                code:'',
                name_es:'',
                name_en:'',
                contract_name:'',
                duration:'',
                locations:[],
                privacy_options: [],
                company_id:'',
                validity_of_confidentiality:'',
                comments:'',
                clients: [],
                funders: [],
                suppliers: [],
                contract_id:'',
                contract:[],
                contract_type:'',
                satisfaction_documents:[],
                contract_backed_staff:[],
                satisfactiondoc_backed_staff:[],
                contract_creator:'',
                contractual_object:'',
                currency_id:'',
                currency:[],
                amount:'',
                amount_mxn:'',
                amount_usd:'',
                contract_start:'',
                contract_end:'',
                members:[],
                description_es:'',
                description_en:'',
                short_description_es:'',
                short_description_en:'',
                types:[],
                methodologies:[],
                topics:[],
                publications:[],
                interviews:'',
                databases:'',
                trainings:'',
                study_cases:'',
                project:'',
                project_id:''
            })
        }),
        methods:{
            toSection(num){
                this.active_section = num;
            },
            toPrevious(){
                (this.active_section > 1) ? this.active_section -= 1 : toast.fire({type: 'error', title: `No hay más secciones.`});
            },
            toNext(){
                if(this.$route.params.id){
                    (this.active_section < 5) ? this.active_section += 1 : toast.fire({type: 'error', title: `No hay más secciones.`});
                } else {
                    (this.active_section < 4) ? this.active_section += 1 : toast.fire({type: 'error', title: `No hay más secciones.`});
                }
            },
            selectPrivacyOption(privacy_option){
                let index = this.form.privacy_options.findIndex(option => option.id == privacy_option.id);
                (index == -1) ? this.form.privacy_options.push(privacy_option) : this.form.privacy_options.splice(index, 1);
            },
            selectSatisfactionDocument(satisfaction_document){
                let sda_index = this.satisfaction_documents.findIndex(document => document.id == satisfaction_document.id);
                this.satisfaction_documents[sda_index].active = 1 - this.satisfaction_documents[sda_index].active;
                
                let index = this.form.satisfaction_documents.findIndex(document => document.id == satisfaction_document.id);
                (index == -1) ? this.form.satisfaction_documents.push(satisfaction_document) : this.form.satisfaction_documents.splice(index, 1);
            },
            changeCompany(company){
                this.form.company_id = company.id;
            },
            createProject(){
                this.$Progress.start();
                this.form.user_id = this.user.id;
                this.form.currency_id = this.form.currency.id;
                this.form.contract_id = this.form.contract.id;
                this.form.project_id = this.form.project.id;
                
                this.form.post('/api/project', 
                    {
                        privacy_options:this.form.privacy_options,
                        members:this.form.members,
                        types:this.form.types,
                        methodologies:this.form.methodologies,
                        topics:this.form.topics,
                        clients:this.form.clients,
                        funders:this.form.funders,
                        suppliers:this.form.suppliers,
                        satisfaction_documents:this.form.satisfaction_documents,
                        contract_backed_staff:this.form.contract_backed_staff,
                        satisfactiondoc_backed_staff:this.form.satisfactiondoc_backed_staff,
                        publications:this.form.publications,
                        locations:this.form.locations,
                    }
                )
                .then((response)=>{
                    toast.fire({
                        type: 'success',
                        title: `Proyecto ${response.data.code} agregado correctamente.`
                    })
                    this.$router.push({ name: 'project', params: { id: response.data.id, s_active: 5 } });
                    //this.$router.push(`/projects/${this.form.id}`)
                    this.$Progress.finish();
                })
                .catch(()=>{
                    this.$Progress.fail();
                    toast.fire({
                        type: 'error',
                        title: `Error al crear proyecto.`
                    })
                })
            },
            updateProject(){
                this.$Progress.start();
                if (this.user) this.form.user_id = this.user.id;
                if (this.form.currency) this.form.currency_id = this.form.currency.id;
                if (this.form.contract) this.form.contract_id = this.form.contract.id;
                if (this.form.project)this.form.project_id = this.form.project.id;
                
                this.form.put(`/api/project/${this.form.id}`,
                    {
                        privacy_options:this.form.privacy_options,
                        members:this.form.members,
                        types:this.form.types,
                        methodologies:this.form.methodologies,
                        topics:this.form.topics,
                        clients:this.form.clients,
                        funders:this.form.funders,
                        suppliers:this.form.suppliers,
                        satisfaction_documents:this.form.satisfaction_documents,
                        contract_backed_staff:this.form.contract_backed_staff,
                        satisfactiondoc_backed_staff:this.form.satisfactiondoc_backed_staff,
                        publications:this.form.publications,
                        locations:this.form.locations,
                    }
                )
                .then(() => {
                    this.$router.push({ name: 'project', params: { id: this.form.id, s_active: this.active_section } });
                    //this.$router.push(`/projects/${this.form.id}`)
                    toast.fire({
                        type: 'success',
                        title: `Proyecto ${this.form.code} actualizado correctamente.`
                    })
                    this.$Progress.finish();
                })
                .catch(() =>{
                    this.$Progress.fail();
                    toast.fire({
                        type: 'error',
                        title: `Error al actualizar proyecto.`
                    })
                })
            },
            getActiveUser(){
                axios.get("/api/active-user")
                .then(
                    ({data}) => (
                        this.user = data
                    )
                )
            },
            
            getProject(){
                this.loading = true;
                this.getPrivacyOptions();
                this.getSatisfactionDocuments();
                this.getProjects();
                (this.$route.params.id) ? axios.get(`/api/project/${this.$route.params.id}`)
                .then(
                    (
                        {data})=>(
                            this.loading = false,
                            this.project = data, 
                            this.form.fill(data)
                        )
                ) : this.loading = false;
            },

            getProjects() {
                axios.get('/api/project')
                .then(
                    ({data}) => (
                        this.projects = data
                    )
                )
            },

            getPrivacyOptions(){
                axios.get('/api/privacy-option')
                .then(
                    ({data}) => (
                        this.privacy_options = data
                    )
                )
            },
            getSatisfactionDocuments(){
                axios.get('/api/satisfaction_document')
                .then(
                    ({data}) => {
                        this.satisfaction_documents = data
                    }
                )
            },
        },
        created(){
            Fire.$on('AfterCreate',() => {
                this.getProject();
            });
            this.getProject();
            this.getActiveUser();
            if(this.$route.params.s_active) this.active_section = this.$route.params.s_active;
        },
        mounted(){
            
        }
    }
</script>

<style lang="scss" scoped>
    .fa-2-5x{
       font-size: 2.5em; 
    }
    .button-text{
        position: relative;
        bottom: 0.6em;
    }
    .page-link-left{
        border-top-left-radius: 0px !important;
        border-bottom-left-radius: 0px !important;
    }
    .page-link-right{
        border-top-right-radius: 0px !important;
        border-bottom-right-radius: 0px !important;
    }
    .page-link{
        box-shadow: none !important;
        border: 0px;
    }
</style>