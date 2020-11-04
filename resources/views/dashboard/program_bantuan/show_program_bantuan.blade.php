@extends('layouts.dashboard_template')

@section('content')

        <!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        {{ $page_title ?? "Page Title" }}
        <small>{{ $page_description ?? '' }}</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
        <li class="active">Here</li>
    </ol>
</section>

<!-- Main content -->
<section class="content container-fluid">
    <div class="box box-primary">
        <div class="box-header with-border">
            <form class="form-horizontal">

                <div class="col-md-4">
                    <div class="form-group">
                        <label for="list_desa" class="col-sm-4 control-label">Desa</label>

                        <div class="col-sm-8">
                            <select class="form-control" id="list_desa">
                                <option value="ALL">ALL</option>
                                @foreach($list_desa as $desa)
                                    <option value="{{$desa->desa_id}}">{{$desa->nama}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 col-lg-2 col-sm-12">
                    <div class="form-group">
                        <label for="list_year" class="col-sm-4 control-label">Tahun</label>

                        <div class="col-sm-8">
                            <select class="form-control" id="list_year">
                                <option value="ALL">ALL</option>
                                @foreach($year_list as $year)
                                    <option value="{{$year}}">{{$year}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- /.row -->
    <div class="row">
        <div class="col-md-12">
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <li class="active"><a href="#penduduk" data-toggle="tab">Penduduk/Perorangan</a></li>
                    <li><a href="#keluarga" data-toggle="tab">Keluarga/KK</a></li>
                </ul>

                <div class="tab-content">
                    <div class="active tab-pane" id="penduduk">
                        <div class="row">
                            <div class="col-md-12">
                                <div id="chart_bantuan_penduduk" style="width: 100%; height: 500px; overflow: hidden; text-align: left;"></div>
                            </div>
                            <div id="tabel_bantuan_penduduk" class="col-md-12">

                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="keluarga">
                        <div class="row">
                            <div class="col-md-12">
                                <div id="chart_bantuan_keluarga" style="width: 100%; height: 500px; overflow: hidden; text-align: left;"></div>
                            </div>
                            <div id="tabel_bantuan_keluarga" class="col-md-12">

                            </div>
                        </div>
                    </div>

                </div>
                <!-- /.nav-tabs-custom -->
            </div>
        </div>
    </div>
</section>
<!-- /.content -->
@endsection

@include('partials.asset_amcharts')
@include('partials.asset_select2')

@push('scripts')
<script>
    $(function () {
        // Select 2 Kecamatan
        $('#list_desa').select2();
        $('#list_year').select2();


        // Change Dashboard when Lsit Desa changed
        $('#list_desa').on('select2:select', function (e) {
            var kid = $('#defaultProfil').val();
            var did = e.params.data;
            var year = $('#list_year').find(":selected").text();

            change_das_bantuan(kid, did.id, year);
        });

        // Change Dashboard when List Year changed
        $('#list_year').on('select2:select', function (e) {
            var kid = $('#defaultProfil').val();
            var did = $('#list_desa').find(":selected").val();
            var year = this.value;
            change_das_bantuan(kid, did, year);
        });


        /*
         Initial Dashboard
         */
        var kid = '{{ $defaultProfil }}';
        if (kid == null) {
            kid = $('#defaultProfil').val();
        }
        var did = $('#list_desa').find(":selected").val();
        var year = $('#list_year').find(":selected").text();

        change_das_bantuan(kid, did, year);
        /*
         End Initial Dashboard
         */
    });

    function change_das_bantuan(kid, did, year)
    {
        $.ajax('{!! route('dashboard.program-bantuan.chart-penduduk') !!}', {
            data: {kid: kid, did: did, y: year}
        }).done(function (data) {
            create_chart_bantuan_penduduk(data);
        });

        $.ajax('{!! route('dashboard.program-bantuan.chart-keluarga') !!}', {
            data: {kid: kid, did: did, y: year}
        }).done(function (data) {
            create_chart_bantuan_keluarga(data);
        });
    }

    function create_chart_bantuan_penduduk(data)
    {
        var chart_bantuan_penduduk = AmCharts.makeChart( "chart_bantuan_penduduk", {
            "hideCredits": true,
            "type": "pie",
            "theme": "light",
            "dataProvider": data,
            "valueField": "value",
            "titleField": "program",
            "outlineAlpha": 0.4,
            "depth3D": 15,
            "balloonText": "[[title]]<br><span style='font-size:14px'><b>[[value]]</b> ([[percents]]%)</span>",
            "angle": 30,
            "export": {
                "enabled": true,
                "pageOrigin":false,
                "fileName":"Peserta Program Bantuan Penduduk/Perorangan",
            },
            "allLabels": [{
                "text": "Peserta Program Bantuan Penduduk/Perorangan",
                "align": "center",
                "bold": true,
                "size": 20,
                "y": -4
            }],
            "legend":{
                "position":"bottom",
                "marginRight":20,
                "autoMargins":false,
                "valueWidth": 120
            },
            "marginTop" : 50
        } );
    }

    function create_chart_bantuan_keluarga(data)
    {
        var chart_bantuan_keluarga = AmCharts.makeChart( "chart_bantuan_keluarga", {
            "hideCredits": true,
            "type": "pie",
            "theme": "light",
            "dataProvider": data,
            "valueField": "value",
            "titleField": "program",
            "outlineAlpha": 0.4,
            "depth3D": 15,
            "balloonText": "[[title]]<br><span style='font-size:14px'><b>[[value]]</b> ([[percents]]%)</span>",
            "angle": 30,
            "export": {
                "enabled": true,
                "pageOrigin":false,
                "fileName":"Peserta Program Bantuan Keluarga/KK",
            },
            "allLabels": [{
                "text": "Peserta Program Bantuan Keluarga/KK",
                "align": "center",
                "bold": true,
                "size": 20,
                "y": -4
            }],
            "legend":{
                "position":"bottom",
                "marginRight":20,
                "autoMargins":false,
                "valueWidth": 120
            },
            "marginTop" : 50
        } );
    }
</script>
@endpush
