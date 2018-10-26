//
//
// MyAjax is generated on the php server and added to the .js file with wp_localize_script
let ajaxurl = MyAjax.ajaxurl;
let ajaxnonce = MyAjax.nonce;

//todo: check difference between JSON and javascript object when sent as ajax
// indata should be formatted as a javascript object (not sure if JSON works) or as a serialized form
// callback is a function to call when the ajax call is successful
// action is the name of the action to call, and helps generate the url
function callAjaxAction_jquery(action, indata, callback = null) {
    let ajaxurl = MyAjax.ajaxurl;
    let fullurl = ajaxurl + "?action=" + action;

    $.ajax({
        url: fullurl,
        type: "post",
        beforeSend: function(xhr) {
            // https://developer.wordpress.org/rest-api/using-the-rest-api/authentication/
            // 'X-WP-Nonce' becomes 'HTTP_X_WP_NONCE' when sent so to access it on the php server use $_SERVER['HTTP_X_WP_NONCE']
            xhr.setRequestHeader("X-WP-Nonce", MyAjax.nonce);
        },
        data: indata,
        success: function(data) {
            console.log("AJAX SUCCESS!");
            if (callback) {
                callback();
            }
            return true;
        },
        error: function(data) {
            console.log("AJAX FAILURE");
            return false;
        }
    });
}
