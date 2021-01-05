(function ($) {
    showSuccessToast = function(){
        'use strict';
        resetToastPosition();
        $.toast({
            heading: 'Validation',
            text: 'Votre éditions à bien été prise en compte.',
            showHideTransition: 'slide',
            icon: 'success',
            loaderBg: '#f96868',
            position: 'top-right'
        })
    };
    showInfoToast = function(){
        'use strict';
        resetToastPosition();
        $.toast({
            heading: 'Info',
            text: 'And these were just the basic demos! Scroll down to check further details on how to customize the output.',
            showHideTransition: 'slide',
            icon: 'info',
            loaderBg: '#46c35f',
            position: 'top-right'
        })
    };
    showWarningToast = function(){
        'use strict';
        resetToastPosition();
        $.toast({
            heading: 'Erreur',
            text: "Une erreur à eu lieu merci de réessayer ulterieurement.",
            showHideTransition: 'slide',
            icon: 'warning',
            loaderBg: '#57c7d4',
            position: 'top-right'
        })
    };
    showDangerToast = function(){
        'use strict';
        resetToastPosition();
        $.toast({
            heading: 'txt',
            text: 'Vous avez anulér votre modification.',
            showHideTransition: 'slide',
            icon: 'error',
            loaderBg: '#f2a654',
            position: 'top-right'
        })
    };
    showToastPosition = function(position) {
        'use strict';
        resetToastPosition();
        $.toast({
            heading: 'Positioning',
            text: 'Specify the custom position object or use one of the predefined ones',
            position: String(position),
            icon: 'info',
            stack: false,
            loaderBg: '#f96868'
        })
    };
    showToastInCustomPosition = function() {
        'use strict';
        resetToastPosition();
        $.toast({
            heading: 'Custom positioning',
            text: 'Specify the custom position object or use one of the predefined ones',
            icon: 'info',
            position: {
                left: 120,
                top: 120
            },
            stack: false,
            loaderBg: '#f96868'
        })
    };
    resetToastPosition = function() {
        $('.jq-toast-wrap').removeClass('bottom-left bottom-right top-left top-right mid-center'); // to remove previous position class
        $(".jq-toast-wrap").css({"top": "", "left": "", "bottom":"", "right": ""}); //to remove previous position style
    };
    VehicleDeleted = function(){
        'use strict';
        resetToastPosition();
        $.toast({
            heading: 'Véhicule supprimé',
            text: 'Le véhicule a bien été supprimé.',
            showHideTransition: 'slide',
            icon: 'success',
            loaderBg: '#f96868',
            position: 'top-right'
        })
    };
    HomeDeleted = function(){
        'use strict';
        resetToastPosition();
        $.toast({
            heading: 'Propriété supprimée',
            text: 'La propriété a bien été supprimée.',
            showHideTransition: 'slide',
            icon: 'success',
            loaderBg: '#f96868',
            position: 'top-right'
        })
    };
    SuccessEdit = function(){
        'use strict';
        resetToastPosition();
        $.toast({
            heading: 'Modifications sauvgardés',
            text: 'Les changements ont bien été pris en compte.',
            showHideTransition: 'slide',
            icon: 'success',
            loaderBg: '#f96868',
            position: 'top-right'
        })
    };
    CancelEdit = function(){
        'use strict';
        resetToastPosition();
        $.toast({
            heading: 'Edition Interompu',
            text: 'Vous avez annulé l\'édition.',
            showHideTransition: 'slide',
            icon: 'error',
            loaderBg: '#f2a654',
            position: 'top-right'
        })
    }
    CancelRefund = function(){
        'use strict';
        resetToastPosition();
        $.toast({
            heading: 'Annulation du remboursement',
            text: 'Vous avez annulé le remboursement.',
            showHideTransition: 'slide',
            icon: 'error',
            loaderBg: '#f2a654',
            position: 'top-right'
        })
    }
    SuccessRefund = function(){
        'use strict';
        resetToastPosition();
        $.toast({
            heading: 'Remboursement Effectué',
            text: 'Le remboursement a bien été pris en compte',
            showHideTransition: 'slide',
            icon: 'success',
            loaderBg: '#f96868',
            position: 'top-right'
        })
    };
    NoExistPlayerRefund = function(){
        'use strict';
        resetToastPosition();
        $.toast({
            heading: 'UID invalide',
            text: 'l\'UID du joueur est invalide.',
            showHideTransition: 'slide',
            icon: 'error',
            loaderBg: '#f2293a',
            position: 'top-right'
        })
    };
    successAddStaff = function(){
        'use strict';
        resetToastPosition();
        $.toast({
            heading: 'Staff Ajouté',
            text: 'Le groupe du membre a bien été modifié !',
            showHideTransition: 'slide',
            icon: 'success',
            loaderBg: '#f96868',
            position: 'top-right'
        })
    };


    errorRequestToast = function(){
        'use strict';
        resetToastPosition();
        $.toast({
            heading: 'Erreur avec la requette',
            text: 'Consultez la console',
            showHideTransition: 'slide',
            icon: 'error',
            loaderBg: '#f2293a',
            position: 'top-right'
        })
    };
})(jQuery);