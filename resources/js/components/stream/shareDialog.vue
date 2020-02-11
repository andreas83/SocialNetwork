<template>

  <div class="row-0 editor-container">
      <editor-menu-bar class="btn default" :editor="editor" v-slot="{ commands, isActive }">
        <div class="menubar">

               <button

                 class="btn default"
                 :class="{ 'is-active': isActive.bold() }"
                 @click="commands.bold"
               >
                 B
               </button>

               <button

                 class="btn default"
                 :class="{ 'is-active': isActive.italic() }"
                 @click="commands.italic"
               >
                 <i>I</i>
               </button>

               <button

                 class="btn default"
                 :class="{ 'is-active': isActive.strike() }"
                 @click="commands.strike"
               >
                  <strike>abz</strike>
               </button>

               <button
               v-if="!isComment"
                 class="btn default"
                 :class="{ 'is-active': isActive.underline() }"
                 @click="commands.underline"
               >
                 _
               </button>

               <button
               v-if="!isComment"
                 class="btn default"
                 :class="{ 'is-active': isActive.code() }"
                 @click="commands.code"
               >
                 Code
               </button>



               <button
                 class="btn default"
                 :class="{ 'is-active': isActive.heading({ level: 1 }) }"
                 @click="commands.heading({ level: 1 })"
               >
                 H1
               </button>

               <button
                 class="btn default"
                 :class="{ 'is-active': isActive.heading({ level: 2 }) }"
                 @click="commands.heading({ level: 2 })"
               >
                 H2
               </button>

               <button
                 class="btn default"
                 :class="{ 'is-active': isActive.heading({ level: 3 }) }"
                 @click="commands.heading({ level: 3 })"
               >
                 H3
               </button>

               <button
                 class="btn default"
                 :class="{ 'is-active': isActive.bullet_list() }"
                 @click="commands.bullet_list"
               >
                 <i class="icon-list" />
               </button>



               <button
               v-if="!isComment"
                 class="btn default"
                 :class="{ 'is-active': isActive.blockquote() }"
                 @click="commands.blockquote"
               >
                 ""
               </button>

               <button
                 class="btn default"
                 :class="{ 'is-active': isActive.code_block() }"
                 @click="commands.code_block"
               >
                 CODE
               </button>


               <button
               v-if="!isComment"
                 class="btn default"
                 @click="commands.undo"
               >
                 undo
               </button>

               <button
               v-if="!isComment"
                 class="btn default"
                 @click="commands.redo"
               >
                redo
               </button>
             </div>
      </editor-menu-bar>



      <editor-content class="editor" :editor="editor" />

      <editor-menu-bar class="btn default" :editor="editor" v-slot="{ commands, isActive }">
        <button class="default icon-picture" @click="openFileDialog(commands.image)"/>
      </editor-menu-bar>



      <button class="btn default" v-if="!isComment" @click.prevent="save"> <i class="icon-heart" /> {{$t('Share')}}</button>
      <button class="btn default" v-if="isComment" @click.prevent="save"> {{$t('Comment')}}</button>

  </div>
</template>
<script>
import { Editor, EditorContent, EditorMenuBar } from 'tiptap'

import {  Blockquote,  CodeBlock,  HardBreak,  Heading,  OrderedList,  BulletList,  Link, ListItem,  TodoItem,  TodoList,  Bold,  Code,  Italic,  Strike,  Underline,  History, Image} from 'tiptap-extensions'


import { Node } from 'tiptap'

export default {
    name: "shareDialog",
    components: {
        EditorMenuBar,
        EditorContent,
      },
    props: {
      isComment:{
        default: false
      },
      edit:{
        dafault:false
      },
      content_id:{
        dafault : 0
      },
      parrent_id:{
        default : 0
      }
    },
    data() {
        return{
          rawjson:"",
          editor: new Editor({
           content: '',
           extensions: [

             new Blockquote(),
             new CodeBlock(),
             new HardBreak(),
             new Heading({ levels: [1, 2, 3] }),
             new BulletList(),
             new OrderedList(),
             new ListItem(),
             new TodoItem(),
             new TodoList(),
             new Bold(),
             new Code(),
             new Italic(),

             new Strike(),
             new Underline(),
             new History(),
             new Image(),
             new Link()
           ],
            onPaste: (view, event, slice) => {
               let urlpattern=/https?:\/\/(www\.)?[-a-zA-Z0-9@:%._+~#=]{2,256}\.[a-zA-Z]{2,}\b([-a-zA-Z0-9@:%_+.~#?&//=]*)/g
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

                         const doc = this.editor.getJSON()

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

                         this.editor.setContent(doc)


                      })
                      .catch(({response}) => {
                        // this.show=true;
                        // this.error=response.data.errors;
                      });
               }

            }
        })
      }
    },
    beforeDestroy() {
      this.editor.destroy()
    },
    mounted(){
      if(this.isComment){
        this.editor.setContent("<h3>Comment</h3>");
      }
    },
    methods:{

      openFileDialog(command){
        var element = document.createElement('div');
        element.innerHTML = '<input multiple="multiple" type="file">';
        let fileInput = element.firstChild;
        let vm=this;
        let token=this.user.api_token;
        fileInput.addEventListener('change', function() {
          let formData = new FormData();

          for (let i = 0; i < fileInput.files.length; i++) {
              let file = fileInput.files[i];
              formData.append('upload[]', file, file.name);
          }
          var xhr = new XMLHttpRequest();



          xhr.open('POST', 'api/content/upload', true);
          xhr.setRequestHeader('Authorization', 'Bearer ' + token);
          xhr.onload = function () {
              if (xhr.status === 200) {
              // Dateien wurden hochgeladen
              ;
                const result = JSON.parse(xhr.responseText);
                for(let index in result)
                {
                  const src= result[index];
                  command({src})
                }


              } else {
                  vm.error = xhr.responseText;
              }
          };
          xhr.send(formData);


        });

        fileInput.click();
      },
      json(){
        this.rawjson=this.editor.getJSON();
      },
      save(e){


          if(this.edit==true)
          {
            let data = {
                html_content: this.editor.getHTML(),
                json_content: this.editor.getJSON(),
                has_comment: this.content.has_comment,
                is_comment:this.content.is_comment,
                parrent_id: this.content.parrent_id,
                anonymous: true,
                visibility: 'friends'
            };
            axios.put('/api/content/'+this.content.id, data)
                .then(({data}) => {

                  this.$store.commit('content/updateContent', data);

                  this.editor.setContent("<h2>Updated</h2>");

                })
                .catch(({response}) => {
                  this.show=true;
                  this.error=response.data.errors;
                });
            return true;
          }else {
            let data = {
                html_content: this.editor.getHTML(),
                json_content: this.editor.getJSON(),
                has_comment: false,
                is_comment:this.isComment,
                parrent_id: this.parrent_id,
                anonymous: true,
                visibility: 'friends'
            };
            axios.post('/api/content', data)
                .then(({data}) => {

                  data.content.show_comment=false;

                  //updateLikes

                  this.$store.commit('content/prependContent', data.content);
                  this.editor.setContent("<h2>Thank You</h2>");
                })
                .catch(({response}) => {
                  this.show=true;
                  this.error=response.data.errors;
                });
          }

      }

    },
    watch:{

      edit(){

        this.editor.setContent(this.content.html_content);
      }
    },
    computed:{
      content(){
        return this.$store.getters["content/getContentById"](this.content_id);
      },
      isAuth(){
        return this.$store.getters["user/isAuth"];
      },
      user(){
        return this.$store.getters["user/getUser"];
      }
    }
  }
</script>
<style>

</style>
