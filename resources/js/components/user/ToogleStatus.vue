<template>
    <a class="btn" data-toggle="tooltip" data-placement="top" title="Cambiar estatus del usuario" @click.stop="changeStatus(user)">
        <i class="fas" :class="{ 'fa-toggle-off' : user.active == 0, 'fa-toggle-on text-orange' : user.active == 1 }"></i>
    </a>
</template>

<script>
    import { createWidgetMixin } from 'vue-instantsearch';
    const connectRefresh = (renderFn, unmountFn) => (widgetParams = {}) => ({
        init() {
            renderFn({ refresh() {} }, true);
        },
        render({ instantSearchInstance }) {
            const refresh = instantSearchInstance.refresh.bind(instantSearchInstance);

            renderFn({ refresh }, false);
        },
        dispose() {
            unmountFn();
        },
    });

    export default {
        props: {
            user: Object,
        },
        name: 'AisStateResults',
        mixins: [createWidgetMixin({ connector: connectRefresh })],
        methods: {
            refresh() {
                this.state.refresh();
            },
            changeStatus(user){
                this.refresh();
                this.$Progress.start();
                if (this.$gate.activeUser().id === user.objectID) {
                    toast.fire({
                        type: 'error',
                        title: `No se puede cambiar el estatus del usuario activo.`
                    })
                    this.$Progress.fail();
                } else {
                    axios.delete(`/api/user/${user.objectID}`)
                    .then(() => {
                        swal.fire({
                            type: 'info',
                            title: `Estatus de ${user.name} actualizado correctamente.`
                        }).then((result) => {
                            if(result.value){
                                location.reload();
                            }
                        })
                        this.$Progress.finish();
                        //this.$emit('refreshStatus', true)
                    })
                    .catch(() => {
                        toast.fire({
                            type: 'error',
                            title: `Error al actualizar el estatus.`
                        })
                        this.$Progress.fail();
                    })
                }
            }
        },
        created(){
        }
    }
</script>

<style lang="scss" scoped>

</style>