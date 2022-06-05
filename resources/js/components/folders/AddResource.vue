<template>
    <!-- Modal -->
    <div class="modal fade" id="resourceModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
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
                  <h5 class="modal-title" id="exampleModalCenterTitle">Agregar archivo</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <form @submit.prevent="AddResource">
                  <div class="modal-body">
                    <div class="form-group">
                      <label for="name">Nombre del archivo</label>
                      <input v-model="form.name" type="text" class="form-control"  id="name">
                    </div>
                    <div class="form-group">
                      <label for="name">Video</label>
                      <input v-model="form.video" type="checkbox" class="form"  id="name">
                    </div>

                    <div class="form-group">
                      <label for="exampleFormControlFile1">Documento</label>
                      <input @change="onImageChange" type="file" class="form-control-file" id="exampleFormControlFile1">
                    </div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-orange">Crear</button>
                  </div>
                </form>
              </template>
            </div>
        </div>
    </div>
</template>

<script>
// import Loading from '../complements/Loading'

    export default {
        props: {
            // folder: Object,
            id_folder: Number,

        },
        data: () => ({
            form: {
                id:'',
                name:'',
                type:'',
                folder_id:'',
                file:'',
                video : false,
            },
            visible: false
        }),
        methods:{
            onImageChange(e) {
                this.form.file = e.target.files[0];
            },
            vaciar(){

                  this.form.id = '';
                  this.form.name = '';
                  this.form.type = '';
                  this.form.folder_id = '';
                  this.form.file = '';
                  this.form.video = false;

            },
            AddResource(){
                // const config = {
                //     headers: { 'content-type': 'multipart/form-data' }
                // }
                this.visible = true;
                let formData = new FormData();
                formData.append('name', this.form.name);
                formData.append('file', this.form.file);
                formData.append('folder_id', this.id_folder);
                formData.append('video', this.form.video);

                // this.$Progress.start();

                axios.post(`/api/resource`, formData)
                .then(response => {
                  // Fire.$emit('AfterCreate');
                  $('#resourceModal').modal('hide')
                  toast.fire({
                      type: 'success',
                      title: 'Archivo creado con éxito'
                  })
                  // this.$Progress.finish();
                  // this.form.reset();
                  this.$emit('refresh-folders',response.data);
                  this.vaciar();
                  this.visible = false;

                })
                .catch((e)=>{
                  console.log(e);
                    toast.fire({
                        type: 'error',
                        title: 'Error al agregar archivo'
                    });
                    this.visible = false;
                    // this.$Progress.fail();
                });
            }
        },
        created(){

        }
    }
</script>

<style lang="scss" scoped>

</style>
