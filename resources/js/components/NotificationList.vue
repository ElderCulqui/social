<template>
    <li class="nav-item dropdown">
        <a dusk="notifications"
            href="#" 
            class="nav-link dropdown-toggle"
            :class="count ? 'text-primary font-weight-bold' : ''"
            id="dropdownNotifications" 
            role="button" 
            data-toggle="dropdown" 
            aria-haspopup="true" 
            aria-expanded="false">
            <slot></slot> <span dusk="notifications-count">{{ count }}</span>   
        </a>
        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownNotifications">
            <div class="dropdown-header text-center">Notificaciones</div>
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
                notifications: [],
                count: ''
            }
        },
        created() {

            // Channel: private-App.User.1, 
            // Event: Illuminate\Notifications\Events\BroadcastNotificationCreated
            if (this.isAuthenticated) {
                Echo.private(`App.User.${this.currentUser.id}`)
                    .notification(notification => {
                        this.count++;
                        this.notifications.push({
                            id: notification.id,
                            data: {
                                link: notification.link,
                                message: notification.message,
                            }
                        })
                    })
            }

            axios.get('/notifications')
                 .then(res => {
                    this.notifications = res.data;
                    this.unreadNotifications();
                 });
            
            EventBus.$on('notifications-read', () => {
                if (this.count === 1) {
                    return this.count = '';
                }

                this.count--;
            });

            EventBus.$on('notifications-unread', () => {
                this.count++;
            });
        },
        methods: {
            unreadNotifications() {
                this.count = this.notifications.filter(notification => { 
                    return notification.read_at === null; 
                }).length || ''
            }
        }
    }
</script>

<style>
    
</style>