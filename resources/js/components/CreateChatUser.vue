<template>
    <div>
        <div class="row">
            <div class="col-md-12">
                <label class="col-md-12 col-xs-12">Select User</label>

                <div class="col-md-12 col-xs-12">
                    <select v-model="users" class="form-control" name="user_id">
                        <option value="">Select User</option>

                        <option v-for="user in initialUsers" :value="user.id">
                            {{ user.name }} {{ user.surname }}
                        </option>
                    </select>
                </div>
            </div>
        </div>

        <br />
        <div class="row">
            <div class="col-md-12">
                <div class="col-md-12 col-xs-12">
                    <button class="btn btn-primary" type="submit" @click.prevent="createChatRoom"><i class="fa fa-save"></i></button>
                    <button type="button" class="btn btn-secondary btn-default bootbox-cancel close-model" style="float: none;"><i class="fa fa-close"></i></button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['initialUsers'],

        data() {
            return {
                name: '',
                users: []
            }
        },

        methods: {
            createChatRoom() {
                axios.post('/chat/store', {name: this.name, users: this.users})
                .then((response) => {
                    this.name = '';
                    this.users = [];
                    Bus.$emit('chatRoomCreated', response.data);
                });
            }
        }
    }
</script>
