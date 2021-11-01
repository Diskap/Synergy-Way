<template>
    <v-app>
    <v-container fill-height fluid class="login-form">
        <v-form
            ref="form"
            v-model="valid"
            lazy-validation
            style="max-width: 195px"
        >
            <v-text-field
                v-model="form.login"
                label="Login"
                :rules="loginRules"
            ></v-text-field>

            <v-text-field
                v-model="form.password"
                type="password"
                :rules="passwordRules"
                label="Password"
            ></v-text-field>
            <v-btn
                :disabled="!valid"
                color=#fff
                class="mr-4"
                @click="loginUser"
            >
                Login
            </v-btn>
            <div v-if="loginError" class=" rounded mb-4 shadow-lg is-danger">
                <div v-for="(v, k) in loginError" :key="k">
                    <span v-for="error in v" :key="error" class="error--text">
                        {{ error }}
                    </span>
                </div>
            </div>
        </v-form>
    </v-container>
    </v-app>
</template>


<script>
export default {
    data: () => ({
        valid:true,
        form: {
            login: '',
            password: ''
        },
        loginError: '',
        loginRules: [
            v => !!v || 'Password is required',
            v => v.length >= 2 || 'Min 2 characters',
        ],
        passwordRules: [
            v => !!v || 'Password is required',
             // v => /(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,}/.test(v) || 'Password must contain at least lowercase letter, one number, a special character and one uppercase letter',
        ]

    }),
    methods:{
        loginUser(){
            axios.post('/api/login',this.form).then((res) =>{
                this.$router.push({ path: `/profile` })
                }).catch((error) => {
                this.loginError = error.response.data.errors;
            })
        }
    }

}
</script>
