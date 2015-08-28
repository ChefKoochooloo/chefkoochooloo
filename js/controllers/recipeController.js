'use strict';

var appModule = angular.module('chefKoochooloo');

appModule.controller('recipeController', ['$scope', '$http', '$location', '$rootScope', '$routeParams', '$modal', 'FileUploader', 
	function($scope, $http, $location, $rootScope, $routeParams, $modal, FileUploader) {
		$scope.action = null;
		$scope.selectedIndex = null;
		$scope.selectedItem = null;
		$scope.selectedCategory = null;
		$scope.selectedStyle = 'panel-primary';

		$scope.recipe = undefined;
		$scope.flags = [];
		$scope.ingredients = [];
		$scope.tools = [];
		$scope.units = [];
		$scope.images = [];

		$scope.ingredient = {};
		$scope.tool = {};
		$scope.step = {};

		$scope.imageUploader = new FileUploader({
			url: 'php/recipeImage.php?recipeId='+$routeParams.recipeId
		});
		$scope.imageUploader.onAfterAddingFile = function(fileItem) {
			$scope.recipe.images.push({fileItem:fileItem});

			$scope.imageUploader.uploadAll();
        };
        $scope.imageUploader.onProgressItem = function(fileItem, progress) {
        };
        $scope.imageUploader.onCompleteItem = function(fileItem, response, status, headers) {
        	for (var i = 0; i < $scope.recipe.images.length; i++) {
        		if ($scope.recipe.images[i].fileItem == fileItem) {
        			$scope.recipe.images[i] = response;
        			break;
        		};
        	};
        };

		$scope.setType = function(type) {
			$scope.recipe.type = type;
		}

		$scope.select = function(category, index, action)
		{
			switch(category) {
				case 'ingredients':
					if ($scope.action == 'insert')
						$scope.recipe.ingredients.splice($scope.selectedIndex, 1);
					$scope.selectedItem = angular.copy($scope.recipe.ingredients[index]);

					$scope.ingredient.selected = $scope.getIngredientById($scope.selectedItem.ingredient);
					break;
				case 'tools':
					if ($scope.action == 'insert')
						$scope.recipe.tools.splice($scope.selectedIndex, 1);
					$scope.selectedItem = angular.copy($scope.recipe.tools[index]);

					$scope.tool.selected = $scope.getToolById($scope.selectedItem.tool);
					break;
				case 'steps':
					if ($scope.action == 'insert')
						$scope.recipe.steps.splice($scope.selectedIndex, 1);
					$scope.selectedItem = angular.copy($scope.recipe.steps[index]);
					break;
			}

			switch(action) {
				case 'insert':
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

			$scope.action = action;
			$scope.selectedIndex = index;
			$scope.selectedCategory = category;
		}

		$scope.isFlagActive = function(flag) {
			if (!$scope.recipe || !$scope.recipe.flags) {
				return false;
			}

			for (var i = 0; i < $scope.recipe.flags.length; ++i) {
				if ($scope.recipe.flags[i].flag == flag.id)
					return true;
			}

			return false;
		}

		$scope.toggleFlag = function(flag) {
        	$http.get('php/recipeFlag.php', {
				params: {  
					action: 		$scope.isFlagActive(flag) ? 'remove' : 'add',
					flag: 			flag.id,
					recipe: 		$scope.recipe.id
				}
			}).
			success(function(data, status, headers, config) {
				console.log(data);
				$scope.recipe.flags = data;
				//$scope.apply();
			});
        }

		$scope.manageIngredients = function() {
			var modalInstance = $modal.open({
		      	templateUrl: 'templates/ingredients.html',
		      	controller: 'ingredientsController',
		      	size: 'lg',
		      	resolve: {
		        	ingredients: function () {
		          		return $scope.ingredients;
			        }
			    }
			});
		}

		$scope.manageTools = function() {
			var modalInstance = $modal.open({
		      	templateUrl: 'templates/tools.html',
		      	controller: 'toolsController',
		      	size: 'lg',
		      	resolve: {
		        	tools: function () {
		          		return $scope.tools;
			        }
			    }
			});
		}

		$scope.addIngredient = function(name) {
			var ingredient = {
				'id': 			null,
				'name': 		'',
				'spotlight': 	''
			};

			//$scope.ingredients.splice()
		}

		$scope.getIngredientById = function(id) {
			for (var i = 0; i < $scope.ingredients.length; ++i) {
				if ($scope.ingredients[i].id == id)
					return $scope.ingredients[i];
			}

			return null;
		}

		$scope.getToolById = function(id) {
			for (var i = 0; i < $scope.tools.length; ++i) {
				if ($scope.tools[i].id == id)
					return $scope.tools[i];
			}

			return null;
		}

		$scope.getUnitById = function(id) {
			for (var i = 0; i < $scope.units.length; ++i) {
				if ($scope.units[i].id == id)
					return $scope.units[i];
			}

			return null;
		}

		//RECIPE

		$scope.updateRecipe = function()
		{
			$http.get('php/recipe.php', {
				params: {  
					action: 		'update',
					id: 			$scope.recipe.id,
					type: 			$scope.recipe.type,
					name: 			$scope.recipe.name,
					presentation: 	$scope.recipe.presentation,
					time: 			$scope.recipe.time
				}
			}).
			success(function(data, status, headers, config) {
				console.log("update recipe:");
				console.log(data);
				$scope.recipe = data;
			});
		}

		//RECIPE INGREDIENTS

		$scope.addRecipeIngredient = function()
		{
			var ingredient = {
				'id':  			null,	
 				'ingredient': 	0,
 				'recipe': 		$scope.recipe.id,
 				'unit': 		0,
 				'amount': 		0
			};

			$scope.recipe.ingredients.push(ingredient);
			$scope.select('ingredients', $scope.recipe.ingredients.length-1, 'insert');
		}

		$scope.updateRecipeIngredient = function()
		{
			$http.get('php/recipeIngredient.php', {
				params: {  
					action: 	$scope.action,
					id: 		$scope.selectedItem.id,
					ingredient: $scope.selectedItem.ingredient,
					recipe: 	$scope.recipe.id,
					unit: 		$scope.selectedItem.unit,
					amount: 	$scope.selectedItem.amount
				}
			}).
			success(function(data, status, headers, config) {
				if ($scope.action == "delete") {
 					$scope.recipe.ingredients.splice($scope.selectedIndex, 1);
 				} else {
 					$scope.recipe.ingredients[$scope.selectedIndex] = data;
 				}

 				$scope.action = null;
 				$scope.select(null, -1, null);
			});
		}

		//RECIPE TOOLS

		$scope.addRecipeTool = function()
		{
			var tool = {
				'id':  			null,	
 				'tool': 		0,
 				'recipe': 		$scope.recipe.id
			};

			$scope.recipe.tools.push(tool);
			$scope.select('tools', $scope.recipe.tools.length-1, 'insert');
		}

		$scope.updateRecipeTool = function()
		{
			$http.get('php/recipeTool.php', {
				params: {  
					action: 	$scope.action,
					id: 		$scope.selectedItem.id,
					tool: 		$scope.selectedItem.tool,
					recipe: 	$scope.recipe.id
				}
			}).
			success(function(data, status, headers, config) {
				if ($scope.action == "delete") {
 					$scope.recipe.tools.splice($scope.selectedIndex, 1);
 				} else {
 					$scope.recipe.tools[$scope.selectedIndex] = data;
 				}

 				$scope.action = null;
 				$scope.select(null, -1, null);
			});
		}

		//RECIPE STEPS

		$scope.addRecipeStep = function()
		{
			var step = {
				'id':  			null,	
 				'recipe': 		$scope.recipe.id,
 				'order': 		$scope.recipe.steps.length,
 				'type': 		0,
 				'label': 		''
			};

			$scope.recipe.steps.push(step);
			$scope.select('steps', $scope.recipe.steps.length-1, 'insert');
		}

		$scope.updateRecipeStep = function()
		{
			$http.get('php/recipeStep.php', {
				params: {  
					action: 	$scope.action,
					id: 		$scope.selectedItem.id,
					recipe: 	$scope.recipe.id,
					order: 		$scope.selectedItem.order ? $scope.selectedItem.order : $scope.selectedIndex,
					label: 		$scope.selectedItem.label,
					type: 		$scope.selectedItem.type
				}
			}).
			success(function(data, status, headers, config) {
				console.log(data);
				if ($scope.action == "delete") {
 					$scope.recipe.steps.splice($scope.selectedIndex, 1);
 				} else {
 					$scope.recipe.steps[$scope.selectedIndex] = data;
 				}

 				$scope.action = null;
 				$scope.select(null, -1, null);
			});
		}

		$scope.selectRecipeImage = function(image)
		{
			console.log(image.id);
			$scope.images = [];
			$http.get('php/recipeImage.php', {
				params: {  
					action: 	'select',
					id: 		image.id,
					recipeId: 	$scope.recipe.id
				}
			}).
			success(function(data, status, headers, config) {
				console.log(data);

				$scope.images.splice(0, $scole.images.length);
				$scope.images = data;
				$scope.images.$apply();
			});
		}

		$scope.removeRecipeImage = function(image)
		{
			$http.get('php/recipeImage.php', {
				params: {  
					action: 	'delete',
					id: 		image.id,
					recipeId: 	$scope.recipe.id
				}
			}).
			success(function(data, status, headers, config) {
				for (var i = 0; i < $scope.recipe.images.length; i++) {
					if ($scope.recipe.images[i] == image) {
						$scope.recipe.images.splice(i, 1);
						break;
					};
				};
			});
		}

		$scope.$watch('ingredient.selected', function(newValue, oldValue) {	
			if (newValue)
				$scope.selectedItem.ingredient = newValue.id;
		});

		$scope.$watch('tool.selected', function(newValue, oldValue) {
			if (newValue)
				$scope.selectedItem.tool = newValue.id;
		});

		//RECIPE
		$http.get('php/recipe.php', {
	        params: {
	   			id: 		$routeParams.recipeId,
	   			country: 	$routeParams.countryId,
	   			action: 	$routeParams.recipeId == 0 ? 'insert' : ''
	   		}
        }).
		success(function(data, status, headers, config) {
			console.log('---------');
			console.log(data);
			$scope.recipe = data;
		});

		//FAGS
		$http.get('php/flags.php').
		success(function(data, status, headers, config) {
			$scope.flags = data;
		});
		//INGREDIENTS
		$http.get('php/ingredients.php').
		success(function(data, status, headers, config) {
			$scope.ingredients = data;
		});
		//TOOLS
		$http.get('php/tools.php').
		success(function(data, status, headers, config) {
			$scope.tools = data;
		});
		//UNITS
		$http.get('php/units.php').
		success(function(data, status, headers, config) {
			$scope.units = data;
		});

		$rootScope.$broadcast('countryChange', { target: 'recipes', countryId : $routeParams.countryId, recipeId : $routeParams.recipeId });
	}
]);