@extends('layouts.content')

@section('title')
{{ MyHelpers::admin_trans(auth()->user()->id,'Home') }}
@endsection

@section('css_style')
<link href="  {{ url('interface_style/css/material-dashboard.css?v=2.1.1') }}" rel="stylesheet" />

<style>
  .with-arrow .nav-link.active {
    position: relative;
  }

  .middle-screen {
    height: 100%;
    width: 100%;
    display: flex;
    flex-direction: column;
    justify-content: center;
    text-align: center;
  }

  .with-arrow .nav-link.active::after {
    content: '';
    border-left: 6px solid transparent;
    border-right: 6px solid transparent;
    border-top: 6px solid #2b90d9;
    position: absolute;
    bottom: -6px;
    left: 50%;
    transform: translateX(-50%);
    display: block;
  }

  /* lined tabs */

  .lined .nav-link {
    border: none;
    border-bottom: 3px solid transparent;
  }

  .lined .nav-link:hover {
    border: none;
    border-bottom: 3px solid transparent;
  }

  .lined .nav-link.active {
    background: none;
    color: #555;
    border-color: #2b90d9;
  }


  .nav-pills .nav-link {
    color: #555;
  }

  .text-uppercase {
    letter-spacing: 0.1em;
  }



  .nav-item>a:hover {
    color: grey;
  }
</style>

@endsection

@section('customer')


<div class="row">
  <div class="col-lg-3 col-md-6 col-sm-6">
    <div class="card card-stats">
      <div class="card-header card-header-warning card-header-icon">
        <div class="card-icon">
          <i class="fa fa-home"></i>
        </div>
        <p class="card-category">{{ MyHelpers::admin_trans(auth()->user()->id,'requests') }} {{ MyHelpers::admin_trans(auth()->user()->id,'Purchase') }}</p>
        <h3 class="card-title">55</h3>
      </div>
      <div class="card-footer">
        <div class="stats">
          <br>
        </div>
      </div>
    </div>
  </div>

  <div class="col-lg-3 col-md-6 col-sm-6">
    <div class="card card-stats">
      <div class="card-header card-header-info card-header-icon">
        <div class="card-icon">
          <i class="fa fa-unlock-alt"></i>
        </div>
        <p class="card-category" style="font-size:small">{{ MyHelpers::admin_trans(auth()->user()->id,'requests') }} {{ MyHelpers::admin_trans(auth()->user()->id,'Mortgage') }}</p>
        <h3 class="card-title">55</h3>
      </div>
      <div class="card-footer">
        <div class="stats">
          <br>
        </div>
      </div>
    </div>
  </div>

  <div class="col-lg-3 col-md-6 col-sm-6">
    <div class="card card-stats">
      <div class="card-header card-header-danger card-header-icon">
        <div class="card-icon">
          <i class="fa fa-handshake-o"></i>
        </div>
        <p class="card-category">{{ MyHelpers::admin_trans(auth()->user()->id,'requests') }} {{ MyHelpers::admin_trans(auth()->user()->id,'Mor-Pur') }}</p>
        <h3 class="card-title">55</h3>
      </div>
      <div class="card-footer">
        <div class="stats">
          <br>
        </div>
      </div>
    </div>
  </div>
  <div class="col-lg-3 col-md-6 col-sm-6">
    <div class="card card-stats">
      <div class="card-header card-header-success card-header-icon">
        <div class="card-icon">
          <i class="fa fa-usd"></i>
        </div>
        <p class="card-category"> {{ MyHelpers::admin_trans(auth()->user()->id,'requests') }} {{ MyHelpers::admin_trans(auth()->user()->id,'Prepayments and Tasahil') }}</p>
        <h3 class="card-title">55</h3>
      </div>
      <div class="card-footer">
        <div class="stats">
          <br>
        </div>

      </div>
    </div>
  </div>
</div>


