<template>
    <v-app v-if="userRole">
        <v-alert
            v-if="editSuccess"
            type="success"
            dismissible
        >success
        </v-alert>
        <v-data-table
            :headers="headersAdmin"
            :items="users"
            item-key="items.id"
            sort-by="calories"
            class="elevation-1"
        >
            <template v-slot:top>
                <v-toolbar
                    flat
                >
                    <v-toolbar-title>All Users</v-toolbar-title>
                    <v-divider
                        class="mx-4"
                        inset
                        vertical
                    ></v-divider>
                    <v-spacer></v-spacer>
                    <v-dialog
                        v-model="dialogOptions"
                        max-width="500px"
                    >
                        <template v-slot:activator="{ on, attrs }">
                            <v-btn
                                color="primary"
                                dark
                                class="mb-2"
                                v-bind="attrs"
                                v-on="on"
                                @click="optionsIndex = 0"
                            >
                                Add Country
                            </v-btn>
                            <v-btn
                                color="primary"
                                dark
                                class="mb-2"
                                v-bind="attrs"
                                v-on="on"
                                @click="optionsIndex = 1"
                            >
                                Add Roles
                            </v-btn>
                        </template>
                        <v-card>
                            <v-card-title>
                                <span class="text-h5">{{ formRoles }}</span>
                            </v-card-title>
                            <v-card-text>
                                <v-col
                                    cols="1"
                                    sm="12"
                                    md="12"
                                >
                                    <v-text-field
                                        v-model="options.name"
                                        label="Name"
                                    ></v-text-field>
                                </v-col>
                            </v-card-text>
                            <v-card-actions>
                                <v-spacer></v-spacer>
                                <v-btn
                                    color="blue darken-1"
                                    text
                                    @click="closeOptions"
                                >
                                    Cancel
                                </v-btn>
                                <v-btn
                                    color="blue darken-1"
                                    text
                                    @click="saveOptions"
                                >
                                    Save
                                </v-btn>
                            </v-card-actions>
                        </v-card>

                    </v-dialog>
                    <v-dialog
                        v-model="dialog"
                        max-width="500px"
                    >
                        <template v-slot:activator="{ on, attrs }">
                            <v-btn
                                color="primary"
                                dark
                                class="mb-2"
                                @click="logout"
                            >
                                Logout
                            </v-btn>

                            <v-btn
                                color="primary"
                                dark
                                class="mb-2"
                                v-bind="attrs"
                                v-on="on"
                            >
                                Add New User
                            </v-btn>
                        </template>
                        <v-card>
                            <v-card-title>
                                <span class="text-h5">{{ formTitle }}</span>
                            </v-card-title>

                            <v-card-text>
                                <v-container>
                                    <v-row>
                                        <v-col
                                            cols="12"
                                            sm="6"
                                            md="4"
                                        >
                                            <v-text-field
                                                v-model="editedUser.first_name"
                                                label="firstname"
                                            ></v-text-field>
                                        </v-col>
                                        <v-col
                                            cols="12"
                                            sm="6"
                                            md="4"
                                        >
                                            <v-text-field
                                                v-model="editedUser.last_name"
                                                label="surname"
                                            ></v-text-field>
                                        </v-col>
                                        <v-col
                                            cols="12"
                                            sm="6"
                                            md="4"
                                        >
                                            <v-text-field
                                                v-model="editedUser.login"
                                                label="login"
                                            ></v-text-field>
                                        </v-col>
                                        <v-col
                                            cols="12"
                                            sm="6"
                                            md="4"
                                        >
                                            <v-text-field
                                                v-model="editedUser.password"
                                                type="password"
                                                label="password"
                                            ></v-text-field>
                                        </v-col>
                                        <v-col
                                            cols="12"
                                            sm="6"
                                            md="4"
                                        >
                                            <v-select
                                                v-model="editedUser.country_id"
                                                :items="countrys"
                                                item-text="name"
                                                item-value="id"
                                                label="Country"
                                            ></v-select>
                                        </v-col>
                                        <v-col
                                            cols="12"
                                            sm="6"
                                            md="4"
                                        >
                                            <v-select
                                                v-model="editedUser.roles_id"
                                                :items="roles"
                                                item-text="name"
                                                item-value="id"
                                                label="Roles"
                                            ></v-select>
                                        </v-col>
                                    </v-row>
                                </v-container>
                            </v-card-text>
                            <div v-if="editErrors" class=" rounded mb-4 shadow-lg is-danger">
                                <div v-for="(v, k) in editErrors" :key="k" style="text-align: center">
                                <span v-for="error in v" :key="error" class="error--text">
                                    {{ error }}
                                </span>
                                </div>
                            </div>
                            <v-card-actions>
                                <v-spacer></v-spacer>
                                <v-btn
                                    color="blue darken-1"
                                    text
                                    @click="close"
                                >
                                    Cancel
                                </v-btn>
                                <v-btn
                                    color="blue darken-1"
                                    text
                                    @click="save"
                                >
                                    Save
                                </v-btn>
                            </v-card-actions>
                        </v-card>
                    </v-dialog>
                    <v-dialog v-model="dialogDelete" max-width="500px">
                        <v-card>
                            <v-card-title class="text-h5">Are you sure you want to delete this item?</v-card-title>
                            <v-card-actions>
                                <v-spacer></v-spacer>
                                <v-btn color="blue darken-1" text @click="closeDelete">Cancel</v-btn>
                                <v-btn color="blue darken-1" text @click="deleteItemConfirm">OK</v-btn>
                                <v-spacer></v-spacer>
                            </v-card-actions>
                        </v-card>
                    </v-dialog>
                </v-toolbar>
            </template>
            <template v-slot:item.actions="{ item }">
                <v-icon small class="mr-2" @click="editItem(item)">
                    mdi-pencil
                </v-icon>
                <v-icon small class="mr-2" @click="deleteItem(item)">
                    mdi-delete
                </v-icon>
            </template>
        </v-data-table>
    </v-app>


    <v-app v-else>
        <v-alert
            v-if="editSuccess"
            type="success"
            dismissible
        >success
        </v-alert>
        <v-data-table
            :headers="headersUser"
            :items="currentUser"
            item-key="items.id"
            sort-by="calories"
            class="elevation-1"
        >
            <template v-slot:top>
                <v-toolbar
                    flat
                >
                    <v-toolbar-title>Your Profile</v-toolbar-title>
                    <v-divider
                        class="mx-4"
                        inset
                        vertical
                    ></v-divider>
                    <v-spacer></v-spacer>
                    <template >
                        <v-btn
                            color="primary"
                            dark
                            class="mb-2"
                            @click="logout"
                        >
                            Logout
                        </v-btn>
                    </template>
                    <v-dialog
                        v-model="dialog"
                        max-width="500px"
                    >
                        <v-card>
                            <v-card-title>
                                <span class="text-h5">{{ formTitle }}</span>
                            </v-card-title>

                            <v-card-text>
                                <v-container>
                                    <v-row>
                                        <v-col
                                            cols="12"
                                            sm="6"
                                            md="4"
                                        >
                                            <v-text-field
                                                v-model="editedUser.first_name"
                                                label="firstname"
                                            ></v-text-field>
                                        </v-col>
                                        <v-col
                                            cols="12"
                                            sm="6"
                                            md="4"
                                        >
                                            <v-text-field
                                                v-model="editedUser.last_name"
                                                label="surname"
                                            ></v-text-field>
                                        </v-col>
                                        <v-col
                                            cols="12"
                                            sm="6"
                                            md="4"
                                        >
                                            <v-text-field
                                                v-model="editedUser.login"
                                                label="login"
                                            ></v-text-field>
                                        </v-col>
                                        <v-col
                                            cols="12"
                                            sm="6"
                                            md="4"
                                        >
                                            <v-text-field
                                                v-model="editedUser.password"
                                                type="password"
                                                label="password"
                                            ></v-text-field>
                                        </v-col>
                                        <v-col
                                            cols="12"
                                            sm="6"
                                            md="4"
                                        >
                                            <v-select
                                                v-model="editedUser.country_id"
                                                :items="countrys"
                                                item-text="name"
                                                item-value="id"
                                                label="Country"
                                            ></v-select>
                                        </v-col>
                                    </v-row>
                                </v-container>
                            </v-card-text>

                            <v-card-actions>
                                <v-spacer></v-spacer>
                                <v-btn
                                    color="blue darken-1"
                                    text
                                    @click="close"
                                >
                                    Cancel
                                </v-btn>
                                <v-btn
                                    color="blue darken-1"
                                    text
                                    @click="save"
                                >
                                    Save
                                </v-btn>
                            </v-card-actions>
                        </v-card>
                    </v-dialog>
                </v-toolbar>
            </template>
            <template v-slot:item.actions="{ item }">
                <v-icon small class="mr-2" @click="editItem(item)">
                    mdi-pencil
                </v-icon>
            </template>
        </v-data-table>
    </v-app>


