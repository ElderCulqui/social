<template>
    <div>
        <div class="card mb-3 border-0 shadow-sm">
            <div class="card-body d-flex flex-column">
                <div class="d-flex align-items-center mb-3">
                    <img class="rounded mr-3 shadow-sm" width="40px" src="https://encrypted-tbn0.gstatic.com/images?q=tbn%3AANd9GcT8wsXoAeQVQpY2jv1uekQY5FOffdqL_stDYTYfBkmV1Q4zuN0I" alt="">
                    <div class="">
                        <h5 class="mb-1" v-text="status.user_name"></h5>
                        <div class="small text-muted" v-text="status.ago"></div>
                    </div>
                </div>
                <p class="card-text text-secondary" v-text="status.body"></p>
            </div>
            <div class="card-footer p-2 d-flex justify-content-between align-items-center">
                <like-btn
                    dusk="like-btn"
                    :url="`/statuses/${status.id}/likes`"
                    :model="status"
                ></like-btn>
                <div class="text-secondary mr-2">
                    <i class="far fa-thumbs-up"></i>
                    <span dusk="likes-count">{{ status.likes_count}}</span>
                </div>
            </div>
            <div class="card-footer">

                <div v-for="comment in comments" :key=comment.id class="mb-3">
                    <img class="rounded shadow-sm float-left mr-2" width="34px" :src="comment.user_avatar" :alt="comment.user_name">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-2 text-secondary">
                            <a href="#"><strong>{{ comment.user_name }}</strong></a>
                            {{ comment.body }}
                        </div>
                        <span dusk="comment-likes-count">{{ comment.likes_count }}</span>

                        <like-btn
                            dusk="comment-like-btn"
                            :url="`/comments/${comment.id}/likes`"
                            :model="comment"
                        ></like-btn>
                    </div>
                </div>
                <form @submit.prevent="addComment" v-if="isAuthenticated">
                    <div class="d-flex align-items-center">
                        <img class="rounded shadow-sm mr-2" width="34px" 
                            src="data:image/jpeg;base64,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" 
                            :alt="currentUser.name">
                        
                        <div class="input-group">
                            <textarea v-model="newComment"
                                    class="form-control border-0 shadow-sm"  
                                    name="comment" 
                                    rows="1"
                                    placeholder="Escrbie un comentario..."
                                    required>
                            </textarea>
                            <div class="input-group-append">
                                <button class="btn btn-primary" dusk="comment-btn">Enviar</button>
                            </div>

                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
import LikeBtn from './LikeBtn'
export default {
    props: {
        status: {
            type: Object,
            required: true
        },
    },
    components: { LikeBtn },
    data() {
        return {
            newComment: '',
            comments: this.status.comments
        }
    },
    methods: {
        addComment(){
            axios.post(`/statuses/${this.status.id}/comments`, {body: this.newComment})
                    .then(res => {
                        this.newComment = '';
                        this.comments.push(res.data.data);
                    })
                    .catch(err => {
                        console.log(err.response.data);
                    })
        },
    },

}
</script>

<style scoped>
    
</style>