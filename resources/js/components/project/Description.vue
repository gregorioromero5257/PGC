<template>
    <div class="row">
        <div class="col-md-12 d-lg-none">
            <p class="h5 font-weight-bold">
                Descripción del proyecto
            </p>
        </div>
        <div class="col-md-12">
            <button type="button" class="btn btn-link float-right text-dark" @click="projectConfiguration">
                <i class="fas fa-cog"></i> Configurar opciones del proyecto
            </button>
        </div>
        <div class="col-md-12">
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
                    <div class="form-group mt-3">
                        <label for="description_es">Descripción detallada</label>
                        <textarea v-model="form.description_es" class="form-control form-control-lg" id="description_es" :class="{ 'is-invalid': form.errors.has('description_es') }" rows="5"></textarea>
                        <has-error :form="form" field="description_es"></has-error>
                    </div>
                    <div class="form-group">
                        <label for="short_description_es">Descripción corta</label>
                        <textarea v-model="form.short_description_es" class="form-control form-control-lg" id="short_description_es" :class="{ 'is-invalid': form.errors.has('short_description_es') }"></textarea>
                        <has-error :form="form" field="short_description_es"></has-error>
                    </div>
                </div>
                <div class="tab-pane fade" id="description_en" role="tabpanel" aria-labelledby="description_en-tab">
                    <div class="form-group mt-3">
                        <label for="description_es">Detailed description</label>
                        <textarea v-model="form.description_en" class="form-control form-control-lg" id="description_en" :class="{ 'is-invalid': form.errors.has('description_en') }" rows="5"></textarea>
                        <has-error :form="form" field="description_en"></has-error>
                    </div>
                    <div class="form-group">
                        <label for="short_description_en">Short description</label>
                        <textarea v-model="form.short_description_en" class="form-control form-control-lg" id="short_description_en" :class="{ 'is-invalid': form.errors.has('short_description_en') }"></textarea>
                        <has-error :form="form" field="short_description_en"></has-error>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <p class="h6 font-weight-bold">
                Categorías
            </p>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                <label for="types">Tipo de proyecto</label>
                <multiselect
                    class="mb-2"
                    v-model="form.types" 
                    :options="types" 
                    :multiple="true" 
                    :close-on-select="false" 
                    :clear-on-select="false" 
                    :preserve-search="true" 
                    :showLabels="false"
                    placeholder="Seleccionar tipo de proyecto"
                    label="name" 
                    track-by="name" 
                    :preselect-first="true"
                >
                    <template slot="selection" slot-scope="{ values, search, isOpen }"><span class="multiselect__single" v-if="values.length &amp;&amp; !isOpen">{{ values.length }} tipos de proyecto seleccionados</span></template>
                </multiselect>

                <span class="badge badge-secondary p-2 mt-2 mr-2" v-for="type in form.types" :key="type.id">
                    {{ type.name }} <i @click="removeType(type)" class="fas fa-times-circle pointer" data-toggle="tooltip" data-placement="top" :title="'Remover '+type.name+' de la selección'"></i>
                </span>
            </div>
            <div class="form-group">
                <label for="types">Análisis, metodologías e implementación</label>
                <multiselect
                    class="mb-2"
                    v-model="form.methodologies" 
                    :options="methodologies" 
                    :multiple="true" 
                    :close-on-select="false" 
                    :clear-on-select="false" 
                    :preserve-search="true" 
                    :showLabels="false"
                    placeholder="Seleccionar Análisis, metodologías e implementación"
                    label="name" 
                    track-by="name" 
                    :preselect-first="true"
                >
                    <template slot="selection" slot-scope="{ values, search, isOpen }"><span class="multiselect__single" v-if="values.length &amp;&amp; !isOpen">{{ values.length }} elementos seleccionados</span></template>
                </multiselect>

                <span class="badge badge-secondary p-2 mt-2 mr-2" v-for="methodology in form.methodologies" :key="methodology.id">
                    {{ methodology.name }} <i @click="removeMethodology(methodology)" class="fas fa-times-circle pointer" data-toggle="tooltip" data-placement="top" :title="'Remover '+methodology.name+' de la selección'"></i>
                </span>
            </div>
            <div class="form-group">
                <label for="types">Temáticas / Enfoque / Población objetivo</label>
                <multiselect
                    class="mb-2"
                    v-model="form.topics" 
                    :options="topics" 
                    :multiple="true" 
                    :close-on-select="false" 
                    :clear-on-select="false" 
                    :preserve-search="true" 
                    :showLabels="false"
                    placeholder="Seleccionar Temáticas / Enfoque / Población objetivo"
                    label="name" 
                    track-by="name" 
                    :preselect-first="true"
                >
                    <template slot="selection" slot-scope="{ values, search, isOpen }"><span class="multiselect__single" v-if="values.length &amp;&amp; !isOpen">{{ values.length }} elementos seleccionados</span></template>
                </multiselect>

                <span class="badge badge-secondary p-2 mt-2 mr-2" v-for="topic in form.topics" :key="topic.id">
                    {{ topic.name }} <i @click="removeTopic(topic)" class="fas fa-times-circle pointer" data-toggle="tooltip" data-placement="top" :title="'Remover '+topic.name+' de la selección'"></i>
                </span>
            </div>
        </div>
        <Publications
            :publications="form.publications"
            @deletePublication="deletePublication"
        />
        <NewPublication 
            @addPublication="addPublication"
        />
        <div class="col-md-12">
            <p class="h6 font-weight-bold">
                Estadísticas
            </p>
        </div>
        <div class="col md-6">
            <div class="form-group">
                <label for="interviews">Entrevistas</label>
                <input v-model="form.interviews" type="number" class="form-control form-control-lg" :class="{ 'is-invalid': form.errors.has('interviews') }" id="interviews">
                <has-error :form="form" field="interviews"></has-error>
            </div>
            <div class="form-group">
                <label for="databases">Bases de datos creadas</label>
                <input v-model="form.databases" type="number" class="form-control form-control-lg" :class="{ 'is-invalid': form.errors.has('databases') }" id="databases">
                <has-error :form="form" field="databases"></has-error>
            </div>
        </div>
        <div class="col md-6">
            <div class="form-group">
                <label for="trainings">Capacitaciones</label>
                <input v-model="form.trainings" type="number" class="form-control form-control-lg" :class="{ 'is-invalid': form.errors.has('trainings') }" id="trainings">
                <has-error :form="form" field="trainings"></has-error>
            </div>
            <div class="form-group">
                <label for="study_cases">Casos de estudio</label>
                <input v-model="form.study_cases" type="number" class="form-control form-control-lg" :class="{ 'is-invalid': form.errors.has('study_cases') }" id="study_cases">
                <has-error :form="form" field="study_cases"></has-error>
            </div>
        </div>
    </div>
