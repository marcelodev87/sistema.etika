"use strict";


function init() {
    middleAuthBox();
}

window.addEventListener("resize", function () {
    init();
});

init();

// auth form middle screen
function middleAuthBox() {
    var box = document.getElementById('middleAuthBox');
    if (box) {
        var screenHeight = window.innerHeight;
        var boxHeight = box.clientHeight;
        var marginHeight = (screenHeight - boxHeight) / 2;
        box.style.marginTop = marginHeight + 'px';
    }
}

// zip
$('#zip').on('change', function(){
    var cep = $(this).val().replace(/\D/g, '');
    if (cep !== "") {
        var validacep = /^[0-9]{8}$/;
        if (validacep.test(cep)) {
            $(this).closest("#street").val("...");
            $("#neighborhood").val("...");
            $("#city").val("...");
            $("#state").val("...");
            $.getJSON("//viacep.com.br/ws/" + cep + "/json/?callback=?", function (dados) {
                if (!("erro" in dados)) {
                    $("#street").val(dados.logradouro);
                    $("#neighborhood").val(dados.bairro);
                    $("#city").val(dados.localidade);
                    $("#state").val(dados.uf);
                    $("#street_number").focus();
                }else {
                    alert("CEP não encontrado no Brasil, porém, você pode prosseguir colocando os dados manualmente");
                }
            });
        } else {
            alert("Formato de CEP inválido.");
        }
    }
});

// DOCUMENT MASK
$(document).on('keydown', '.document-mask', function (e) {

    var digit = e.key.replace(/\D/g, '');

    var value = $(this).val().replace(/\D/g, '');

    var size = value.concat(digit).length;

    $(this).mask((size <= 11) ? '000.000.000-00' : '00.000.000/0000-00');
});

// PHONE MASK
var SPMaskBehavior = function (val) {
        return val.replace(/\D/g, '').length === 11 ? '(00) 00000-0000' : '(00) 0000-00009';
    },
    spOptions = {
        onKeyPress: function(val, e, field, options) {
            field.mask(SPMaskBehavior.apply({}, arguments), options);
        }
    };
$('.phone-mask').mask(SPMaskBehavior, spOptions);


$(document).keyup(function (e) {
    if (e.key === "Escape") { // escape key maps to keycode `27`
        var $comment = $('.sidebar-right-blank');
        if (!$comment.hasClass('hide')) {
            hideComments()
        }
    }
});

function hideComments() {
    var $comment = $('.sidebar-right-blank');
    $comment.addClass('hide');
    $comment.find('.body').html('');
}

$(function () {
    $('.selectpicker').selectpicker({
        liveSearch: true
    });
});
