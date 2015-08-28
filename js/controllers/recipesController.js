'use strict';

var appModule = angular.module('chefKoochooloo');

appModule.controller('recipesController', ['$scope', '$http', '$location', '$rootScope', '$routeParams',
	function($scope, $http, $location, $rootScope, $routeParams) {
		$scope.country = {};
		$scope.recipes = [];

		$scope.$on('countrySelected', function(event, args) {
			$scope.country = args.country;

			$scope.recipes = [];

			$http.get('php/recipes.php', {
	        		params: {
	        			country: 	$scope.country.code
	        		}
	        	}).
				success(function(data, status, headers, config) {
					console.log(data);
	 				$scope.recipes = data;
	 			});
		});

		$scope.addRecipe = function() {
			$location.path('/recipes/'+$routeParams.countryId+'/0');
		}

		$scope.editRecipe = function(recipe) {
			$location.path('/recipes/'+$routeParams.countryId+'/'+recipe.id);
		}

		$scope.deleteRecipe = function(recipeIndex) {
			$http.get('php/recipe.php', {
				params: {  
					action: 		'delete',
					id: 			$scope.recipes[recipeIndex].id
				}
			}).
			success(function(data, status, headers, config) {
				$scope.recipes.splice(recipeIndex, 1);
			});			
		}

		$rootScope.$broadcast('countryChange', { target: 'recipes', countryId : $routeParams.countryId });
	}
]);