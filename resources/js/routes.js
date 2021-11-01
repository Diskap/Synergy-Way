import Home from './components/Home';
import NotFound from "./components/NotFound";
import Login from "./components/Login";
import Register from "./components/Register";
import Profile from "./components/Profile";

export default {
    mode: 'history',

    routes: [
        {
            path: '/',
            component: Home,
            name: "Home"
        },
        {
            path: '/login',
            component: Login
        },
        {
            path: '/register',
            component: Register
        },
        {
            path: '/profile',
            component: Profile,
            name:'Profile'
        },
        {
            path: '*',
            component: NotFound
        }
    ]
}
