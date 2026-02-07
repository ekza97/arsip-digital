@extends('layouts.app')

@section('content')
    <div class="container-xl">

        <h1 class="app-page-title pb-2"><i class="fas fa-hashtag me-2"></i>Dokumen</h1>

        <div class="row">
            <div class="col-12 col-lg-12">
                <div class="app-card shadow-sm">
                    <div class="app-card-body p-3">
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <div class="form-floating mb-3">
                                    <select class="form-select" id="filter_tahun_anggaran" name="fiscal_year_id">
                                        <option value="" selected>Silahkan pilih</option>
                                        @foreach ($fiscalYears as $fiscalYear)
                                            <option value="{{ $fiscalYear->id }}">{{ $fiscalYear->year }}</option>
                                        @endforeach
                                    </select>
                                    <label for="filter_tahun_anggaran">Tahun Anggaran</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-floating mb-3">
                                    <select class="form-select" id="filter_category_document" name="category_id">
                                        <option value="" selected>Silahkan pilih</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                    <label for="filter_category_document">Kategori Dokumen</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-floating mb-3">
                                    <input type="date" class="form-control flatpicker" id="filter_document_date"
                                        name="document_date" placeholder="Pilih tanggal dokumen">
                                    <label for="filter_document_date">Tanggal Dokumen</label>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover table-striped table-bordered" id="document-table">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Tahun</th>
                                        <th>Kategori</th>
                                        <th>Judul</th>
                                        <th>Nomor</th>
                                        <th>Tanggal</th>
                                        <th>Ukuran</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                            </table>
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
        $(document).ready(function() {
            $('#document-table').DataTable({
                ordering: true,
                serverSide: true,
                processing: true,
                ajax: {
                    url: '{{ route('document.index') }}',
                    data: function(q) {
                        q.fiscal_year = $('#filter_tahun_anggaran').val();
                        q.category = $('#filter_category_document').val();
                        q.document_date = $('#filter_document_date').val();
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        width: '10px',
                        orderable: false,
                        searchable: false,
                        className: 'text-center',
                    },
                    {
                        data: 'fiscal_year.year',
                        name: 'fiscal_year.year',
                        width: '80px',
                        className: 'text-center',
                    },
                    {
                        data: 'category.name',
                        name: 'category.name'
                    },
                    {
                        data: 'title',
                        name: 'title'
                    },
                    {
                        data: 'document_number',
                        name: 'document_number'
                    },
                    {
                        data: 'document_date',
                        name: 'document_date',
                        width: '100px',
                        className: 'text-center',
                    },
                    {
                        data: 'file_size',
                        name: 'file_size',
                        width: '100px',
                        className: 'text-center',
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        width: '150px',
                        className: 'text-center',
                    },
                ],
            });

            $('#filter_tahun_anggaran').change(function() {
                reloadTable('#document-table');
            });

            $('#filter_category_document').change(function() {
                reloadTable('#document-table');
            });

            $('#filter_document_date').change(function() {
                reloadTable('#document-table');
            });

        });

        function reloadTable(id) {
            var table = $(id).DataTable();
            table.cleaData;
            table.ajax.reload();
        }


        $('table').on('draw.dt', function() {
            $('[data-bs-toggle="tooltip"]').tooltip();
        });

        $(document).on('click', '#deleteDocument', function(e) {
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
                        console.log(response);
                        linkDel = '';
                        $('#deleteModal').modal('hide');
                        reloadTable('#document-table');
                        if (response.status) {
                            toastr.success(response.message, 'SUCCESS');
                        } else {
                            toastr.warning(response.message, 'WARNING');
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
@endpush
