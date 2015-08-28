'use strict';

var appModule = angular.module('chefKoochooloo');

appModule.controller('applicationController',['$scope','$http', '$location', '$routeParams', '$rootScope',
	function($scope, $http, $location, $routeParams, $rootScope) {
		$scope.country = {};
		$scope.countries = [];
		$scope.countryId = null;
		$scope.target = 'country';
		$scope.recipeId = null;

		$scope.selectCountry = function(countryId)
		{
			$scope.countryId = countryId;

			for(var i = 0; i < $scope.countries.length; ++i) {
	 			if ($scope.countries[i].id == $scope.countryId) {
	 				$scope.country.selected = $scope.countries[i];
	 				
	 				$rootScope.$broadcast('countrySelected', { country : $scope.countries[i] });
	 				break;
	 			}
			}
		}

		$scope.goTo = function(target) {
			$scope.target = target;

			$location.path('/'+target+'/'+$scope.countryId);
		}

		$scope.$watch('country.selected', function(newValue, oldValue) {
			if (newValue) {
				if ($scope.recipeId != null) {
					$location.path('/'+$scope.target+'/'+newValue.id+'/'+recipeId);
					$scope.recipeId = null;
				} else {
					$location.path('/'+$scope.target+'/'+newValue.id);
				}
			}
		});

		$scope.$on('countryChange', function(event, args) {
			$scope.target = args.target;
			$scope.recipeId = args.recipeId;

			$scope.selectCountry(args.countryId);
		});

		$http.get('php/countries.php').
 			success(function(data, status, headers, config) {
 				$scope.countries = data;
 				
 				$scope.selectCountry($scope.countryId); 				
 			}
 		);
	}]
);