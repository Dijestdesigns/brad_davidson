<template>
    <div>
        <lobby-chat v-for="lobby in lobbies" :lobby="lobby" :key="lobby.id"></lobby-chat>
    </div>
</template>

<script>
    export default {
        props: ['initialLobbies', 'user'],

        data() {
            return {
                lobbies: []
            }
        },

        mounted() {
            this.lobbies = this.initialGroups;

            Bus.$on('lobbyCreated', (lobby) => {
                this.lobbies.push(lobby);
            });

            this.listenForNewLobbies();
        },

        methods: {
            listenForNewLobbies() {
                Echo.private('users.' + this.user.id)
                    .listen('LobbyCreated', (e) => {
                        this.lobbies.push(e.lobby);
                    });
            }
        }
    }
</script>
