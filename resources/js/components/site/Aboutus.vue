<template>
    <div class="aboutus">
        <div class="row mh-90 justify-content-md-center align-items-center bg-dark text-white">
            <div class="col-md-10">
                <h1 class="text-center display-3">¿Quiénes somos?</h1>
                <p class="text-center h1 font-weight-light text-orange-site">
                    10 años generando información para impulsar la mejor toma de decisiones
                </p>
                <p class="text-center h2 font-weight-light pt-4 mb-4">
                    Nuestros valores:
                </p>
                <div class="row">
                    <div class="col-md-4 text-center">
                        <img src="/img/site/diamond1.svg" class="d-block mx-auto" alt="diamond 1" style="width:100px;" rel="preload">
                        <p class="lead">
                            Precisión en tiempos y entregables
                        </p>
                    </div>
                    <div class="col-md-4 text-center">
                        <img src="/img/site/flower.svg" class="d-block mx-auto" alt="flower" style="width:89px;" rel="preload">
                        <p class="lead">
                            Excelencia e innovación en procesos
                        </p>
                    </div>
                    <div class="col-md-4 text-center">
                        <img src="/img/site/hexagon.svg" class="d-block mx-auto" alt="hexagon" style="width:89px;" rel="preload">
                        <p class="lead">
                            Flexibilidad en selección de metodologías para entregar resultados
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="row mh-70 justify-content-md-center align-items-center">
                    <div class="col-md-10 p-4">
                        <h2 class="display-4 mb-3">Incidencia</h2>
                        <p class="lead mb-1">
                            Nosotros proponemos contribuir a la generación e implementación de políticas, intervenciones y programas que favorezcan de manera significativa y sustentable el desarrollo económico, la reducción de la pobreza y la igualdad de oportunidades.
                        </p>
                        <p class="lead mb-1">
                            Nos motiva pensar que nuestro quehacer fortalece a la región Latinoamericana, y en particular a los países en los que operamos.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="row mh-70 justify-content-md-center align-items-center">
                    <div class="col-md-10">
                        <img src="/img/site/building_blocks.svg" class="card-img-top" alt="city" rel="preload">
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="row mh-70 justify-content-md-center align-items-center">
                    <div class="col-md-10 p-4">
                        <h2 class="display-4 mb-3">Historia</h2>
                        <p class="lead mb-1 text-uppercase">
                            10 años de experiencia
                        </p>
                        <p class="lead mb-1">
                            C230 Consultores nació en el 2009 como el brazo consultor de Fundación IDEA, con el propósito de extender la gama de servicios ofrecidos a clientes públicos y privados en áreas de fomentar el desarrollo social y económico de América Latina y México.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 order-md-first">
                <div class="row mh-70 justify-content-md-center align-items-center">
                    <div class="col-md-10">
                        <img src="/img/site/process.svg" class="card-img-top" alt="city" rel="preload">
                    </div>
                </div>
            </div>
        </div>


        <div class="row mh-90 justify-content-md-center align-items-center bg-dark text-white">
            <div class="col-md-8">
                <h1 class="text-center display-4">Nuestro talento, nuestro valor</h1>
                <p class="text-center lead">
                    En C230 Consultores <span class="text-orange-site">contamos con un equipo de trabajo multidisciplinario</span>  con amplia experiencia y credenciales académicas de las <span class="text-orange-site">mejores universidades nacionales</span> (UNAM, CIDE, Tecnológico de Monterrey, Universidad Iberoamericana), <span class="text-orange-site">e internacionales</span> (Columbia, Harvard, Oxford, NYU, MIT, Stanford, Chicago, University of Toronto y University of Notre Dame).
                </p>
                <p class="text-center lead">
                    Además de las credenciales académicas, nos distinguen cualidades como el deseo de aportar a la región latinoamericana a través de nuestro trabajo, así como la curiosidad intelectual y sincero interés por los temas que tratamos.
                </p>
                <p class="text-center lead">
                    Nuestro objetivo es lograr <span class="text-orange-site">desarrollar las soluciones</span> que más valor aporten a Latinoamérica y a nuestros clientes.
                </p>
            </div>
        </div>

        <div class="row mh-50 justify-content-md-center">
            <div class="col-md-10">
                <h2 class="text-center display-4 mt-4 mb-4">Socios</h2>
                <div class="row mb-4">
                    <div class="col-sm-6 col-md-4" v-for="partner in partners" :key="partner.id">
                        <div class="card mx-auto mb-3 shadow-sm">
                            <div v-if="partnerSelected != partner.id" class="card-header" :style="{ 'background': 'url(' + partner.avatar + ') top no-repeat' }"></div>
                            <div class="card-body pt-3 bg-secondary text-white">
                                <div class="media">
                                    <div v-if="partner.avatar && partnerSelected == partner.id" class="partner-avatar-bg align-self-center mr-3" :style="{ 'background': 'url(' + partner.avatar + ')' }"></div>
                                    <div v-else-if="!partner.avatar && partnerSelected == partner.id" class="partner-avatar-bg align-self-center mr-3" style="background-image: url('/img/user-circle-solid.svg');"></div>
                                    <div class="media-body">
                                        <p class="h3 font-weight-light mb-0">
                                            {{ partner.name }}
                                        </p>
                                    </div>
                                    <i v-if="partnerSelected != partner.id" class="fas fa-info-circle fa-lg align-self-center ml-2 pointer" @click="selectPartner(partner)"></i>
                                    <i v-if="partnerSelected == partner.id" class="fas fa-times fa-lg align-self-center ml-2 pointer" @click="unselectPartner(partner)"></i>
                                </div>
                            </div>
                            <div class="card-footer" v-show="partnerSelected == partner.id">
                                <p class="partner-bio">
                                    {{ partner.bio_es }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mh-40 justify-content-md-center align-items-center bg-dark text-white" style="background-image: url('/img/site/zocalo.jpg');">
            <div class="col-md-8">
                <h2 class="text-center display-4">Tenemos un objetivo claro:</h2>
                <p class="text-center lead">
                    Fomentar el desarrollo social y económico de América Latina y México.
                </p>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data: () => ({
            partnerSelected: '',
            partners: [],
        }),
        methods:{
            selectPartner(partner){
                this.partnerSelected = partner.id;
            },
            unselectPartner(partner){
                this.partnerSelected = '';
            },
            getPartners(){
                axios.get("/api/partners")
                .then(
                    ({data}) => (
                        this.partners = data
                    )
                )
            }
        },
        created(){
            this.getPartners();
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
.mh-50 {
  min-height: 50vh;
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

.card-header {
    padding: 0.75rem 1.25rem;
    border-bottom: 0;
    border-top-left-radius: 0px;
    border-top-right-radius: 0px;
    min-height: 20em;
    max-height: 20em;

    background-clip: padding-box !important;
    margin: 0px;
    float: left;
    background-size: cover !important;
    background-position: center center !important;
}
.card-footer{
    min-height: 20em;
    max-height: 20em;
    overflow-y: auto;
}
.partner-avatar-bg {
    width: 40px;
    height: 40px;
    border-radius: 60px;
    background-clip: padding-box;
    margin: 7px 0 0 5px;
    float: left;
    background-size: cover !important;
    background-position: center center !important;
}
</style>