import axios from "axios";
import router from '../../router';

const actions = {
    /**
     *
     * @param {*} param0
     * @param {*} loginData
     */
    async currentUsernames({ commit }) {
        const resp = await axios.get('/api/usernamesAll');
        resp.data && resp.data.forEach(user => {
            commit('ADD_EXISTING_USER', user.email);
        })
    },
    /**
     * Login the user with its user data
     *
     * @param {String} username
     * @param {String} password
     */
    async login({ commit }, loginData) {
        console.log(loginData);
        commit('AUTH_REQUEST')
        try {
            const response = await axios.post('/api/login', loginData)
            const token = response.data.token;
            const username = response.data.user.name;
            const role = response.data.user.role;
            const id = response.data.user.id;
            /* Set the token data on the localStorage */
            localStorage.setItem('id', id);
            localStorage.setItem('token', token);
            localStorage.setItem('username', username);
            localStorage.setItem('role', role);
            commit('AUTH_SUCCESS', { user: username, token: token, role: role, id: id });
            /* If the user log in, move it to his dashboard */
            router.push({ name: 'home' });
        } catch (error) {
            commit('AUTH_ERROR');
            /* If an error occurs delete all the localStorage possible data */
            localStorage.removeItem('token');
            localStorage.removeItem('username');
            localStorage.removeItem('role');
            localStorage.removeItem('id');
            /* Send the response errors to the view */
            throw error;
        }
    },
    /**
     * Registers a new user
     *
     * @param {Object} regData { name, email, password, role }
     */
    async register({ dispatch }, regData) {
        const resp = await axios.post('/api/register', regData);
        /* if the register process is correct login the user */
        resp.data && dispatch('login', { email: resp.data.email, password: resp.data.password });
    },
    /**
     * Logout the user
     */
    logout({ commit, state }) {
        commit('LOGOUT');
        /* Remove the localStorage data */
        localStorage.removeItem('token');
        localStorage.removeItem('username');
        localStorage.removeItem('role');
        localStorage.removeItem('id');
        location.reload();
    },
}
const getters = {
    /**
     * Returns the current token
     */
    getToken: state => state.token,
    /**
     * Returns the current authenticated user
     */
    authenticatedUser: state => state.user,
    /**
     * Returns the current authenticated role
     */
    authenticatedRole: state => state.role,
    /**
     * Returns the current user id
     */
    authId: state => state.id,
    /**
     * Returns the current status
     */
    authStatus: state => state.status,
    /**
     * Return the current registered users
     */
    getExistingUsernames: state => state.existingUsers,
    /**
     * Returns is the user is authenticated validating if exists a token
     */
    isLoggedIn: state => !!state.token,
}
const mutations = {
    ADD_EXISTING_USER(state, user) {
        state.existingUsers.push(user);
    },
    AUTH_ERROR(state) {
        state.status = 'error';
    },
    AUTH_REQUEST(state) {
        state.status = 'loading';
    },
    AUTH_SUCCESS(state, { user, token, role, id }) {
        state.status = 'success';
        state.token = token;
        state.user = user;
        state.role = role;
        state.id = id;
    },
    LOGOUT(state) {
        state.status = '';
        state.token = '';
        state.user = '';
        state.role = '';
        state.id = '';
    },
    SET_TOKEN(state, token) {
        state.token = token;
    }
}
const state = {
    status: '',
    existingUsers: [],
    token: localStorage.getItem('token') || '',
    user: localStorage.getItem('username') || '',
    role: localStorage.getItem('role') || '',
    id: localStorage.getItem('id') || '',
}
export default {
    namespaced: true,
    state,
    actions,
    mutations,
    getters,
}
