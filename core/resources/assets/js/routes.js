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

      '/landingpages': function () { loadRoute('landingpages', 'landingpages'); },
      '/landingpages/:order': function (order) { loadRoute('landingpages?order=' + order, 'landingpages'); },
      '/landingpages/create': function () { loadRoute('landingpages/create', 'landingpages'); },
      '/landingpages/create/:cat': function (cat) { loadRoute('landingpages/create/' + cat, 'landingpages'); },
      '/landingpages/editor/:sl': function (sl) { loadRoute('landingpages/editor?sl=' + sl, 'landingpages', true, true); },
      '/landingpages/analytics/:sl': function (sl) { loadRoute('landingpages/analytics?sl=' + sl, 'landingpages'); },

      '/forms': function () { loadRoute('forms', 'forms'); },
      '/forms/:order': function (order) { loadRoute('forms?order=' + order, 'forms'); },
      '/forms/create': function () { loadRoute('forms/create', 'forms'); },
      '/forms/create/:cat': function (cat) { loadRoute('forms/create/' + cat, 'forms'); },
      '/forms/editor/:sl': function (sl) { loadRoute('forms/editor?sl=' + sl, 'forms', true, true); },
      '/forms/entries/:sl': function (sl) { loadRoute('forms/entries?sl=' + sl, 'forms'); },

      '/emailcampaigns': function () { loadRoute('emailcampaigns', 'emailcampaigns'); },
      '/emailcampaigns/:order': function (order) { loadRoute('emailcampaigns?order=' + order, 'emailcampaigns'); },
      '/emailcampaigns/create': function () { loadRoute('emailcampaigns/create', 'emailcampaigns'); },
      '/emailcampaigns/create/:cat': function (cat) { loadRoute('emailcampaigns/create/' + cat, 'emailcampaigns'); },
      '/emailcampaigns/editor/:sl': function (sl) { loadRoute('emailcampaigns/editor?sl=' + sl, 'emailcampaigns', true, true); },
      '/emailcampaigns/analytics/:sl': function (sl) { loadRoute('emailcampaigns/analytics?sl=' + sl, 'emailcampaigns'); },

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

    function loadRoute(url, route, showGenericTitle, showDeviceSelection) {
      $('#view').load(url, function() {
        onPartialLoaded();

        if (typeof showGenericTitle == 'undefined' || ! showGenericTitle) {
          $('#generic_title a').text('');
        }

        if (showDeviceSelection) {
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