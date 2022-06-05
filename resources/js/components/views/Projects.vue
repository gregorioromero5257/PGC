<template>
    <ais-instant-search
        class="staff container py-2"
        :search-client="searchClient"
        index-name="projects"
        :stalled-search-delay="200"
    >
        <Loading v-if="loading" />
        <NoConnection v-if="isOffline" />
        <div class="row" v-if="isOnline && $gate.isActive() && !loading">
            <div class="col-md-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><router-link class="text-orange" to="/home">Inicio</router-link></li>
                        <li class="breadcrumb-item active" aria-current="page">Proyectos</li>
                    </ol>
                </nav>
            </div>
            <div class="col-md-12" v-if="$gate.activeUser().role_id != 5">
                <router-link
                    class="btn btn-outline-orange float-right font-weight-bold"
                    :to="{
                        name: 'project',
                        params: { id: 0, s_active: active_section }
                    }"
                >
                    <i class="fas fa-plus mr-1"></i>
                    Agregar proyecto
                </router-link>
            </div>
        </div>
        <div class="col-md-9">
          <ais-search-box>
              <div slot-scope="{ currentRefinement, isSearchStalled, refine }" class="form-group mb-0">
                  <div class="input-group-prepend main-search">
                      <span class="input-group-text search-icon" id="basic-addon1">
                          <i class="fas fa-search text-secondary"></i>
                      </span>
                      <input
                          class="form-control search-input"
                          type="text"
                          v-model="query"
                          @input="refine($event.currentTarget.value)"
                          placeholder="Buscar proyecto..."
                      >
                      <span class="input-group-text loading-icon" id="basic-addon1">
                          <i class="fas fa-spinner fa-pulse" :hidden="!isSearchStalled"></i>
                      </span>
                  </div>
              </div>
          </ais-search-box>
        </div>


        <div class="row mt-4">


            <div class="col-md-9">
              <!-- <div class="col-md-8">
                <ais-search-box>
                    <div slot-scope="{ currentRefinement, isSearchStalled, refine }" class="form-group mb-0">
                        <div class="input-group-prepend main-search">
                            <span class="input-group-text search-icon" id="basic-addon1">
                                <i class="fas fa-search text-secondary"></i>
                            </span>
                            <input
                                class="form-control search-input"
                                type="text"
                                v-model="query"
                                @input="refine($event.currentTarget.value)"
                                placeholder="Buscar proyecto..."
                            >
                            <span class="input-group-text loading-icon" id="basic-addon1">
                                <i class="fas fa-spinner fa-pulse" :hidden="!isSearchStalled"></i>
                            </span>
                        </div>
                    </div>
                </ais-search-box>
              </div>
              <div class="col-md-3">
                <router-link
                    class="btn btn-outline-orange float-right font-weight-bold"
                    :to="{
                        name: 'project',
                        params: { id: 0, s_active: active_section }
                    }"
                >
                    <i class="fas fa-plus mr-1"></i>
                    Agregar proyecto
                </router-link>
              </div> -->



                <ais-stats>
                    <p style="color: #495057; border-bottom:0.25rem solid #dee2e6;" class="mt-3 pb-3 h3" slot-scope="{ hitsPerPage, nbPages, nbHits }">
                        {{ nbHits }} proyectos encontrados
                    </p>
                </ais-stats>

                <ais-current-refinements class="col-md-12">
                    <div slot-scope="{ items }" class=mr-2>
                        <span v-for="item in items" :key="item.attribute">
                            <a class="ml-1 text-white pointer" style="text-decoration:none;" @click.prevent="item.refine(refinement)" data-toggle="tooltip" data-placement="top" :title="'Eliminar el filtro ' + refinement.value" v-for="refinement in item.refinements" :key="[refinement.attribute,refinement.type,refinement.value,refinement.operator].join(':')">
                                <span class="badge badge-pill badge-secondary p-2 mt-2 mr-2">
                                    {{ refinement.value }}  <i class="fas fa-times-circle"></i>
                                </span>
                            </a>
                        </span>
                        <ais-clear-refinements class="d-inline-flex">
                            <div slot-scope="{ canRefine, refine, createURL }">
                                <a class="btn btn-link" :href="createURL()" @click.prevent="refine" v-if="canRefine">
                                    Borrar todos los filtros
                                </a>
                            </div>
                        </ais-clear-refinements>
                    </div>
                </ais-current-refinements>

                <ais-hits>
                    <div class="row mt-2 mb-4" slot-scope="{ items }" v-if="items.length > 0">
                        <div class="col-md-12">
                            <div class="media table-head">
                                <i
                                    @click.stop="toogleProjectsSelection"
                                    class="far p-2 mb-2 align-self-center pointer"
                                    :class="{
                                        'fa-square': allSelected == 0,
                                        'fa-check-square' : allSelected == 1,
                                        'fa-minus-square' : allSelected == 2,
                                    }"
                                    data-toggle="tooltip"
                                    data-placement="bottom"
                                    title="Seleccionar"
                                ></i>
                                <div class="media-body">
                                    <button class="btn btn-orange" @click.stop="downloadSelectedProjects"
                                    :class="{
                                        'd-none': allSelected == 0,
                                        'd-inline-block': allSelected == 1 || allSelected == 2
                                    }"
                                    >
                                      <i
                                          class="fas fa-download"
                                          data-toggle="tooltip"
                                          data-placement="bottom"
                                          title="Descargar proyectos seleccionados"
                                      ></i>&nbsp;Descargar selección
                                    </button>

                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="d-none d-md-flex align-md-items-center flex-orientation table-head font-weight-bold">
                                <div class="p-2 w-md-5"></div>
                                <div class="p-2 w-md-20">Código</div>
                                <div class="p-2 w-md-35">Nombre</div>
                                <div class="p-2 w-md-20">Clientes</div>
                                <div class="p-2 w-md-20 text-center-md">Año(s)</div>
                            </div>
                            <div
                                class="d-flex align-items-md-center flex-orientation table-row pointer"
                                @mouseover="optionsHover = item.objectID"
                                @mouseleave="optionsHover = ''"
                                v-for="item in items"
                                :key="item.objectID"
                                @click="goToProject(item)"
                                @contextmenu.prevent.stop="projectOptions(item)"
                            >
                                <div class="p-2 w-md-5 d-none d-md-flex" @click.stop="addToDownloadProjects(item)">
                                    <i
                                        class="far"
                                        :class="{
                                            'fa-check-square' : downloadProjects.includes(item.objectID) == true,
                                            'fa-square' : downloadProjects.includes(item.objectID) == false
                                        }"
                                    ></i>
                                </div>
                                <div class="p-2 w-md-20">
                                    <span class="font-weight-bold d-md-none">Código: </span> <ais-highlight attribute="code" :hit="item" />
                                </div>
                                <div class="p-2 w-md-35">
                                    <span class="font-weight-bold d-md-none">Nombre: </span> <ais-highlight attribute="name_es" :hit="item" />
                                </div>
                                <div class="p-2 w-md-20">
                                    <span class="font-weight-bold d-md-none">Clientes: </span>
                                    <span v-if="item.clients">
                                        <span
                                            v-for="(client, index) in item.clients"
                                            :key="index"
                                        >
                                            <ais-highlight
                                                :hit="item"
                                                :attribute="'clients.' + index"
                                            /><span v-if="index !== (item.clients.length -1)">, </span>
                                        </span>
                                    </span>
                                    <span v-else>N/A</span>
                                </div>
                                <div class="p-2 w-md-20 text-center-md">
                                    <span class="font-weight-bold d-md-none">
                                        Año(s):
                                    </span>
                                    <span v-if="item.start_year" :class="{ 'd-md-none' : item.objectID == optionsHover }">
                                        {{ item.start_year }}
                                        <span v-if="item.end_year && item.end_year > item.start_year" :class="{ 'd-md-none' : item.objectID == optionsHover }">
                                        - {{ item.end_year }}
                                        </span>
                                    </span>
                                    <span v-else>
                                        N/A
                                    </span>
                                </div>
                                <div class="p-3 text-right-md d-md-none text-center">
                                    <div class="btn-group w-100" role="group" aria-label="">
                                        <a class="btn pointer" @click.stop="openNewTab(item)" data-toggle="tooltip" data-placement="top" title="Abrir proyecto en nueva pestaña">
                                            <i class="fas fa-external-link-alt"></i>
                                        </a>
                                        <a v-if="$gate.activeUser().role_id == 1 || $gate.activeUser().role_id == 2 || $gate.activeUser().role_id == 3 || $gate.activeUser().id == item.user_id" @click.stop="editProject(item)" class="btn" data-toggle="tooltip" data-placement="top" title="Editar proyecto">
                                            <i class="fas fa-pencil-alt pointer"></i>
                                        </a>
                                        <button v-if="$gate.activeUser().role_id == 1 || $gate.activeUser().role_id == 2" type="button" class="btn" data-toggle="tooltip" data-placement="top" title="Eliminar proyecto" @click.stop="deleteProject(item)">
                                            <i class="far fa-trash-alt pointer"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="d-none project-options" :class="{ 'd-md-flex' : item.objectID == optionsHover }">
                                    <a class="text-dark mr-4 pointer" @click.stop="openNewTab(item)" data-toggle="tooltip" data-placement="top" title="Abrir proyecto en nueva pestaña">
                                        <i class="fas fa-external-link-alt"></i>
                                    </a>
                                    <a v-if="$gate.activeUser().position_id != 7" class="text-dark mr-4" @click.stop="editProject(item)">
                                        <i class="fas fa-pencil-alt pointer" data-toggle="tooltip" data-placement="top" title="Editar proyecto"></i>
                                    </a>
                                    <a v-if="$gate.activeUser().role_id == 1 || $gate.activeUser().role_id == 2" class="text-dark mr-3 pointer" @click.stop="deleteProject(item)">
                                        <i class="far fa-trash-alt pointer" data-toggle="tooltip" data-placement="top" title="Eliminar proyecto"></i>
                                    </a>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="text-center mt-4" v-else>
                        <p>
                            No hay resultados para: <q>{{query}}</q>
                        </p>
                        <svg id="a706cf1c-1654-439b-8fcf-310eb7aa0e00" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" width="40%" viewBox="0 0 1120.59226 777.91584"><title>not found</title><circle cx="212.59226" cy="103" r="64" fill="#ff6584"/><path d="M563.68016,404.16381c0,151.01141-89.77389,203.73895-200.51559,203.73895S162.649,555.17522,162.649,404.16381,363.16457,61.04208,363.16457,61.04208,563.68016,253.1524,563.68016,404.16381Z" transform="translate(-39.70387 -61.04208)" fill="#f2f2f2"/><polygon points="316.156 523.761 318.21 397.378 403.674 241.024 318.532 377.552 319.455 320.725 378.357 207.605 319.699 305.687 319.699 305.687 321.359 203.481 384.433 113.423 321.621 187.409 322.658 0 316.138 248.096 316.674 237.861 252.547 139.704 315.646 257.508 309.671 371.654 309.493 368.625 235.565 265.329 309.269 379.328 308.522 393.603 308.388 393.818 308.449 394.99 293.29 684.589 313.544 684.589 315.974 535.005 389.496 421.285 316.156 523.761" fill="#3f3d56"/><path d="M1160.29613,466.01367c0,123.61-73.4842,166.77-164.13156,166.77s-164.13156-43.16-164.13156-166.77S996.16457,185.15218,996.16457,185.15218,1160.29613,342.40364,1160.29613,466.01367Z" transform="translate(-39.70387 -61.04208)" fill="#f2f2f2"/><polygon points="950.482 552.833 952.162 449.383 1022.119 321.4 952.426 433.154 953.182 386.639 1001.396 294.044 953.382 374.329 953.382 374.329 954.741 290.669 1006.369 216.952 954.954 277.514 955.804 124.11 950.467 327.188 950.906 318.811 898.414 238.464 950.064 334.893 945.173 428.327 945.027 425.847 884.514 341.294 944.844 434.608 944.232 446.293 944.123 446.469 944.173 447.428 931.764 684.478 948.343 684.478 950.332 562.037 1010.514 468.952 950.482 552.833" fill="#3f3d56"/><ellipse cx="554.59226" cy="680.47903" rx="554.59226" ry="28.03433" fill="#3f3d56"/><ellipse cx="892.44491" cy="726.79663" rx="94.98858" ry="4.80162" fill="#3f3d56"/><ellipse cx="548.71959" cy="773.11422" rx="94.98858" ry="4.80162" fill="#3f3d56"/><ellipse cx="287.94432" cy="734.27887" rx="217.01436" ry="10.96996" fill="#3f3d56"/><circle cx="97.08375" cy="566.26982" r="79" fill="#2f2e41"/><rect x="99.80546" y="689.02332" width="24" height="43" transform="translate(-31.32451 -62.31008) rotate(0.67509)" fill="#2f2e41"/><rect x="147.80213" y="689.58887" width="24" height="43" transform="translate(-31.31452 -62.87555) rotate(0.67509)" fill="#2f2e41"/><ellipse cx="119.54569" cy="732.61606" rx="7.5" ry="20" transform="translate(-654.1319 782.47948) rotate(-89.32491)" fill="#2f2e41"/><ellipse cx="167.55414" cy="732.18168" rx="7.5" ry="20" transform="translate(-606.25475 830.05533) rotate(-89.32491)" fill="#2f2e41"/><circle cx="99.31925" cy="546.29477" r="27" fill="#fff"/><circle cx="99.31925" cy="546.29477" r="9" fill="#3f3d56"/><path d="M61.02588,552.94636c-6.04185-28.64075,14.68758-57.26483,46.30049-63.93367s62.13813,11.14292,68.18,39.78367-14.97834,38.93-46.59124,45.59886S67.06774,581.58712,61.02588,552.94636Z" transform="translate(-39.70387 -61.04208)" fill="#efb04e"/><path d="M257.29613,671.38411c0,55.07585-32.73985,74.3063-73.13,74.3063q-1.40351,0-2.80255-.0312c-1.87139-.04011-3.72494-.1292-5.55619-.254-36.45135-2.57979-64.77127-22.79937-64.77127-74.02113,0-53.00843,67.73872-119.89612,72.827-124.84633l.00892-.00889c.19608-.19159.29409-.28516.29409-.28516S257.29613,616.30827,257.29613,671.38411Z" transform="translate(-39.70387 -61.04208)" fill="#efb04e"/><path d="M181.50168,737.26482l26.747-37.37367-26.81386,41.4773-.07125,4.29076c-1.87139-.04011-3.72494-.1292-5.55619-.254l2.88282-55.10258-.0223-.42775.049-.0802.27179-5.20415-26.88076-41.5798,26.96539,37.67668.06244,1.105,2.17874-41.63324-23.0132-42.96551,23.29391,35.6583,2.26789-86.31419.00892-.294v.28516l-.37871,68.064,22.91079-26.98321-23.00435,32.84678-.60595,37.27566L204.18523,621.958l-21.4805,41.259-.33863,20.723,31.05561-49.79149-31.17146,57.023Z" transform="translate(-39.70387 -61.04208)" fill="#3f3d56"/><circle cx="712.48505" cy="565.41532" r="79" fill="#2f2e41"/><rect x="741.77716" y="691.82355" width="24" height="43" transform="translate(-215.99457 191.86399) rotate(-17.08345)" fill="#2f2e41"/><rect x="787.6593" y="677.72286" width="24" height="43" transform="matrix(0.95588, -0.29376, 0.29376, 0.95588, -209.82788, 204.72037)" fill="#2f2e41"/><ellipse cx="767.887" cy="732.00275" rx="20" ry="7.5" transform="translate(-220.8593 196.83312) rotate(-17.08345)" fill="#2f2e41"/><ellipse cx="813.47537" cy="716.94619" rx="20" ry="7.5" transform="translate(-214.42477 209.56103) rotate(-17.08345)" fill="#2f2e41"/><circle cx="708.52153" cy="545.71023" r="27" fill="#fff"/><circle cx="708.52153" cy="545.71023" r="9" fill="#3f3d56"/><path d="M657.35526,578.74316c-14.48957-25.43323-3.47841-59.016,24.59412-75.0092s62.57592-8.34055,77.06549,17.09268-2.39072,41.6435-30.46325,57.63671S671.84483,604.17639,657.35526,578.74316Z" transform="translate(-39.70387 -61.04208)" fill="#efb04e"/><path d="M611.29613,661.29875c0,50.55711-30.05368,68.20979-67.13,68.20979q-1.28835,0-2.57261-.02864c-1.71785-.03682-3.41933-.1186-5.10033-.23313-33.46068-2.36813-59.45707-20.92878-59.45707-67.948,0-48.65932,62.18106-110.05916,66.85186-114.60322l.00819-.00817c.18-.17587.27-.26177.27-.26177S611.29613,610.74164,611.29613,661.29875Z" transform="translate(-39.70387 -61.04208)" fill="#efb04e"/><path d="M541.72029,721.77424l24.55253-34.30732-24.6139,38.07426-.0654,3.93872c-1.71785-.03682-3.41933-.1186-5.10033-.23313l2.6463-50.58165-.02047-.39266.045-.07361.24949-4.77718-24.67531-38.16836,24.753,34.58547.05731,1.01433,2-38.21741-21.12507-39.44039L541.80616,625.928l2.08182-79.23247.00819-.26994v.26177l-.34764,62.47962,21.031-24.76934-21.11693,30.15184-.55624,34.21735,19.63634-32.839-19.71812,37.87389-.31085,19.0228,28.50763-45.70631-28.614,52.34448Z" transform="translate(-39.70387 -61.04208)" fill="#3f3d56"/><path d="M875.29613,682.38411c0,55.07585-32.73985,74.3063-73.13,74.3063q-1.4035,0-2.80255-.0312c-1.87139-.04011-3.72494-.1292-5.55619-.254-36.45135-2.57979-64.77127-22.79937-64.77127-74.02113,0-53.00843,67.73872-119.89612,72.827-124.84633l.00892-.00889c.19608-.19159.29409-.28516.29409-.28516S875.29613,627.30827,875.29613,682.38411Z" transform="translate(-39.70387 -61.04208)" fill="#efb04e"/><path d="M799.50168,748.26482l26.747-37.37367-26.81386,41.4773-.07125,4.29076c-1.87139-.04011-3.72494-.1292-5.55619-.254l2.88282-55.10258-.0223-.42775.049-.0802.27179-5.20415L770.108,654.01076l26.96539,37.67668.06244,1.105,2.17874-41.63324-23.0132-42.96551,23.29391,35.6583,2.26789-86.31419.00892-.294v.28516l-.37871,68.064,22.91079-26.98321-23.00435,32.84678-.606,37.27566L822.18523,632.958l-21.4805,41.259-.33863,20.723,31.05561-49.79149-31.17146,57.023Z" transform="translate(-39.70387 -61.04208)" fill="#3f3d56"/><ellipse cx="721.51694" cy="656.82212" rx="12.40027" ry="39.5" transform="translate(-220.83517 966.22323) rotate(-64.62574)" fill="#2f2e41"/><ellipse cx="112.51694" cy="651.82212" rx="12.40027" ry="39.5" transform="translate(-574.07936 452.71367) rotate(-68.15829)" fill="#2f2e41"/></svg>
                    </div>
                </ais-hits>

                <ais-pagination
                    class="text-center"
                    :class-names="{
                        'ais-Pagination-list': 'pagination justify-content-center',
                        'ais-Pagination-item': 'page-item',
                        'ais-Pagination-link': 'page-link',
                        'ais-Pagination-item--selected': 'active',
                    }"
                />
            </div>

            <div class="col-md-3 d-none d-md-block">
              <h4 style="color : #156CAB;"><b>Filtrar por:</b></h4>

