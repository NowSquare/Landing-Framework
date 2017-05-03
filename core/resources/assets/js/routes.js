$(function() {
  if (window.location.pathname == '/platform') {
    
    /*
     * Called with every route change
     */

    var allRoutes = function() {
      //
    };

    /*
     * Called before every route change
     */

    var beforeRoute = function() {
      // Loader
      $('#view').html('<div class="container"><div class="row"><div class="col-xs-12"><div class="loader" id="throbber"></div></div></div>');
      $('#throbber').css('margin', (parseInt($(window).outerHeight()) / 2) - 115 + 'px auto 0 auto');
    };

    /*
     * Called after every route change
     */

    var afterRoute = function() {
      //
    };

    /*
     * Routes
     */

    var router = Router({
      '/': function () { loadRoute('platform/dashboard'); },
      '/media': function () { loadRoute('platform/media/browser'); },

      '/profile': function () { loadRoute('platform/profile'); },
      '/plan': function () { loadRoute('platform/plan', 'profile'); },

      '/create': function () { loadRoute('platform/module/create'); },

      '/forms/create': function () { loadRoute('forms/create', 'create'); },

      '/landingpages/create': function () { loadRoute('landingpages/create', 'create'); },
      '/landingpages/editor/:sl': function (sl) { loadRoute('landingpages/editor?sl=' + sl, 'landingpage/editor'); },

      '/members': function () { loadRoute('platform/members'); },
      '/member/:sl': function (sl) { loadRoute('platform/member/edit?sl=' + sl, 'members'); },

      '/admin/plans': function () { loadRoute('platform/admin/plans'); },
      '/admin/plan': function () { loadRoute('platform/admin/plan/new', 'admin/plans'); },
      '/admin/plan/:sl': function (sl) { loadRoute('platform/admin/plan/edit?sl=' + sl, 'admin/plans'); },

      '/admin/resellers': function () { loadRoute('platform/admin/resellers'); },
      '/admin/reseller': function () { loadRoute('platform/admin/reseller/new', 'admin/resellers'); },
      '/admin/reseller/:sl': function (sl) { loadRoute('platform/admin/reseller/edit?sl=' + sl, 'admin/resellers'); },

      '/admin/users': function () { loadRoute('platform/admin/users'); },
      '/admin/user': function () { loadRoute('platform/admin/user/new', 'admin/users'); },
      '/admin/user/:sl': function (sl) { loadRoute('platform/admin/user/edit?sl=' + sl, 'admin/users'); }
    });

    /*
     * Route configuration
     */

    router.configure({
      on: allRoutes,
      before: beforeRoute,
      after: afterRoute
    });

    router.init('#/');

    function loadRoute(url, route) {
      $('#view').load(url, function() {
        onPartialLoaded();

        if (route == 'landingpage/editor') {
          $('#device_selector').fadeIn(100);
        } else {
          $('#device_selector').fadeOut(100);
        }

        $('.navigation-menu li').removeClass('active');
        initNavbarMenuActive(route);
      });
    }
  }
});