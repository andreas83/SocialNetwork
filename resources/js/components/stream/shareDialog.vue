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
import { Editor, EditorContent, EditorMenuBar, Node } from 'tiptap'
import { CodeBlockHighlight, Blockquote,  CodeBlock,  HardBreak,  Heading,  OrderedList,  BulletList,  Link, ListItem,  TodoItem,  TodoList,  Bold,  Code,  Italic,  Strike,  Underline,  History, Image} from 'tiptap-extensions'
import {onPasteUrl} from "./editor/onPasteUrl";
import {mapGetters, mapActions} from 'vuex';

import javascript from 'highlight.js/lib/languages/javascript'
import css from 'highlight.js/lib/languages/css'
import python from 'highlight.js/lib/languages/python'
import php from 'highlight.js/lib/languages/php'


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
      reshare:{
        dafault:false
      },
      content_id:{
        dafault : 0
      },
      parent_id:{
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
             new Link(),
             new CodeBlockHighlight({
                languages: {
                  javascript,
                  css,
                  python,
                  php
                },
              }),
           ],
            onPaste: (view, event, slice) => {

              onPasteUrl(view,event,slice, this.editor);
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

      ...mapActions('content', ['createContent', 'updateContent']),
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

      save(e){


          if(this.edit==true)
          {
            let data = {
                id: this.content.id,
                html_content: this.editor.getHTML(),
                json_content: this.editor.getJSON(),
                has_comment: this.content.has_comment,
                is_comment:this.content.is_comment,
                parent_id: this.content.parent_id,
                anonymous: true,
                visibility: 'friends'
            };

            this.updateContent(data);
            this.editor.setContent("<h2>Updated</h2>");
            this.$emit('updated', this.content.id);
          }else {
            let data = {
                html_content: this.editor.getHTML(),
                json_content: this.editor.getJSON(),
                has_comment: false,
                is_comment:this.isComment,
                parent_id: this.parent_id,
                anonymous: true,
                visibility: 'friends'
            };
            this.createContent(data);
            this.editor.setContent("<h2>Saved</h2>");
            this.$emit('saved');
          }


      }

    },
    watch:{
      content_id(){
            this.editor.setContent(this.content.html_content);
      },

      reshare(val){
        if(val===true)
        {
            this.editor.setContent(this.content.html_content);
        }
      },
      edit(val){
        if(val===true)
        {
            this.editor.setContent(this.content.html_content);
        }
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

<style lang="scss">
pre {
  &::before {
    content: attr(data-language);
    text-transform: uppercase;
    display: block;
    text-align: right;
    font-weight: bold;
    font-size: 0.6rem;
  }
  code {
    .hljs-comment,
    .hljs-quote {
      color: #999999;
    }
    .hljs-variable,
    .hljs-template-variable,
    .hljs-attribute,
    .hljs-tag,
    .hljs-name,
    .hljs-regexp,
    .hljs-link,
    .hljs-name,
    .hljs-selector-id,
    .hljs-selector-class {
      color: #f2777a;
    }
    .hljs-number,
    .hljs-meta,
    .hljs-built_in,
    .hljs-builtin-name,
    .hljs-literal,
    .hljs-type,
    .hljs-params {
      color: #f99157;
    }
    .hljs-string,
    .hljs-symbol,
    .hljs-bullet {
      color: #99cc99;
    }
    .hljs-title,
    .hljs-section {
      color: #ffcc66;
    }
    .hljs-keyword,
    .hljs-selector-tag {
      color: #6699cc;
    }
    .hljs-emphasis {
      font-style: italic;
    }
    .hljs-strong {
      font-weight: 700;
    }
  }
}
</style>
