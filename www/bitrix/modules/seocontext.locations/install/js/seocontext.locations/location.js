var seocontext = seocontext || {};
if (typeof seocontext.location !== 'undefined')
    console.warn("Namespace seocontext.location is already exists.");
else
    seocontext.location = {};

(function(){
    const key = "SEOCONTEXT_LOCATION";

    this.get = function () {
        return BX.localStorage.get(key);
    };

    this.set = function (code, name) {
        var oldLocation = this.get();
        BX.localStorage.set(key, {'code': code, 'name': name}, 31104000);

        // setting cookie
        var date = new Date(new Date().getTime() + 31104000*1000);
        document.cookie = key+"_COOKIE="+code+"; path=/; expires=" + date.toUTCString();

        if (!oldLocation || oldLocation.code != code)
            $(document).trigger('location:change');
    }
}).apply(seocontext.location);