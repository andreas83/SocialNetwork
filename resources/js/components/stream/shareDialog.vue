<template>

  <div class="row-0 editor-container">

      <editor-menu-bar v-if="showMeta" class="btn default" :editor="editor" v-slot="{ commands, isActive }">
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


      <div class="col-lg-6 col-md-8">
        <editor-menu-bar class="btn default" :editor="editor" v-slot="{ commands, isActive }">
          <button class="default icon-picture" @click="openFileDialog(commands.image)"/>
        </editor-menu-bar>
        <button class="btn default" v-if="!isComment" @click.prevent="save"> <i class="icon-heart" /> {{$t('Share')}}</button>
        <button class="btn default" v-if="isComment" @click.prevent="save"> {{$t('Comment')}}</button>
      </div>
      <div v-if="!isComment" class="col-lg-6 col-md-4">

        <a href="#"  v-if="!showMeta"  @click="showMeta=true">Show Advanced</a>
        <a href="#" v-if="showMeta" @click="showMeta=false">Hide Advanced</a>
      </div>
      <div class="row-0" v-if="showMeta">
        <div class="col-lg-6 col-md-12">
        <div v-if="showCreateGroup!=true" class="form-field">

          <div class="row-0">
            <div class="col-lg-9 col-md-9">
              <input v-if="selectedGroupId==false"  type="text"  placeholder="Select group " v-model="searchGroup">
            </div>
            <div class="col-lg-3 col-md-3">
              <button v-if="selectedGroupId==false"  @click="showCreateGroup=true"> + </button>
            </div>
          </div>
          <div v-if="selectedGroupId" class="row-0 autocomplete selected">
            <div class="col-lg-9">
              {{previewGroup.name}}
            </div>
            <div class="col-lg-2">
              <div class="small avatar" v-bind:style="{ 'background-image': 'url(' + getThumbnail(previewGroup.avatar, 50, 50) + ')' }"></div>
            </div>
            <div class="col-lg-1">
              <button @click="selectedGroupId=false">x</button>
            </div>
          </div>

          <div  v-if="selectedGroupId==false"  class="row-0 autocomplete group" @click="selectGroup(item)" v-for="item in autoCompleteGroup">
              <div class="col-lg-8">
                {{item.name}}
              </div>
              <div class="col-lg-4">
                <div class="small avatar" v-bind:style="{ 'background-image': 'url(' + getThumbnail(item.avatar, 50, 50) + ')' }"></div>
              </div>

          </div>





        </div>
        <GroupCreate :suggestion="searchGroup" v-on:cancled="groupCancled"  v-on:saved="groupSaved" v-show="showCreateGroup"></GroupCreate>
      </div>
      <div class="col-lg-6 col-md-12">

        <label for="anonymous">{{$t("post.anonymously")}}</label>
        <input id="anonymous" type="checkbox" v-model="anonymous" value="true">
        <p v-if="anonymous">{{$t("Note you can't edit nor delete afterwars.")}}</p>
      </div>



      </div>
  </div>
</template>
<script>
import { Editor, EditorContent, EditorMenuBar, Node } from 'tiptap'
import { CodeBlockHighlight, Blockquote,  CodeBlock,  HardBreak,  Heading,  OrderedList,  BulletList,  Link, ListItem,  TodoItem,  TodoList,  Bold,  Code,  Italic,  Strike,  Underline,  History, Image} from 'tiptap-extensions'
import {onPasteUrl} from "./editor/onPasteUrl";
import {mapGetters, mapActions} from 'vuex';
import { debounce } from 'lodash'
import {upload} from '../../helper/upload'
import {getThumbnail} from '../../helper/resize'
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
      },
      group_id:{
        default : 0
      }
    },
    data() {
        return{
          searchGroup:"",
          rawjson:"",
          showMeta:false,
          showCreateGroup:false,
          selectedGroupId:false,
          anonymous:false,
          previewGroup:{
            name:"",
            avatar:"",
            id:0
          },
          editor: new Editor({
           content: "<p></p>",

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
    async mounted(){
      if(this.selectedGroupId==false && this.group_id!=false)
      {
        this.selectedGroupId=this.group_id;
      }
      if(this.isComment){
        this.editor.setContent("<h3>Comment</h3>");
      }
    },
    methods:{
      getThumbnail,
      ...mapActions('content' , ['createContent', 'updateContent']),
      ...mapActions('groups' , ['setGroup', 'getGroup']),


      groupSaved(item){
        this.showCreateGroup=false;
        this.selectGroup(item);
      },

      groupCancled(){
          this.showCreateGroup=false;
      },

      openFileDialog(command){
        let vm=this

        upload(function(result){
            const src= result;
            command({src})
            // for(let index in result)
            // {
            //   const src= result[index];
            //   command({src})
            // }
        });
      },
      selectGroup(item){
        this.selectedGroupId=item.id;
        this.previewGroup.id=item.id;
        this.previewGroup.name=item.name;
        this.previewGroup.avatar=item.avatar;


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
                group_id: this.content.group_id,
                anonymous: this.anonymous,
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
                group_id: this.selectedGroupId,
                anonymous: this.anonymous,
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
          console.log();
            this.editor.setContent(this.content.html_content);
        }
      }
    },
    computed:{
      autoCompleteGroup(){

        return this.$store.getters["groups/getGroup"];
      },
      content(){
        return this.$store.getters["content/getContentById"](this.content_id);
      },
      isAuth(){
        return this.$store.getters["user/isAuth"];
      },
      user(){
        return this.$store.getters["user/getUser"];
      }
    },
    watch: {
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
      },
      searchGroup: debounce(function () {
        this.setGroup([]);




        if(this.searchGroup.length>1)
        {
           this.getGroup({search:this.searchGroup});
        }

      }, 300)
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
