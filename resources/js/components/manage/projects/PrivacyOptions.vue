<template>
    <div class="privacy_options">
        <Loading v-if="loading" />
        <div class="row justify-content-md-center" v-else>
            <div class="col-md-10">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><router-link class="text-orange" to="/home">Inicio</router-link></li>
                        <li class="breadcrumb-item"><router-link class="text-orange" to="/manage/home">Administración</router-link></li>
                        <li class="breadcrumb-item active" aria-current="page">Proyectos</li>
                        <li class="breadcrumb-item active" aria-current="page">Opciones de privacidad</li>
                    </ol>
                </nav>
            </div>
            <div class="col-md-10">
                <h1 class="h2 text-center font-weight-bold">Opciones de Privacidad</h1>
                <button type="button" class="btn btn-outline-orange float-right font-weight-bold pointer" @click="newModal">
                    <i class="fas fa-plus mr-1"></i>
                    Agregar opción
                </button>
            </div>
            <div class="col-md-10 mt-3">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Fecha de creación</th>
                                <th></th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="privacy_option in privacy_options" :key="privacy_option.id">
                                <td>{{ privacy_option.name }}</td>
                                <td>{{ privacy_option.created_at | myDate }}</td>
                                <td>
                                    <a class="text-dark pointer" @click="editModal(privacy_option)">
                                        <i class="fas fa-pencil-alt pointer" data-toggle="tooltip" data-placement="top" title="Editar"></i>
                                    </a> 
                                </td>
                                <td>
                                    <a class="text-dark pointer" @click="deletePrivacyOption(privacy_option)">
                                        <i class="fa fa-trash-alt pointer" data-toggle="tooltip" data-placement="top" title="Eliminar"></i>
                                    </a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="modal fade" id="Modal" tabindex="-1" role="dialog" aria-labelledby="ModalTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="ModalTitle" v-show="!editmode">Agregar opción de privacidad</h5>
                        <h5 class="modal-title" id="ModalTitle" v-show="editmode">Editar opción de privacidad</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form @submit.prevent="!editmode ? createPrivacyOption() : updatePrivacyOption()">
                            <div class="form-group">
                                <label for="name">Nombre</label>
                                <input v-model="form.name" type="text" id="name" class="form-control form-control-lg" :class="{ 'is-invalid': form.errors.has('name') }" name="name">
                                <has-error :form="form" field="name"></has-error>
                            </div>
                            <div class="form-group text-center">
                                <button type="submit" class="btn btn-orange btn-rounded" v-show="!editmode">Guardar</button>
                                <button type="submit" class="btn btn-orange btn-rounded" v-show="editmode">Editar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import Loading from '../../complements/Loading'
    import Sidenav from '../../complements/Sidenav'
    export default {
        components: {
            Sidenav, Loading
        },
        data: () => ({
            loading: false,
            editmode: false,
            privacy_options: [],
            form: new Form({
                id:'',
                name:'',
            })
        }),
        methods: {
            getPrivacyOptions(){
                this.loading = true;
                axios.get('/api/privacy-option')
                .then(
                    ({data}) => (
                        this.loading = false,
                        this.privacy_options = data
                    )
                )
            },
            newModal(){
                this.editmode = false;
                this.form.reset();
                $('#Modal').modal('show');
            },
            createPrivacyOption(){
                this.$Progress.start();
                this.form.post('/api/privacy-option')
                .then((response)=>{
                    toast.fire({
                        type: 'success',
                        title: `${response.data.name} agregada correctamente.`
                    })
                    this.$Progress.finish();
                    $('#Modal').modal('hide');
                    Fire.$emit('AfterCreate');
                })
                .catch(()=>{
                    this.$Progress.fail();
                })
            },
            editModal(privacy_option){
                this.editmode = true;
                this.form.reset();
                $('#Modal').modal('show');
                this.form.fill(privacy_option);
            },
            updatePrivacyOption(){
                this.$Progress.start();
                this.form.put('/api/privacy-option/'+this.form.id)
                .then(() => {
                    $('#Modal').modal('hide');
                    toast.fire({
                        type: 'success',
                        title: `Opción de privacidad editada correctamente.`
                    })
                    this.$Progress.finish();
                    Fire.$emit('AfterCreate');
                }).catch(() => {
                    this.$Progress.fail();
                });
            },
            deletePrivacyOption(privacy_option){
                swal.fire({
                    title: '¿Estás seguro?',
                    text: "¡No podrás revertir esto!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sí, eliminar opción',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.value) {
                        this.$Progress.start();
                        axios.delete(`/api/privacy-option/${privacy_option.id}`).then(()=>{
                            swal.fire(
                                'Eliminado',
                                'La opción de privacidad ha sido eliminada.',
                                'success'
                            )
                            Fire.$emit('AfterCreate');
                            this.$Progress.finish();
                        }).catch(()=> {
                            swal("Error", "Algo salió mal", "warning");
                            this.$Progress.fail();
                        })
                        
                    }
                })
            },
        },
        created(){
            this.getPrivacyOptions();
            Fire.$on('AfterCreate',() => {
                this.getPrivacyOptions();
            });
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