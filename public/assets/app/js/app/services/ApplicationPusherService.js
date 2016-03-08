define(['pusher'], function (Pusher) {
    function wlApplicationPusherService($pusher, $http) {
        var service = {
            pusher : null,
            init : function() {
                var self = this;
            },
            getPusher : function() {
                if (this.pusher == null) {
                    this.init();
                }

                return this.pusher;
            }
        }

        service.init();

        return service;
    }

    wlApplicationPusherService.$inject = ['$pusher', '$http'];

    return wlApplicationPusherService;
});