</template>

<script>
    import NewPublication from '../publication/NewPublication'
    import Publications from '../publication/Publications'
    import Multiselect from 'vue-multiselect'
    export default {
        props: {
            form: Object,
        },
        components: {
            Multiselect,
            NewPublication,
            Publications
        },
        data: () => ({
            methodologies:[],
            topics:[],
            types:[],
            satisfaction_documents:[],
        }),
        methods: {
            projectConfiguration(){
                toast.fire({
                    type: 'info',
                    title: `Guarda la información del proyecto antes de configurar las opciones.`
                })
            },
            addPublication(publication){
                this.form.publications.push(publication);

            },
            deletePublication(publication_selected){
                this.form.publications = this.form.publications.filter(publication => publication.id != publication_selected.id);
            },
            removeType(type_selected){
                this.$props.form.types = this.$props.form.types.filter(type => type.id != type_selected.id);
            },
            removeMethodology(methodology_selected){
                this.$props.form.methodologies = this.$props.form.methodologies.filter(methodology => methodology.id != methodology_selected.id);
            },
            removeTopic(topic_selected){
                this.$props.form.topics = this.$props.form.topics.filter(topic => topic.id != topic_selected.id);
            },
            getMethodologies(){
                axios.get('/api/methodology')
                .then(
                    ({data}) => (
                        this.methodologies = data
                    )
                )
            },
            getTypes(){
                axios.get('/api/type')
                .then(
                    ({data}) => (
                        this.types = data
                    )
                )
            },
            getTopics(){
                axios.get('/api/topic')
                .then(
                    ({data}) => (
                        this.topics = data
                    )
                )
            },
            getSatisfactionDocuments(){
                axios.get('/api/satisfaction_document')
                .then(
                    ({data}) => (
                        this.satisfaction_documents = data
                    )
                )
            },
        },
        created(){
            this.getMethodologies();
            this.getTypes();
            this.getTopics();
            this.getSatisfactionDocuments();
        }
    }
</script>

<style lang="scss" scoped>

</style>