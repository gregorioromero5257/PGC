<template>
    <div class="add-staff-member">
        <Loading v-if="loading" />
        <nav class="navbar navbar-light bg-orange  justify-content-center" v-if="$route.path == '/staff/edit/'+$route.params.id && ($gate.activeUser().id == form.id || $gate.activeUser().role_id == 1 || $gate.activeUser().role_id == 2) && !loading && $gate.isActive()">
            <span class="navbar-text">
                Estás editando la información de este colaborador
            </span>
        </nav>
        <div class="container" v-if="($gate.activeUser().id == form.id || $gate.activeUser().role_id == 1 || $gate.activeUser().role_id == 2 || $gate.activeUser().role_id == 3) && !loading && $gate.isActive()">
            <div class="row">
                <div class="col-md-12">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><router-link class="text-orange" to="/home">Inicio</router-link></li>
                            <li class="breadcrumb-item"><router-link class="text-orange" to="/staff">Colaboradores</router-link></li>
                            <li v-if="$route.path == '/staff/create'" class="breadcrumb-item active" aria-current="page">Agregar colaborador</li>
                            <li v-if="$route.path == '/staff/edit/'+$route.params.id" class="breadcrumb-item" aria-current="page">
                                <router-link class="text-orange" :to="'/staff/'+$route.params.id">
                                    {{ form.name }}
                                </router-link>
                            </li>
                            <li v-if="$route.path == '/staff/edit/'+$route.params.id" class="breadcrumb-item active" aria-current="page">Editar</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 text-center mb-3">
                    <img v-if="form.avatar" class="profile-avatar pointer" :src="form.avatar" alt="avatar">
                    <i v-else-if="$route.path == '/staff/edit/'+$route.params.id" class="pointer far fa-user-circle profile-avatar-icon text-secondary"></i>
                </div>
                <div class="col-md-12">
                    <h1 class="h2 text-center font-weight-bold">
                        <span v-if="$route.path == '/staff/create'">
                            Agregar colaborador
                        </span>
                    </h1>
                </div>
            </div>
            <form @submit.prevent="$route.path == '/staff/create' ? createUser() : updateUser()">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="name">Nombre completo</label>
                        <input v-model="form.name" type="text" id="name" class="form-control form-control-lg" :class="{ 'is-invalid': form.errors.has('name') }" name="name">
                        <has-error :form="form" field="name"></has-error>
                    </div>
                    <div class="form-group">
                        <label for="email">Correo electrónico</label>
                        <input v-model="form.email" id="email" type="email" class="form-control form-control-lg" :class="{ 'is-invalid': form.errors.has('email') }" name="email">
                        <has-error :form="form" field="email"></has-error>
                    </div>
                    <div class="form-group">
                        <label for="exampleFormControlFile1">Avatar</label>
                        <input type="file" class="form-control-file" id="avatar" @change="updateAvatar">
                    </div>
                    <div class="form-group" v-if="$gate.activeUser().role_id == 1 || $gate.activeUser().role_id == 2">
                        <label for="type">Rol</label>
                        <multiselect
                            v-model="form.role"
                            :options="filteredRoles"
                            :showLabels="false"
                            placeholder=""
                            label="name"
                            :disabled="!$gate.isDeveloper() && form.role && form.role.id == 1"
                            track-by="name">
                        </multiselect>
                        <small v-if="!$gate.isDeveloper() && form.role && form.role.id == 1" id="passwordHelp" class="form-text text-muted">No tienes los permisos para editar el rol de este usuario.</small>
                    </div>
                    <div class="form-group" v-if="$gate.activeUser().role_id == 1 || $gate.activeUser().role_id == 2 || $gate.activeUser().role_id == 3">
                        <label for="position">Posición</label>
                        <multiselect
                            v-model="form.position"
                            :options="positions"
                            :showLabels="false"
                            placeholder=""
                            label="name"
                            track-by="name">
                        </multiselect>
                    </div>
                    <div class="form-group">
                        <label for="organizacion">Organización</label>
                        <input v-model="form.organizacion" id="organizacion" type="organizacion" class="form-control form-control-lg" :class="{ 'is-invalid': form.errors.has('organizacion') }" name="organizacion">
                        <has-error :form="form" field="organizacion"></has-error>
                    </div>
                    <div class="form-group">
                        <label for="bio_es">Semblanza</label>
                        <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="pills-spanish-tab" data-toggle="pill" href="#pills-spanish" role="tab" aria-controls="pills-spanish" aria-selected="true">Español</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="pills-english-tab" data-toggle="pill" href="#pills-english" role="tab" aria-controls="pills-english" aria-selected="false">Inglés</a>
                            </li>
                        </ul>
                        <div class="tab-content" id="pills-tabContent">
                            <div class="tab-pane fade show active" id="pills-spanish" role="tabpanel" aria-labelledby="pills-spanish-tab">
                                <textarea v-model="form.bio_es" class="form-control form-control-lg mb-4" id="bio_es"></textarea>
                            </div>
                            <div class="tab-pane fade" id="pills-english" role="tabpanel" aria-labelledby="pills-english-tab">
                                <textarea v-model="form.bio_en" class="form-control form-control-lg" id="bio_en"></textarea>
                            </div>
                        </div>
                    </div>
