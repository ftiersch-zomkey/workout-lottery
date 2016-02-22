define(['angular'], function (angular) {
    function wlSignInController($scope, $state, Notification, Auth, $localStorage) {
        $scope.signInData = {
            email : null,
            password : null
        };

        $scope.signIn = signIn;

        function signIn() {
            Auth.signin($scope.signInData, successAuth, function () {
                Notification.error('Invalid credentials.');
            });
        }

        function successAuth(res) {
            $state.go('restricted.dashboard');
        }

        function activate() {
            if ($localStorage.token) {
                $state.go('restricted.dashboard');
            }
        }

        activate();
    }

    wlSignInController.$inject = ['$scope', '$state', 'Notification', 'Auth', '$localStorage'];

    return wlSignInController;
});