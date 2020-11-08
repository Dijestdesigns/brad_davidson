<template>
    <div>
        <div class="group-rom" v-for="(chatMessage, key, index) in conversations">
            <div class="first-part">
                {{ chatMessage.user.name }} {{ chatMessage.user.surname }}
            </div>
            <div class="second-part">
                <span v-html="chatMessage.message"></span>
                <br />
                <img :src="chatMessage.file" />
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
            <form enctype="multipart/form-data">
                <div>
                    <div class="row">
                        <div class="col-md-11 col-xs-9">
                            <div class="input-group">
                                <input type="text" class="form-control w-87 emojis" placeholder="Type your message here..." v-model="message" autofocus />
                                <div class="input-group-append">
                                    <!-- <span type="button" class="btn btn-xs btn-primary p-10"><i class="fa fa-meh-o"></i></span> -->
                                    <label for="file" class="btn btn-xs btn-primary p-10"><i class="fa fa-paperclip"></i></label>
                                </div>
                            </div>
                            <input type="file" class="form-control d-none" id="file" :ref="fileFieldName" @change="store()" accept="image/*" :name="fileFieldName" />
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
                conversations: [],
                fileFieldName: 'file'
            }
        },

        mounted() {
            this.conversations = this.chatMessages;
            this.listenForNewMessage();

            let self = this;

            setTimeout(function() {
                $(".emojionearea .emojionearea-editor").on("keydown", function (e) {
                    let key = e.which;

                    if (key == 13) {
                        self.store();
                        return false;
                    }
                });
            }, 2000);
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

                if (typeof data.user !== typeof undefined && data.user.surname !== null) {
                    var title = data.user.name + ' ' + data.user.surname + ' send you message.';
                } else {
                    var title = data.user.name + ' send you message.';
                }

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
                    axios.post('/chat/room', this.getDataRoom())
                    .then((response) => {
                        this.message = '';
                        $(".emojionearea-editor").html('');
                        this.conversations.push(response.data);

                        this.scrollToElement();
                    });
                } else {
                    axios.post('/chat/individual', this.getDataIndividual())
                    .then((response) => {
                        this.message = '';
                        $(".emojionearea-editor").html('');
                        this.conversations.push(response.data);

                        this.scrollToElement();
                    });
                }
            },

            getDataRoom() {
                const data = new FormData();

                this.message = $(".emojionearea-editor").html();

                data.append('message', this.message);
                data.append('chat_room_id', this.room.id);
                data.append('file', this.$refs.file.files[0]);

                return data;
            },

            getDataIndividual() {
                const data = new FormData();

                this.message = $(".emojionearea-editor").html();

                data.append('message', this.message);
                data.append('user_id', this.users.id);
                data.append('file', this.$refs.file.files[0]);

                return data;
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
