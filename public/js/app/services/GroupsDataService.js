define(['angular'], function (angular) {
    function wlGroupsDataService($http, urls) {
        var service = {
            ownGroups : null,
            detailedGroups : {},
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
                    if (self.detailedGroups[groupId]) {
                        resolve(self.detailedGroups[groupId]);
                    } else {
                        $http.get(urls.BASE_API + '/groups/' + groupId).then(function (group) {
                            self.detailedGroups[groupId] = group.data;
                            resolve(group.data);
                        }, function (errors) {
                            reject(errors.data);
                        });
                    }
                });
            },
            removeUserFromGroup : function (user, group) {
                var self = this;

                return new Promise(function (resolve, reject) {
                    $http.delete(urls.BASE_API + '/groups/' + group.id + '/users/' + user.id).then(function (result) {
                        for (var i = 0; i < group.users.length; i++) {
                            if (group.users[i].id == user.id) {
                                group.users.splice(i, 1);
                            }
                        }
                        resolve(user);
                    }, function (errors) {
                        reject(errors.data);
                    });
                });
            },
            markDrawAsSucceeded : function (draw, user) {
                var self = this;
                return new Promise(function (resolve, reject) {
                    $http.put(urls.BASE_API + '/draws/' + draw.id + '/succeeded/' + (user ? user.id : '')).then(function (result) {
                        self.getSingleGroup(draw.group_id).then(function (group) {
                            for (var i = 0; i < group.draws.length; i++) {
                                if (group.draws[i].id == draw.id) {
                                    for (var j = 0; j < group.draws[i].users.length; j++) {
                                        if (group.draws[i].users[j].id == user.id) {
                                            group.draws[i].users[j].pivot.succeeded = 1;
                                            break;
                                        }
                                    }
                                    break;
                                }
                            }
                        });
                        resolve(result.data);
                    }, function (errors) {
                        reject(errors.data);
                    });
                });
            }
        }

        return service;
    }

    wlGroupsDataService.$inject = ['$http', 'urls'];

    return wlGroupsDataService;
});