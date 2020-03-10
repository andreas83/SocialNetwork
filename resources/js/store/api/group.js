import httpClient from './httpClient';

const END_POINT = '/api/group';

function getGroup ( group_id, name , search, limit ) {

    return httpClient.get(END_POINT, { params:{
      group_id:group_id,
      name:name,
      search:search,
      limit:limit
    } });
}

const createGroup = ( name, description, avatar, visibility
                      )=>httpClient.post(END_POINT,
                      {
                        name,
                        description,
                        avatar,
                        visibility
                       });


function getGroupMembers(id){
  return httpClient.get('/api/group/membership/'+id);
}

function leave(id){
 return httpClient.delete('/api/group/membership/'+id);
}

const join = ( id
)=>httpClient.post("/api/group/membership/"+id,
                      {});

export {
    getGroup,
    createGroup,
    leave,
    join,
    getGroupMembers
}
