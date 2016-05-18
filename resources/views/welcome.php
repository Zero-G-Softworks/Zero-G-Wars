<!DOCTYPE html>
<html lang="en" ng-app="app">
    <head>
        <title>Laravel</title>
        <base href="/ZeroGWars/public/" />

        <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
        <script src="//ajax.googleapis.com/ajax/libs/angularjs/1.4.3/angular.min.js"></script>
        <script src="//ajax.googleapis.com/ajax/libs/angularjs/1.4.3/angular-sanitize.min.js"></script>
        <script src="/ZeroGWars/public/js/app.js"></script>

        <style>
            html, body {
                height: 100%;
            }

            body {
                margin: 0;
                padding: 0;
                width: 100%;
                display: table;
                font-weight: 100;
            }

            .container {
                text-align: center;
                display: table-cell;
                vertical-align: middle;
            }
            
            .loginRegister {
                width: 15em;
                border-color: black;
                padding: 15px;
                margin: 10px auto;
            }
            
            td {
                width: 50px;
                height: 40px;
            }
        </style>
    </head>
    <body>
        <div ng-controller="MainController as ctrl" class="container">
            <div class="loginRegister panel" ng-hide="ctrl.isLoggedIn">
                <input type="text" id="user" ng-model="ctrl.user" autocomplete="off" placeholder="username">
                <br /><input type="email" id="email" ng-model="ctrl.email" autocomplete="off" placeholder="email">
                <br /><input type="password" id="pass" ng-model="ctrl.pass" autocomplete="off" placeholder="password">
                <br /><button ng-click="ctrl.register()" class="btn">Register</button>
            </div>
            <div class="loginRegister panel" ng-hide="ctrl.isLoggedIn">
                <input type="text" id="user" ng-model="ctrl.user" autocomplete="off" placeholder="username">
                <br /><input type="password" id="pass" ng-model="ctrl.pass" autocomplete="off" placeholder="password">
                <br /><button ng-click="ctrl.login()" class="btn">Login</button>
            </div>
            <div ng-show="ctrl.isLoggedIn">
            <p>
                <button ng-click="ctrl.logout()" class="btn">Logout</button>
            </p>
            <p>
                <button ng-click="ctrl.createGame()" class="btn">Create game</button>
            </p>
            <p>
                <button ng-click="ctrl.joinGame()" class="btn">Join game</button>
                <input type="text" id="game_id" ng-model="ctrl.game_id" autocomplete="off" placeholder="game ID">
            </p>
            <p>
                <button ng-click="ctrl.placeShip()" class="btn">Place ship</button>
                <input type="text" id="tile" ng-model="ctrl.tile" autocomplete="off" placeholder="tile [ not functioning ]">
                <select ng-model="ctrl.shipType" ng-options="type.id as type.name for type in ctrl.shipTypes"></select>
                <select ng-model="ctrl.shipRot">
                    <option value="down">Down</option>
                    <option value="right">Right</option>
                    <option value="up">Up</option>
                    <option value="left">Left</option>
                </select>
            </p>
            </div>

            <p ng-bind-html="ctrl.status | linky" style="white-space: pre;"></p>
            
            <table class="table-striped table-bordered">
                <tr>
                    <td>&nbsp;</td>
                    <td ng-repeat="j in ctrl.cols"><b>{{ j }}</b></td>
                </tr>
                <tr ng-repeat="i in ctrl.rows">
                    <td><b>{{ i }}</b></td>
                    <td ng-repeat="j in ctrl.cols" ng-model="ctrl.tileList[j+''+i]">{{ ctrl.tileList[j+''+i] }}</td>
                </tr>
            </table>
        </div>
    </body>
</html>
