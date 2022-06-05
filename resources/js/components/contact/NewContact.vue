<template>
    <div class="col-md-12">
        <p class="font-weight-bold">Nuevo contacto</p>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="name">Nombre</label>
                <input v-model="contact_form.name" type="text" class="form-control form-control-lg" id="name">
            </div>
            <div class="form-group col-md-6">
                <label for="email">Correo electrónico</label>
                <input v-model="contact_form.email" type="email" class="form-control form-control-lg" id="email">
            </div>
            <div class="form-group col-md-6">
                <label for="position">Puesto</label>
                <input v-model="contact_form.position" type="text" class="form-control form-control-lg" id="position">
            </div>
            <div class="form-group col-md-6">
                <label for="phone">Teléfono</label>
                <input v-model="contact_form.phone" type="text" class="form-control form-control-lg" id="phone">
            </div>
            <div class="form-group col-md-12 text-center">
                <button class="btn btn-orange btn-rounded text-center" @click.prevent="createContact">Agregar contacto</button>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            stackeholder: Object
        },
        data: () => ({
            contact_form: new Form({
                id:'',
                stackeholder_id:'',
                name:'',
                email:'',
                position:'',
                phone:'',
            }),
        }),
        methods: {
            createContact(){
                this.$Progress.start();
                this.contact_form.stackeholder_id = this.stackeholder.id;
                this.contact_form.post('/api/contact')
                .then((response)=>{
                    this.contact_form.reset();
                    this.$emit('addContact', response.data);
                    toast.fire({
                        type: 'success',
                        title: `${response.data.name} agregado a contactos`
                    })
                    this.$Progress.finish();
                })
                .catch(()=>{
                    this.$Progress.fail();
                    toast.fire({
                        type: 'error',
                        title: `Error al crear contacto.`
                    })
                })
            },
        },
        created(){

        }
    }
</script>

<style lang="scss" scoped>

</style>