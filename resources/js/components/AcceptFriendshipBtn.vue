<template>
    <div>
        <div v-if="localfriendshipStatus === 'pending'">
            <span v-text="sender.name"></span> te ha enviado una solicitud de amistad
            <button @click="acceptFriendshipRequest">Aceptar solicitud</button>
            <button dusk="deny-friendship" @click="denyFriendshipRequest">Denegar solicitud</button>
        </div>
        <div v-else-if="localfriendshipStatus === 'accepted'">
        Tú y <span v-text="sender.name"></span> son amigos 
        </div>
        <div v-else-if="localfriendshipStatus === 'denied'">
        Solicitud denegada de <span v-text="sender.name"></span>
        </div>
        <div v-if="localfriendshipStatus === 'deleted'">Solicitud eliminada</div>
        <button v-else dusk="delete-friendship" @click="deleteFriendship">Eliminar</button>
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