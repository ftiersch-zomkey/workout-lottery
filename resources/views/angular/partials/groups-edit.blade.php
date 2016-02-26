<h1>@{{ group.name }}</h1>
<md-content layout="row">
    <md-content flex>
        <md-list>
            <md-subheader>Latest Draws</md-subheader>
            <md-list-item class="md-3-line" ng-repeat="draw in group.draws">
                <div class="md-list-item-text">
                    <h4><span ng-repeat="exercise in draw.exercises">@{{ exercise.name }}: @{{ exercise.pivot.reps }} ·</span></h4>
                    <h3>
                        <span ng-repeat="user in draw.users">
                            <md-icon md-font-set="material-icons">@{{ (user.pivot.succeeded == 1) ? 'check' : 'highlight_off' }}</md-icon>
                            @{{ user.name }}
                        </span>
                    </h3>
                    <p am-time-ago="draw.created_at | amParse:'YYYY-MM-DD HH:mm:ss'"></p>
                    <md-icon md-font-set="material-icons" class="md-secondary" ng-click="markDrawAsSucceeded(draw)" ng-if="!userFinishedDraw(draw) && draw.can_still_succeed">check</md-icon>
                </div>
            </md-list-item>
        </md-list>
    </md-content>
    <md-content flex>
        <md-list>
            <md-subheader>Users</md-subheader>
            <md-list-item class="md-2-line" ng-repeat="user in group.users">
                <div class="md-list-item-text">
                    <h3>@{{ user.name }}</h3>
                    <p>Success: <b>122</b> · Fail: <b>22</b></p>
                    <md-icon md-font-set="material-icons" class="md-secondary" ng-click="removeUserFromGroup(user, $event)" ng-if="user.id != group.creator_user_id && (currentUser.id == user.id || currentUser.id == group.creator_user_id)">remove_circle_outline</md-icon>
                </div>
            </md-list-item>
        </md-list>
    </md-content>
</md-content>
<md-content>
    <h2>Settings</h2>
</md-content>