<template>
    <div class="row"> 
        <div class="col-md-12 d-lg-none">
            <p class="h5 font-weight-bold">
                Información de las partes interesadas
            </p>
        </div>
        <div class="col-md-12">
            <button type="button" class="btn btn-link float-right text-dark" @click="projectConfiguration">
                <i class="fas fa-cog"></i> Agregar cliente, financiador o proveedor
            </button>
        </div>
        <div class="col-md-12 mb-4">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="customers-tab" data-toggle="tab" href="#customers" role="tab" aria-controls="customers" aria-selected="true">Clientes</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="funders-tab" data-toggle="tab" href="#funders" role="tab" aria-controls="funders" aria-selected="false">Financiadores</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="suppliers-tab" data-toggle="tab" href="#suppliers" role="tab" aria-controls="suppliers" aria-selected="false">Proveedores</a>
                </li>
            </ul>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active mt-3" id="customers" role="tabpanel" aria-labelledby="customers-tab">
                    <div class="form-group">
                        <label class="typo__label">Seleccionar cliente</label>
                        <multiselect 
                            v-model="form.clients" 
                            :options="getClients"
                            :multiple="true"
                            :close-on-select="false" 
                            :clear-on-select="false" 
                            :preserve-search="true" 
                            :showLabels="false"
                            placeholder="Seleccionar cliente" 
                            label="name" 
                            track-by="name">
                            <template slot="selection" slot-scope="{ values, search, isOpen }"><span class="multiselect__single" v-if="values.length &amp;&amp; !isOpen">{{ values.length }} cliente(s) seleccionado(s)</span></template>
                        </multiselect>
                        <stackeholders
                            :stackeholders="form.clients"
                            :form="form"
                        />
                    </div>
                </div>
                <div class="tab-pane fade mt-3" id="funders" role="tabpanel" aria-labelledby="funders-tab">
                    <div class="form-group">
                        <label class="typo__label">Seleccionar financiador</label>
                        <multiselect 
                            v-model="form.funders" 
                            :options="stackeholders"
                            :multiple="true"
                            :close-on-select="false" 
                            :clear-on-select="false" 
                            :preserve-search="true" 
                            :showLabels="false"
                            placeholder="Seleccionar financiador"
                            label="name" 
                            track-by="name">
                            <template slot="selection" slot-scope="{ values, search, isOpen }"><span class="multiselect__single" v-if="values.length &amp;&amp; !isOpen">{{ values.length }} financiador(es) seleccionado(s)</span></template>
                        </multiselect>
                        <stackeholders
                            :stackeholders="form.funders"
                            :form="form"
                        />
                    </div>
                </div>
                <div class="tab-pane fade mt-3" id="suppliers" role="tabpanel" aria-labelledby="suppliers-tab">
                    <div class="form-group">
                        <label class="typo__label">Seleccionar proveedor</label>
                        <multiselect 
                            v-model="form.suppliers" 
                            :options="stackeholders"
                            :multiple="true"
                            :close-on-select="false" 
                            :clear-on-select="false" 
                            :preserve-search="true" 
                            :showLabels="false"
                            placeholder="Seleccionar proveedor"
                            label="name" 
                            track-by="name">
                            <template slot="selection" slot-scope="{ values, search, isOpen }"><span class="multiselect__single" v-if="values.length &amp;&amp; !isOpen">{{ values.length }} proveedor(es) seleccionado(s)</span></template>
                        </multiselect>
                        <stackeholders
                            :stackeholders="form.suppliers"
                            :form="form"
                        />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import stackeholders from '../stackeholders/Stakeholders'
    import Multiselect from 'vue-multiselect'
    export default {
        props: {
            form: Object,
        },
        components: {
            stackeholders,
            Multiselect
        },
        data: () => ({
            stackeholders: [],
        }),
        methods: {
            projectConfiguration(){
                toast.fire({
                    type: 'info',
                    title: `Guarda la información del proyecto antes de agregar nuevos clientes, financiadores o proveedores.`
                })
            },
            getStackeholders(){
                axios.get('/api/stackeholder')
                .then(
                    ({data}) => (
                        this.stackeholders = data
                    )
                )
            },
        },
        computed: {
            getClients: function () {
                return this.stackeholders.filter(stackeholder => stackeholder.sh_type_id == 1)
            },
            getFunders: function () {
                return this.stackeholders.filter(stackeholder => stackeholder.sh_type_id == 2)
            },
            getSuppliers: function () {
                return this.stackeholders.filter(stackeholder => stackeholder.sh_type_id == 3)
            }
        },
        created(){
            this.getStackeholders();
            Fire.$on('updateStakeholders', () => {
                this.getStackeholders();
            });
        }
    }
</script>

<style lang="scss" scoped>
    .card-header{
        padding: 0.75rem 1.25rem;
        margin-bottom: 0;
        border-bottom: 0px;
    }

    .badge-circle {
        /* padding-right: 0.6em; */
        /* padding-left: 0.6em; */
        border-radius: 50%;
        padding: 7px;
    }
    .contact-border{
        border-bottom: solid lightgray 1.4px;
    }
</style>