(function ($) {
    "use strict";
    var pluginName = 'jumaQuiz',
        option,
        defaults = {
            container: 'juma-quiz',
            progress: true,
            nextAuto: false,
            showFinalScore: false,
            manyCount: false,
            isSave: false,
            code: false,
            pollidmain: false
        },
        ques,
        forward,
        answers,
        cur_ques,
        tmp_points = [],
        points = 0,
        score_child,
        score_i = 0,
        progress,
        progress_w,
        progress_bar,
        chooseCount = 0,
        con,
        $pollid,
        $quesid,
        $ansid,
        $text,
        $otherid,
        many = [],
        con_w;


    function Plugin(element, options) {
        this.element = element;

        this.options = $.extend({}, defaults, options);

        option = this.options;

        this._defaults = defaults;
        this._name = pluginName;

        this.init();
    }

    function addProgress() {
        $(con).before('<div class="juma-progress"><span></span></div>');
        con_w = $(con).outerWidth();
        progress = '.juma-progress';
        progress_bar = $(progress + '> span');
        $(progress).css('width', con_w);
    }

    function setActive(ques) {
        if (!option.nextAuto) {
            $(con).after('<button type="submit" style="padding: 10px 80px;" title="Дараагийн асуулт" class="juma-forward btn btn-warning">Дараах</button>');
        }

        forward = $('.juma-forward');
        forward.attr("disabled", "disabled");
        if (!$(ques).hasClass('active')) {
            $(ques + ':first-child').addClass('active');
        }
    }

    function answer() {
        answers = $(ques + '> ul li');

        answers.on('click keydown', function (ev) {

            if (ev.which == 1 || ev.which == 13) {

                if ($(this).hasClass('manychoice')) {

                    if ($(this).hasClass('juma-choice')) {
                        $(this).removeClass('juma-choice');
                        $(this).attr('aria-checked', false);
                        $(this).attr("disabled", "disabled");
                        chooseCount = chooseCount - 1;
                        var index = many.indexOf($(this).find('p').text());
                        if (index > -1) {
                            many.splice(many.indexOf($(this).find('p').text()), 1);
                        }
                    } else {
                        if (chooseCount < $(this).attr('aria-count')) {
                            cur_ques = $(ques).index($(".active")) + 1;
                            forward.removeAttr("disabled");
                            $(this).addClass('juma-choice');
                            $(this).attr('aria-checked', true);
                            chooseCount = chooseCount + 1;
                            $pollid = $(this).attr('aria-pollid');
                            $quesid = $(this).attr('aria-quesid');
                            many.push($(this).find('p').text());
                        }
                        else {
                            alert('Та ' + $(this).attr('aria-count') + ' хариулт сонгох боломжтой!')
                        }
                    }
                    $otherid = $(this).attr('aria-otherid');
                    $ansid = many;
                } else {
                    answers.removeClass('juma-choice');
                    answers.attr('aria-checked', false);
                    forward.attr("disabled", "disabled");
                    cur_ques = $(ques).index($(".active")) + 1;
                    forward.removeAttr("disabled");
                    $(this).addClass('juma-choice');
                    $(this).attr('aria-checked', true);
                    $pollid = $(this).attr('aria-pollid');
                    $quesid = $(this).attr('aria-quesid');
                    $ansid = $(this).find('p').text();
                    $otherid = $(this).attr('aria-otherid');
                }
                if (option.nextAuto) {
                    nextQuestion();
                }
            }
        });
        if (!option.nextAuto) {
            forward.click(function () {
                nextQuestion();
                if (option.isSave) {
                    $text = $('#otherdata' + $otherid).val();
                    insertData($pollid, $quesid, $ansid, $text);
                }
                chooseCount = 0;
                $ansid = '';
                $pollid = '';
                $quesid = '';
                $ansid = '';
                many = [];
                $text = '';
            });
        }
    }

    function insertData($pollid, $quesid, $ansid, $text) {

        $.ajax({
            url: 'https://www.yolo.mn/insertdatapoll',
//            url: 'http://dev.yolo.mn:888/insertdatapoll',
            type: "POST",
            data: {poll: $pollid, ques: $quesid, ans: $ansid.toString(), text: $text, code: option.code },
            dataType: "json", //butsaj ireh responsiin torol
            success: function (data) {
                console.log('ok');
            },
            error: function (result) {
                console.log('aldaa nemeh');
            }
        });
    }

    function nextQuestion() {
        tmp_points[cur_ques] = $('.juma-choice').data('points');
        points += tmp_points[cur_ques];
        cur_ques = $(ques + '.active');
        if (cur_ques.is(':last-child')) {
            $(progress + ' > span').css('width', con_w);
            $(con).hide();
            forward.hide();
            score_child = $('.juma-score li').length;
            for (score_i; score_i < score_child; score_i += 1) {
                console.log(option.pollidmain);
                if (option.pollidmain) {
                    $.cookie("oneTimecookie" + option.pollidmain, true);
                }
                var a = $('.juma-score li')[score_i];
                if (points <= $(a).data('points')) {
                    $(a).slideDown('slow');
                    if (option.showFinalScore) {
                        $('.juma-final-score').text(points);
                    }
                    return;
                }
            }

        } else {
            $(ques).removeClass('active').hide();
            (cur_ques).next('li').addClass('active').fadeIn('slow');
            forward.attr("disabled", "disabled");
            if (option.progress) {
                progress_bar.css('width', con_w / $(ques).length);
                progress_w = parseInt(progress_bar.css('width'), 10);
                $(progress + ' > span').css('width', progress_w * ($(ques).index($(".active"))) + 'px');
            }
        }
    }

    Plugin.prototype = {
        init: function () {
            con = '#' + option.container;
            ques = con + ' > li';
            if (option.progress) {
                addProgress();
            }
            setActive(ques);
            answer();
        }
    };

    $.fn[pluginName] = function (options) {
        return this.each(function () {
            if (!$.data(this, 'plugin_' + pluginName)) {
                $.data(this, 'plugin_' + pluginName,
                    new Plugin(this, options));
            }
        });
    };

}(jQuery));


