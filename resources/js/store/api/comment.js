import httpClient from './httpClient';


const GET_END_POINT = '/content/comments';
const POST_END_POINT = '/content';


const getComment = (content_id) => httpClient.get(GET_END_POINT, { content_id });


const createComment = (html_content, json_content, has_comment, is_comment , parrent_id , anonymous, visibility ) =>
httpClient.post(POST_END_POINT, { html_content, json_content, has_comment, is_comment , parrent_id , anonymous, visibility });

export {
    getComment,
    createComment
}
