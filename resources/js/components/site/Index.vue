<template>
    <div class="index">
        <div class="row">
            <div class="col-md-12 p-0">
                <flickity ref="flickity" :options="flickityOptions">
                    <div class="carousel-cell mh-90" v-for="project in projects" :key="project.id" style="width:100%;" :style="{ 'background': 'url(' + project.img + ') no-repeat center center' }">
                        <div class="row mh-90 justify-content-md-center align-items-center" style="width:100%;">
                            <div class="col-10 col-md-8 text-white m-4">
                                <p class="text-uppercase">
                                    <span class="h3 font-weight-light d-none d-md-block">Proyecto destacado</span>
                                    <span class="h3 font-weight-light d-flex d-md-none">Proyecto destacado</span>
                                </p>
                                <p>
                                    <span class="display-4 d-none d-md-block">{{ project.name }}</span>
                                    <span class="h1 font-weight-light d-flex d-md-none">{{ project.name }}</span>
                                </p>
                                <p class="lead d-none d-sm-block">
                                    {{ project.description }}
                                </p>
                                <router-link :to="'/proyectos/'+project.id" class="btn btn-orange-site">
                                    Saber más...
                                </router-link>
                            </div>
                        </div>
                    </div>
                </flickity>
            </div>
        </div>

        <div class="row mh-90 justify-content-md-center align-items-center bg-dark text-white">
            <div class="col-md-8">
                <h1 class="text-center display-4">
                    <span class="display-4 d-none d-md-block">
                        ¿Quiénes somos?
                    </span>
                    <span class="h1 d-md-none font-weight-light">
                        ¿Quiénes somos?
                    </span>
                </h1>
                <p class="text-center lead pt-4">
                    Somos una consultoría con
                    <span class="text-orange-site">10 años de experiencia</span> generando información para impulsar la mejor toma de decisiones.
                </p>
                <p class="text-center lead">
                    Nuestros valores se basan en la
                    <span class="text-orange-site">excelencia e innovación en procesos</span>, solidez en el manejo de tiempos y flexibilidad para entregar resultados específicos y en sintonía a las necesidades de nuestros clientes.
                </p>
            </div>
        </div>

        <div class="row">
            <div class="col-md-7">
                <div class="row mh-70 justify-content-md-center align-items-center">
                    <div class="col-md-10">
                        <h1>
                            <span class="display-4 d-none d-md-block">
                                ¿Qué hacemos?
                            </span>
                            <span class="h1 d-md-none font-weight-light">
                                ¿Qué hacemos?
                            </span>
                        </h1>
                        <p class="lead text-uppercase pt-4">Asesoramos la toma de decisiones</p>
                        <p class="lead">
                            Ayudamos a resolver problemas y añadimos valor a los proyectos de cada uno de nuestros clientes.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-5 bg-secondary">
                <div class="row mh-70 justify-content-md-center align-items-center">
                    <div class="col-md-10">
                        <div class="card mx-auto mt-4 mb-4 shadow-sm">
                            <img src="/img/site/card_img.svg" class="card-img-top" alt="card image" rel="preload">
                            <div class="card-body pt-3">
                                <p class="lead text-uppercase mb-1">
                                    Nuestra práctica
                                </p>
                                <p class="card-text">
                                    Nuestra experiencia nos facilita diseñar y adaptar el alcance de nuestros proyectos de acuerdo a los problemas y a las necesidades específicas de cada cliente.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mh-40 justify-content-md-center align-items-center bg-orange-site">
            <div class="col-md-12 text-center">
                <h2 class="font-weight-light">Únete a C230 Consultores</h2>
                <p class="lead mb-0">Estamos constantemente en búsqueda de talento.</p>
                <p class="lead">Si estas interesado en ingresar a C230 Consultores, revisa nuestras vacantes:</p>
                <router-link to="/vacantes" class="btn btn-outline-light" style="border-radius: 0rem; !important">Ir a vacantes</router-link>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="row mh-70 justify-content-md-center align-items-center">
                    <div class="col-10 mx-auto col-md-10 border-orange p-4">
                        <h2 class="mb-3">
                            <span class="display-4 d-none d-md-block">
                                ¿Para quiénes?
                            </span>
                            <span class="h1 d-md-none font-weight-light">
                                ¿Para quiénes?
                            </span>
                        </h2>
                        <p class="lead mb-1">
                            <i class="far fa-circle fa-xs text-orange-site mr-1"></i> Organizaciones públicas
                        </p>
                        <p class="lead mb-1">
                            <i class="far fa-circle fa-xs text-orange-site mr-1"></i> Multilaterales
                        </p>
                        <p class="lead mb-1">
                            <i class="far fa-circle fa-xs text-orange-site mr-1"></i> Organismos internacionales
                        </p>
                        <p class="lead mb-1">
                            <i class="far fa-circle fa-xs text-orange-site mr-1"></i> Sector privado
                        </p>
                        <p class="lead mb-1">
                            <i class="far fa-circle fa-xs text-orange-site mr-1"></i> Organizaciones sin fines de lucro
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="row mh-70 justify-content-md-center align-items-center">
                    <div class="col-10 mx-auto col-md-10">
                        <img src="/img/site/city.png" class="card-img-top" alt="city" rel="preload">
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 order-md-last">
                <div class="row mh-80 justify-content-md-center align-items-center">
                    <div class="col-10 mx-auto col-md-10 border-orange p-4">
                        <h2 class="mb-3">
                            <span class="display-4 d-none d-md-block">
                                ¿En qué trabajamos?
                            </span>
                            <span class="h1 d-md-none font-weight-light">
                                ¿En qué trabajamos?
                            </span>
                        </h2>
                        <p class="lead mb-1">
                            <i class="far fa-circle fa-xs text-orange-site mr-1"></i> Estrategias
                        </p>
                        <p class="lead mb-1">
                            <i class="far fa-circle fa-xs text-orange-site mr-1"></i> Políticas públicas
                        </p>
                        <p class="lead mb-1">
                            <i class="far fa-circle fa-xs text-orange-site mr-1"></i> Problemas públicos
                        </p>
                        <p class="lead mb-1">
                            <i class="far fa-circle fa-xs text-orange-site mr-1"></i> Iniciativas públicas
                        </p>
                        <p class="lead mb-1">
                            <i class="far fa-circle fa-xs text-orange-site mr-1"></i> Comunicación
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="row mh-80 justify-content-md-center align-items-center">
                    <div class="col-8 mx-auto col-md-8">
                        <img src="/img/site/graph.png" class="card-img-top" alt="city" rel="preload">
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="row mh-80 justify-content-md-center align-items-center">
                    <div class="col-10 mx-auto col-md-10 border-orange p-4">
                        <h2 class="mb-3">
                            <span class="display-4 d-none d-md-block">
                                Proyectos
                            </span>
                            <span class="h1 d-md-none font-weight-light">
                                Proyectos
                            </span>
                        </h2>
                        <p class="lead mb-1">
                            Desde hace 10 años hemos realizado proyectos en diversos sectores y para múltiples clientes lo cual nos ha brindado una experiencia única en consultoría en Latinoamérica.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="row mh-80 justify-content-md-center align-items-center">
                    <div class="col-10 mx-auto col-md-8">
                        <img src="/img/site/laptop.png" class="card-img-top" alt="city" rel="preload">
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import Flickity from 'vue-flickity';
    export default {
        components: {
            Flickity
        },
        data: () => ({
            flickityOptions: {
                initialIndex: 0,
                prevNextButtons: false,
                pageDots: true,
                wrapAround: true,
                autoPlay: true,
                fullscreen: true
            },
            projects: [
                {
                    id: 1,
                    name: 'Reducción de Traslados Laborales RTL',
                    description: '¿Se puede reducir la emisisión de gases de efectos contaminantes y mejorar el bienestar de los trabajadores al mismo tiempo que se reduce el congestionamiento vehicular?',
                    img: '/img/site/bus.jpg'
                },
                {
                    id: 2,
                    name: 'La inseguridad pública y la Violencia Basada en Género en México',
                    description: '¿La inseguridad pública se experimenta de manera distinta en mujeres y hombres?',
                    img: '/img/site/march.jpg'
                },
                {
                    id: 3,
                    name: 'Mejora Regulatoria',
                    description: '¿Las regulaciones gubernamentales fomentan o restringen la actividad empresarial? La simplificación administrativa de procesos estatales y municipales promueve la eficiencia y el fortalecimiento de capacidades para lograr objetivos específicos de las administraciones.',
                    img: '/img/site/contract.jpg'
                },
                {
                    id: 4,
                    name: 'Policía de Proximidad',
                    description : '¿Cómo podemos mejorar la seguridad pública local integrando un enfoque de proximidad a las policías locales?',
                    img: '/img/site/police.jpg'
                }
            ]
        }),
        methods:{
        },
        computed: {

        },
        beforeMount(){

        },
        mounted(){

        },
        created(){

        }
    };
</script>

<style lang="scss" scoped>
.mh-90 {
    height: 90vh;
}
.mh-80 {
    min-height: 50vh;
    @media (min-width: 576px) {
        min-height: 50vh;
    }
    @media (min-width: 768px) {
        min-height: 80vh;
    }
    @media (min-width: 992px) {
        min-height: 80vh;
    }
    @media (min-width: 1200px) {
        min-height: 80vh;
    }
}
.mh-70 {
    min-height: 50vh;
    @media (min-width: 576px) {
        min-height: 50vh;
    }
    @media (min-width: 768px) {
        min-height: 70vh;
    }
    @media (min-width: 992px) {
        min-height: 70vh;
    }
    @media (min-width: 1200px) {
        min-height: 70vh;
    }
}
.mh-40 {
  min-height: 40vh;
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

.card-img-top {
    width: 100%;
    border-top-left-radius: 0px;
    border-top-right-radius: 0px;
}

.border-orange {
    border: solid 1px #ff7900;
}

.img-card-site {
    width: 90%;
}

.title-separator {
    border-bottom: solid 3px #ff7900;
    padding-bottom: 7px;
}
</style>