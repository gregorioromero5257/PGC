<template>
    <div class="row mb-4">
        <div class="col-md-12 d-lg-none">
            <p class="h5 font-weight-bold">
                Datos generales
            </p>
        </div>
        <div class="col-md-12">
            <button type="button" class="btn btn-link float-right text-dark" @click="projectConfiguration">
                <i class="fas fa-cog"></i> Configurar opciones del proyecto
            </button>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="code">Código de proyecto</label>
                <input v-model="form.code" type="text" class="form-control form-control-lg" :class="{ 'is-invalid': form.errors.has('code') }" id="code">
                <has-error :form="form" field="code"></has-error>
            </div>
            <div class="form-group">
                <label for="name_es">Nombre de proyecto</label>
                <input v-model="form.name_es" type="text" class="form-control form-control-lg" :class="{ 'is-invalid': form.errors.has('name_es') }" id="name_es">
                <has-error :form="form" field="name_es"></has-error>
            </div>
            <div class="form-group">
                <label for="name_en">Project name</label>
                <input v-model="form.name_en" type="text" class="form-control form-control-lg" :class="{ 'is-invalid': form.errors.has('name_en') }" id="name_en">
                <has-error :form="form" field="name_en"></has-error>
            </div>
            <div class="form-group">
                <label for="contract_name">Nombre del proyecto en contrato</label>
                <input v-model="form.contract_name" type="text" class="form-control form-control-lg" :class="{ 'is-invalid': form.errors.has('contract_name') }" id="contract_name">
                <has-error :form="form" field="contract_name"></has-error>
            </div>
            <div class="form-group">
                <label for="project_id">Proyecto vinculado</label>
                <multiselect
                    class="mb-2"
                    v-model="form.project"
                    :options="projects"
                    :multiple="false"
                    :close-on-select="true"
                    :clear-on-select="false"
                    :preserve-search="true"
                    :showLabels="false"
                    :custom-label="nameWithCode"
                    placeholder=""
                    label="code"
                    track-by="code"
                    :preselect-first="false"
                >
                    <template slot="selection" slot-scope="{ values, search, isOpen }"><span class="multiselect__single" v-if="values.length &amp;&amp; !isOpen">{{ values.length }} elemento(s) seleccionado(s)</span></template>
                </multiselect>
                <has-error :form="form" field="project"></has-error>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="location">Ubicación geográfica</label>
                <multiselect
                    class="mb-2"
                    v-model="form.locations"
                    :options="locations"
                    :multiple="true"
                    :close-on-select="false"
                    :clear-on-select="false"
                    :preserve-search="true"
                    :showLabels="false"
                    placeholder=""
                    label="name"
                    track-by="name"
                    :preselect-first="true"
                >
                    <template slot="selection" slot-scope="{ values, search, isOpen }"><span class="multiselect__single" v-if="values.length &amp;&amp; !isOpen">{{ values.length }} elemento(s) seleccionado(s)</span></template>
                </multiselect>
                <has-error :form="form" field="location"></has-error>
            </div>
            <div class="form-group">
                <label for="company_id">Empresa y oficina que realizaron el proyecto</label>
                <div class="row mt-2">
                    <div class="col-md-6 mb-2" v-for="company in companies" :key="company.id">
                        <i class="far fa-lg pointer" @click="changeCompany(company)" :class="{ 'fa-check-square' : company.id == form.company_id, 'fa-square' : company.id != form.company_id }"></i> {{ company.name }}
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="validity_of_confidentiality">Vigencia de confidencialidad</label>
                <input v-model="form.validity_of_confidentiality" type="date" class="form-control form-control-lg" :class="{ 'is-invalid': form.errors.has('validity_of_confidentiality') }" id="validity_of_confidentiality">
                <has-error :form="form" field="validity_of_confidentiality"></has-error>
            </div>
            <div class="form-group">
                <label for="comments">Comentarios adicionales</label>
                <textarea v-model="form.comments" class="form-control form-control-lg" id="comments" :class="{ 'is-invalid': form.errors.has('comments') }"></textarea>
                <has-error :form="form" field="comments"></has-error>
            </div>
            <div class="form-group">
                <label for="privacy">Privacidad</label>
                <div class="row mt-2 mb-pj">
                    <div class="col-md-6 mb-2" v-for="privacy_option in privacy_options" :key="privacy_option.id">
                        <i class="far fa-lg pointer"
                            @click="selectPrivacyOption(privacy_option)"
                            :class="{
                                'fa-check-square' : form.privacy_options.filter(po => po.id === privacy_option.id).length > 0,
                                'fa-square' : form.privacy_options.filter(po => po.id === privacy_option.id).length <= 0
                            }"></i> {{ privacy_option.name }}
                    </div>
                </div>
            </div>

        </div>
        <div class="col-md-12 mb-3">
            <p class="h5 font-weight-bold">
                Equipo de trabajo
            </p>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                <multiselect
                    class="mb-2"
                    v-model="form.members"
                    :options="users"
                    :multiple="true"
                    :close-on-select="false"
                    :clear-on-select="false"
                    :preserve-search="true"
                    :showLabels="false"
                    placeholder="Agregar miembro del equipo"
                    label="name"
                    track-by="name"
                    :preselect-first="true"
                >
                    <template slot="selection" slot-scope="{ values, search, isOpen }"><span class="multiselect__single" v-if="values.length &amp;&amp; !isOpen">{{ values.length }} miembro(s) seleccionado(s)</span></template>
                </multiselect>
            </div>
        </div>
        <div class="col-md-12 mt-2">
            <ul class="list-group list-group-flush" v-for="member in form.members" :key="member.id" v-show="form.members">
                <li class="list-group-item">
                    <div class="media pointer" @click="goToUser(member)">
                        <div v-if="member.avatar" class="avatar-bg mr-2" :style="{ 'background': 'url(' + member.avatar + ')' }"></div>
                        <div v-else class="avatar-bg mr-2" style="background-image: url('/img/user-circle-solid.svg');"></div>
                        <div class="media-body">
                            <i @click.stop="removeMember(member)" class="fas fa-times-circle pointer float-right" data-toggle="tooltip" data-placement="top" :title="'Remover '+member.name+' de la selección'"></i>
                            <h5 class="mt-2 mb-0">{{ member.name }}</h5>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</template>

