import httpClient from './httpClient';

const END_POINT = '/api/content/likes';

const getLikesById = (content_id) => httpClient.get(END_POINT , {params: {content_id:content_id }});
const createLike = (key, content_id ) => httpClient.post(END_POINT, { key, content_id });

export {
    getLikesById,
    createLike
}