/*!
 * jQuery Cookie Plugin v1.4.1
 * https://github.com/carhartl/jquery-cookie
 *
 * Copyright 2006, 2014 Klaus Hartl
 * Released under the MIT license
 */
(function (factory) {
    if (typeof define === 'function' && define.amd) {
        // AMD (Register as an anonymous module)
        define(['jquery'], factory);
    } else if (typeof exports === 'object') {
        // Node/CommonJS
        module.exports = factory(require('jquery'));
    } else {
        // Browser globals
        factory(jQuery);
    }
}(function ($) {

    var pluses = /\+/g;

    function encode(s) {
        return config.raw ? s : encodeURIComponent(s);
    }

    function decode(s) {
        return config.raw ? s : decodeURIComponent(s);
    }

    function stringifyCookieValue(value) {
        return encode(config.json ? JSON.stringify(value) : String(value));
    }

    function parseCookieValue(s) {
        if (s.indexOf('"') === 0) {
            // This is a quoted cookie as according to RFC2068, unescape...
            s = s.slice(1, -1).replace(/\\"/g, '"').replace(/\\\\/g, '\\');
        }

        try {
            // Replace server-side written pluses with spaces.
            // If we can't decode the cookie, ignore it, it's unusable.
            // If we can't parse the cookie, ignore it, it's unusable.
            s = decodeURIComponent(s.replace(pluses, ' '));
            return config.json ? JSON.parse(s) : s;
        } catch (e) {
        }
    }

    function read(s, converter) {
        var value = config.raw ? s : parseCookieValue(s);
        return $.isFunction(converter) ? converter(value) : value;
    }

    var config = $.cookie = function (key, value, options) {

        // Write

        if (arguments.length > 1 && !$.isFunction(value)) {
            options = $.extend({}, config.defaults, options);

            if (typeof options.expires === 'number') {
                var days = options.expires, t = options.expires = new Date();
                t.setMilliseconds(t.getMilliseconds() + days * 864e+5);
            }

            return (document.cookie = [
                encode(key), '=', stringifyCookieValue(value),
                options.expires ? '; expires=' + options.expires.toUTCString() : '', // use expires attribute, max-age is not supported by IE
                options.path ? '; path=' + options.path : '',
                options.domain ? '; domain=' + options.domain : '',
                options.secure ? '; secure' : ''
            ].join(''));
        }

        // Read

        var result = key ? undefined : {},
        // To prevent the for loop in the first place assign an empty array
        // in case there are no cookies at all. Also prevents odd result when
        // calling $.cookie().
            cookies = document.cookie ? document.cookie.split('; ') : [],
            i = 0,
            l = cookies.length;

        for (; i < l; i++) {
            var parts = cookies[i].split('='),
                name = decode(parts.shift()),
                cookie = parts.join('=');

            if (key === name) {
                // If second argument (value) is a function it's a converter...
                result = read(cookie, value);
                break;
            }

            // Prevent storing a cookie that we couldn't decode.
            if (!key && (cookie = read(cookie)) !== undefined) {
                result[name] = cookie;
            }
        }

        return result;
    };

    config.defaults = {};

    $.removeCookie = function (key, options) {
        // Must not alter options, thus extending a fresh object...
        $.cookie(key, '', $.extend({}, options, { expires: -1 }));
        return !$.cookie(key);
    };

}));


