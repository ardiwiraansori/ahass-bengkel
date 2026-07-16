@extends('layouts.user_type.auth')

@section('title', 'Master Mekanik')

@section('content')
    <div class="row">
        <div class="col-12">
            <div id="alert-container"></div>

            <div class="card mb-4">
                <div class="card-header pb-0">
                    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between">
                        <div>
                            <h5 class="mb-1">Master Mekanik</h5>

                            <p class="text-sm text-secondary mb-0">
                                Kelola data mekanik yang dapat dipilih pada Work Order.
                            </p>
                        </div>

                        <button type="button" class="btn bg-gradient-primary mt-3 mt-md-0 mb-0" id="btn-add-mechanic">
                            <i class="fas fa-plus me-1"></i>
                            Tambah Mekanik
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

                                <input type="text" class="form-control" id="search-mechanic"
                                    placeholder="Cari ID, Honda ID, nama, atau nomor HP...">
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table align-items-center mb-0" id="mechanic-table">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        No.
                                    </th>

                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        ID Mekanik
                                    </th>

                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Honda ID
                                    </th>

                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Nama Mekanik
                                    </th>

                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Nomor HP
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

                            <tbody id="mechanic-table-body">
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        Memuat data...
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div id="mechanic-empty" class="text-center text-secondary py-4 d-none">
                        Data mekanik tidak ditemukan.
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="mechanic-modal" tabindex="-1" aria-labelledby="mechanic-modal-label" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form id="mechanic-form">
                    <div class="modal-header">
                        <h5 class="modal-title" id="mechanic-modal-label">
                            Tambah Master Mekanik
                        </h5>

                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>

                    <div class="modal-body">
                        <input type="hidden" id="form-mode" value="create">

                        <div class="mb-3">
                            <label for="id_mekanik" class="form-label">
                                ID Mekanik
                            </label>

                            <input type="text" class="form-control text-uppercase" id="id_mekanik" name="id_mekanik"
                                maxlength="30" placeholder="Contoh: MEK/68601/00003" autocomplete="off">

                            <div class="invalid-feedback" id="error-id_mekanik"></div>
                        </div>

                        <div class="mb-3">
                            <label for="honda_id_mekanik" class="form-label">
                                Honda ID Mekanik
                            </label>

                            <input type="text" class="form-control text-uppercase" id="honda_id_mekanik"
                                name="honda_id_mekanik" maxlength="30" placeholder="Contoh: 77777" autocomplete="off">

                            <div class="invalid-feedback" id="error-honda_id_mekanik"></div>
                        </div>

                        <div class="mb-3">
                            <label for="nama_mekanik" class="form-label">
                                Nama Mekanik
                            </label>

                            <input type="text" class="form-control" id="nama_mekanik" name="nama_mekanik" maxlength="100"
                                autocomplete="off">

                            <div class="invalid-feedback" id="error-nama_mekanik"></div>
                        </div>

                        <div class="mb-3">
                            <label for="no_hp" class="form-label">
                                Nomor HP
                            </label>

                            <input type="text" class="form-control" id="no_hp" name="no_hp" maxlength="20"
                                placeholder="Contoh: 081234567890" autocomplete="off">

                            <div class="invalid-feedback" id="error-no_hp"></div>
                        </div>

                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="status_aktif" name="status_aktif"
                                checked>

                            <label class="form-check-label" for="status_aktif">
                                Mekanik aktif
                            </label>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-light mb-0" data-bs-dismiss="modal">
                            Batal
                        </button>

                        <button type="submit" class="btn bg-gradient-primary mb-0" id="btn-save-mechanic">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        #mechanic-table th,
        #mechanic-table td {
            vertical-align: middle;
        }

        #mechanic-table td:last-child {
            white-space: nowrap;
        }

        #mechanic-table .btn {
            font-size: 11px;
        }
    </style>
@endpush

