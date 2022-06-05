<template>
    <div class="home container py-2">
        <Loading v-if="loading" />
        <div class="row justify-content-md-center" v-if="!loading && $gate.isActive()">
            <!-- <div class="col-md-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item active" aria-current="page">Inicio</li>
                    </ol>
                </nav>
            </div> -->
            <div class="col-md-1">

            </div>
            <div class="col-md-11 mb-4">
              <br>
                <!-- <h1 class="h2 text-center font-weight-bold">Bienvendo {{ $gate.activeUser().name }}</h1> -->
                <h1 class="h2 text-left font-weight-bold">Bienvenid{{ $gate.activeUser().pronombre === 'él' ? 'o' : $gate.activeUser().pronombre === 'ella' ? 'a' : $gate.activeUser().pronombre === 'elle' ? 'e' :''}} a la Plataforma de <br> Gestión del Conocimiento (PGC)</h1>
                <!-- <p class="text-center">Fundación IDEA y C230 Consultores cuentan con:</p> -->
            </div>
            <div class="col-md-1">

            </div>
            <div class="col-md-11">
              <h5 class="text-left text-orange font-weight-bold">Fundación IDEA y C230 Consultores cuentan con:</h5>
            </div>
            <div class="col-md-1">

            </div>
            <div class="col-md-11 mb-1">
              <div class="row">
                <div class="col-md-3">
                    <div class="card mb-3" @click="goToProjects">
                        <div class="card-body text-left">
                          <img src="/img/icono-proyectos.png" style="width : 1.5rem;">
                            <h2 class="h2 font-weight-bold text-new">
                                {{ totalProjects }}
                            </h2>
                            <p class="mb-0">
                                Proyecto(s)
                                <br><br><br>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card mb-3" @click="goToStaff">
                        <div class="card-body text-left">
                            <img src="/img/icono- equipo de trabajo.png" style="width : 1.5rem;">
                            <h2 class="h2 font-weight-bold text-new">
                                {{ totalUsers }}
                            </h2>
                            <p class="mb-0">
                              Colaboradores y <br>
                              colaboradoras <br>
                              (Equipo de trabajo)
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-md-3" v-if="$gate.activeUser().role_id == 1 || $gate.activeUser().role_id == 2 || $gate.activeUser().role_id == 3">
                    <div class="card mb-3" @click="goToVacancies">
                        <div class="card-body text-left">
                            <img src="/img/icono- vacantes.png" style="width : 1.5rem;">
                            <h2 class="h2 font-weight-bold text-new">
                                {{ totalVacancies }}
                            </h2>
                            <p class="mb-0">
                                Vacante(s)
                                <br><br><br>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card mb-3" @click="goToResources">
                        <div class="card-body text-left">
                            <img src="/img/icono- recursos.png" style="width : 1.5rem;">
                            <h2 class="h2 font-weight-bold text-new">
                                {{ totalResources }}
                            </h2>
                            <p class="mb-0">
                                Recurso(s)
                                <br><br><br>
                            </p>
                        </div>
                    </div>
                </div>
              </div>
            </div>


            <div class="col-md-1">

            </div>
            <div class="col-md-11 mb-4" >
              <button class="btn btn-outline-orange-two font-weight-bold" @click="goToManage">
                <img src="/img/icono- administracion.png" style="width : 1rem;"> &nbsp;
                Administración de la PGC &nbsp; &nbsp; &nbsp; &nbsp;</button>



                <!-- <div class="card card-block d-flex bg-light pointer mb-3" id="admin-card" @click="goToManage">
                    <div class="card-body text-center align-items-center d-flex justify-content-center">
                        <p class="mb-0 lead">
                            Administración de la plataforma
                        </p>
                    </div>
                </div> -->
            </div>

            <div class="col-md-1 mb-2">

            </div>
            <div class="col-md-11 mb-2">
              <h5 class="text-left text-orange font-weight-bold">Otras plataformas de Fundación IDEA y C230 Consultores:</h5>

              <a href="https://timeworks.c-230.com" target="_blank" class="btn btn-outline-gray-two font-weight-bold">
                &nbsp;&nbsp; &nbsp; &nbsp; &nbsp;<img src="/img/TimeWorks.png" style="width : 6rem;"> &nbsp;&nbsp; &nbsp; &nbsp; &nbsp;</a >
                &nbsp;&nbsp;<br><br>
              <a href="https://radarc19.c-230.com" target="_blank" class="btn btn-outline-gray-two font-weight-bold">
                &nbsp;&nbsp; &nbsp; &nbsp; &nbsp;<img src="/img/Radar- Logo.png" style="width : 6.5rem;"> &nbsp;&nbsp; &nbsp; &nbsp; &nbsp;</a >

            </div>
        </div>
        <br>
        <NoActive v-if="!loading && !$gate.isActive()" />
    </div>
</template>

<script>
    import NoActive from '../complements/NoActive'
    import Loading from '../complements/Loading'
    export default {
        components: {
            Loading, NoActive
        },
        data: () => ({
            loading: false,
            users: [],
            projects: [],
            vacancies: [],
            resources: [],
            totalUsers : '',
            totalProjects : '',
            totalVacancies : '',
            totalResources : '',
        }),
        methods:{
            goToManage(){
                this.$router.push(`/manage/home`)
            },
            goToVacancies(){
                this.$router.push(`/vacancies`)
            },
            goToResources(){
                this.$router.push(`/resources`)
            },
            goToStaff(){
                this.$router.push(`/staff`)
            },
            goToProjects(){
                this.$router.push(`/projects`)
            },

            getProjects(){
                this.loading = true;
                axios.get('/api/get-totaly-all-data').then(response =>{
                  this.totalUsers = response.data.totalUsers;
                  this.totalProjects = response.data.totalProjects;
                  this.totalResources = response.data.totalResources;
                  this.totalVacancies = response.data.totalVacancies;
                  this.loading = false;
                }).catch(e => {
                  this.loading = false;
                  console.error(e);
                })
            }
        },
        created(){
            if(this.$gate.isActive()) this.getProjects();
        }
    }
</script>

<style lang="scss" scoped>
    .card {
        border: 1px solid #999999;
        // // border:none;
        // border-radius: 5px;
        // border-bottom-left-radius:
        background:#F7F8FA;

    }

    .text-new {
      color: #384954;
    }

    .card-body {
        // min-height: 10rem;
        // padding: 2.2rem;
        // background:#F7F8FA;
    }

    .btn-outline-orange-two {
      border: 1px solid #F68E1D;
      color: #384954;
      border-radius: 20px;
    }

    .btn-outline-gray-two {
      // width: 100px;
      height: 4rem;
      // border: 1px solid #384954;
      color: #384954;
      // border-radius: 5px;
    }
    //
    // .card-shadow {
    //     -webkit-box-shadow: 2px 2px 4px 0px rgba(0,0,0,0.2);
    //     -moz-box-shadow: 2px 2px 4px 0px rgba(0,0,0,0.2);
    //     box-shadow: 2px 2px 4px 0px rgba(0,0,0,0.2);
    // }
</style>
