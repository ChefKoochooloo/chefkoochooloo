'use strict';

var appModule = angular.module('chefKoochooloo');

appModule.directive('ngThumb', ['$window', function($window) {
        var helper = {
            support: !!($window.FileReader && $window.CanvasRenderingContext2D),
            isFile: function(item) {
                return angular.isObject(item) && item instanceof $window.File;
            },
            isImage: function(file) {
                var type =  '|' + file.type.slice(file.type.lastIndexOf('/') + 1) + '|';
                return '|jpg|png|jpeg|bmp|gif|'.indexOf(type) !== -1;
            }
        };

        return {
            restrict: 'A',
            template: '<canvas/>',
            link: function(scope, element, attributes) {
                if (!helper.support) return;

                var params = scope.$eval(attributes.ngThumb);

                if (!helper.isFile(params.file)) return;
                if (!helper.isImage(params.file)) return;

                var canvas = element.find('canvas');
                var reader = new FileReader();

                reader.onload = onLoadFile;
                reader.readAsDataURL(params.file);

                function onLoadFile(event) {
                    var img = new Image();
                    img.onload = onLoadImage;
                    img.src = event.target.result;
                }

                function onLoadImage() {
                    var width = params.width || this.width / this.height * params.height;
                    var height = params.height || this.height / this.width * params.width;
                    canvas.attr({ width: width, height: height });
                    canvas[0].getContext('2d').drawImage(this, 0, 0, width, height);
                }
            }
        };
    }]);

appModule.controller('countryController', ['$scope', '$http', '$rootScope', '$routeParams', 'FileUploader',
	function($scope, $http, $rootScope, $routeParams, FileUploader) {
        $scope.covers = [];
        $scope.flags = [];

		$scope.coverUploader = new FileUploader({
			url: 'php/countryCover.php?id='+$routeParams.countryId
		});
		$scope.coverUploader.onAfterAddingFile = function(fileItem) {
        	$scope.covers[0] = $scope.coverUploader.queue[$scope.coverUploader.queue.length-1];

        	$scope.coverUploader.queue = $scope.covers;
        	$scope.coverUploader.uploadAll();
        };
        $scope.coverUploader.onProgressItem = function(fileItem, progress) {
        };
        $scope.coverUploader.onCompleteItem = function(fileItem, response, status, headers) {
        	console.log(response);
        };

        $scope.flagUploader = new FileUploader({
			url: 'php/countryFlag.php?id='+$routeParams.countryId
		});
		$scope.flagUploader.onAfterAddingFile = function(fileItem) {
        	$scope.flags[0] = $scope.flagUploader.queue[$scope.flagUploader.queue.length-1];

        	$scope.flagUploader.queue = $scope.flags;
        	$scope.flagUploader.uploadAll();
        };
        $scope.flagUploader.onProgressItem = function(fileItem, progress) {
        };
        $scope.flagUploader.onCompleteItem = function(fileItem, response, status, headers) {
        };
        
		$scope.action = null;
		$scope.selectedIndex = null;
		$scope.selectedItem = null;
		$scope.selectedCategory = null;
		$scope.selectedStyle = 'panel-primary';

		$scope.country = {};

		$scope.select = function(category, index, action)
		{
			switch(category) {
				case 'facts':
					if ($scope.action == 'insert')
						$scope.country.facts.splice($scope.selectedIndex, 1);
					$scope.selectedItem = angular.copy($scope.country.facts[index]);
					break;
				case 'issues':
					if ($scope.action == 'insert')
						$scope.country.issues.splice($scope.selectedIndex, 1);
					$scope.selectedItem = angular.copy($scope.country.issues[index]);
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

		$scope.addCountryFact = function()
		{
			var fact = {
				'id':  		null,	
				'country': 	$scope.country.id,
 				'fact': 	''
			};

			$scope.country.facts.push(fact);
			$scope.select('facts', $scope.country.facts.length-1, 'insert');
		}

		$scope.updateCountryFact = function()
		{
			$http.get('php/countryFact.php', {
				params: {  
					action: 	$scope.action,
					id: 		$scope.selectedItem.id,
					country: 	$scope.selectedItem.country,
					fact: 		$scope.selectedItem.fact
				}
			}).
			success(function(data, status, headers, config) {
				if ($scope.action == "delete") {
 					$scope.country.facts.splice($scope.selectedIndex, 1);
 				} else {
 					$scope.country.facts[$scope.selectedIndex] = data;
 				}

 				$scope.action = null;
 				$scope.select(null, -1, null);
			});
		}

		$scope.addCountryIssue = function()
		{
			var issue = {
				'id':  		null,
				'country': 	$scope.country.id,	
 				'issue': 	'',
 				'url': 		''
			};

			$scope.country.issues.push(issue);
			$scope.select('issues', $scope.country.issues.length-1, 'insert');
		}

		$scope.updateCountryIssue = function()
		{
			console.log($scope.selectedItem);
			$http.get('php/countryIssue.php', {
				params: {  
					action: 	$scope.action,
					id: 		$scope.selectedItem.id,
					country: 	$scope.selectedItem.country,
					issue: 		$scope.selectedItem.issue,
					url: 		$scope.selectedItem.url
				}
			}).
			success(function(data, status, headers, config) {
				console.log(data);
				if ($scope.action == "delete") {
 					$scope.country.issues.splice($scope.selectedIndex, 1);
 				} else {
 					$scope.country.issues[$scope.selectedIndex] = data;
 				}

 				$scope.action = null;
 				$scope.select(null, -1, null);
			});
		}

		$scope.updateCountry = function()
		{
			console.log({  
					action: 		'update',
					id: 			$scope.country.id,
					wish: 			$scope.country.wish,
					capital: 		$scope.country.capital,
					population: 	$scope.country.population,
					languages: 		$scope.country.languages
				});
			$http.get('php/country.php', {
				params: {  
					action: 		'update',
					id: 			$scope.country.id,
					wish: 			$scope.country.wish,
					capital: 		$scope.country.capital,
					population: 	$scope.country.population,
					languages: 		$scope.country.languages
				}
			}).
			success(function(data, status, headers, config) {
				console.log(data);
				$scope.country = data;
			});
		}

		$scope.$on('countrySelected', function(event, args) {
			$scope.country = args.country;

			console.log($scope.country);
		});

		$rootScope.$broadcast('countryChange', { target: 'country', countryId : $routeParams.countryId });
	}
]);