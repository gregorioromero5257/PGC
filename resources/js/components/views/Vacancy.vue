<template>
  <div>

    <div class="grid-gralv">
      <div class="uno-gdr">
        <span class="color-title" ><img src="/img/icono- vacantes.png" style="width : 2rem;">&nbsp;Reclutamiento</span>

      </div>
      <div class="dos-gdr-v" :class="backgroundce">

        <div class="row">
          <div class="col-md-12" v-if="backgroundce != 'row-disable'">
            <p class="float-center form-control-eigth"> ESTÁS {{crud === 'edit' ? ' EDITANDO ESTA VACANTE' : ' AGREGANDO UNA VACANTE'}}</p>
          </div>
          <div class="col-md-1" >
          </div>
          <div class="col-md-6" style="border-bottom: 1px solid;">
            <br>
            <template v-if="backgroundce === 'row-disable'">
              <h3><b>{{vacancies.name}}</b></h3>
            </template>
            <template v-else>
              <input type="text" class="form-control" v-model="vacancies.name">
            </template>
            <h6>Introducción</h6>
            <div v-show="computeds == true">
              {{introduction}}
            </div>
            <template v-if="backgroundce === 'row-disable'">
              <p>
                {{vacancies.short_description}}
              </p>
            </template>
            <template v-else>
              <textarea v-model="vacancies.short_description" rows="5" cols="80" class="form-control"></textarea>
            </template>

          </div>
          <div class="col-md-4" style="border-bottom: 1px solid;">
            <label>Empresa que busca el puesto</label>
            <select class="form-control" v-model="vacancies.company" :disabled="backgroundce === 'row-disable'">
              <option value="1">Fundación IDEA México</option>
              <option value="2">C230 Consultores México</option>
            </select>
            <label>Tipo de vacante</label>
            <select class="form-control" v-model="vacancies.type" @change="validation()" :disabled="backgroundce === 'row-disable'">
              <option value="1">Coordinadores (posición abierta)</option>
              <option value="2">Coordinadores (reclutamiento continuo)</option>
              <option value="3">Analistas (posición abierta)</option>
              <option value="4">Analistas (reclutamiento continuo)</option>
              <option value="5">Otro</option>
            </select>
            <label>Vigencia</label>
            <input type="date" v-model="vacancies.validity" class="form-control" :disabled="backgroundce === 'row-disable' || disable_button">
            <br>
          </div>
          <div class="col-md-1">

          </div>
        </div>
        <div class="row ">
          <div class="col-md-1">
          </div>
          <div class="col-md-3">
            <br>
            <a  @click="activeSelect()" v-if="($gate.ifHavePermisos([1, 2]) || $gate.ifPermisionWeb() > 7) && crud === 'edit'" class="btn btn-outline-blue mb-4">Editar vacante </a>
          </div>
        </div>
        <div class="row " style="background-color : #E2E8F0">

          <div class="col-md-11">
            <label><b>Requisitos</b></label>
            <vue-editor v-model="vacancies.requirements" :editor-toolbar="customToolbar" :class="backgroundtext" :disabled="backgroundce === 'row-disable'"></vue-editor>
          </div>
          <div class="col-md-1">
          </div>
          <div class="col-md-11">
            <label><b>Experiencia y conocimientos deseables</b></label>
            <vue-editor v-model="vacancies.experience" :editor-toolbar="customToolbar" :class="backgroundtext" :disabled="backgroundce === 'row-disable'"></vue-editor>
          </div>
          <div class="col-md-1">
          </div>
          <div class="col-md-11">
            <label><b>¿Qué ofrecemos?</b></label>
            <vue-editor v-model="vacancies.offer" :editor-toolbar="customToolbar" :class="backgroundtext" :disabled="backgroundce === 'row-disable'"></vue-editor>
          </div>
          <div class="col-md-12">
            <br>
          </div>
          <template v-if="backgroundce != 'row-disable'">
          <div class="col-md-12 mb-3 float-center" >
            <button class="btn btn-outline-orange-one" @click="SavePublicVacancie()">
              <i class="fas fa-plus mr-1"></i>
              Guardar y publicar
            </button>
            <button type="button" class="btn btn-outline-gray-one" @click="cancelar()">Cancelar</button>
          </div>
        </template>
        </div>

      </div>
    </div>

  </div>





</template>

