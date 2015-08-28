'use strict';

var appModule = angular.module('chefKoochooloo');

appModule.controller('toolsController', ['$scope', '$http', '$modalInstance', 'tools',
	function($scope, $http, $modalInstance, tools) {
		$scope.tools = tools;

		$scope.action = null;
		$scope.selectedIndex = null;
		$scope.selectedStyle = 'panel-primary';

		$scope.tempName;
		$scope.tempUrl;

		$scope.selectTool = function(index, action)
		{
			if ($scope.action == 'insert') {
				$scope.tools.splice($scope.selectedIndex, 1);
			};

			$scope.action = action;
			$scope.selectedIndex = index;

			var tool = $scope.tools[index];

			if (tool) {
				$scope.tempName = tool.name;
				$scope.tempUrl = tool.url;
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

		$scope.addTool = function()
		{
			var tool = {
				'id': 			null,
				'name': 		'',
				'url': 	''
			};

			$scope.tools.splice(0, 0, tool);
			$scope.selectTool(0, 'insert');

			var myEl = angular.element( document.querySelector( '#toolsContainer' ) );
			$(myEl).scrollTop(0);
		}

		$scope.updateTool = function()
 		{
 			var tool = $scope.tools[$scope.selectedIndex];
 			tool.name = $scope.tempName;
 			tool.url = $scope.tempUrl;

 			$http.get('php/tool.php', {
	        		params: {
	        			action: 	$scope.action, 
	            		id: 		tool.id,
	            		name: 		tool.name,
	            		url: 	tool.url
	        		}
	        	}).
 				success(function(data, status, headers, config) {
 					if ($scope.action == "delete") {
 						$scope.tools.splice($scope.selectedIndex, 1);
 					} else {
 						$scope.tools[$scope.selectedIndex] = data;
 					}

 					$scope.action = null;
 					$scope.selectTool(-1, null);
	 			});
 		}
	}
]);