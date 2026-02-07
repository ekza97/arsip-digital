<!--Delete Modal -->
<div class="modal fade text-left" id="deleteModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger">
                <h5 class="modal-title text-white mt-n1" id="deleteModalLabel">Konfirmasi Hapus</h5>
                <!--begin::Close-->
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                <!--end::Close-->
            </div>
            <div class="modal-body">
                Anda yakin ingin menghapus data ini ?
            </div>
            <div class="modal-footer p-2">
                <button type="button" class="btn btn-secondary btnBatal">
                    <i class="fas fa-times"></i>
                    <span>Batal</span>
                </button>
                <button type="submit" class="btn btn-danger ml-1 text-white" id="btnYes">
                    <i class="fas fa-trash"></i>
                    <span>Iya Hapus</span>
                </button>
            </div>
        </div>
    </div>
</div>
