<template>
    <div class="stakeholders row">
        <Stackeholder 
            v-for="stackeholder in stackeholders" 
            :key="stackeholder.id"
            :stackeholder="stackeholder"
            :form="form"
        />
    </div>
</template>

<script>
    import Stackeholder from '../stackeholders/Stakeholder'
    export default {
        props: {
            stackeholders: Array,
            form: Object,
        },
        components: {
            Stackeholder
        },
        data: () => ({

        }),
        methods: {

        },
        created(){

        }
    }
</script>

<style lang="scss" scoped>

</style>