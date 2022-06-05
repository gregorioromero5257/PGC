<template>
    <div class="featured-project">
        <div class="row mh-80 bg-dark text-white">
            <div class="col-md-5">
                <div class="row mh-40 justify-content-md-center align-items-start">
                    <div class="col-md-11">
                        <p class="h3 font-weight-light text-uppercase mt-4" style="letter-spacing: 0.3em;">
                            <i class="fas fa-chevron-right text-orange-site"></i> proyecto destacado
                        </p>
                    </div>
                </div>
                <div class="row mh-40 justify-content-md-center align-items-end">
                    <div class="col-md-11">
                        <h1 class="mb-4">
                            <span class="display-4 font-weight-bold d-none d-md-block">
                                {{ project.name }}
                            </span>
                            <span class="h1 d-md-none">
                                {{ project.name }}
                            </span>
                        </h1>
                    </div>
                </div>
            </div>
            <div 
                class="col-md-7 mh-80"
                :style="{ 'background': 'url(' + project.img + ') no-repeat top center' }"
                style="background-size: cover;"
            ></div>
        </div>

        <div class="row mh-30 bg-light justify-content-md-center align-items-center">
            <div class="col-md-10">
                <p class="mt-4">
                    <span class="lead font-weight-light d-none d-md-block" v-html="project.description"></span>
                    <span class="lead font-weight-light d-md-none" v-html="project.description"></span>
                </p>
                <p class="mb-4">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb bg-light">
                            <li class="breadcrumb-item" v-for="section in project.sections" :key="section.id">
                                <a class="text-orange-site" :href="'#'+section.title">{{ section.title }}</a>
                            </li>
                        </ol>
                    </nav>
                </p>
            </div>
        </div>

        <div class="row mh-30 justify-content-md-center" v-if="project.clients.length >= 1">
            <div class="col-md-10">
                <div class="card shadow mt-4 mb-4">
                    <div class="row">
                        <div class="col-md-12">
                            <h2 class="h1 text-center mt-4" style="color:#979797;">
                                Clientes
                            </h2>
                        </div>
                    </div>
                    <div class="row justify-content-md-center align-items-center">
                        <div class="col-md-3" v-for="client in project.clients" :key="client.id">
                            <div class="card mx-auto p-2">
                                <img :src="client.img" class="card-img-top p-3" alt="card image">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div :id="section.title" class="row justify-content-md-center align-items-center" v-for="(section, index) in project.sections" :key="index">
            <div class="col-md-10 mt-2">
                <p class="display-4" style="color: #4A4A4A;">
                    {{ section.title }}
                </p>
                <div class="row justify-content-end">
                    <div class="col-md-11">
                        <p class="lead">
                            {{ section.subtitle }}
                        </p>
                    </div>
                </div>
                <div class="row justify-content-end" v-if="section.subsections.length >= 1">
                    <div class="col-sm-12" :class="{ 'col-md-12' : subsection.type == 'with-image-text', 'col-md-11' : subsection.type == 'simple', 'col-md-6' : subsection.type == 'with-image' }" v-for="(subsection, index) in section.subsections" :key="index">
                        <div class="card mx-auto mt-2 mb-2 shadow" v-show="subsection.type == 'with-image-text'">
                            <div class="card-body pt-3">
                                <div class="row justify-content-md-center align-items-start">
                                    <div class="col-md-2 mb-2" v-if="subsection.img">
                                        <img class="mx-auto d-block" style="width:80%;" :src="subsection.img" :alt="subsection.title">
                                    </div>
                                    <div class="col-md-10">
                                        <p v-if="subsection.title" class="h3 mb-0">
                                            {{ subsection.title }}
                                        </p>
                                        <p class="lead" v-html="subsection.text"></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card mx-auto mt-2 mb-2 shadow" v-show="subsection.type == 'with-image'">
                            <div class="card-body pt-3">
                                <div class="row justify-content-md-center align-items-center">
                                    <div class="col-md-12" v-if="subsection.img">
                                        <img class="mx-auto d-block p-3" style="width: auto;max-width: 100%;height: 15em;" :src="subsection.img" :alt="subsection.title">
                                        <p v-if="subsection.title" class="h3 text-center">
                                            {{ subsection.title }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                         <p class="lead" v-html="subsection.text" v-show="subsection.type == 'simple'"></p>
                    </div>
                </div>
                <div class="row justify-content-end">
                    <div class="col-md-11">
                        <p class="lead">
                            {{ section.footer }}
                        </p>
                    </div>
                </div>
                <hr style="border-top: 2px solid rgba(0, 0, 0, 0.1);">
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data: () => ({
            projects: [
                {
                    id: 1,
                    name: 'Reducción de Traslados Laborales RTL',
                    description: '<p>Diversos factores han provocado que el número y tiempos de traslados aumenten, generando consecuencias negativas para el medio ambiente, la productividad de los trabajadores y el bienestar general de la población.</p><p class="lead">Una alternativa para reducir la emisión de gases contaminantes y reducir el congestionamiento vial ha sido la adopción de esquemas Reducción de Traslados Laborales(RTL o Commute Trip Reduction en inglés).</p>',
                    img: '/img/site/bus.jpg',
                    clients: [],
                    sections: [
                        {
                            id: 1,
                            title: 'Actividades',
                            subtitle: 'Los esquemas de Reducción de Traslados Laborales contemplan acciones que modifiquen la manera de trabajar o trasladarse de las personas.Estos esquemas incluyen acciones tales como:',
                            subsections: [
                                {
                                    id: 1,
                                    title: 'Incluir teletrabajo',
                                    text: '',
                                    img: '/img/site/home_work.png',
                                    type: 'with-image'
                                },
                                {
                                    id: 2,
                                    title: 'Implementar horarios escalonados',
                                    text: '',
                                    img: '/img/site/schedules.png',
                                    type: 'with-image'
                                },
                                {
                                    id: 3,
                                    title: 'Hacer uso de un programa autos compartidos',
                                    text: '',
                                    img: '/img/site/shared_rides.png',
                                    type: 'with-image'
                                },
                                {
                                    id: 4,
                                    title: 'Establecer semanas comprimidas',
                                    text: '',
                                    img: '/img/site/compressed_weeks.png',
                                    type: 'with-image'
                                },
                                {
                                    id: 5,
                                    title: 'Fomentar de un sistema de vanpool',
                                    text: '',
                                    img: '/img/site/vanpool.png',
                                    type: 'with-image'
                                },
                                {
                                    id: 6,
                                    title: 'Establecer apoyos a empleados para uso de transporte público',
                                    text: '',
                                    img: '/img/site/help.png',
                                    type: 'with-image'
                                },
                                {
                                    id: 7,
                                    title: 'Implementar fomento del uso de la bicicleta',
                                    text: '',
                                    img: '/img/site/bike.png',
                                    type: 'with-image'
                                },
                                {
                                    id: 8,
                                    title: 'Programa de estacionamiento preferencial para auto compartido',
                                    text: '',
                                    img: '/img/site/parking.png',
                                    type: 'with-image'
                                }
                            ],
                            footer: 'Muchas ciudades, incluyendo metrópolis como Los Ángeles o Phoenix han implementado estos esquemas de Reducción de Traslados Laborales a través de sus legislaciones locales. Actualmente, no hay ninguna ciudad en México​ que haya implementado un esquema de Reducción de Traslados Laborales.'
                        },
                        {
                            id: 2,
                            title: 'Objetivo',
                            subtitle: 'Elaborar un estudio técnico para la Secretaría de Medio Ambiente y Recursos Naturales (SEMARNAT) para el diseño e implementación de un esquema de Reducción de Traslados Laborales en las principales ciudades de México para reducir la emisión de gases de efecto invernadero.',
                            subsections: []
                        },
                        {
                            id: 3,
                            title: 'Propuesta de solución',
                            subtitle: '',
                            subsections: [
                                {
                                    id: 9,
                                    title: '',
                                    text: '<p class="lead">Tras un análisis de diversas alternativas basadas en buenas prácticas internacionales y considerando el contexto jurídico y político-administrativo mexicano, se propuso la adopción de un esquema de Reducción de Traslados Laborales de participación voluntaria.</p>',
                                    img: '',
                                    type: 'simple'
                                },
                                {
                                    id: 10,
                                    title: '',
                                    text: '<p class="lead">Bajo este esquema, las organizaciones públicas y privadas pudiesen adoptar -de manera voluntaria- una o más medidas que logren reducir los traslados laborales de sus miembros con el apoyo técnico y supervisión de cumplimiento de PROFEPA, quienes otorgarían reconocimientos a las empresas que logren cumplir con sus metas de reducción de traslados laborales.</p>',
                                    img: '',
                                    type: 'simple'
                                },
                                {
                                    id: 11,
                                    title: '',
                                    text: '<p class="lead">Se propuso que SEMARNAT, en coordinación con PROFEPA, evaluasen la efectividad del esquema; así como con la identificación de buenas prácticas para la eventual migración a un esquema obligatorio.</p>',
                                    img: '',
                                    type: 'simple'
                                },
                            ]
                        },
                    ]
                },
                {
                    id: 2,
                    name: 'La inseguridad pública y la Violencia Basada en Género en México',
                    description: '<p>En octubre de 2015, USAID/México contrató a C230 Consultores para llevar a cabo un estudio sobre los vínculos entre la inseguridad pública y la violencia basada en género (VBG) en México para informar a los Objetivos de Desarrollo de la Estrategia 2014- 2018 de USAID/México: 1) Prevención de la violencia y la delincuencia; 2) Estado de derecho; 3) Derechos humanos. La metodología del estudio incluyó revisión de literatura, entrevistas a profundidad, dos casos de estudio (en Monterrey, Nuevo León y en Ecatepec, Estado de México), y un mapeo de actores e intervenciones de VBG en México. Se publicó el reporte final en mayo de 2016.</p>',
                    img: '/img/site/march.jpg',
                    clients: [
                        {
                            id:1,
                            name: 'USAID',
                            img: '/img/site/usaid.png'
                        },
                    ],
                    sections: [
                        {
                            id: 4,
                            title: 'Hallazgos',
                            subtitle: '',
                            subsections: [
                                {
                                    id: 12,
                                    title: '',
                                    text: '<p class="lead">La inseguridad pública se experimenta de manera distinta en mujeres y hombres. Los hombres son la mayoría de los perpetradores y de las víctimas de violencia en México. Las mujeres son menos propensas que los hombres a sufrir la mayoría de los delitos, excepto la violencia sexual y secuestros. Las mujeres tienen más miedo que los hombres de sufrir un delito, y son más propensas a limitar su participación en espacios públicos en consecuencia.</p>',
                                    img: '',
                                    type: 'simple'
                                },
                                {
                                    id: 13,
                                    title: '',
                                    text: '<p class="lead">La VBG en México está sub-registrada, subestimada y mal medida. Hay una falta de información actualizada, desagregada, local y geo-referenciada sobre la VBG, lo cual dificulta el diseño y la implementación de políticas adecuadas para prevenir y atender la VBG.</p>',
                                    img: '',
                                    type: 'simple'
                                },
                                {
                                    id: 14,
                                    title: '',
                                    text: '<p class="lead">La VBG prolifera y se intensifica donde incrementa la violencia generalizada y hay impunidad.</p>',
                                    img: '',
                                    type: 'simple'
                                },
                                {
                                    id: 15,
                                    title: '',
                                    text: '<p class="lead">Hay una desarticulación de las intervenciones de seguridad pública y las intervenciones de VBG en México, que suelen enfocarse solamente en la violencia contra las mujeres (VCM). La prioridad de la política pública para combatir al crimen organizado “opaca” a la VBG en México.</p>',
                                    img: '',
                                    type: 'simple'
                                },
                                {
                                    id: 16,
                                    title: '',
                                    text: '<p class="lead">La mayoría de las intervenciones de prevención de VBG en México, tanto del gobierno como de la sociedad civil, tienden a ser ex post facto, después de que haya ocurrido un hecho de violencia, para prevenir la reincidencia y para proporcionar servicios a víctimas. Hay poca prevención antes del hecho de violencia, o en el largo plazo después de la violencia.</p>',
                                    img: '',
                                    type: 'simple'
                                },
                                {
                                    id: 17,
                                    title: '',
                                    text: '<p class="lead">El acceso de justicia para víctimas de VBG requiere alta capacitación de los actores del sistema de justicia penal para evitar su revictimización y garantizar sus derechos.</p>',
                                    img: '',
                                    type: 'simple'
                                },
                                {
                                    id: 18,
                                    title: '',
                                    text: '<p class="lead">Cuando se aplica la perspectiva de género a las violaciones graves de derechos humanos, se revelan características diferenciadas de los y las víctimas de homicidio/feminicidio, trata de personas, desapariciones forzadas, tortura y violencia contra defensores de derechos humanos y periodistas.</p>',
                                    img: '',
                                    type: 'simple'
                                },
                            ]
                        },
                        {
                            id: 5,
                            title: 'Recomendaciones',
                            subtitle: '',
                            subsections: [
                                {
                                    id: 19,
                                    title: 'Perspectiva de género transversal',
                                    text: 'Promover la implementación de una perspectiva de género transversal en todas las intervenciones de seguridad pública.',
                                    img: '/img/site/gender_perspective.png',
                                    type: 'with-image-text'
                                },
                                {
                                    id: 20,
                                    title: 'Implementación local',
                                    text: 'Desarrollar la capacidad estatal y municipal para la implementación de la política nacional y el marco jurídico en contra VBG',
                                    img: '/img/site/local_implementation.png',
                                    type: 'with-image-text'
                                },
                                {
                                    id: 21,
                                    title: 'Intervenciones multi-sectoriales',
                                    text: 'Generar colaboraciones entre el gobierno y las organizaciones de sociedad civil que trabajan en intervenciones contra de la VBG',
                                    img: '/img/site/multisectoral_interventions.png',
                                    type: 'with-image-text'
                                },
                                {
                                    id: 22,
                                    title: 'Decisiones basadas en evidencia',
                                    text: 'Apoyar a la documentación de VBG y los aspectos de género en las violaciones graves de derechos humanos. Impulsar el desarrollo de monitoreo y evaluación de la VBG en la práctica para promover el aprendizaje y la mejora continua',
                                    img: '/img/site/evidence_based_decisions.png',
                                    type: 'with-image-text'
                                },
                                {
                                    id: 23,
                                    title: 'Prevención primaria',
                                    text: 'Incluir la prevención de VBG en intervenciones de prevención más amplias contra la violencia y la delincuencia en el hogar, la escuela y la comunidad',
                                    img: '/img/site/primary_prev.png',
                                    type: 'with-image-text'
                                },
                                {
                                    id: 24,
                                    title: 'Acceso a justicia con perspectiva de género',
                                    text: 'Capacitar a agentes de seguridad y a operadores de justicia en maneras prácticas de intervenir en casos de VBG',
                                    img: '/img/site/access_to_justice_wgp.png',
                                    type: 'with-image-text'
                                },
                            ]
                        },
                    ]
                },
                {
                    id: 3,
                    name: 'Mejora Regulatoria',
                    description: '¿Las regulaciones gubernamentales fomentan o restringen la actividad empresarial? La simplificación administrativa de procesos estatales y municipales promueve la eficiencia y el fortalecimiento de capacidades para lograr objetivos específicos de las administraciones.',
                    img: '/img/site/contract.jpg'
                },
                {
                    id: 4,
                    name: 'Implementación del Modelo de Policía de Proximidad',
                    description : '¿Cómo se puede mejorar la eficiencia y la percepción de las policías locales en México?',
                    img: '/img/site/police.jpg',
                    clients: [
                        {
                            id:2,
                            name: 'Comisión Nacional de Seguridad',
                            img: '/img/site/cns-logo.png'
                        },
                        {
                            id:3,
                            name: 'Secretaría de Gobernación',
                            img: '/img/site/segob.png'
                        },
                        {
                            id:4,
                            name: 'Secretariado Ejecutivo Del Sistema Nacional De Seguridad Pública',
                            img: '/img/site/secretariado_ejecutivo.png'
                        },
                        {
                            id:5,
                            name: 'Los Cabos',
                            img: '/img/site/cabos.png'
                        },
                    ],
                    sections: [
                        {
                            id: 6,
                            title: 'Contexto',
                            subtitle: '',
                            subsections: [
                                {
                                    id: 25,
                                    title: '',
                                    text: '<p class="lead"><span class="text-orange-site">México enfrenta una crisis de confianza en sus instituciones policiales locales.</span> Según el INEGI, menos del 40% de los mexicanos percibe como efectiva la policía preventiva municipal . Los policías municipales, por su lado, suelen enfrentar condiciones laborales adversas: salarios bajos, turnos largos, y limitadas oportunidades de crecimiento. Adicionalmente, el bajo desempeño de las instituciones está vinculado a la carencia de elementos básicos de gestión, tales como el registro, gestión y análisis de información; la toma de decisiones con base en información; y la evaluación y adaptación de estrategias. En consecuencia, las corporaciones policiales suelen ser predominantemente reactivas, y cada vez menos preparadas ante un crimen más complejo y una ciudadanía más exigente.</p>',
                                    img: '',
                                    type: 'simple'
                                },
                                {
                                    id: 26,
                                    title: '',
                                    text: '<p class="lead"><span class="text-orange-site">La Policía de Proximidad</span> (o Community Policing, en inglés) es una estrategia de gestión policial que, además de combatir la violencia y delincuencia, busca identificar y resolver sus causas. Promueve el acercamiento con los ciudadanos, vistos como corresponsables en la producción de la seguridad, para conocer sus problemas y solucionarlos colaborativamente.</p>',
                                    img: '',
                                    type: 'simple'
                                },
                                {
                                    id: 27,
                                    title: '',
                                    text: '<p class="lead">Modelos de Policía de Proximidad se han implementado de forma exitosa en los últimos 30 años alrededor del mundo, por ejemplo en <span class="text-orange-site">Irlanda del Norte, Estados Unidos, Colombia o Chile.</span> En México, algunos municipios también han tenido iniciativas al respecto. Sin embargo, en México no se contaba con un modelo nacional que permitiera homogeneizar las bases para la gestión de instituciones policiales con enfoque de proximidad. Entre 2015 y 2016, el Grupo Técnico formado por la Subsecretaría de Prevención de la Secretaría de Gobernación, la Comisión Nacional de Seguridad y el Secretariado Ejecutivo del Sistema Nacional de Seguridad Pública, con asistencia técnica de Fundación IDEA y financiamiento de la Embajada Británica y los municipios de General Escobedo (NL) y Morelia (MICH), desarrollaron un Modelo mexicano de Policía de Proximidad. Hoy, los municipios que reciben recursos del Programa para el Fortalecimiento de la Seguridad (FORTASEG) pueden utilizar una parte de éstos para implementar el modelo.</p>',
                                    img: '',
                                    type: 'simple'
                                },
                            ]
                        },
                        {
                            id: 7,
                            title: 'Objetivo',
                            subtitle: 'Apoyar al municipio de Los Cabos, Baja California Sur, en la implementación del Modelo de Policía de Proximidad.',
                            subsections: []
                        },
                        {
                            id: 8,
                            title: 'Propuestas',
                            subtitle: 'Tras un diagnóstico inicial, y un análisis de las brechas entre los requerimientos del Modelo de Policía de Proximidad y la situación de la Dirección General de Seguridad Pública, Policía Preventiva y Tránsito Municipal de Los Cabos, C230 Consultores diseñó un plan de implementación, y proporcionó asistencia técnica al municipio con respecto a los siguientes elementos:',
                            subsections: [
                                {
                                    id: 28,
                                    title: 'Implementación de la visión de proximidad',
                                    text: '',
                                    img: '/img/site/proximity_vision.png',
                                    type: 'with-image'
                                },
                                {
                                    id: 29,
                                    title: 'Fortalecimiento de la Unidad de Análisis',
                                    text: '',
                                    img: '/img/site/analysis_unit.png',
                                    type: 'with-image'
                                },
                                {
                                    id: 30,
                                    title: 'Integración de la visión de proximidad en la operación policial',
                                    text: '',
                                    img: '/img/site/proximity_vision_integration.png',
                                    type: 'with-image'
                                },
                                {
                                    id: 31,
                                    title: 'Ampliación de la operación de la Unidad Especializada a la atención de Violencia Familiar y de Género',
                                    text: '',
                                    img: '/img/site/familiargender_violence.png',
                                    type: 'with-image'
                                },
                                {
                                    id: 32,
                                    title: 'Implementación de un esquema de colaboración con la comunidad con un enfoque de proximidad',
                                    text: '',
                                    img: '/img/site/colaboration.png',
                                    type: 'with-image'
                                },
                                {
                                    id: 33,
                                    title: 'Desarrollo de recomendaciones para una estrategia integral de prevención',
                                    text: '',
                                    img: '/img/site/recomendations.png',
                                    type: 'with-image'
                                },
                            ]
                        },
                    ]
                }
            ],
            project: {}
        }),
        methods: {
            getProject(){
                this.project = this.projects.find(project => project.id == this.$route.params.id);
            }
        },
        created(){
            this.getProject();
        }
    }
</script>

<style lang="scss" scoped>
.mh-100 {
  min-height: 100vh;
}
.mh-90 {
  min-height: 90vh;
}
.mh-80 {
    min-height: 40vh;

    @media (min-width: 576px) {
        min-height: 40vh;
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
  min-height: 70vh;
}
.mh-60 {
  min-height: 60vh;
}
.mh-50 {
  min-height: 50vh;
}
.mh-40 {
    @media (min-width: 576px) {

    }
    @media (min-width: 768px) {

    }
    @media (min-width: 992px) {
        min-height: 40vh;
    }
    @media (min-width: 1200px) {
        min-height: 40vh;
    }
}
.mh-30 {
  min-height: 30vh;
}
.card-header {
    border-radius: 0px !important;
    padding: 0.75rem 1.25rem;
    margin-bottom: 0;
    background-color: rgba(0, 0, 0, 0.03);
    border-bottom: 1px solid rgba(0, 0, 0, 0.125);
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
.img-card-site {
    width: 90%;
}

/* Style to change separator  */
.breadcrumb-item + .breadcrumb-item::before {
    content: ">";
    color: #ff7900;
}
</style>