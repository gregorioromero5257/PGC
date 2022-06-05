<template>
    <div class="AddVacancy">
        <nav class="navbar navbar-light bg-orange  justify-content-center" v-if="$route.path == '/vacancies/edit/'+$route.params.id && $gate.isActive()">
            <span class="navbar-text">
                Estás editando la información de esta vacante
            </span>
        </nav>
        <div class="container py-2">
            <div class="row">
                <div class="col-md-12">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a class="text-orange" href="/home">Inicio</a></li>
                            <li class="breadcrumb-item"><a class="text-orange" href="/vacancies">Vacantes</a></li>
                            <li v-if="$route.path == '/vacancies/create'" class="breadcrumb-item active" aria-current="page">Agregar vacante</li>
                            <li v-if="$route.path == '/vacancies/edit/'+$route.params.id" class="breadcrumb-item active" aria-current="page">
                                <router-link class="text-orange" :to="'/vacancies/'+form.slug">{{ form.slug }}</router-link>
                            </li>
                            <li v-if="$route.path == '/vacancies/edit/'+$route.params.id" class="breadcrumb-item active" aria-current="page">Editar</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <div class="row" v-if="$route.path == '/vacancies/create'">
                <div class="col-md-12">
                    <h1 class="h2 text-center font-weight-bold">Agregar vacante</h1>
                </div>
            </div>
            <form @submit.prevent="$route.path == '/vacancies/create' ? createVacancy() : updateVacancy()">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="name">Nombre de la vacante</label>
                        <input v-model="form.name" type="text" id="name" class="form-control form-control-lg" :class="{ 'is-invalid': form.errors.has('name') }" name="name">
                        <has-error :form="form" field="name"></has-error>
                    </div>

                    <div class="form-group">
                        <label for="department_id">Departamento</label>
                        <multiselect 
                            v-model="form.department" 
                            :options="departments" 
                            :showLabels="false"
                            placeholder="" 
                            label="name"
                            track-by="name"
                        >
                        </multiselect>
                    </div>

                    <div class="form-group">
                        <label for="short_description">Descripción corta</label>
                        <textarea v-model="form.short_description" class="form-control form-control-lg mb-4" id="short_description"></textarea>
                        <has-error :form="form" field="short_description"></has-error>
                    </div>

                    <div class="form-group">
                        <label for="requirements">Requisitos de la vacante</label>
                        <vue-editor 
                            v-model="form.requirements"
                            :editor-toolbar="customToolbar"
                        >
                        </vue-editor>
                        <has-error :form="form" field="requirements"></has-error>
                    </div>

                    <div class="form-group">
                        <label for="description">Descripción de la vacante</label>
                        <vue-editor 
                            v-model="form.description"
                            :editor-toolbar="customToolbar"
                        >
                        </vue-editor>
                        <has-error :form="form" field="description"></has-error>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="form-group text-center mb-5">
                        <button type="submit" class="btn btn-orange btn-rounded">
                            <span v-if="$route.path == '/vacancies/create'">
                                Agregar vacante
                            </span>
                            <span v-if="$route.path == '/vacancies/edit/'+$route.params.id">
                                Editar vacante
                            </span>
                        </button>
                    </div>
                </div>
            </div>

            </form>
        </div>
    </div>
</template>

<script>
    import NoActive from '../complements/NoActive'
    import Multiselect from 'vue-multiselect'
    import NoAccess from '../complements/NoAccess'
    import Loading from '../complements/Loading'
    import { VueEditor } from "vue2-editor";
    export default {
        components: {
            Multiselect, 
            NoAccess, 
            Loading, 
            NoActive,
            VueEditor
        },
        data: () => ({
            loading: false,
            customToolbar: [
                ["bold", "italic", "underline"],
                [{ list: "ordered" }, { list: "bullet" }]
            ],
            departments: [],
            form: new Form({
                id: '',
                name:'',
                short_description:'',
                requirements: '',
                description: '',
                active: 1,
                department: '',
                department_id: '',
                slug: '',
                created_at: '',
                updated_at: '',
            })
        }),
        methods:{
            createVacancy(){
                this.$Progress.start();

                this.form.department_id = this.form.department.id;

                this.form.post('/api/vacancy')
                .then((response)=>{
                    toast.fire({type: 'success',title: `Vacante ${response.data.name} agregada correctamente.`})
                    this.$router.push(`/vacancies/${response.data.slug}`)
                    this.$Progress.finish();
                })

            },
            updateVacancy(){
                this.$Progress.start();

                if (this.form.department_id) this.form.department_id = this.form.department.id;

                this.form.put(`/api/vacancy/${this.form.id}`)
                .then((response) => {
                    this.$router.push(`/vacancies/${response.data.slug}`)
                    toast.fire({
                        type: 'success',
                        title: `Vacante ${response.data.slug} editada correctamente.`
                    })
                    this.$Progress.finish();
                })
                .catch(() => {
                    this.$Progress.fail();
                })
            },
            getDepartments(){
                axios.get('/api/department')
                .then(
                    ({data}) => {
                        this.departments = data
                    }
                )
            }
        },
        created(){
            this.getDepartments();

            if(this.$route.params.id){
                this.loading = true;
                axios.get(`/api/vacancy-by-slug/${this.$route.params.id}`)
                .then(
                    ({data}) => (
                        this.loading = false,
                        this.form.fill(data)
                    )
                )
            }
        }
    }
</script>

<style lang="scss" scoped>

</style>