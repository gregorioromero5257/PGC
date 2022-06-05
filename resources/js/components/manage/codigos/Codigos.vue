<template>
    <div class="companies">
        <Loading v-if="loading" />
        <div class="row justify-content-md-center" v-else>
            <div class="col-md-10">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><router-link class="text-orange" to="/home">Inicio</router-link></li>
                        <li class="breadcrumb-item"><router-link class="text-orange" to="/manage/home">Administración</router-link></li>
                        <li class="breadcrumb-item active" aria-current="page">Proyectos</li>
                        <li class="breadcrumb-item active" aria-current="page">Códigos</li>
                    </ol>
                </nav>
            </div>
            <div class="col-md-10">
                <h1 class="h2 text-center font-weight-bold">Lista de códigos activos</h1>
                <button type="button" class="btn btn-outline-orange float-right font-weight-bold pointer" @click="newModal">
                    <i class="fas fa-plus mr-1"></i>
                    Agregar
                </button>
            </div>
            <div class="col-md-10 mt-3">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Código</th>
                                <th>Organización</th>
                                <th>Categoría</th>
                                <th>Comentarios</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="code in codes" :key="codes.id">
                                <td>{{ code.codigo }}</td>
                                <td>{{ code.organizacion }}</td>
                                <td>{{ code.categoria}}</td>
                                <td>{{ code.comentarios}}</td>
                                <td>
                                    <a class="text-dark pointer" @click="editModal(code)">
                                        <i class="fas fa-pencil-alt pointer" data-toggle="tooltip" data-placement="top" title="Editar"></i>
                                    </a>
                                <!-- </td>
                                <td> -->
                                    <a class="text-dark pointer" @click="deleteCompany(code)">
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
                        <h5 class="modal-title" id="ModalTitle" v-show="!editmode">Agregar código</h5>
                        <h5 class="modal-title" id="ModalTitle" v-show="editmode">Editar código</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form @submit.prevent="!editmode ? createCompany() : updateCompany()">
                            <div class="form-group">
                                <label for="name">Código</label>
                                <input v-model="form.codigo" type="text" id="name" class="form-control form-control-lg" :class="{ 'is-invalid': form.errors.has('name') }" name="name">
                                <has-error :form="form" field="name"></has-error>
                            </div>
                            <div class="form-group">
                                <label for="name">Organización</label>
                                <v-select label="name" placeholder="Seleccione una opción" :options="organizaciones" v-model="form.organizacion" ></v-select>
                                <!-- <input v-model="form.organizacion" type="text" id="name" class="form-control form-control-lg" :class="{ 'is-invalid': form.errors.has('name') }" name="name"> -->
                                <has-error :form="form" field="name"></has-error>
                            </div>
                            <div class="form-group">
                                <label for="name">Categoría</label>
                                <v-select label="name" placeholder="Seleccione una opción" :options="categorias" v-model="form.categoria" ></v-select>
                                <!-- <input v-model="form.categoria" type="text" id="name" class="form-control form-control-lg" :class="{ 'is-invalid': form.errors.has('name') }" name="name"> -->
                                <has-error :form="form" field="name"></has-error>
                            </div>
                            <div class="form-group">
                                <label for="name">Comentarios</label>
                                <input v-model="form.comentarios" type="text" id="name" class="form-control form-control-lg" :class="{ 'is-invalid': form.errors.has('name') }" name="name">
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
            editmode: false,
            codes: [],
            categorias : ['Desarrollo de clientes','No laborado','Overhead','Ovearhead','Proyectos','Administración de proyectos'],
            organizaciones : ['C230 Consulting','C230 Colombia','C230 Méx','IDEA','Supernova'],
            form: new Form({
                id:'',
                codigo:'',
                organizacion:'',
                categoria:'',
                comentarios:'',
            })
        }),
        methods: {
            getCodes(){
                this.loading = true;
                axios.get('/api/project-codes')
                .then(
                    ({data}) => (
                        this.loading = false,
                        this.codes = data
                    )
                )
            },
            newModal(){
                this.editmode = false;
                this.form.reset();
                $('#Modal').modal('show');
            },
            createCompany(){
                this.$Progress.start();
                this.form.post('/api/project-codes')
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
            editModal(code){
                this.editmode = true;
                this.form.reset();
                $('#Modal').modal('show');
                this.form.fill(code);
            },
            updateCompany(company){
                this.$Progress.start();
                this.form.put('/api/project-codes/'+this.form.id)
                .then(() => {
                    $('#Modal').modal('hide');
                    toast.fire({
                        type: 'success',
                        title: `Editado correctamente.`
                    })
                    this.$Progress.finish();
                    Fire.$emit('AfterCreate');
                }).catch(() => {
                    this.$Progress.fail();
                });
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
                        axios.delete(`/api/project-codes/${code.id}`).then(()=>{
                            swal.fire(
                                'Eliminado',
                                '-',
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
            this.getCodes();
            Fire.$on('AfterCreate',() => {
                this.getCodes();
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
