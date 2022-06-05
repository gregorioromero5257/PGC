<template>
    <div class="col-md-12" v-show="publications.length >= 1">
        <p class="h6 font-weight-bold">
            Publicaciones
        </p>
        <div class="list-group list-group-flush mt-3 mb-4">
            <a class="list-group-item list-group-item-action" v-for="(publication, index) in publications" :key="index">
                <div class="d-flex w-100 justify-content-between">
                    <a v-if="publication.link != null" :href="'//'+publication.link" target="_blank" class="mb-1 h4 text-orange">
                        {{ publication.name }} <i class="fas fa-link fa-xs ml-1"></i>
                    </a>
                    <span class="h5" v-else>
                        {{ publication.name }}
                    </span>
                    <a class="btn pointer pt-0 pb-0" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-ellipsis-v" data-toggle="tooltip" data-placement="top" title="Ver Opciones"></i>
                    </a>
                    <div class="dropdown-menu">
<!--                         <a class="dropdown-item pointer" @click="editPublication(publication)"><i class="fas fa-pencil-alt mr-2"></i> Editar</a> -->
                        <a class="dropdown-item pointer" @click="deletePublication(publication)"><i class="far fa-trash-alt mr-2"></i> Eliminar</a>
                    </div>
                </div>
                <p class="mb-1">{{ publication.description }}</p>
            </a>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            publications: Array,
        },
        data: () => ({

        }),
        methods:{
            editPublication(){

            },
            deletePublication(publication_selected){
                this.$Progress.start();
                swal.fire({
                    title: '¿Estás seguro?',
                    text: "¡No podrás revertir esto!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.value) {
                        swal.fire(
                            'Eliminado',
                            'Publicación eliminada.',
                            'success'
                        )
                        this.$emit('deletePublication', publication_selected)
                        this.$Progress.finish();
                    } else {
                        this.$Progress.finish();
                    }
                })
            }
        },
        created(){

        }
    }
</script>

<style lang="scss" scoped>

</style>