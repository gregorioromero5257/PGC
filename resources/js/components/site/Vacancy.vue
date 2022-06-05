<template>
    <div class="vacancy">
        <div class="row mh-60 justify-content-end bg-light">
            <div class="col-md-7">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mt-4">
                        <li class="breadcrumb-item"><router-link class="text-orange" to="/vacantes">Vacantes</router-link></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ vacancy.slug }}</li>
                    </ol>
                </nav>

                <h1>
                    <span class="display-4 d-none d-md-block">{{ vacancy.name }}</span>
                    <span class="h1 d-md-none">{{ vacancy.name }}</span>
                </h1>
                <p class="lead">
                    {{ vacancy.short_description }}
                </p>
            </div>
            <div
                v-if="vacancy.department_id == 1"
                class="col-md-4 mh-60"
                style="background-image: url('/img/site/vacancy_consultancy_single.png'); background-repeat: no-repeat; background-position: center; background-size: cover;"
            ></div>
            <div
                v-else-if="vacancy.department_id == 2"
                class="col-md-4 mh-60"
                style="background-image: url('/img/site/vacancy_administration_single.png'); background-repeat: no-repeat; background-position: center; background-size: cover;"
            ></div>
            <div
                v-else-if="vacancy.department_id == 3"
                class="col-md-4 mh-60"
                style="background-image: url('/img/site/vacancy_digitalsolutions_single.png'); background-repeat: no-repeat; background-position: center; background-size: cover;"
            ></div>
        </div>
        <div class="row justify-content-md-center mt-4 mb-4">
            <div class="col-md-10">
                <hr>
            </div>
            <div class="col-md-3">
                <p class="h1 font-weight-light text-right">Requisitos de la vacante</p>
            </div>
            <div class="col-md-7">
                <p class="lead" v-html="vacancy.requirements"></p>
            </div>
        </div>

        <div class="row justify-content-md-center mt-4 mb-4">
            <div class="col-md-10">
                <hr>
            </div>
            <div class="col-md-3">
                <p class="h1 font-weight-light text-right">Descripción de la vacante</p>
            </div>
            <div class="col-md-7">
                <p class="lead" v-html="vacancy.description"></p>
            </div>
        </div>

        <div class="row justify-content-md-center mt-2 mb-4">
            <div class="col-md-10">
                <hr>
            </div>
            <div class="col-md-10 mt-4">
                <p class="lead text-center">
                    Si estás interesado(a) en esta posición, favor de mandar tu CV a <span class="text-orange-site">reclutamiento@c-230.com</span> mencionando la posición en el asunto del correo.
                </p>
            </div>
        </div>

        <div class="row mh-50 justify-content-end bg-light">
            <div class="col-md-5">
                <div class="row mh-50 justify-content-center align-items-center">
                    <div class="col-md-12">
                        <img style="width:70%;" class="mb-3 mt-3" src="/img/site/iuvenia.png" alt="iuvenia">
                        <p class="lead">
                            A C230 Consultores le otorgaron el distintivo IUVENIA para <span class="text-orange-site">Empresas Amigas de los Jóvenes</span>, por ser una organización líder en políticas de Recursos Humanos, que atrae al mejor talento, invierte en él y desarrolla a su gente.
                        </p>
                    </div>
                </div>
            </div>
            <div 
                class="col-md-6 mh-50"
                style="background-image: url('/img/site/analistas.jpg'); background-repeat: no-repeat; background-position: center; background-size: cover;"
            >
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        components: {

        },
        data: () => ({
            loading: false,
            vacancy: {}
        }),
        methods: {
            getVacancy() {
                this.loading = true;
                axios.get(`/api/vacancy-by-slug/${this.$route.params.slug}`)
                .then(
                    ({data}) => (
                        this.vacancy = data
                    )
                )
                .catch(

                )
            }
        },
        created() {
            this.getVacancy();
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
.mh-60 {
  min-height: 60vh;
}
.mh-50 {
  min-height: 50vh;
}
.mh-40 {
  min-height: 40vh;
}
.mh-30 {
  min-height: 30vh;
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

.breadcrumb {
    display: flex;
    flex-wrap: wrap;
    padding: 0.75rem 1rem;
    margin-bottom: 1rem;
    list-style: none;
    background-color: #F6F6F6 !important;
    border-radius: 0.25rem;
}

.bg-light {
    background-color: #F6F6F6 !important;
}
</style>