define(['angular'], function (angular) {
    function wlSignInController($scope, $state, Notification, $auth) {
        $scope.signInData = {
            email : null,
            password : null
        };

        $scope.signIn = signIn;

        function signIn() {
            $auth.login($scope.signInData).then(function (response) {
                $state.go('restricted.dashboard');
            }).catch(function (response) {
                Notification.error('Invalid credentials.');
            });
        }

        function activate() {
            if ($auth.isAuthenticated()) {
                $state.go('restricted.dashboard');
            }
        }

        activate();
    }

    wlSignInController.$inject = ['$scope', '$state', 'Notification', '$auth'];

    return wlSignInController;
});