</template>


<script>
export default {
    data: () => ({
        dialogOptions: false,
        dialog: false,
        dialogDelete: false,
        headersAdmin: [
            {text: 'ID', value: 'id', sortable: false, align: ' d-none'},
            {text: 'First name', value: 'first_name', sortable: false},
            {text: 'Surname', value: 'last_name'},
            {text: 'Login', value: 'login'},
            {text: 'Country', value: 'country.name'},
            {text: 'Roles', value: 'roles.name'},
            {text: 'Actions', value: 'actions', sortable: false},
        ],
        headersUser: [
            {text: 'ID', value: 'id', sortable: false, align: ' d-none'},
            {text: 'First name', value: 'first_name', sortable: false},
            {text: 'Surname', value: 'last_name'},
            {text: 'Login', value: 'login'},
            {text: 'Country', value: 'country.name'},
            {text: 'Actions', value: 'actions', sortable: false},
        ],
        users: [],
        userRole: false,
        currentUser: [],
        countrys: [],
        roles: [],
        options: {
            name: ''
        },
        editedIndex: -1,
        optionsIndex: 0,
        editedUser: {
            id: '',
            first_name: '',
            last_name: '',
            login: '',
            password: '',
            country_id: '',
            roles_id: '',
        },
        defaultItem: {
            name: '',
            calories: 0,
            fat: 0,
            carbs: 0,
            protein: 0,
        },
        editErrors:null,
        editSuccess:false
    }),

    computed: {
        formTitle() {
            return this.editedIndex === -1 ? 'New User' : 'Edit User'
        },
        formRoles() {
            return this.optionsIndex === 0 ? 'New Counrty' : 'New Roles'
        },
    },

    watch: {
        dialog(val) {
            val || this.close()
        },
        dialogOptions(val) {
            val || this.close()
        },
        dialogDelete(val) {
            val || this.closeDelete()
        },
    },

    created() {
        this.initialize()
    },

    methods: {
        initialize() {
            axios.get('/api/user', {withCredentials: true}).then((res) => {
                this.userRole = (res.data.roles_id === 1) ? true : false;
            }).catch((error) => {
                this.$router.push({name: "Home"})
            })
            axios.get('/api/country').then((res) => {
                this.countrys = res.data;
            })
            axios.get('/api/roles').then((res) => {
                this.roles = res.data;
            })
            axios.get('/api/getCurrentUser').then((res) => {
                this.currentUser = [res.data.user]

            }).catch((error) => {
                this.error = error.response.data.errors;
            })

        },

        editItem(item) {
            this.editedIndex = this.currentUser[0].roles_id === 1? this.users.indexOf(item) : this.currentUser.indexOf(item)
            this.editedUser = Object.assign(item)
            this.dialog = true
        },

        deleteItem(item) {
            this.editedIndex = this.users.indexOf(item)
            this.editedUser = Object.assign({}, item)
            this.dialogDelete = true
        },
        addRole() {
            axios.post('/api/addrole', this.options).then(() => {
            }).catch((error) => {
                this.error = error.response.data.errors;
            })
        },
        addCountry() {
            axios.post('/api/addcountry', this.options).then(() => {
            }).catch((error) => {
                this.error = error.response.data.errors;
            })
        },

        deleteItemConfirm() {
            this.deleteUser()
            axios.get('/api/getAllUser').then((res) => {
                this.users = res.data.user
            }).catch((error) => {
                this.error = error.response.data.errors;
            });
            this.closeDelete()
        },

        close() {
            this.dialog = false
            this.$nextTick(() => {
                this.editedUser = Object.assign({}, this.defaultItem)
                this.editedIndex = -1
            })
        },

        closeDelete() {
            this.dialogDelete = false
            this.$nextTick(() => {
                this.editedUser = Object.assign({}, this.defaultItem)
                this.editedIndex = -1
            })
        },

        save() {
            if (this.editedIndex !== -1) {

                axios.post('/api/edituser', this.editedUser).then((res) => {
                    if(res.status == 201){
                        this.editSuccess = true;
                        axios.get('/api/getAllUser').then((res) => {
                            this.users = res.data.user
                        }).catch((error) => {
                            this.error = error.response.data.errors;
                        });
                        this.dialog = false;
                    }
                }).catch((error) => {
                    this.editErrors = error.response.data.errors;
                    console.log(this.editErrors)
                })

            } else {
                this.addNewUser();
                this.dialog = false;
            }

        },

        addNewUser() {
            axios.post('/api/register', this.editedUser).then(() => {
            }).catch((error) => {
                this.error = error.response.data.errors;
            })
        },

        deleteUser() {
            axios.post('/api/delete', this.editedUser).then(() => {
            }).catch((error) => {
                this.error = error.response.data.errors;
            })
        },

        logout() {
            axios.post('/api/logout').then(() => {
                this.$router.push({name: "Home"})
            })
        },

        closeOptions() {
            this.dialogOptions = false
            this.$nextTick(() => {
                this.editedUser = Object.assign({}, this.defaultItem)
                this.addingIndex = 0
            })
        },

        saveOptions() {
            if (this.optionsIndex === 0) {
                this.addCountry();
                this.dialogOptions = false;
            } else {
                this.addRole();
                this.dialogOptions = false;
            }
        }
    },
    mounted() {
        axios.get('/api/getAllUser').then((res) => {
            this.users = res.data.user
        }).catch((error) => {
            this.error = error.response.data.errors;
        })


    }
}
</script>
