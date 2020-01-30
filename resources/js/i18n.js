import Vue from 'vue'
import VueI18n from 'vue-i18n'

Vue.use(VueI18n)

function loadLocaleMessages () {
    const locales = require.context('./locales', true, /[A-Za-z0-9-_,\s]+\.json$/i)
    const messages = {}
    locales.keys().forEach(key => {
        const matched = key.match(/([A-Za-z0-9-_]+)\./i)
        if (matched && matched.length > 1) {
            const locale = matched[1]
            messages[locale] = locales(key)
        }
    })
    return messages
}
function getBrowserLanguages() {
    var languages = [ 'en' ];
    if (navigator) {
        var language;
        if (navigator.language) { language = navigator.language; }
        else if (navigator.browserLanguage) { language = navigator.browserLanguage; }
        else if (navigator.userLanguage) { language = navigator.userLanguage; }
        else if (navigator.systemLanguage) { language = navigator.systemLanguage; }
        else { language = 'en'; }

        languages = [ language, language.substring(0,2) ];
    } // else, we'll just accept the default languages
    //if( )
    if(localStorage.getItem('lang')!=null)
    {
      return localStorage.getItem('lang');
    }
    return languages[1];
}

export default new VueI18n({
    locale: getBrowserLanguages(),
    fallbackLocale:  'en',
    messages: loadLocaleMessages()
})
