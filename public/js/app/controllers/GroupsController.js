define(['angular'], function (angular) {
    function wlGroupsController($scope, $state, Notification, $http, urls, wlGroupsDataService) {
        $scope.ownGroups = {};

        $scope.addGroup = addGroup;

        function addGroup() {
            console.log('test');
        }

        function loadOwnGroups() {
            wlGroupsDataService.getOwnGroups().then(function (groups) {
                $scope.ownGroups = groups;
                $scope.$apply();
            });
        }

        function activate() {
            loadOwnGroups();
        }

        activate();
    }

    wlGroupsController.$inject = ['$scope', '$state', 'Notification', '$http', 'urls', 'wlGroupsDataService'];

    return wlGroupsController;
});