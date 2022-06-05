<template>
  <div>
    <div class="grid-gral">
      <div class="uno-gdr">
        <span class="color-title" ><img src="/img/icono- vacantes.png" style="width : 2rem;">&nbsp;Reclutamiento</span>
      </div>
      <div class="dos-gdr">
        <div class="content-uno-gdr" :style="width_responsive">
          <div class="grid-buttons">
            <div class="inner-addon left-addon">
              <i class="glyphicon fas fa-search"></i>
              <input type="text" class="form-control" placeholder="Buscar vacante"  style="border-radius : 15px;" v-model="vacancie_text" @input="findVacancies()"/>
              <h5 class="mt-3 font-weight-bold" style="color : #F68E1D">{{vacancies.length}} vacantes encontradas</h5>
            </div>
            <div class="btns">
              <template v-if="$screen.width <= 768">
                <button class="btn btn-outline-orange float-right" @click="addClass()">
                  <i class="fas fa-filter"></i>
                </button>
              </template>
              <button class="btn btn-outline-orange float-right font-weight-bold" @click="addEditVacancie(0)"><i class="fas fa-plus mr-1"></i> Agregar vacante</button>
            </div>
          </div>
          <template v-for="v in vacancies">
            <div class="card card-vacancies">
              <div class="card-body">
                <h5 class="text-titile-card">{{v.name}}</h5>
                <h6 class="text-titile-card">{{v.company == 1 ? 'Fundación IDEA' : v.company == 2 ? 'C230 Consultores' : ''}}</h6>
                <p class="text-color-letter-content"><img src="img/Vigencia.png" height="16">
                  <b>Vigencia:
                    <template v-if="v.validity === null">
                      Convocatoria abierta
                    </template>
                    <template v-else>
                      hasta el {{v.validity}}
                    </template>
                  </b>
                </p>
                <p class="text-color-letter-content"><img src="img/Publicacion.png" height="16"> <b>Publicación:</b>

                  <label class="switch">
                    <input type="checkbox" v-model="v.active" @change="checkStatus(v.id)">
                    <span class="slider round"></span>
                  </label>
                </p>
                <a @click="addEditVacancie(v.id)" class="btn btn-outline-orange">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b> Leer más</b>&nbsp;&nbsp;
                  <img src="/img/Leer-mas.png" style="width : 0.5rem;"></a>
                </div>
              </div>
              <br>
            </template>
        </div>
        <div class="content-dos-gdr" :style="display_resposive">
          <h4 style="color : #156CAB;"><b>Filtrar por:</b></h4>
          <div class="accordion-menu">
            <ul class="test">

              <li class="test-l">
                <input type="checkbox" class="test-c" checked>
                <i class="arrow"></i>
                <h2 class="title-accordion">Estatus</h2>
                <p class="testp">
                  <ul style="padding-left: 1.4em !important;">
                    <li>
                      <input class="form-check-input me-1" type="checkbox" @change="setDataFilters(1, 'estatus')">
                      Activo
                    </li>
                    <li>
                      <input class="form-check-input me-1" type="checkbox" @change="setDataFilters(0, 'estatus')">
                      Inactivo
                    </li>
                  </ul>
                </p>
              </li>

              <li class="test-l">
                <input type="checkbox" class="test-c" checked>
                <i class="arrow"></i>
                <h2 class="title-accordion">Tipo de vacante</h2>
                <p class="testp">
                  <ul style="padding-left: 1.4em !important;">
                    <li>
                      <input class="form-check-input me-1" type="checkbox" @change="setDataFilters(1, 'tipo')">
                      Coordinadores (posición abierta)
                    </li>
                    <li>
                      <input class="form-check-input me-1" type="checkbox" @change="setDataFilters(2, 'tipo')">
                      Coordinadores (reclutamiento continuo)
                    </li>
                    <li>
                      <input class="form-check-input me-1" type="checkbox" @change="setDataFilters(3, 'tipo')">
                      Analistas (posición abierta)
                    </li>
                    <li>
                      <input class="form-check-input me-1" type="checkbox" @change="setDataFilters(4, 'tipo')">
                      Analistas (reclutamiento continuo)
                    </li>
                  </ul>
                </p>
              </li>

            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
  </template>

  <script>
  export default {
    components: {

    },
    data: () => ({
      display_resposive : '',
      width_responsive : '',
      sizeTable : '550px',
      display_btn : 'display: none; max-width: 250px;',
      vacancies: [],
      vacancie_text : '',

      filtersEstatus : [],
      filtersTipo : [],

      filtersData : [],

    }),
    watch: {
          '$screen.width'() {
            if (this.$screen.width <= 768) {
              this.width_responsive = 'width : ' + (this.$screen.width - 50) +'px;';
              this.display_resposive = "display: none; max-width: 250px;";
            }else {
              this.width_responsive = '';
              this.display_resposive = "";
            }
          }
      },
    methods:{
      addClass() {
        if (this.display_resposive === 'display: none; max-width: 250px;') {
          this.display_resposive = "display: block; max-width: 250px; position:absolute; z-index : 2;top: 300px";
        }else {
          this.display_resposive = "display: none; max-width: 250px;";
        }
      },

      getVacancies(){
        if (this.$screen.width <= 768) {
          this.width_responsive = 'width : ' + (this.$screen.width - 50) +'px;';
          this.display_resposive = "display: none; max-width: 250px;";
        }else {
          this.width_responsive = '';
          this.display_resposive = '';
        }

        axios.get('/api/vacancy')
        .then(
          ({data}) => {
            this.vacancies = data
          }
        )
      },

      checkStatus(id){
        let status = (event.target.checked == false ? 0 : 1);

        axios.get('/api/change-status-vacancies/' + id + '&' + status).then(response => {
          swal.fire({
            title:'Correcto',
            text:'La vacante ha sido guardada.',
            type:'success'
          });
        }).catch(e => {
          console.error(e);
        });
      },

      findVacancies(){
        if (this.vacancie_text.length > 1) {
          // this.projects = [];
          axios.post('/api/find-vacancies',{
            data : this.vacancie_text
          }).then(response => {
            this.vacancies = response.data;
          }).catch(e => {
            console.error(e);
          });
        }else if (this.vacancie_text.length == 0) {
          this.getVacancies();
        }
      },

      setDataFilters(data, type){
        if (event.target.checked == true) {
          if (type === 'estatus') {
            this.filtersEstatus.push(data);
          }else if (type === 'tipo') {
            this.filtersTipo.push(data);
          }
        }else if (event.target.checked == false) {
          this.removeItemFromArrFilters(data, type);
        }
        this.constArray();

      },

      constArray(){
        this.filtersData =[{
          'estatus' : this.filtersEstatus,
          'tipo' : this.filtersTipo,
        }];

        // this.getWithFilters();
        axios.post('/api/get-with-filters-vacancies',{
          data : this.filtersData,
          // text : this.user_text,
        }).then(response => {
          console.log(response);
          this.vacancies = response.data;
        }).catch(e => {
          console.error(e);
        });
      },

      removeItemFromArrFilters (item, type ) {
        if (type === 'estatus') {
          var i = this.filtersEstatus.indexOf( item );
          if ( i !== -1 ) {
            this.filtersEstatus.splice( i, 1 );
          }
        }else if (type === 'tipo') {
          var i = this.filtersTipo.indexOf( item );
          if ( i !== -1 ) {
            this.filtersTipo.splice( i, 1 );
          }
        }
      },

      addEditVacancie(data){
        this.$router.push({ name: 'vacancies', params: { id: data, s_active: 1 } })
      }
    },
    created(){
      this.getVacancies();
    }
  }
  </script>

  <style lang="scss" scoped>
  .separator {
    color: #495057; border-bottom:0.20rem solid #dee2e6;
  }

  .card{
    position: relative;
    display: flex;
    flex-direction: column;
    min-width: 0;
    word-wrap: break-word;
    background-color: #ffff;
    background-clip: border-box;
    border: 0;
    border-radius: 0;
  }

  .card-img {
    width: 100%;
    border-radius: 0;
  }

  .title-vacancies{
    background-color: rgba(255,121,0,60%);
  }

  .vacancy-description {
    height: 8em;
    overflow-y: auto;
  }

  .card-vacancies{
    border-top-width: 3px;
    border-top-style: solid;
    border-top-color: #f68e1d;
    // border-bottom: none;
    // border-left: none;
    // border-right: none;
    box-shadow: 2px 2px 2px gray;
    /* width: 100%; */
    /* min-height: 100%; */
    top: 0;
    left: 0;
  }

  .text-titile-card{
    color: #13314c !important;
  }

  .btn-outline-orange {
    color: #f68e1d;
    border-color: #f68e1d;
    background-color: transparent;
    &:hover {
      color: #f68e1d;
      border-color: #f68e1d;
    }
    border-radius: 50px;
    width: auto;
    height: 32px;
    text-align: center;
  }

  .text-color-letter-content {
    color: #7a7a7a !important;
  }

  .switch {
    position: relative;
    display: inline-block;
    width: 30px;
    height: 14px;
    top: 6px;
  }

  .switch input {
    opacity: 0;
    width: 0;
    height: 0;
  }

  .slider {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: #ccc;
    -webkit-transition: .4s;
    transition: .4s;
  }

  .slider:before {
    position: absolute;
    content: "";
    height: 12px;
    width: 12px;
    left: 1px;
    bottom: 1px;
    background-color: white;
    -webkit-transition: .4s;
    transition: .4s;
  }

  input:checked + .slider {
    background-color: #28a745;
  }

  input:focus + .slider {
    box-shadow: 0 0 2px #28a745;
  }

  input:checked + .slider:before {
    -webkit-transform: translateX(14px);
    -ms-transform: translateX(14px);
    transform: translateX(14px);
  }

  /* Rounded sliders */
  .slider.round {
    border-radius: 34px;
  }

  .slider.round:before {
    border-radius: 50%;
  }
  </style>
