define(['angular'], function (angular) {
    function wlGroupsController($scope, $state, Notification, $http, urls) {
        $scope.addGroup = addGroup;

        function addGroup() {
            console.log('test');
        }

        function activate() {

        }

        activate();
    }

    wlGroupsController.$inject = ['$scope', '$state', 'Notification', '$http', 'urls'];

    return wlGroupsController;
});