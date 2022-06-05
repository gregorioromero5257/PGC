<template>
    <div class="stackeholders">
        <Loading v-if="loading" />
        <div class="row justify-content-md-center" v-else>
            <div class="col-md-10">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><router-link class="text-orange" to="/home">Inicio</router-link></li>
                        <li class="breadcrumb-item"><router-link class="text-orange" to="/manage/home">Administración</router-link></li>
                        <li class="breadcrumb-item active" aria-current="page">Partes interesadas</li>
                    </ol>
                </nav>
            </div>
            <div class="col-md-10">
                <h1 class="h2 text-center font-weight-bold">
                    <span>Partes interesadas</span>
                </h1>
                <button type="button" class="btn btn-outline-orange float-right font-weight-bold pointer" @click="newModal">
                    <i class="fas fa-plus mr-1"></i>
                    <span>Agregar</span>
                </button>
            </div>
            <div class="col-md-10 mt-3">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th class="text-center">Tipo</th>
                                <th></th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="stackeholder in stackeholders" :key="stackeholder.id">
                                <td>{{ stackeholder.name }}</td>
                                <td class="text-center" v-if=" stackeholder.sh_description">{{ stackeholder.sh_description.name }}</td>
                                <td class="text-center" v-else> Sin registro</td>
                                <td class="text-center">
                                    <a class="text-dark" @click="editModal(stackeholder)">
                                        <i class="fas fa-pencil-alt pointer" data-toggle="tooltip" data-placement="top" title="Editar"></i>
                                    </a> 
                                </td>
                                <td class="text-center">
                                    <a class="text-dark" @click="deleteStackeholder(stackeholder)" v-if="$gate.activeUser().role_id == 1 || $gate.activeUser().role_id == 2 || $gate.activeUser().id == stackeholder.user_id">
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
                        <h5 class="modal-title" id="ModalTitle" v-show="!editmode">
                            <span>Agregar</span>
                        </h5>
                        <h5 class="modal-title" id="ModalTitle" v-show="editmode">
                            <span>Editar</span>
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form @submit.prevent="!editmode ? createStackeholder() : updateStackeholder()">
                            <div class="form-group">
                                <label for="name">Nombre</label>
                                <input v-model="form.name" type="text" id="name" class="form-control form-control-lg" :class="{ 'is-invalid': form.errors.has('name') }" name="name">
                                <has-error :form="form" field="name"></has-error>
                            </div>
                            <div class="form-group">
                                <label for="sh_description">Tipo</label>
                                <multiselect 
                                    v-model="form.sh_description" 
                                    :options="sh_descriptions" 
                                    :showLabels="false"
                                    placeholder="" 
                                    label="name"
                                    track-by="name">
                                </multiselect>
                                <has-error :form="form" field="sh_description"></has-error>
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
    import Multiselect from 'vue-multiselect'
    export default {
        components: {
            Multiselect, Loading
        },
        data: () => ({
            loading: false,
            editmode:false,
            stackeholders:[],
            sh_descriptions:[],
            form: new Form({
                id:'',
                name:'',
                sh_description:{},
                sh_description_id:'',
                sh_type:{},
                sh_type_id:1,
                user_id:'',
            })
        }),
        methods: {
            getStackeholders(){
                this.loading = true;
                axios.get('/api/stackeholder')
                .then(
                    ({data}) => (
                        this.loading = false,
                        this.stackeholders = data
                    )
                )
            },
            getShDescription(){
                axios.get('/api/sh-description')
                .then(
                    ({data}) => (
                        this.sh_descriptions = data
                    )
                )
            },
            newModal(){
                this.editmode = false;
                this.form.reset();
                $('#Modal').modal('show');
            },
            createStackeholder(){
                this.$Progress.start();
                if (this.form.sh_description) this.form.sh_description_id = this.form.sh_description.id;
                this.form.sh_type_id = 1;
                this.form.post('/api/stackeholder')
                .then((response)=>{
                    toast.fire({
                        type: 'success',
                        title: `Agregado correctamente.`
                    })
                    this.$Progress.finish();
                    $('#Modal').modal('hide');
                    Fire.$emit('AfterCreate');
                })
                .catch(()=>{
                    this.$Progress.fail();
                })
            },
            editModal(stackeholder){
                this.editmode = true;
                this.form.reset();
                $('#Modal').modal('show');
                this.form.fill(stackeholder);
            },
            updateStackeholder(){
                this.$Progress.start();
                if (this.form.sh_description) this.form.sh_description_id = this.form.sh_description.id;
                this.form.sh_type_id = 1;
                this.form.put('/api/stackeholder/'+this.form.id)
                .then(() => {
                    $('#Modal').modal('hide');
                    toast.fire({
                        type: 'success',
                        title: `Información editada correctamente.`
                    })
                    this.$Progress.finish();
                    Fire.$emit('AfterCreate');
                }).catch(() => {
                    this.$Progress.fail();
                });
            },
            deleteStackeholder(stackeholder){
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
                        axios.delete(`/api/stackeholder/${stackeholder.id}`).then(()=>{
                            swal.fire(
                                'Eliminado',
                                'Información eliminada.',
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
        computed: {
            getFilteredStackeholders: function () {
                return this.stackeholders.filter(stackeholder => stackeholder.sh_type_id == this.stackeholderType)
            },
        },
        created(){
            this.getStackeholders();
            this.getShDescription();
            Fire.$on('AfterCreate',() => {
                this.getStackeholders();
            });
        }
    }
</script>

<style lang="scss" scoped>
</style>