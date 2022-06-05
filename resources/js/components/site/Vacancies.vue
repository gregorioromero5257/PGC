<template>
    <div class="vacancies">
        <div class="row mh-70 justify-content-md-center" style="background-image: url('/img/site/analistas.jpg'); background-repeat: no-repeat; background-position: top; background-size: cover;">
            <div class="col-md-8">
                <h1 class="text-center display-4 text-white mt-4 title-vacancies p-2">
                    <span>¡Únete a nuestro equipo!</span>
                </h1>
            </div>
        </div>

        <div class="row mh-90 justify-content-md-center bg-secondary pt-4 pb-4">
            <div class="col-md-10" v-for="vacancy in vacancies" :key="vacancy.id">
				<div class="card mb-4 shadow-sm">
                    <div class="row no-gutters">
                        <div
                            v-if="vacancy.department_id == 1"
                            class="col-md-3" 
                            style="background-image: url('/img/site/vacancy_consultancy.png'); background-repeat: no-repeat; background-position: center; background-size: cover;"
                        >
                            <img class="d-flex d-md-none card-img" src="/img/site/vacancy_consultancy.png"  alt="consultancy">
                        </div>
                        <div
                            v-else-if="vacancy.department_id == 2"
                            class="col-md-3" 
                            style="background-image: url('/img/site/vacancy_administration.png'); background-repeat: no-repeat; background-position: center; background-size: cover;"
                        >
                            <img class="d-flex d-md-none card-img" src="/img/site/vacancy_administration.png"  alt="administration">
                        </div>
                        <div
                            v-else-if="vacancy.department_id == 3"
                            class="col-md-3" 
                            style="background-image: url('/img/site/vacancy_digitalsolutions.png'); background-repeat: no-repeat; background-position: center; background-size: cover;"
                        >
                            <img class="d-flex d-md-none card-img" src="/img/site/vacancy_digitalsolutions.png"  alt="digital solutions">
                        </div>
                        <div class="col-md-9">
                            <div class="card-body">
                                <p>
                                    <span class="badge badge-secondary p-2 mr-2" v-if="vacancy.department">{{ vacancy.department.name }}</span> <span class="text-muted"><i class="far fa-clock"></i> Última actualización {{ vacancy.updated_at | myDate }}</span>
                                </p>
                                <h5 class="font-weight-light h3">
                                    {{ vacancy.name }}
                                </h5>
                                <p class="lead mb-2 vacancy-description">
                                    {{ vacancy.short_description }}
                                </p>
                                <router-link class="btn btn-link mb-3" :to="'/vacantes/'+vacancy.slug">
                                    Ver más <i class="fas fa-chevron-right ml-1"></i>
                                </router-link>
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
		components: {

		},
        data: () => ({
			vacancies: []
		}),
		methods: {
			getVacancies(){
                axios.get('/api/active-vacancies')
                .then(
                    ({data}) => {
                        this.vacancies = data
                    }
                )
            }
		},
		created(){
			this.getVacancies();
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

.card-img {
    width: 100%;
    border-radius: 0;
}

.title-vacancies{
  background-color: rgba(255,121,0,60%);
}

.vacancy-description {
	height: 8em;
	overflow-y: auto;
}
</style>