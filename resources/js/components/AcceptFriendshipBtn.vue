<template>
    <div class="d-flex justify-content-between bg-light p-3 rounded mb-3 shadow-sm">
        <div>
            <div v-if="localfriendshipStatus === 'pending'">
                <span v-text="sender.name"></span> te ha enviado una solicitud de amistad
            </div>
            <div v-if="localfriendshipStatus === 'accepted'">
                Tú y <span v-text="sender.name"></span> son amigos 
            </div>
            <div v-if="localfriendshipStatus === 'denied'">
                Solicitud denegada de <span v-text="sender.name"></span>
            </div>
            <div v-if="localfriendshipStatus === 'deleted'">
                Solicitud eliminada de <span v-text="sender.name"></span>
            </div>
        </div>
        <div>
            <button class="btn btn-small btn-primary" v-if="localfriendshipStatus === 'pending'" dusk="accept-friendship" @click="acceptFriendshipRequest">Aceptar solicitud</button>
            <button class="btn btn-small btn-warning" v-if="localfriendshipStatus === 'pending'" dusk="deny-friendship" @click="denyFriendshipRequest">Denegar solicitud</button>
            <button class="btn btn-small btn-danger" v-if="localfriendshipStatus !== 'deleted'" dusk="delete-friendship" @click="deleteFriendship">Eliminar</button>

        </div>
    </div>
</template>

<script>
    export default {
        props: {
            sender:{
                type: Object,
                required: true,
            },
            friendshipStatus:{
                type: String,
                required: true,
            }
        },
        data(){
            return {
                localfriendshipStatus : this.friendshipStatus
            }
        },

        methods: {
            acceptFriendshipRequest(){
                axios.post(`/accept-friendships/${this.sender.name}`)
                    .then(res => {
                        this.localfriendshipStatus = res.data.friendship_status
                    })
                    .catch(err => {
                        console.log(err.response.data)
                    })
            },

            denyFriendshipRequest(){
                axios.delete(`/accept-friendships/${this.sender.name}`)
                    .then(res => {
                        this.localfriendshipStatus = res.data.friendship_status
                    })
                    .catch(err => {
                        console.log(err.response.data)
                    })
            },

            deleteFriendship(){
                axios.delete(`/friendships/${this.sender.name}`)
                    .then(res => {
                        this.localfriendshipStatus = res.data.friendship_status
                    })
                    .catch(err => {
                        console.log(err.response.data)
                    })
            }
        }
    }
</script>

<style lang="">
    
</style>