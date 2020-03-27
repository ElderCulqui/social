<template>
    <div>
        <div @click="redirectIfGuest">
            <status-list-item 
                v-for="status in statuses" 
                :key="status.id" 
                :status="status"
            ></status-list-item>
            
        </div>
    </div>
</template>

<script>
import StatusListItem from './StatusListItem'

export default {
    components: { StatusListItem },
    
    props: {
        url: String
    },
    
    data() {
        return {
            statuses: []
        }
    },
    
    mounted() {
        axios.get(this.getUrl)
            .then(res => {
                this.statuses = res.data.data;
            })
            .catch(err => {
                console.log(err.response.data);
            });
        EventBus.$on('status-created', status => {
            this.statuses.unshift(status);
        })
    },

    computed: {
        getUrl(){
            return this.url ? this.url : '/statuses';
        }
    }
}
</script>

<style scoped>

</style>