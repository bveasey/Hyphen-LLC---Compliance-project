export function parseUrl(url, params = {}) {
    if (!url.includes(':')) return url;

    const urlItems = url.split('/');
    const urlItemsWithParams = urlItems.map((item) => {
        if (item[0] === ':' && params[item.slice(1)]) {
            return params[item.slice(1)];
        }

        return item;
    });

    return urlItemsWithParams.join('/');
}
