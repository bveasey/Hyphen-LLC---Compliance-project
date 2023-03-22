export const global = ({ to }) => {
    // todo: replace with something that edits the whole head...
    if (to.meta && to.meta.title) {
        document.title = to.meta.title
    }
}

export const authenticated = ({ to, authStore }) => {
    if (!authStore.authenticated && to.name !== 'login') {
        return 'login'
    }
}

export const guest = ({ to, authStore }) => {
    if (authStore.authenticated && to.name !== 'dashboard') {
        return 'dashboard'
    }
}

export const permission = ({ authStore, permission }) => {
    // console.log('middleware: permission')
    // if (authStore.authenticated) {
    //     return '/'
    // }
}