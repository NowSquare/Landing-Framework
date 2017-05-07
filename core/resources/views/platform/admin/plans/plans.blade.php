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
            <a class="navbar-brand no-link" href="javascript:void(0);">{{ trans('global.plans') }}</a>
          </div>

          <div class="collapse navbar-collapse" id="bs-title-navbar">

            <div class="navbar-form navbar-right">
                <a href="#/admin/plan" class="btn btn-success"><i class="fa fa-plus" aria-hidden="true"></i> {{ trans('global.create_plan') }}</a>
            </div>

          </div>
        </div>
      </nav>
    
    </div>
  </div>

  <script>
var admin_plans_table = $('#dt-table-admin_plans').DataTable({
  ajax: "{{ url('platform/admin/plans/data') }}",
  order: [
    [0, "asc"]
  ],
  dom: "<'row'<'col-sm-12 dt-header'<'pull-left'lr><'pull-right'f><'pull-right hidden-sm hidden-xs'T><'clearfix'>>>t<'row'<'col-sm-12 dt-footer'<'pull-left'i><'pull-right'p><'clearfix'>>>",
  processing: true,
  rowReorder: {
    selector: '.reorder-handle',
    dataSrc: 'order'
  },
  serverSide: true,
  stateSave: true,
  responsive: true,
  stripeClasses: [],
  lengthMenu: [
    [10, 25, 50, 75, 100, 1000000],
    [10, 25, 50, 75, 100, "{{ trans('global.all') }}"]
  ],
  columns: [{
    data: "order",
    width: 5
  }, {
    data: "name",
    sortable: false
  }, {
    data: "price1_string",
    width: 120,
    sortable: false
  }, {
    data: "created_at",
    width: 90,
    sortable: false
  }, {
    data: "default",
    width: 60,
    sortable: false
  }, {
    data: "active",
    width: 60,
    sortable: false
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
        return '<div style="cursor:ns-resize; text-align:center" class="reorder-handle"><i class="fa fa-sort" aria-hidden="true"></i></div>';
      },
      targets: [0] /* Column to re-render */
    },
    {
      render: function (data, type, row) {
        return '<div data-moment="fromNowDateTime">' + data + '</div>';
      },
      targets: [3] /* Column to re-render */
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
      targets: [4,5]
    },
    {
      render: function (data, type, row) {
        var disabled = (row.undeletable == '1') ? ' disabled' : '';
        var btn_delete = (disabled) ? '' : ' row-btn-delete';
        return '<div class="row-actions-wrap"><div class="text-center row-actions" data-sl="' + data + '">' + 
          '<a href="#/admin/plan/' + data + '" class="btn btn-xs btn-inverse row-btn-edit" data-toggle="tooltip" title="{{ trans('global.edit') }}"><i class="fa fa-pencil"></i></a> ' + 
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

admin_plans_table.on( 'row-reorder', function (e, diff, edit ) {

  blockUI();

  var rows = {};

  for (var i=0; i < diff.length; i++) {
    var rowData = admin_plans_table.row( diff[i].node ).data();
    rows[rowData.sl]= diff[i].newData;
    //console.log('UPDATE ' + diff[i].newData + ' WHERE order = ' + diff[i].oldData);
  }

  var jqxhr = $.ajax({
    url: "{{ url('platform/admin/plan/order') }}",
    data: {rows: rows,  _token: '<?= csrf_token() ?>'},
    method: 'POST'
  })
  .done(function(data) {
    if(data.result == 'success') {
      admin_plans_table.ajax.reload();
    }
  })
  .fail(function() {
    console.log('error');
  })
  .always(function() {
    unblockUI();
  });
});

$('#dt-table-admin_plans_wrapper .dataTables_filter input').attr('placeholder', "{{ trans('global.search_') }}");
</script>
  <div class="row">
  <div class="col-sm-12">
    <div class="card-box table-responsive">
    <table class="table table-striped table-bordered table-hover" id="dt-table-admin_plans" style="width:100%">
      <thead>
      <tr>
        <th></th>
        <th>{{ trans('global.name') }}</th>
        <th>{{ trans('global.price') }}</th>
        <th>{{ trans('global.created') }}</th>
        <th class="text-center">{{ trans('global.default') }}</th>
        <th class="text-center">{{ trans('global.active') }}</th>
        <th class="text-center">{{ trans('global.actions') }}</th>
      </tr>
      </thead>
    </table>
    </div>
  </div>
  </div>
  <script>

$('#dt-table-admin_plans').on('click', '.row-btn-delete', function() {
  var sl = $(this).parent('.row-actions').attr('data-sl');

  swal({
    title: _lang['confirm'],
    type: "warning",
    showCancelButton: true,
    cancelButtonText: _lang['cancel'],
    confirmButtonColor: "#DD6B55",
    confirmButtonText: _lang['yes_delete']
  }, 
  function(){
    blockUI();

    var jqxhr = $.ajax({
      url: "{{ url('platform/admin/plan/delete') }}",
      data: {sl: sl,  _token: '<?= csrf_token() ?>'},
      method: 'POST'
    })
    .done(function(data) {
      if(data.result == 'success')
      {
        admin_plans_table.ajax.reload();
      }
      else
      {
        swal(data.msg);
      }
    })
    .fail(function() {
      console.log('error');
    })
    .always(function() {
      unblockUI();
    });
  });
});

</script> 
</div>
<!-- end container --> 