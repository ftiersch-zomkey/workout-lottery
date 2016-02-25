define(['angular'], function (angular) {
    function wlGroupsDataService($http, urls) {
        var service = {
            ownGroups : null,
            getOwnGroups : function (forceReload) {
                forceReload = forceReload || false;
                var self = this;

                return new Promise(function (resolve, reject) {
                    if (self.ownGroups == null || forceReload) {
                        $http.get(urls.BASE_API + '/groups/own').then(function (groups) {
                            self.ownGroups = groups.data;
                            resolve(groups.data);
                        }, function (errors) {
                            reject(errors.data);
                        })
                    } else {
                        resolve(self.ownGroups);
                    }
                });
            },
            getSingleGroup : function (groupId) {
                var self = this;

                return new Promise(function (resolve, reject) {
                    $http.get(urls.BASE_API + '/groups/' + groupId).then(function (group) {
                        resolve(group.data);
                    }, function (errors) {
                        reject(errors.data);
                    })
                });
            }
        }

        return service;
    }

    wlGroupsDataService.$inject = ['$http', 'urls'];

    return wlGroupsDataService;
});