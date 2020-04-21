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
            <notification-list-item
                v-for="notification of notifications"
                :notification="notification"
                :key="notification.id"
            ></notification-list-item>
        </div>
    </li>
</template>

<script>
    import NotificationListItem from './NotificationListItem'

    export default {
        components: { NotificationListItem },
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