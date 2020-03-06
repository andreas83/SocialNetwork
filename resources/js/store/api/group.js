import httpClient from './httpClient';

const END_POINT = '/api/group';

function getGroup ( group_id, name , limit ) {

    return httpClient.get(END_POINT, { params:{
      group_id:group_id,
      name:name,
      limit:limit
    } });
}

const createGroup = ( name, description, background, status
                      )=>httpClient.post(END_POINT,
                      {
                        name,
                        description,
                        background,
                        status
                       });


export {
    getGroup,
    createGroup
}
