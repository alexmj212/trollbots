'use strict';

/**
 * @ngdoc function
 * @name webApp.controller:AboutCtrl
 * @description
 * # AboutCtrl
 * Controller of the webApp
 */
angular.module('webApp')
	.controller('AboutCtrl', function ($scope, $http) {
		$http.get('/README.md')
			.success(function(data){
				$scope.text = data;
			});
  });
