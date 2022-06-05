<template>
    <div class="contact">
        <div class="row list-header pt-2 pb-2">
            <div class="col-md-12">
                <a class="btn pointer float-right pt-0 pb-0" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-ellipsis-v" data-toggle="tooltip" data-placement="top" title="Ver Opciones"></i>
                </a>
                <div class="dropdown-menu">
                    <a class="dropdown-item pointer" @click="editContact(contact)"><i class="fas fa-pencil-alt mr-2"></i> Editar</a>
                    <a class="dropdown-item pointer" @click="deleteContact(contact)"><i class="far fa-trash-alt mr-2"></i> Eliminar</a>
                </div>
                <p class="pointer mb-0" data-toggle="collapse" :href="'#contactInfo'+contact.id" role="button" aria-expanded="false" :aria-controls="'contactInfor'+contact.id">
                    <span class="badge badge-circle badge-secondary">{{ contact.name | myFirstLetter }}</span>
                    <span class="pt-1">{{ contact.name }}</span>
                </p>
            </div>
        </div>
        <div class="form-row mt-3 collapse" :id="'contactInfo'+contact.id">
            <div class="form-group col-md-6">
                <label for="name">Nombre</label>
                <input v-model="contact_form.name" type="text" class="form-control form-control-lg" id="name" :readonly="!editmode">
            </div>
            <div class="form-group col-md-6">
                <label for="email">Correo electrónico</label>
                <input v-model="contact_form.email" type="email" class="form-control form-control-lg" id="email" :readonly="!editmode">
            </div>
            <div class="form-group col-md-6">
                <label for="name">Puesto</label>
                <input v-model="contact_form.position" type="text" class="form-control form-control-lg" id="position" :readonly="!editmode">
            </div>
            <div class="form-group col-md-6">
                <label for="phone">Teléfono</label>
                <input v-model="contact_form.phone" type="text" class="form-control form-control-lg" id="phone" :readonly="!editmode">
            </div>
            <div class="form-group col-md-12 text-center" v-show="editmode">
                <button class="btn btn-orange btn-rounded" @click="updateContact(contact)">Guardar datos</button>
                <button class="btn btn-secondary btn-rounded" @click="cancelEdit(contact)">Cancelar</button>
            </div>
        </div>
    </div>
</template>

<script>
    import NewContact from '../contact/NewContact'
    export default {
        props: {
            contact: Object,
        },
        components: {
            NewContact
        },
        data: () => ({
            editmode:false,
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
            deleteContact(contact){
                this.$Progress.start();
                swal.fire({
                    title: '¿Estás seguro?',
                    text: "¡No podrás revertir esto!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sí, eliminar contacto',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.value) {
                        axios.delete(`/api/contact/${contact.id}`)
                        .then((response)=>{
                            this.$Progress.finish();
                            this.$emit('deleteContact', response.data);
                            swal.fire('Eliminado', 'Contacto eliminado', 'success');
                        }).catch(()=> {
                            this.$Progress.fail();
                            swal("Error", "Algo salió mal", "warning");
                        })
                        
                    }
                })
            },
            cancelEdit(contact){
                $(`#contactInfo${contact.id}`).collapse('hide')
                this.editmode = false
            },
            editContact(contact){
                $(`#contactInfo${contact.id}`).collapse('show')
                this.editmode = true
            },
            updateContact(contact){
                this.$Progress.start();
                this.contact_form.put(`/api/contact/${this.contact_form.id}`)
                .then((response) => {
                    toast.fire({
                        type: 'success',
                        title: `Información actualizada correctamente.`
                    })
                    this.$emit('updateContact', response.data);
                    this.$Progress.finish();
                    this.editmode = false;
                })
                .catch(() => {
                    this.$Progress.fail();
                    toast.fire({
                        type: 'error',
                        title: `Error al actualizar proyecto.`
                    })
                })
            },
        },
        created(){
            this.contact_form.fill(this.contact)
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
        border-radius: 50%;
        padding: 7px;
    }

    .list-header{
        transition: all 0.2s ease-out !important;
        &:hover {
            background-color: #E8E8E8;
        }
    }
    .badge-circle[data-v-4fb95116] {
        border-radius: 50%;
        width: 20px;
        height: 20px;
        padding-top: 5px;
        padding-left: 4px;
        padding-right: 4px;
    }
</style>