<template>
    <v-app>
        <v-dialog v-model="settingsDialog" persistent max-width="500px">
            <v-card>
                <v-card-title>
                    User Settings
                </v-card-title>
                <v-card-text>
                    <message-bus :bus-uuid="storeSettingsFormUuid"></message-bus>
                    <v-text-field
                            label="Password"
                            v-model="password"
                            hint="At least 8 characters"
                            min="8"
                            counter
                            :type="revealPassword ? 'password' : 'text'"
                            :append-icon="revealPassword ? 'visibility' : 'visibility_off'"
                            :append-icon-cb="() => (revealPassword = !revealPassword)"
                            :error-messages="fieldErrors(storeSettingsFormUuid, 'password')"
                    ></v-text-field>
                    <v-text-field
                            label="Password Confirmation"
                            v-model="passwordConfirmation"
                            counter
                            type="password"
                            :type="revealConfirmation ? 'password' : 'text'"
                            :append-icon="revealConfirmation ? 'visibility' : 'visibility_off'"
                            :append-icon-cb="() => (revealConfirmation = !revealConfirmation)"
                            :error-messages="fieldErrors(storeSettingsFormUuid, 'password_confirmation')"
                    ></v-text-field>
                </v-card-text>
                <v-card-actions>
                    <v-btn color="primary" flat @click.stop="closePassword" :disabled="storeSettingsSubmitting">Close</v-btn>
                    <v-spacer></v-spacer>
                    <v-btn color="green" flat :loading="storeSettingsSubmitting" @click="doSettingsSubmit">Update</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <v-navigation-drawer
                fixed
                :clipped="true"
                v-model="drawer"
                app
        >
            <v-list>
                <v-list-tile
                        value="true"
                        v-for="(item, i) in items"
                        :key="i"
                        :to="item.path"
                        :replace="true"
                >
                    <v-list-tile-action>
                        <v-icon v-html="item.icon"></v-icon>
                    </v-list-tile-action>
                    <v-list-tile-content>
                        <v-list-tile-title v-text="item.title"></v-list-tile-title>
                    </v-list-tile-content>
                </v-list-tile>
            </v-list>
        </v-navigation-drawer>
        <v-toolbar fixed app :clipped-left="true" color="light-blue">
            <v-toolbar-side-icon @click.stop="drawer = !drawer"></v-toolbar-side-icon>
            <v-toolbar-title v-text="title"></v-toolbar-title>
            <v-spacer></v-spacer>
            <v-btn icon @click="settingsDialog = true">
                <v-icon>settings</v-icon>
            </v-btn>
            <v-btn icon href="/logout">
                <v-icon>lock</v-icon>
            </v-btn>
        </v-toolbar>
        <v-content fluid>
            <router-view></router-view>
        </v-content>
        <v-footer :fixed="true" app>
            <span>&copy; 2018 <strong>AgentRef</strong>.com</span>
        </v-footer>
    </v-app>
</template>

<script>
    import { mapActions, mapGetters, mapState, mapMutations } from 'vuex'
    import MessageBus from 'Travel/components/MessageBus'

    export default {
        data () {
            return {
                clipped: true,
                drawer: false,
                fixed: true,
                items: [
                    {
                        icon: 'library_books',
                        title: 'Knowledge Base',
                        path: '/kb'
                    },
                    {
                        icon: 'search',
                        title: 'Search',
                        path: '/search'
                    },
                    {
                        icon: 'mode_edit',
                        title: 'Content Manager',
                        path: '/content/manage'
                    }
                ],
                right: true,
                rightDrawer: false,
                title: 'AgentRef',
                settingsDialog: false,
                revealPassword: true,
                revealConfirmation: true
            }
        },
        methods: {
            ...mapActions({
                loadPermissions: 'auth/permissions',
                syncContent: 'content/syncContent',
                settingsSubmit: 'settings/submit'
            }),
            doFetches () {
                this.loadPermissions()
                this.syncContent()
            },
            ...mapMutations('settings', {
                updatePassword: 'UPDATE_PASSWORD',
                updatePasswordConfirmation: 'UPDATE_PASSWORD_CONFIRMATION'
            }),
            doSettingsSubmit () {
                return this.settingsSubmit()
            },
            closePassword () {
                this.updatePassword({ password: null })
                this.updatePasswordConfirmation({ password_confirmation: null })
                this.settingsDialog = false
                this.revealPassword = true
                this.revealConfirmation = true
            }
        },
        computed: {
            ...mapGetters({
                hasPermission: 'auth/hasPermission',
                hasRole: 'auth/hasRole',
                authenticated: 'auth/authenticated',
                fieldErrors: 'validation/errors'
            }),
            ...mapState('settings', {
                storePassword: state => state.resetPassword.password,
                storePasswordConfirmation: state => state.resetPassword.password_confirmation,
                storeSettingsFormUuid: state => state.resetPassword.formUuid,
                storeSettingsSubmitting: state => state.resetPassword.submitting
            }),
            password: {
                get () {
                    return this.storePassword
                },
                set (value) {
                    this.updatePassword({ password: value })
                }
            },
            passwordConfirmation: {
                get () {
                    return this.storePasswordConfirmation
                },
                set (value) {
                    this.updatePasswordConfirmation({ password_confirmation: value })
                }
            }
        },
        mounted () {
            this.doFetches()
        },
        components: {
            'message-bus': MessageBus
        }
    }
</script>