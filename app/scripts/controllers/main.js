'use strict';

/**
 * @ngdoc function
 * @name webApp.controller:MainCtrl
 * @description
 * # MainCtrl
 * Controller of the webApp
 */
angular.module('webApp')
	.controller('MainCtrl', function ($scope, $http) {
		$scope.tips = $http.get('/tips.json')
		.success(function(data){
			$scope.tips = data;
		});

		$scope.order = '$key';
		$scope.reverse = true;

		$scope.convertToDate = function (stringDate){
		  var dateOut = new Date(stringDate);
		  dateOut.setDate(dateOut.getDate() + 1);
		  return dateOut;
		};
	});
