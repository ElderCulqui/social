<template>
    <li class="nav-item dropdown">
        <a dusk="notifications"
            href="#" 
            class="nav-link dropdown-toggle" 
            id="dropdownNotifications" 
            role="button" 
            data-toggle="dropdown" 
            aria-haspopup="true" 
            aria-expanded="false">
            <slot></slot>    
        </a>
        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownNotifications">
            <div v-if="notifications.length > 0">
                <a v-for="notification of notifications"
                   :key="notification.id"
                   :dusk="notification.id"
                   :href="notification.data.link"
                   class="dropdown-item"
                > {{ notification.data.message }} </a>
            </div>
        </div>
    </li>
</template>

<script>
    export default {
        data() {
            return {
                notifications: []
            }
        },
        created() {
            axios.get('/notifications')
                 .then(res => {
                    console.log(res.data)
                    this.notifications = res.data;
                 })
        },
    }
</script>

<style>
    
</style>