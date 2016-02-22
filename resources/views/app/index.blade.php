@extends('layouts.base-app')

@section('content')
    <md-toolbar ng-cloak>
        <div class="md-toolbar-tools" ng-controller="wlNavigationController">
            <md-button class="md-icon-button" aria-label="Open Navigation" hide-gt-md>
                <md-icon md-font-set="material-icons" ng-click="toggleNav()">@{{ (isNavOpen()) ? 'chevron_left' : 'menu'}}</md-icon>
            </md-button>
            <h2>
                <span>Workout Lottery</span>
            </h2>
        </div>
    </md-toolbar>
    <md-content layout="row" ng-controller="wlNavigationController" ng-cloak flex>
        <md-sidenav class="md-sidenav-left md-whiteframe-z2" md-component-id="left" md-is-locked-open="$mdMedia('gt-md')">
            <md-content ng-if="!isSignedIn()">
                <md-list>
                    <md-list-item ui-sref="public.signin">
                        <md-icon md-font-set="material-icons">account_circle</md-icon>
                        <p>Sign In</p>
                    </md-list-item>
                </md-list>
            </md-content>
            <md-content ng-if="isSignedIn()">
                <md-list>
                    <md-list-item ui-sref="restricted.dashboard">
                        <md-icon md-font-set="material-icons">dashboard</md-icon>
                        <p>Dashboard</p>
                    </md-list-item>
                    <md-list-item ui-sref="restricted.groups">
                        <md-icon md-font-set="material-icons">groups</md-icon>
                        <p>My Groups</p>
                    </md-list-item>
                    <md-list-item ui-sref="public.logout">
                        <md-icon md-font-set="material-icons">exit_to_app</md-icon>
                        <p>Logout</p>
                    </md-list-item>
                </md-list>
            </md-content>
        </md-sidenav>
        <div ui-view="content" layout-padding flex></div>
    </md-content>
@endsection