<!--                     <div class="form-group">
                        <label for="active">Estatus</label>
                        <i class="fas fa-lg pointer" :class="{ 'fa-toggle-off' : form.active == 0, 'fa-toggle-on text-orange' : form.active == 1 }" @click="toogleStatus"></i>
                    </div> -->
                    <div class="form-group">
                        <label for="password">Contraseña</label>
                        <input v-model="form.password" type="password" class="form-control form-control-lg" :class="{ 'is-invalid': form.errors.has('name') }" id="password">
                        <has-error :form="form" field="password"></has-error>
                        <small v-if="$route.path !== '/staff/create'" id="passwordHelp" class="form-text text-muted">Dejar en blanco si no desea cambiar la contraseña.</small>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group text-center mb-5">
                        <button type="submit" class="btn btn-orange btn-rounded">
                            <span v-if="$route.path == '/staff/create'">
                                Agregar colaborador
                            </span>
                            <span v-if="$route.path == '/staff/edit/'+$route.params.id">
                                Guardar cambios
                            </span>
                        </button>
                    </div>
                </div>
            </div>
            </form>
        </div>
        <NoAccess v-if="!($gate.activeUser().id == form.id || $gate.activeUser().role_id == 1 || $gate.activeUser().role_id == 2) && $gate.isActive() && !loading" />
        <NoActive v-if="!loading && !$gate.isActive()" />
    </div>
</template>

<script>
    import NoActive from '../complements/NoActive'
    import Multiselect from 'vue-multiselect'
    import NoAccess from '../complements/NoAccess'
    import Loading from '../complements/Loading'
    import {
        Heading,
        Blockquote,
        Bold,
        HardBreak,
        Italic
    } from 'tiptap-extensions'
    export default {
        components: {
            Multiselect,
            NoAccess,
            Loading,
            NoActive,
        },
        data: () => ({
            loading: false,
            roles:[],
            positions:[],
            form: new Form({
                id:'',
                name:'',
                email:'',
                type:'',
                role_id:'',
                role:{},
                position_id:'',
                position:{},
                organizacion : '',
                bio_es:'',
                bio_en:'',
                avatar:'',
                password:'',
                active:1,
                files:[],
            }),
        }),
        methods:{
            goToProfile(id){
                (this.$route.path !== '/staff/create') ? console.log(this.$router.push(`/staff/${id}`)) : false
            },
            getRoles(){
                axios.get(`/api/role`)
                .then(
                    ({data}) => (
                        this.roles = data
                    )
                )
            },
            getPositions(){
                axios.get(`/api/position`)
                .then(
                    ({data}) => (
                        this.positions = data
                    )
                )
            },
            fileDeleted(){
                Fire.$emit('AfterCreate');
            },
            updateAvatar(e){
                let file = e.target.files[0];
                let reader = new FileReader();
                reader.onloadend = (file) => {
                    this.form.avatar = reader.result;
                }
                reader.readAsDataURL(file);
            },
            toogleStatus(){
                this.form.active = 1 - this.form.active;
            },
            createUser(){
                let user_files = this.form.files
                this.$Progress.start();
                this.form.role_id = this.form.role.id;
                this.form.position_id = this.form.position.id;
                this.form.post('/api/user')
                .then((response)=>{
                    toast.fire({type: 'success',title: `${response.data.name} agregado/a correctamente.`})
                    this.$router.push(`/staff/${response.data.id}`)
                    this.$Progress.finish();
                })
                .catch(()=>{
                    this.$Progress.fail();
                })
            },
            updateUser(){
                this.$Progress.start();
                if (this.form.role) this.form.role_id = this.form.role.id;
                if (this.form.position) this.form.position_id = this.form.position.id;
                this.form.put(`/api/user/${this.form.id}`)
                .then(() => {
                    this.$router.push(`/staff/${this.form.id}`)
                    toast.fire({
                        type: 'success',
                        title: `Información de ${this.form.name} editada correctamente.`
                    })
                    this.$Progress.finish();
                })
                .catch(() =>{
                    this.$Progress.fail();
                })
            },
        },
        computed: {
            filteredRoles: function () {
                return (this.$gate.isDeveloper()) ? this.roles : this.roles.filter(role => role.id !== 1)
            }
        },
        created(){
            this.getRoles();
            this.getPositions();
            if(this.$route.params.id){
                this.loading = true;
                axios.get(`/api/user/${this.$route.params.id}`)
                .then(
                    ({data}) => (
                        this.loading = false,
                        this.form.fill(data)
                    )
                )
            }
            Fire.$on('AfterCreate',() => {
                if(this.$route.params.id){
                    this.loading = true;
                    axios.get(`/api/user/${this.$route.params.id}`)
                    .then(
                        ({data}) => (
                            this.loading = false,
                            this.form.fill(data)
                        )
                    )
                }
            });
        },
    }
</script>

<style lang="scss" scoped>

</style>
