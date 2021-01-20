import httpClient from './httpClient';

const END_POINT = '/api/user';

const getUser =() => httpClient.get(END_POINT);

export {
    getUser
}
