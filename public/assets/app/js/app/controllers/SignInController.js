define(['angular'], function (angular) {
    function wlSignInController($scope, $state, Notification, wlUser) {
        $scope.signInData = {
            email : null,
            password : null
        };

        $scope.signIn = signIn;

        function signIn() {
            wlUser.login($scope.signInData).then(function (user) {
                $state.go('restricted.dashboard');
            }).catch(function (errors) {
                Notification.error('Invalid credentials.');
            });
        }

        function activate() {
            if (wlUser.isAuthenticated()) {
                $state.go('restricted.dashboard');
            }
        }

        activate();
    }

    wlSignInController.$inject = ['$scope', '$state', 'Notification', 'wlUser'];

    return wlSignInController;
});