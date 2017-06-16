<section class="-x-block">
  <div class="content text-dark content-padding-l -x-block-bg-img" style="">
    <div class="content-overlay -x-block-bg-color" style="background-color:rgba(240,240,240,1)">
      <div class="container">
        <div class="row">
          <div class="col-12 text-center">
            <div class="-x-text">
              <p class="lead">{!! trans('landingpages::block.sale_ends_in') !!}</p>
            </div>
            <h1 class="display-4 mt-3 -x-countdown" data-countdown="{{ \Carbon\Carbon::now('UTC')->addDays(2)->format('Y-m-d H:i:s') }}">
              <span class="day">_</span> day(s) and <span class="hour">__</span>h <span class="minute">__</span>m <span class="second">__</span>s
            </h1>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>