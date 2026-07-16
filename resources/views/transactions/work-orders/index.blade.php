@extends('layouts.user_type.auth')

@section('title', 'Work Order')

@section('content')
    <div class="row">
        <div class="col-12">
            <div id="alert-container"></div>

            <div class="card mb-4">
                <div class="card-header pb-0">
                    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between">
                        <div>
                            <h5 class="mb-1">Work Order</h5>

                            <p class="text-sm text-secondary mb-0">
                                Kelola Work Order dari Form Service Advisor.
                            </p>
                        </div>

                        <button type="button" class="btn bg-gradient-primary mt-3 mt-md-0 mb-0" id="btn-add-wo">
                            <i class="fas fa-plus me-1"></i>
                            Buat Work Order
                        </button>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-search"></i>
                                </span>

                                <input type="text" class="form-control" id="search-wo"
                                    placeholder="Cari nomor WO, SA, customer, kendaraan, atau mekanik...">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <select class="form-select" id="filter-wo-status">
                                <option value="">Semua Status</option>
                                <option value="DRAFT">DRAFT</option>
                                <option value="MENUNGGU">MENUNGGU</option>
                                <option value="DIKERJAKAN">DIKERJAKAN</option>
                                <option value="SELESAI">SELESAI</option>
                                <option value="BATAL">BATAL</option>
                            </select>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table align-items-center mb-0" id="wo-table">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        No.
                                    </th>

                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        ID Work Order
                                    </th>

                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Form SA
                                    </th>

                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Customer
                                    </th>

                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Kendaraan
                                    </th>

                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Mekanik
                                    </th>

                                    <th
                                        class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">
                                        Jasa
                                    </th>

                                    <th
                                        class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">
                                        Part
                                    </th>

                                    <th
                                        class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-end">
                                        Total
                                    </th>

                                    <th
                                        class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">
                                        Status
                                    </th>

                                    <th
                                        class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">
                                        Aksi
                                    </th>
                                </tr>
                            </thead>

                            <tbody id="wo-table-body">
                                <tr>
                                    <td colspan="11" class="text-center py-4">
                                        Memuat data...
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div id="wo-empty" class="text-center text-secondary py-4 d-none">
                        Work Order tidak ditemukan.
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Work Order --}}
    <div class="modal fade" id="wo-modal" tabindex="-1" aria-labelledby="wo-modal-label" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <form id="wo-form">
                    <div class="modal-header">
                        <h5 class="modal-title" id="wo-modal-label">
                            Buat Work Order
                        </h5>

                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>

                    <div class="modal-body">
                        <div id="wo-form-alert"></div>

                        <input type="hidden" id="wo-form-mode" value="create">

                        <input type="hidden" id="id_wo">

                        <div class="mb-3 d-none" id="id-wo-container">
                            <label class="form-label">
                                ID Work Order
                            </label>

                            <input type="text" class="form-control" id="id_wo_display" readonly>
                        </div>

                        <div class="mb-3" id="id-sa-select-container">
                            <label for="id_sa" class="form-label">
                                Form Service Advisor
                            </label>

                            <select class="form-select" id="id_sa">
                                <option value="">
                                    Memuat Form SA...
                                </option>
                            </select>

                            <div class="invalid-feedback" id="error-id_sa"></div>
                        </div>

                        <div class="mb-3 d-none" id="id-sa-display-container">
                            <label class="form-label">
                                Form Service Advisor
                            </label>

                            <input type="text" class="form-control" id="id_sa_display" readonly>
                        </div>

                        <div class="alert alert-light border text-dark" id="sa-information">
                            Pilih Form Service Advisor untuk melihat customer
                            dan kendaraan.
                        </div>

                        <div class="mb-3">
                            <label for="id_mekanik" class="form-label">
                                Mekanik
                            </label>

                            <select class="form-select" id="id_mekanik">
                                <option value="">
                                    Memuat mekanik...
                                </option>
                            </select>

                            <div class="invalid-feedback" id="error-id_mekanik"></div>

                            <small class="text-secondary">
                                Mekanik boleh belum dipilih saat Work Order masih DRAFT.
                            </small>
                        </div>

                        <div class="mb-3 d-none" id="diskon-container">
                            <label for="diskon" class="form-label">
                                Diskon
                            </label>

                            <div class="input-group">
                                <span class="input-group-text">
                                    Rp
                                </span>

                                <input type="text" class="form-control text-end" id="diskon" inputmode="numeric"
                                    value="0">
                            </div>

                            <div class="invalid-feedback" id="error-diskon"></div>
                        </div>

                        <div class="mb-3">
                            <label for="catatan_mekanik" class="form-label">
                                Catatan Mekanik
                            </label>

                            <textarea class="form-control" id="catatan_mekanik" rows="4" maxlength="2000"
                                placeholder="Catatan awal pekerjaan, kondisi kendaraan, atau instruksi..."></textarea>

                            <div class="invalid-feedback" id="error-catatan_mekanik"></div>
                        </div>

                        <div class="row d-none" id="wo-total-container">
                            <div class="col-md-4">
                                <div class="border rounded p-3">
                                    <small class="text-secondary">
                                        Total Jasa
                                    </small>

                                    <div class="font-weight-bold" id="total-jasa-display">
                                        Rp0
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="border rounded p-3">
                                    <small class="text-secondary">
                                        Total Part
                                    </small>

                                    <div class="font-weight-bold" id="total-part-display">
                                        Rp0
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="border rounded p-3">
                                    <small class="text-secondary">
                                        Grand Total
                                    </small>

                                    <div class="font-weight-bold" id="grand-total-display">
                                        Rp0
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-light mb-0" data-bs-dismiss="modal">
                            Batal
                        </button>

                        <button type="submit" class="btn bg-gradient-primary mb-0" id="btn-save-wo">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Modal Kelola Jasa Work Order --}}
    <div class="modal fade" id="wo-job-modal" tabindex="-1" aria-labelledby="wo-job-modal-label" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <div>
                        <h5 class="modal-title mb-1" id="wo-job-modal-label">
                            Kelola Jasa Work Order
                        </h5>

                        <div class="text-sm text-secondary">
                            <span id="wo-job-work-order-id">-</span>
                            <span class="mx-1">•</span>
                            <span class="badge bg-gradient-secondary" id="wo-job-status">
                                -
                            </span>
                        </div>
                    </div>

                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>

                <div class="modal-body">
                    <div id="wo-job-alert"></div>

                    <div class="card border shadow-none mb-4" id="wo-job-form-container">
                        <div class="card-body">
                            <h6 class="mb-3" id="wo-job-form-title">
                                Tambah Jasa
                            </h6>

                            <form id="wo-job-form">
                                <div class="row align-items-end">
                                    <div class="col-md-7">
                                        <div class="mb-3 mb-md-0">
                                            <label for="wo-job-id-job" class="form-label">
                                                Master Jasa
                                            </label>

                                            <select class="form-select" id="wo-job-id-job">
                                                <option value="">
                                                    Memuat jasa...
                                                </option>
                                            </select>

                                            <div class="invalid-feedback" id="wo-job-error-id_job"></div>
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <div class="mb-3 mb-md-0">
                                            <label for="wo-job-qty" class="form-label">
                                                Qty
                                            </label>

                                            <input type="number" class="form-control" id="wo-job-qty" min="1"
                                                max="999" step="1" value="1">

                                            <div class="invalid-feedback" id="wo-job-error-qty"></div>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <button type="submit" class="btn bg-gradient-primary mb-0" id="btn-save-wo-job">
                                            Tambah
                                        </button>

                                        <button type="button" class="btn btn-light mb-0 d-none"
                                            id="btn-cancel-edit-wo-job">
                                            Batal Edit
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table align-items-center mb-0" id="wo-job-table">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        No.
                                    </th>

                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        ID Jasa
                                    </th>

                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Keterangan
                                    </th>

                                    <th
                                        class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">
                                        Qty
                                    </th>

                                    <th
                                        class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-end">
                                        Harga
                                    </th>

                                    <th
                                        class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-end">
                                        Subtotal
                                    </th>

                                    <th
                                        class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">
                                        Aksi
                                    </th>
                                </tr>
                            </thead>

                            <tbody id="wo-job-table-body">
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        Belum ada jasa.
                                    </td>
                                </tr>
                            </tbody>

                            <tfoot>
                                <tr>
                                    <th colspan="5" class="text-end">
                                        Total Jasa
                                    </th>

                                    <th class="text-end" id="wo-job-total">
                                        Rp0
                                    </th>

                                    <th></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <div id="wo-job-empty" class="text-center text-secondary py-4 d-none">
                        Belum ada jasa pada Work Order ini.
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light mb-0" data-bs-dismiss="modal">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        #wo-table th,
        #wo-table td {
            vertical-align: middle;
        }

        #wo-table td:nth-child(4),
        #wo-table td:nth-child(5),
        #wo-table td:nth-child(6) {
            min-width: 160px;
        }

        #wo-table td:last-child {
            white-space: nowrap;
        }

        #wo-table .btn {
            font-size: 11px;
        }

        #wo-job-table th,
        #wo-job-table td {
            vertical-align: middle;
        }

        #wo-job-table td:nth-child(3) {
            min-width: 260px;
            white-space: normal;
        }

        #wo-job-table td:last-child {
            white-space: nowrap;
        }

        #wo-job-table .btn {
            font-size: 11px;
        }
    </style>
