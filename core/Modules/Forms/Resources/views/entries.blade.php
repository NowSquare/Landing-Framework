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
            <a class="navbar-brand link" href="#/forms">{{ trans('forms::global.module_name_plural') }}</a>
            <a class="navbar-brand no-link" href="javascript:void(0);">\</a>
            <a class="navbar-brand no-link" href="javascript:void(0);">{{ trans('forms::global.entries') }}</a>
            <a class="navbar-brand no-link" href="javascript:void(0);">\</a>
            <a class="navbar-brand link" href="{{ $this_form->url() }}" target="_blank">{{ $this_form->name }}</a>
          </div>

          <div class="collapse navbar-collapse" id="bs-title-navbar">
<?php /*
            <div class="navbar-form navbar-right m-l-15">
              <a href="#/forms/entries/new" class="btn btn-success"><i class="fa fa-plus" aria-hidden="true"></i> {{ trans('global.create_entry') }}</a>
            </div>
*/ ?>
            <div class="navbar-form navbar-right m-l-15">
              <div class="form-control" id="reportrange" style="cursor:pointer;padding:5px 10px; display:table">
                <i class="mi date_range" style="margin:0 5px 0 0"></i> <span></span>
              </div>
            </div>

            <div class="navbar-form navbar-right" style="min-width:240px">

<select id="forms" class="select2-required">
<?php
foreach($forms as $form) {
$sl_form = \Platform\Controllers\Core\Secure::array2string(['form_id' => $form->id]);
$selected = ($form->id == $form_id) ? ' selected' : '';
echo '<option value="' . $sl_form . '"' . $selected . '>' . $form->name . '</option>';
}
?>
</select>

<script>
$('#forms').on('change', function() {
  document.location = '#/forms/entries/' + $(this).val();
});
</script>
              </div>

<?php if ($data_found) { ?>
            <ul class="nav navbar-nav navbar-right">
              <li class="dropdown">
                <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">{{ trans('global.records') }} <span class="caret"></span></a>
                <ul class="dropdown-menu">
                  <li><a href="javascript:void(0);" id="select-all">{{ trans('global.select_all') }}</a></li>
                  <li><a href="javascript:void(0);" id="deselect-all">{{ trans('global.select_none') }}</a></li>
                  <li role="separator" class="divider"></li>
                  <li class="dropdown-header">{{ trans('global.with_selected') }}</li>
                  <li class="must-have-selection"><a href="javascript:void(0);" id="selected-delete">{{ trans('global.delete_selected') }}</a></li>
                </ul>
              </li><?php /*
              <li class="dropdown">
                <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">{{ trans('global.export') }} <span class="caret"></span></a>
                <ul class="dropdown-menu">
                  <li><a href="{{ url('forms/entries/export?type=xls') }}">Excel5 (xls)</a></li>
                  <li><a href="{{ url('forms/entries/export?type=xlsx') }}">Excel2007 (xlsx)</a></li>
                  <li><a href="{{ url('forms/entries/export?type=csv') }}">CSV</a></li>
                </ul>
              </li>*/ ?>
            </ul>
<?php } // $data_found ?>
          </div>
        </div>
      </nav>
     
    </div>
  </div>

  <div class="row">
    <div class="col-sm-12">
<?php if ($data_found) { ?>
      <div class="card-box table-responsive">
        <table class="table table-striped table-bordered table-hover table-selectable" id="dt-table-entries" style="width:100%">
          <thead>
            <tr>
              <th>{{ trans('global.email') }}</th>
<?php 
foreach($columns['form'] as $column) { 
  foreach (trans('global.form_fields') as $form_cat => $form_items) {
    $column = str_replace($form_cat . '_',$form_cat . '.', $column);
  }
  $trans = trans('global.form_fields.' . $column);
?>
              <th>{{ $trans }}</th>
<?php } ?>
<?php 
foreach($columns['custom'] as $column) { 
?>
              <th>{{ $column }}</th>
<?php } ?>
              <th>{{ trans('global.created') }}</th>
              <th class="text-center">{{ trans('global.actions') }}</th>
            </tr>
          </thead>
        </table>
      </div>

<?php } else { // $data_found ?>

<div class="panel panel-border panel-inverse">
  <div class="panel-heading">
    <h3 class="panel-title">{{ trans('forms::global.no_entries_found') }}</h3>
  </div>
  <div class="panel-body">
    <p>{!! trans('forms::global.no_entries_found_text', ['link' => $this_form->url()]) !!}</p>
  </div>
</div>


<?php } // $data_found ?>
    </div>
  </div>

