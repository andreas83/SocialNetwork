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
                 class="btn default"
                 :class="{ 'is-active': isActive.underline() }"
                 @click="commands.underline"
               >
                 _
               </button>

               <button
                 class="btn default"
                 :class="{ 'is-active': isActive.code() }"
                 @click="commands.code"
               >
                 Code
               </button>

               <button
                 class="btn default"
                 :class="{ 'is-active': isActive.paragraph() }"
                 @click="commands.paragraph"
               >
                 P
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
                 class="btn default"
                 @click="commands.undo"
               >
                 undo
               </button>

               <button
                 class="btn default"
                 @click="commands.redo"
               >
                redo
               </button>

             </div>
      </editor-menu-bar>



      <editor-content class="editor" :editor="editor" />


      <button class="default icon-picture"/>




      <button class="btn default" v-if="!isComment" v-on:click="save"> <i class="icon-heart" /> {{$t('Share')}}</button>
      <button class="btn default" v-if="isComment" v-on:click="save"> {{$t('Comment')}}</button>



  </div>
</template>
<script>
import { Editor, EditorContent, EditorMenuBar } from 'tiptap'
import {  Blockquote,  CodeBlock,  HardBreak,  Heading,  OrderedList,  BulletList,  ListItem,  TodoItem,  TodoList,  Bold,  Code,  Italic,  Link,  Strike,  Underline,  History} from 'tiptap-extensions'

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
      parrent_id:{
        default : 0
      }
    },
    data() {
        return{

          editor: new Editor({
           content: '<h1>Yay Headlines!</h1>         <p>All these <strong>cool tags</strong> are working now.</p>',
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
             new Link(),
             new Strike(),
             new Underline(),
             new History(),
           ],
        })
      }
    },
    beforeDestroy() {
      this.editor.destroy()
    },
    mounted(){

    },
    methods:{
      save(e){
        e.preventDefault();
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

                  this.$router.push('/');
              })
              .catch(({response}) => {
                this.show=true;
                this.error=response.data.errors;
              });
      }

    },
    computed:{
      isAuth(){
        return this.$store.getters["user/isAuth"];
      }
    }
  }
</script>
<style>

</style>
