
/**
 * First we will load all of this project's JavaScript dependencies which
 * include Vue and Vue Resource. This gives a great starting point for
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the body of the page. From here, you may begin adding components to
 * the application, or feel free to tweak this setup for your needs.
 */

const app = new Vue({
    el: '#app'
});

let ajaxErrorHandler = function(response) {
    if(401 === response.status) {
        location.assign('/login');
    }
};

function formToObject (form) {
    let post = {};
    _.forEach($(form).serializeArray(), function(item) {
        post[ item.name ] = item.value;
    });

    return post;
}

jQuery(function ($) {
    // кнопки, які працюють лише авторизованних користувачів
    if(!$('.logout-form').length) {
        $('.require-authentication').on('click', function (event) {
            event.preventDefault();
            event.stopPropagation();
            location.assign('/login');
        });
    }

    // проголосувати за петицію
   $('.vote-button').on('click', function () {
       const petitionId = $(this).data('petitionId');

       Vue.http.post('/petitions/' + petitionId + '/vote')
           .then((response) => {
               location.reload();
           }, ajaxErrorHandler);
   });

    // відобразити форму "додати комментар"
    $('.petition-comments-list').each(function() {
        $addComentWidget = $('.add-comment-form', this);

        $('.petition-comments-list__add-button', this).on('click', function(event) {
            if(!event.isDefaultPrevented()){
                $(this).hide();
                $addComentWidget.slideDown().find(':input:visible:not(:button):first').focus();
            }
        });
    });

    // відправити форму "додати коментар"
    $('.add-comment-form').on('submit', function (event) {
        event.preventDefault();

        const petitionId = $(this).data('petitionId');
        let post = formToObject(this);

        Vue.http.post('/petitions/' + petitionId + '/comments', post)
            .then((response) => {
                location.reload();
            }, ajaxErrorHandler);

    });

    // видалити петицію
    $('.petition-item__remove').on('click', function (event) {
        event.preventDefault();
        let confirmText = $(this).data('confirmText');

        if(confirm(confirmText)) {
            const petitionId = $(this).data('petitionId');

            Vue.http.delete('/petitions/' + petitionId)
                .then((response) => {
                    location.assign('/');
                }, ajaxErrorHandler);
        }
    });

    $('.petition-comment-item__edit').on('click', function (event) {
        event.preventDefault();
        $(this).hide();

        const petitionId = $(this).data('petitionId');
        const petitionCommentId = $(this).data('petitionCommentId');
        let $petitionCommentContainer = $(this).parents('.petition-comment-item:first');
        let $petitionCommentContent = $petitionCommentContainer.find('.petition-comment-item__content');

        let $form = $('.add-comment-form').clone()
            .removeData()
            .addClass('edit-comment-form');

        let content = $petitionCommentContent.html();
        content = content.replace(/<br[^>]*>\n?/gi, "\n");
        content = _.trim(content);
        let $contentInput = $form.find('#content').val(content);
        $contentInput.height($petitionCommentContent.height() + 40);

        $petitionCommentContent.empty().append($form);
        $form.show();
        $contentInput.focus();

        $form.on('submit', function(event) {
           event.preventDefault();
           let postData = formToObject($form);

           Vue.http.put('/petitions/' + petitionId + '/comments/' + petitionCommentId, postData)
               .then((response) => {
                   location.reload();
               }, ajaxErrorHandler);
        });

    });


    // видалити петицію
    $('.petition-comment-item__remove').on('click', function (event) {
        event.preventDefault();
        let confirmText = $(this).data('confirmText');

        if(confirm(confirmText)) {
            const petitionId = $(this).data('petitionId');
            const petitionCommentId = $(this).data('petitionCommentId');

            Vue.http.delete('/petitions/' + petitionId + '/comments/' + petitionCommentId)
                .then((response) => {
                    location.reload();
                }, ajaxErrorHandler);
        }
    });

});

