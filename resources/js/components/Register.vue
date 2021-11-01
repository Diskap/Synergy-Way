<template>
    <v-app>
    <v-container fill-height fluid class="login-form">
        <v-form
            ref="form"
            v-model="valid"
            lazy-validation
            style="max-width: 223px"
        >
            <v-text-field
                v-model="form.first_name"
                :rules="first_nameRules"
                label="First name"
                required
            ></v-text-field>

            <v-text-field
                v-model="form.last_name"
                :rules="last_nameRules"
                label="Surname"
                required
            ></v-text-field>

            <v-text-field
                v-model="form.login"
                :rules="loginRules"
                label="Login"
                required
            ></v-text-field>

            <v-text-field
                v-model="form.password"
                label="Password"
                :rules="passwordRules"
                type="Password"
                required
            ></v-text-field>

            <v-select
                v-model="form.country_id"
                :items="this.countrys"
                :rules="country_idRules"
                item-text="name"
                item-value="id"
                label="Country"
                required
            ></v-select>

            <v-btn
                :disabled="!valid"
                color=#fff
                class="mr-4"
                @click="saveForm"
            >
                Register
            </v-btn>
            <div v-if="errors" class=" py-2 px-4 pr-0 rounded font-bold mb-4 shadow-lg is-danger">
                <div v-for="(v, k) in errors" :key="k">
                    <p v-for="error in v" :key="error" class="text-sm error--text">
                        {{ error }}
                    </p>
                </div>
            </div>
        </v-form>
    </v-container>
    </v-app>
</template>


<script>
export default {
    data: () => ({
        valid: true,
        form: {
            first_name: '',
            last_name: '',
            login: '',
            password: '',
            country_id: ''
        },
        first_nameRules: [
            v => !!v || 'Password is required',
            v => v.length >= 2 || 'Min 2 characters',
        ],
        last_nameRules: [
            v => !!v || 'Password is required',
            v => v.length >= 2 || 'Min 2 characters',
        ],
        loginRules: [
            v => !!v || 'Password is required',
            v => v.length >= 2 || 'Min 2 characters',
        ],
        passwordRules: [
            v => !!v || 'Password is required',
            v => /(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,}/.test(v) || 'Password must contain at least lowercase letter, one number, a special character and one uppercase letter',
        ],
        country_idRules: [
            v => !!v || 'Password is required'
        ],
        errors: '',
        countrys: [],
    }),
    created() {
        this.initialize()
    },
    methods: {
        initialize(){
            axios.get('/api/country').then((res) => {
                this.countrys = res.data;
            })
        },
        saveForm() {
            axios.post('/api/register', this.form).then(() => {
            }).catch((error) => {
                this.errors = error.response.data.errors;
            })
        }
    },
   mounted() {
       axios.get('/api/country').then((res) => {
            this.countrys = res.data;
        })
    }
}
</script>