@endpush

@push('scripts')
    <script>
        $(function() {
            const baseUrl =
                @json(url('/transactions/work-orders'));

            const detailUrl = `${baseUrl}/detail`;

            const workOrderJobBaseUrl =
                @json(url('/transactions/work-order-jobs'));

            const serviceJobDataUrl =
                @json(url('/master/jobs/data'));

            const woJobModalElement =
                document.getElementById('wo-job-modal');

            const woJobModal =
                bootstrap.Modal.getOrCreateInstance(
                    woJobModalElement
                );

            let serviceJobs = [];
            let activeWorkOrderId = null;
            let activeWorkOrderStatus = null;
            let activeWorkOrderJobs = [];
            let editingWorkOrderJobId = null;

            const serviceAdvisorDataUrl =
                @json(url('/transactions/service-advisors/data'));

            const mechanicDataUrl =
                @json(url('/master/mechanics/data'));

            const preselectedServiceAdvisorId =
                @json(request('id_sa'));

            const woModalElement =
                document.getElementById('wo-modal');

            const woModal =
                bootstrap.Modal.getOrCreateInstance(
                    woModalElement
                );

            let workOrders = [];
            let openServiceAdvisors = [];
            let mechanics = [];

            $.when(
                    loadServiceAdvisorOptions(),
                    loadMechanicOptions()
                )
                .always(function() {
                    if (preselectedServiceAdvisorId) {
                        openCreateModal(
                            preselectedServiceAdvisorId
                        );
                    }
                });

            loadWorkOrders();
            loadServiceJobs();

            $('#btn-add-wo').on('click', function() {
                openCreateModal();
            });

            $('#search-wo, #filter-wo-status').on(
                'input change',
                function() {
                    renderWorkOrders();
                }
            );

            $('#id_sa').on('change', function() {
                renderServiceAdvisorInformation(
                    $(this).val()
                );
            });

            $('#diskon').on('input', function() {
                const numericValue = $(this)
                    .val()
                    .replace(/\D/g, '');

                $(this).val(
                    formatNumber(numericValue)
                );
            });

            $('#wo-form').on('submit', function(event) {
                event.preventDefault();

                clearValidationErrors();

                const isEdit =
                    $('#wo-form-mode').val() === 'edit';

                const payload = isEdit ? {
                    id_wo: $('#id_wo').val(),

                    id_mekanik: $('#id_mekanik').val(),

                    diskon: $('#diskon')
                        .val()
                        .replace(/\D/g, ''),

                    catatan_mekanik: $('#catatan_mekanik')
                        .val()
                        .trim(),
                } : {
                    id_sa: $('#id_sa').val(),

                    id_mekanik: $('#id_mekanik').val(),

                    catatan_mekanik: $('#catatan_mekanik')
                        .val()
                        .trim(),
                };

                setSubmitLoading(true);

                $.ajax({
                        url: baseUrl,
                        type: isEdit ? 'PUT' : 'POST',
                        data: payload,
                    })
                    .done(function(response) {
                        woModal.hide();

                        showAlert(
                            'success',
                            response.message
                        );

                        loadWorkOrders();
                        loadServiceAdvisorOptions();
                    })
                    .fail(function(xhr) {
                        if (
                            xhr.status === 422 &&
                            xhr.responseJSON?.errors
                        ) {
                            showValidationErrors(
                                xhr.responseJSON.errors
                            );

                            return;
                        }

                        showFormAlert(
                            xhr.responseJSON?.message ??
                            'Work Order gagal disimpan.'
                        );
                    })
                    .always(function() {
                        setSubmitLoading(false);
                    });
            });

            $(document).on(
                'click',
                '.btn-edit-wo',
                function() {
                    loadWorkOrderDetail(
                        $(this).data('id'),
                        'edit'
                    );
                }
            );

            $(document).on(
                'click',
                '.btn-detail-wo',
                function() {
                    loadWorkOrderDetail(
                        $(this).data('id'),
                        'view'
                    );
                }
            );

            $(document).on(
                'click',
                '.btn-manage-jobs',
                function() {
                    loadWorkOrderJobs(
                        $(this).data('id'),
                        true
                    );
                }
            );

            $('#wo-job-form').on(
                'submit',
                function(event) {
                    event.preventDefault();

                    clearWorkOrderJobValidation();

                    const isEdit =
                        editingWorkOrderJobId !== null;

                    const payload = isEdit ? {
                        qty: $('#wo-job-qty').val(),
                    } : {
                        id_wo: activeWorkOrderId,
                        id_job: $('#wo-job-id-job').val(),
                        qty: $('#wo-job-qty').val(),
                    };

                    setWorkOrderJobSubmitLoading(true);

                    $.ajax({
                            url: isEdit ?
                                `${workOrderJobBaseUrl}/${editingWorkOrderJobId}` : workOrderJobBaseUrl,

                            type: isEdit ? 'PUT' : 'POST',

                            data: payload,
                        })
                        .done(function(response) {
                            showWorkOrderJobAlert(
                                'success',
                                response.message
                            );

                            resetWorkOrderJobForm();

                            loadWorkOrderJobs(
                                activeWorkOrderId,
                                false
                            );

                            loadWorkOrders();
                        })
                        .fail(function(xhr) {
                            if (
                                xhr.status === 422 &&
                                xhr.responseJSON?.errors
                            ) {
                                showWorkOrderJobValidation(
                                    xhr.responseJSON.errors
                                );

                                return;
                            }

                            showWorkOrderJobAlert(
                                'danger',
                                xhr.responseJSON?.message ??
                                'Jasa Work Order gagal disimpan.'
                            );
                        })
                        .always(function() {
                            setWorkOrderJobSubmitLoading(false);
                        });
                }
            );

            $(document).on(
                'click',
                '.btn-edit-wo-job',
                function() {
                    const workOrderJobId =
                        Number($(this).data('id'));

                    const workOrderJob =
                        activeWorkOrderJobs.find(
                            function(item) {
                                return Number(item.id) ===
                                    workOrderJobId;
                            }
                        );

                    if (!workOrderJob) {
                        return;
                    }

                    editingWorkOrderJobId =
                        workOrderJob.id;

                    $('#wo-job-form-title')
                        .text('Edit Jumlah Jasa');

                    renderServiceJobOptions(
                        workOrderJob.id_job
                    );

                    $('#wo-job-id-job')
                        .val(workOrderJob.id_job)
                        .prop('disabled', true);

                    $('#wo-job-qty')
                        .val(workOrderJob.qty);

                    $('#btn-save-wo-job')
                        .text('Simpan Perubahan');

                    $('#btn-cancel-edit-wo-job')
                        .removeClass('d-none');

                    clearWorkOrderJobValidation();
                }
            );

            $('#btn-cancel-edit-wo-job').on(
                'click',
                function() {
                    resetWorkOrderJobForm();
                }
            );

            $(document).on(
                'click',
                '.btn-delete-wo-job',
                function() {
                    const workOrderJobId =
                        $(this).data('id');

                    const jobDescription =
                        $(this).data('name');

                    if (
                        !confirm(
                            `Yakin ingin menghapus jasa "${jobDescription}"?`
                        )
                    ) {
                        return;
                    }

                    $.ajax({
                            url: `${workOrderJobBaseUrl}/` +
                                workOrderJobId,

                            type: 'DELETE',
                        })
                        .done(function(response) {
                            showWorkOrderJobAlert(
                                'success',
                                response.message
                            );

                            resetWorkOrderJobForm();

                            loadWorkOrderJobs(
                                activeWorkOrderId,
                                false
                            );

                            loadWorkOrders();
                        })
                        .fail(function(xhr) {
                            const message =
                                firstValidationMessage(xhr) ??
                                xhr.responseJSON?.message ??
                                'Jasa Work Order gagal dihapus.';

                            showWorkOrderJobAlert(
                                'danger',
                                message
                            );
                        });
                }
            );

            function loadServiceJobs() {
                $('#wo-job-id-job')
                    .prop('disabled', true)
                    .html(`
            <option value="">
                Memuat master jasa...
            </option>
        `);

                return $.ajax({
                        url: serviceJobDataUrl,
                        type: 'GET',
                    })
                    .done(function(response) {
                        const rows = Array.isArray(response.data) ?
                            response.data :
                            (
                                Array.isArray(response.data?.data) ?
                                response.data.data : []
                            );

                        serviceJobs = rows.filter(
                            function(serviceJob) {
                                const activeValue =
                                    serviceJob.is_active ??
                                    serviceJob.status_aktif ??
                                    false;

                                return (
                                    activeValue === true ||
                                    activeValue === 1 ||
                                    activeValue === '1' ||
                                    activeValue === 'true'
                                );
                            }
                        );

                        renderServiceJobOptions();
                    })
                    .fail(function(xhr) {
                        serviceJobs = [];

                        $('#wo-job-id-job')
                            .prop('disabled', true)
                            .html(`
                <option value="">
                    Master jasa gagal dimuat
                </option>
            `);

                        console.error(
                            'Gagal memuat Master Jasa:',
                            xhr.responseJSON ?? xhr.responseText
                        );
                    });
            }


            function loadWorkOrderJobs(
                workOrderId,
                showModal = true
            ) {
                $('#wo-job-table-body').html(`
        <tr>
            <td colspan="7" class="text-center py-4">
                Memuat jasa...
            </td>
        </tr>
    `);

                $('#wo-job-empty').addClass('d-none');

                return $.ajax({
                        url: detailUrl,
                        type: 'GET',
                        data: {
                            id_wo: workOrderId,
                        },
                    })
                    .done(function(response) {
                        const workOrder = response.data;

                        activeWorkOrderId =
                            workOrder.id_wo;

                        activeWorkOrderStatus =
                            workOrder.status;

                        activeWorkOrderJobs =
                            workOrder.jobs ?? [];

                        $('#wo-job-work-order-id')
                            .text(activeWorkOrderId);

                        $('#wo-job-status')
                            .attr(
                                'class',
                                `badge ${
                    workOrderStatusClass(
                        activeWorkOrderStatus
                    )
                }`
                            )
                            .text(activeWorkOrderStatus);

                        const editable = [
                            'DRAFT',
                            'MENUNGGU',
                        ].includes(activeWorkOrderStatus);

                        $('#wo-job-form-container')
                            .toggleClass(
                                'd-none',
                                !editable
                            );

                        resetWorkOrderJobForm();
                        renderWorkOrderJobs();

                        $('#wo-job-total').text(
                            formatRupiah(
                                workOrder.total_jasa
                            )
                        );

                        if (showModal) {
                            woJobModal.show();
                        }
                    })
                    .fail(function(xhr) {
                        showAlert(
                            'danger',
                            xhr.responseJSON?.message ??
                            'Data jasa Work Order gagal dimuat.'
                        );
                    });
            }

            function renderWorkOrderJobs() {
                const editable = [
                    'DRAFT',
                    'MENUNGGU',
                ].includes(activeWorkOrderStatus);

                if (
                    activeWorkOrderJobs.length === 0
                ) {
                    $('#wo-job-table-body').empty();

                    $('#wo-job-empty')
                        .removeClass('d-none');

                    return;
                }

                $('#wo-job-empty').addClass('d-none');

                const rows = activeWorkOrderJobs.map(
                    function(workOrderJob, index) {
                        const actions = editable ?
                            `
                    <button
                        type="button"
                        class="btn btn-sm btn-outline-primary px-3 mb-0 me-1 btn-edit-wo-job"
                        data-id="${workOrderJob.id}"
                    >
                        Edit
                    </button>

                    <button
                        type="button"
                        class="btn btn-sm btn-outline-danger px-3 mb-0 btn-delete-wo-job"
                        data-id="${workOrderJob.id}"
                        data-name="${escapeHtml(
                            workOrderJob
                                .keterangan_job
                        )}"
                    >
                        Hapus
                    </button>
                  ` :
                            `
                    <span class="text-xs text-secondary">
                        Tidak dapat diubah
                    </span>
                  `;

                        return `
                <tr>
                    <td class="ps-4">
                        <span class="text-sm">
                            ${index + 1}
                        </span>
                    </td>

                    <td>
                        <span class="text-sm font-weight-bold">
                            ${escapeHtml(
                                workOrderJob.id_job
                            )}
                        </span>
                    </td>

                    <td>
                        <span class="text-sm">
                            ${escapeHtml(
                                workOrderJob
                                    .keterangan_job
                            )}
                        </span>
                    </td>

                    <td class="text-center">
                        <span class="badge bg-gradient-info">
                            ${workOrderJob.qty}
                        </span>
                    </td>

                    <td class="text-end">
                        <span class="text-sm">
                            ${formatRupiah(
                                workOrderJob
                                    .harga_satuan
                            )}
                        </span>
                    </td>

                    <td class="text-end">
                        <span class="text-sm font-weight-bold">
                            ${formatRupiah(
                                workOrderJob.subtotal
                            )}
                        </span>
                    </td>

                    <td class="text-center">
                        ${actions}
                    </td>
                </tr>
            `;
                    }
                ).join('');

                $('#wo-job-table-body').html(rows);
            }

            function renderServiceJobOptions(
                selectedJobId = null
            ) {
                const select =
                    $('#wo-job-id-job');

                if (serviceJobs.length === 0) {
                    select
                        .prop('disabled', true)
                        .html(`
                <option value="">
                    Tidak ada Master Jasa aktif
                </option>
            `);

                    return;
                }

                const usedJobIds =
                    activeWorkOrderJobs.map(
                        function(workOrderJob) {
                            return String(
                                workOrderJob.id_job
                            );
                        }
                    );

                const availableJobs =
                    serviceJobs.filter(
                        function(serviceJob) {
                            const serviceJobId =
                                String(serviceJob.id_job);

                            return (
                                serviceJobId ===
                                String(selectedJobId ?? '') ||
                                !usedJobIds.includes(
                                    serviceJobId
                                )
                            );
                        }
                    );

                if (availableJobs.length === 0) {
                    select
                        .prop('disabled', true)
                        .html(`
                <option value="">
                    Semua jasa aktif sudah ditambahkan
                </option>
            `);

                    return;
                }

                const options =
                    availableJobs.map(
                        function(serviceJob) {
                            return `
                    <option
                        value="${escapeHtml(
                            serviceJob.id_job
                        )}"
                    >
                        ${escapeHtml(
                            serviceJob.keterangan
                        )}
                        — ${formatRupiah(
                            serviceJob.harga
                        )}
                    </option>
                `;
                        }
                    ).join('');

                select
                    .prop(
                        'disabled',
                        editingWorkOrderJobId !== null
                    )
                    .html(`
            <option value="">
                Pilih jasa
            </option>
            ${options}
        `);

                if (selectedJobId) {
                    select.val(
                        String(selectedJobId)
                    );
                }
            }

            function resetWorkOrderJobForm() {
                editingWorkOrderJobId = null;

                $('#wo-job-form')[0].reset();

                $('#wo-job-form-title')
                    .text('Tambah Jasa');

                $('#wo-job-qty').val(1);

                $('#wo-job-id-job')
                    .prop('disabled', false);

                $('#btn-save-wo-job')
                    .text('Tambah');

                $('#btn-cancel-edit-wo-job')
                    .addClass('d-none');

                clearWorkOrderJobValidation();
                renderServiceJobOptions();
            }

            function showWorkOrderJobValidation(
                errors
            ) {
                Object.keys(errors).forEach(
                    function(field) {
                        if (field === 'id_wo') {
                            showWorkOrderJobAlert(
                                'danger',
                                errors[field][0]
                            );

                            return;
                        }

                        const input =
                            field === 'id_job' ?
                            $('#wo-job-id-job') :
                            $(`#wo-job-${field}`);

                        const errorContainer =
                            $(`#wo-job-error-${field}`);

                        input.addClass('is-invalid');

                        errorContainer.text(
                            errors[field][0]
                        );
                    }
                );
            }

            function clearWorkOrderJobValidation() {
                $('#wo-job-form .is-invalid')
                    .removeClass('is-invalid');

                $('#wo-job-form .invalid-feedback')
                    .text('');
            }

            function setWorkOrderJobSubmitLoading(
                isLoading
            ) {
                const button =
                    $('#btn-save-wo-job');

                button.prop('disabled', isLoading);

                if (isLoading) {
                    button.text('Menyimpan...');
                    return;
                }

                button.text(
                    editingWorkOrderJobId ?
                    'Simpan Perubahan' :
                    'Tambah'
                );
            }

            function showWorkOrderJobAlert(
                type,
                message
            ) {
                $('#wo-job-alert').html(`
        <div
            class="alert alert-${type} alert-dismissible text-white fade show"
            role="alert"
        >
            ${escapeHtml(message)}

            <button
                type="button"
                class="btn-close btn-close-white"
                data-bs-dismiss="alert"
                aria-label="Tutup"
            ></button>
        </div>
    `);
            }

            function workOrderStatusClass(status) {
                const classes = {
                    DRAFT: 'bg-gradient-secondary',
                    MENUNGGU: 'bg-gradient-warning',
                    DIKERJAKAN: 'bg-gradient-info',
                    SELESAI: 'bg-gradient-success',
                    BATAL: 'bg-gradient-danger',
                };

                return classes[status] ??
                    'bg-gradient-dark';
            }

            function openCreateModal(
                selectedServiceAdvisorId = null
            ) {
                resetForm();

                $('#wo-form-mode').val('create');
                $('#wo-modal-label').text(
                    'Buat Work Order'
                );

                $('#id-wo-container')
                    .addClass('d-none');

                $('#id-sa-display-container')
                    .addClass('d-none');

                $('#id-sa-select-container')
                    .removeClass('d-none');

                $('#diskon-container')
                    .addClass('d-none');

                $('#wo-total-container')
                    .addClass('d-none');

                $('#btn-save-wo')
                    .removeClass('d-none');

                setFormDisabled(false);

                renderServiceAdvisorSelect();

                if (selectedServiceAdvisorId) {
                    const exists = openServiceAdvisors.some(
                        function(serviceAdvisor) {
                            return (
                                serviceAdvisor.id_sa ===
                                selectedServiceAdvisorId
                            );
                        }
                    );

                    if (exists) {
                        $('#id_sa').val(
                            selectedServiceAdvisorId
                        );

                        renderServiceAdvisorInformation(
                            selectedServiceAdvisorId
                        );
                    } else {
                        showAlert(
                            'warning',
                            'Form Service Advisor tidak tersedia atau sudah memiliki Work Order.'
                        );

                        return;
                    }
                }

                woModal.show();
            }

            function loadWorkOrderDetail(
                workOrderId,
                mode
            ) {
                clearValidationErrors();

                $.ajax({
                        url: detailUrl,
                        type: 'GET',
                        data: {
                            id_wo: workOrderId,
                        },
                    })
                    .done(function(response) {
                        const workOrder = response.data;
                        const serviceAdvisor =
                            workOrder.service_advisor_form;

                        resetForm();

                        $('#wo-form-mode').val(mode);

                        $('#wo-modal-label').text(
                            mode === 'edit' ?
                            'Edit Work Order' :
                            'Detail Work Order'
                        );

                        $('#id_wo').val(
                            workOrder.id_wo
                        );

                        $('#id_wo_display').val(
                            workOrder.id_wo
                        );

                        $('#id-wo-container')
                            .removeClass('d-none');

                        $('#id-sa-select-container')
                            .addClass('d-none');

                        $('#id-sa-display-container')
                            .removeClass('d-none');

                        $('#id_sa_display').val(
                            workOrder.id_sa
                        );

                        $('#id_mekanik').val(
                            workOrder.id_mekanik ?? ''
                        );

                        $('#diskon').val(
                            formatNumber(
                                workOrder.diskon
                            )
                        );

                        $('#catatan_mekanik').val(
                            workOrder.catatan_mekanik ?? ''
                        );

                        $('#diskon-container')
                            .removeClass('d-none');

                        $('#wo-total-container')
                            .removeClass('d-none');

                        $('#total-jasa-display').text(
                            formatRupiah(
                                workOrder.total_jasa
                            )
                        );

                        $('#total-part-display').text(
                            formatRupiah(
                                workOrder.total_part
                            )
                        );

                        $('#grand-total-display').text(
                            formatRupiah(
                                workOrder.grand_total
                            )
                        );

                        renderServiceAdvisorDetail(
                            serviceAdvisor
                        );

                        const isView = mode === 'view';

                        setFormDisabled(isView);

                        $('#btn-save-wo').toggleClass(
                            'd-none',
                            isView
                        );

                        woModal.show();
                    })
                    .fail(function(xhr) {
                        showAlert(
                            'danger',
                            xhr.responseJSON?.message ??
                            'Detail Work Order gagal diambil.'
                        );
                    });
            }

            function loadServiceAdvisorOptions() {
                return $.ajax({
                        url: serviceAdvisorDataUrl,
                        type: 'GET',
                    })
                    .done(function(response) {
                        const serviceAdvisors =
                            response.data ?? [];

                        openServiceAdvisors =
                            serviceAdvisors.filter(
                                function(serviceAdvisor) {
                                    return (
                                        serviceAdvisor.status ===
                                        'OPEN' &&
                                        !serviceAdvisor.work_order
                                    );
                                }
                            );

                        renderServiceAdvisorSelect();
                    })
                    .fail(function() {
                        openServiceAdvisors = [];

                        showAlert(
                            'danger',
                            'Data Form Service Advisor gagal dimuat.'
                        );
                    });
            }

            function renderServiceAdvisorSelect() {
                const select = $('#id_sa');

                if (openServiceAdvisors.length === 0) {
                    select.html(`
                    <option value="">
                        Tidak ada Form SA berstatus OPEN
                    </option>
                `);

                    return;
                }

                const options = openServiceAdvisors.map(
                    function(serviceAdvisor) {
                        const customer =
                            serviceAdvisor.customer;

                        const vehicle =
                            serviceAdvisor.vehicle;

                        return `
                        <option
                            value="${escapeHtml(
                                serviceAdvisor.id_sa
                            )}"
                        >
                            ${escapeHtml(
                                serviceAdvisor.id_sa
                            )}
                            — ${escapeHtml(
                                customer?.nama_customer
                                    ?? '-'
                            )}
                            — ${escapeHtml(
                                vehicle?.no_plat
                                    ?? '-'
                            )}
                        </option>
                    `;
                    }
                ).join('');

                select.html(`
                <option value="">
                    Pilih Form Service Advisor
                </option>
                ${options}
            `);
            }

            function loadMechanicOptions() {
                return $.ajax({
                        url: mechanicDataUrl,
                        type: 'GET',
                    })
                    .done(function(response) {
                        mechanics = (
                            response.data ?? []
                        ).filter(function(mechanic) {
                            return mechanic.status_aktif;
                        });

                        const options = mechanics.map(
                            function(mechanic) {
                                return `
                            <option
                                value="${escapeHtml(
                                    mechanic.id_mekanik
                                )}"
                            >
                                ${escapeHtml(
                                    mechanic.nama_mekanik
                                )}
                                — ${escapeHtml(
                                    mechanic.honda_id_mekanik
                                )}
                            </option>
                        `;
                            }
                        ).join('');

                        $('#id_mekanik').html(`
                    <option value="">
                        Belum ditentukan
                    </option>
                    ${options}
                `);
                    })
                    .fail(function() {
                        mechanics = [];

                        $('#id_mekanik').html(`
                    <option value="">
                        Mekanik gagal dimuat
                    </option>
                `);
                    });
            }

            function loadWorkOrders() {
                $('#wo-table-body').html(`
                <tr>
                    <td colspan="11" class="text-center py-4">
                        Memuat data...
                    </td>
                </tr>
            `);

                $.ajax({
                        url: `${baseUrl}/data`,
                        type: 'GET',
                    })
                    .done(function(response) {
                        workOrders =
                            response.data ?? [];

                        renderWorkOrders();
                    })
                    .fail(function(xhr) {
                        workOrders = [];

                        $('#wo-table-body').html(`
                    <tr>
                        <td
                            colspan="11"
                            class="text-center text-danger py-4"
                        >
                            Data Work Order gagal dimuat.
                        </td>
                    </tr>
                `);

                        showAlert(
                            'danger',
                            xhr.responseJSON?.message ??
                            'Data Work Order gagal dimuat.'
                        );
                    });
            }

            function renderWorkOrders() {
                const keyword = $('#search-wo')
                    .val()
                    .toLowerCase()
                    .trim();

                const selectedStatus =
                    $('#filter-wo-status').val();

                const filtered = workOrders.filter(
                    function(workOrder) {
                        const serviceAdvisor =
                            workOrder.service_advisor_form;

                        const customer =
                            serviceAdvisor?.customer;

                        const vehicle =
                            serviceAdvisor?.vehicle;

                        const searchableText = [
                                workOrder.id_wo,
                                workOrder.id_sa,
                                customer?.nama_customer,
                                customer?.no_hp,
                                vehicle?.no_plat,
                                vehicle?.nama_unit,
                                workOrder.mechanic
                                ?.nama_mekanik,
                                workOrder.status,
                            ]
                            .join(' ')
                            .toLowerCase();

                        return (
                            searchableText.includes(keyword) &&
                            (
                                !selectedStatus ||
                                workOrder.status ===
                                selectedStatus
                            )
                        );
                    }
                );

                if (filtered.length === 0) {
                    $('#wo-table-body').empty();
                    $('#wo-empty').removeClass('d-none');

                    return;
                }

                $('#wo-empty').addClass('d-none');

                const rows = filtered.map(
                    function(workOrder, index) {
                        const serviceAdvisor =
                            workOrder.service_advisor_form;

                        const customer =
                            serviceAdvisor?.customer;

                        const vehicle =
                            serviceAdvisor?.vehicle;

                        const editable = [
                            'DRAFT',
                            'MENUNGGU',
                        ].includes(workOrder.status);

                        const editButton = editable ?
                            `
                            <button
                                type="button"
                                class="btn btn-sm btn-outline-primary px-3 mb-0 me-1 btn-edit-wo"
                                data-id="${escapeHtml(
                                    workOrder.id_wo
                                )}"
                            >
                                Edit
                            </button>
                          ` :
                            '';

                        return `
                        <tr>
                            <td class="ps-4">
                                <span class="text-sm">
                                    ${index + 1}
                                </span>
                            </td>

                            <td>
                                <span class="text-sm font-weight-bold">
                                    ${escapeHtml(
                                        workOrder.id_wo
                                    )}
                                </span>
                            </td>

                            <td>
                                <span class="text-sm">
                                    ${escapeHtml(
                                        workOrder.id_sa
                                    )}
                                </span>
                            </td>

                            <td>
                                <span class="text-sm font-weight-bold d-block">
                                    ${escapeHtml(
                                        customer?.nama_customer
                                            ?? '-'
                                    )}
                                </span>

                                <span class="text-xs text-secondary">
                                    ${escapeHtml(
                                        customer?.no_hp
                                            ?? '-'
                                    )}
                                </span>
                            </td>

                            <td>
                                <span class="text-sm font-weight-bold d-block">
                                    ${escapeHtml(
                                        vehicle?.no_plat
                                            ?? '-'
                                    )}
                                </span>

                                <span class="text-xs text-secondary">
                                    ${escapeHtml(
                                        vehicle?.nama_unit
                                            ?? '-'
                                    )}
                                </span>
                            </td>

                         <td>
    <span class="text-sm">
        ${escapeHtml(
            workOrder.mechanic
                ?.nama_mekanik
                ?? 'Belum ditentukan'
        )}
    </span>
</td>

<td class="text-center">
    <span class="badge bg-gradient-info">
        ${workOrder.jobs_count ?? 0}
    </span>
</td>

<td class="text-center">
    <span class="badge bg-gradient-warning">
        ${workOrder.parts_count ?? 0}
    </span>
</td>

<td class="text-end">
    <span class="text-sm font-weight-bold">
        ${formatRupiah(
            workOrder.grand_total
        )}
    </span>
</td>

<td class="text-center">
    ${statusBadge(
        workOrder.status
    )}
</td>

<td class="text-center">
    ${editButton}

    <button
        type="button"
        class="btn btn-sm btn-outline-success px-3 mb-0 me-1 btn-manage-jobs"
        data-id="${escapeHtml(
            workOrder.id_wo
        )}"
    >
        Jasa
    </button>

    <button
        type="button"
        class="btn btn-sm btn-outline-dark px-3 mb-0 btn-detail-wo"
        data-id="${escapeHtml(
            workOrder.id_wo
        )}"
    >
        Detail
    </button>
</td>
                        </tr>
                    `;
                    }
                ).join('');

                $('#wo-table-body').html(rows);
            }

            function renderServiceAdvisorInformation(
                serviceAdvisorId
            ) {
                const serviceAdvisor =
                    openServiceAdvisors.find(
                        function(item) {
                            return (
                                item.id_sa ===
                                serviceAdvisorId
                            );
                        }
                    );

                renderServiceAdvisorDetail(
                    serviceAdvisor
                );
            }

            function renderServiceAdvisorDetail(
                serviceAdvisor
            ) {
                if (!serviceAdvisor) {
                    $('#sa-information').html(
                        'Pilih Form Service Advisor untuk melihat customer dan kendaraan.'
                    );

                    return;
                }

                const customer =
                    serviceAdvisor.customer;

                const vehicle =
                    serviceAdvisor.vehicle;

                $('#sa-information').html(`
                <div class="row">
                    <div class="col-md-6">
                        <small class="text-secondary">
                            Customer
                        </small>

                        <div class="font-weight-bold">
                            ${escapeHtml(
                                customer?.nama_customer
                                    ?? '-'
                            )}
                        </div>

                        <div class="text-sm">
                            ${escapeHtml(
                                customer?.no_hp ?? '-'
                            )}
                        </div>
                    </div>

                    <div class="col-md-6">
                        <small class="text-secondary">
                            Kendaraan
                        </small>

                        <div class="font-weight-bold">
                            ${escapeHtml(
                                vehicle?.no_plat ?? '-'
                            )}
                        </div>

                        <div class="text-sm">
                            ${escapeHtml(
                                vehicle?.nama_unit ?? '-'
                            )}
                        </div>
                    </div>

                    <div class="col-12 mt-3">
                        <small class="text-secondary">
                            Keluhan
                        </small>

                        <div class="text-sm">
                            ${escapeHtml(
                                serviceAdvisor.keluhan
                                    ?? '-'
                            )}
                        </div>
                    </div>
                </div>
            `);
            }

            function resetForm() {
                $('#wo-form')[0].reset();

                $('#id_wo').val('');
                $('#id_wo_display').val('');
                $('#id_sa_display').val('');
                $('#wo-form-mode').val('create');

                $('#sa-information').html(
                    'Pilih Form Service Advisor untuk melihat customer dan kendaraan.'
                );

                $('#diskon').val('0');

                clearValidationErrors();
            }

            function setFormDisabled(disabled) {
                $('#id_mekanik')
                    .prop('disabled', disabled);

                $('#diskon')
                    .prop('disabled', disabled);

                $('#catatan_mekanik')
                    .prop('disabled', disabled);
            }

            function showValidationErrors(errors) {
                Object.keys(errors).forEach(
                    function(field) {
                        if (field === 'id_wo') {
                            showFormAlert(
                                errors[field][0]
                            );

                            return;
                        }

                        const input = $(`#${field}`);
                        const errorContainer =
                            $(`#error-${field}`);

                        input.addClass('is-invalid');

                        errorContainer.text(
                            errors[field][0]
                        );
                    }
                );
            }

            function clearValidationErrors() {
                $('#wo-form .is-invalid')
                    .removeClass('is-invalid');

                $('#wo-form .invalid-feedback')
                    .text('');

                $('#wo-form-alert').empty();
            }

            function setSubmitLoading(isLoading) {
                const button = $('#btn-save-wo');

                button.prop('disabled', isLoading);

                button.text(
                    isLoading ? 'Menyimpan...' : 'Simpan'
                );
            }

            function showFormAlert(message) {
                $('#wo-form-alert').html(`
                <div
                    class="alert alert-danger text-white"
                    role="alert"
                >
                    ${escapeHtml(message)}
                </div>
            `);
            }

            function showAlert(type, message) {
                const alert = `
                <div
                    class="alert alert-${type} alert-dismissible text-white fade show"
                    role="alert"
                >
                    ${escapeHtml(message)}

                    <button
                        type="button"
                        class="btn-close btn-close-white"
                        data-bs-dismiss="alert"
                        aria-label="Tutup"
                    ></button>
                </div>
            `;

                $('#alert-container').html(alert);
            }

            function statusBadge(status) {
                const classes = {
                    DRAFT: 'bg-gradient-secondary',
                    MENUNGGU: 'bg-gradient-warning',
                    DIKERJAKAN: 'bg-gradient-info',
                    SELESAI: 'bg-gradient-success',
                    BATAL: 'bg-gradient-danger',
                };

                return `
                <span class="badge ${
                    classes[status] ??
                    'bg-gradient-dark'
                }">
                    ${escapeHtml(status)}
                </span>
            `;
            }

            function formatNumber(value) {
                const numericValue = String(
                    value ?? ''
                ).replace(/\D/g, '');

                return numericValue ?
                    new Intl.NumberFormat('id-ID')
                    .format(Number(numericValue)) :
                    '';
            }

            function formatRupiah(value) {
                return new Intl.NumberFormat(
                    'id-ID', {
                        style: 'currency',
                        currency: 'IDR',
                        minimumFractionDigits: 0,
                    }
                ).format(Number(value ?? 0));
            }

            function escapeHtml(value) {
                return $('<div>')
                    .text(value ?? '')
                    .html();
            }
        });
    </script>
@endpush
