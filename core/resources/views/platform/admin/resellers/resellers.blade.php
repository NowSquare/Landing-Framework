<div class="container">

  <div class="row m-t">
    <div class="col-sm-12">
     
       <nav class="navbar navbar-default card-box sub-navbar">
        <div class="container-fluid">

          <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-title-navbar" aria-expanded="false">
              <span class="sr-only">Toggle navigation</span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand no-link" href="javascript:void(0);">{{ trans('global.admin') }}</a>
            <a class="navbar-brand no-link" href="javascript:void(0);">\</a>
            <a class="navbar-brand no-link" href="javascript:void(0);">{{ trans('global.resellers') }}</a>
          </div>

          <div class="collapse navbar-collapse" id="bs-title-navbar">

            <div class="navbar-form navbar-right">
                <a href="#/admin/reseller" class="btn btn-success"><i class="fa fa-plus" aria-hidden="true"></i> {{ trans('global.create_reseller') }}</a>
            </div>

          </div>
        </div>
      </nav>
    
    </div>
  </div>

  <script>
var admin_resellers_table = $('#dt-table-admin_resellers').DataTable({
  ajax: "{{ url('platform/admin/resellers/data') }}",
  order: [
    [4, "asc"]
  ],
  dom: "<'row'<'col-sm-12 dt-header'<'pull-left'lr><'pull-right'f><'pull-right hidden-sm hidden-xs'T><'clearfix'>>>t<'row'<'col-sm-12 dt-footer'<'pull-left'i><'pull-right'p><'clearfix'>>>",
  processing: true,
  serverSide: true,
  stateSave: true,
  responsive: false,
  stripeClasses: [],
  lengthMenu: [
    [10, 25, 50, 75, 100, 1000000],
    [10, 25, 50, 75, 100, "{{ trans('global.all') }}"]
  ],
  columns: [{
    data: "name"
  }, {
    data: "domain"
  }, {
    data: "user_name"
  }, {
    data: "user_email"
  }, {
    data: "created_at",
    width: 90
  }, {
    data: "active",
    width: 60
  }, {
    data: "sl",
    width: 74,
    sortable: false
  }],
  fnDrawCallback: function() {
    onDataTableLoad();
  },
  columnDefs: [
    {
      render: function (data, type, row) {
        return '<div data-moment="fromNowDateTime">' + data + '</div>';
      },
      targets: [4] /* Column to re-render */
    },
    {
      render: function (data, type, row) {
        return (row.domain != '*') ? '<a href="http://' + row.domain + '" class="link" target="_blank"> ' + row.domain + '</a>' : row.domain;
      },
      targets: 1
    },
    {
      render: function (data, type, row) {
        return '<img src="' + row.favicon + '" style="height:16px;float: left;margin: 1px 4px;"> ' + row.name;
      },
      targets: 0
    },
    {
      render: function (data, type, row) {
        if(data == 1)
        {
          return '<div class="text-center"><i class="fa fa-check" aria-hidden="true"></i></div>';
        }
        else
        {
          return '<div class="text-center"><i class="fa fa-times" aria-hidden="true"></i></div>';
        }
      },
      targets: 5
    },
    {
      render: function (data, type, row) {
        var disabled = (row.undeletable == '1') ? ' disabled' : '';
        var btn_delete = (disabled) ? '' : ' row-btn-delete';
        return '<div class="row-actions-wrap"><div class="text-center row-actions" data-sl="' + data + '">' + 
          '<a href="#/admin/reseller/' + data + '" class="btn btn-xs btn-inverse row-btn-edit" data-toggle="tooltip" title="{{ trans('global.edit') }}"><i class="fa fa-pencil"></i></a> ' + 
          '<a href="javascript:void(0);" class="btn btn-xs btn-danger' + btn_delete + '" data-toggle="tooltip" title="{{ trans('global.delete') }}"' + disabled + '><i class="fa fa-trash"></i></a>' + 
          '</div></div>';
      },
      targets: 6 /* Column to re-render */
    },
  ],
  language: {
    search: "",
    emptyTable: "{{ trans('global.empty_table') }}",
    info: "{{ trans('global.dt_info') }}",
    infoEmpty: "",
    infoFiltered: "(filtered from _MAX_ total entries)",
    thousands: "{{ trans('i18n.thousands_sep') }}",
    lengthMenu: "{{ trans('global.show_records') }}",
    processing: '<i class="fa fa-circle-o-notch fa-spin"></i>',
    paginate: {
      first: '<i class="fa fa-fast-backward"></i>',
      last: '<i class="fa fa-fast-forward"></i>',
      next: '<i class="fa fa-caret-right"></i>',
      previous: '<i class="fa fa-caret-left"></i>'
    }
  }
});

$('#dt-table-admin_resellers_wrapper .dataTables_filter input').attr('placeholder', "{{ trans('global.search_') }}");

</script>
  <div class="row">
  <div class="col-sm-12">
    <div class="card-box table-responsive">
    <table class="table table-cutoff table-striped table-bordered table-hover" id="dt-table-admin_resellers" style="width:100%">
      <thead>
      <tr>
        <th>{{ trans('global.platform_name') }}</th>
        <th>{{ trans('global.domain') }}</th>
        <th>{{ trans('global.reseller_name') }}</th>
        <th>{{ trans('global.reseller_email') }}</th>
        <th>{{ trans('global.created') }}</th>
        <th class="text-center">{{ trans('global.active') }}</th>
        <th class="text-center">{{ trans('global.actions') }}</th>
      </tr>
      </thead>
    </table>
    </div>
  </div>
  </div>
  <script>

$('#dt-table-admin_resellers').on('click', '.row-btn-delete', function() {
  var sl = $(this).parent('.row-actions').attr('data-sl');

  swal({
    title: _lang['confirm'],
    type: "warning",
    showCancelButton: true,
    cancelButtonText: _lang['cancel'],
    confirmButtonColor: "#DD6B55",
    confirmButtonText: _lang['yes_delete']
  }).then(function() {
    blockUI();

    var jqxhr = $.ajax({
      url: "{{ url('platform/admin/reseller/delete') }}",
      data: {sl: sl,  _token: '<?= csrf_token() ?>'},
      method: 'POST'
    })
    .done(function(data) {
      if(data.result == 'success') {
        admin_resellers_table.ajax.reload();
      } else {
        swal(data.msg);
      }
    })
    .fail(function() {
      console.log('error');
    })
    .always(function() {
      unblockUI();
    });
  }, function (dismiss) {
    // Do nothing on cancel
    // dismiss can be 'cancel', 'overlay', 'close', and 'timer'
  });
});

</script> 
</div>
<!-- end container --> 