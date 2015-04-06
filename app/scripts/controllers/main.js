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
			$scope.totalSent = 0;
			$scope.totalUsers = 0;
			$scope.mostSentUser = '';
			$scope.mostSentNum = -1;
			$scope.mostReceivedUser = '';
			$scope.mostReceivedNum = -1;
			//$scope.lastReceivedUser = '';
			//$scope.lastReceivedDate = 3000;
			for (var tip in $scope.tips){
				if($scope.tips[tip].sent > $scope.mostSentNum){
					$scope.mostSentNum = $scope.tips[tip].sent;
					$scope.mostSentUser = tip;
				}
				if($scope.tips[tip].received > $scope.mostReceivedNum){
					$scope.mostReceivedNum = $scope.tips[tip].received;
					$scope.mostReceivedUser = tip;
				}
				/*if($scope.tip[tip].last_received_date < $scope.lastReceivedDate){ // jshint ignore:line
					$scope.lastReceivedDate = $scope.tip[tip].last_received_date; // jshint ignore:line
					$scope.lastReceivedUser = tip;
				}*/
				$scope.totalSent = $scope.totalSent + $scope.tips[tip].sent;
				$scope.totalUsers +=1;
			}
		});

		$scope.order = '$key';
		$scope.reverse = false;

		$scope.convertToDate = function (stringDate){
		  var dateOut = new Date(stringDate);
		  dateOut.setDate(dateOut.getDate() + 1);
		  return dateOut;
		};

	});
