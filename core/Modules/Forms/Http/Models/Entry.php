<?php
namespace Modules\Forms\Http\Models;

use Illuminate\Database\Eloquent\Model;

class Entry extends Model {

  protected $table = 'form_entries';

  protected $casts = [
    'entry' => 'json',
    'meta' => 'json'
  ];

  public function setUpdatedAtAttribute($value) {
    // Do nothing.
  }

  public function getUpdatedAtColumn() {
    return null;
  }

  /**
   * Dynamically set a model's table.
   *
   * @param  $table
   * @return void
   */
  public function setTable($table) {
    $this->table = $table;
    return $this;
  }

  /**
   * Get all columns which have a non-null entry.
   *
   * @param \Illuminate\Database\Eloquent\Builder $query
   * @return \Illuminate\Database\Eloquent\Builder
   */
  public function getColumns($form_id) {
    $columns = [
      'personal_first_name',
      'personal_last_name',
      'personal_name',
      'personal_gender',
      'personal_title',
      'personal_impressum',
      'personal_birthday',
      'personal_website',
      'personal_address1',
      'personal_address2',
      'personal_street',
      'personal_house_number',
      'personal_phone',
      'personal_mobile',
      'personal_fax',
      'personal_postal',
      'personal_city',
      'personal_state',
      'personal_country',
      'business_company',
      'business_job_title',
      'business_website',
      'business_email',
      'business_address1',
      'business_address2',
      'business_street',
      'business_house_number',
      'business_phone',
      'business_mobile',
      'business_fax',
      'business_postal',
      'business_city',
      'business_state',
      'business_country',
      'booking_date',
      'booking_start_date',
      'booking_end_date',
      'booking_time',
      'booking_start_time',
      'booking_end_time',
      'booking_date_time',
      'booking_start_date_time',
      'booking_end_date_time'
    ];

    $sql = "SELECT * FROM (";

    $column_count = 0;
    foreach ($columns as $column) {
      $sql .= "SELECT
                IF(COUNT(`" . $column . "`), NULL, '" . $column . "') AS `column`
              FROM " . $this->table . " WHERE form_id = " . $form_id . "";

      if ($column_count < count($columns) - 1) $sql .= " UNION ALL ";
      $column_count++;
    }

    $sql .= ") t WHERE `column` IS NOT NULL";

    $columns_null_query = \DB::select($sql);

    $columns_null = [];
    
    foreach ($columns_null_query as $column) {
      $columns_null[] = $column->column;
    }

    // Get non-null columns
    $columns_not_null = array_diff($columns, $columns_null);

    // Reset keys
    $columns_not_null = array_values($columns_not_null);

    $return['form'] = $columns_not_null;

    // Get custom columns
    $sql = "SELECT entry FROM " . $this->table . " WHERE form_id = " . $form_id . "";

    $columns_json_query = \DB::select($sql);

    $columns_not_null = [];

    foreach ($columns_json_query as $column) {
      $json = json_decode($column->entry);
      foreach ($json as $key => $val) {
        if ($val != '') $columns_not_null[] = $key;
      }
    }

    $columns_not_null = array_unique($columns_not_null); 

    $return['custom'] = $columns_not_null;

    return $return;
  }

  public function form() {
    return $this->belongsTo('Forms\Form', 'form_id');
  }
}