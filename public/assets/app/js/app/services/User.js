define(['angular'], function (angular) {
    function wlUser($http, urls, $auth) {
        var service = {
            user : null,
            login : function (signInData) {
                var self = this;
                return new Promise(function (resolve, reject) {
                    $auth.login(signInData).then(function (response) {
                        self.user = response.data.user;
                        resolve(response.data.user);
                    }, function (errors) {
                        reject(errors.data);
                    })
                });
            },
            isAuthenticated : function() {
                if ($auth.isAuthenticated() && this.user == null) {
                    this.load();
                }
                return $auth.isAuthenticated();
            },
            load : function () {
                var self = this;
                return new Promise(function (resolve, reject) {
                    $http.get(urls.BASE_API + '/users/own').then(function (user) {
                        self.user = user.data;
                        resolve(user.data);
                    }, function (errors) {
                        reject(errors.data);
                    });
                });
            },
            getUser : function() {
                var self = this;
                return new Promise(function (resolve, reject) {
                    if (self.user != null) {
                        resolve(self.user);
                    } else {
                        self.load().then(function (user) {
                            resolve(user);
                        }, function (errors) {
                            reject(errors);
                        })
                    }
                });
            }
        }

        return service;
    }

    wlUser.$inject = ['$http', 'urls', '$auth'];

    return wlUser;
});