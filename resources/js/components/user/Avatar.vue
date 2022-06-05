<template>
  <main class="main">
    <!-- Modal -->
    <div class="modal fade" id="avatarModal" tabindex="-1"  data-backdrop="static" data-keyboard="false"
    role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <vue-element-loading :active="loading" spinner="spinner" color="#FFA000" text="Cargando..." :is-full-screen="true"/>
        <div class="modal-header">
          <h5 class="modal-title">Opciones</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body" align="center">
          <croppa v-model="myCroppa"
          :initial-image="user.avatar"
          placeholder="Elegir imagen"
          :disable-drag-and-drop="false"
          remove-button-color="black"
          :width="450"
          :height="450"
          :quality="2"
          :show-loading="true"
          :loading-size="50"
          :loading-color="'#606060'">
        </croppa>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success" @click="download()"> <i class="fas fa-download"></i> Descargar</button>
        <button type="button" v-show="edit || nuevo" class="btn btn-primary" @click="upload()"> <i class="fas fa-save"></i> Guardar</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal"> <i class="fas fa-close"></i> Cerrar</button>
      </div>
    </div>
  </div>
</div>
</main>

</template>
<script>

export default {
  name: "Avatar",
  props: {
    user: Object,
    edit : Boolean,
    nuevo : Boolean,
  },
  data: () => ({
    myCroppa: {},
    loading : false,
  }),
  methods:{
    upload() {
      if (!this.myCroppa.hasImage()) {
        alert('no image to upload')
        return
      }

      this.loading =true;
      this.myCroppa.generateBlob((blob) => {
        var fd = new FormData();
        fd.append('avatar', blob);
        fd.append('name', this.user.name);
        fd.append('id', this.user.id);
        axios.post('/api/upload-avatar',fd).then(response => {
          toast.fire({
              type: 'success',
              title: 'Imagen actualizada con éxito'
          });
          this.$emit('close-modal-avatar',response.data);
          this.loading =false;

        }).catch(e => {
          this.loading =false;
          toast.fire({
              type: 'error',
              title: 'Error al agregar imagen'
          });
        });
      });
    },

    download(type, compressionRate) {
      this.myCroppa.generateBlob((blob) => {
        var url = URL.createObjectURL(blob)
        var a = document.createElement('a');
        a.download = 'filename';
        a.href = url;
        a.click();
        URL.revokeObjectURL(url);
      }, type, compressionRate)
    },




  },
  created(){

  }
}
</script>
