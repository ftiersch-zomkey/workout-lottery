define(['angular'], function (angular) {
    function wlGroupsEditController($scope, $state, $stateParams, Notification, $http, urls, Auth, wlGroupsDataService) {
        $scope.group = {};
        $scope.loggedInUser = Auth.user;

        function loadGroup() {
            wlGroupsDataService.getSingleGroup($stateParams.group_id).then(function (group) {
                $scope.group = group;
                $scope.$apply();
            }, function (errors) {

            });
        }

        function activate() {
            loadGroup();
        }

        activate();
    }

    wlGroupsEditController.$inject = ['$scope', '$state', '$stateParams', 'Notification', '$http', 'urls', 'Auth', 'wlGroupsDataService'];

    return wlGroupsEditController;
});