<template>
    <div class="col-md-12">
        <p class="h6 font-weight-bold">
            Publicación
        </p>
        <div class="form-group">
            <label for="name">Nombre de la publicación</label>
            <input v-model="publication_form.name" type="text" class="form-control form-control-lg" id="name">
        </div>

        <div class="form-group">
            <label for="link">Enlace a la publicación</label>
            <input v-model="publication_form.link" type="text" class="form-control form-control-lg" id="link">
        </div>

        <div class="form-group">
            <label for="description">Nota adicional sobre la publicación</label>
            <textarea v-model="publication_form.description" class="form-control form-control-lg" id="description"></textarea>
        </div>
        <div class="form-group text-center">
            <button class="btn btn-orange btn-rounded" @click="addPublication">Agregar publicación</button>
        </div>
    </div>
</template>

<script>
    export default {
        props: {

        },
        data: () => ({
            publication_form: new Form ({
                id:'',
                project_id:'',
                name: '',
                link: '',
                description: '',
            })
        }),
        methods:{
            addPublication(){
                this.$Progress.start();
                if (this.publication_form.name && this.publication_form.description) {
                    let publication = {};
                    publication.id = '';
                    publication.project_id = '';
                    publication.name = this.publication_form.name;
                    publication.link = this.publication_form.link;
                    publication.description = this.publication_form.description;
                    this.publication_form.reset();
                    this.$emit('addPublication', publication)
                    toast.fire({
                        type: 'success',
                        title: `Publicación agregada correctamente`
                    })
                    this.$Progress.finish();
                } else {
                    this.$Progress.fail();
                    toast.fire({
                        type: 'error',
                        title: `Error al crear publicación`
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