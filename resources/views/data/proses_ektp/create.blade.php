@extends('layouts.dashboard_template')

@section('content')
        <!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        {{ $page_title or "Page Title" }}
        <small>{{ $page_description ?? '' }}</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{route('dashboard.profil')}}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><a href="{{route('data.proses-ektp.index')}}">Proses e-KTP</a></li>
        <li class="active">{{$page_title}}</li>
    </ol>
</section>

<!-- Main content -->
<section class="content container-fluid">
    <div class="row">
        <div class="col-md-12">
            @include( 'partials.flash_message' )
            <div class="box box-primary">
                {{-- <div class="box-header with-border">
                     <h3 class="box-title">Aksi</h3>
                 </div>--}}
                <!-- /.box-header -->

                <!-- form start -->
                {!! Form::open( [ 'route' => 'data.proses-ektp.store', 'method' => 'post','id' => 'form-ektp', 'class' => 'form-horizontal form-label-left'] ) !!}

                <div class="box-body">

                    @if (count($errors) > 0)
                        <div class="alert alert-danger">
                            <strong>Oops!</strong> Ada yang salah dengan inputan Anda.<br><br>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @include('data.proses_ektp.form_create')

                </div>
                <!-- /.box-body -->
                <div class="box-footer">
                    <div class="pull-right">
                        <div class="control-group">
                            <a href="{{ route('data.proses-ektp.index') }}">
                                <button type="button" class="btn btn-default btn-sm"><i class="fa fa-refresh"></i> Batal
                                </button>
                            </a>
                            <button  id="btn_submit" type="submit" class="btn btn-primary btn-sm"><i class="fa fa-save"></i> Simpan
                            </button>
                        </div>
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
    <!-- /.row -->

</section>
<!-- /.content -->
@endsection
@include(('partials.asset_select2'))
@include('partials.asset_datetimepicker')
@push('scripts')
<script>
    $(function () {
        $('#penduduk_id').select2({
            ajax: {
                url: '{!! route('api.penduduk') !!}',
                dataType: 'json',
                delay: 200,
                data: function (params) {
                    return {
                        q: params.term,
                        page: params.page
                    };
                },
                processResults: function (data, params) {
                    params.page = params.page || 1;
                    return {
                        results: data.data,
                        pagination: {
                            more: (params.page * 10) < data.total
                        }
                    };
                }
            },
            minimumInputLength: 1,
            templateResult: function (repo) {
                if (repo.loading) return repo.nama;
                var markup = repo.nama;
                return markup;
            },
            templateSelection: function (repo) {
                return repo.nama;
            },
            escapeMarkup: function (markup) {
                return markup;
            },
            placeholder: "Nama Penduduk",
            allowClear: true,
        });

        $('#penduduk_id').on('select2:select', function (e) {
            var data = e.params.data;

            $('#alamat').val(data.alamat_sekarang);
            $('#nik').val(data.nik);
            $('#status_rekam').val(data.status_rekam);
            $('#status_rekam_nama').val(data.status_rekam);
            if(data.status_rekam == 1 || data.status_rekam == 2){
                $('#warning_status').removeClass("hide");
                document.getElementById("btn_submit").disabled = true;
            }else{
                $('#warning_status').addClass("hide");
                document.getElementById("btn_submit").disabled = false;
            }
        });

        //Datetimepicker
        $('.datepicker').each(function () {
            var $this = $(this);
            $this.datetimepicker({
                format: 'YYYY-MM-D'
            });
        });

    })


</script>
@endpush