define(['angular'], function (angular) {
    function wlDashboardController($scope, $state, Notification, $http, urls) {
        function activate() {
            $http.get(urls.BASE_API + '/test').then(function(result) {
                Notification.success('Got something!');
            });
        }

        activate();
    }

    wlDashboardController.$inject = ['$scope', '$state', 'Notification', '$http', 'urls'];

    return wlDashboardController;
});