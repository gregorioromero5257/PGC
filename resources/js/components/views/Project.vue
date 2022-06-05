<template>
  <div class="project">
    <!-- <Loading v-if="loading" /> -->
    <vue-element-loading :active="loading" spinner="spinner" color="#FFA000" text="Cargando..." :is-full-screen="true"/>
    <div class="container">
      <div class="col-md-12">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><router-link class="text-orange" to="/home">Inicio</router-link></li>
            <li class="breadcrumb-item"><router-link class="text-orange" to="/projects">Proyectos</router-link></li>
            <li class="breadcrumb-item active" aria-current="page">{{ project.code }}</li>
          </ol>
        </nav>
      </div>
      <div :class="container_class" :style="style_responsive" >

        <div class="container" v-if="!loading && $gate.isActive()" :style="style_responsive">

          <div class="col-md-12" v-if="edit && !nuevo">
            <p class="float-center form-control-eigth"> ESTÁS EDITANDO ESTE PROYECTO</p>
          </div>
          <div class="col-md-12" v-if="nuevo">
            <p class="float-center form-control-eigth"> AGREGANDO PROYECTO</p>
          </div>

          <!-- Inicio titulo datos generales -->
          <div class="form-row">

            <div class="col-md-6 mb-1">
              <template v-if="edit == false && nuevo == false">
                <template v-if="project.name_es">
                  <p class="form-control-three mb-0">{{ project.name_es }} </p>
                </template>
                <template v-else>
                  <p class="form-control-three mb-0">N/A</p>
                </template>
              </template>
              <template v-else>
                <label>Nombre proyecto (ES)</label>
                <textarea class="form-control" v-model="project.name_es" :disabled="!$gate.ifHavePermisos([1, 2, 8]) && (!edit || !nuevo)"> </textarea>
              </template>

            </div>
            <div class="col-md-6 mb-1" >
              <label>Empresa que realiza el proyecto</label>
              <v-select placeholder="Seleccione una opción" :options="listCompanies" label="name" v-model="project.company" @input="getTematics()" :disabled="!$gate.ifHavePermisos([1, 2, 8])"></v-select>
            </div>
            <div class="col-md-6 mb-1">
              <template v-if="edit == false && nuevo == false">
                <template v-if="project.code">
                  <label>Código</label>
                  <p class="form-control-four mb-0">{{ project.code }} </p>
                </template>
                <template v-else>
                  <p class="form-control-four mb-0">N/A </p>
                </template>
              </template>
              <template v-else>
                <label>Código</label>
                <input type="text" class="form-control" v-model="project.code" :disabled="!$gate.ifHavePermisos([1, 2, 8])">
              </template>
            </div>
            <div class="col-md-6 mb-1">
              <label>Cliente(s)</label>
              <v-select multiple :options="listaStackeholders" label="name" v-model="project.clients" :disabled="!$gate.ifHavePermisos([1, 2, 8])"></v-select>
            </div>
            <div class="col-md-6 mb-2">
              <label>Año(s)</label>
              <input type="text" class="form-control borders-out" :value="years_input" :disabled="!$gate.ifHavePermisos([1, 2, 8])">
            </div>
            <div class="col-md-6 mb-1">
              <label>Proyecto(s) vinculado(s)</label>
              <v-select multiple :options="listLinkedsProjects" label="code" v-model="project.linkeds" :disabled="!$gate.ifHavePermisos([1, 2, 8])"></v-select>
            </div>
              <div class="col-md-6">
              </div>
              <div class="col-md-6" v-show="$gate.ifPermisionWeb() > 7">
                <label>Temática(s)</label>
                  <v-select multiple :options="tematics_web" label="name" v-model="project.tematics_web" ></v-select>
              </div>
          </div>
          <div class="form-row" v-if="!nuevo">
            <div class="col-md-6">
              <p class="mb-0">&nbsp;</p>
              <a   @click="activeSelect()" class="btn btn-outline-blue mb-0"
              v-if="$gate.ifHavePermisos([1, 2, 3, 4, 7])">
              Editar proyecto
            </a>
          </div>
        </div>
        <br>
        <!-- Fin datos generales -->

        <!-- Inicio segunda sección -->
        <div class="col-md-12 mb-4" v-if="(edit) || state_view == 1" :style="style_responsive">
          <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item">
              <a class="nav-link active" id="technique-tab" data-toggle="tab" href="#technique" role="tab" aria-controls="technique" aria-selected="true">Información tecnica</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" v-show="$gate.ifHavePermisos([1, 2, 3])" id="administrative-tab" data-toggle="tab" href="#administrative" role="tab" aria-controls="administrative" aria-selected="false">
                Información administrativa</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" v-show="$gate.ifPermisionWeb() > 7" id="web-tab" data-toggle="tab" href="#web" role="tab" aria-controls="web" aria-selected="false">
                Publicación web</a>
            </li>

          </ul>
          <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active mt-3" id="technique" role="tabpanel" aria-labelledby="technique-tab">

              <div class="col-md-12" :style="style_responsive">
                <div class="card card-shadow-one bg-light-one pointer">
                  <div class="card-body margin-profile" >
                    <p class="form-control-three mt-2 d-inline-block">Datos Generales</p>
                    <div class="form-row">
                      <div class="col-md-6 mb-1">

                        <label>Nombre de Proyecto en Contrato </label>
                        <textarea name="name" rows="4" cols="80" class="form-control" v-model="project.contract_name" :disabled="!$gate.ifHavePermisos([1, 2, 8])"></textarea>
                      </div>
                      <div class="col-md-6 mb-1">
                        <template v-if="edit == false">
                          <label for="privacy">Privacidad</label>
                          <div v-for="privacy_option in project.privacy_options" :key="privacy_option.id">
                            <i class="far fa-lg" :class="'fa-check-square'">
                            </i> {{ privacy_option.name }}
                          </div>
                        </template>
                        <template v-else>
                          <template v-if="$gate.ifHavePermisos([1, 2, 8])">
                            <div class="form-group">
                              <label for="privacy">Privacidad</label>
                              <div class="row mt-2 mb-pj">
                                <div class="col-md-6 mb-2" v-for="privacy_option in privacy_options" :key="privacy_option.id">
                                  <i class="far fa-lg pointer"
                                  @click="selectPrivacyOption(privacy_option)"
                                  :class="{
                                    'fa-check-square' : project.privacy_options.filter(po => po.id === privacy_option.id).length > 0,
                                    'fa-square' : project.privacy_options.filter(po => po.id === privacy_option.id).length <= 0
                                    }"></i> {{ privacy_option.name }}
                                  </div>
                                </div>
                              </div>
                          </template>
                          </template>




                        </div>
                      </div>

                      <div class="form-row">
                        <div class="col-md-6 mb-1">
                          <label>Project Name (EN)</label>
                          <input type="text" class="form-control" v-model="project.name_en" :disabled="!$gate.ifHavePermisos([1, 2, 8])">
                        </div>
                        <div class="col-md-6 mb-1"></div>
                      </div>

                      <div class="form-row">
                        <div class="col-md-6">
                          <label for="locations">Ubicación geográfica</label>
                          <v-select multiple label="name" :options="listLocations" v-model="project.locations" :disabled="!$gate.ifHavePermisos([1, 2, 8])"></v-select>
                        </div>
                        <div class="col-md-6">
                          <label for="locations">Comentarios sobre la privacidad</label>
                          <input type="text" class="form-control" v-model="project.comments" :disabled="!$gate.ifHavePermisos([1, 2, 8])">
                        </div>
                      </div>

                      <div class="form-row">
                        <div class="col-md-6">
                          <label>Rol de contrato en el proyecto</label>
                          <select class="form-control" v-model="project.rol" :disabled="!$gate.ifHavePermisos([1, 2, 8])">
                            <option value="1">Prime</option>
                            <option value="2">Consorcio</option>
                          </select>
                        </div>
                        <div class="col-md-6">
                          <label for="locations">Vigencia de confidencialidad</label>
                          <input type="date" class="form-control" v-model="project.validity_of_confidentiality" :disabled="!$gate.ifHavePermisos([1, 2, 8])">
                        </div>
                      </div>

                      <template v-if="project.rol == 2">
                        <div class="form-row">
                          <div class="col-md-3">
                            <label>Empresa con quien se realizó el consorcio</label>
                            <input type="text" class="form-control" v-model="consortiums.company" :disabled="!$gate.ifHavePermisos([1, 2, 8])">
                          </div>
                          <div class="col-md-3">
                            <label>Empresa representante <br>&nbsp;</label>
                            <input type="text" class="form-control" v-model="consortiums.company_represent" :disabled="!$gate.ifHavePermisos([1, 2, 8])">
                          </div>
                        </div>
                        <div class="form-row">
                          <div class="col-md-6">
                            <label>Acciones particulares</label>
                            <textarea type="text" class="form-control" v-model="consortiums.actions" :disabled="!$gate.ifHavePermisos([1, 2, 8])"></textarea>
                          </div>
                        </div>
                        <div class="form-row">
                          <div class="col-md-6">
                            <label>Cliente final</label>
                            <input type="text" class="form-control" v-model="consortiums.final_client" :disabled="!$gate.ifHavePermisos([1, 2, 8])">
                          </div>
                        </div>
                      </template>


                    </div>
                  </div>
                </div>
                <!--  -->
                <!--  -->
                <div class="col-md-12" v-if="(edit) || state_view == 1" :style="style_responsive">
                  <div class="card card-shadow-one bg-light-one pointer">
                    <div class="card-body margin-profile" >
                      <p class="form-control-three mt-2 d-inline-block">Descripción del proyecto</p>

                      <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item">
                          <a class="nav-link active" id="description_es-tab" data-toggle="tab" href="#description_es" role="tab" aria-controls="description_es" aria-selected="true">Español</a>
                        </li>
                        <li class="nav-item">
                          <a class="nav-link" id="description_en-tab" data-toggle="tab" href="#description_en" role="tab" aria-controls="description_en" aria-selected="false">Inglés</a>
                        </li>
                      </ul>
                      <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="description_es" role="tabpanel" aria-labelledby="description_es-tab">
                          <template v-if="edit == false">
                            <div class="form-group mt-3">
                              <label for="description_es">Descripción detallada</label>
                              <p class="font-answer project-description">{{ project.description_es }}</p>
                            </div>
                          </template>
                          <template v-else>
                            <div class="form-group mt-3">
                              <label for="description_es">Descripción detallada</label>
                              {{!$gate.ifHavePermisos([1, 2, 8])}}
                              <textarea class="form-control" v-model="project.description_es" :disabled="!$gate.ifHavePermisos([1, 2, 8])"></textarea>
                            </div>
                          </template>

                          <template v-if="edit == false">
                            <div class="form-group">
                              <label for="short_description_es">Descripción corta</label>
                              <p class="font-answer project-description">{{ project.short_description_es }}</p>
                            </div>
                          </template>
                          <template v-else>
                            <div class="form-group">
                              <label for="short_description_es">Descripción corta</label>
                              <textarea class="form-control" v-model="project.short_description_es" :disabled="!$gate.ifHavePermisos([1, 2, 8])"></textarea>
                            </div>
                          </template>
                        </div>
                        <div class="tab-pane fade" id="description_en" role="tabpanel" aria-labelledby="description_en-tab">
                          <template v-if="edit == false">
                            <div class="form-group mt-3">
                              <label for="description_es">Detailed description</label>
                              <p class="font-answer project-description">{{ project.description_en }}</p>
                            </div>
                          </template>
                          <template v-else>
                            <div class="form-group mt-3">
                              <label for="description_es">Detailed description</label>
                              <textarea class="form-control" v-model="project.description_en" :disabled="!$gate.ifHavePermisos([1, 2, 8])"></textarea>
                            </div>
                          </template>
                          <template v-if="edit == false">
                            <div class="form-group">
                              <label for="short_description_en">Short description</label>
                              <p class="font-answer project-description">{{ project.short_description_en }}</p>
                            </div>
                          </template>
                          <template v-else>
                            <div class="form-group">
                              <label for="short_description_en">Short description</label>
                              <textarea class="form-control" v-model="project.short_description_en" :disabled="!$gate.ifHavePermisos([1, 2, 8])"></textarea>
                            </div>
                          </template>
                        </div>
                      </div>
                      <div class="form-row">
                        <div class="col-md-6">
                          <p class="h6 font-weight-bold">
                            Publicaciones
                          </p>
                        </div>
                      </div>
                      <div class="form-row">
                        <div class="col-md-6">
                          <label>Nombre de la publicación</label>
                          <input type="text" class="form-control" v-model="publications_edit.name" :disabled="!$gate.ifHavePermisos([1, 2, 8])">
                        </div>
                        <div class="col-md-6">
                          <label>Enlace</label>
                          <input type="text" class="form-control" v-model="publications_edit.link" :disabled="!$gate.ifHavePermisos([1, 2, 8])">
                        </div>
                      </div>
                      <div class="form-row">
                        <div class="col-md-6 mb-2">
                          <label>Nota</label>
                          <input type="text" class="form-control" v-model="publications_edit.description" :disabled="!$gate.ifHavePermisos([1, 2, 8])">
                        </div>
                        <div class="col-md-6">
                          <label>Archivo</label>
                          <template v-if="publications_edit.file === ''">
                            <input @change="onFileChange" type="file" class="form-control-file" id="exampleFormControlFiles">
                          </template>
                          <template v-else>
                            <br>
                            <button type="button" class="btn" title="Descargar archivo" data-toggle="tooltip" data-placement="top" @click.stop="clearFilePublication()">
                              <i class="fas fa-retweet"></i>
                            </button>
                          </template>
                        </div>
                      </div>
                      <div class="form-row mb-3" v-if="$gate.ifHavePermisos([1, 2, 8])">
                        <template v-if="publications_edit.id == 0">
                          <button class="btn btn-outline-blue mb-0" @click="saveEditPublications('save')"> <i class="fas fa-plus"></i>&nbsp;&nbsp;Agregar&nbsp;&nbsp;</button>
                        </template>
                        <template v-if="publications_edit.id != 0">
                          <button class="btn btn-outline-blue mb-0" @click="saveEditPublications('edit')"> <i class="fas fa-plus"></i>&nbsp;&nbsp;Editar&nbsp;&nbsp;</button>
                          &nbsp;
                          <button class="btn btn-outline-cancel mb-0" @click="cancelPublication()"> <i class="fas fa-clear"></i>&nbsp;&nbsp;Cancelar&nbsp;&nbsp;</button>
                        </template>
                      </div>
                      <div class="row mb-3">
                        <div class="col-md-12">

                          <table class="table  responsive">
                            <thead>
                              <tr>
                                <th scope="col" width="5%">#</th>
                                <th scope="col" width="40%">Nombre</th>
                                <th scope="col" width="10%">Enlace</th>
                                <th scope="col" width="10%">Nota</th>
                                <th scope="col" width="35%">Archivo</th>
                              </tr>
                            </thead>
                            <tbody>
                              <tr v-for="t in publications"  >
                                <th scope="row">
                                  <template v-if="$gate.ifHavePermisos([1, 2, 8])">

                                    <button @click.stop="editPublications(t)"  type="button" class="btn" data-toggle="tooltip" data-placement="top" title="Editar">
                                      <i class="fas fa-pencil-alt pointer"></i>
                                    </button>
                                    <!-- v-if="$gate.activeUser().role_id == 1 || $gate.activeUser().role_id == 2"  -->
                                    <button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="Eliminar" @click.stop="deletePublication(t.id)">
                                      <i class="far fa-trash-alt pointer"></i>
                                    </button>
                                  </template>
                                </th>
                                <td>{{t.name}}</td>
                                <td><a :href="t.link">{{t.link}}</a></td>
                                <td>{{t.description}}</td>
                                <td>
                                  <template v-if="t.file">
                                    <button type="button" class="btn" title="Descargar archivo" data-toggle="tooltip" data-placement="top" @click.stop="downloadFilePublication(t)">
                                      <img src="/img/np_download.png" style="width : 1rem;">
                                    </button>
                                  </template>
                                  <template v-else>
                                    <span >No hay archivo</span>
                                  </template>
                                </td>
                              </tr>

                            </tbody>
                          </table>
                        </div>

                      </div>
                      <br>
                      <div class="form-row">
                        <div class="col-md-6 mb-2">
                          <p class="h6 font-weight-bold">
                            Categorías
                          </p>
                          <div class="form-group">
                            <label for="types" class="d-block">Tipo de proyecto</label>
                            <template v-if="edit == false">
                              <span class="badge badge-secondary p-2 mt-2 mr-2" v-for="type in project.types" :key="type.id" v-show="project.types">
                                {{ type.name }}
                              </span>
                            </template>
                            <template v-else>
                              <v-select multiple label="name" :options="listTypes" v-model="project.types" :disabled="!$gate.ifHavePermisos([1, 2, 8])"></v-select>
                            </template>
                          </div>
                          <div class="form-group">
                            <label for="types" class="d-block">Análisis, metodologías e implementación</label>
                            <template v-if="edit == false">
                              <span class="badge badge-secondary p-2 mt-2 mr-2" v-for="methodology in project.methodologies" :key="methodology.id" v-show="project.methodologies">
                                {{ methodology.name }}
                              </span>
                            </template>
                            <template v-else>
                              <v-select multiple label="name" :options="listMetodologies" v-model="project.methodologies" :disabled="!$gate.ifHavePermisos([1, 2, 8])"></v-select>
                            </template>
                          </div>
                          <div class="form-group">
                            <label for="types" class="d-block">Temáticas / Enfoque / Población objetivo</label>
                            <template v-if="edit == false">
                              <span class="badge badge-secondary p-2 mt-2 mr-2" v-for="topic in project.topics" :key="topic.id" v-show="project.topics">
                                {{ topic.name }}
                              </span>
                            </template>
                            <template v-else>
                              <v-select multiple label="name" :options="listTopics" v-model="project.topics" :disabled="!$gate.ifHavePermisos([1, 2, 8])"></v-select>
                            </template>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <p class="h6 font-weight-bold">
                            Estadísticas
                          </p>
                          <div class="col-md-6">
                            <div class="form-group">
                              <label for="interviews">Entrevistas</label>
                              <input type="number" placeholder="0" class="form-control" :disabled="!$gate.ifHavePermisos([1, 2, 8])" v-model="project.interviews">
                            </div>
                            <div class="form-group">
                              <label for="databases">Bases de datos creadas</label>
                              <input type="number" placeholder="0" class="form-control" :disabled="!$gate.ifHavePermisos([1, 2, 8])" v-model="project.databases">
                            </div>
                          </div>
                          <div class="col-md-6">
                            <div class="form-group">
                              <label for="trainings">Capacitaciones</label>
                              <input type="number" placeholder="0" class="form-control" :disabled="!$gate.ifHavePermisos([1, 2, 8])" v-model="project.trainings">
                            </div>
                            <div class="form-group">
                              <label for="study_cases">Casos de estudio</label>
                              <input type="number" placeholder="0" class="form-control" :disabled="!$gate.ifHavePermisos([1, 2, 8])" v-model="project.study_cases">
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <!--  -->
                <br>
                <!--  -->
                <div class="col-md-12 mb-3" v-if="edit || state_view == 1" :style="style_responsive">
                  <div class="card card-shadow-one bg-light-one pointer">
                    <div class="card-body margin-profile" >
                      <p class="form-control-three mt-2 d-inline-block">Equipo de trabajo</p>

                      <div class="row">
                        <vue-element-loading :active="isActiveTeam" spinner="spinner" color="#FFA000" :text="textLoadingTeam"/>



                        <div class="col-md-12">
                          <template v-if="title_button_team === 'Equipo interno'">
                            <button class="btn btn-outline-orange float-right font-weight-bold" @click="newModalMembers" v-if="$gate.ifHavePermisos([1, 2, 8])">
                              <i class="fas fa-plus mr-1"></i>
                              Agregar colaborador interno
                            </button>
                          </template>
                          <template v-if="title_button_team === 'Equipo externo'">
                            <button type="button" class="btn btn-outline-orange float-right font-weight-bold" @click="addMemberExtern" v-if="$gate.ifHavePermisos([1, 2, 8])">
                              <i class="fas fa-plus mr-1"></i> Agregar colaborador externo
                            </button>
                          </template>
                        </div>
                        <div class="col-md-12 mb-4">
                          <ul class="nav nav-tabs" id="myTabinfo" role="tablist">
                            <li class="nav-item">
                              <a class="nav-link active" @click="title_button_team = 'Equipo interno'" id="interno-tab" data-toggle="tab" href="#interno" role="tab" aria-controls="interno" aria-selected="true">Equipo interno</a>
                            </li>
                            <li class="nav-item">
                              <a class="nav-link" @click="title_button_team = 'Equipo externo'" id="externo-tab" data-toggle="tab" href="#externo" role="tab" aria-controls="externo" aria-selected="false">Equipo externo</a>
                            </li>
                          </ul>
                          <div class="tab-content" id="myTabContent">
                            <!--Inicio  -->
                            <div class="tab-pane fade show active mt-3" id="interno" role="tabpanel" aria-labelledby="interno-tab">
                              <div class="row">
                                <div class="col-md-12 mt-2" v-show="MembersProject.length > 0">
                                  <div class="card">

                                    <div class="col-md-12 mt-2 mb-3">

                                      <ul class="list-group list-group-flush" v-for="member in MembersProject" :key="member.id" v-show="project.members">
                                        <li class="list-group-item">
                                          <div class="row">
                                            <div class="col-md-9">
                                              <div class="media pointer" @click="goToUser(member)">
                                                <div v-if="member.avatar" class="avatar-bg mr-2" :style="{ 'background': 'url(' + member.avatar + ')' }"></div>
                                                <div v-else class="avatar-bg mr-2" style="background-image: url('/img/user-circle-solid.svg');"></div>
                                                <div class="media-body">
                                                  <h5 class="mt-2 mb-0">{{ member.name }}&nbsp;
                                                    <template v-if="member.project_manager == 1">
                                                      <span class="badge badge-info">AP</span>
                                                    </template>
                                                  </h5>
                                                </div>
                                              </div>
                                            </div>
                                            <div class="col-md-3">
                                              <i class="fas fa-user-times pointer" @click="deleteMember(member.id)" v-if="$gate.ifHavePermisos([1, 2, 8])"></i>
                                            </div>
                                          </div>

                                        </li>
                                      </ul>
                                    </div>
                                  </div>
                                </div>
                                <div class="col-md-12 mt-2" v-show="MembersProject.length <= 0">
                                  <p class="text-center">
                                    No hay equipo.
                                  </p>
                                </div>
                              </div>
                            </div>
                            <!-- Fin -->
                            <div class="tab-pane fade mt-3" id="externo" role="tabpanel" aria-labelledby="externo-tab">
                              <div class="row">
                                <div class="col-md-12 mb-4" v-show="member_extern.length > 0">
                                  <ul class="list-group list-group-flush">
                                    <li class="list-group-item pt-0 pb-0 pl-3 pr-3" v-for="contact in member_extern" :key="member_extern.id">
                                      <div class="row list-header pt-2 pb-2">
                                        <div class="col-md-10">
                                          <p class="pointer mb-0" data-toggle="collapse" :href="'#contactInfo'+contact.id" role="button" aria-expanded="false" :aria-controls="'contactInfor'+contact.id">
                                            <span class="badge badge-circle badge-secondary">{{ contact.name | myFirstLetter }}</span>
                                            <span class="pt-1">{{ contact.name }}</span>
                                          </p>
                                        </div>
                                        <div class="col-md-2">
                                          <i class="fas fa-user-times pointer" @click="deleteColaboradorExterno(contact.id)" v-if="$gate.ifHavePermisos([1, 2, 8])"></i>
                                          &nbsp;&nbsp;
                                          <i class="fas fa-user-edit pointer" @click="editContactExtern(contact)" v-if="$gate.ifHavePermisos([1, 2, 8])"></i>
                                        </div>
                                      </div>
                                      <div class="form-row mt-3 collapse" :id="'contactInfo'+contact.id">
                                        <div class="form-group col-md-6">
                                          <label for="name">Nombre</label>
                                          <input v-model="contact.name" type="text" class="form-control form-control-lg" id="funder_name" readonly>
                                        </div>
                                        <div class="form-group col-md-6">
                                          <label for="email">Correo electrónico</label>
                                          <input v-model="contact.email" type="email" class="form-control form-control-lg" id="funder_email" readonly>
                                        </div>
                                        <div class="form-group col-md-6">
                                          <label for="name">Puesto</label>
                                          <input v-model="contact.position" type="text" class="form-control form-control-lg" id="funder_position" readonly>
                                        </div>
                                        <div class="form-group col-md-6">
                                          <label for="phone">Teléfono</label>
                                          <input v-model="contact.phone" type="text" class="form-control form-control-lg" id="phone" readonly>
                                        </div>
                                      </div>
                                    </li>
                                  </ul>
                                </div>
                                <div class="col-md-12 mb-4" v-show="member_extern.length <= 0">
                                  <p>No hay colaboradores agregados.</p>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>


                      <!-- ********************************************* -->
                    </div>
                  </div>
                </div>
                <!--  -->

                <!--  -->
                <div class="col-md-12" v-if="edit || state_view == 1" :style="style_responsive">
                  <div class="card card-shadow-one bg-light-one pointer">
                    <div class="card-body margin-profile" >
                      <p class="form-control-three mt-2 d-inline-block">Información de las partes interesadas</p>

                      <div class="row">

                        <div class="col-md-12">
                          <button type="button" class="btn btn-outline-orange float-right font-weight-bold" @click="addStackeholder" v-if="$gate.ifHavePermisos([1, 2, 8])">
                            <i class="fas fa-plus mr-1"></i> Agregar {{title_button}}
                          </button>
                        </div>
                        <div class="col-md-12 mb-4">
                          <ul class="nav nav-tabs" id="myTabinfo" role="tablist">
                            <li class="nav-item">
                              <a class="nav-link active" @click="title_button = 'cliente'" id="customers-tab" data-toggle="tab" href="#customers" role="tab" aria-controls="customers" aria-selected="true">Clientes</a>
                            </li>
                            <li class="nav-item">
                              <a class="nav-link" @click="title_button = 'financiador'" id="funders-tab" data-toggle="tab" href="#funders" role="tab" aria-controls="funders" aria-selected="false">Financiadores</a>
                            </li>
                            <li class="nav-item">
                              <a class="nav-link" @click="title_button = 'proveedor'" id="suppliers-tab" data-toggle="tab" href="#suppliers" role="tab" aria-controls="suppliers" aria-selected="false">Proveedores</a>
                            </li>
                            <li class="nav-item">
                              <a class="nav-link" @click="title_button = 'consorcio'" id="consortion-tab" data-toggle="tab" href="#consortion" role="tab" aria-controls="consortion" aria-selected="false">Consorcio</a>
                            </li>
                          </ul>
                          <div class="tab-content" id="myTabContent">
                            <!--Inicio  -->
                            <div class="tab-pane fade show active mt-3" id="customers" role="tabpanel" aria-labelledby="customers-tab">
                              <div class="row">
                                <div class="col-md-12 mt-2" v-for="client in project_clients" :key="client[0].id" v-show="project_clients.length > 0">
                                  <div class="card">
                                    <div class="card-header bg-white">
                                      <div class="form-row">
                                        <div class="col-md-9">
                                          <p class="font-weight-bold">{{ client[0].name }}
                                            <span class="badge badge-secondary" v-if="client[0].sh_description != null">{{ client[0].sh_description }}</span>
                                            <span class="badge badge-secondary" v-else> Sin registro</span>
                                          </p>
                                        </div>
                                        <div class="col-md-3">
                                          <button type="button" class="btn btn-outline-secondary float-right font-weight-bold" @click="addContact(client[0].stackeholder_id)" v-if="$gate.ifHavePermisos([1, 2, 8])">
                                            <i class="fas fa-user-plus mr-1"></i>
                                          </button>
                                          <button type="button" class="btn btn-outline-secondary float-right font-weight-bold" @click="deleteClient(client[0].id)" v-if="$gate.ifHavePermisos([1, 2, 8])">
                                            <i class="fas fa-times mr-1"></i>
                                          </button>
                                        </div>
                                      </div>


                                      <br>
                                      <hr class="mb-0 mt-3">
                                    </div>
                                    <div class="card-body pt-0">
                                      <div class="row">
                                        <div class="col-md-12 mb-4" v-show="client.contacts.length > 0">
                                          <p class="font-weight-bold mb-3">
                                            Contactos
                                          </p>
                                          <ul class="list-group list-group-flush">
                                            <li class="list-group-item pt-0 pb-0 pl-3 pr-3" v-for="contact in client.contacts" :key="contact.id">
                                              <div class="row list-header pt-2 pb-2">
                                                <div class="col-md-10">
                                                  <p class="pointer mb-0" data-toggle="collapse" :href="'#contactInfo'+contact.id" role="button" aria-expanded="false" :aria-controls="'contactInfor'+contact.id">
                                                    <span class="badge badge-circle badge-secondary">{{ contact.name | myFirstLetter }}</span>
                                                    <span class="pt-1">{{ contact.name }}</span>
                                                  </p>
                                                </div>
                                                <div class="col-md-2">
                                                  <i class="fas fa-user-times pointer" @click="deleteContact(contact.id)" v-if="$gate.ifHavePermisos([1, 2, 8])"></i>
                                                  &nbsp;&nbsp;
                                                  <i class="fas fa-user-edit pointer" @click="editContact(contact)" v-if="$gate.ifHavePermisos([1, 2, 8])"></i>
                                                </div>
                                              </div>
                                              <div class="form-row mt-3 collapse" :id="'contactInfo'+contact.id">
                                                <div class="form-group col-md-6">
                                                  <label for="name">Nombre</label>
                                                  <input v-model="contact.name" type="text" class="form-control form-control-lg" id="name" readonly>
                                                </div>
                                                <div class="form-group col-md-6">
                                                  <label for="email">Correo electrónico</label>
                                                  <input v-model="contact.email" type="email" class="form-control form-control-lg" id="email" readonly>
                                                </div>
                                                <div class="form-group col-md-6">
                                                  <label for="name">Puesto</label>
                                                  <input v-model="contact.position" type="text" class="form-control form-control-lg" id="position" readonly>
                                                </div>
                                                <div class="form-group col-md-6">
                                                  <label for="phone">Teléfono</label>
                                                  <input v-model="contact.phone" type="text" class="form-control form-control-lg" id="phone" readonly>
                                                </div>
                                              </div>
                                            </li>
                                          </ul>
                                        </div>
                                        <div class="col-md-12 mb-4" v-show="client.contacts.length <= 0">
                                          <p>No hay contactos agregados.</p>
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                                <div class="col-md-12 mt-2" v-show="project_clients.length <= 0">
                                  <p class="text-center">
                                    No hay clientes.
                                  </p>
                                </div>
                              </div>
                            </div>
                            <!-- Fin -->
                            <div class="tab-pane fade mt-3" id="funders" role="tabpanel" aria-labelledby="funders-tab">
                              <div class="row">
                                <div class="col-md-12 mt-2" v-for="funder in project_funders" :key="funder[0].id" v-show="project_funders.length > 0">
                                  <div class="card">
                                    <div class="card-header bg-white">
                                      <div class="form-row">
                                        <div class="col-md-9">
                                          <p class="mb-0 font-weight-bold">{{ funder[0].name }} <span class="badge badge-secondary">{{ funder[0].sh_description == null ? '' : funder[0].sh_description}}</span></p>
                                        </div>
                                        <div class="col-md-3">
                                          <button type="button" class="btn btn-outline-secondary float-right font-weight-bold" @click="addContact(funder[0].stackeholder_id)" v-if="$gate.ifHavePermisos([1, 2, 8])">
                                            <i class="fas fa-user-plus mr-1"></i>
                                          </button>
                                          <button type="button" class="btn btn-outline-secondary float-right font-weight-bold" @click="deleteFunder(funder[0].id)" v-if="$gate.ifHavePermisos([1, 2, 8])">
                                            <i class="fas fa-times mr-1"></i>
                                          </button>
                                        </div>

                                      </div>
                                      <hr class="mb-0 mt-3">
                                    </div>
                                    <div class="card-body pt-0">
                                      <div class="row">
                                        <div class="col-md-12 mb-4" v-show="funder.contacts.length > 0">
                                          <p class="font-weight-bold mb-3">
                                            Contactos
                                          </p>
                                          <ul class="list-group list-group-flush">
                                            <li class="list-group-item pt-0 pb-0 pl-3 pr-3" v-for="contact in funder.contacts" :key="contact.id">
                                              <div class="row list-header pt-2 pb-2">
                                                <div class="col-md-10">
                                                  <p class="pointer mb-0" data-toggle="collapse" :href="'#contactInfo'+contact.id" role="button" aria-expanded="false" :aria-controls="'contactInfor'+contact.id">
                                                    <span class="badge badge-circle badge-secondary">{{ contact.name | myFirstLetter }}</span>
                                                    <span class="pt-1">{{ contact.name }}</span>
                                                  </p>
                                                </div>
                                                <div class="col-md-2">
                                                  <i class="fas fa-user-times pointer" @click="deleteContact(contact.id)" v-if="$gate.ifHavePermisos([1, 2, 8])"></i>
                                                  &nbsp;&nbsp;
                                                  <i class="fas fa-user-edit pointer" @click="editContact(contact)" v-if="$gate.ifHavePermisos([1, 2, 8])"></i>
                                                </div>
                                              </div>
                                              <div class="form-row mt-3 collapse" :id="'contactInfo'+contact.id">
                                                <div class="form-group col-md-6">
                                                  <label for="name">Nombre</label>
                                                  <input v-model="contact.name" type="text" class="form-control form-control-lg" id="funder_name" readonly>
                                                </div>
                                                <div class="form-group col-md-6">
                                                  <label for="email">Correo electrónico</label>
                                                  <input v-model="contact.email" type="email" class="form-control form-control-lg" id="funder_email" readonly>
                                                </div>
                                                <div class="form-group col-md-6">
                                                  <label for="name">Puesto</label>
                                                  <input v-model="contact.position" type="text" class="form-control form-control-lg" id="funder_position" readonly>
                                                </div>
                                                <div class="form-group col-md-6">
                                                  <label for="phone">Teléfono</label>
                                                  <input v-model="contact.phone" type="text" class="form-control form-control-lg" id="phone" readonly>
                                                </div>
                                              </div>
                                            </li>
                                          </ul>
                                        </div>
                                        <div class="col-md-12 mb-4" v-show="funder.contacts.length <= 0">
                                          <p>No hay contactos agregados.</p>
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                                <div class="col-md-12 mt-2" v-show="project_funders.length <= 0">
                                  <p class="text-center">
                                    No hay financiadores.
                                  </p>
                                </div>
                              </div>
                            </div>
                            <div class="tab-pane fade mt-3" id="suppliers" role="tabpanel" aria-labelledby="suppliers-tab">
                              <div class="row">
                                <div class="col-md-12 mt-2" v-for="supplier in project_suppliers" :key="supplier.id" v-show="project_suppliers.length > 0">
                                  <div class="card">
                                    <div class="card-header bg-white">
                                      <div class="form-row">
                                        <div class="col-md-9">
                                          <p class="mb-0 font-weight-bold">{{ supplier[0].name }} <span class="badge badge-secondary">{{ supplier[0].sh_description }}</span></p>
                                        </div>
                                        <div class="col-md-3">
                                          <button type="button" class="btn btn-outline-secondary float-right font-weight-bold" @click="addContact(supplier[0].stackeholder_id)" v-if="$gate.ifHavePermisos([1, 2, 8])">
                                            <i class="fas fa-user-plus mr-1"></i>
                                          </button>
                                          <button type="button" class="btn btn-outline-secondary float-right font-weight-bold" @click="deleteSupplier(supplier[0].id)" v-if="$gate.ifHavePermisos([1, 2, 8])">
                                            <i class="fas fa-times mr-1"></i>
                                          </button>
                                        </div>
                                      </div>
                                      <hr class="mb-0 mt-3">
                                    </div>
                                    <div class="card-body pt-0">
                                      <div class="row">
                                        <div class="col-md-12 mb-4" v-show="supplier.contacts.length > 0">
                                          <p class="font-weight-bold mb-3">
                                            Contactos
                                          </p>
                                          <ul class="list-group list-group-flush">
                                            <li class="list-group-item pt-0 pb-0 pl-3 pr-3" v-for="contact in supplier.contacts" :key="contact.id">
                                              <div class="row list-header pt-2 pb-2">
                                                <div class="col-md-10">
                                                  <p class="pointer mb-0" data-toggle="collapse" :href="'#contactInfo'+contact.id" role="button" aria-expanded="false" :aria-controls="'contactInfor'+contact.id">
                                                    <span class="badge badge-circle badge-secondary">{{ contact.name | myFirstLetter }}</span>
                                                    <span class="pt-1">{{ contact.name }}</span>
                                                  </p>
                                                </div>
                                                <div class="col-md-2">
                                                  <i class="fas fa-user-times pointer" @click="deleteContact(contact.id)" v-if="$gate.ifHavePermisos([1, 2, 8])"></i>
                                                  &nbsp;&nbsp;
                                                  <i class="fas fa-user-edit pointer" @click="editContact(contact)" v-if="$gate.ifHavePermisos([1, 2, 8])"></i>
                                                </div>
                                              </div>
                                              <div class="form-row mt-3 collapse" :id="'contactInfo'+contact.id">
                                                <div class="form-group col-md-6">
                                                  <label for="name">Nombre</label>
                                                  <input v-model="contact.name" type="text" class="form-control form-control-lg" id="name" readonly>
                                                </div>
                                                <div class="form-group col-md-6">
                                                  <label for="email">Correo electrónico</label>
                                                  <input v-model="contact.email" type="email" class="form-control form-control-lg" id="supplier_email" readonly>
                                                </div>
                                                <div class="form-group col-md-6">
                                                  <label for="name">Puesto</label>
                                                  <input v-model="contact.position" type="text" class="form-control form-control-lg" id="supplier_position" readonly>
                                                </div>
                                                <div class="form-group col-md-6">
                                                  <label for="phone">Teléfono</label>
                                                  <input v-model="contact.phone" type="text" class="form-control form-control-lg" id="phone" readonly>
                                                </div>
                                              </div>
                                            </li>
                                          </ul>
                                        </div>
                                        <div class="col-md-12 mb-4" v-show="supplier.contacts.length <= 0">
                                          <p>No hay contactos agregados.</p>
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                                <div class="col-md-12 mt-2" v-show="project_suppliers.length <= 0">
                                  <p class="text-center">
                                    No hay proveedores.
                                  </p>
                                </div>
                              </div>
                            </div>
                            <div class="tab-pane fade mt-3" id="consortion" role="tabpanel" aria-labelledby="consortion-tab">
                              <div class="row">
                                <div class="col-md-12 mt-2" v-for="supplier in project_consortions" :key="supplier.id" v-show="project_consortions.length > 0">
                                  <div class="card">
                                    <div class="card-header bg-white">
                                      <div class="form-row">
                                        <div class="col-md-9">
                                          <p class="mb-0 font-weight-bold">{{ supplier[0].name }} <span class="badge badge-secondary">{{ supplier[0].sh_description }}</span></p>
                                        </div>
                                        <div class="col-md-3">
                                          <button type="button" class="btn btn-outline-secondary float-right font-weight-bold" @click="addContact(supplier[0].stackeholder_id)" v-if="$gate.ifHavePermisos([1, 2, 8])">
                                            <i class="fas fa-user-plus mr-1"></i>
                                          </button>
                                          <button type="button" class="btn btn-outline-secondary float-right font-weight-bold" @click="deleteConsortion(supplier[0].id)" v-if="$gate.ifHavePermisos([1, 2, 8])">
                                            <i class="fas fa-times mr-1"></i>
                                          </button>
                                        </div>
                                      </div>
                                      <hr class="mb-0 mt-3">
                                    </div>
                                    <div class="card-body pt-0">
                                      <div class="row">
                                        <div class="col-md-12 mb-4" v-show="supplier.contacts.length > 0">
                                          <p class="font-weight-bold mb-3">
                                            Contactos
                                          </p>
                                          <ul class="list-group list-group-flush">
                                            <li class="list-group-item pt-0 pb-0 pl-3 pr-3" v-for="contact in supplier.contacts" :key="contact.id">
                                              <div class="row list-header pt-2 pb-2">
                                                <div class="col-md-10">
                                                  <p class="pointer mb-0" data-toggle="collapse" :href="'#contactInfo'+contact.id" role="button" aria-expanded="false" :aria-controls="'contactInfor'+contact.id">
                                                    <span class="badge badge-circle badge-secondary">{{ contact.name | myFirstLetter }}</span>
                                                    <span class="pt-1">{{ contact.name }}</span>
                                                  </p>
                                                </div>
                                                <div class="col-md-2">
                                                  <i class="fas fa-user-times pointer" @click="deleteContact(contact.id)" v-if="$gate.ifHavePermisos([1, 2, 8])"></i>
                                                  &nbsp;&nbsp;
                                                  <i class="fas fa-user-edit pointer" @click="editContact(contact)" v-if="$gate.ifHavePermisos([1, 2, 8])"></i>
                                                </div>
                                              </div>
                                              <div class="form-row mt-3 collapse" :id="'contactInfo'+contact.id">
                                                <div class="form-group col-md-6">
                                                  <label for="name">Nombre</label>
                                                  <input v-model="contact.name" type="text" class="form-control form-control-lg" id="name" readonly>
                                                </div>
                                                <div class="form-group col-md-6">
                                                  <label for="email">Correo electrónico</label>
                                                  <input v-model="contact.email" type="email" class="form-control form-control-lg" id="supplier_email" readonly>
                                                </div>
                                                <div class="form-group col-md-6">
                                                  <label for="name">Puesto</label>
                                                  <input v-model="contact.position" type="text" class="form-control form-control-lg" id="supplier_position" readonly>
                                                </div>
                                                <div class="form-group col-md-6">
                                                  <label for="phone">Teléfono</label>
                                                  <input v-model="contact.phone" type="text" class="form-control form-control-lg" id="phone" readonly>
                                                </div>
                                              </div>
                                            </li>
                                          </ul>
                                        </div>
                                        <div class="col-md-12 mb-4" v-show="supplier.contacts.length <= 0">
                                          <p>No hay contactos agregados.</p>
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                                <div class="col-md-12 mt-2" v-show="project_suppliers.length <= 0">
                                  <p class="text-center">
                                    No hay consorcios.
                                  </p>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>

                    </div>
                  </div>
                </div>
                <!--  -->
                <br>
                <!--  -->
                <div class="col-md-12" v-if="edit || state_view == 1" :style="style_responsive">
                  <div class="card card-shadow-one bg-light-one pointer">
                    <div class="card-body margin-profile" >
                      <p class="form-control-three mt-2 d-inline-block">Archivos</p>

                      <div class="row">
                        <div class="col-md-12 mb-3">

                          &nbsp;
                          <button class="btn btn-outline-blue float-right" @click="newModalFileDropbox" v-show="$gate.ifHavePermisos([1, 2, 8])">
                            <i class="fas fa-plus"></i>
                            Agregar liga de Dropbox
                          </button>
                          &nbsp;
                          <button class="btn btn-outline-blue float-right" @click="newModal" v-show="$gate.ifHavePermisos([1, 2, 8])">
                            <i class="fas fa-plus"></i>
                            Agregar archivo
                          </button>
                        </div>
                        <div class="col-md-12" v-if="project.files && project.files.length > 0">

                          <template v-if="visible">
                            <div class="row">
                                <div class="col-md-12 mt-4 text-center">
                                    <p>
                                        <i class="fas fa-spinner fa-pulse fa-4x"></i>
                                    </p>
                                    <p class="h4">Descargando...</p>
                                </div>
                            </div>
                          </template>
                          <template v-else>
                            <table class="table responsive">
                              <thead class="thead-blue">
                                <tr>
                                  <th scope="col" width="45%">Nombre</th>
                                  <th scope="col" width="15%">Tipo</th>
                                  <th scope="col" width="12%">Fecha</th>
                                  <th scope="col" width="14%">Descargar / Acceder</th>
                                  <th scope="col" width="9%">Eliminar</th>
                                </tr>
                              </thead>
                              <tbody>
                                <tr v-for="file in project.files">
                                  <td>{{file.name}}</td>
                                  <td>
                                    <i
                                    class="fas fa-lg align-self-center mr-2"
                                    :class="{
                                      'fab fa-dropbox' : file.type == 'Dropbox',
                                      'fa-file-pdf text-danger' : file.type == '.pdf',
                                      'fa-file-word text-primary' : file.type == '.doc' || file.type == '.docx',
                                      'fa-file-excel text-success' : file.type == '.xls' || file.type == '.xlsx',
                                      'fa-file-csv text-success' : file.type == '.csv',
                                      'fa-file-archive text-info' : file.type == '.zip',
                                      'fa-file-powerpoint text-orange' : file.type == '.ppt' || file.type == '.pptx' || file.type == '.pps',
                                      'fa-file-alt' : file.type != 'Dropbox' && file.type != '.pdf' && file.type != '.doc' && file.type != '.docx' && file.type != '.xls' && file.type != '.xlsx' && file.type != '.csv' && file.type != '.zip'
                                      && file.type != '.ppt' && file.type != '.pptx' && file.type != '.pps'
                                      }"
                                      ></i>
                                      &nbsp;{{file.type}}
                                    </td>
                                    <td>{{(file.updated_at).substring(0,10)}}</td>
                                    <td>
                                      <template v-if="file.type != 'Dropbox'">
                                        <button type="button" class="btn" title="Descargar archivo" data-toggle="tooltip" data-placement="top" @click="downloadFile(file)" v-show="$gate.ifHavePermisos([1, 2, 3, 4, 7])">
                                          <img src="/img/np_download.png" style="width : 1rem;">
                                        </button>
                                      </template>
                                      <template v-else>

                                        <a class="btn" :href="file.file" target="_blank">
                                          <i class="fas fa-external-link-alt"></i>
                                        </a>
                                      </template>
                                    </td>
                                    <td>
                                      <button type="button" class="btn" title="Eliminar archivo" data-toggle="tooltip" data-placement="top" @click="deleteFile(file)" v-show="$gate.ifHavePermisos([1, 2, 8])">
                                        <img src="/img/np_delete.png" style="width : 1rem;">
                                      </button>
                                    </td>
                                  </tr>
                                </tbody>
                              </table>
                          </template>


                          </div>
                          <div class="col-md-12 text-center" v-else>
                            <p>No hay archivos para este proyecto.</p>
                            <img src="/img/sin-archivos-cargados.png" width="40%">
                          </div>
                        </div>

                      </div>
                    </div>
                  </div>
                  <br>
                  <!--  -->
                  <!--  -->
                </div>
                <!-- Fin -->
                <div class="tab-pane fade mt-3" id="administrative" role="tabpanel" aria-labelledby="administrative-tab">

                  <div class="col-md-12" :style="style_responsive">
                    <div class="card card-shadow-one bg-light-one pointer">
                      <div class="card-body margin-profile" >
                        <p class="form-control-three mt-2 d-inline-block">Datos de administración</p>
                        <div class="form-row">
                          <div class="col-md-6">
                            <label><b>Contrato</b></label><br>
                            <label>Mecanismo de contratación </label><br>
                            <label>&nbsp;</label>
                            <input type="text" class="form-control" v-model="project.mechanism_contract" :disabled="!edit">
                          </div>
                          <div class="col-md-2">
                            <label><b>Monto del contrato</b></label><br>
                            <label>Fecha del contrato </label><br>
                            <label>&nbsp;</label>
                            <input type="date" class="form-control" v-model="project.date_contract" :disabled="!edit">
                          </div>
                          <div class="col-md-2">
                            <label>&nbsp;</label><br>
                            <label>Divisa</label><br>
                            <label>&nbsp;</label>
                            <v-select :options="listCurrencys" v-model="project.currency" label="name" :disabled="!edit"></v-select>
                          </div>
                          <div class="col-md-2">
                            <label>&nbsp;</label><br>
                            <label>Tipo cambio (a la fecha del contrato)</label>
                            <input type="text" class="form-control mb-1" v-model="project.type_change" :disabled="!edit">
                          </div>

                        </div>
                        <div class="form-row">
                          <div class="col-md-6">
                            <label>Tipo de contrato</label>
                            <v-select :options="listContracts" label="name" v-model="project.contract" :disabled="!edit"></v-select>
                          </div>
                          <div class="col-md-4">
                            <label>Monto antes de IVA (moneda original)</label>
                            <money v-model="project.amount_before_iva" v-bind="money" class="form-control" :disabled="!edit" style="background-color:white; cursor: text;"></money>

                          </div>
                          <div class="col-md-2">
                            <label>Divisa</label>
                            <v-select :options="listCurrencys" v-model="project.currency" label="name" :disabled="!edit"></v-select>
                          </div>
                        </div>
                        <div class="form-row">
                          <div class="col-md-6">
                            <label>Número / Folio del contrato</label>
                            <input v-model="project.folio" class="form-control" :disabled="!edit" id="folio" style="background-color:white; cursor: text;">
                          </div>
                          <div class="col-md-4">
                            <label>IVA (moneda original)</label>
                            <money v-model="project.iva" v-bind="money" class="form-control" :disabled="!edit" style="background-color:white; cursor: text;"></money>
                          </div>
                          <div class="col-md-2">
                            <label>Divisa</label>
                            <v-select :options="listCurrencys" v-model="project.currency_iva" label="name" :disabled="!edit"></v-select>
                          </div>
                        </div>

                        <div class="form-row">
                          <template v-if="edit  == false">
                            <div class="col-md-6 mb-3">
                              <label>Tipo de documento de satisfacción :</label>
                              <div :disabled="!edit" class="mb-2" v-for="satisfaction_document in satisfaction_documents" :key="satisfaction_document.id">
                                <i class="far fa-lg pointer"
                                :class="{
                                  'fa-check-square' : project.satisfaction_documents.filter(sd => sd.id === satisfaction_document.id).length > 0,
                                  'fa-square' : project.satisfaction_documents.filter(sd => sd.id === satisfaction_document.id).length <= 0
                                  }">
                                </i> {{ satisfaction_document.name }}
                              </div>
                            </div>
                          </template>
                          <template v-else>
                            <template v-if="$gate.ifHavePermisos([1, 2, 8])">

                              <div class="col-md-6 mb-3">
                                <label>Tipo de documento de satisfacción :</label>
                                <div class="mb-2" v-for="satisfaction_document in satisfaction_documents" :key="satisfaction_document.id">
                                  <i class="far fa-lg pointer"
                                  @click="selectSatisfactionDocument(satisfaction_document)"
                                  :class="{
                                    'fa-check-square' : project.satisfaction_documents.filter(sd => sd.id === satisfaction_document.id).length > 0,
                                    'fa-square' : project.satisfaction_documents.filter(sd => sd.id === satisfaction_document.id).length <= 0
                                    }">
                                  </i> {{ satisfaction_document.name }}
                                </div>
                              </div>
                            </template>
                          </template>
                          <div class="col-md-4">
                            <label>Monto después de IVA(moneda original)</label>
                            <money v-model="project.amount" v-bind="money" :disabled="edit" class="form-control" id="amount" style="background-color:white; cursor: text;"></money>
                          </div>
                          <div class="col-md-2">
                            <label>Divisa</label>
                            <v-select :options="listCurrencys" v-model="project.currency" label="name" :disabled="edit"></v-select>
                          </div>

                        </div>
                        <div class="form-row">
                          <template v-if="project.satisfaction_documents.filter(sd => sd.id == 3).length == 1">
                            <div class="col-md-12">
                              <template v-if="!edit">
                                <span class="badge badge-secondary p-2 mt-2 mr-2" v-for="type in document_type_names" >
                                  {{ type.name }}
                                </span>
                              </template>
                              <template v-else>
                                <v-select taggable multiple v-model="document_type_names" :options="listUsers" label="name" :disabled="!$gate.ifHavePermisos([1, 2, 8])"/>
                              </template>
                            </div>
                          </template>
                        </div>

                        <div class="form-row">
                          <div class="col-md-6">
                            <label>A nombre de quién o qué identidad se hizo el contrato</label>
                            <input type="text" class="form-control" :disabled="!edit" v-model="project.contract_creator">
                          </div>
                          <div class="col-md-6">
                            <label>Monto total en MXN (a la fecha de firma del contrato)</label>
                            <!-- <money v-model="project.amount_mxn" v-bind="mxn" class="form-control" :disabled="!edit" id="amount_mxn" style="background-color:white; cursor: text;"></money> -->
                            <v-money-spinner v-model="project.amount_mxn" v-bind="config_mxn" :disabled="!edit"></v-money-spinner>
                          </div>
                        </div>
                        <div class="form-row">
                          <div class="col-md-6">
                            <label>Objeto contractual</label>
                            <input type="text" class="form-control" :disabled="!edit" v-model="project.contractual_object">
                          </div>
                          <div class="col-md-6">
                            <label>Monto total en USD (a la fecha de firma del contrato)</label>
                            <!-- <money v-model="project.amount_usd" v-bind="usd" class="form-control" :disabled="!edit" id="amount_usd" style="background-color:white; cursor: text;"></money> -->
                            <v-money-spinner v-model="project.amount_usd" v-bind="config_usd" :disabled="!edit"></v-money-spinner>
                          </div>
                        </div>
                        <div class="form-row">
                          <div class="col-md-3">
                            <label>Inicio del contrato</label>
                            <input type="date" class="form-control" :disabled="!edit" v-model="project.contract_start">
                          </div>
                          <div class="col-md-3">
                            <label>Fin del contrato</label>
                            <input type="date" class="form-control" :disabled="!edit" v-model="project.contract_end">
                          </div>
                          <div class="col-md-2">
                            <label>Duración del contrato</label>
                            <input type="text" class="form-control" :disabled="!edit" v-model="project.duration">
                          </div>
                        </div>
                      </div>
                    </div>
                    <br>

                    <div class="col-md-12 mb-4" >
                      <div class="margin-profile">

                        <div class="form-row">
                          <div class="col-md-6 mb-3">

                            <div class="card card-shadow-one bg-light-one pointer">
                              <div class="card-header">
                                <label class="form-control-eigth">TimeWorks</label>
                                <template v-if="edit">
                                  <label class="switch float-right">
                                    <input type="checkbox" v-model="timeworks.principal" @click="vaciar($event)">
                                    <span class="slider round"></span>
                                  </label>
                                </template>
                                <template v-else>
                                  <label class="switch-disable float-right">
                                    <input type="checkbox" v-model="timeworks.principal" disabled>
                                    <span class="slider round"></span>
                                  </label>
                                </template>
                              </div>
                              <div class="card-body" v-if="timeworks.principal">
                                <div class="form-row">
                                  <div class="col-md-12 mb-3">
                                    <label>Categoría</label>
                                    <v-select label="name" placeholder="Seleccione una opción" :options="categorias" v-model="timeworks.categoria" :disabled="!edit"></v-select>
                                  </div>
                                  <div class="col-md-12 mb-3" v-show="edit">
                                    <label>Administración del proyecto</label><br>
                                    <input type="checkbox" v-model="timeworks.oh">&nbsp;<label>+OH</label>
                                  </div>
                                </div>
                              </div>
                            </div>

                          </div>
                          <div class="col-md-6 mb-3">
                            <div class="card card-shadow-one bg-light-one pointer">
                              <div class="card-header">
                                <label class="form-control-eigth">TimeSheets</label>

                                <template v-if="edit">
                                  <label class="switch float-right">
                                    <input type="checkbox" v-model="project.time_sheets" >
                                    <span class="slider round"></span>
                                  </label>
                                </template>
                                <template v-else>
                                  <label class="switch-disable float-right">
                                    <input type="checkbox" v-model="project.time_sheets" disabled>
                                    <span class="slider round"></span>
                                  </label>
                                </template>
                              </div>

                            </div>
                          </div>
                        </div>
                      </div>
                    </div>

                  </div>

                </div>

                <div class="tab-pane fade mt-3" id="web" role="tabpanel" aria-labelledby="web-tab">

                  <div class="col-md-12" :style="style_responsive">
                    <div class="card card-shadow-one bg-light-one pointer">
                      <div class="card-body margin-profile" >
