<template>
    <button 
        @click="sendFriendshipRequest()"
        dusk="request-friendship">
        {{ textBtn }}
    </button>
</template>

<script>
    export default {
        props: {
            recipient: {
                type: Object,
                require: true
            }
        },

        data(){
            return {
                textBtn : 'Enviar solicitud de amistad'
            }
        },

        methods: {
            sendFriendshipRequest(){
                axios.post(`friendships/${this.recipient.name}`)
                    .then(res => {
                        this.textBtn = 'Solicitud enviada';
                    })
                    .catch(err => {
                        console.log(err.response.data);      
                    })
            }
        }
    }
</script>

<style lang="" scoped>
    
</style>