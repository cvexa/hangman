let errors = 0;
let checkUrl = $('#characters').attr('data-url');
let redirectUrl = $('#characters').attr('data-redirect-url');

$(document).ready(function () {
    $('#characters').find('li').each(function () {
        $(this).on('click', function () {
            if (!$(this).hasClass("disabled-li")) {
                checkIfCharExists($(this).text());
                $(this).addClass('disabled');
                $(this).addClass('disabled-li')
            }
            $(this).unbind();
            $(this).css('pointer-events','none');
        });
    });
    $('#fullAttemptTrigger').on('click', function () {
        checkFullWord($('#fullAttempt').val());
    });

});

function checkFullWord(fullText) {
    let checkUrl = $('#fullAttemptTrigger').attr('data-url');
    if (errors < 6) {
        $('#fullAttemptTrigger').css('pointer-events','none');
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            url: checkUrl,
            method: 'post',
            data: {
                fullText: fullText
            },
            success: function (result) {
                $('#fullAttemptTrigger').css('pointer-events','auto');
                redirectAfterGame(result);
            }
        });
    } else {
        window.location = redirectUrl;
    }
}

function checkIfCharExists(char) {
    if (errors < 6) {
        $('#characters').css('pointer-events','none');
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            url: checkUrl,
            method: 'post',
            data: {
                char: char
            },
            success: function (result) {
                $('#characters').css('pointer-events','auto');
                if (result.word) {
                    $('#masked-word').html(result.word);
                } else {
                    errors += 1;
                    let errorSrc = $('#hangman-pic').attr('data-error' + errors);
                    $('#hangman-pic').attr('src', errorSrc);
                }
                redirectAfterGame(result);
            }
        });
    } else {
        window.location = redirectUrl;
    }
}

function redirectAfterGame(data) {
    if (data.redirect) {
        let msg = 'Играта свърши, ';
        if (data.isWinner) {
            msg += 'позна думата !';
        } else {
            msg += 'не позна думата !';
        }
        let errorSrc = $('#hangman-pic').attr('data-error5');
        $('#hangman-pic').attr('src', errorSrc);
        setTimeout(
            alertFunc(msg)
            , 5000)
        window.location = redirectUrl;
    }
}

function alertFunc(msg) {
    alert(msg);
}
