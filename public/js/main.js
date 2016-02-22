require.config({
    baseUrl: 'js/app',
    paths: {
        "jquery": '../libs/jquery/dist/jquery',
        "pusher": '../libs/pusher/dist/pusher',
        "pusher-angular": '../libs/pusher-angular/lib/pusher-angular',
        "angular": '../libs/angular/angular',
        "angular-ui-router": '../libs/angular-ui-router/release/angular-ui-router',
        "angular-ui-router-title": '../libs/angular-ui-router-title/angular-ui-router-title',
        "angular-ui-notification": '../libs/angular-ui-notification/dist/angular-ui-notification.min',
        "angular-sanitize": '../libs/angular-sanitize/angular-sanitize',
        "angular-file-upload": '../libs/angular-file-upload/dist/angular-file-upload.min',
        "angular-storage": "../libs/ngStorage/ngStorage.min",
        "angular-aria": "../libs/angular-aria/angular-aria.min",
        "angular-animate": "../libs/angular-animate/angular-animate.min",
        "angular-material": "../libs/angular-material/angular-material.min"
    },
    shim: {
        jquery: {
            exports: '$'
        },
        angular: {
            deps: ['jquery'],
            exports: 'angular'
        },
        "angular-sanitize": ['angular'],
        "angular-ui-router": ['angular'],
        "angular-ui-notification": ['angular'],
        "angular-file-upload": ['angular'],
        "angular-storage": ['angular'],
        "angular-aria": ['angular'],
        "angular-animate": ['angular'],
        "angular-material": ['angular', 'angular-aria', 'angular-animate'],
        "pusher-angular": ['angular']
    }
});

requirejs(['jquery', 'modules/ApplicationModule'], function ($) {
    angular.bootstrap(document, ['wlApplication']);
});