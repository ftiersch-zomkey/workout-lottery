require.config({
    baseUrl: 'js/app',
    paths: {
        "jquery": '../libs/jquery/dist/jquery',
        "pusher": '../libs/pusher/dist/pusher',
        "moment": '../libs/moment/min/moment.min',
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
        "angular-material": "../libs/angular-material/angular-material.min",
        "angular-moment": "../libs/angular-moment/angular-moment.min",
        "raven-js": "../libs/raven-js/dist/raven.min",
        "raven-js-angular": "../libs/raven-js/dist/plugins/angular.min",
        "satellizer" : "../libs/satellizer/satellizer.min"
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
        "angular-moment": ['angular', 'moment'],
        "pusher-angular": ['angular'],
        "raven-js-angular": ['angular', 'raven-js'],
        "satellizer": ['angular']
    }
});

requirejs(['jquery', 'raven-js', 'angular', 'modules/ApplicationModule'], function ($, Raven, angular) {
    Raven.config('https://8349a55a6f834efba8ce56bd640c272c@app.getsentry.com/67629').install();
    angular.bootstrap(document, ['wlApplication']);
});