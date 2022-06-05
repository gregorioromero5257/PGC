<template>
  <!-- d-md-block -->
    <div class="sidenav m-see" :style="m_see">
        <nav class="nav flex-column navbar-dark pl-3 pr-3" style="padding-top: 9vw;">
            <h5 class="mb-0 mt-2" style="justify-content : space-between;display: flex;">
                <router-link to="/manage/home" class="sidenav-link text-white pb-1 ml-3 mr-3">Administración</router-link>
                <span class="justify-content-right text-light" @click="closeNav()">
                  X
                </span>
            </h5>
            <p>&nbsp;</p>
            <li class="nav-item">
                <h6 class="mb-0 mt-2 pb-1">
                    <a href="#collapseProjects" class="sidenav-link text-white pb-1 ml-3 mr-3" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="collapseProjects">
                        Categorías en proyectos
                    </a>
                </h6>
                <ul class="list-unstyled collapse" :class="{ 'show' : section == 2 }" id="collapseProjects">
                    <li v-if="$gate.activeUser().role_id == 1 || $gate.activeUser().role_id == 2 || $gate.activeUser().role_id == 3">
                        <router-link to="/manage/projects/privacy_options" class="text-white sidenav-link">
                            Opciones de privacidad
                        </router-link>
                    </li>
                    <li>
                        <router-link to="/manage/projects/locations" class="text-white sidenav-link">Ubicaciones geográficas</router-link>
                    </li>
                    <li v-if="$gate.activeUser().role_id == 1 || $gate.activeUser().role_id == 2">
                        <router-link to="/manage/projects/companies" class="text-white sidenav-link">
                            Empresas y oficinas
                        </router-link>
                    </li>
                    <li v-if="$gate.activeUser().role_id == 1 || $gate.activeUser().role_id == 2 || $gate.activeUser().role_id == 3">
                        <router-link to="/manage/projects/contracts" class="text-white sidenav-link">
                            Tipos de contrato
                        </router-link>
                    </li>
                    <li v-if="$gate.activeUser().role_id == 1 || $gate.activeUser().role_id == 2 || $gate.activeUser().role_id == 3">
                        <router-link to="/manage/projects/satisfaction_documents" class="text-white sidenav-link">
                            Tipos de documentos de satisfacción
                        </router-link>
                    </li>
                    <li v-if="$gate.activeUser().role_id == 1 || $gate.activeUser().role_id == 2 || $gate.activeUser().role_id == 3">
                        <router-link to="/manage/projects/currencies" class="text-white sidenav-link">
                            Monedas
                        </router-link>
                    </li>
                    <li v-if="$gate.activeUser().role_id == 1 || $gate.activeUser().role_id == 2 || $gate.activeUser().role_id == 3">
                        <router-link to="/manage/projects/types" class="text-white sidenav-link">
                            Tipos de proyecto
                        </router-link>
                    </li>
                    <li>
                        <router-link to="/manage/projects/methodologies" class="text-white sidenav-link">Análisis, metodologías e implementación</router-link>
                    </li>
                    <li>
                        <router-link to="/manage/projects/topics" class="text-white sidenav-link">Temáticas / Enfoque / Población objetivo</router-link>
                    </li>
                </ul>
            </li>
            <li class="nav-item">
                <h6 class="mb-0 pt-1 pb-1">
                    <router-link to="/manage/stackeholders" class="sidenav-link text-white pb-1 ml-3 mr-3">
                        Partes interesadas
                    </router-link>
                </h6>
            </li>
            <li class="nav-item" v-if="$gate.activeUser().role_id == 1 || $gate.activeUser().role_id == 2 || $gate.activeUser().role_id == 3">
                <h6 class="mb-0 pt-1 pb-1">
                    <a href="#collapseStaff" class="sidenav-link text-white pb-1 ml-3 mr-3" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="collapseStaff">
                      Equipo de trabajo
                    </a>
                </h6>
                <ul class="list-unstyled collapse" :class="{ 'show' : section == 4 }" id="collapseStaff">
                    <li v-if="$gate.activeUser().role_id == 1 || $gate.activeUser().role_id == 2">
                        <router-link to="/manage/staff/roles" class="text-white sidenav-link">Tipos de perfil</router-link>
                    </li>
                    <li>
                        <router-link to="/manage/staff/positions" class="text-white sidenav-link">Cargos</router-link>
                    </li>
                </ul>
            </li>
            <li class="nav-item">
                <h6 class="mb-0 pt-1 pb-1">
                    <router-link to="/manage/codigos" class="sidenav-link text-white pb-1 ml-3 mr-3">
                      Códigos de proyectos
                    </router-link>
                </h6>
            </li>
            <li class="nav-item" v-show="$gate.ifPermisionWeb() > 7">
                <h6 class="mb-0 pt-1 pb-1">
                    <router-link to="/manage/idea" class="sidenav-link text-white pb-1 ml-3 mr-3">
                      Publicacion web (IDEA)
                    </router-link>
                </h6>
            </li>
        </nav>
    </div>
</template>

<script>
    export default {
      props: {
        m_see: String,
      },
        data: () => ({
            section: 1,
        }),
        methods: {
            openProjects(){
                $(`#collapseProjects`).collapse('show')
            },

            closeNav(){
              this.$emit('close-nav');
            }
        },
        computed: {

        },
        created(){
            if (this.$route.path.includes("projects")){
                this.section = 2
            } else if (this.$route.path.includes("stackeholders")){
                this.section = 3
            } else if (this.$route.path.includes("staff")) {
                this.section = 4
            } else {
                this.section = 1
            }
        }
    }
</script>

<style lang="scss" scoped>
    .list-unstyled{
        margin-bottom: 0px;
        li {
            padding: 0.2rem 1rem 0.2rem 1.5rem;
        }
        a {
            text-decoration: none;
            background-color: transparent;
        }

    }
    .sidenav {
        position: absolute;
        height: 96%;
        width: 17vw;
        // z-index: -1;
        // overflow-x: hidden;
        overflow-y: auto;
        transition: 0.5s;
        top: 11px;
        left: 26px;
        background: #384954;
    }

    @media screen and (max-width: 768px) {
      .sidenav {
        left: 15px !important;
        width: 40vw !important;
        top: 50px !important;
        z-index: 2;
      }
      // .m-see {
      //
      // }
      // .d-md-block {
      //   display: block !important;
      // }
    }
    @media screen and (max-width: 480px) {
      .sidenav {
        left: 10px !important;
        width: 50vw !important;
        top: 50px !important;
      }
      // .m-see {
      //
      // }
      // .d-md-block {
      //   display: block !important;
      // }
    }
    .logo {
        font-size: 1.125rem;
        padding-top: 1em;
        margin-bottom: 1.25em;
    }
    .sidenav-link{
        text-decoration: none;
        &:hover{
            border-bottom-color: #EFB04E;
            border-bottom-style: solid;
            border-bottom-width: 3px;
        }
    }
</style>
