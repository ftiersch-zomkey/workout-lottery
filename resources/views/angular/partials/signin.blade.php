<h1>Sign In</h1>
<md-content layout="column">
    <md-input-container>
        <md-icon md-font-set="material-icons">mail_outline</md-icon>
        <input ng-model="signInData.email" type="email" placeholder="E-Mail" ng-required="true">
    </md-input-container>
    <md-input-container>
        <md-icon md-font-set="material-icons">lock_outline</md-icon>
        <input ng-model="signInData.password" type="password" placeholder="Password" ng-required="true">
    </md-input-container>
    <md-content layout="row">
        <md-button class="md-raised md-primary" ng-click="signIn()">Log in</md-button>
        <md-button class="md-raised">Sign Up</md-button>
    </md-content>
</md-content>