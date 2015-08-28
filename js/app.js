'use strict';

/* App Module */

var chefApp = angular.module('chefKoochooloo', ['ngRoute', 'ngSanitize', 'angularFileUpload', 'ui.bootstrap', 'ui.select']);

chefApp.config(['$locationProvider', '$routeProvider',
  function($locationProvider, $routeProvider) {
    $locationProvider.html5Mode(false);

    $routeProvider.
      when('/country/:countryId', {
        templateUrl: 'templates/country.html',
        controller: 'countryController'
      }).
      when('/recipes/:countryId', {
        templateUrl: 'templates/recipes.html',
        controller: 'recipesController'
      }).
      when('/recipes/:countryId/:recipeId/', {
        templateUrl: 'templates/recipe.html',
        controller: 'recipeController'
      }).
      otherwise({
        redirectTo: '/country/'
      });
  }]
);
