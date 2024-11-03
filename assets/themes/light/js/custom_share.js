'use strict';

$.fn.socialSharingPlugin = function(options){
    let settings = $.extend({
            title: '',
            description: '',
            img: '',
            url: '',
            btnClass: '',
            enable: null,
            responsive: false,
            mobilePosition: 'left',
            copyMessage: 'Copied to clipboard',
            copyTooltipPosition: 'center-bottom'
        }, options),
        defaults = {
            popupWidth: 640,
            popupHeight: 528,
            copyTop: 0,
            copyLeft: 0,
            copyTxt: '',
            copyTimeout: 5000,
            appName: 'SocialJS:'
        },
        urls = {

            facebook: {
                icon: 'fa-brands fa-facebook-f',
                color: '#4267B2',
                url: 'https://www.facebook.com/sharer.php?u=[post-url]',
                title: 'Share on Facebook'
            },
            twitter: {
                icon: 'fab fa-twitter',
                color: '#00acee',
                url: 'https://twitter.com/share?url=[post-url]&text=[post-desc]',
                title: 'Share on Twitter'
            },
            pinterest: {
                icon: 'fab fa-pinterest',
                color: '#E60023',
                url: 'https://pinterest.com/pin/create/bookmarklet/?media=[post-img]&url=[post-url]&description=[post-title]',
                title: 'Share on Pinterest'
            },
            linkedin: {
                icon: 'fa-brands fa-linkedin-in',
                color: '#0072b1',
                url: 'https://www.linkedin.com/shareArticle?url=[post-url]&title=[post-title]',
                title: 'Share on Linkedin'
            },
            reddit: {
                icon: 'fab fa-reddit',
                color: '#FF5700',
                url: 'https://reddit.com/submit?url=[post-url]&title=[post-title]',
                title: 'Share on Reddit'
            },
            stumbleupon:{
                icon: 'fab fa-stumbleupon',
                color: '#f74425',
                url: 'https://www.stumbleupon.com/submit?url=[post-url]&title=[post-title]',
                title: 'Share on StumbleUpon'
            },
            pocket:{
                icon: 'fab fa-get-pocket',
                color: '#E60023',
                url: 'https://getpocket.com/save?url=[post-url]&title=[post-title]',
                title: 'Share on Pocket'
            },
            email:{
                icon: 'fas fa-envelope',
                color: '#5522a4',
                url: 'mailto:?subject=[post-title]&body=Check out this site: [post-url]',
                title: 'Share on Email'
            },
            whatsapp:{
                icon: 'fab fa-whatsapp',
                color: '#00e676',
                url: 'https://wa.me/?text=[post-title]+[post-url]',
                title: 'Share on Whatsapp'
            },
            telegram:{
                icon: 'fa-brands fa-telegram',
                color: '#00acee',
                url: 'https://telegram.me/share/url?text=[post-title]&url=[post-url]',
                title: 'Share on Telegram'
            }
        },

        copyTrigger = function(text){
            let tooltip = $('<div class="socialJS-custom-tooltip ' + settings.copyTooltipPosition + '"><i class="fas fa-share text-warning"></i> ' + settings.copyMessage + '</div>');
            $.each($(document).find('.socialJS-custom-tooltip'), function () {
                $(this).remove();
            });
            $('body').append(tooltip);
            tooltip.animate({
                opacity: 1
            }, 300);
            setTimeout(function () {
                tooltip.animate({
                    opacity: 0
                }, 300);
                setTimeout(function () {
                    tooltip.remove()
                }, 300);
            }, defaults.copyTimeout);
        },
        build = function (e) {
            if(!$.isArray(settings.enable))
            {
                console.error(defaults.appName + ' You must enable at least 1 social link');
                return;
            }
            let $splugin = $('<div class="socialJS row d-flex justify-content-center">');
            let customDiv1 =  $('<div class="col-md-6">');
            let customDiv2 =  $('<div class="col-md-6">');
            if(settings.responsive)
            {
                $splugin.addClass('responsive');
                $splugin.addClass(settings.mobilePosition);
            }

            $.each(settings.enable, function (k, v) {
                if(!urls[v])
                {
                    console.error(defaults.appName + ' ' + v + ' is not a valid url');
                    return;
                }
                let $element = $('<a>');

                let link = urls[v].url
                    .replace('[post-title]', encodeURIComponent(settings.title))
                    .replace('[post-url]', encodeURIComponent(settings.url))
                    .replace('[post-img]', encodeURIComponent(settings.img))
                    .replace('[post-desc]', encodeURIComponent(settings.description));
                $element.addClass(settings.btnClass);
                $element.attr('data-action', 'social-share');
                $element.addClass( `social-share ${v}`);
                $element.attr('href', link);
                $element.css('background-color', urls[v].color);

                let customRow = $('<div class="row d-flex justify-content-center">');
                let customDiv = $('<div class="d-flex justify-content-between align-items-center">');
                customDiv.append('<i class="' + urls[v].icon + '" style="color:' + urls[v].color + '"></i>');
                customDiv.append(`<span>${urls[v].title}</span>`);

                $element.append(customDiv)
                if(k <= 3){
                    customDiv1.append($element);
                }else {
                    customDiv2.append($element);
                }
                // $splugin.append($element);
            });
            $splugin.append(customDiv1).append(customDiv2);
            e.append($splugin);

            $(document).on('click', '[data-action="social-share"]', function (ele) {
                ele.preventDefault();
                if($(this).attr('href').startsWith('copy:')){
                    defaults.copyTop = e.pageY + 25;
                    defaults.copyLeft = e.pageX - 15;
                    copyTrigger(decodeURIComponent($(this).attr('href').replace('copy:', '')));
                } else {
                    let top = (screen.height / 2) - (defaults.popupHeight / 2),
                        left = (screen.width / 2) - (defaults.popupWidth / 2);
                    window.open($(this).data('href') || $(this).attr('href'), 't', 'toolbar=0,resizable=1,status=0,copyhistory=no,width=' + defaults.popupWidth + ',height=' + defaults.popupHeight + ',top=' + top + ',left=' + left);
                }
            });

            $(document).on('click', '.socialJS-custom-tooltip', function () {
                $(this).remove();
            })
        };

    return this.each(function() {
        return new build($(this));
    });
};
