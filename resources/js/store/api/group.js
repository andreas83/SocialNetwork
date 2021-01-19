import httpClient from './httpClient';

const END_POINT = '/api/group';

function getGroup ( group_id, name , search, limit, random ) {

    return httpClient.get(END_POINT, { params:{
      group_id:group_id,
      name:name,
      search:search,
      limit:limit,
      random: random
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

function approveMember(id, user_id){
  return httpClient.post('/api/group/membership/'+id+'/approve', {user_id: user_id});
}

function declineMember(id, user_id){
  return httpClient.delete('/api/group/membership/'+id+'/delcline', {user_id: user_id});
}

export {
    getGroup,
    createGroup,
    leave,
    join,
    approveMember,
    declineMember,
    getGroupMembers
}
