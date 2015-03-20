  angular.module(' ', ['ngMessages'])
    .controller('FormController', ['$scope', function($scope) {
      $scope.userType = '';
      $scope.email = 'me@example.com';
    }]);

  it('should check ng-class', function() {
  expect(element(by.css('.base-class')).getAttribute('class')).not.
    toMatch(/my-class/);

  element(by.id('setbtn')).click();

  expect(element(by.css('.base-class')).getAttribute('class')).
    toMatch(/my-class/);

  element(by.id('clearbtn')).click();

  expect(element(by.css('.base-class')).getAttribute('class')).not.
    toMatch(/my-class/);
});




