<template>
    <div class="whatwedo">
        <div class="row mh-90 justify-content-md-center align-items-center bg-dark text-white">
            <div class="col-md-10">
                <h1 class="text-center display-3 pb-4">¿Qué hacemos?</h1>
                <div class="row">
                    <div class="col-md-4 text-center">
                        <img src="/img/site/hexagon2.svg" class="d-block mx-auto" alt="hexagon 2" style="width:89px;" rel="preload">
                        <p class="text-orange-site h2 font-weight-light">
                            Generamos insumos
                        </p>
                        <p class="lead">
                            para apoyar la toma de decisiones de nuestros clientes.
                        </p>
                    </div>
                    <div class="col-md-4 text-center">
                        <img src="/img/site/circlesDo.svg" class="d-block mx-auto pt-2 pb-2" alt="flower" style="width:120px;" rel="preload">
                        <p class="text-orange-site h2 font-weight-light">
                            Ayudamos
                        </p>
                        <p class="lead">
                            a resolver problemas
                        </p>
                    </div>
                    <div class="col-md-4 text-center">
                        <img src="/img/site/triangle3D.svg" class="d-block mx-auto pt-2 pb-3" alt="hexagon" style="width:89px;" rel="preload">
                        <p class="text-orange-site h2 font-weight-light">
                            Añadimos valor
                        </p>
                        <p class="lead">
                            al trabajo de nuestros clientes
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mh-90 justify-content-md-center align-items-center">
            <div class="col-md-10">
                <h1 class="text-center display-4 pb-4 pt-4">
                    ¿Para quiénes?
                </h1>
                <div class="row">
                    <div class="col-md-6" v-for="target in whatWeDoContent" :key="target.id" v-show="target.section == 'forwhom'">
                        <div class="card mb-4 shadow-sm">
                            <div class="card-header bg-orange-site">
                                <h3 class="font-weight-light h3">
                                    {{ target.name }}
                                </h3>
                            </div>
                            <div class="card-body" style="min-height:10em;">
                                <p class="lead">
                                    {{ target.description }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12 order-1">
                <h1 class="text-center display-4 pb-4 pt-4">
                    ¿En qué trabajamos?
                </h1>
            </div>
            <div :style="[theme.bgimg ? { 'background': 'url(' + theme.bgimg + ') bottom right no-repeat' } : {}]" class="col-sm-6 col-md-6" :class="{ 'bg-dark text-white': theme.id == 5 || theme.id == 8, 'bg-light': theme.id == 6 || theme.id == 7, 'order-2' : theme.id == 5, 'order-3' : theme.id == 6, 'order-5 order-md-4' : theme.id == 7, 'order-4 order-md-5' : theme.id == 8 }" v-for="theme in whatWeDoContent" :key="theme.id" v-show="theme.section == 'themesofwork'">
                <div class="row mh-70 justify-content-md-center align-items-center">
                    <div class="col-md-6">
                        <img class="mx-auto d-block p-3" :src="theme.img" :alt="theme.name" style="width:60%;" rel="preload">
                        <h2 class="h2 font-weight-light text-uppercase text-center">
                            {{ theme.name }}
                        </h2>
                        <p class="lead">
                            {{ theme.description }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mh-80 justify-content-md-center align-items-center bg-light" style="background-image: url('/img/site/digital-solutions-3.jpg'); background-repeat: no-repeat; background-position: center; background-size: cover;">
            <div class="col-md-6 text-white p-3 ds-content title-ds">
                <h1 class="text-center display-4">
                    Soluciones digitales
                </h1>
                <p class="h3 font-weight-light text-center mt-1">
                    Trabajamos en proyectos que involucran al gobierno y que tienen con fin último un beneficio positivo en México.
                </p>
            </div>
        </div>

        <div class="row" v-for="service in whatWeDoContent" :key="service.id" v-show="service.section == 'digitalsolutionsservices'">
            <div class="col-sm-6 col-md-6 bg-dark order-1" :class="{ 'order-md-2': service.id%2 == 0 }">
                <div class="row mh-70 justify-content-md-center align-items-center">
                    <div class="col-md-6">
                        <img class="mx-auto d-block p-3" :src="service.img" :alt="service.name" style="width:60%;" rel="preload">
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-6 bg-light order-2" :class="{ 'order-md-1': service.id%2 == 0 }">
                <div class="row mh-70 justify-content-md-center align-items-center">
                    <div class="col-md-6">
                        <h3 class="h3 font-weight-light text-uppercase text-center">
                            {{ service.name }}
                        </h3>
                        <p class="lead" v-html="service.description"></p>
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
            whatWeDoContent: [
                {
                    id: 1,
                    name: 'Organizaciones Públicas',
                    description: 'Son todas aquellas organizaciones que pertenecen al Estado, sea este nacional, municipal, o de cualquier otro ámbito administrativo-gubernamental, ya sea de una manera total o parcial.',
                    section: 'forwhom',
                    img: '',
                    bgimg: '',
                },
                {
                    id: 2,
                    name: 'Sector Privado',
                    description: 'Parte de la economía que busca el lucro en su actividad y que no está controlada por el Estado, como por ejemplo, las empresas.',
                    section: 'forwhom',
                    img: '',
                },
                {
                    id: 3,
                    name: 'Multilaterales',
                    description: 'Organización conformada por tres o más naciones cuya principal misión será trabajar conjuntamente enlas problemáticas y aspectos relacionados con los países que integran la organización en cuestión.',
                    section: 'forwhom',
                    img: '',
                    bgimg: '',
                },
                {
                    id: 4,
                    name: 'Organizaciones Internacionales',
                    description: 'Todo grupo o asociación que se extiende más allá de las fronteras de un Estado y que adopta una estructura orgánica permanente.',
                    section: 'forwhom',
                    img: '',
                    bgimg: '',
                },
                {
                    id: 5,
                    name: 'Estrategias',
                    description: 'Diseño, evaluación, análisis e implementación de programas, indicadores y soluciones que redunden en el beneficio de la sociedad, la economía y el medio ambiente.',
                    section: 'themesofwork',
                    img: '/img/site/circle.svg',
                    bgimg: '',
                },
                {
                    id: 6,
                    name: 'Políticas Públicas',
                    description: 'Buscamos alinear intereses de diferentes grupos políticos sociales y económicos bajo una visión común que les permita desarrollarse y prosperar sosteniblemente.',
                    section: 'themesofwork',
                    img: '/img/site/piramid.svg',
                    bgimg: '/img/site/piramid_bg.svg',
                },
                {
                    id: 7,
                    name: 'Problemas Públicos',
                    description: 'Analizamos recursos y oportunidades de desarrollo que afecten a la calidad de vida de las personas, comunidades y el entorno que las sostiene.',
                    section: 'themesofwork',
                    img: '/img/site/arrow.svg',
                    bgimg: '/img/site/arrow_bg.svg',
                },
                {
                    id: 8,
                    name: 'Iniciativas Públicas',
                    description: 'Trabajamos en proyectos que involucran al gobierno y que tienen con fin último un beneficio positivo en México.',
                    section: 'themesofwork',
                    img: '/img/site/circles.svg',
                    bgimg: '',
                },
                {
                    id: 9,
                    name: 'Ideación de soluciones digitales basadas en principios de diseño centrado en el usuario:',
                    description: "<ul><li>Aplicaciones web</li><li>Aplicaciones móviles</li><li>Diseño de bases de datos y API's</li><li>Visualización de datos</li><li>Plataformas digitales para monitoreo de indicadores</li></ul>",
                    section: 'digitalsolutionsservices',
                    img: '/img/site/figureHexa.png',
                    bgimg: '',
                },
                {
                    id: 10,
                    name: 'Diseño de experiencias e interfaces para soluciones digitales (UI/UX):',
                    description: '<ul><li>User Interface</li><li>User Experience</li></ul>',
                    section: 'digitalsolutionsservices',
                    img: '/img/site/figureCircle.png',
                    bgimg: '',
                },
                {
                    id: 11,
                    name: 'Desarrollo e implementación de soluciones digitales',
                    description: '',
                    section: 'digitalsolutionsservices',
                    img: '/img/site/figureRombo.png',
                    bgimg: '',
                },
                {
                    id: 12,
                    name: 'Desarrollo y prototipado de software y arquitectura de información',
                    description: '',
                    section: 'digitalsolutionsservices',
                    img: '/img/site/figureTriang.png',
                    bgimg: '',
                },
                {
                    id: 13,
                    name: 'Implementación de metodología ágil para desarrollo de software',
                    description: '',
                    section: 'digitalsolutionsservices',
                    img: '/img/site/figureCubo.png',
                    bgimg: '',
                },
                {
                    id: 14,
                    name: 'Talleres de diseño estratégico y metodologías de diseño de productos y servicios',
                    description: '',
                    section: 'digitalsolutionsservices',
                    img: '/img/site/figureCircle2.png',
                    bgimg: '',
                },
            ],
        }),
        methods: {

        }
    }
</script>

<style lang="scss" scoped>
.mh-90 {
  min-height: 90vh;
}
.mh-80 {
  min-height: 80vh;
}
.mh-70 {
  min-height: 70vh;
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
    border: 1px solid rgba(0, 0, 0, 0.125);
    border-radius: 0;
}

.card-header {
    border-radius: 0px !important;
    padding: 0.75rem 1.25rem;
    margin-bottom: 0;
    background-color: rgba(0, 0, 0, 0.03);
    border-bottom: 1px solid rgba(0, 0, 0, 0.125);
}

.title-ds{
  background-color: rgba(255,121,0,60%);
}
</style>