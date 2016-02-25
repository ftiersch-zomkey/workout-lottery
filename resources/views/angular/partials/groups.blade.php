<h1>Groups</h1>
<md-button class="md-fab md-accent md-floating-fab md-floating-fab-br">
    <md-icon md-font-set="material-icons">add</md-icon>
</md-button>
<md-content>
    <md-list>
        <md-subheader>My Groups</md-subheader>
        <md-list-item class="md-2-line" ui-sref="restricted.groups.edit({group_id : group.id})" ng-repeat="group in ownGroups">
            <md-icon md-font-set="material-icons">groups</md-icon>
            <div class="md-list-item-text">
                <h3>@{{ group.name }}</h3>
                <md-icon md-font-set="material-icons" class="md-secondary">exit_to_app</md-icon>
            </div>
        </md-list-item>
    </md-list>
</md-content>