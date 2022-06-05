<template>
    <div class="row mb-3">
        <div class="col-md-12 d-lg-none">
            <p class="h5 font-weight-bold">
                Datos de administración
            </p>
        </div>
        <div class="col-md-12">
            <button type="button" class="btn btn-link float-right text-dark" @click="projectConfiguration">
                <i class="fas fa-cog"></i> Configurar opciones del proyecto
            </button>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="contract_type">Tipo de contrato</label>
                <multiselect 
                    v-model="form.contract" 
                    :options="contracts" 
                    :showLabels="false"
                    placeholder="" 
                    label="name" 
                    track-by="name">
                </multiselect>
                <has-error :form="form" field="contract_id"></has-error>
            </div>
            <div class="form-group">
                <label for="contract_backed_staff">Staff respaldado por contrato</label>
                <multiselect
                    class="mb-2"
                    v-model="form.contract_backed_staff" 
                    :options="users" 
                    :multiple="true" 
                    :close-on-select="false" 
                    :clear-on-select="false" 
                    :preserve-search="true" 
                    :showLabels="false"
                    placeholder=""
                    label="name" 
                    track-by="name" 
                    :preselect-first="true"
                >
                    <template slot="selection" slot-scope="{ values, search, isOpen }"><span class="multiselect__single" v-if="values.length &amp;&amp; !isOpen">{{ values.length }} miembro(s) seleccionado(s)</span></template>
                </multiselect>
            </div>
            <div class="form-group">
                <label for="satisfaction_documents">Tipo de documento de satisfacción</label>
                <div class="row mt-2 mb-pj">
                    <div 
                        class="col-md-6 mb-2" 
                        v-for="satisfaction_document in satisfaction_documents" 
                        :key="satisfaction_document.id"
                    >
                        <i 
                            class="far fa-lg pointer" 
                            @click="selectSatisfactionDocument(satisfaction_document)" 
                            :class="{ 
                                'fa-check-square' : form.satisfaction_documents.filter(sd => sd.id === satisfaction_document.id).length > 0,
                                'fa-square' : form.satisfaction_documents.filter(sd => sd.id === satisfaction_document.id).length <= 0
                            }">
                        </i> {{ satisfaction_document.name }}
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="satisfactiondoc_backed_staff">Staff respaldado por documento de satisfacción</label>
                <multiselect
                    class="mb-2"
                    v-model="form.satisfactiondoc_backed_staff" 
                    :options="users" 
                    :multiple="true" 
                    :close-on-select="false" 
                    :clear-on-select="false" 
                    :preserve-search="true" 
                    :showLabels="false"
                    placeholder=""
                    label="name" 
                    track-by="name" 
                    :preselect-first="true"
                >
                    <template slot="selection" slot-scope="{ values, search, isOpen }"><span class="multiselect__single" v-if="values.length &amp;&amp; !isOpen">{{ values.length }} miembro(s) seleccionado(s)</span></template>
                </multiselect>
            </div>
            <div class="form-group">
                <label for="contract_creator">A nombre de quién o qué identidad se hizo el contrato</label>
                <input v-model="form.contract_creator" type="text" class="form-control form-control-lg" :class="{ 'is-invalid': form.errors.has('contract_creator') }" id="contract_creator">
                <has-error :form="form" field="contract_creator"></has-error>
            </div>
            <div class="form-group">
                <label for="contractual_object">Objeto contractual</label>
                <input v-model="form.contractual_object" type="text" class="form-control form-control-lg" :class="{ 'is-invalid': form.errors.has('contractual_object') }" id="contractual_object">
                <small id="contractual_objectHelp" class="form-text text-muted font-italic">* Mencionar el objeto que viene en el contrato</small>
                <has-error :form="form" field="contractual_object"></has-error>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="currency">Moneda original del contrato</label>
                <multiselect 
                    v-model="form.currency" 
                    :options="currencies" 
                    :showLabels="false"
                    placeholder="" 
                    label="name" 
                    track-by="name">
                </multiselect>
                <has-error :form="form" field="currency_id"></has-error>
            </div>
            <div class="form-group">
                <label for="amount">Monto en contrato sin IVA (en moneda original)</label>
                <money v-model="form.amount" v-bind="money" class="form-control form-control-lg" :class="{ 'is-invalid': form.errors.has('amount') }" id="amount"></money>
                <has-error :form="form" field="amount"></has-error>
            </div>
            <div class="form-group">
                <label for="amount_usd">Monto en USD (según fecha de firma de contrato)</label>
                <money v-model="form.amount_usd" v-bind="usd" class="form-control form-control-lg" :class="{ 'is-invalid': form.errors.has('amount_usd') }" id="amount_usd"></money>
                <has-error :form="form" field="amount_usd"></has-error>
            </div>
            <div class="form-group">
                <label for="contract_start">Fecha de inicio de contrato</label>
                <input v-model="form.contract_start" type="date" class="form-control form-control-lg" :class="{ 'is-invalid': form.errors.has('contract_start') }" id="contract_start">
                <has-error :form="form" field="contract_start"></has-error>
            </div>
            <div class="form-group">
                <label for="contract_end">Fecha de fin de contrato</label>
                <input v-model="form.contract_end" type="date" class="form-control form-control-lg" :class="{ 'is-invalid': form.errors.has('contract_end') }" id="contract_end">
                <has-error :form="form" field="contract_end"></has-error>
            </div>
            <div class="form-group">
                <label for="duration">Duración de contrato</label>
                <input v-model="form.duration" type="text" class="form-control form-control-lg" :class="{ 'is-invalid': form.errors.has('duration') }" id="duration">
                <has-error :form="form" field="duration"></has-error>
            </div>
        </div>
    </div>
</template>

<script>
    import Multiselect from 'vue-multiselect'
    import {Money} from 'v-money'
    export default {
        props: {
            form: Object,
            satisfaction_documents: Array
        },
        components:{
            Money, Multiselect
        },
        data: () => ({
            contracts:[],
            currencies:[],
            users:[],
            usd: {
                decimal: '.',
                thousands: ',',
                prefix: '$ ',
                suffix: ' USD',
                precision: 2,
                masked: false
            },
            money: {
                decimal: '.',
                thousands: ',',
                prefix: '$ ',
                suffix: '',
                precision: 2,
                masked: false
            },
        }),
        methods: {
            projectConfiguration(){
                toast.fire({
                    type: 'info',
                    title: `Guarda la información del proyecto antes de configurar las opciones.`
                })
            },
            selectSatisfactionDocument(satisfaction_document){
                this.$emit('selectSatisfactionDocument', satisfaction_document)
            },
            getContracts(){
                axios.get('/api/contract')
                .then(
                    ({data}) => (
                        this.contracts = data
                    )
                )
            },
            getCurrencies(){
                axios.get('/api/currency')
                .then(
                    ({data}) => (
                        this.currencies = data
                    )
                )
            },
            getUsers(){
                axios.get("/api/user")
                .then(
                    ({data}) => (
                        this.users = data
                    )
                )
            },
        },
        created(){
            this.getContracts();
            this.getCurrencies();
            this.getUsers();
        }
    }
</script>

<style lang="scss" scoped>

</style>