<template>
    <div class="positions">
        <Loading v-if="loading" />
        <div class="row justify-content-md-center" v-else>
            <div class="col-md-10">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><router-link class="text-orange" to="/home">Inicio</router-link></li>
                        <li class="breadcrumb-item"><router-link class="text-orange" to="/manage/home">Administración</router-link></li>
                        <li class="breadcrumb-item active" aria-current="page">Personal</li>
                        <li class="breadcrumb-item active" aria-current="page">Posiciones</li>
                    </ol>
                </nav>
            </div>
            <div class="col-md-10">
                <h1 class="h2 text-center font-weight-bold">
                    Posiciones
                </h1>
                <button type="button" class="btn btn-outline-orange float-right font-weight-bold pointer" @click="newModal">
                    <i class="fas fa-plus mr-1"></i>
                    Agregar posición
                </button>
            </div>
            <div class="col-md-10 mt-3">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th class="text-center">Fecha de creación</th>
                                <th class="text-center"></th>
                                <th class="text-center"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="position in positions" :key="position.id">
                                <td>{{ position.name }}</td>
                                <td class="text-center">{{ position.created_at | myDate }}</td>
                                <td class="text-center">
                                    <a class="text-dark" @click="editModal(position)">
                                        <i class="fas fa-pencil-alt pointer" data-toggle="tooltip" data-placement="top" title="Editar"></i>
                                    </a> 
                                </td>
                                <td class="text-center">
                                    <a class="text-dark" @click="deletePosition(position)">
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
                            Agregar posición
                        </h5>
                        <h5 class="modal-title" id="ModalTitle" v-show="editmode">
                            Editar posición
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form @submit.prevent="!editmode ? createPosition() : updatePosition()">
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
    export default {
        components: {
            Loading
        },
        data: () => ({
            loading: false,
            editmode:false,
            positions: [],
            form: new Form({
                id:'',
                name:'',
            })
        }),
        methods:{
            getPositions(){
                this.loading = true;
                axios.get('/api/position')
                .then(
                    ({data}) => (
                        this.loading = false,
                        this.positions = data
                    )
                )
            },
            newModal(){
                this.editmode = false;
                this.form.reset();
                $('#Modal').modal('show');
            },
            createPosition(){
                this.$Progress.start();
                this.form.post('/api/position')
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
            editModal(position){
                this.editmode = true;
                this.form.reset();
                $('#Modal').modal('show');
                this.form.fill(position);
            },
            updatePosition(){
                this.$Progress.start();
                this.form.put('/api/position/'+this.form.id)
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
            deletePosition(position){
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
                        axios.delete(`/api/position/${position.id}`).then(()=>{
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
        created(){
            this.getPositions();
            Fire.$on('AfterCreate',() => {
                this.getPositions();
            });
        }
    }
</script>

<style lang="scss" scoped>

</style>