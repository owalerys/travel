<template>
    <v-container fluid v-show="activeMessages">
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
    </v-container>
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
            },
            activeMessages () {
                for (let i = 0; i < this.messages.length; i++) {
                    if (this.messages[i].value) {
                        return true
                    }
                }

                return false
            }
        },
        methods: {
            ...mapActions({
                dismiss: 'messages/dismiss'
            })
        }
    }
</script>