<div class="center-block">
  <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">

  <div class="panel panel-default">
    <div class="panel-heading active" role="tab" id="headingOne">
      <p class="font-weight-bold mb-1">
        <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
          Organización
        </a>
      </p>
    </div>
    <div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
      <div class="panel-body">
        <ais-refinement-list attribute="company">
            <div slot-scope="{items,refine,createURL}">
                <!-- <p class="font-weight-bold mb-1">Oficinas</p> -->
                <ul style="list-style-type:none" class="pl-0">
                    <li v-for="item in items" :key="item.value">
                        <a class="text-dark" style="text-decoration:none;" :href="createURL(item)" @click.prevent="refine(item.value)">
                            <i class="far" :class="{ 'fa-check-square' : item.isRefined, 'fa-square' : !item.isRefined }"></i>
                            <ais-highlight attribute="item" :hit="item"/>
                            <span class="badge badge-pill badge-secondary">
                                {{ item.count.toLocaleString() }}
                            </span>
                        </a>
                    </li>
                </ul>
            </div>
        </ais-refinement-list>
      </div>
    </div>
  </div>
  <!-- <hr>

  <div class="panel panel-default">
    <div class="panel-heading" role="tab" id="headingTwo">
      <p class="font-weight-bold mb-1">
        <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
          Tiempo
        </a>
      </p>
    </div>
    <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
      <div class="panel-body">
      </div>
    </div>
  </div> -->
  <hr>

  <div class="panel panel-default">
    <div class="panel-heading" role="tab" id="headingThree">
      <p class="font-weight-bold mb-1">
        <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
          Clientes
        </a>
      </p>
    </div>
    <div id="collapseThree" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">
      <div class="panel-body">
        <ais-refinement-list
            class="mb-3"
            attribute="clients"
            searchable
            show-more
            :limit="0"
            :show-more-limit="50"
        >
            <div slot-scope="{items,isShowingMore,isFromSearch,canToggleShowMore,refine,createURL,toggleShowMore,searchForItems}">
                <!-- <p class="font-weight-bold mb-1">Clientes</p> -->
                <div class="input-group-prepend">
                    <span class="input-group-text search-icon" id="basic-addon1">
                        <i class="fas fa-search text-secondary"></i>
                    </span>
                    <input type="text" class="form-control search-input" placeholder="Buscar..." @input="searchForItems($event.currentTarget.value)">
                    <span class="input-group-text loading-icon" id="basic-addon1">

                    </span>
                </div>
                <ul style="list-style-type:none" class="pl-0 mt-2">
                    <li v-if="isFromSearch && !items.length">No hay resultados.</li>
                    <li v-for="item in items" :key="item.value">
                        <a class="text-dark" style="text-decoration:none;" :href="createURL(item)" @click.prevent="refine(item.value)">
                            <i class="far" :class="{ 'fa-check-square' : item.isRefined, 'fa-square' : !item.isRefined }"></i>
                            <ais-highlight attribute="item" :hit="item"/>
                            <span class="badge badge-pill badge-secondary">
                                {{ item.count.toLocaleString() }}
                            </span>
                        </a>
                    </li>
                </ul>
                <button class="btn btn-link" @click="toggleShowMore" :disabled="!canToggleShowMore">
                    <span v-if="!isShowingMore">Mostrar más <i class="fas fa-caret-down"></i></span>
                    <span v-else>Mostrar menos <i class="fas fa-caret-up"></i></span>
                </button>
            </div>
        </ais-refinement-list>
      </div>
    </div>
  </div>
  <hr>

  <div class="panel panel-default">
    <div class="panel-heading" role="tab" id="headingFour">
      <p class="font-weight-bold mb-1">
        <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
          Tipos de proyecto
        </a>
      </p>
    </div>
    <div id="collapseFour" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingFour">
      <div class="panel-body">
        <ais-refinement-list
            class="mb-3"
            attribute="project_type"
            searchable
            show-more
            :limit="0"
            :show-more-limit="50"
        >
            <div slot-scope="{items,isShowingMore,isFromSearch,canToggleShowMore,refine,createURL,toggleShowMore,searchForItems}">
                <!-- <p class="font-weight-bold mb-1">Tipos de proyecto</p> -->
                <div class="input-group-prepend">
                    <span class="input-group-text search-icon" id="basic-addon1">
                        <i class="fas fa-search text-secondary"></i>
                    </span>
                    <input type="text" class="form-control search-input" placeholder="Buscar..." @input="searchForItems($event.currentTarget.value)">
                    <span class="input-group-text loading-icon" id="basic-addon1">

                    </span>
                </div>
                <ul style="list-style-type:none" class="pl-0 mt-2">
                    <li v-if="isFromSearch && !items.length">No hay resultados.</li>
                    <li v-for="item in items" :key="item.value">
                        <a class="text-dark" style="text-decoration:none;" :href="createURL(item)" @click.prevent="refine(item.value)">
                            <i class="far" :class="{ 'fa-check-square' : item.isRefined, 'fa-square' : !item.isRefined }"></i>
                            <ais-highlight attribute="item" :hit="item"/>
                            <span class="badge badge-pill badge-secondary">
                                {{ item.count.toLocaleString() }}
                            </span>
                        </a>
                    </li>
                </ul>
                <button class="btn btn-link" @click="toggleShowMore" :disabled="!canToggleShowMore">
                    <span v-if="!isShowingMore">Mostrar más <i class="fas fa-caret-down"></i></span>
                    <span v-else>Mostrar menos <i class="fas fa-caret-up"></i></span>
                </button>
            </div>
        </ais-refinement-list>
      </div>
    </div>
  </div>
  <hr>

  <div class="panel panel-default">
    <div class="panel-heading" role="tab" id="headingFive">
      <p class="font-weight-bold mb-1">
        <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
          Análisis, metodologías e implementación
        </a>
      </p>
    </div>
    <div id="collapseFive" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingFive">
      <div class="panel-body">
        <ais-refinement-list
            class="mb-3"
            attribute="methodologies"
            searchable
            show-more
            :limit="0"
            :show-more-limit="50"
        >
            <div slot-scope="{items,isShowingMore,isFromSearch,canToggleShowMore,refine,createURL,toggleShowMore,searchForItems}">
                <!-- <p class="font-weight-bold mb-1">Análisis, metodologías e implementación</p> -->
                <div class="input-group-prepend">
                    <span class="input-group-text search-icon" id="basic-addon1">
                        <i class="fas fa-search text-secondary"></i>
                    </span>
                    <input type="text" class="form-control search-input" placeholder="Buscar..." @input="searchForItems($event.currentTarget.value)">
                    <span class="input-group-text loading-icon" id="basic-addon1">

                    </span>
                </div>
                <ul style="list-style-type:none" class="pl-0 mt-2">
                    <li v-if="isFromSearch && !items.length">No hay resultados.</li>
                    <li v-for="item in items" :key="item.value">
                        <a class="text-dark" style="text-decoration:none;" :href="createURL(item)" @click.prevent="refine(item.value)">
                            <i class="far" :class="{ 'fa-check-square' : item.isRefined, 'fa-square' : !item.isRefined }"></i>
                            <ais-highlight attribute="item" :hit="item"/>
                            <span class="badge badge-pill badge-secondary">
                                {{ item.count.toLocaleString() }}
                            </span>
                        </a>
                    </li>
                </ul>
                <button class="btn btn-link" @click="toggleShowMore" :disabled="!canToggleShowMore">
                    <span v-if="!isShowingMore">Mostrar más <i class="fas fa-caret-down"></i></span>
                    <span v-else>Mostrar menos <i class="fas fa-caret-up"></i></span>
                </button>
            </div>
        </ais-refinement-list>
      </div>
    </div>
  </div>
  <hr>

  <div class="panel panel-default">
    <div class="panel-heading" role="tab" id="headingSix">
      <p class="font-weight-bold mb-1">
        <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseSix" aria-expanded="false" aria-controls="collapseSix">
          Temáticas / Enfoque / Población objetivo
        </a>
      </p>
    </div>
    <div id="collapseSix" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingSix">
      <div class="panel-body">
        <ais-refinement-list
            class="mb-3"
            attribute="topic"
            searchable
            show-more
            :limit="0"
            :show-more-limit="50"
        >
            <div slot-scope="{items,isShowingMore,isFromSearch,canToggleShowMore,refine,createURL,toggleShowMore,searchForItems}">
                <!-- <p class="font-weight-bold mb-1">Temáticas / Enfoque / Población objetivo</p> -->
                <div class="input-group-prepend">
                    <span class="input-group-text search-icon" id="basic-addon1">
                        <i class="fas fa-search text-secondary"></i>
                    </span>
                    <input type="text" class="form-control search-input" placeholder="Buscar..." @input="searchForItems($event.currentTarget.value)">
                    <span class="input-group-text loading-icon" id="basic-addon1">

                    </span>
                </div>
                <ul style="list-style-type:none" class="pl-0 mt-2">
                    <li v-if="isFromSearch && !items.length">No hay resultados.</li>
                    <li v-for="item in items" :key="item.value">
                        <a class="text-dark" style="text-decoration:none;" :href="createURL(item)" @click.prevent="refine(item.value)">
                            <i class="far" :class="{ 'fa-check-square' : item.isRefined, 'fa-square' : !item.isRefined }"></i>
                            <ais-highlight attribute="item" :hit="item"/>
                            <span class="badge badge-pill badge-secondary">
                                {{ item.count.toLocaleString() }}
                            </span>
                        </a>
                    </li>
                </ul>
                <button class="btn btn-link" @click="toggleShowMore" :disabled="!canToggleShowMore">
                    <span v-if="!isShowingMore">Mostrar más <i class="fas fa-caret-down"></i></span>
                    <span v-else>Mostrar menos <i class="fas fa-caret-up"></i></span>
                </button>
            </div>
        </ais-refinement-list>
      </div>
    </div>
  </div>
  <hr>

  <div class="panel panel-default">
    <div class="panel-heading" role="tab" id="headingSeven">
      <p class="font-weight-bold mb-1">
        <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseSeven" aria-expanded="false" aria-controls="collapseSeven">
          Colaboradores
        </a>
      </p>
    </div>
    <div id="collapseSeven" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingSeven">
      <div class="panel-body">
        <ais-refinement-list
            class="mb-3"
            attribute="members"
            searchable
            show-more
            :limit="0"
            :show-more-limit="50"
        >
            <div slot-scope="{items,isShowingMore,isFromSearch,canToggleShowMore,refine,createURL,toggleShowMore,searchForItems}">
                <!-- <p class="font-weight-bold mb-1">Colaboradores</p> -->
                <div class="input-group-prepend">
                    <span class="input-group-text search-icon" id="basic-addon1">
                        <i class="fas fa-search text-secondary"></i>
                    </span>
                    <input type="text" class="form-control search-input" placeholder="Buscar..." @input="searchForItems($event.currentTarget.value)">
                    <span class="input-group-text loading-icon" id="basic-addon1">

                    </span>
                </div>
                <ul style="list-style-type:none" class="pl-0 mt-2">
                    <li v-if="isFromSearch && !items.length">No hay resultados.</li>
                    <li v-for="item in items" :key="item.value">
                        <a class="text-dark" style="text-decoration:none;" :href="createURL(item)" @click.prevent="refine(item.value)">
                            <i class="far" :class="{ 'fa-check-square' : item.isRefined, 'fa-square' : !item.isRefined }"></i>
                            <ais-highlight attribute="item" :hit="item"/>
                            <span class="badge badge-pill badge-secondary">
                                {{ item.count.toLocaleString() }}
                            </span>
                        </a>
                    </li>
                </ul>
                <button class="btn btn-link" @click="toggleShowMore" :disabled="!canToggleShowMore">
                    <span v-if="!isShowingMore">Mostrar más <i class="fas fa-caret-down"></i></span>
                    <span v-else>Mostrar menos <i class="fas fa-caret-up"></i></span>
                </button>
            </div>
        </ais-refinement-list>
      </div>
    </div>
  </div>
  <hr>

  <div class="panel panel-default">
    <div class="panel-heading" role="tab" id="headingEight">
      <p class="font-weight-bold mb-1">
        <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseEight" aria-expanded="false" aria-controls="collapseEight">
          Ubicaciones geográficas
        </a>
      </p>
    </div>
    <div id="collapseEight" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingEight">
      <div class="panel-body">
        <ais-refinement-list
            class="mb-3"
            attribute="location"
            searchable
            show-more
            :limit="0"
            :show-more-limit="50"
        >
            <div slot-scope="{items,isShowingMore,isFromSearch,canToggleShowMore,refine,createURL,toggleShowMore,searchForItems}">
                <!-- <p class="font-weight-bold mb-1">Ubicaciones geográficas</p> -->
                <div class="input-group-prepend">
                    <span class="input-group-text search-icon" id="basic-addon1">
                        <i class="fas fa-search text-secondary"></i>
                    </span>
                    <input type="text" class="form-control search-input" placeholder="Buscar..." @input="searchForItems($event.currentTarget.value)">
                    <span class="input-group-text loading-icon" id="basic-addon1">

                    </span>
                </div>
                <ul style="list-style-type:none" class="pl-0 mt-2">
                    <li v-if="isFromSearch && !items.length">No hay resultados.</li>
                    <li v-for="item in items" :key="item.value">
                        <a class="text-dark" style="text-decoration:none;" :href="createURL(item)" @click.prevent="refine(item.value)">
                            <i class="far" :class="{ 'fa-check-square' : item.isRefined, 'fa-square' : !item.isRefined }"></i>
                            <ais-highlight attribute="item" :hit="item"/>
                            <span class="badge badge-pill badge-secondary">
                                {{ item.count.toLocaleString() }}
                            </span>
                        </a>
                    </li>
                </ul>
                <button class="btn btn-link" @click="toggleShowMore" :disabled="!canToggleShowMore">
                    <span v-if="!isShowingMore">Mostrar más <i class="fas fa-caret-down"></i></span>
                    <span v-else>Mostrar menos <i class="fas fa-caret-up"></i></span>
                </button>
            </div>
        </ais-refinement-list>
      </div>
    </div>
  </div>
  <hr>


