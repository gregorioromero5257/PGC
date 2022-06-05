<template>
    <div class="footer">
        <div class="row bg-dark pt-4">
            <div class="col-md-12">
                <div class="container-fluid">
                    <div class="row pt-4 bg-dark justify-content-md-center">
                        <div class="col-sm-6 col-md-3 text-center">
                            <p class="h2 text-orange-site font-weight-light text-uppercase">C230 Consultores</p>
                            <p class="text-white lead">
                            <i class="fas fa-map-marker-alt mr-1"></i> Leibnitz No. 20, Anzures, Miguel Hidalgo, 11590 Ciudad de México, CDMX
                            </p>
                        </div>
                        <div class="col-sm-6 col-md-3 text-center">
                            <p class="h2 text-orange-site font-weight-light text-uppercase">Contacto</p>
                            <p class="text-white lead mb-1">
                            <i class="far fa-envelope mr-1"></i> info@c-230.com
                            </p>
                            <p class="text-white lead">
                            <i class="fas fa-phone-square-alt mr-1"></i> +52 (55) 5208 1403
                            </p>
                        </div>
                        <div class="col-sm-6 col-md-3 text-center">
                            <p class="h2 text-orange-site font-weight-light text-uppercase">Medios</p>
                            <p class="lead mb-1">
                            <a class="text-white" href="https://www.linkedin.com/company/c230-consultores/" target="_blank">
                                <i class="fab fa-linkedin mr-1"></i> LinkedIn
                            </a>
                            </p>
                            <p class="lead mb-1">
                            <a class="text-white" href="https://twitter.com/C230Consultores" target="_blank">
                                <i class="fab fa-twitter-square"></i> Twitter
                            </a>
                            </p>
                        </div>
                        <div class="col-sm-6 col-md-3 text-center">
                            <p class="h2 text-orange-site font-weight-light text-uppercase">Privacidad</p>
                            <p class="text-white lead">
                                <router-link to="/aviso-de-privacidad" class="text-white">
                                    <i class="fas fa-lock mr-1"></i> Aviso de privacidad
                                </router-link>
                            </p>
                        </div>
                        <div class="col-md-12 text-white">
                            <div class="site-disclaimer mt-2 mb-4">
                                <p class="mb-1 lead">C230 Consultores no cobra ninguna cuota durante los procesos de reclutamiento, selección y contratación a los aspirantes.</p>
                                <p class="mb-1 lead">C230 Consultores no realiza ningún cobro durante sus procesos de adquisiciones a sus proveedores.</p>
                                <p class="lead">C230 Consultores mantiene una política de cero tolerancia con respecto a la trata de personas.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        
    }
</script>

<style lang="scss" scoped>
.site-disclaimer {
    border: solid 2px #ff7900;
    padding: 1.5em;
}
</style>