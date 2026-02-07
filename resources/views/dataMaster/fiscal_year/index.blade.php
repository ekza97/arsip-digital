@extends('layouts.app')

@section('content')
    <div class="container-xl">

        <h1 class="app-page-title pb-2"><i class="fas fa-hashtag me-2"></i>Tahun Anggaran</h1>

        <div class="row">
            <div class="col-12 col-lg-4 mb-3">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <form action="" method="post" id="saveFiscalYear">
                            @csrf
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control year" id="floatingYear" name="year"
                                    placeholder="" required autofocus>
                                <label for="floatingYear">Tahun Anggaran<span class="text-danger">*</span></label>
                            </div>
                            <div class="form-floating mb-3">
                                <select class="form-select" id="floatingStatus" aria-label="Status select"
                                    name="is_active" required>
                                    <option value="">Silahkan pilih</option>
                                    <option value="0">Tidak Aktif</option>
                                    <option value="1">Aktif</option>
                                </select>
                                <label for="floatingStatus">Status<span class="text-danger">*</span></label>
                            </div>
                            <button class="btn btn-lg app-btn-primary text-uppercase float-end" type="submit"
                                id="btnSave">
                                <i class="fas fa-save me-2"></i>
                                Simpan Data
                            </button>
                        </form>
                    </div><!--//app-card-body-->
                </div><!--//app-card-->
            </div><!--//col-->

            <div class="col-12 col-lg-8">
                <div class="app-card shadow-sm">
                    <div class="app-card-body p-2">

                        <div class="table-responsive">
                            {!! $dataTable->table() !!}
                        </div>

                    </div>
                </div>
            </div>
        </div>

    </div><!--//container-fluid-->

    <!--Delete Modal -->
    @include('utils.ajaxDelete')
@endsection

@push('scriptjs')
    <script>
        $('table').on('draw.dt', function() {
            $('[data-bs-toggle="tooltip"]').tooltip();
        })

        // $('#btnCancel').on('click', function() {
        //     $('[name="name"]').val('');
        //     $('[name="name"]').focus();
        //     $("#saveCategory").attr('action', '');
        //     $("#btnSave").html('<i class="fas fa-save"></i> SIMPAN');
        // });

        function editData(id) {
            $('[name="name"]').focus();
            var link = "{{ route('fiscal_year.edit', ':id') }}";
            link = link.replace(':id', id);

            $.ajax({
                url: link,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: "GET",
                data: {
                    id: id
                },

                success: function(response) {
                    window.scrollTo(0, 0);
                    $("#btnSave").html('<i class="fas fa-save"></i> UPDATE DATA');
                    $("#saveFiscalYear").attr('action', response.link);
                    $('[name="year"]').val(response.year);
                    $('[name="is_active"]').val(response.is_active);
                },
                error: function(response) {
                    toastr.error('Terjadi kesalahan', 'ERROR');
                },
            });
        }

        $('#saveFiscalYear').on('submit', function(e) {
            e.preventDefault();
            $("#overlay").fadeIn(300);
            var link = $("#saveFiscalYear").attr('action');
            let type = "PUT";
            if (link == "") {
                link = "{{ route('fiscal_year.store') }}";
                type = "POST";
            }
            let year = $('[name="year"]').val();
            let is_active = $('[name="is_active"]').val();

            $.ajax({
                url: link,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: type,
                dataType: 'json',
                data: {
                    year: year,
                    is_active: is_active
                },
                success: function(response) {
                    $("#overlay").fadeOut(300);
                    $("#saveFiscalYear").attr('action', '');
                    $("#btnSave").html('<i class="fas fa-save"></i> SIMPAN DATA').removeAttr('disabled');
                    //delete field
                    $('[name="year"]').val('');
                    $('[name="is_active"]').val('Silahkan pilih');
                    // window.scrollTo(0, document.body.scrollHeight);
                    $('#fiscalyear-table').DataTable().ajax.reload(null, false);
                    $('[name="name"]').focus();
                    toastr.success(response.message, 'SUCCESS');
                    // Swal.fire({
                    //     icon: 'success',
                    //     title: 'Berhasil',
                    //     text: response.message,
                    // });
                },
                error: function(response) {
                    $("#overlay").fadeOut(300);
                    $("#btnSave").html('<i class="fas fa-save"></i> SIMPAN DATA').removeAttr('disabled');
                    toastr.error('Proses menyimpan error: ' + response.responseText, 'ERROR');
                },
            });
        });

        $(document).on('click', '#deleteFiscalYear', function(e) {
            e.preventDefault();
            let allData = new FormData(this);
            var linkDel = $(this).attr('action');
            $('#deleteModal').modal('show');
            $("#btnYes").click(function() {
                $.ajax({
                    url: linkDel,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: "POST",
                    data: allData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        linkDel = '';
                        $('#deleteModal').modal('hide');
                        $('#fiscalyear-table').DataTable().ajax.reload(null, false);
                        $('[name="year"]').focus();
                        if (response) {
                            toastr.success('Berhasil hapus', 'SUCCESS');
                        }
                        if (!response) {
                            toastr.warning('Tidak Bisa Dihapus', 'WARNING');
                        }
                    },

                });
            });
            $(".btnBatal").click(function() {
                linkDel = '';
                $('#deleteModal').modal('hide');
            });
        });
    </script>

    {!! $dataTable->scripts() !!}
@endpush
