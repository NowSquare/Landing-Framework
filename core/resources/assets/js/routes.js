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
      '/funnels': function () { loadRoute('platform/funnels', 'funnels'); },
      '/funnels/new': function () { loadRoute('platform/funnels/new', 'funnels'); },
      '/funnels/:sl': function (sl) { loadRoute('platform/funnels/edit?sl=' + sl, 'funnels'); },

      '/media': function () { loadRoute('platform/media/browser'); },
      '/profile': function () { loadRoute('platform/profile'); },
      '/plan': function () { loadRoute('platform/plan', 'profile'); },

      '/landingpages': function () { loadRoute('landingpages', 'landingpages'); },
      '/landingpages/order/:order': function (order) { loadRoute('landingpages?order=' + order, 'landingpages'); },
      '/landingpages/create': function () { loadRoute('landingpages/create', 'landingpages'); },
      '/landingpages/create/:cat': function (cat) { loadRoute('landingpages/create/' + cat, 'landingpages'); },
      '/landingpages/editor/:sl': function (sl) { loadRoute('landingpages/editor?sl=' + sl, 'landingpages', true, true); },
      '/landingpages/source/:sl': function (sl) { loadRoute('landingpages/source?sl=' + sl, 'landingpages', true, false, true); },
      '/landingpages/analytics/:sl': function (sl) { loadRoute('landingpages/analytics?sl=' + sl, 'landingpages'); },

      '/forms': function () { loadRoute('forms', 'forms'); },
      '/forms/order/:order': function (order) { loadRoute('forms?order=' + order, 'forms'); },
      '/forms/create': function () { loadRoute('forms/create', 'forms'); },
      '/forms/create/:cat': function (cat) { loadRoute('forms/create/' + cat, 'forms'); },
      '/forms/editor/:sl': function (sl) { loadRoute('forms/editor?sl=' + sl, 'forms', true, true); },
      '/forms/source/:sl': function (sl) { loadRoute('forms/source?sl=' + sl, 'forms', true, false, true); },
      '/forms/entries/:sl': function (sl) { loadRoute('forms/entries?sl=' + sl, 'forms'); },

      '/emailcampaigns': function () { loadRoute('emailcampaigns', 'emailcampaigns'); },
      '/emailcampaigns/order/:order': function (order) { loadRoute('emailcampaigns?order=' + order, 'emailcampaigns'); },
      '/emailcampaigns/create': function () { loadRoute('emailcampaigns/create', 'emailcampaigns'); },
      '/emailcampaigns/edit/:sl': function (sl) { loadRoute('emailcampaigns/edit?sl=' + sl, 'emailcampaigns'); },
      '/emailcampaigns/emails/:sl': function (sl) { loadRoute('emailcampaigns/emails?sl=' + sl, 'emailcampaigns'); },
      '/emailcampaigns/emails/order/:sl/:order': function (sl, order) { loadRoute('emailcampaigns/emails?sl=' + sl + '&order=' + order, 'emailcampaigns'); },
      '/emailcampaigns/emails/create/:sl': function (sl) { loadRoute('emailcampaigns/emails/create?sl=' + sl, 'emailcampaigns'); },
      '/emailcampaigns/emails/create/:sl/:template': function (sl, template) { loadRoute('emailcampaigns/emails/create/' + template + '?sl=' + sl, 'emailcampaigns'); },
      '/emailcampaigns/emails/editor/:sl': function (sl) { loadRoute('emailcampaigns/emails/editor?sl=' + sl, 'emailcampaigns', true, true); },
      '/emailcampaigns/emails/source/:sl': function (sl) { loadRoute('emailcampaigns/emails/source?sl=' + sl, 'emailcampaigns'); },

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

    function loadRoute(url, route, showGenericTitle, showDeviceSelection, showEditButtons) {
      $('#view').load(url, function() {
        onPartialLoaded();

        if (typeof showGenericTitle == 'undefined' || ! showGenericTitle) {
          $('#generic_title a').text('');
        }

        if (showDeviceSelection) {
          $('#device_selector').show();
        } else {
          $('#device_selector').hide();
        }

        if (showEditButtons) {
          $('#edit_buttons').show();
        } else {
          $('#edit_buttons').hide();
        }

        if (
          (typeof showGenericTitle == 'undefined' || ! showGenericTitle) &&
          (typeof showDeviceSelection == 'undefined' || ! showDeviceSelection) &&
          (typeof showEditButtons == 'undefined' || ! showEditButtons)
        ) {
          $('#funnel_selector').show();
        } else {
          $('#funnel_selector').hide();
        }

        $('.navigation-menu li').removeClass('active');
        initNavbarMenuActive(route);
      });
    }
  }
});