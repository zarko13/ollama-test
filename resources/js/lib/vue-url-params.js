function getParamsAsObj(){
    let search = window.location.search.substring(1)
    let params = {};

    if( search != "" ){
        params = JSON
                    .parse('{"' + search.replace(/&/g, '","')
                    .replace( /=/g,'":"') + '"}', function(key, value) {
                        return key==="" ? value:decodeURIComponent(value)
                    });
    }

    return params;
}

export default {
    install(app, options) {

        app.config.globalProperties.$addUrlParams = function (key, value) {

            let params = getParamsAsObj();

            params[key] = value;

            var queryString = Object.keys(params).map((key) => {

                    return encodeURIComponent(key) + '=' + encodeURIComponent(params[key])

            }).join('&');

            window.history.replaceState(null, null, window.location.pathname+'?'+queryString);
            return queryString;
        },

        app.config.globalProperties.$clearUrlParams = function () {
            window.history.replaceState(null, null, window.location.pathname);
        },

        app.config.globalProperties.$getUrlParamsAsObj = function() {
            return getParamsAsObj();
        }
    }
}
