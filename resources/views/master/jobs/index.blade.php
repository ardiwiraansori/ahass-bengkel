@extends('layouts.user_type.auth')

@section('title', 'Master Jasa')

@push('styles')
<style>
    #job-table th,
    #job-table td {
        vertical-align: middle;
    }

    #job-table td:nth-child(4) {
        min-width: 260px;
        white-space: normal;
    }

    #job-table .btn {
        font-size: 11px;
    }
</style>
@endpush

@section('content')
    <div class="row">
        <div class="col-12">
            <div id="alert-container"></div>

            <div class="card mb-4">
                <div class="card-header pb-0">
                    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between">
                        <div>
                            <h5 class="mb-1">Master Jasa</h5>
                            <p class="text-sm text-secondary mb-0">
                                Kelola daftar jasa servis bengkel AHASS.
                            </p>
                        </div>

                        <button type="button" class="btn bg-gradient-primary mt-3 mt-md-0 mb-0" id="btn-add-job">
                            <i class="fas fa-plus me-1"></i>
                            Tambah Jasa
                        </button>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-5">
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-search"></i>
                                </span>

                                <input type="text" class="form-control" id="search-job"
                                    placeholder="Cari ID Job, kode motor, atau keterangan...">
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table align-items-center mb-0" id="job-table">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        No.
                                    </th>

                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        ID Job
                                    </th>

                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Kode Motor
                                    </th>

                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Keterangan
                                    </th>

                                    <th
                                        class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-end">
                                        Harga
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

                            <tbody id="job-table-body">
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        Memuat data...
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div id="job-empty" class="text-center text-secondary py-4 d-none">
                        Data jasa tidak ditemukan.
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal --}}
    <div class="modal fade" id="job-modal" tabindex="-1" aria-labelledby="job-modal-label" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form id="job-form">
                    <div class="modal-header">
                        <h5 class="modal-title" id="job-modal-label">
                            Tambah Master Jasa
                        </h5>

                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>

                    <div class="modal-body">
                        <input type="hidden" id="form-mode" value="create">

                        <div class="mb-3">
                            <label for="id_job" class="form-label">
                                ID Job
                            </label>

                            <input type="text" class="form-control text-uppercase" id="id_job" name="id_job"
                                maxlength="30" autocomplete="off">

                            <div class="invalid-feedback" id="error-id_job"></div>
                        </div>

                        <div class="mb-3">
                            <label for="kode_motor" class="form-label">
                                Kode Motor
                            </label>

                            <input type="text" class="form-control text-uppercase" id="kode_motor" name="kode_motor"
                                maxlength="10" autocomplete="off">

                            <div class="invalid-feedback" id="error-kode_motor"></div>
                        </div>

                        <div class="mb-3">
                            <label for="keterangan" class="form-label">
                                Keterangan Jasa
                            </label>

                            <textarea class="form-control" id="keterangan" name="keterangan" rows="3" maxlength="255"></textarea>

                            <div class="invalid-feedback" id="error-keterangan"></div>
                        </div>

                        <div class="mb-3">
                            <label for="harga" class="form-label">
                                Harga
                            </label>

                            <div class="input-group">
                                <span class="input-group-text">Rp</span>

                                <input type="text" class="form-control text-end" id="harga" name="harga"
                                    inputmode="numeric" autocomplete="off">

                                <div class="invalid-feedback" id="error-harga"></div>
                            </div>
                        </div>

                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" checked>

                            <label class="form-check-label" for="is_active">
                                Jasa aktif
                            </label>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-light mb-0" data-bs-dismiss="modal">
                            Batal
                        </button>

                        <button type="submit" class="btn bg-gradient-primary mb-0" id="btn-save-job">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(function() {
            const baseUrl = @json(url('/master/jobs'));
            const jobModalElement = document.getElementById('job-modal');
            const jobModal = bootstrap.Modal.getOrCreateInstance(jobModalElement);

            let jobs = [];

            loadJobs();

            $('#btn-add-job').on('click', function() {
                resetForm();

                $('#form-mode').val('create');
                $('#job-modal-label').text('Tambah Master Jasa');
                $('#id_job').prop('readonly', false);
                $('#is_active').prop('checked', true);

                jobModal.show();
            });

            $('#search-job').on('input', function() {
                renderJobs($(this).val());
            });

            $('#harga').on('input', function() {
                const numericValue = $(this).val().replace(/\D/g, '');
                $(this).val(formatNumber(numericValue));
            });

            $('#id_job, #kode_motor').on('input', function() {
                $(this).val($(this).val().toUpperCase());
            });

            $('#job-form').on('submit', function(event) {
                event.preventDefault();

                clearValidationErrors();

                const mode = $('#form-mode').val();
                const idJob = $('#id_job').val().trim();
                const isEdit = mode === 'edit';

                const url = isEdit ?
                    `${baseUrl}/${encodeURIComponent(idJob)}` :
                    baseUrl;

                const method = isEdit ? 'PUT' : 'POST';

                const payload = {
                    id_job: idJob,
                    kode_motor: $('#kode_motor').val().trim(),
                    keterangan: $('#keterangan').val().trim(),
                    harga: $('#harga').val().replace(/\D/g, ''),
                    is_active: $('#is_active').is(':checked') ? 1 : 0,
                };

                setSubmitLoading(true);

                $.ajax({
                        url: url,
                        type: method,
                        data: payload,
                    })
                    .done(function(response) {
                        jobModal.hide();

                        showAlert('success', response.message);
                        loadJobs();
                    })
                    .fail(function(xhr) {
                        if (xhr.status === 422 && xhr.responseJSON?.errors) {
                            showValidationErrors(xhr.responseJSON.errors);
                            return;
                        }

                        showAlert(
                            'danger',
                            xhr.responseJSON?.message ??
                            'Terjadi kesalahan saat menyimpan data.'
                        );
                    })
                    .always(function() {
                        setSubmitLoading(false);
                    });
            });

            $(document).on('click', '.btn-edit-job', function() {
                const idJob = $(this).data('id');

                clearValidationErrors();

                $.ajax({
                        url: `${baseUrl}/${encodeURIComponent(idJob)}`,
                        type: 'GET',
                    })
                    .done(function(response) {
                        const job = response.data;

                        $('#form-mode').val('edit');
                        $('#job-modal-label').text('Edit Master Jasa');

                        $('#id_job')
                            .val(job.id_job)
                            .prop('readonly', true);

                        $('#kode_motor').val(job.kode_motor);
                        $('#keterangan').val(job.keterangan);
                        $('#harga').val(formatNumber(job.harga));
                        $('#is_active').prop('checked', Boolean(job.is_active));

                        jobModal.show();
                    })
                    .fail(function(xhr) {
                        showAlert(
                            'danger',
                            xhr.responseJSON?.message ??
                            'Data jasa gagal diambil.'
                        );
                    });
            });

            $(document).on('click', '.btn-toggle-job', function() {
                const idJob = $(this).data('id');
                const isActive = Number($(this).data('active')) === 1;

                const actionText = isActive ? 'menonaktifkan' : 'mengaktifkan';

                if (!confirm(`Yakin ingin ${actionText} jasa ${idJob}?`)) {
                    return;
                }

                $.ajax({
                        url: `${baseUrl}/${encodeURIComponent(idJob)}/toggle-status`,
                        type: 'PATCH',
                    })
                    .done(function(response) {
                        showAlert('success', response.message);
                        loadJobs();
                    })
                    .fail(function(xhr) {
                        showAlert(
                            'danger',
                            xhr.responseJSON?.message ??
                            'Status jasa gagal diubah.'
                        );
                    });
            });

            function loadJobs() {
                $('#job-table-body').html(`
                <tr>
                    <td colspan="7" class="text-center py-4">
                        Memuat data...
                    </td>
                </tr>
            `);

                $.ajax({
                        url: `${baseUrl}/data`,
                        type: 'GET',
                    })
                    .done(function(response) {
                        jobs = response.data ?? [];
                        renderJobs($('#search-job').val());
                    })
                    .fail(function(xhr) {
                        jobs = [];

                        $('#job-table-body').html(`
                    <tr>
                        <td colspan="7" class="text-center text-danger py-4">
                            Data gagal dimuat.
                        </td>
                    </tr>
                `);

                        showAlert(
                            'danger',
                            xhr.responseJSON?.message ??
                            'Master jasa gagal dimuat.'
                        );
                    });
            }

            function renderJobs(keyword = '') {
                const normalizedKeyword = keyword.toLowerCase().trim();

                const filteredJobs = jobs.filter(function(job) {
                    const searchableText = [
                        job.id_job,
                        job.kode_motor,
                        job.keterangan,
                    ].join(' ').toLowerCase();

                    return searchableText.includes(normalizedKeyword);
                });

                if (filteredJobs.length === 0) {
                    $('#job-table-body').empty();
                    $('#job-empty').removeClass('d-none');
                    return;
                }

                $('#job-empty').addClass('d-none');

                const rows = filteredJobs.map(function(job, index) {
                    const statusBadge = job.is_active ?
                        '<span class="badge bg-gradient-success">Aktif</span>' :
                        '<span class="badge bg-gradient-secondary">Nonaktif</span>';

                    const toggleLabel = job.is_active ?
                        'Nonaktif' :
                        'Aktifkan';

                    const toggleClass = job.is_active ?
                        'btn-outline-danger' :
                        'btn-outline-success';

                    return `
                    <tr>
                        <td class="ps-4">
                            <span class="text-sm">${index + 1}</span>
                        </td>

                        <td>
                            <span class="text-sm font-weight-bold">
                                ${escapeHtml(job.id_job)}
                            </span>
                        </td>

                        <td>
                            <span class="badge bg-gradient-info">
                                ${escapeHtml(job.kode_motor)}
                            </span>
                        </td>

                        <td>
                            <span class="text-sm">
                                ${escapeHtml(job.keterangan)}
                            </span>
                        </td>

                        <td class="text-end">
                            <span class="text-sm font-weight-bold">
                                ${formatRupiah(job.harga)}
                            </span>
                        </td>

                        <td class="text-center">
                            ${statusBadge}
                        </td>

                        <td class="text-center">
                            <button
                                type="button"
                                class="btn btn-sm btn-outline-primary mb-0 me-1 btn-edit-job"
                                data-id="${escapeHtml(job.id_job)}"
                            >
                                Edit
                            </button>

                            <button
                                type="button"
                                class="btn btn-sm ${toggleClass} mb-0 btn-toggle-job"
                                data-id="${escapeHtml(job.id_job)}"
                                data-active="${job.is_active ? 1 : 0}"
                            >
                                ${toggleLabel}
                            </button>
                        </td>
                    </tr>
                `;
                }).join('');

                $('#job-table-body').html(rows);
            }

            function resetForm() {
                $('#job-form')[0].reset();
                $('#id_job').prop('readonly', false);
                $('#is_active').prop('checked', true);

                clearValidationErrors();
            }

            function showValidationErrors(errors) {
                Object.keys(errors).forEach(function(field) {
                    const input = $(`#${field}`);
                    const errorContainer = $(`#error-${field}`);

                    input.addClass('is-invalid');
                    errorContainer.text(errors[field][0]);
                });
            }

            function clearValidationErrors() {
                $('#job-form .is-invalid').removeClass('is-invalid');
                $('#job-form .invalid-feedback').text('');
            }

            function setSubmitLoading(isLoading) {
                const button = $('#btn-save-job');

                button.prop('disabled', isLoading);
                button.text(isLoading ? 'Menyimpan...' : 'Simpan');
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

                window.setTimeout(function() {
                    const alertElement = document.querySelector(
                        '#alert-container .alert'
                    );

                    if (alertElement) {
                        bootstrap.Alert.getOrCreateInstance(alertElement).close();
                    }
                }, 4000);
            }

            function formatNumber(value) {
                const number = String(value ?? '').replace(/\D/g, '');

                if (!number) {
                    return '';
                }

                return new Intl.NumberFormat('id-ID').format(Number(number));
            }

            function formatRupiah(value) {
                return new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR',
                    minimumFractionDigits: 0,
                }).format(Number(value ?? 0));
            }

            function escapeHtml(value) {
                return $('<div>').text(value ?? '').html();
            }
        });
    </script>
@endpush
