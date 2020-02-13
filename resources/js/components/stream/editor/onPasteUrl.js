
function onPasteUrl(view,event,slice,editor) {

    let urlpattern=/https?:\/\/(www\.)?[-a-zA-Z0-9@:%._\+~#=]{2,256}\.[a-z]{2,4}\b([-a-zA-Z0-9@:%_\+.~#?&//=]*)/g
    if(slice.content.content[0].textContent.match(urlpattern))
    {

       //found url
       let data={
         url:slice.content.content[0].textContent
       }
       axios.post('/api/content/ogparser', data)
           .then(({data}) => {

              let image=data.ogtags.image;
              let title=data.ogtags.title;
              let description=data.ogtags.description;

              const doc = editor.getJSON()

              if (title) {
                doc.content.push({
                   "type": "heading",
                   "attrs": {
                     "level": 3
                   },
                   "content": [
                     {
                       "type": "text",
                       "text": title
                     }
                   ]
                 });

              }

               //add image
               if(image)
               {
                 doc.content.push(
                 {
                   "type": "paragraph",
                   "content": [
                     {
                       "type": "image",
                       "attrs": {
                         "src": [
                           image
                         ],
                         "alt": null,
                         "title": null
                       }
                     }
                   ]
                 });
               }
               //add description
               if(description)
               {
                 doc.content.push(
                 {
                   "type": "paragraph",
                   "content": [
                     {
                       "type": "text",
                       "text": description
                     }
                   ]
                 });
               }
              editor.setContent(doc)


           })
           .catch(({response}) => {

           });
    }
}

export{
  onPasteUrl
}
