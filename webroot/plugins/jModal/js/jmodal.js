// Version 1.1.2
// -> bind para on
function jResize() {
    var elementos = $('.jModal');
    if (elementos.length > 0) {
        var hW = $(window).height(),
            hO, mT;

        $.each(elementos, function(i,obj) {
            obj = $(obj);
            hO = obj.outerHeight();
            mT = parseInt((hW-hO)/2);

            if (mT < 0) {
                obj.css('margin-top', 0).closest('.jModalBack').css('overflow-Y', 'auto');
                $('body,html').addClass('jModalBodyScroll');
            } else {
                obj.css('margin-top', mT).closest('.jModalBack').css('overflow-Y', 'hidden');
                $('body,html').removeClass('jModalBodyScroll');
            }
        });
    } else {
        $('body,html').removeClass('jModalBodyScroll');
    }
}

$(window).bind('resize.jModal', _.throttle(jResize, 400));

$.fn.jModal = jModal = function(options) {
    var config = {
        bgClose: true,//bool - background close
        close: true,//bool - is closeble
        msg: '',//string - message
        responsive: true,//bool
        width: 'auto',//size,
        height: 'auto',//size,
        onShow: function(){},//function - on appear
        onClose: function(){},//function
        onConfirm: false,//function/false
        onCancel: false,//function/false
        className: '',
        html: {
            bg: '<div class="jModalBack"><div class="jModal"><div class="jContent"></div></div></div>',
            closeBtn: '<button class="blob text-danger glyphicon glyphicon-remove-circle jClose"></button>',
            confirmBtn: '<input type="button" name="buttonConfirm" class="jConfirm btn btn-primary" value="Confirmar">',
            cancelBtn: '<input type="button" name="buttonConfirm" class="jCancel btn btn-primary" value="Cancelar">',
            btns: '<div class="btns"></div>'
        }
    },
    element;//parcial

    $.extend(true, config, typeof options == 'object' ? options : {msg: options});

    element = $(config.html.bg)
    element.responsive = config.responsive;
    element.addClass(config.className);
    element.find('.jContent').append($(this)[0] instanceof Element ? $(this).clone(false).removeClass('hidden') : config.msg);
    $('body').append(element);

    element.find('.jModal').css(config.responsive ? {'max-width': config.width, 'max-height': config.height} : {width: config.width, height: config.height});

    if (config.width !== 'auto')
        element.find('.jModal').addClass('expansive');

    if (config.width != 'auto')
        element.find('.jContent').css('width', '100%');

    if (config.height != 'auto')
        element.find('.jContent').css('height', '100%');

    element.close = function(e) {
        if (typeof e == 'undefined' || e.target == this) {
            var close = config.onClose();
            if (typeof close == 'undefined' || close) {
                element.remove();
                $(window).trigger('resize');
            }
        }
    };

    element.onCancel = config.onCancel;

    element.cancel = function(e) {
        var cancel  = typeof element.onCancel == 'function' ? element.onCancel() : true;

        if (cancel || typeof cancel == 'undefined')
            element.close();
    };

    element.onConfirm = config.onConfirm;

    element.confirm = function() {
        var confirm  = typeof element.onConfirm == 'function' ? element.onConfirm() : true;

        if (confirm || typeof confirm == 'undefined')
            element.close();
    };

    if (config.close) {
      if(!element.find('.jClose').length)
        element.find('.jModal').prepend(config.html.closeBtn);

      if (config.bgClose)
        element.bind('click', element.close);
    }

    if (config.onCancel && !element.find('.jCancel').length) {
        if (!element.find('.btns').length)
            element.find('.jModal').append(config.html.btns);

        element.find('.btns').append(config.html.cancelBtn);
    }

    if (config.onConfirm && !element.find('.jConfirm').length) {
        if (!element.find('.btns').length)
            element.find('.jModal').append(config.html.btns);

        element.find('.btns').append(config.html.confirmBtn);
    }

    function bind(e, func) {
        e = element.find(e);
        if (e.length)
            e.bind('click', func);
    }

    element.on('click', '.jClose', element.close);
    element.on('click', '.jCancel', element.cancel);
    element.on('click', '.jConfirm', element.confirm);
    element.on('DOMSubtreeModified.jModal', _.throttle(jResize, 400));

    jResize();

    element.onShow = config.onShow;
    element.onShow();

    return element;
};
