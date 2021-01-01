<template>
    <div class="nav notify-row" id="top_menu" style="float: right;margin-left: unset;">
        <ul class="nav top-menu">
            <li id="header_notification_bar" class="dropdown">
                <a data-toggle="dropdown" href="index.html#">
                    <i class="fa fa-bell-o"></i>
                    <span class="badge bg-theme">
                        {{ total }}
                    </span>
                </a>
                <ul class="dropdown-menu extended notification">
                    <div class="notify-arrow notify-arrow-green"></div>
                    <li>
                        <p class="green">You have 
                            <span>{{ total }}</span> 
                            new notifications
                        </p>
                    </li>
                    <li v-for="(notification, key, index) in notifications.datas.slice(0, 5)">
                        <a :href="notification.href + '?is_read=1&id=' + notification.id">
                            <span class="photo">
                                <img alt="avatar" :src="notification.send_by_user.profile_photo_icon">
                            </span>
                            <span class="subject">
                                <span class="from">{{ notification.title }}</span>
                                <span class="time" :id="'time-' + notification.id" v-dateshow="notification"></span>
                            </span>
                            <span class="message">
                                {{ notification.message }}
                            </span>
                        </a>
                    </li>
                    <li v-if="notifications.datas.length > 0">
                        <a href="notifications">See all notifications</a>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</template>

<script>
    export default {
        props: ['notificationsData', 'userId'],

        data() {
            return {
                notifications: this.notificationsData,
                total: this.notificationsData.datas.length
            }

            // return this.notifications;
        },

        mounted() {
            this.notifications = this.notificationsData;
            this.total         = this.notificationsData.datas.length;

            this.listenForNewNotification();
            this.listenForReadNotification();
        },

        methods: {
            listenForNewNotification() {
                Echo.private('notifications.' + this.userId)
                .listen('Notifications', (e) => {
                    if (typeof e !== typeof undefined && Object.values(e).length > 0) {
                        this.notifications.datas.splice(0, 0, e);
                        this.total++;
                    }
                });
            },

            listenForReadNotification() {
                Echo.private('dashboard-read-notifications')
                .listen('Notifications', (e) => {
                    if (typeof e !== typeof undefined && Object.values(e).length > 0) {
                        this.notifications.datas = e;
                    }
                });
            }
        },

        directives: {
            dateshow : function (el, user) {
                let date = moment(user.value.updated_at).fromNow();

                el.innerText = date;
            }
        }
    }
</script>
