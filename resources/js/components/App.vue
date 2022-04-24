<template>
    <div class="container">
        <div v-for="pensee in pensees">
            <h4>{{pensee.user.name}}</h4>
            <p>{{pensee.text}}</p>
            <p>{{pensee.created_at}}</p>
        </div>
    </div>
</template>

<script>
    export default {
        resource: null,
        data(){
            return {
                pensees: {}
            }
        },

        mounted() {
            this.resource = this.$resource('/pensees/{/id}')
            this.resource.get().then((response) => {
                this.pensees = response.body
            })
        }
    }
</script>
