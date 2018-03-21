<template>
    <div>
        <v-alert
                v-for="(message, i) in messages"
                :type="message.type"
                :key="message.messageUuid"
                :value="message.value === true"
                @input="dismiss({ messageUuid: message.messageUuid })"
                dismissible
                transition="slide-x-transition"
        >
            {{ message.message }}
        </v-alert>
    </div>
</template>

<script>
    import { mapGetters, mapActions } from 'vuex'

    export default {
        props: ['busUuid'],
        computed: {
            ...mapGetters({
                getMessages: 'messages/messages'
            }),
            messages () {
                return this.getMessages(this.busUuid)
            }
        },
        methods: {
            ...mapActions({
                dismiss: 'messages/dismiss'
            })
        }
    }
</script>
