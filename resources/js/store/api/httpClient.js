window.axios = require('axios');

const httpClient = axios.create({
    baseURL: process.env.VUE_APP_BASE_URL,
    headers: {
        "Content-Type": "application/json",
        // anything you want to add to the headers
    }
});

const getAuthToken = () => localStorage.getItem('token');

const authInterceptor = (config) => {
    config.headers['Authorization'] = "Bearer " +getAuthToken();
    return config;
}

httpClient.interceptors.request.use(authInterceptor);


export default httpClient;