</div>
</div>




                <!-- <ais-range-input attribute="start_year">
                    <div class="form-row mb-4" slot-scope="{ currentRefinement, range, canRefine, refine, }">
                        <div class="col-12">
                          <p>kkf</p>
                            <p class="font-weight-bold mb-2">Rango de años</p>
                        </div>
                        <div class="input-group-prepend col-6">
                            <span class="input-group-text search-icon" id="basic-addon1">
                                <i class="far fa-calendar-alt"></i>
                            </span>
                            <input
                                type="number"
                                class="form-control search-input-filter"
                                :min="range.min"
                                :max="range.max"
                                :placeholder="range.min"
                                :disabled="!canRefine"
                                :value="formatMinValue(currentRefinement.min, range.min)"
                                @input="refine({
                                min:$event.currentTarget.value,
                                max: formatMaxValue(currentRefinement.max, range.max),
                                })"
                            >
                        </div>
                        <div class="input-group-prepend col-6">
                            <span class="input-group-text search-icon" id="basic-addon1">
                                <i class="far fa-calendar-alt"></i>
                            </span>
                            <input
                                type="number"
                                class="form-control search-input-filter"
                                :min="range.min"
                                :max="range.max"
                                :placeholder="range.max"
                                :disabled="!canRefine"
                                :value="formatMaxValue(currentRefinement.max,range.max)"
                                @input="refine({
                                min:formatMinValue(currentRefinement.min, range.min),
                                max: $event.currentTarget.value,
                                })"
                            >
                        </div>
                    </div>
                </ais-range-input> -->












            </div>

        </div>
        <NoActive v-if="!loading && !$gate.isActive()" />
    </ais-instant-search>
