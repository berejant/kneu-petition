import './bootstrap';

let ajaxErrorHandler = function(xhr) {
    if(401 === xhr.status) {
        location.assign('/login');
    }
};

function formToObject (form) {
    let post = {};
    $(form).serializeArray().forEach(function(item) {
        post[ item.name ] = item.value;
    });

    return post;
}

jQuery(function ($) {
    let $logoutForm = $('#logout-form'),
        isGuest = !$logoutForm.length;

    if(isGuest) {
        // кнопки, які працюють лише авторизованних користувачів
        $('.require-authentication').on('click', function (event) {
            event.preventDefault();
            event.stopPropagation();
            location.assign('/login');
        });

    } else {
        $('.logout-button').on('click', function(event) {
            event.preventDefault();
            $logoutForm.submit();
        });
    }

    // проголосувати за петицію
   $('.vote-button').on('click', function () {
       const petitionId = $(this).data('petitionId');

       $.post('/petitions/' + petitionId + '/vote')
           .then((response) => {
               location.reload();
           }, ajaxErrorHandler);
   });

    // відобразити форму "додати комментар"
    $('.petition-comments-list').each(function() {
        const $addComentWidget = $('.add-comment-form', this);

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

        $.post('/petitions/' + petitionId + '/comments', post)
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

            $.ajax({url: '/petitions/' + petitionId, method: 'DELETE'})
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
        content = content.trim();
        let $contentInput = $form.find('#content').val(content);
        $contentInput.height($petitionCommentContent.height() + 40);

        $petitionCommentContent.empty().append($form);
        $form.show();
        $contentInput.focus();

        $form.on('submit', function(event) {
           event.preventDefault();
           let postData = formToObject($form);

           $.ajax({url: '/petitions/' + petitionId + '/comments/' + petitionCommentId, method: 'PUT', data: postData})
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

            $.ajax({url: '/petitions/' + petitionId + '/comments/' + petitionCommentId, method: 'DELETE'})
                .then((response) => {
                    location.reload();
                }, ajaxErrorHandler);
        }
    });

    $('.petition-statistics__status-diargam').each(makeStatusDiagram);

    let $petitionQrcode = $('.petition-qrcode').click(function () {
        $petitionQrcode.toggleClass('petition-qrcode__focused');
    })

});

// Google charts
function loadGoogleCharts() {
    if(!loadGoogleCharts.xhr) {
        loadGoogleCharts.xhr = jQuery.ajax({
            dataType:'script',
            cache:true,
            url:'https://www.gstatic.com/charts/loader.js',
            success: function() {
                google.charts.load("current", {packages:["corechart"]});
            }
        });
    }

    let defer = jQuery.Deferred();

    loadGoogleCharts.xhr.then(function () {
        google.charts.setOnLoadCallback(defer.resolve);
    }, defer.reject);

    return defer.promise();
}

function makeStatusDiagram() {
    let elem = this;

    loadGoogleCharts().then(function () {
        let config = $(elem).data();

        let data = google.visualization.arrayToDataTable(config.data);
        delete config.data;

        const options = Object.assign({
            fontName: 'Roboto',
            chartArea: {
                height: 320,
            },
            is3D: true,
            titleTextStyle: {
                fontSize: 28,
                bold: true,
            },
            legend: {
                position: 'labeled',
                alignment: 'start',
                textStyle: {
                    fontSize: 16,
                }
            },
            pieStartAngle: 90,
            pieSliceText: 'value',
            pieSliceTextStyle: {
                color: "white",
                fontName: 'Roboto',
                bold: true,
                fontSize: 18,
            }
        }, config);

        let chart = new google.visualization.PieChart(elem);
        chart.draw(data, options);
    });
}
