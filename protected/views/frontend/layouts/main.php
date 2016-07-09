<!doctype html>
<html ng-app="MyApp">
<head>
	<base href="/">
    <meta charset="utf-8">
    <title ng-bind="pageTitle">Люта Справа | Ukrainian Armed Forces | Хлопцям багато футболок не буває</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

	<link href='https://fonts.googleapis.com/css?family=PT+Sans&subset=latin,cyrillic-ext,cyrillic' rel='stylesheet' type='text/css'>
    <link href="/css/bootstrap.min.css" rel="stylesheet">
	<link href="/css/animate.css" rel="stylesheet">
    <link href="/css/style.css" rel="stylesheet">
	<link href="/img/favicon.ico" rel="SHORTCUT ICON" type="image/x-icon">
</head>

<body scroll >

	<div class="wrap">
	<!-- container -->

		<div class="container-fluid" ng-class="{'fixed': fixed}">
			
			<!-- row 1: header -->
			<header class="row" ng-class="{'fixed': fixed}" >
				<div class="col-sm-2 col-xs-3 parent" ng-class="{'fixed': fixed}" >
					<a href="/" class="inner" ng-click="goTo('home')"><img src="/img/logo.png" alt="Люта Справа" class="img-responsive center-block"></a>
				</div>	
				<div class="col-sm-8 col-xs-6 parent" ng-class="{'fixed': fixed}">
					<h1 class="inner">Купуючи одну футболку собі, другу ти даруєш солдату або офіцеру</h1>
				</div>
				<div class="col-sm-2 col-xs-3 parent" ng-class="{'fixed': fixed}">
					<div class="inner">
						<img src="/img/t-shirts.png" alt="" class="img-responsive center-block middle">
					</div>
				</div>
			</header>

			<!-- row 2: navigation --> 
			<nav class="row navbar navbar-default" role="navigation" ng-class="{'fixed': fixed}">
				   
				<button type="button" class="navbar-toggle pull-left" data-toggle="collapse"  data-target=".collapse">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				  
				<div class="collapse navbar-collapse pull-left">
					<ul class="nav navbar-nav">
						<!--<li ng-class="{'active':menuActive == 'home'}"><a href="/" ng-click="goTo('home')">
							Головна</a></li>-->
						<li ng-repeat="section in sections" ng-class="{'active':menuActive == section.code}">
							<a href="" ng-click="goTo(section.code)" >{{section.name}}</a></li>
						<li ng-class="{'active':menuActive == 'shop'}" ><a href="/shop">
							Магазин</a></li>
						<li ng-class="{'active':menuActive == 'with_us'}"><a href="/with_us" >
							Уже з нами</a></li>		
					</ul> 
				</div>
				
				<a href="/cart" ng-controller="CartHeader" class="cart_menu pull-right">
					<i class="glyphicon glyphicon-shopping-cart"> </i> 
					Кошик {{cart.description}}
				</a>
			</nav> 
			

			<div ng-view></div>
			
			
		</div> <!-- end container -->	
	</div>

	<!-- footer -->
	<footer class="text-center ">
		&copy; COPYRIGHT — LUTA SPRAVA PUBLISHING, KYIV, UKRAINE, 2014-2016. ALL RIGHTS RESERVED. <br>
		&copy;  DESIGN — A.G. LABORATORY OF DESIGN<br>
		&copy;  DEVELOPMENT — <a href="mailto:ira.left@gmail.com">IRINA LIVA</a>
	</footer>	




<!-- javascript -->
	<script type="text/javascript"  src="/js/jquery-1.12.3.min.js"></script>
    <script type="text/javascript"  src="/js/bootstrap.min.js"></script>
	
	<script type="text/javascript"  src="https://code.angularjs.org/1.4.8/angular.js"></script>
	<script type="text/javascript"  src="https://code.angularjs.org/1.4.8/angular-route.js"></script>
	<script type="text/javascript"  src="https://code.angularjs.org/1.4.8/angular-cookies.min.js"></script>
	<script type="text/javascript"  src="/js/angular-filter.js"></script>
	<script type="text/javascript"  src="/js/app.js"></script>
	<script type="text/javascript"  src="/js/controllers.js"></script>
	<script type="text/javascript"  src="/js/filters.js"></script>
	<script type="text/javascript"  src="/js/factories.js"></script>
	<script type="text/javascript"  src="/js/directives.js"></script>

</body>
</html>
