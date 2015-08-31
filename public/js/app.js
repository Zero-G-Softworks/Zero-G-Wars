/* 
 * This is primarily a test console and is not part of the final project.
 */

window.app = angular.module( 'app', ['ngSanitize'] );

window.app.config(function($locationProvider) {
    $locationProvider.html5Mode(true);
});

window.app.controller( 'MainController', [ '$http', '$interval', '$location', function ( $http, $interval, $location ) {
    // mimics controlleAs sugar
    var ctrl = this;

    ctrl.status = 'Waiting for your command...';
    ctrl.data = '';
    ctrl.shipTypes = [];
    
    //var api_base = 'http://localhost/ZeroGWars/public/api/v1';
    var api_base = './api/v1';
    var site_base = 'http://localhost/ZeroGWars/public';
    var user = '';
    ctrl.game = '';
    var wait;
    var rows = 15;
    var cols = 15;
    
    ctrl.isLoggedIn = false;
    
    ctrl.getNumber = function(num) {
        return new Array(parseInt(num, 10));
    };
    
    ctrl.createGame = function () {
        $http
        .post( api_base + '/game' )
        .then(function (response) {
            ctrl.game = response.data.game_id;
            ctrl.updateConsole("created game: " + response.data.game_id + "; awaiting player2"); // success!
            ctrl.updateConsole('send your friend to: ' + site_base + '/' + response.data.game_id);
            wait = $interval(awaitPlayer, 1000);
        })
        .catch(function ( reason ) {
            console.log(reason);
            ctrl.updateConsole("Error: " + reason.status); // fail :(
        });
    };
    
    var awaitPlayer = function () {
        $http
        .get( api_base + '/game/' + ctrl.game )
        .then(function (response) {
            if( response.data.player2 !== null ) {
                ctrl.updateConsole(response.data.player2 + " joined game: " + ctrl.game);
                $interval.cancel(wait);
                wait = undefined;
            }
        })
        .catch(function ( reason ) {
            console.log(reason);
            ctrl.updateConsole("Error: " + reason.status); // fail :(
            $interval.cancel(wait);
            wait = undefined;
        });
    };
    
    ctrl.joinGame = function () {
        $http
        .get( api_base + '/game/' + ctrl.game_id )
        .then(function (response) {
            ctrl.game = ctrl.game_id;
            ctrl.updateConsole("joined " + response.data.player1 +" in game: " + response.data.game_id);
        })
        .catch(function ( reason ) {
            console.log(reason);
            ctrl.updateConsole("Error: " + reason.status); // fail :(
        });
    };
    
    ctrl.login = function () {
        $http
        .post( api_base + '/auth/login', { 'user':ctrl.user, 'pass':ctrl.pass } )
        .then(function (response) {
            if( response.data === "failed" )
                ctrl.updateConsole("failed login");
            else if( response.data === "already logged in as: " + user ) {
                ctrl.updateConsole("already logged in");
                user = response.data.user;
                ctrl.isLoggedIn = true;
            }
            else {
                ctrl.updateConsole("logged in as: " + response.data.user);
                user = response.data.user;
                ctrl.isLoggedIn = true;
            }
        })
        .catch(function ( reason ) {
            console.log(reason);
            ctrl.updateConsole("Error: " + reason.status); // fail :(
        });
    };
    
    ctrl.logout = function () {
        $http
        .post( api_base + '/auth/logout' )
        .then(function (response) {
            ctrl.updateConsole("logged out");
            ctrl.isLoggedIn = false;
        })
        .catch(function ( reason ) {
            console.log(reason);
            ctrl.updateConsole("Error: " + reason.status); // fail :(
        });
    };
    
    ctrl.register = function () {
        $http
        .post( api_base + '/auth/register', { 'name':'random', 'user':ctrl.user, 'email':ctrl.email, 'pass':ctrl.pass } )
        .then(function (response) {
            ctrl.updateConsole("registered: " + response.data.username);
        })
        .catch(function ( reason ) {
            console.log(reason);
            ctrl.updateConsole("Error: " + reason.status); // fail :(
        });
    };
    
    ctrl.updateConsole = function(str) {
        if( ctrl.status == 'Waiting for your command...' )
            ctrl.status = str;
        else
            ctrl.status += "\n" + str;
    };
    
    ctrl.checkAuth = function () {
        $http
        .get( api_base + '/auth/check' )
        .then(function (response) {
            if( angular.isDefined(response.data.user) ) {
                ctrl.isLoggedIn = true;
                ctrl.user = response.data.user;
            }
            else
                ctrl.isLoggedIn = false;
        })
        .catch(function ( reason ) {
            console.log(reason);
            ctrl.updateConsole("Error: " + reason.status); // fail :(
        });
    };
    
    ctrl.getShipTypes = function() {
        $http
        .get( api_base + '/game/shiptypes' )
        .then(function (response) {
            ctrl.shipTypes = response.data;
            ctrl.shipType = response.data[0].id;
            ctrl.shipRot = "down";
            ctrl.tile = "D6";
        })
        .catch(function ( reason ) {
            console.log(reason);
            ctrl.updateConsole("Error: " + reason.status); // fail :(
        });
    };
    
    ctrl.placeShip = function () {
        var ship = -1;
        
        ctrl.shipTypes.forEach(function (entry) {
            if( entry.id === ctrl.shipType ) {
                ship = entry;
            }
        });
        if( ship === -1 ) return false;
        
        if( ctrl.isLoggedIn !== true ) {
            $http
            .get( api_base + '/auth/check' )
            .then(function (response) {
                if( angular.isDefined(response.data.user) ) {
                    ctrl.isLoggedIn = true;
                    ctrl.user = response.data.user;
                }
                else
                    ctrl.isLoggedIn = false;
            })
            .catch(function ( reason ) {
                console.log(reason);
                ctrl.updateConsole("Error: " + reason.status); // fail :(
                return false;
            });
        }
        
        $http
        .post( api_base + '/game/' + ctrl.game + '/put/' + ship.id + "/" + ctrl.tile + "/" + ctrl.shipRot )
        .then(function (response) {
            console.log(response);
            if( !angular.isDefined(response.data.source_tile) ) {
                console.log(response.data.error);
                ctrl.updateConsole("Error: " + response.data.error); // fail :(
                return false;
            }
            
            var fromCol = response.data.source_tile[0].charCodeAt(0) - 64;
            var fromRow = parseInt(response.data.source_tile[1]);
            var toCol = response.data.target_tile[0].charCodeAt(0) - 64;
            var toRow = parseInt(response.data.target_tile[1]);
            
            var col = fromCol;
            var row = fromRow;
            var direction = 1;
            
            if( fromCol > toCol || fromRow > toRow ) {
                direction = -1;
            }
            
            while( col !== toCol + 1 ) {
                thisTile = String.fromCharCode(64 + col) + "" + row;
                ctrl.tileList[thisTile] = "X";
                col += direction;
            }
            
            col = fromCol;
            while( row !== toRow + 1 ) {
                thisTile = String.fromCharCode(64 + col) + "" + row;
                ctrl.tileList[thisTile] = "X";
                row += direction;
            }
        })
        .catch(function ( reason ) {
            console.log(reason);
            ctrl.updateConsole("Error: " + reason.status); // fail :(
            return false;
        });
    };
    
    ctrl.init = function () {
        ctrl.checkAuth();
        ctrl.game_id = $location.path().replace("/", "");
        
        ctrl.getShipTypes();
    };
    
    ctrl.tileList = [];
    ctrl.tileList["D5"] = "test";
    
    var range = [];
    for(var i=0;i<rows;i++) {
        range.push(i+1);
    }
    ctrl.rows = range;
    
    var range = [];
    for(var i=0;i<cols;i++) {
        range.push(String.fromCharCode(65 + i));
    }
    ctrl.cols = range;
    
    
    ctrl.init();
}]);