</template>

<script>
    import VueSlider from 'vue-slider-component';
    import 'vue-slider-component/theme/default.css'
    import algoliasearch from 'algoliasearch/lite';
    import NoActive from '../complements/NoActive'
    import NoConnection from '../complements/NoConnection'
    import Loading from '../complements/Loading'
    export default {
        components: {
            NoConnection, NoActive, VueSlider, Loading
        },
        data: () => ({
            active_section: 1,
            optionsHover:'',
            query:'',
            loading: false,
            term:'',
            projects: [],
            searchResults: [],
            options: {
                verbose: true,
                shouldSort: true,
                includeMatches: true,
                threshold: 0.5,
                location: 0,
                distance: 500,
                maxPatternLength: 32,
                minMatchCharLength: 1,
                keys: ['code', 'name_es', 'name_en', 'contract_name'],
            },
            searchClient: algoliasearch(
                algolia_appid,
                algolia_apikey
            ),
            range: {
                min: 0,
                max: 10000000,
                interval: 1
            },
            downloadProjects:[],
            allSelected: 0,
        }),
        methods: {
            openNewTab(project){
                //let routeData = this.$router.push({ name: 'project', params: { id: project.objectID, s_active: this.active_section }  })
                window.open(`/projects/${project.objectID}`, '_blank');
            },
            toogleProjectsSelection(){
                this.downloadProjects = [];
                if(this.allSelected == 0) {
                    this.allSelected = 1;
                    this.downloadProjects = this.projects.map(function(project) {
                        return project.id.toString();
                    });
                } else {
                    this.allSelected = 0;
                }

            },
            ckeckStatus(project){
                (this.downloadProjects.includes(project.objectID)) ? 'fa-check-square' : 'fa-square'
            },
            addToDownloadProjects(project){
                this.allSelected = 2;
                (this.downloadProjects.includes(project.objectID)) ? this.downloadProjects.splice( this.downloadProjects.indexOf(project.objectID), 1 ) : this.downloadProjects.push(project.objectID)

            },
            formatMinValue(minValue, minRange) {
                return minValue !== null && minValue !== minRange ? minValue : '';
            },
            formatMaxValue(maxValue, maxRange) {
                return maxValue !== null && maxValue !== maxRange ? maxValue : '';
            },
            getProjects(){
                this.loading = true
                axios.get("/api/project")
                .then(
                    ({data}) => (
                        this.projects = data,
                        this.loading = false
                    )
                )
            },
            downloadSelectedProjects(){
                axios({
                    method: 'put',
                    url: '/api/download-project',
                    data: {
                        projects: this.downloadProjects
                    },
                    responseType: 'blob', // important
                }).then((response) => {
                    this.$Progress.finish();
                    const url = window.URL.createObjectURL(new Blob([response.data]));
                    const link = document.createElement('a');
                    link.href = url;
                    link.setAttribute('download', 'projects.xlsx');
                    document.body.appendChild(link);
                    link.click();
                    toast.fire({
                        type: 'success',
                        title: `Proyectos descargados correctamente.`
                    })
                });
            },
            goToProject(item){
                this.$router.push({ name: 'project', params: { id: item.objectID, s_active: this.active_section } })
                //window.location.href = `/projects/${item.objectID}`
            },
            editProject(project){
                this.$router.push({ name: 'project', params: { id: project.objectID, s_active: this.active_section } })
                //window.location.href = `/projects/edit/${project.objectID}`
            },
            deleteProject(project){
                swal.fire({
                    title: '¿Estás seguro?',
                    text: "¡No podrás revertir esto!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sí, eliminar projecto',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.value) {
                        axios.delete(`api/project/${project.objectID}`).then(()=>{
                            swal.fire({
                                title:'Eliminado',
                                text:'El projecto ha sido eliminado.',
                                type:'success'
                            }).then((result) => {
                                if(result.value){
                                    //console.log("yes sir!");
                                    location.reload();
                                }
                            })
                        }).catch(()=> {
                            swal("Error", "Algo salió mal", "warning");
                        })

                    }
                })
            }
        },
        created(){
          this.getProjects();
          Fire.$on('AfterCreate',() => {
              this.getProjects();
          });
        }
    }
