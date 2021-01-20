import httpClient from './httpClient';


const END_POINT = '/api/content';



const getContentById = (content_id) => httpClient.get(END_POINT, { params:{id:content_id} });
const getComment = (content_id) => httpClient.get(END_POINT+"/comments/"+content_id, {  });

const createContent = ( html_content,
                        json_content,
                        has_comment,
                        is_comment,
                        parent_id,
                        group_id,
                        anonymous,
                        visibility
                      )=>httpClient.post(END_POINT,
                      {
                        html_content,
                        json_content,
                        has_comment,
                        is_comment,
                        parent_id,
                        group_id,
                        anonymous,
                        visibility
                       });

function updateContent ( id, html_content, json_content, has_comment, is_comment,
                         parent_id, group_id, anonymous, visibility ) {

    return httpClient.put('/api/content/'+id, {
        html_content,
        json_content,
        has_comment,
        is_comment,
        parent_id,
        group_id,
        anonymous,
        visibility
    });
}

function deleteContent(id){
  return httpClient.delete('/api/content/'+id);
}


function getMoreContent ( next_id, user_id, content_id, group_id, limit  ) {

    return httpClient.get(END_POINT, { params:{
      next_id:next_id,
      user_id:user_id,
      content_id: content_id,
      group_id: group_id,
      limit:limit
    } });
}

const getContent = (content_id) => httpClient.get(END_POINT, { params:{id:content_id} });

export {
    getContent,
    getContentById,
    getMoreContent,
    getComment,
    createContent,
    updateContent,
    deleteContent
}
