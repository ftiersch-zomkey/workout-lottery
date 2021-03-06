define(['angular'], function (angular) {
    function wlGroupsEditController($scope, $state, $stateParams, Notification, $mdDialog, $http, urls, wlUser, wlGroupsDataService) {
        $scope.group = {};
        $scope.currentUser = {};

        $scope.removeUserFromGroup = removeUserFromGroup;
        $scope.markDrawAsSucceeded = markDrawAsSucceeded;
        $scope.userFinishedDraw = userFinishedDraw;

        function userFinishedDraw(draw) {
            for (var i = 0; i < draw.users.length; i++) {
                if (draw.users[i].id == $scope.currentUser.id) {
                    return draw.users[i].pivot.succeeded == 1;
                }
            }
            return false;
        }

        function markDrawAsSucceeded(draw) {
            wlGroupsDataService.markDrawAsSucceeded(draw, $scope.currentUser).then(function() {
                $scope.$apply();
            }, function (errors) {
                Notification.error(errors.error);
            });
        }

        function removeUserFromGroup(user, ev) {
            // Appending dialog to document.body to cover sidenav in docs app
            var confirm = $mdDialog.confirm()
                .title('Remove user ' + user.name + ' from this group?')
                .textContent('The user will lose all his progress!')
                .targetEvent(ev)
                .ok('Yes, delete')
                .cancel('No, cancel');
            $mdDialog.show(confirm).then(function() {
                wlGroupsDataService.removeUserFromGroup(user, $scope.group).then(function (user) {
                    Notification.success('User ' + user.name + ' was removed successfully from this group');
                }, function (errors) {
                    Notification.error('An unexpected error happened, please try again');
                });
            }, function() {
            });
        }

        function loadGroup() {
            wlGroupsDataService.getSingleGroup($stateParams.group_id).then(function (group) {
                $scope.group = group;
                $scope.$apply();
            }, function (errors) {

            });
        }

        function activate() {
            loadGroup();

            wlUser.getUser().then(function (user) {
                $scope.currentUser = user;
                $scope.$apply();
            });
        }

        activate();
    }

    wlGroupsEditController.$inject = ['$scope', '$state', '$stateParams', 'Notification', '$mdDialog', '$http', 'urls', 'wlUser', 'wlGroupsDataService'];

    return wlGroupsEditController;
});