@push('scripts')
    <script>
        $(function() {
            const baseUrl = @json(url('/master/mechanics'));

            const mechanicModalElement =
                document.getElementById('mechanic-modal');

            const mechanicModal =
                bootstrap.Modal.getOrCreateInstance(
                    mechanicModalElement
                );

            let mechanics = [];

            loadMechanics();

            $('#btn-add-mechanic').on('click', function() {
                resetForm();

                $('#form-mode').val('create');
                $('#mechanic-modal-label')
                    .text('Tambah Master Mekanik');

                $('#id_mekanik').prop('readonly', false);
                $('#honda_id_mekanik').prop('readonly', false);
                $('#status_aktif').prop('checked', true);

                mechanicModal.show();
            });

            $('#search-mechanic').on('input', function() {
                renderMechanics($(this).val());
            });

            $('#id_mekanik, #honda_id_mekanik').on(
                'input',
                function() {
                    $(this).val($(this).val().toUpperCase());
                }
            );

            $('#no_hp').on('input', function() {
                const validCharacters = $(this)
                    .val()
                    .replace(/[^0-9+\-\s()]/g, '');

                $(this).val(validCharacters);
            });

            $('#mechanic-form').on('submit', function(event) {
                event.preventDefault();

                clearValidationErrors();

                const mode = $('#form-mode').val();
                const hondaId = $('#honda_id_mekanik')
                    .val()
                    .trim();

                const isEdit = mode === 'edit';

                const url = isEdit ?
                    `${baseUrl}/${encodeURIComponent(hondaId)}` :
                    baseUrl;

                const method = isEdit ? 'PUT' : 'POST';

                const payload = {
                    id_mekanik: $('#id_mekanik').val().trim(),
                    honda_id_mekanik: hondaId,
                    nama_mekanik: $('#nama_mekanik').val().trim(),
                    no_hp: $('#no_hp').val().trim(),
                    status_aktif: $('#status_aktif').is(':checked') ?
                        1 :
                        0,
                };

                setSubmitLoading(true);

                $.ajax({
                        url: url,
                        type: method,
                        data: payload,
                    })
                    .done(function(response) {
                        mechanicModal.hide();

                        showAlert('success', response.message);
                        loadMechanics();
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

                        showAlert(
                            'danger',
                            xhr.responseJSON?.message ??
                            'Terjadi kesalahan saat menyimpan mekanik.'
                        );
                    })
                    .always(function() {
                        setSubmitLoading(false);
                    });
            });

            $(document).on(
                'click',
                '.btn-edit-mechanic',
                function() {
                    const hondaId = $(this).data('id');

                    clearValidationErrors();

                    $.ajax({
                            url: `${baseUrl}/${encodeURIComponent(hondaId)}`,
                            type: 'GET',
                        })
                        .done(function(response) {
                            const mechanic = response.data;

                            $('#form-mode').val('edit');

                            $('#mechanic-modal-label')
                                .text('Edit Master Mekanik');

                            $('#id_mekanik')
                                .val(mechanic.id_mekanik)
                                .prop('readonly', true);

                            $('#honda_id_mekanik')
                                .val(mechanic.honda_id_mekanik)
                                .prop('readonly', true);

                            $('#nama_mekanik')
                                .val(mechanic.nama_mekanik);

                            $('#no_hp').val(mechanic.no_hp ?? '');

                            $('#status_aktif').prop(
                                'checked',
                                Boolean(mechanic.status_aktif)
                            );

                            mechanicModal.show();
                        })
                        .fail(function(xhr) {
                            showAlert(
                                'danger',
                                xhr.responseJSON?.message ??
                                'Data mekanik gagal diambil.'
                            );
                        });
                }
            );

            $(document).on(
                'click',
                '.btn-toggle-mechanic',
                function() {
                    const hondaId = $(this).data('id');

                    const isActive =
                        Number($(this).data('active')) === 1;

                    const actionText = isActive ?
                        'menonaktifkan' :
                        'mengaktifkan';

                    if (
                        !confirm(
                            `Yakin ingin ${actionText} mekanik ${hondaId}?`
                        )
                    ) {
                        return;
                    }

                    $.ajax({
                            url: `${baseUrl}/${encodeURIComponent(hondaId)}` +
                                '/toggle-status',
                            type: 'PATCH',
                        })
                        .done(function(response) {
                            showAlert('success', response.message);
                            loadMechanics();
                        })
                        .fail(function(xhr) {
                            showAlert(
                                'danger',
                                xhr.responseJSON?.message ??
                                'Status mekanik gagal diubah.'
                            );
                        });
                }
            );

            function loadMechanics() {
                $('#mechanic-table-body').html(`
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
                        mechanics = response.data ?? [];

                        renderMechanics(
                            $('#search-mechanic').val()
                        );
                    })
                    .fail(function(xhr) {
                        mechanics = [];

                        $('#mechanic-table-body').html(`
                    <tr>
                        <td
                            colspan="7"
                            class="text-center text-danger py-4"
                        >
                            Data mekanik gagal dimuat.
                        </td>
                    </tr>
                `);

                        showAlert(
                            'danger',
                            xhr.responseJSON?.message ??
                            'Master mekanik gagal dimuat.'
                        );
                    });
            }

            function renderMechanics(keyword = '') {
                const normalizedKeyword = keyword
                    .toLowerCase()
                    .trim();

                const filteredMechanics = mechanics.filter(
                    function(mechanic) {
                        const searchableText = [
                                mechanic.id_mekanik,
                                mechanic.honda_id_mekanik,
                                mechanic.nama_mekanik,
                                mechanic.no_hp,
                            ]
                            .join(' ')
                            .toLowerCase();

                        return searchableText.includes(
                            normalizedKeyword
                        );
                    }
                );

                if (filteredMechanics.length === 0) {
                    $('#mechanic-table-body').empty();
                    $('#mechanic-empty').removeClass('d-none');

                    return;
                }

                $('#mechanic-empty').addClass('d-none');

                const rows = filteredMechanics.map(
                    function(mechanic, index) {
                        const statusBadge =
                            mechanic.status_aktif ?
                            '<span class="badge bg-gradient-success">Aktif</span>' :
                            '<span class="badge bg-gradient-secondary">Nonaktif</span>';

                        const toggleLabel =
                            mechanic.status_aktif ?
                            'Nonaktif' :
                            'Aktifkan';

                        const toggleClass =
                            mechanic.status_aktif ?
                            'btn-outline-danger' :
                            'btn-outline-success';

                        return `
                        <tr>
                            <td class="ps-4">
                                <span class="text-sm">
                                    ${index + 1}
                                </span>
                            </td>

                            <td>
                                <span class="text-sm font-weight-bold">
                                    ${escapeHtml(mechanic.id_mekanik)}
                                </span>
                            </td>

                            <td>
                                <span class="badge bg-gradient-info">
                                    ${escapeHtml(
                                        mechanic.honda_id_mekanik
                                    )}
                                </span>
                            </td>

                            <td>
                                <span class="text-sm">
                                    ${escapeHtml(
                                        mechanic.nama_mekanik
                                    )}
                                </span>
                            </td>

                            <td>
                                <span class="text-sm">
                                    ${escapeHtml(
                                        mechanic.no_hp || '-'
                                    )}
                                </span>
                            </td>

                            <td class="text-center">
                                ${statusBadge}
                            </td>

                            <td class="text-center">
                                <button
                                    type="button"
                                    class="btn btn-sm btn-outline-primary px-3 mb-0 me-1 btn-edit-mechanic"
                                    data-id="${escapeHtml(
                                        mechanic.honda_id_mekanik
                                    )}"
                                >
                                    Edit
                                </button>

                                <button
                                    type="button"
                                    class="btn btn-sm ${toggleClass} px-3 mb-0 btn-toggle-mechanic"
                                    data-id="${escapeHtml(
                                        mechanic.honda_id_mekanik
                                    )}"
                                    data-active="${
                                        mechanic.status_aktif ? 1 : 0
                                    }"
                                >
                                    ${toggleLabel}
                                </button>
                            </td>
                        </tr>
                    `;
                    }
                ).join('');

                $('#mechanic-table-body').html(rows);
            }

            function resetForm() {
                $('#mechanic-form')[0].reset();

                $('#id_mekanik').prop('readonly', false);
                $('#honda_id_mekanik').prop('readonly', false);
                $('#status_aktif').prop('checked', true);

                clearValidationErrors();
            }

            function showValidationErrors(errors) {
                Object.keys(errors).forEach(function(field) {
                    const input = $(`#${field}`);
                    const errorContainer =
                        $(`#error-${field}`);

                    input.addClass('is-invalid');
                    errorContainer.text(errors[field][0]);
                });
            }

            function clearValidationErrors() {
                $('#mechanic-form .is-invalid')
                    .removeClass('is-invalid');

                $('#mechanic-form .invalid-feedback')
                    .text('');
            }

            function setSubmitLoading(isLoading) {
                const button = $('#btn-save-mechanic');

                button.prop('disabled', isLoading);

                button.text(
                    isLoading ? 'Menyimpan...' : 'Simpan'
                );
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
                    const alertElement =
                        document.querySelector(
                            '#alert-container .alert'
                        );

                    if (alertElement) {
                        bootstrap.Alert
                            .getOrCreateInstance(alertElement)
                            .close();
                    }
                }, 4000);
            }

            function escapeHtml(value) {
                return $('<div>')
                    .text(value ?? '')
                    .html();
            }
        });
    </script>
@endpush
