'use strict';

var appModule = angular.module('chefKoochooloo');

appModule.controller('ingredientsController', ['$scope', '$http', '$modalInstance', 'ingredients',
	function($scope, $http, $modalInstance, ingredients) {
		$scope.ingredients = ingredients;

		$scope.action = null;
		$scope.selectedIndex = null;
		$scope.selectedStyle = 'panel-primary';

		$scope.tempName;
		$scope.tempSpotlight;

		$scope.selectIngredient = function(index, action)
		{
			if ($scope.action == 'insert') {
				$scope.ingredients.splice($scope.selectedIndex, 1);
			};

			$scope.action = action;
			$scope.selectedIndex = index;

			var ingredient = $scope.ingredients[index];

			if (ingredient) {
				$scope.tempName = ingredient.name;
				$scope.tempSpotlight = ingredient.spotlight;
			}

			switch(action) {
				case 'insert':
					$scope.selectedStyle = 'info';
					break;
				case 'update':
					$scope.selectedStyle = 'info';
					break;
				case 'delete':
					$scope.selectedStyle = 'danger';
					break;
				default:
					$scope.selectedStyle = 'primary';
					break;
			}
		}

		$scope.addIngredient = function()
		{
			var ingredient = {
				'id': 			null,
				'name': 		'',
				'spotlight': 	''
			};

			$scope.ingredients.splice(0, 0, ingredient);
			$scope.selectIngredient(0, 'insert');

			var myEl = angular.element( document.querySelector( '#ingredientsContainer' ) );
			$(myEl).scrollTop(0);
		}

		$scope.updateIngredient = function()
 		{
 			var ingredient = $scope.ingredients[$scope.selectedIndex];
 			ingredient.name = $scope.tempName;
 			ingredient.spotlight = $scope.tempSpotlight;

 			$http.get('php/ingredient.php', {
	        		params: {
	        			action: 	$scope.action, 
	            		id: 		ingredient.id,
	            		name: 		ingredient.name,
	            		spotlight: 	ingredient.spotlight
	        		}
	        	}).
 				success(function(data, status, headers, config) {
 					if ($scope.action == "delete") {
 						$scope.ingredients.splice($scope.selectedIndex, 1);
 					} else {
 						$scope.ingredients[$scope.selectedIndex] = data;
 					}

 					$scope.action = null;
 					$scope.selectIngredient(-1, null);
	 			});
 		}

		/*$http.get('php/ingredients.php').
 			success(function(data, status, headers, config) {
 				$scope.ingredients = data;
 			}
 		);*/
	}
]);