<!--
                        <croppa v-model="myCroppa"
         initial-image="/vue-croppa/static/500.jpeg"
         :width="250"
         :height="250"
         :quality="2">
 </croppa>
 -->




                        <template v-if="edit">

                        <h5 for="description_es_web"><b>Imagen</b></h5>
                        <template v-if="image_single.name == '' || image_single.name == null">
                            <div class="row">
                              <div class="col-md-9">
                                <input type="file" id="files_web_i_id" accept="image/x-png,image/gif,image/jpeg"  class="form-control" @change="onFileWIChange">
                              </div>
                              <div class="col-md-3">
                                <button type="button" name="button" class="btn btn-outline-blue" :style="color_btn" @click="addFileW()">
                                  <i class="fas fa-plus"></i> Añadir archivo</button>
                              </div>
                            </div>
                        </template>
                        <template v-else>
                          <div class="row">
                            <div class="col-md-9">
                              <a :href="image_single.path" target="_blank">{{image_single.name}}</a>
                            </div>
                            <div class="col-md-3">
                              <button type="button" name="button" class="btn btn-outline-red" @click="deleteImageSingle()">Eliminar
                                <i class="far fa-trash-alt pointer"></i>
                              </button>
                            </div>
                          </div>
                        </template>
                      </template>



                        <br>

                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                          <li class="nav-item">
                            <a class="nav-link active" id="description_es_web-tab" data-toggle="tab" href="#description_es_web" role="tab" aria-controls="description_es_web" aria-selected="true">Español</a>
                          </li>
                          <li class="nav-item">
                            <a class="nav-link" id="description_en_web-tab" data-toggle="tab" href="#description_en_web" role="tab" aria-controls="description_en_web" aria-selected="false">Inglés</a>
                          </li>
                        </ul>

                        <div class="tab-content" id="myTabContent">

                          <div class="tab-pane fade show active" id="description_es_web" role="tabpanel" aria-labelledby="description_es_web-tab">

                            <p class="form-control-three mt-2 d-inline-block">Título de la publicación</p>
                            <template v-if="edit">
                              <div class="row">
                                <div class="col-md-12">
                                    <label>Título de la publicación</label>
                                    <textarea v-model="project_title.title_es" class="form-control" rows="2" cols="80" ></textarea>
                                </div>
                              </div>
                            </template>
                            <template v-else>
                              <div class="row">
                                <div class="col-md-12">
                                  <label>Título de la publicación</label>
                                  <textarea v-model="project_title.title_es" class="form-control" rows="2" cols="80" disabled></textarea>
                                </div>
                              </div>
                            </template>
                            <br>
                            <template v-if="project.company.id == 1">
                              <div class="form-row">
                                <div class="form-group col-md-3">
                                  <label for="inputCity">Fecha de publicación</label>
                                  <input type="date" v-model="data_web.publication_date" class="form-control" id="inputCity">
                                </div>
                                <div class="form-group col-md-3">
                                  <label for="inputState">Tipo</label>
                                  <select id="inputState" class="form-control" v-model="data_web.type">
                                    <option selected>Seleccione...</option>
                                    <option value="true">Publicación</option>
                                    <option value="false">Prensa</option>
                                    <option value="">No activo</option>
                                  </select>
                                </div>
                              </div>
                            </template>
                            <br>
                            <p class="form-control-three mt-2 d-inline-block">Texto de la publicación</p>
                            <template v-if="edit && project.company.id != 1">
                              <div class="row">
                                <div class="col-md-4">
                                  <div class="form-check">
                                    <input class="form-check-input" type="checkbox" v-model="data_web.publication_principal" id="flexCheckDefault">
                                    <label class="form-check-label" for="flexCheckDefault">
                                      &nbsp;Marcar como publicación principal
                                    </label>
                                  </div>
                                </div>
                                <template v-if="data_web.publication_principal">

                                  <div class="col-md-12">
                                    <label>Resumen del proyecto</label>
                                    <textarea v-model="project_resumen.resumen_es" class="form-control" rows="8" cols="80" :maxlength="max"></textarea>
                                  </div>
                                </template>
                              </div>
                            </template>

                            <br>

                            <h5 for="description_es_web"><b>Descripción general</b></h5>
                            <template v-if="edit == false">
                              <div class="form-group">
                                <div v-html="data_web.description_es"></div>
                              </div>
                            </template>
                            <template v-else>
                              <template v-if="project.company.id != 1">
                                <div class="form-group">
                                  <vue-editor v-model="data_web.description_es" :editor-toolbar="customToolbar" style="background:#ffffff !important;"></vue-editor>
                                </div>
                              </template>
                              <template v-if="project.company.id == 1">
                                <vue-editor v-model="data_web.description_es" style="background:#ffffff !important;"></vue-editor>
                              </template>
                            </template>

                            <template v-if="project.company.id != 1">
                              <h5 for="description_es_web"><b>Subtítulos</b></h5>
                              <template  v-if="edit != false">
                                <div class="form-row">
                                  <div class="col-md-2">
                                    <button type="button" name="button" class="btn btn-outline-blue" @click="addSub()">
                                      <i class="fas fa-plus"></i> Añadir sección
                                    </button>
                                  </div>
                                </div>
                              </template>
                              <template v-for="(tes, index) in data_web.subtitles"  >

                                <template v-if="edit == false">
                                  <div class="form-group mt-3">
                                    <p><b>{{tes.name_seccion_es}}</b></p>
                                    <div v-html="tes.subtitle_es"></div>

                                  </div>
                                </template>

                                <template v-else>
                                  <div class="form-row">
                                    <div class="col-md-10">
                                      <label>Nombre de la sección</label>
                                      <input type="text" class="form-control" v-model="tes.name_seccion_es">
                                    </div>
                                    <div class="col-md-2">
                                      <label>&nbsp;</label><br>
                                      <button type="button" name="button" class="btn btn-outline-red" @click="deleteSub(index)">Eliminar
                                        <i class="far fa-trash-alt pointer"></i>
                                      </button>
                                    </div>
                                  </div>
                                  <div class="form-group mt-3">
                                    <vue-editor v-model="tes.subtitle_es" :editor-toolbar="customToolbar" style="background:#ffffff !important;"></vue-editor>
                                  </div>
                                </template>

                              </template>
                            </template>



                          </div>

                          <div class="tab-pane fade" id="description_en_web" role="tabpanel" aria-labelledby="description_en_web-tab">
                            <p class="form-control-three mt-2 d-inline-block">Publication title</p>
                            <template v-if="edit">
                              <div class="row">
                                <div class="col-md-12">
                                    <label>Publication title</label>
                                    <textarea v-model="project_title.title_en" class="form-control" rows="2" cols="80" ></textarea>
                                </div>
                              </div>
                            </template>
                            <template v-else>
                              <div class="row">
                                <div class="col-md-12">
                                  <label>Publication title</label>
                                  <textarea v-model="project_title.title_en" class="form-control" rows="2" cols="80" disabled></textarea>
                                </div>
                              </div>
                            </template>
                            <br>
                            <p class="form-control-three mt-2 d-inline-block">Publication text</p>
                            <template v-if="edit && project.company.id != 1">
                              <div class="row">
                                <div class="col-md-4">
                                  <div class="form-check">
                                    <input class="form-check-input" type="checkbox" v-model="data_web.publication_principal" id="flexCheckDefault">
                                    <label class="form-check-label" for="flexCheckDefault">
                                      &nbsp;Mark as primary publication
                                    </label>
                                  </div>
                                </div>
                                <template v-if="data_web.publication_principal">

                                  <div class="col-md-12">
                                    <label>Project summary</label>
                                    <textarea v-model="project_resumen.resumen_en" class="form-control" rows="8" cols="80" :maxlength="max"></textarea>
                                  </div>
                                </template>
                              </div>
                            </template>
                            <br>
                            <h5 for="description_es_web"><b>General description</b></h5>

                            <template v-if="edit == false">
                              <div class="form-group mt-3">
                                <div v-html="data_web.description_en"></div>
                              </div>
                            </template>
                            <template v-else>
                              <template v-if="project.company.id != 1">
                                <div class="form-group mt-3">
                                  <vue-editor v-model="data_web.description_en" :editor-toolbar="customToolbar" style="background:#ffffff !important;"></vue-editor>
                                </div>
                              </template>
                              <template v-if="project.company.id == 1">
                                <vue-editor v-model="data_web.description_en"  style="background:#ffffff !important;"></vue-editor>
                              </template>
                            </template>
                            <template v-if="project.company.id != 1">
                              <h5 for="description_es_web"><b>Subtitle</b></h5>
                              <template  v-if="edit != false">
                                <div class="form-row">
                                  <div class="col-md-2">
                                    <button type="button" name="button" class="btn btn-outline-blue" @click="addSub()">
                                      <i class="fas fa-plus"></i> Añadir sección
                                    </button>
                                  </div>
                                </div>
                              </template>
                              <template v-for="(ten, indexs) in data_web.subtitles"  >

                                <template v-if="edit == false">
                                  <div class="form-group">
                                    <p><b>{{ten.name_seccion_en}}</b></p>
                                    <div v-html="ten.subtitle_en"></div>
                                  </div>
                                </template>
                                <template v-else>
                                  <div class="form-row">
                                    <div class="col-md-10">
                                      <label>Section name</label>
                                      <input type="text" class="form-control" v-model="ten.name_seccion_en">
                                    </div>
                                    <div class="col-md-2">
                                      <label>&nbsp;</label><br>
                                      <button type="button" name="button" class="btn btn-outline-red" @click="deleteSub(indexs)">Eliminar
                                        <i class="far fa-trash-alt pointer"></i>
                                      </button>
                                    </div>
                                  </div>
                                  <div class="form-group">
                                    <vue-editor v-model="ten.subtitle_en" :editor-toolbar="customToolbar" style="background:#ffffff !important;"></vue-editor>
                                  </div>
                                </template>

                              </template>
                            </template>

                          </div>

                        </div>





                      </div>
                    </div>


                  </div>
                  <br>
                  <div class="row" style="margin-left:0px;margin-right:0px;">
                    <div class="col-md-8"  :style="style_responsive">
                      <div class="card card-shadow-one bg-light-one pointer" v-show="!edit_resources">
                        <div class="card-body margin-profile" >
                          <label class="form-control-eigth">Recursos descargables</label>
                          <template v-if="edit">
                            <label class="switch float-right">
                              <input type="checkbox" v-model="data_web.files" >
                              <span class="slider round"></span>
                            </label>
                          </template>
                          <template v-if="data_web.files != false && edit">

                          <div class="row">
                            <div class="col-md-8">
                              <label>Nombre del archivo</label>
                              <input type="file" id="files_web_id" class="form-control" @change="onFileWChange">
                            </div>
                            <div class="col-md-4">
                              <label>&nbsp;</label>
                              <br>
                              <button type="button" name="button" class="btn btn-outline-blue" @click="addFileW()">
                                <i class="fas fa-plus"></i> Añadir archivo</button>
                            </div>
                          </div>
                          <br>

                            <template v-for="(f, index) in data_web.files_n">
                              <div class="row">
                            <div class="col-md-9">
                              <a :href="f.path" target="_blank">{{f.name}}</a>
                            </div>
                            <div class="col-md-3">
                              <button type="button" name="button" class="btn btn-outline-red" @click="deleteFills(index)">Eliminar
                                <i class="far fa-trash-alt pointer"></i>
                              </button>
                            </div>
                          </div>
                          </template>

                        </template>

                        </div>
                      </div>
                      <div class="card card-shadow-one bg-light-one pointer" v-show="edit_resources">
                        <div class="card-body margin-profile" >
                          <p>
                            <i class="fas fa-spinner fa-pulse fa-4x"></i>
                          </p>
                          <p class="h4">Guardando...</p>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-4"  :style="style_responsive">
                      <div class="card card-shadow-one bg-light-one pointer">
                        <div class="card-body margin-profile" >
                          <label class="form-control-eigth">Publicar</label>
                          <template v-if="edit">
                            <label class="switch float-right">
                              <input type="checkbox" v-model="data_web.public_state" >
                              <span class="slider round"></span>
                            </label>

                            <!-- <template v-if="data_web.public_state == true">

                            <div class="form-check">
                              <input class="form-check-input" type="checkbox" v-model="data_web.publication_principal" id="flexCheckDefault">
                              <label class="form-check-label" for="flexCheckDefault">
                                &nbsp;Marcar como publicación principal
                              </label>
                            </div>
                          </template> -->
                          </template>



                        </div>
                      </div>
                    </div>
                  </div>


                </div>

              </div>
            </div>
            <!-- Fin segunda sessión -->


            <div class="col-md-12 mb-3 float-center" v-if="edit && !nuevo">
              <button class="btn btn-outline-orange-one" @click="updateProject()">
                <i class="fas fa-plus mr-1"></i>
                Guardar cambios
              </button>
              <button type="button" class="btn btn-outline-gray-one" @click="cancelar()">Cancelar</button>
            </div>

            <div class="col-md-12 mb-3 float-center" v-if="nuevo">
              <button class="btn btn-outline-orange-one" @click="createProyecto()">
                <i class="fas fa-plus mr-1"></i>
                Agregar Proyecto
              </button>
              <button type="button" class="btn btn-outline-gray-one" @click="cancelar()">Cancelar</button>
            </div>

          </div>
        </div>
      </div>

      <!-- Modal -->
      <div class="modal fade" id="addNew" tabindex="-1" role="dialog" aria-labelledby="addNewLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="addNewLabel">Agregar documento</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form @submit.prevent="addDocument()" enctype="multipart/form-data">
              <div class="modal-body">
                <div class="form-group">
                  <label for="name">Nombre del archivo</label>
                  <input v-model="fileform.name" type="text" name="name" placeholder="" class="form-control" :class="{ 'is-invalid': fileform.errors.has('name') }">
                  <has-error :form="fileform" field="name"></has-error>
                </div>

                <div class="form-group">
                  <label for="exampleFormControlFile1">Documento</label>
                  <input @change="onImageChange" type="file" class="form-control-file" id="exampleFormControlFile1">
                </div>
              </div>
              <div class="modal-footer">
                <button type="submit" class="btn btn-orange btn-round">Agregar</button>
                <button type="button" class="btn btn-secondary btn-round" data-dismiss="modal">Cancelar</button>
              </div>
            </form>
          </div>
        </div>
      </div>

      <!-- Modal File Dropbox-->
      <div class="modal fade" id="addNewFileDropbox" tabindex="-1" role="dialog" aria-labelledby="addNewLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="addNewLabel">Agregar liga a Dropbox</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <div class="form-group">
                <label for="name">Nombre del archivo</label>
                <input v-model="fileform.name" type="text" name="name" placeholder="" class="form-control" :class="{ 'is-invalid': fileform.errors.has('name') }">
                <has-error :form="fileform" field="name"></has-error>
              </div>

              <div class="form-group">
                <label for="name">Link</label>
                <input v-model="fileform.file" type="text" name="file" placeholder="" class="form-control" >
                <has-error :form="fileform" field="file"></has-error>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-orange btn-round" @click="addDocumentDropbox()">Agregar</button>
              <button type="button" class="btn btn-secondary btn-round" data-dismiss="modal">Cancelar</button>
            </div>
          </div>
        </div>
      </div>

      <!-- Modal New Member -->
      <div class="modal fade" id="addNewMembers"  tabindex="-1" role="dialog" aria-labelledby="addNewLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="addNewLabel">Agregar colaborador</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <!-- <div class="form-group">
                <label for="name">Nombre</label>
                <v-select :options="listUsers" label="name" v-model="listusersproject"></v-select>
              </div> -->

              <div class="form-row align-items-center">
    <div class="col-md-5">
      <label class="sr-only" for="inlineFormInput">Name</label>
      <v-select :options="listUsers" class="mb-2 md-3" label="name" v-model="member_project"></v-select>
    </div>
    <div class="col-auto">
      <div class="form-check mb-2">
        <input class="form-check-input" type="checkbox" id="autoSizingCheck" v-model="project_manager">
        <label class="form-check-label" for="autoSizingCheck">
          Administrador(a) Proyecto
        </label>
      </div>
    </div>
  </div>

            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-orange btn-round" @click="GuardarMember()">Agregar</button>
              <button type="button" class="btn btn-secondary btn-round" data-dismiss="modal">Cancelar</button>
            </div>
          </div>
        </div>
      </div>

      <!-- Modal new cliente , fiunanciador , proveedor -->
      <!-- Modal New Member -->
      <div class="modal fade" id="addNewCFP"  tabindex="-1" role="dialog" aria-labelledby="addNewLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" >Agregar {{title_button}}</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">

              <div class="form-group">
                <label for="name">{{title_button}}</label>
                <v-select multiple :options="listaStackeholders" label="name" v-model="project_cliente_new"></v-select>
              </div>


            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-orange btn-round" @click="GuardarCFP()">Agregar</button>
              <button type="button" class="btn btn-secondary btn-round" data-dismiss="modal">Cancelar</button>
            </div>
          </div>
        </div>
      </div>

      <!-- Modal contact-->
      <div class="modal fade" id="addNewContact" tabindex="-1" role="dialog" aria-labelledby="addNewLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" >{{contact_new.id == 0 ? 'Agregar ' : 'Editar '}}Contacto</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">

              <div class="form-row">
                <div class="form-group col-md-6">
                  <label for="name">Nombre</label>
                  <input v-model="contact_new.name" type="text" class="form-control form-control-lg" id="name">
                </div>
                <div class="form-group col-md-6">
                  <label for="email">Correo electrónico</label>
                  <input v-model="contact_new.email" type="email" class="form-control form-control-lg" id="supplier_email">
                </div>
                <div class="form-group col-md-6">
                  <label for="name">Puesto</label>
                  <input v-model="contact_new.position" type="text" class="form-control form-control-lg" id="supplier_position">
                </div>
                <div class="form-group col-md-6">
                  <label for="phone">Teléfono</label>
                  <input v-model="contact_new.phone" type="text" class="form-control form-control-lg" id="phone">
                </div>
              </div>

            </div>
            <div class="modal-footer">
              <template v-if="contact_new.id == 0">
                <button type="button" class="btn btn-orange btn-round" @click="GuardarContacto()">Agregar</button>
              </template>
              <template v-else>
                <button type="button" class="btn btn-orange btn-round" @click="EditarContacto()">Editar</button>
              </template>
              <button type="button" class="btn btn-secondary btn-round" data-dismiss="modal">Cancelar</button>
            </div>
          </div>
        </div>
      </div>

      <!-- Modal contact-->
      <div class="modal fade" id="addNewMembersExtern" tabindex="-1" role="dialog" aria-labelledby="addNewLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" >{{contact_new.id == 0 ? 'Agregar ' : 'Editar '}} colaborador</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">

              <div class="form-row">
                <div class="form-group col-md-6">
                  <label for="name">Nombre</label>
                  <input v-model="member_extern_new.name" type="text" class="form-control form-control-lg" id="name">
                </div>
                <div class="form-group col-md-6">
                  <label for="email">Correo electrónico</label>
                  <input v-model="member_extern_new.email" type="email" class="form-control form-control-lg" id="supplier_email">
                </div>
                <div class="form-group col-md-6">
                  <label for="name">Puesto</label>
                  <input v-model="member_extern_new.position" type="text" class="form-control form-control-lg" id="supplier_position">
                </div>
                <div class="form-group col-md-6">
                  <label for="phone">Teléfono</label>
                  <input v-model="member_extern_new.phone" type="text" class="form-control form-control-lg" id="phone">
                </div>
              </div>

            </div>
            <div class="modal-footer">
              <template v-if="member_extern_new.id == 0">
                <button type="button" class="btn btn-orange btn-round" @click="GuardarColaboradorExterno()">Agregar</button>
              </template>
              <template v-else>
                <button type="button" class="btn btn-orange btn-round" @click="EditarColaboradorExterno()">Editdar</button>
              </template>
              <button type="button" class="btn btn-secondary btn-round" data-dismiss="modal">Cancelar</button>
            </div>
          </div>
        </div>
      </div>

      <NoActive v-if="!loading && !$gate.isActive()" />
    </div>
  </template>

  <script>
  import { VueEditor } from "vue2-editor";
  import NoActive from '../complements/NoActive'
  import NoAccess from '../complements/NoAccess'
  import Loading from '../complements/Loading'
  import {Money} from 'v-money'
  export default {
    components:{
      Money, Loading, NoActive, NoAccess, VueEditor
    },
    data: () => ({
      isActiveTeam : false,
      textLoadingTeam : '',

      visible : false,
      myCroppa: {},

      color_btn : 'color :#686e76 !important;',
      max : 450,
      style_responsive :'',
      edit_resources : false,
      // configuraciones componente input money
      config_mxn: {
        spinner: false,
        min: 0,
        prefix: "$ ",
        suffix: " MXN",
        precision: 2,
        decimal: '.',
        thousands: ',',
        bootstrap: true,
        masked: true,
        align : "left",
        invalidMessage: 'Minimo es 0'
      },
      config_usd: {
        spinner: false,
        min: 0,
        prefix: "$ ",
        suffix: " USD",
        precision: 2,
        decimal: '.',
        thousands: ',',
        bootstrap: true,
        masked: true,
        align : "left",
        invalidMessage: 'Minimo es 0'
      },
      // Fin configuraciones
      document_type_names : '',
      contact_new : {
        id : 0,
        name : '',
        email : '',
        position : '',
        phone : '',
      },
      member_extern_new : {
        id : 0,
        name : '',
        email : '',
        position : '',
        phone : '',
      },
      member_extern : [],
      modalMembers : 0,
      listusersproject : [],
      member_project : {
        id : '',
        name : '',
      },
      project_manager : 0,
      project_cliente_new : '',
      project_financiador_new : '',
      project_proveedor_new : '',
      stackeholder_id :0,
      state_view : 0,
      edit : false,
      nuevo : false,
      container_class : 'container',
      title_button : 'cliente',
      title_button_team : 'Equipo interno',
      listLocations :  [],
      listCompanies : [],
      listaStackeholders : [],
      listLinkedsProjects : [],
      listContracts : [],
      listTypes : [],
      listMetodologies : [],
      listTopics : [],
      listCurrencys : [],
      tematics_web : [],
      tematics_web_complete : [],
      listUsers : [],
      project_clients : [],
      project_funders : [],
      project_suppliers : [],
      project_consortions : [],
      project_sections: [
        {
          id: 1,
          name: "Datos generales"
        },
        {
          id: 2,
          name: "Información de las partes interesadas"
        },
        {
          id:3,
          name:"Datos de administración"
        },
        {
          id:4,
          name:"Descripción del proyecto"
        },
        {
          id:5,
          name: "Archivos"
        }
      ],
      loading: false,
      privacy_options: [],
      satisfaction_documents: [],
      active_section: 0,
      project: {},
      company_initial : '',
      tematics_web_temporal : '',
      locations:[],
      MembersProject : [],
      company:{},
      contract:{},
      currency:{},
      data_web : {},
      image_single : {},
      publications: [],
      publications_edit: {
        id : 0,
        name : '',
        description : '',
        link : '',
        file : '',
      },
      data_w : {
        file : '',
        file_i : '',
      },
      years_input : '',
      usd: {
        decimal: '.',
        thousands: ',',
        prefix: '$ ',
        suffix: ' USD',
        precision: 2,
        masked: false
      },
      money: {
        decimal: '.',
        thousands: ',',
        prefix: '$ ',
        suffix: '',
        precision: 2,
        masked: false
      },
      consortiums : {
        company : '',
        company_represent : '',
        actions : '',
        final_client : '',
      },
      categorias : ['Desarrollo de clientes','No laborado','Overhead','Ovearhead','Proyectos','Administración de proyectos'],
      timeworks : {
        principal : false,
        categoria : '',
        oh : false,
        timesheets : false,
      },
      fileform: new Form({
        id:'',
        project_id: '',
        name : '',
        type: '',
        file: '',
      }),
      customToolbar: [
                        ["bold", "italic", "underline","link"],
                        [{ list: "ordered" }, { list: "bullet" }],
                    ],
                    project_resumen : {},
                    project_title : {},

      form: new Form({
        id:'',
        user_id:'',
        code:'',
        name_es:'',
        name_en:'',
        contract_name:'',
        duration:'',
        locations:[],
        privacy_options: [],
        company_id:'',
        validity_of_confidentiality:'',
        comments:'',
        clients: [],
        funders: [],
        suppliers: [],
        contract_id:'',
        contract:[],
        contract_type:'',
        satisfaction_documents:[],
        contract_backed_staff:[],
        satisfactiondoc_backed_staff:[],
        contract_creator:'',
        contractual_object:'',
        currency_id:'',
        currency:[],
        amount:0,
        amount_mxn:0,
        amount_usd:0,
        folio : '',
        contract_start:'',
        contract_end:'',
        members:[],
        description_es:'',
        description_en:'',
        short_description_es:'',
        short_description_en:'',
        types:[],
        methodologies:[],
        topics:[],
        publications:'',
        interviews:'',
        databases:'',
        trainings:'',
        study_cases:'',
        project:'',
        project_id:'',
        project_resumen : '',
        project_title : '',
        time_sheets : 0,
      })
    }),
    watch : {
      '$screen.width'() {
        if (this.$screen.width <= 768) {
          this.style_responsive = 'padding: 2px 0px 2px 0px;';
        }else {
          this.style_responsive = '';
        }
      },

      project_resumen(){
        if (this.project_resumen.resumen_es.length > 450) {
          toast.fire({
            type: 'warning',
            title: 'No se puede exeder de 450 caracteres'
          });
        }
      },


    },
    methods:{

      uploadCroppedImage() {
       this.myCroppa.generateBlob((blob) => {
         // write code to upload the cropped image file (a file is a blob)
       }, 'image/jpeg', 0.8) // 80% compressed jpeg file
     },

      getTematics() {
        if(this.project.company != '' ) {
          if (this.project.company.id != this.company_initial) {
            if (this.tematics_web_temporal == '') {
              this.tematics_web_temporal = this.project.tematics_web;
            }
            this.project.tematics_web = '';
          }else {
            if (this.tematics_web_temporal != '') {
             this.project.tematics_web = this.tematics_web_temporal;
            }
          }
          this.tematics_web = [];
          if (this.project.company.id == 1) {
            this.tematics_web_complete.forEach((item, i) => {
              if (item.company_id == 1) {
                this.tematics_web.push(item);
              }
            });
          }
          if (this.project.company.id != 1) {
            this.tematics_web_complete.forEach((item, i) => {
              if (item.company_id != 1) {
                this.tematics_web.push(item);
              }
            });
          }
        }
      },

      deleteImageSingle(){
        // axios.get('/api/delete-image-single/' + this.$route.params.id).then(response => {
          this.image_single = {'name' : '', 'path' : ''};
        // }).catch(e => {
        //   console.error(e);
        // })
      },

      deleteSub(i){
        (this.data_web.subtitles).splice(i, 1);
      },

      deleteFills(i){
        swal.fire({
          title: 'Desea eliminar la recurso ?',
          showCancelButton: true,
          confirmButtonText: `Eliminar`,
          confirmButtonColor : '#dd6b55',
        }).then((result) => {
          if (result.isConfirmed) {
            (this.data_web.files_n).splice(i, 1);
          }
        });
      },

      addSub(){
        (this.data_web.subtitles).push({
          'name_seccion_es' : '',
          'name_seccion_en' : '',
          'subtitle_es' : '',
          'subtitle_en' : ''
        });
      },

      onFileWChange(e) {
        console.log(e);
          this.data_w.file = e.target.files[0];

          // let formData = new FormData();
          // formData.append('file', this.publications_edit.file);
      },

      onFileWIChange(e) {
        // console.log(e);
        toast.fire({
          type: 'warning',
          title: 'Subir imagenes con escala 2:3'
        });

          this.data_w.file_i = e.target.files[0];
          this.color_btn = 'color :#063772 !important;';


          // let formData = new FormData();
          // formData.append('file', this.publications_edit.file);
      },

      addFileW(){

        var url = 'https://fundacionidea.org.mx/api/webcontent';
        // var url = 'http://127.0.0.1:8000/api/webcontent';
        // if (this.data_w.file === '') {
        //   toast.fire({
        //     type: 'warning',
        //     title: `Seleccione un archivo`
        //   });
        // }else {
          let formData = new FormData();
          formData.append('file', this.data_w.file);
          formData.append('file_i', this.data_w.file_i);
          formData.append('id', this.$route.params.id);

          if (this.project.company.id == 1) {
            if (this.data_web.public_state == true) {
              this.edit_resources = true;
              axios.post( url + '/update-file-web-pgc',formData).then(response => {
                this.edit_resources = false;
              }).catch(e => {
                this.edit_resources = false;
              });
            }
          }

          axios.post(`/api/save-file-web-publications`, formData).then(response => {
          if (response.status) {
            if (response.data.type == 1) {
              (this.data_web.files_n).push(
                {
                  'name' : response.data.name,
                  'path' : response.data.path
                }
              );
              this.data_w.file = '';
              $("#files_web_id").val('');
            }else if (response.data.type == 2) {
              let me = this;
              me.image_single =
                {
                  'name' : response.data.name,
                  'path' : response.data.path
                };
              this.data_w.file_i = '';
              $("#files_web_i_id").val('');
            }
            this.color_btn = 'color :#686e76 !important;';

          }

        });


        // }
      },

      // Funciones para sección "PUBLICACIONES"
      editPublications(data){
        this.publications_edit.id = data.id;
        this.publications_edit.name = data.name;
        this.publications_edit.description = data.description;
        this.publications_edit.link = data.link;
        this.publications_edit.file = data.file;
      },

      deletePublication(id){
        this.$Progress.start();
        swal.fire({
          title: 'Desea eliminar la publicación ?',
          showCancelButton: true,
          confirmButtonText: `Eliminar`,
          confirmButtonColor : '#dd6b55',
        }).then((result) => {
          if (result.isConfirmed) {
            axios.get('/api/delete-publications/' + id + '&' + this.$route.params.id).then(response => {
              this.publications = response.data;
            }).catch(e => {
              console.error(e);
            });
          }
          this.$Progress.finish();
        });
      },

      cancelPublication(){
        this.publications_edit.id  = 0;
        this.publications_edit.name  = '';
        this.publications_edit.description  = '';
        this.publications_edit.link  = '';
        this.publications_edit.file  = '';
      },

      saveEditPublications(type){

        const config = {
          headers: { 'content-type': 'multipart/form-data' }
        }
        let formData = new FormData();
        formData.append('id', this.publications_edit.id);
        formData.append('file', this.publications_edit.file);
        formData.append('name', this.publications_edit.name);
        formData.append('description', this.publications_edit.description);
        formData.append('link', this.publications_edit.link);
        formData.append('project_id', this.$route.params.id);
        formData.append('type', type);

        axios.post(`/api/save-edit-publications`, formData, config).then(response => {
          toast.fire({
            type: 'success',
            title: 'Correcto'
          });
          this.publications = response.data;
          this.cancelPublication();
        }).catch(e => {
          toast.fire({
            type: 'error',
            title: 'Error al agregar documento'
          });
        });
      },

      onFileChange(e) {
        this.publications_edit.file = e.target.files[0];
      },

      downloadFilePublication(data){
        this.$Progress.start();
        axios({
          url: '/api/download-project-file-publication/' + data.id,
          method: 'GET',
          responseType: 'blob', // importante
        }).then((response) => {
          this.$Progress.finish();
          const url = window.URL.createObjectURL(new Blob([response.data]));
          const link = document.createElement('a');
          link.href = url;
          link.setAttribute('download', data.file); //archivo = nombre del archivo alojado en el ftp
          document.body.appendChild(link);
          link.click();
          //--Llama el metodo para borrar el archivo local una vez descargado--//
          axios.get('/api/clear-project-file-publication/' + data.id)
          .then(response => {
          })
          .catch(function (error) {
            console.log(error);
          });
          //--fin del metodo borrar--//
        }).catch(error => {
          console.error(error);
        });
      },

      clearFilePublication(){
        this.publications_edit.file  = '';
      },
      // Fin de funciones sección PUBLICACIONES

      projectConfiguration(){
        this.$router.push(`/manage/home`)
      },

      addStackeholder(){
        this.project_cliente_new = '';
        this.project_financiador_new = '';
        this.project_proveedor_new = '';
        $('#addNewCFP').modal('show');
      },

      onImageChange(e) {
        this.fileform.file = e.target.files[0];
      },

      newModal(){
        this.fileform.reset();
        $('#addNew').modal('show');
      },
      newModalFileDropbox(){
        $('#addNewFileDropbox').modal('show');
      },
      newModalMembers(){
        $('#addNewMembers').modal('show');
      },

      vaciarMemberExtern(){
        this.member_extern_new.id = 0;
        this.member_extern_new.name = '';
        this.member_extern_new.email = '';
        this.member_extern_new.phone = '';
        this.member_extern_new.position = '';
      },

      addMemberExtern(){
        this.vaciarMemberExtern();
        $('#addNewMembersExtern').modal('show');
      },

      GuardarColaboradorExterno(){
        this.$Progress.start();
        axios.post('/api/save-contact/',{
          stackeholder_id : null,
          project_id : this.$route.params.id,
          data : this.member_extern_new,
          type : 4,
        }).then(response => {
          toast.fire({
            type: 'success',
            title: `Agregado correctamente.`
          });
          this.vaciarMemberExtern();
          $('#addNewMembersExtern').modal('hide');
          this.getMembersExtern();
          this.$Progress.finish();
        }).catch(e => {
          console.error(e);
          toast.fire({
            type: 'error',
            title: `Error al guardar clientes.`
          });
          this.$Progress.finish();
        });
      },

      EditarColaboradorExterno(){
        this.$Progress.start();
        axios.post('/api/edit-contact/',{
          data : this.member_extern_new,
        }).then(response => {
          toast.fire({
            type: 'success',
            title: `Editado correctamente.`
          });
          this.vaciarMemberExtern();
          $('#addNewMembersExtern').modal('hide');
          this.getMembersExtern();
          this.$Progress.finish();
        }).catch(e => {
          console.error(e);
          toast.fire({
            type: 'error',
            title: `Error al editar.`
          });
          this.$Progress.finish();
        });
      },

      deleteColaboradorExterno(id){
        this.$Progress.start();
        swal.fire({
          title: 'Desea eliminar el contacto ?',
          showCancelButton: true,
          confirmButtonText: `Eliminar`,
          confirmButtonColor : '#dd6b55',
        }).then((result) => {
          if (result.isConfirmed) {
            axios.get('/api/delete-contact/' + id).then(response => {
              this.getMembersExtern();
              toast.fire({
                type: 'success',
                title: `Eliminado correctamente.`
              });
              this.$Progress.finish();
            }).catch(e => {
              toast.fire({
                type: 'error',
                title: `Error al guardar clientes.`
              });
              this.$Progress.finish();
            });
          }
        });
      },

      downloadFile(project_file){
        this.visible = true;
        axios({
          url: `/api/download-project-file/${project_file.id}`,
          method: 'GET',
          responseType: 'blob', // important
        }).then((response) => {
          const url = window.URL.createObjectURL(new Blob([response.data]));
          const link = document.createElement('a');
          link.href = url;
          link.setAttribute('download', project_file.file);
          document.body.appendChild(link);
          link.click();
          this.visible = false;
        }).catch((e) => {
          this.visible = false;
        });
      },
      addDocument(e){
        const config = {
          headers: { 'content-type': 'multipart/form-data' }
        }

        let formData = new FormData();
        formData.append('name', this.fileform.name);
        formData.append('file', this.fileform.file);
        formData.append('project_id', this.project.id);

        this.$Progress.start();

        axios.post(`/api/project-file`, formData, config)
        .then(() => {
          Fire.$emit('AfterCreate');
          $('#addNew').modal('hide')
          toast.fire({
            type: 'success',
            title: 'Documento creado con éxito'
          })
          this.$Progress.finish();
          this.fileform.reset();
        })
        .catch((e)=>{
          console.log(e);

          toast.fire({
            type: 'error',
            title: 'Error al agregar documento'
          })
          this.$Progress.fail();
        })
      },

      addDocumentDropbox(){
        this.$Progress.start();

        axios.post('/api/project-file-dropbox',{
          project_id : this.project.id,
          name : this.fileform.name,
          type : 'Dropbox',
          file : this.fileform.file,
        }).then(response => {
          this.$Progress.finish();
          this.fileform.reset();
          Fire.$emit('AfterCreate');
          $('#addNewFileDropbox').modal('hide');
          toast.fire({
            type: 'success',
            title: 'Documento creado con éxito'
          });
        }).catch(e => {
          this.$Progress.fail();
          console.error(e);
        });
      },

      deleteFile(file){
        this.$Progress.start();
        swal.fire({
          title: '¿Estás seguro?',
          text: "¡No podrás revertir esto!",
          type: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Sí, ¡bórralo!',
          cancelButtonText: 'Cancelar'
        }).then((result) => {
          if (result.value) {
            this.fileform.delete(`/api/project-file/${file.id}`)
            .then(()=>{
              this.$Progress.finish();
              Fire.$emit('AfterCreate');
              swal.fire(
                '¡Eliminado!',
                'El archivo ha sido eliminado.',
                'success'
              )
            }).catch(()=> {
              this.$Progress.fail();
              swal.fire(
                'Error',
                'Algo salió mal',
                'warning'
              )
            });
          }
        })
      },

      selectPrivacyOption(privacy_option){
        let index = this.project.privacy_options.findIndex(option => option.id == privacy_option.id);
        (index == -1) ? this.project.privacy_options.push(privacy_option) : this.project.privacy_options.splice(index, 1);
      },

      selectSatisfactionDocument(satisfaction_documents){
        let index = this.project.satisfaction_documents.findIndex(option => option.id == satisfaction_documents.id);
        (index == -1) ? this.project.satisfaction_documents.push(satisfaction_documents) : this.project.satisfaction_documents.splice(index, 1);
      },

      activeSelect(){
        this.edit = true;
        this.container_class = 'container container-border-edit';
      },

      activeSelectNew(){
        this.nuevo = true;
        this.container_class = 'container container-border-edit';
      },

      cancelar(){
        if (this.nuevo == true) {
          this.$router.push(`/projects`)
        }
        this.edit = false;
        this.container_class = 'container';
        this.nuevo = false;
      },

      goToUser(member){
        this.$router.push(`/staff/${member.id}`)
      },

      toSection(num){
        this.active_section = num;
      },

      webChanges() {
        axios.post('/api/data-web-store-file',{
          image_single : this.image_single,
          id : this.project.id
        }).then(response => {
          console.log('correcto');
        });
      },

      updateProject(){
        // console.log(this.project);
        this.loading = true;
        this.project.company_id = this.project.company.id;
        this.project.location_id = this.project.locations == null ? '' : this.project.locations.id;
        this.project.contract_id = this.project.contract == null ?  '' : this.project.contract.id;
        this.project.currency_id = this.project.currency == null ? '' : this.project.currency.id;
        this.project.project_resumen = this.project_resumen;
        this.project.project_title = this.project_title;
      



        let me = this;
        axios.post('/api/project-aditional',{
          id : this.project.id,
          rol : this.project.rol,
          consortiums : me.consortiums,
          document_type_names : this.document_type_names,
        }).then(response => {
          console.log('correcto');
        });

        axios.post('/api/project-codes-save',{
          codigo : this.project.code,
          organizacion : this.project.company.name,
          timeworks : this.timeworks.principal,
          categoria : this.timeworks.categoria,
          oh : this.timeworks.oh,
          timesheets : this.timeworks.timesheets,
          project_id : this.project.id,
        }).then(response => {
        });

        this.form.put(`/api/project/${this.project.id}`,{
          data : this.project,
        }).then(() => {
          this.$router.push({ name: 'project', params: { id: this.project.id, s_active: this.active_section } });
          toast.fire({
            type: 'success',
            title: `Proyecto ${this.project.code} actualizado correctamente.`
          })
          this.loading = false;
          this.cancelar();
        }).catch(() =>{
          this.loading = false;
          toast.fire({
            type: 'error',
            title: `Error al actualizar proyecto.`
          });
          this.cancelar();
        });



        axios.post('/api/data-web-store',{
          data_web : this.data_web,
          id : this.project.id
        }).then(response => {
          console.log('correcto');
        });

        var url = 'https://fundacionidea.org.mx/api/webcontent';
        // var url = 'http://127.0.0.1:8000/api/webcontent';

        if (this.project.company.id == 1) {
          if (this.data_web.public_state == true) {
            var post_tags = [];
            var files_n = [];

            (this.project.tematics_web).forEach((item, i) => {
              post_tags.push(item.name);
            });

            (this.data_web.files_n).forEach((item, i) => {
              files_n.push(item.name);
            });

            axios.post( url + '/update-data-web-idea',{
              'id' : this.$route.params.id,
              'name_es' : this.project.name_es,
              'title_es' : this.project.project_title.title_es,
              'description_es' : this.data_web.description_es,
              'public' : this.data_web.type,
              'publication_date' : this.data_web.publication_date,
              'tags' :  post_tags,
              'files' : files_n,
            }),then(response => {
              console.log('correcto wp');
            });
          }
        }

        axios.post('/api/data-web-store-file',{
          image_single : this.image_single,
          id : this.project.id
        }).then(response => {
        });


      },
      vaciar(e){
        if (e.target.checked == false) {
          this.timeworks.principal = false;
          this.timeworks.categoria = '';
          this.timeworks.oh = false;
        }
      },
      GuardarMember(){

        this.isActiveTeam = true;
        this.textLoadingTeam = 'Guardando';
        axios.post('/api/save-members',{
          project_id : this.$route.params.id,
          user_id : this.member_project.id,
          project_manager : this.project_manager,
          // data : this.listusersproject,
        }).then(response => {
          this.isActiveTeam = false;
          if (response.data.warning) {
            toast.fire({
              type: 'warning',
              title: response.data.msn
            });
            this.listusersproject = [];
          }else {
            toast.fire({
              type: 'success',
              title: `Agregado correctamente.`
            });
            this.listusersproject = [];
            $('#addNewMembers').modal('hide');
            this.getMembers(this.$route.params.id);
            this.member_project = {
              id : '',
              name : '',
            };
            this.project_manager = 0;
          }
        }).catch(e => {
          this.isActiveTeam = false;
          toast.fire({
            type: 'error',
            title: `Error al guardar colaboradores.`
          });
          this.$Progress.finish();
        });
      },

      deleteMember(id){
        swal.fire({
          title: 'Desea eliminar el colaborador ?',
          showCancelButton: true,
          confirmButtonText: `Eliminar`,
          confirmButtonColor : '#dd6b55',
        }).then((result) => {
          if (result) {
            this.isActiveTeam = true;
            this.textLoadingTeam = 'Eliminado';
            axios.post('/api/delete-members',{
              project_id : this.$route.params.id,
              user_id : id,
            }).then(response => {
              toast.fire({
                type: 'success',
                title: `Eliminado correctamente.`
              });
              this.getMembers(this.$route.params.id);
              this.isActiveTeam = false;
            }).catch(e => {
              this.isActiveTeam = false;
              toast.fire({
                type: 'error',
                title: `Error al eliminar.`
              });
              this.$Progress.finish();
            });
          }
        });
      },

      GuardarCFP(){
        this.$Progress.start();
        var url = '';
        this.title_button === 'cliente' ? url = '/api/save-client' :
        this.title_button === 'financiador' ?  url = '/api/save-funder' :
        this.title_button === 'proveedor' ? url = '/api/save-supplier' :
        this.title_button === 'consorcio' ? url = '/api/save-consortions' : '';

        axios.post(url,{
          project_id : this.$route.params.id,
          data : this.project_cliente_new,
        }).then(response => {
          toast.fire({
            type: 'success',
            title: `Agregado correctamente.`
          });
          $('#addNewCFP').modal('hide');
          this.project_cliente_new = '';
          this.getCFP();
          this.$Progress.finish();
        }).catch(e => {
          toast.fire({
            type: 'error',
            title: `Error al guardar.`
          });
          this.$Progress.finish();
        });

      },

      deleteClient(id){
        this.$Progress.start();
        swal.fire({
          title: 'Desea eliminar cliente ?',
          showCancelButton: true,
          confirmButtonText: `Eliminar`,
          confirmButtonColor : '#dd6b55',
        }).then((result) => {
          if (result.isConfirmed) {
            axios.get('/api/delete-client/' + id).then(response => {
              this.getClients(this.$route.params.id);
              toast.fire({
                type: 'success',
                title: `Eliminado correctamente.`
              });
              this.$Progress.finish();
            }).catch(e => {
              toast.fire({
                type: 'error',
                title: `Error al guardar clientes.`
              });
              this.$Progress.finish();
            });
          }
        });
      },

      deleteFunder(id){
        this.$Progress.start();
        swal.fire({
          title: 'Desea eliminar financiador ?',
          showCancelButton: true,
          confirmButtonText: `Eliminar`,
          confirmButtonColor : '#dd6b55',
        }).then((result) => {
          if (result.isConfirmed) {
            axios.get('/api/delete-funder/' + id).then(response => {
              this.getFunders(this.$route.params.id);
              toast.fire({
                type: 'success',
                title: `Eliminado correctamente.`
              });
              this.$Progress.finish();
            }).catch(e => {
              toast.fire({
                type: 'error',
                title: `Error al eliminar.`
              });
              this.$Progress.finish();
            });
          }
        });
      },

      deleteSupplier(id){
        this.$Progress.start();
        swal.fire({
          title: 'Desea eliminar proveedor ?',
          showCancelButton: true,
          confirmButtonText: `Eliminar`,
          confirmButtonColor : '#dd6b55',
        }).then((result) => {
          if (result.isConfirmed) {
            axios.get('/api/delete-supplier/' + id).then(response => {
              this.getSuppliers(this.$route.params.id);
              toast.fire({
                type: 'success',
                title: `Eliminado correctamente.`
              });
              this.$Progress.finish();
            }).catch(e => {
              toast.fire({
                type: 'error',
                title: `Error al eliminar.`
              });
              this.$Progress.finish();
            });
          }
        });
      },

      deleteConsortion(id){
        this.$Progress.start();
        swal.fire({
          title: 'Desea eliminar consorcio ?',
          showCancelButton: true,
          confirmButtonText: `Eliminar`,
          confirmButtonColor : '#dd6b55',
        }).then((result) => {
          if (result.isConfirmed) {
            axios.get('/api/delete-consortions/' + id).then(response => {
              this.getConsortions(this.$route.params.id);
              toast.fire({
                type: 'success',
                title: `Eliminado correctamente.`
              });
              this.$Progress.finish();
            }).catch(e => {
              toast.fire({
                type: 'error',
                title: `Error al eliminar.`
              });
              this.$Progress.finish();
            });
          }
        });
      },

      getCFP(){
        this.title_button === 'cliente' ? this.getClients(this.$route.params.id) :
        this.title_button === 'financiador' ? this.getFunders(this.$route.params.id) :
        this.title_button === 'proveedor' ?  this.getSuppliers(this.$route.params.id) :
        this.title_button === 'consorcio' ?  this.getConsortions(this.$route.params.id) : 0;
      },

      deleteContact(id){
        this.$Progress.start();
        swal.fire({
          title: 'Desea eliminar el contacto ?',
          showCancelButton: true,
          confirmButtonText: `Eliminar`,
          confirmButtonColor : '#dd6b55',
        }).then((result) => {
          if (result.isConfirmed) {
            axios.get('/api/delete-contact/' + id).then(response => {
              this.getCFP();
              toast.fire({
                type: 'success',
                title: `Eliminado correctamente.`
              });
              this.$Progress.finish();
            }).catch(e => {
              toast.fire({
                type: 'error',
                title: `Error al guardar clientes.`
              });
              this.$Progress.finish();
            });
          }
        });
      },

      vaciarContact(){
        this.contact_new.id = 0;
        this.contact_new.name = '';
        this.contact_new.email = '';
        this.contact_new.phone = '';
        this.contact_new.position = '';
      },

      addContact(id){
        this.vaciarContact();
        $('#addNewContact').modal('show');
        this.stackeholder_id = id;
      },

      GuardarContacto(){
        this.$Progress.start();
        axios.post('/api/save-contact/',{
          stackeholder_id : this.stackeholder_id,
          project_id : this.$route.params.id,
          data : this.contact_new,
          type : (this.title_button === 'cliente' ? 1 : this.title_button === 'financiador' ? 2 : this.title_button === 'proveedor' ? 3 : this.title_button === 'consorcio' ? 5 : 0),
        }).then(response => {
          toast.fire({
            type: 'success',
            title: `Agregado correctamente.`
          });
          this.vaciarContact();
          $('#addNewContact').modal('hide');
          this.getCFP();
          this.$Progress.finish();
        }).catch(e => {
          console.error(e);
          toast.fire({
            type: 'error',
            title: `Error al guardar clientes.`
          });
          this.$Progress.finish();
        });
      },

      EditarContacto(){
        this.$Progress.start();
        axios.post('/api/edit-contact/',{
          data : this.contact_new,
        }).then(response => {
          toast.fire({
            type: 'success',
            title: `Editado correctamente.`
          });
          this.vaciarContact();
          $('#addNewContact').modal('hide');
          this.getCFP();
          this.$Progress.finish();
        }).catch(e => {
          console.error(e);
          toast.fire({
            type: 'error',
            title: `Error al editar.`
          });
          this.$Progress.finish();
        });
      },

      editContact(data){
        this.contact_new.id = data.id;
        this.contact_new.name = data.name;
        this.contact_new.email = data.email;
        this.contact_new.position = data.position;
        this.contact_new.phone = data.phone;
        $('#addNewContact').modal('show');
      },

      editContactExtern(data){
        this.member_extern_new.id = data.id;
        this.member_extern_new.name = data.name;
        this.member_extern_new.email = data.email;
        this.member_extern_new.position = data.position;
        this.member_extern_new.phone = data.phone;
        $('#addNewMembersExtern').modal('show');
      },

      async getProject(){
        if (this.$route.params.id != 0) {
          this.loading = true;
          this.getPrivacyOptions();
          this.getSatisfactionDocuments();
          const query = await axios.get(`/api/project/${this.$route.params.id}`);
          this.getMembers(this.$route.params.id);
          this.getMembersExtern();
          this.getClients(this.$route.params.id);
          this.getFunders(this.$route.params.id);
          this.getSuppliers(this.$route.params.id);
          this.getConsortions(this.$route.params.id);
          this.project = query.data;
          this.project.currency_iva = JSON.parse(query.data.currency_iva);
          this.data_web = JSON.parse(query.data.data_web);
          this.image_single = JSON.parse(query.data.file_web_single);
          this.locations = query.data.locations;
          this.company = query.data.company;
          this.company_initial = query.data.company.id;
          this.contract = query.data.contract;
          this.currency = query.data.currency;
          this.project_resumen = JSON.parse(query.data.project_resumen);
          this.project_title = JSON.parse(query.data.project_title);
          this.document_type_names = (query.data.project_document_names).length == 0 ? '' : JSON.parse(query.data.project_document_names[0].data);
          this.publications = query.data.publications;
          this.consortiums = query.data.consortiums.length == 0 ? {
            company : '',
            company_represent : '',
            actions : '',
            final_client : '',
          } : query.data.consortiums[0];
          if (query.data.project_codes.length == 1) {
            this.timeworks.principal = true;
            this.timeworks.categoria = query.data.project_codes[0].categoria;
          }else if (query.data.project_codes.length == 2) {
            this.timeworks.principal = true;
            this.timeworks.categoria = query.data.project_codes[0].categoria;
            this.timeworks.oh = true;
          }
          this.loading = false;
          var start = '';
          var end = '';
          if(query.data.contract_start != null){
            start = (query.data.contract_start).substring(0,4);
          }
          if(query.data.contract_end != null){
            end = (query.data.contract_end).substring(0,4);
          }
          this.years_input = start + '-' + end;
          this.state_view = 1;
        }else {
          this.state_view = 2;
          this.nuevo = true;
          this.activeSelectNew();
        }
      },
      getPrivacyOptions(){
        axios.get('/api/privacy-option')
        .then(
          ({data}) => (
            this.privacy_options = data
          )
        )
      },

      getSatisfactionDocuments(){
        axios.get('/api/satisfaction_document')
        .then(
          ({data}) => {
            this.satisfaction_documents = data
          }
        )
      },

      getMembers(id){
        this.MembersProject = [];
        axios.get('/api/get-members-project/' + id).then(response => {
          this.MembersProject = response.data;
        }).catch(e => {
          console.error(e);
        });
      },

      getMembersExtern(){
        this.member_extern = [];
        axios.get('/api/get-members-extern-project/' + this.$route.params.id).then(response => {
          this.member_extern = response.data;
        }).catch(e => {
          console.error(e);
        });
      },

      getClients(id){
        this.project_clients = [];
        axios.get('/api/get-clients-project/' + id).then(response => {
          this.project_clients = response.data;
        }).catch(e => {
          console.error(e);
        });
      },

      getFunders(id){
        this.project_funders = [];
        axios.get('/api/get-funders-project/' + id).then(response => {
          this.project_funders = response.data;
        }).catch(e => {
          console.error(e);
        });
      },

      getSuppliers(id){
        this.project_suppliers = [];
        axios.get('/api/get-suppliers-project/' + id).then(response => {
          this.project_suppliers = response.data;
        }).catch(e => {
          console.error(e);
        });
      },

      getConsortions(id){
        this.project_consortions = [];
        axios.get('/api/get-consortions-project/' + id).then(response => {
          this.project_consortions = response.data;
        }).catch(e => {
          console.error(e);
        });
      },

      getData(){
        if (this.$screen.width <= 768) {
          this.style_responsive = 'padding: 2px 0px 2px 0px;';
        }else {
          this.style_responsive = '';
        }

        this.listCompanies = [];
        this.listaStackeholders  = [];
        this.listLinkedsProjects = [];
        this.listLocations = [];
        this.listLinkedsProjects = [];
        this.listTypes = [];
        this.listMetodologies = [];
        this.listTopics = [];
        this.listCurrencys = [];
        this.listContracts = [];
        this.listUsers = [];
        this.tematics_web_complete = [];
        axios.get('/api/company/').then(response => {
          this.listCompanies = response.data
        });

        axios.get('/api/stackeholder/').then(response => {
          this.listaStackeholders = response.data;
        });

        axios.get('/api/project/').then(response => {
          this.listLinkedsProjects = response.data;
        });

        axios.get('/api/tematics/').then(response => {
          this.tematics_web_complete = response.data;
          this.getTematics();
        });

        axios.get('/api/location/').then(response => {
          this.listLocations = response.data;
        });

        axios.get('/api/contract/').then(response => {
          this.listContracts = response.data;
        });
        axios.get('/api/type/').then(response => {
          this.listTypes = response.data;
        });
        axios.get('/api/methodology/').then(response => {
          this.listMetodologies = response.data;
        });
        axios.get('/api/topic/').then(response => {
          this.listTopics = response.data;
        });
        axios.get('/api/currency/').then(response => {
          this.listCurrencys = response.data;
        });
        axios.get('/api/active-users').then(response => {
          this.listUsers = response.data;
        });
      },

      createProyecto(){
        if(!this.project.name_es){
          toast.fire({type: 'warning',title: 'Escriba el nombre de proyecto'});
        }else if (!this.project.company) {
          toast.fire({type: 'warning',title: 'Seleccione la empresa que realiza el proyecto'});
        }else if (!this.project.code) {
          toast.fire({type: 'warning',title: 'Escriba el código del proyecto'});
        }else if (!this.project.clients) {
          toast.fire({type: 'warning',title: 'Seleccione el(los) clientes'});
        }else {
          axios.post('/api/add-new-project',{
            name_es : this.project.name_es,
            company_id : this.project.company.id,
            code : this.project.code,
            clients : this.project.clients,
            linkeds : this.project.linkeds,
          }).then(response => {
            this.$route.params.id = response.data;
            this.getProject();
            this.$router.push({ name: 'project', params: { id: response.data, s_active: 1 } });
            this.nuevo = false;
            this.edit = true;
            toast.fire({type: 'success',title: 'Proyecto creado correctamente'});
          });
        }
      }
    },

    created(){
      if(this.$gate.isActive()) this.getProject();
      Fire.$on('AfterCreate',() => {
        if(this.$gate.isActive()) this.getProject();
      });
      if(this.$route.params.s_active) this.active_section = this.$route.params.s_active;
      this.getData();
    }
  }
  </script>

  <style lang="scss" scoped>
  .card-header{
    padding: 0.75rem 1.25rem;
    margin-bottom: 0;
    border-bottom: 0px;
  }
  .list-header{
    transition: all 0.2s ease-out !important;
    &:hover {
      background-color: #E8E8E8;
    }
  }
  .fa-2-5x{
    font-size: 2.5em;
  }
  .button-text{
    position: relative;
    bottom: 0.6em;
  }
  .page-link-left{
    border-top-left-radius: 0px !important;
    border-bottom-left-radius: 0px !important;
  }
  .page-link-right{
    border-top-right-radius: 0px !important;
    border-bottom-right-radius: 0px !important;
  }
  .page-link{
    box-shadow: none !important;
    border: 0px;
  }
  .font-answer {
    border: 1px solid #ced4da;
    font-size: 1.125rem;
    color: #495057;
    padding: 0.5rem 1rem;
  }
  .font-answer-list {
    border: 1px solid #ced4da;
    color: #495057;
    padding: 0.5rem 1rem;
  }
  .badge-circle {
    border-radius: 50%;
    width: 20px;
    height: 20px;
    padding: 7px;
    padding-top: 5px;
    padding-left: 6px;
    padding-right: 4px;
  }

  .project-description {
    max-height: 20em;
    overflow-y: auto;
  }


  .switch {
    position: relative;
    display: inline-block;
    width: 45px;
    height: 20px;
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
    height: 15px;
    width: 15px;
    left: 1px;
    bottom: 2px;
    background-color: white;
    transition: 0.4s;
  }

  input:checked + .slider {
    background-color: #FFA000;
  }

  input:focus + .slider {
    box-shadow: 0 0 1px #2196F3;
  }

  input:checked + .slider:before {
    -webkit-transform: translateX(26px);
    -ms-transform: translateX(26px);
    transform: translateX(26px);
  }

  /* Rounded sliders */
  .slider.round {
    border-radius: 12px;
  }

  .slider.round:before {
    border-radius: 50%;
  }


  /* disable */
  .switch-disable {
    position: relative;
    display: inline-block;
    width: 45px;
    height: 20px;
  }

  .switch-disable input {
    opacity: 0;
    width: 0;
    height: 0;
  }

  .slider-disable {
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

  .slider-disable:before {
    position: absolute;
    content: "";
    height: 15px;
    width: 15px;
    left: 1px;
    bottom: 2px;
    background-color: white;
    transition: 0.4s;
  }

  input:checked + .slider-disable {
    background-color: #BBBBBB;
  }

  input:focus + .slider-disable {
    box-shadow: 0 0 1px #2196F3;
  }

  input:checked + .slider-disable:before {
    -webkit-transform: translateX(26px);
    -ms-transform: translateX(26px);
    transform: translateX(26px);
  }

  /* Rounded sliders */
  .slider-disable.round {
    border-radius: 12px;
  }

  .slider-disable.round:before {
    border-radius: 50%;
  }

  .container-border-edit {
    border : 1px solid;
    border-radius: 20px;
    background: #E2E8F0;
    border-color: #063772;
  }

  @media only screen and (max-width: 677px) {
    .margin-profile {
      margin-left: 1px;
      margin-right: 1px;
      padding: 2px 1px 2px 1px;
    }
  }


  // .nav-tabs .nav-link.active {
  //   color: #495057 !important;
  // }
  </style>