<script>
    import Multiselect from 'vue-multiselect'
    export default {
        components: {
            Multiselect
        },
        props: {
            form: Object,
            privacy_options: Array,
            projects: Array,
        },
        data: () => ({
            locations: [],
            companies: [],
            users:[],
        }),
        methods: {
            projectConfiguration(){
                toast.fire({
                    type: 'info',
                    title: `Guarda la información del proyecto antes de configurar las opciones.`
                })
            },
            nameWithCode({ name_es, code }){
                return `(${code}) - ${name_es}`
            },
            goToUser(member){
                this.$router.push(`/staff/${member.id}`)
            },
            removeMember(member_selected){
                this.$props.form.members = this.$props.form.members.filter(member => member.id != member_selected.id);
            },
            getIndex(id){
                return this.form.privacy_options.findIndex(i => i.id === id)
            },
            changeCompany(company){
                this.$emit('changeCompany', company)
            },
            selectPrivacyOption(privacy_option){
                this.$emit('selectPrivacyOption', privacy_option)
            },
            getCompanies(){
                axios.get('/api/company')
                .then(
                    ({data}) => (
                        this.companies = data
                    )
                )
            },
            getLocations(){
                axios.get('/api/location')
                .then(
                    ({data}) => (
                        this.locations = data
                    )
                )
            },
            getUsers(){
                axios.get("/api/user")
                .then(
                    ({data}) => (
                        this.users = data
                    )
                )
            },
        },
        computed: {

        },
        created(){
            this.getCompanies();
            this.getLocations();
            this.getUsers();
        }
    }
</script>

<style lang="scss" scoped>
    .mb-pj {
        margin-bottom: 1.9rem !important;
    }
</style>
