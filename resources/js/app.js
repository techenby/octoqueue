require('./bootstrap')

import Alpine from 'alpinejs'
import tippy from 'tippy.js'
import 'tippy.js/dist/tippy.css';

Alpine.directive('tooltip', (el, { expression }) => {
    tippy(el, { content: expression })
})

window.Alpine = Alpine
Alpine.start()