<div class="row">
  <div class="col-lg-6 col-md-12">
    <div class="au-card recent-report">
      <div class="au-card-inner">
        <h3 class="title-2">recent reports</h3>
        <div class="chart-info">
          <div class="chart-info__left">
            <div class="chart-note">
              <span class="dot dot--blue"></span>
              <span>products</span>
            </div>
            <div class="chart-note mr-0">
              <span class="dot dot--green"></span>
              <span>services</span>
            </div>
          </div>
          <div class="chart-info__right">
            <div class="chart-statis">
              <span class="index incre">
                <i class="zmdi zmdi-long-arrow-up"></i>25%</span>
              <span class="label">products</span>
            </div>
            <div class="chart-statis mr-0">
              <span class="index decre">
                <i class="zmdi zmdi-long-arrow-down"></i>10%</span>
              <span class="label">services</span>
            </div>
          </div>
        </div>
        <div class="recent-report__chart">
          <canvas id="recent-rep-chart"></canvas>
        </div>
      </div>
    </div>
  </div>
  <div class="col-lg-6 col-md-12">

    <div class="card">
      <div class="card-header card-header-warning">
        <h4 class="card-title">آمار کارکنان</h4>
        <p class="card-category">کارکنان جدید از ۱۵ آبان ۱۳۹۶</p>
      </div>
      <div class="card-body table-responsive">
        <table class="table table-hover">
          <thead class="text-warning">
            <th>کد</th>
            <th>نام</th>
            <th>حقوق</th>
            <th>استان</th>
          </thead>
          <tbody>
            <tr>
              <td>1</td>
              <td>احمد حسینی</td>
              <td>$36,738</td>
              <td>مازندران</td>
            </tr>
            <tr>
              <td>2</td>
              <td>مینا رضایی</td>
              <td>$23,789</td>
              <td>گلستان</td>
            </tr>
            <tr>
              <td>3</td>
              <td>مبینا احمدپور</td>
              <td>$56,142</td>
              <td>تهران</td>
            </tr>
            <tr>
              <td>4</td>
              <td>جلال آقایی</td>
              <td>$38,735</td>
              <td>شهرکرد</td>
            </tr>
            <tr>
              <td>4</td>
              <td>جلال آقایی</td>
              <td>$38,735</td>
              <td>شهرکرد</td>
            </tr>
            <tr>
              <td>4</td>
              <td>جلال آقایی</td>
              <td>$38,735</td>
              <td>شهرکرد</td>
            </tr>

          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-lg-6 col-md-12">
    <div class="card">
      <div class="card-header card-header-primary">
        <h3 class="card-title">اعلان ها</h3>
        <p class="card-category">ایجاد شده توسط دوست ما
          <a target="_blank" href="https://github.com/mouse0270">Robert McIntosh</a>. لطفا
          <a href="http://bootstrap-notify.remabledesigns.com/" target="_blank">مستندات کامل </a> را مشاهده بکنید.
        </p>
      </div>
      <div class="card-body">
        <div class="alert alert-warning">
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <i class="material-icons">close</i>
          </button>
          <span>
            این یک اعلان است که با کلاس "alert-warning" ایجاد شده است.</span>
        </div>
        <div class="alert alert-primary">
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <i class="material-icons">close</i>
          </button>
          <span>
            این یک اعلان است که با کلاس "alert-primary" ایجاد شده است.</span>
        </div>
        <div class="alert alert-info alert-with-icon" data-notify="container">
          <i class="material-icons" data-notify="icon">add_alert</i>
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <i class="material-icons">close</i>
          </button>
          <span data-notify="پیام">این یک اعلان با دکمه بستن و آیکن است</span>
        </div>
      </div>
    </div>
  </div>

  <div class="col-lg-6 col-md-12">
    <div class="card">
      <div class="card-header">
        <h4>Animated Labels</h4>
      </div>
      <div class="card-body">
        <p class="muted">To create a default progress bar, add a
          <code>.progress-bar-striped and .progress-bar-animated</code> class to a
          <code>&lt;div&gt;</code> element:</p>
        <div class="progress mb-2">
          <div class="progress-bar bg-success progress-bar-striped progress-bar-animated" role="progressbar" style="width: 25%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">25%</div>
        </div>
        <div class="progress mb-2">
          <div class="progress-bar bg-info progress-bar-striped progress-bar-animated" role="progressbar" style="width: 50%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100">50%</div>
        </div>
        <div class="progress mb-2">
          <div class="progress-bar bg-warning progress-bar-striped progress-bar-animated" role="progressbar" style="width: 75%" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100">75%</div>
        </div>
        <div class="progress mb-2">
          <div class="progress-bar bg-danger progress-bar-striped progress-bar-animated" role="progressbar" style="width: 90%" aria-valuenow="90" aria-valuemin="0" aria-valuemax="100">90%</div>
        </div>
      </div>
    </div>

  </div>

