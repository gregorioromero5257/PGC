<template>
    <div class="col-md-12 mt-3">
        <div class="card">
            <div class="card-header bg-white">
                <i @click="removeStackeholder(stackeholder)" class="fas fa-times-circle pointer float-right" data-toggle="tooltip" data-placement="top" :title="'Remover '+stackeholder.name+' de la selección'"></i>
                <p class="mb-0 font-weight-bold">{{ stackeholder.name }} <span class="badge badge-secondary">{{ stackeholder.sh_description.name }}</span></p>
                <hr class="mb-0 mt-3">
            </div>
            <div class="card-body pt-0">
                <div class="row">
                    <div class="col-md-12 mb-4" v-show="stackeholder.contacts.length > 0">
                        <p class="font-weight-bold mb-3">
                            Contactos
                        </p>
                        <ul class="list-group list-group-flush">
                            <li
                                class="list-group-item pt-0 pb-0 pl-3 pr-3"
                                is="contact"
                                v-for="contact in stackeholder.contacts"
                                :key="contact.id"
                                :contact="contact"
                                @deleteContact="deleteContact"
                                @updateContact="updateContact"
                            ></li>
                        </ul>
                    </div>
                    <NewContact
                        :stackeholder="stackeholder"
                        @addContact="addContact"
                    />
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import NewContact from '../contact/NewContact'
    import contact from '../contact/Contact'
    export default {
        props: {
            stackeholder: Object,
            form: Object
        },
        components: {
            contact,
            NewContact
        },
        data: () => ({

        }),
        methods: {
            updateContact(contact_selected){
                let contactIndex = '';
                contactIndex = this.stackeholder.contacts.findIndex((contact => contact.id == contact_selected.id));
                this.stackeholder.contacts[contactIndex].name = contact_selected.name;
                this.stackeholder.contacts[contactIndex].email = contact_selected.email;
                this.stackeholder.contacts[contactIndex].position = contact_selected.position;
                this.stackeholder.contacts[contactIndex].phone = contact_selected.phone;
            },
            deleteContact(contact_selected){
                this.stackeholder.contacts = this.stackeholder.contacts.filter(contact => contact.id != contact_selected.id);
            },
            addContact(contact){
                this.stackeholder.contacts.push(contact);
            },
            removeStackeholder(stackeholder_selected){
                if (stackeholder_selected.sh_type_id == 1) {
                    this.$props.form.clients = this.$props.form.clients.filter(client => client.id != stackeholder_selected.id); 
                } else if (stackeholder_selected.sh_type_id == 2) {
                    this.$props.form.funders = this.$props.form.funders.filter(funder => funder.id != stackeholder_selected.id); 
                } else {
                    this.$props.form.providers = this.$props.form.providers.filter(provider => provider.id != stackeholder_selected.id); 
                }
            }
        },
        created(){

        }
    }
</script>

<style lang="scss" scoped>
    .card-header{
        padding: 0.75rem 1.25rem;
        margin-bottom: 0;
        border-bottom: 0px;
    }
</style>