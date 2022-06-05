<template>
    <!-- Modal -->
    <div class="modal fade" id="folderModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
              <template v-if="visible">
                <div class="row">
                    <div class="col-md-12 mt-4 text-center">
                        <p>
                            <i class="fas fa-spinner fa-pulse fa-4x"></i>
                        </p>
                        <p class="h4">Cargando...</p>
                    </div>
                </div>
              </template>
              <template v-else>

                <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalCenterTitle" v-show="!editmode">Nueva Carpeta</h5>
                  <h5 class="modal-title" id="exampleModalCenterTitle" v-show="editmode">Cambiar nombre de carpeta</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body">
                  <div class="form-group">
                    <label>Se guardará en <b>../{{title}}</b></label>

                    <input v-model="form.name" type="text" class="form-control form-control-lg" id="name" placeholder="Nombre de la carpeta">
                  </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn" data-dismiss="modal">Cancelar</button>
                  <button type="submit" class="btn btn-orange" v-show="!editmode" @click="AddFolder()">Crear</button>
                  <button type="submit" class="btn btn-orange" v-show="editmode">Aceptar</button>
                </div>
              </template>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            editmode: Boolean,
            id_folder: Number,
            title: String,
        },

        data: () => ({
            form: {
                id:'',
                name:'',
                order:'',
                upperfolder_id:''
            },
            visible: false

        }),
        methods: {
            AddFolder(){
              this.visible = true;

              axios.post('/api/folder',{
                'name' : this.form.name,
                'order' : this.form.order,
                'upperfolder_id' : this.id_folder,
              }).then(response => {
                this.$emit('saveFolder');
                $('#folderModal').modal('hide');
                this.visible = false;

              }).catch(e => {
                this.visible = false;

                console.error(e);
              })

                // this.form.post('/api/folder')
                // .then((response)=>{
                //     toast.fire({
                //         type: 'success',
                //         title: `Carpeta agregada correctamente.`
                //     })
                //     Fire.$emit('AfterCreate');
                //     this.$Progress.finish();
                //     $('#folderModal').modal('hide')
                // }).catch(()=>{
                //     this.$Progress.fail();
                //     toast.fire({
                //         type: 'error',
                //         title: `Error al crear proyecto.`
                //     })
                // })
            },
            EditFolder(){
                this.$Progress.start();
                this.form.put('/api/folder/'+this.form.id)
                .then(() => {
                    toast.fire({
                        type: 'success',
                        title: `Carpeta editada correctamente.`
                    })
                    Fire.$emit('AfterCreate');
                    this.$Progress.finish();
                    $('#folderModal').modal('hide')
                }).catch(()=>{
                    this.$Progress.fail();
                    toast.fire({
                        type: 'error',
                        title: `Error al editar carpeta.`
                    })
                })
            }
        },
        created(){

        }
    }
</script>

<style lang="scss" scoped>

</style>