</div>



<div class="row">
  <div class="col-lg-7">
    <div class="table-responsive table--no-card m-b-30">
      <table class="table table-borderless table-striped table-earning">
        <thead>
          <tr>
            <th>date</th>
            <th>order ID</th>
            <th>name</th>
            <th class="text-right">price</th>
            <th class="text-right">quantity</th>
            <th class="text-right">total</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>2018-09-29 05:57</td>
            <td>100398</td>
            <td>iPhone X 64Gb Grey</td>
            <td class="text-right">$999.00</td>
            <td class="text-right">1</td>
            <td class="text-right">$999.00</td>
          </tr>
          <tr>
            <td>2018-09-28 01:22</td>
            <td>100397</td>
            <td>Samsung S8 Black</td>
            <td class="text-right">$756.00</td>
            <td class="text-right">1</td>
            <td class="text-right">$756.00</td>
          </tr>
          <tr>
            <td>2018-09-27 02:12</td>
            <td>100396</td>
            <td>Game Console Controller</td>
            <td class="text-right">$22.00</td>
            <td class="text-right">2</td>
            <td class="text-right">$44.00</td>
          </tr>
          <tr>
            <td>2018-09-26 23:06</td>
            <td>100395</td>
            <td>iPhone X 256Gb Black</td>
            <td class="text-right">$1199.00</td>
            <td class="text-right">1</td>
            <td class="text-right">$1199.00</td>
          </tr>
          <tr>
            <td>2018-09-25 19:03</td>
            <td>100393</td>
            <td>USB 3.0 Cable</td>
            <td class="text-right">$10.00</td>
            <td class="text-right">3</td>
            <td class="text-right">$30.00</td>
          </tr>
          <tr>
            <td>2018-09-29 05:57</td>
            <td>100392</td>
            <td>Smartwatch 4.0 LTE Wifi</td>
            <td class="text-right">$199.00</td>
            <td class="text-right">6</td>
            <td class="text-right">$1494.00</td>
          </tr>
          <tr>
            <td>2018-09-24 19:10</td>
            <td>100391</td>
            <td>Camera C430W 4k</td>
            <td class="text-right">$699.00</td>
            <td class="text-right">1</td>
            <td class="text-right">$699.00</td>
          </tr>
          <tr>
            <td>2018-09-22 00:43</td>
            <td>100393</td>
            <td>USB 3.0 Cable</td>
            <td class="text-right">$10.00</td>
            <td class="text-right">3</td>
            <td class="text-right">$30.00</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
  <div class="col-lg-5">
    <div class="au-card au-card--bg au-card-top-countries m-b-30">
      <div class="au-card-inner">
    
      <h3 class="title-2 m-b-40">Single Bar Chart</h3>
      <canvas id="singelBarChart"></canvas>
   
 
      </div>
    </div>
  </div>
</div>

@endsection

@section('scripts')

<script>


</script>
@endsection