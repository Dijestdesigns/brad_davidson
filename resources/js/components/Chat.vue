<template>
    <div>
        <div class="group-rom" v-for="(chatMessage, key, index) in conversations">
            <div class="first-part">
                {{ chatMessage.user.name }} {{ chatMessage.user.surname }}
            </div>
            <div class="second-part">
                {{ chatMessage.message }}
            </div>
            <div class="third-part text-right">
                {{ chatMessage.created_at | formatDate }}
            </div>
        </div>
        <div class="group-rom" style="margin-bottom: 20px;">
            <div class="first-part">&nbsp;</div>
            <div class="second-part">&nbsp;</div>
            <div class="third-part text-right">&nbsp;</div>
        </div>
        <footer>
            <form>
                <div class="input-group input-group-lg">
                    <div class="row">
                        <div class="col-md-11 col-xs-9">
                            <input type="text" class="form-control" placeholder="Type your message here..." v-model="message" autofocus />
                        </div>
                        <div class="col-md-1 col-xs-3">
                            <div class="input-group-btn">
                                <button class="btn btn-theme" @click.prevent="store()">Send</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </footer>
    </div>
</template>

<script>
    export default {
        props: ['room', 'users', 'userId', 'chatMessages'],

        data() {
            return {
                message: '',
                room_id: (typeof this.room !== typeof undefined && this.room.id != "") ? this.room.id : "",
                conversations: []
            }
        },

        mounted() {
            this.conversations = this.chatMessages;
            this.listenForNewMessage();
        },

        methods: {
            scrollToElement() {
                setTimeout(() => {
                    const el = this.$el.getElementsByClassName('group-rom')[this.$el.getElementsByClassName("group-rom").length - 1];

                    if (el) {
                        el.scrollIntoView();
                    }
                }, 50);
            },

            desktopNotification(data) {
                if (!('Notification' in window)) {
                    console.log('Web Notification is not supported');

                    return false;
                }

                let title = data.user.name + (typeof data.user !== typeof undefined && typeof data.user.surname !== null && data.user.surname.length > 0 ? ' ' + data.user.surname : '') + ' send you message.';

                Notification.requestPermission(permission => {
                    let notification = new Notification(title, {
                        body: data.message,
                        icon: data.profile_photo
                    });

                    // link to page on clicking the notification
                    notification.onclick = () => {
                        window.open(window.location.href);
                    };
                });
            },

            store() {
                if (typeof this.room !== typeof undefined && this.room.id != "") {
                    axios.post('/chat/room', {message: this.message, chat_room_id: this.room.id})
                    .then((response) => {
                        this.message = '';
                        this.conversations.push(response.data);

                        this.scrollToElement();
                    });
                } else {
                    axios.post('/chat/individual', {message: this.message, user_id: this.users.id})
                    .then((response) => {
                        this.message = '';
                        this.conversations.push(response.data);

                        this.scrollToElement();
                    });
                }
            },

            markAsRead(data) {
                axios.post('/chat/markAsRead/' + data.chat_id)
                .then((response) => {});
            },

            listenForNewMessage() {
                if (typeof this.room !== typeof undefined && this.room.id != "") {
                    Echo.private('rooms.' + this.room.id)
                    .listen('NewMessage', (e) => {
                        if (typeof e !== typeof undefined && Object.values(e).length > 0) {
                            this.conversations.push(e);

                            this.desktopNotification(e);

                            this.markAsRead(e);

                            this.scrollToElement();
                        }
                    });
                } else {
                    Echo.private('users.' + this.userId + '.' + this.users.id)
                    .listen('NewMessageIndividual', (e) => {
                        if (typeof e !== typeof undefined && Object.values(e).length > 0) {
                            this.conversations.push(e);

                            this.desktopNotification(e);

                            this.markAsRead(e);

                            this.scrollToElement();
                        }
                    });
                }
            }
        }
    }
</script>