</div>

<style type="text/css">
</style>

<script>

<?php if ($data_found) { ?>
/* 
 * DataTable
 */

var date_start = '{{ $date_start }}';
var date_end = '{{ $date_end }}';

var entries_table = $('#dt-table-entries').DataTable({
  ajax: { 
    url: '{{ url('forms/entries/data') }}',
    type: 'POST',
    data: function(d) {
      d.date_start = date_start;
      d.date_end = date_end;
      d._token = '<?= csrf_token() ?>';
      d.sl = '{{ $sl }}';
    }
  },
  order: [ [<?php echo count($columns['form']) + count($columns['custom']) + 1 ?>, "desc"] ],
  dom: "<'row'<'col-sm-12 dt-header'<'pull-left'lr><'pull-right'f><'pull-right hidden-sm hidden-xs'T><'clearfix'>>>t<'row'<'col-sm-12 dt-footer'<'pull-left'i><'pull-right'p><'clearfix'>>>",
  processing: true,
  serverSide: true,
  stateSave: true,
  responsive: true,
  stripeClasses: [],
  lengthMenu: [
    [10, 25, 50, 75, 100],
    [10, 25, 50, 75, 100]
  ],
  columns: [
    {	data: "email" }, 
<?php foreach($columns['form'] as $column) { ?>
    { data: "{{ $column }}" }, 
<?php } ?>
<?php foreach($columns['custom'] as $column) { ?>
    { data: "{{ $column }}" }, 
<?php } ?>
    { data: "created_at", width: 110 },
    { data: "sl", width: 74, sortable: false}
  ],
  rowCallback: function(row, data) {
    if($.inArray(data.DT_RowId.replace('row_', ''), selected_entries) !== -1) {
      $(row).addClass('success');
    }
  },
  fnDrawCallback: function() {
    onDataTableLoad();
  },
  columnDefs: [
    {
      render: function (data, type, row) {
        return '<div data-moment="fromNowDateTime">' + data + '</div>';
      },
      targets: [<?php echo count($columns['form']) + count($columns['custom']) + 1 ?>]
    },/*
		{
			render: function (data, type, row) {
        var html = '<div class="text-center"><button class="btn btn-default btn-xs mapRow" data-id="' + row.DT_RowId + '" data-lat="' + row.lat + '" data-lng="' + row.lng + '" data-zoom="' + row.zoom + '" id="mapRow' + row.DT_RowId + '" data-toggle="popover" data-placement="top" data-html="true" data-trigger="focus" data-content="<div class=\'gmap\' id=\'gmap-' + row.DT_RowId + '\' style=\'width:240px;height:240px;\'></div>"><i class="fa fa-map-marker" aria-hidden="true"></i> {{ trans('global.map') }}</button></div>';

				return html;
			},
			targets: [12]
		},*/
    {
      render: function (data, type, row) {
        return '<div class="row-actions-wrap"><div class="text-center row-actions" data-sl="' + data + '">' + 
<?php /*          '<a href="#/forms/entries/edit/' + data + '" class="btn btn-xs btn-success row-btn-edit" data-toggle="tooltip" title="{{ trans('global.edit') }}"><i class="fa fa-pencil"></i></a> ' + */?>
          '<a href="javascript:void(0);" class="btn btn-xs btn-danger row-btn-delete" data-toggle="tooltip" title="{{ trans('global.delete') }}"><i class="fa fa-trash"></i></a>' + 
          '</div></div>';
      },
      targets: <?php echo count($columns['form']) + count($columns['custom']) + 2 ?>
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
})
.on('init.dt', function() {
	var count = $(this).dataTable().fnGetData().length;
	if(count == 0) {
		$('.must-have-selection').addClass('disabled');
	}
});

$('#select-all').on('click', function() {
	selected_entries = [];

	$('#dt-table-entries tbody tr').each(function() {
		var id = this.id.replace('row_', '');
		selected_entries.push(id);
	});

	checkButtonVisibility();
	entries_table.ajax.reload();
});

$('#deselect-all').on('click', function() {
	selected_entries = [];
	checkButtonVisibility();
	entries_table.ajax.reload();
});
    
// Click
$('#dt-table-entries').on('click', 'tr', function() {
	checkButtonVisibility();
});

$('#dt-table-entries_wrapper .dataTables_filter input').attr('placeholder', "{{ trans('global.search_') }}");

$('#dt-table-entries tbody').on('click dblclick', 'tr', function(e) {
    if(e.target.nodeName == 'TD') {
      var td_index = $(e.target).index();
    } else {
      var td_index = $(e.target).parents('td').index();
    }

    if(td_index == <?php echo count($columns['form']) + count($columns['custom']) + 2 ?>) return;

    var id = this.id.replace('row_', '');
    var index = $.inArray(id, selected_entries);

    if (index === -1) {
      selected_entries.push(id);
    } else {
      selected_entries.splice(index, 1);
    }

    $(this).toggleClass('success');
});

checkButtonVisibility();

function checkButtonVisibility() {
  var disabled = (parseInt(selected_entries.length) > 0) ? false : true;
	if (disabled) {
		$('.must-have-selection').addClass('disabled');
	} else {
		$('.must-have-selection').removeClass('disabled');
	}
}

$('#dt-table-entries').on('click', '.row-btn-delete', function() {
  var sl = $(this).parent('.row-actions').attr('data-sl');

  swal({
    title: _lang['confirm'],
    type: "warning",
    showCancelButton: true,
    cancelButtonText: _lang['cancel'],
    confirmButtonColor: "#DD6B55",
    confirmButtonText: _lang['yes_delete']
  }).then(function (result) {
    blockUI();
  
    var jqxhr = $.ajax({
      url: "{{ url('forms/entry/delete') }}",
      data: {sl: sl, _token: '<?= csrf_token() ?>'},
      method: 'POST'
    })
    .done(function(data) {
      if(data.result == 'success') {
        entries_table.ajax.reload();
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

$('#selected-delete').on('click', function() {
	if (! $(this).parent('li').hasClass('disabled'))
	{
		swal({
		  title: _lang['confirm'],
		  type: "warning",
		  showCancelButton: true,
		  cancelButtonText: _lang['cancel'],
		  confirmButtonColor: "#DD6B55",
		  confirmButtonText: _lang['yes_delete']
    }).then(function (result) {
			blockUI();
		
			var jqxhr = $.ajax({
				url: "{{ url('forms/entry/delete') }}",
				data: { ids: selected_entries, _token: '<?= csrf_token() ?>'},
				method: 'POST'
			})
			.done(function() {
				selected_entries = [];
				entries_table.ajax.reload();
				checkButtonVisibility();
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
	}
});

<?php } else { // $data_found ?>

<?php } // $data_found ?>

/* 
 * Date Range Picker
 */

$('#reportrange span').html(moment('<?php echo $date_start ?>').format('MMMM D, YYYY') + ' - ' + moment('<?php echo $date_end ?>').format('MMMM D, YYYY'));

daterangepicker_opts.ranges = {
 [ _lang['today'] ]: [ moment(), moment() ],
 [ _lang['yesterday'] ]: [ moment().subtract(1, 'days'), moment().subtract(1, 'days') ],
 [ _lang['last_7_days'] ]: [ moment().subtract(6, 'days'), moment() ],
 [ _lang['last_30_days'] ]: [ moment().subtract(29, 'days'), moment() ],
 [ _lang['this_month'] ]: [ moment().startOf('month'), moment().endOf('month') ],
 [ _lang['last_month'] ]: [ moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month') ],
 [ _lang['all_time'] ]: [ moment('<?php echo $earliest_date ?>').format('MM-D-YYYY'), moment() ]
};
  
daterangepicker_opts.startDate = moment('<?php echo $date_start ?>').format('MM-D-YYYY');
daterangepicker_opts.endDate = moment('<?php echo $date_end ?>').format('MM-D-YYYY');
daterangepicker_opts.minDate = moment('<?php echo $earliest_date ?>').format('MM-D-YYYY');
daterangepicker_opts.maxDate = '<?php echo date('m/d/Y') ?>';

$('#reportrange').daterangepicker(daterangepicker_opts, 
  function(start, end) {
    console.log("Callback has been called!");
    $('#reportrange span').html(start.format('D MMMM YYYY') + ' - ' + end.format('D MMMM YYYY'));
  startDate = start;
  endDate = end;    

  });

$('#reportrange').on('apply.daterangepicker', function(ev, picker) {
  $('#reportrange span').html(picker.startDate.format('MMMM D, YYYY') + ' - ' + picker.endDate.format('MMMM D, YYYY'));
  date_start = picker.startDate.format('YYYY-MM-DD');
  date_end = picker.endDate.format('YYYY-MM-DD');

<?php if ($data_found) { ?>
  entries_table.ajax.reload();
<?php } // $data_found ?>
});

</script>