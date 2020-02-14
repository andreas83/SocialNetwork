import httpClient from './httpClient';


const END_POINT = '/api/content';



const getContentById = (content_id) => httpClient.get(END_POINT, { params:{id:content_id} });
const getComment = (content_id) => httpClient.get(END_POINT+"/comments/"+content_id, {  });

const createContent = ( html_content,
                        json_content,
                        has_comment,
                        is_comment,
                        parrent_id,
                        anonymous,
                        visibility
                      )=>httpClient.post(END_POINT,
                      {
                        html_content,
                        json_content,
                        has_comment,
                        is_comment,
                        parrent_id,
                        anonymous,
                        visibility
                       });

function updateContent ( id, html_content, json_content, has_comment,is_comment,
                         parrent_id, anonymous,visibility ) {

    return httpClient.put('/api/content/'+id, {
        html_content,
        json_content,
        has_comment,
        is_comment,
        parrent_id,
        anonymous,
        visibility
    });
}

function deleteContent(id){
  return httpClient.delete('/api/content/'+id);
}



const getContent = (content_id) => httpClient.get(END_POINT, { params:{id:content_id} });

export {
    getContent,
    getContentById,
    getComment,
    createContent,
    updateContent,
    deleteContent
}