</script>
<style >
.wrapper{
  width:70%;
}
@media(max-width:992px){
 .wrapper{
  width:100%;
}
}
.panel-heading {
  padding: 0;
	border:0;
}
.panel-title>a, .panel-title>a:active{
	display:block;
	padding:15px;
  color:#555;
  font-size:16px;
  font-weight:bold;
	text-transform:uppercase;
	letter-spacing:1px;
  word-spacing:3px;
	text-decoration:none;
}
.panel-heading  a:before {
   font-family: 'Glyphicons Halflings';
   content: "\e114";
   float: right;
   transition: all 0.5s;
}
.panel-heading.active a:before {
	-webkit-transform: rotate(180deg);
	-moz-transform: rotate(180deg);
	transform: rotate(180deg);
}
</style>

<style lang="scss" scoped>
    textarea, input {
        font-family: FontAwesome, "Open Sans", Verdana, sans-serif;
        font-style: normal;
        font-weight: normal;
        text-decoration: inherit;
    }
    .ais-Hits-list{
        display: flex;
        flex-direction: column;
        padding-left: 0;
        margin-bottom: 0;
    }
    li.ais-Hits-item{
        list-style-type: none;
    }
    .project_name{
        width: 40%;
    }
    .flex-orientation{
        @media (max-width: 767.98px) {
            flex-direction: column!important;
        }
    }
    .table-head{
        border-bottom: 2px solid #dee2e6;
    }
    .table-row{
        @media (max-width: 767.98px) {
            border: solid 1px #dee2e6;
            margin-bottom: 1.5em;
            box-shadow: 2px 2px 2px 0px #dee2e6;
            padding: 1em;
            &:hover{
                box-shadow: 5px 5px 5px 0px #dee2e6;
            }
        }
        @media (min-width: 768px) {
            border-bottom: 1px solid #dee2e6;
            &:hover{
                box-shadow: 2px 2px 2px 0px #dee2e6;
            }
        }
    }
    .text-center-md {
        @media (min-width: 991.98px) {
            text-align: center !important;
        }
    }
    .text-right-md {
        @media (min-width: 991.98px) {
            text-align: right !important;
        }
    }
    .project-options {
        padding-left: 2em;
        padding-right: 1em;
        position: absolute;
        z-index: 1;
        background-color: white;
        right: 1em;
        margin-right: 2px;
    }

    div.card.card-active {
        box-shadow:rgba(0, 0, 5, 0.35) 2px 2px 4px 0px;
    }

</style>
