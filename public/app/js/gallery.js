if ($().magnificPopup) {
    $(".gallery-img").magnificPopup({
        type: "image",
        tLoading: "Loading image #%curr%...",
        removalDelay: 600,
        mainClass: "my-mfp-slide-bottom",
        gallery: {
            enabled: true,
            navigateByImgClick: true,
            preload: [0, 1],
        },
        image: {
            tError: '<a href="%url%">The image #%curr%</a> could not be loaded.',
        },
    });
}
$(document).ready(function () {
    $.magnificPopup.instance.next = function () {
        // if index is not last, call parent method
        if (
            $.magnificPopup.instance.index <
            $.magnificPopup.instance.items.length - 1
        ) {
            $.magnificPopup.proto.next.call(this /*, optional arguments */);
        }
    };
    $.magnificPopup.instance.prev = function () {
        // if index is not first, call parent method
        if ($.magnificPopup.instance.index > 0) {
            $.magnificPopup.proto.prev.call(this /*, optional arguments */);
        }
    };

    $.magnificPopup.instance.toggleArrows = function () {
        // if index is not last, show the Next-Image Arrow Button:
        if (
            $.magnificPopup.instance.index <
            $.magnificPopup.instance.items.length - 1
        ) {
            $(".mfp-arrow-right").show();
        }
        // if index is last, hide the Next-Image Arrow Button:
        if (
            $.magnificPopup.instance.index ==
            $.magnificPopup.instance.items.length - 1
        ) {
            $(".mfp-arrow-right").hide();
        }

        // if index is not first, show the Previous-Image Arrow Button:
        if ($.magnificPopup.instance.index > 0) {
            $(".mfp-arrow-left").show();
        }
        // if index is first, hide the Previous-Image Arrow Button:
        if ($.magnificPopup.instance.index == 0) {
            $(".mfp-arrow-left").hide();
        }
    };

    $.magnificPopup.instance.updateItemHTML = function () {
        $.magnificPopup.instance.toggleArrows();
        // You may call parent ("original") method like so:
        $.magnificPopup.proto.updateItemHTML.call(this /*, optional arguments */);
    };
});
