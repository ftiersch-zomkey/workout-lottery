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
    'raven-js-angular'
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
    var wlApplication = angular.module('wlApplication', ['ui.router', 'ui-notification', 'pusher-angular', 'ngAria', 'ngAnimate', 'ngMaterial', 'ngStorage'])

    // set constants for easy to change base URLs
    .constant('urls', {
        BASE: 'http://www.workout-lottery.local',
        BASE_API: 'http://www.workout-lottery.local/api',
        BASE_TEMPLATES: 'http://www.workout-lottery.local/template'
    })

    // automatically add CSRF Token to AJAX calls
    .run( ['$http', function( $http ){
        $http.defaults.headers.common['X-CSRF-TOKEN'] = angular.element('head').find('meta[name=csrf_token]')[0].content;
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

    // automatically add the jwt token to any request and redirect to login on forbidden requests
    .config(['$httpProvider', function ($httpProvider) {
        $httpProvider.interceptors.push(['$q', '$localStorage', '$injector', function ($q, $localStorage, $injector) {
            return {
                'request': function (config) {
                    config.headers = config.headers || {};
                    if ($localStorage.token) {
                        config.headers.Authorization = 'Bearer ' + $localStorage.token;
                    }
                    return config;
                },
                'responseError': function (response) {
                    if (response.status === 401 || response.status === 403) {
                        $injector.get('$state').transitionTo('public.signin');
                    }
                    return $q.reject(response);
                }
            };
        }]);
    }])

    .config(['$stateProvider', '$urlRouterProvider', 'urls', function($stateProvider, $urlRouterProvider, urls) {
        //
        // For any unmatched url, redirect to /state1
        $urlRouterProvider.otherwise("/dashboard");
        //
        // Now set up the states
        $stateProvider
            .state('restricted', {
                url : '/'
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
                url : '/public'
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
                        controller: ['Auth', '$state', function (Auth, $state) {
                            Auth.logout(function () {
                                $state.go('public.signin');
                            });
                        }]
                    }
                }
            });
    }])

    .factory('wlApplicationPusherService', wlApplicationPusherService)
    .factory('wlGroupsDataService', wlGroupsDataService)

    .factory('Auth', ['$http', '$localStorage', 'urls', function ($http, $localStorage, urls) {
        function urlBase64Decode(str) {
            var output = str.replace('-', '+').replace('_', '/');
            switch (output.length % 4) {
                case 0:
                    break;
                case 2:
                    output += '==';
                    break;
                case 3:
                    output += '=';
                    break;
                default:
                    throw 'Illegal base64url string!';
            }
            return window.atob(output);
        }

        function getClaimsFromToken() {
            var token = $localStorage.token;
            var user = {};
            if (typeof token !== 'undefined') {
                var encoded = token.split('.')[1];
                user = JSON.parse(urlBase64Decode(encoded));
            }
            return user;
        }

        var tokenClaims = getClaimsFromToken();
        var isSignedIn = ($localStorage.token != null);

        return {
            signup: function (data, success, error) {
                $http.post(urls.BASE + '/signup', data).success(success).error(error)
            },
            signin: function (data, success, error) {
                $http.post(urls.BASE + '/auth', data).success(function(res) {
                    $localStorage.token = res.token;
                    isSignedIn = true;
                    success(res);
                }).error(error)
            },
            logout: function (success) {
                tokenClaims = {};
                delete $localStorage.token;
                isSignedIn = false;
                success();
            },
            isSignedIn: function() {
                return isSignedIn;
            },
            getTokenClaims: function () {
                return tokenClaims;
            }
        };
    }])

    .controller('wlSignInController', wlSignInController)
    .controller('wlDashboardController', wlDashboardController)
    .controller('wlGroupsController', wlGroupsController)
    .controller('wlGroupsEditController', wlGroupsEditController)
    .controller('wlNavigationController', ['$scope', '$mdSidenav', 'Auth', function ($scope, $mdSidenav, Auth) {
        $scope.toggleNav = function() {
            $mdSidenav('left').toggle();
            navIsOpen = !navIsOpen;
        }

        var navIsOpen = false;
        $scope.isNavOpen = function() {
            return navIsOpen;
        }

        $scope.isSignedIn = Auth.isSignedIn;
    }]);

    return wlApplication;
});
