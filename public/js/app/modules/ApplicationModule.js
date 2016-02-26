define([
    'pusher',
    'angular',
    'controllers/SignInController',
    'controllers/DashboardController',
    'controllers/GroupsController',
    'controllers/GroupsEditController',
    'services/GroupsDataService',
    'services/ApplicationPusherService',
    'pusher-angular',
    'angular-ui-router',
    'angular-ui-notification',
    'angular-storage',
    'angular-aria',
    'angular-animate',
    'angular-material',
    'angular-moment',
    'raven-js-angular',
    'satellizer'
], function (
    Pusher,
    angular,
    wlSignInController,
    wlDashboardController,
    wlGroupsController,
    wlGroupsEditController,
    wlGroupsDataService,
    wlApplicationPusherService
) {
    var wlApplication = angular.module('wlApplication', ['ui.router', 'ui-notification', 'pusher-angular', 'ngAria', 'ngAnimate', 'ngMaterial', 'ngStorage', 'angularMoment', 'satellizer'])

    // set constants for easy to change base URLs
    .constant('urls', {
        BASE: 'http://www.workout-lottery.local',
        BASE_API: 'http://www.workout-lottery.local/api',
        BASE_TEMPLATES: 'http://www.workout-lottery.local/template'
    })

    .filter('reduce', function() {
        return function (array, field, delimiter) {
            delimiter = delimiter || ", ";

            if (array.length > 0) {
                return array.reduce(function (prev, current, index, arr) {
                    return prev[field] + delimiter + current[field];
                });
            } else {
                return '';
            }
        };
    })

    // automatically add CSRF Token to AJAX calls
    .run( ['$http', function( $http ){
        $http.defaults.headers.common['X-CSRF-TOKEN'] = angular.element('head').find('meta[name=csrf_token]')[0].content;
    }])

    .run(['$rootScope', '$auth', '$state', 'Notification', function($rootScope, $auth, $state, Notification) {

        // Listen to '$locationChangeSuccess', not '$stateChangeStart'
        $rootScope.$on('$stateChangeStart', function(ev, toState, toParams, fromState, fromParams, options) {
            if (toState.data.needsAuthentication && !$auth.isAuthenticated()) {
                ev.preventDefault();
                Notification.error('Please log in first to see this page');
                $state.go('public.signin');
            }
        });
    }])

    .config(['NotificationProvider', function(NotificationProvider) {
        NotificationProvider.setOptions({
            startTop: 20,
            startRight: 10,
            verticalSpacing: 20,
            horizontalSpacing: 20,
            positionX: 'right',
            positionY: 'top'
        });
    }])

    .config(['$stateProvider', '$urlRouterProvider', 'urls', function($stateProvider, $urlRouterProvider, urls) {
        //
        // For any unmatched url, redirect to /state1
        $urlRouterProvider.otherwise("/dashboard");
        //
        // Now set up the states
        $stateProvider
            .state('restricted', {
                url : '/',
                data : {
                    needsAuthentication : true
                }
            })
            .state('restricted.dashboard', {
                url: "dashboard",
                views: {
                    "content@": {
                        templateUrl: urls.BASE_TEMPLATES + '/dashboard',
                        controller: 'wlDashboardController'
                    }
                }
            })
            .state('restricted.groups', {
                url: "groups",
                views: {
                    "content@": {
                        templateUrl: urls.BASE_TEMPLATES + '/groups',
                        controller: 'wlGroupsController'
                    }
                }
            })
            .state('restricted.groups.edit', {
                url: "/edit/:group_id",
                views: {
                    "content@": {
                        templateUrl: urls.BASE_TEMPLATES + '/groups-edit',
                        controller: 'wlGroupsEditController'
                    }
                }
            })
            .state('public', {
                url : '/public',
                data : {
                }
            })
            .state('public.signin', {
                url: "/signin",
                views: {
                    "content@": {
                        templateUrl: urls.BASE_TEMPLATES + '/signin',
                        controller: 'wlSignInController'
                    }
                }
            })
            .state('public.logout', {
                url: "/logout",
                views: {
                    "content@": {
                        template: 'Logging out...',
                        controller: ['$auth', '$state', function ($auth, $state) {
                            $auth.logout();
                            $state.go('public.signin');
                        }]
                    }
                }
            });
    }])

    .factory('wlApplicationPusherService', wlApplicationPusherService)
    .factory('wlGroupsDataService', wlGroupsDataService)

    .controller('wlSignInController', wlSignInController)
    .controller('wlDashboardController', wlDashboardController)
    .controller('wlGroupsController', wlGroupsController)
    .controller('wlGroupsEditController', wlGroupsEditController)
    .controller('wlNavigationController', ['$scope', '$mdSidenav', '$auth', function ($scope, $mdSidenav, $auth) {
        $scope.toggleNav = function() {
            $mdSidenav('left').toggle();
            navIsOpen = !navIsOpen;
        }

        var navIsOpen = false;
        $scope.isNavOpen = function() {
            return navIsOpen;
        }

        $scope.isSignedIn = $auth.isAuthenticated;
    }]);

    return wlApplication;
});
