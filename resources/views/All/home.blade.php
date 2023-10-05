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
        <div class="stats" style="font-size:small;">
          <i class="material-icons text-primary">add_circle_outline</i><a href="#">{{ MyHelpers::admin_trans(auth()->user()->id,'Add') }}</a>
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
        <div class="stats" style="font-size:small;">
          <i class="material-icons text-primary">add_circle_outline</i><a href="#">{{ MyHelpers::admin_trans(auth()->user()->id,'Add') }}</a>
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

@endsection

@section('scripts')

<script>


</script>
@endsection