<template>
    <div class="dropdown-item d-flex align-items-center"
        :class="isRead ? '' : 'bg-light'"
    >
        <a :dusk="notification.id"
           :href="notification.data.link"
           class="dropdown-item"
        > {{ notification.data.message }} </a>

        <button v-if="isRead"
            class="btn btn-link mr-2"
            @click.stop="markAsUnread"
            :dusk="`mark-as-unread-${notification.id}`"
        >
            <i class="far fa-circle"></i>
            <span class="position-absolute bg-dark text-white ml-2 py-2 px-2 rounded"> Marcar como NO Leída </span>
        </button>
        <button v-else 
            class="btn btn-link mr-2"
            @click.stop="markAsRead"
            :dusk="`mark-as-read-${notification.id}`"
        >
            <i class="fas fa-circle"></i>
            <span class="position-absolute bg-dark text-white ml-2 py-2 px-2 rounded"> Marcar como Leída </span>
        </button>
    </div>
</template>

<script>
    export default {
        props: {
            notification: Object,
        },
        data () {
            return {
                isRead: !! this.notification.read_at
            }
        },
        methods: {
            markAsRead() {
                axios.post(`/read-notifications/${this.notification.id}`)
                     .then(res => {
                         this.isRead = true;
                         EventBus.$emit('notifications-read');
                     })
            },
            markAsUnread() {
                axios.delete(`/read-notifications/${this.notification.id}`)
                     .then(res => {
                         this.isRead = false;
                         EventBus.$emit('notifications-unread');
                     })
            }
        }
    }
</script>

<style lang="scss" scoped>

    button > span {
        display: none;
    }

    button i {
        &:hover {
            & + span {
                display: inline;
            }
        }
    }

</style>