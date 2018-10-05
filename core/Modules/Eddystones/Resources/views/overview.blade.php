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
            <a class="navbar-brand no-link" href="javascript:void(0);">{{ trans('eddystones::global.eddystone_beacons') }} ({{ $eddystones['count'] }})</a>
          </div>

          <div class="collapse navbar-collapse" id="bs-title-navbar">
            <div class="navbar-form navbar-right">
              <a href="#/eddystones/create" class="btn btn-success"><i class="fa fa-plus" aria-hidden="true"></i> {{ trans('eddystones::global.create_eddystone') }}</a>
            </div>
          </div>
        </div>
      </nav>
    
    </div>
  </div>

  <div class="row">
    <div class="col-sm-12">
      <div class="card-box table-responsive">

          <table id="tbl-eddystones" class="table table-striped table-bordered">
            <thead>
              <tr>
                <th>{{ trans('global.name') }}</th>
                <th>{{ trans('eddystones::global.id') }}</th>
                <th class="text-center">{{ trans('global.active') }}</th>
                <th class="text-center">{{ trans('global.actions') }}</th>
              </tr>
            </thead>
            <tbody>
<?php 
foreach($eddystones['beacons'] as $eddystone) {
  $beaconName = $eddystone->getBeaconName();
  $sl_eddystone = \Platform\Controllers\Core\Secure::array2string(['beaconName' => $beaconName]);
?>
              <tr>
                <td>{{ $eddystone->description }}</td>
                <td>{{ $beaconName }}</td>
                <td>{{ $eddystone->status }}</td>
                <td>{{ $sl_eddystone }}</td>
              </tr>
<?php 
} 
?>
            </tbody>
          </table>
      </div>
    </div>

  </div>
</div>

<style type="text/css">
</style>

<script>
var eddystones_table = $('#tbl-eddystones').DataTable({
  order: [
    [0, "asc"]
  ],
  dom: "<'row'<'col-sm-12 dt-header'<'pull-left'lr><'pull-right'f><'pull-right hidden-sm hidden-xs'T><'clearfix'>>>t<'row'<'col-sm-12 dt-footer'<'pull-left'i><'pull-right'p><'clearfix'>>>",
  processing: true,
  stateSave: true,
  responsive: false,
  stripeClasses: [],
  lengthMenu: [
    [10, 25, 50, 75, 100, 1000000],
    [10, 25, 50, 75, 100, "{{ trans('global.all') }}"]
  ],
  columns: [{
  }, {
  }, {
    width: 60
  }, {
    width: 74,
    sortable: false
  }],
  fnDrawCallback: function() {
    onDataTableLoad();
  },
  columnDefs: [
    {
      render: function (data, type, row) {
        if(data == 'ACTIVE')
        {
          return '<div class="text-center"><i class="fa fa-check" aria-hidden="true"></i></div>';
        }
        else
        {
          return '<div class="text-center"><i class="fa fa-times" aria-hidden="true"></i></div>';
        }
      },
      targets: 2
    },
    {
      render: function (data, type, row) {

        return '<div class="row-actions-wrap">' + 
               '<div class="text-center row-actions" data-sl="' + data + '">' + 
               '<a href="#/eddystones/' + data + '" class="btn btn-xs btn-inverse row-btn-edit" data-toggle="tooltip" title="{{ trans('global.edit') }}"><i class="fa fa-pencil"></i></a> ' + 
               '<a href="javascript:void(0);" class="btn btn-xs btn-danger row-btn-delete" data-toggle="tooltip" title="{{ trans('global.delete') }}"><i class="fa fa-trash"></i></a>' + 
               '</div>' + 
               '</div>';
      },
      targets: 3 /* Column to re-render */
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

$('#tbl-eddystones').on('click', '.row-btn-delete', function() {
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
      url: "{{ url('eddystones/delete') }}",
      data: {sl: sl,  _token: '<?= csrf_token() ?>'},
      method: 'POST'
    })
    .done(function(data) {
      if(data.result == 'success') {
        document.location.reload();
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