<script>
import { VueEditor } from "vue2-editor";

    export default {
      components :{ VueEditor},
      computed : {
        introduction(){
          if ((this.vacancies.short_description).length > 450) {
          return  swal.fire({
                title:'Información',
                text:'La Introducción no puede exeder 450 caracteres',
                type:'warning'
            });
          }
        }
      },
        data: () => ({
            disable_button : false,
            computeds : false,
            loading: false,
            vacancy: {},
            backgroundce : 'row-disable',
            crud : '',
            backgroundtext : '',
            vacancies : {
              name : '',
              short_description : '',
              company : '',
              type : '',
              validity : '',
              requirements : '',
              experience : '',
              offer : '',
            },
            customToolbar: [
                              ["bold", "italic", "underline","link"],
                              [{ list: "ordered" }, { list: "bullet" }],
                          ]
        }),
        methods: {
            getVacancy() {
              // this.loading = true;
              if (this.$route.params.id != 0) {
                this.crud = 'edit';
                // this.backgroundce : 'row-edit';
                axios.get(`/api/vacancy-by-slug/${this.$route.params.id}`)
                .then(response => {
                  this.vacancies = response.data;
                });
              }else {
                this.backgroundtext = 'background-text';
                this.crud = 'create';
                this.backgroundce = 'row-edit';
              }
            },

            cancelar(){
              // console.log();
              if (this.$route.params.id == 0) {
                this.$router.push('/vacancies');
                this.crud = '';
              }else {
                this.backgroundce = 'row-disable';
                this.crud = 'edit';
                this.backgroundtext = '';
              }
            },

            activeSelect(){
              this.backgroundtext = 'background-text';
              this.crud = 'edit';
              this.backgroundce = 'row-edit';
            },

            mensaje(data){
              swal.fire({
                    title:'Información',
                    text: data,
                    type:'warning'
                });
            },

            SavePublicVacancie(){
              if (this.vacancies.name === '' || this.vacancies.name === null) {
                this.mensaje('Escriba el nombre de la vacante');
              }else if (this.vacancies.short_description === '' || this.vacancies.short_description === null ) {
                this.mensaje('Escriba una introducción de la vacante');
              }else if (this.vacancies.company === '' || this.vacancies.company === null) {
                this.mensaje('Seleccione la empresa dirigida de la vacante');
              }else if (this.vacancies.type === '' || this.vacancies.type === null) {
                this.mensaje('Seleccione el tipo de vacante');
              }else if ((this.vacancies.type == 1 || this.vacancies.type == 3 || this.vacancies.type == 5) && (this.vacancies.validity === '' || this.vacancies.validity === null)) {
                this.mensaje('Defina la vigencia de la vacante');
              }else if (this.vacancies.requirements === '' || this.vacancies.requirements === null) {
                this.mensaje('Escriba los requisitos de la vacante');
              }else if (this.vacancies.experience === '' || this.vacancies.experience === null) {
                this.mensaje('Escriba la experiencia para la vacante');
              }else if (this.vacancies.offer === '' || this.vacancies.offer === null) {
                this.mensaje('Escriba la oferta de la vacante');
              } else {
                axios.post('/api/savepublicvacancy',{
                  id : this.$route.params.id,
                  name : this.vacancies.name,
                  short_description : this.vacancies.short_description,
                  company : this.vacancies.company,
                  type : this.vacancies.type,
                  validity : this.vacancies.validity,
                  requirements : this.vacancies.requirements,
                  experience :this.vacancies.experience,
                  offer : this.vacancies.offer,
                  method : this.crud,
                }).then(response => {
                  this.cancelar();
                  swal.fire({
                      title:'Correcto',
                      text:'La vacante ha sido guardada y publicada.',
                      type:'success'
                  });
                }).catch(e => {
                  console.error(e);
                });
              }
            },

            validation(){
              if(this.vacancies.type == 2 || this.vacancies.type == 4){
                this.disable_button = true;
                this.vacancies.validity = '';
              }else {
                this.disable_button = false;
              }
            },

            deleteVacancy(vacancy){
                swal.fire({
                    title: '¿Estás seguro?',
                    text: "¡No podrás revertir esto!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sí, eliminar vacante',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.value) {
                        axios.delete(`/api/vacancy/${vacancy.id}`).then(()=>{
                            swal.fire({
                                title:'Eliminado',
                                text:'La vacante ha sido eliminada.',
                                type:'success'
                            }).then((result) => {
                                if(result.value){
                                    //console.log("yes sir!");
                                    this.$router.push(`/vacancies`)
                                }
                            })
                        }).catch(()=> {
                            swal("Error", "Algo salió mal", "warning");
                        })

                    }
                })
            }
        },
        created() {
            this.getVacancy();
        }
    }
</script>
<style>
  .row-disable {
      border: 1px solid gray;
      border-radius: 5px;
  }

  .row-edit{
    background-color : #E2E8F0;
    border: 1px solid gray;
    border-radius: 5px;
  }

  .background-text {
    background-color: #ffffff;
  }
</style>

<style lang="scss" scoped>
    .font-answer {
        border: 1px solid #ced4da;
        font-size: 1.125rem;
        color: #495057;
        padding: 0.5rem 1rem;
        max-height: 20em;
        overflow-y: auto;
    }
    .row {
  display: flex;
  flex-wrap: wrap;
  margin-right: 0px;
  margin-left: 0px;
}
</style>
