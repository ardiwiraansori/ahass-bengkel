@extends('layouts.user_type.auth')

@section('title', 'Master Part')

@section('content')
    <div class="row">
        <div class="col-12">
            <div id="alert-container"></div>

            <div class="card mb-4">
                <div class="card-header pb-0">
                    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between">
                        <div>
                            <h5 class="mb-1">Master Part</h5>
                            <p class="text-sm text-secondary mb-0">
                                Kelola data suku cadang dan ketersediaan stok.
                            </p>
                        </div>

                        <button type="button" class="btn bg-gradient-primary mt-3 mt-md-0 mb-0" id="btn-add-part">
                            <i class="fas fa-plus me-1"></i>
                            Tambah Part
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

                                <input type="text" class="form-control" id="search-part"
                                    placeholder="Cari Part Number atau nama part...">
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table align-items-center mb-0" id="part-table">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        No.
                                    </th>

                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Part Number
                                    </th>

                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Nama Part
                                    </th>

                                    <th
                                        class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-end">
                                        Harga
                                    </th>

                                    <th
                                        class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">
                                        Stock
                                    </th>

                                    <th
                                        class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">
                                        RFS
                                    </th>

                                    <th
                                        class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">
                                        Book
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

                            <tbody id="part-table-body">
                                <tr>
                                    <td colspan="9" class="text-center py-4">
                                        Memuat data...
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div id="part-empty" class="text-center text-secondary py-4 d-none">
                        Data part tidak ditemukan.
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal tambah/edit part --}}
    <div class="modal fade" id="part-modal" tabindex="-1" aria-labelledby="part-modal-label" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form id="part-form">
                    <div class="modal-header">
                        <h5 class="modal-title" id="part-modal-label">
                            Tambah Master Part
                        </h5>

                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>

                    <div class="modal-body">
                        <input type="hidden" id="form-mode" value="create">

                        <div class="mb-3">
                            <label for="part_number" class="form-label">
                                Part Number
                            </label>

                            <input type="text" class="form-control text-uppercase" id="part_number" name="part_number"
                                maxlength="30" autocomplete="off">

                            <div class="invalid-feedback" id="error-part_number"></div>
                        </div>

                        <div class="mb-3">
                            <label for="nama_part" class="form-label">
                                Nama Part
                            </label>

                            <textarea class="form-control" id="nama_part" name="nama_part" rows="3" maxlength="255"></textarea>

                            <div class="invalid-feedback" id="error-nama_part"></div>
                        </div>

                        <div class="mb-3">
                            <label for="harga" class="form-label">
                                Harga
                            </label>

                            <div class="input-group">
                                <span class="input-group-text">Rp</span>

                                <input type="text" class="form-control text-end" id="harga" name="harga"
                                    inputmode="numeric" autocomplete="off">
                            </div>

                            <div class="invalid-feedback" id="error-harga"></div>
                        </div>

                        <div class="mb-3">
                            <label for="qty_stock" class="form-label">
                                Qty Stock
                            </label>

                            <input type="number" class="form-control" id="qty_stock" name="qty_stock" min="0"
                                step="1" autocomplete="off">

                            <div class="invalid-feedback" id="error-qty_stock"></div>

                            <small class="text-secondary" id="stock-information">
                                Part baru akan memiliki Qty RFS yang sama dengan Qty Stock
                                dan Qty Book nol.
                            </small>
                        </div>

                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" checked>

                            <label class="form-check-label" for="is_active">
                                Part aktif
                            </label>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-light mb-0" data-bs-dismiss="modal">
                            Batal
                        </button>

                        <button type="submit" class="btn bg-gradient-primary mb-0" id="btn-save-part">
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
        #part-table th,
        #part-table td {
            vertical-align: middle;
        }

        #part-table td:nth-child(3) {
            min-width: 260px;
            white-space: normal;
        }

        #part-table td:last-child {
            white-space: nowrap;
        }

        #part-table .btn {
            font-size: 11px;
        }
    </style>
@endpush

@push('scripts')
    <script>
        $(function() {
            const baseUrl = @json(url('/master/parts'));

            const partModalElement = document.getElementById('part-modal');
            const partModal = bootstrap.Modal.getOrCreateInstance(
                partModalElement
            );

            let parts = [];

            loadParts();

            $('#btn-add-part').on('click', function() {
                resetForm();

                $('#form-mode').val('create');
                $('#part-modal-label').text('Tambah Master Part');
                $('#part_number').prop('readonly', false);
                $('#is_active').prop('checked', true);

                $('#stock-information').text(
                    'Part baru akan memiliki Qty RFS yang sama dengan Qty Stock dan Qty Book nol.'
                );

                partModal.show();
            });

            $('#search-part').on('input', function() {
                renderParts($(this).val());
            });

            $('#harga').on('input', function() {
                const numericValue = $(this).val().replace(/\D/g, '');

                $(this).val(formatNumber(numericValue));
            });

            $('#part_number').on('input', function() {
                $(this).val($(this).val().toUpperCase());
            });

            $('#part-form').on('keypress', function(event) {
                if (
                    event.key === 'Enter' &&
                    event.target.tagName !== 'TEXTAREA'
                ) {
                    event.preventDefault();
                }
            });

            $('#part-form').on('submit', function(event) {
                event.preventDefault();

                clearValidationErrors();

                const mode = $('#form-mode').val();
                const partNumber = $('#part_number').val().trim();
                const isEdit = mode === 'edit';

                const url = isEdit ?
                    `${baseUrl}/${encodeURIComponent(partNumber)}` :
                    baseUrl;

                const method = isEdit ? 'PUT' : 'POST';

                const payload = {
                    part_number: partNumber,
                    nama_part: $('#nama_part').val().trim(),
                    harga: $('#harga').val().replace(/\D/g, ''),
                    qty_stock: $('#qty_stock').val(),
                    is_active: $('#is_active').is(':checked') ? 1 : 0,
                };

                setSubmitLoading(true);

                $.ajax({
                        url: url,
                        type: method,
                        data: payload,
                    })
                    .done(function(response) {
                        partModal.hide();

                        showAlert('success', response.message);
                        loadParts();
                    })
                    .fail(function(xhr) {
                        if (
                            xhr.status === 422 &&
                            xhr.responseJSON?.errors
                        ) {
                            showValidationErrors(xhr.responseJSON.errors);
                            return;
                        }

                        showAlert(
                            'danger',
                            xhr.responseJSON?.message ??
                            'Terjadi kesalahan saat menyimpan part.'
                        );
                    })
                    .always(function() {
                        setSubmitLoading(false);
                    });
            });

            $(document).on('click', '.btn-edit-part', function() {
                const partNumber = $(this).data('id');

                clearValidationErrors();

                $.ajax({
                        url: `${baseUrl}/${encodeURIComponent(partNumber)}`,
                        type: 'GET',
                    })
                    .done(function(response) {
                        const part = response.data;

                        $('#form-mode').val('edit');
                        $('#part-modal-label').text('Edit Master Part');

                        $('#part_number')
                            .val(part.part_number)
                            .prop('readonly', true);

                        $('#nama_part').val(part.nama_part);
                        $('#harga').val(formatNumber(part.harga));
                        $('#qty_stock').val(part.qty_stock);
                        $('#is_active').prop(
                            'checked',
                            Boolean(part.is_active)
                        );

                        $('#stock-information').text(
                            `Qty Book saat ini: ${part.qty_book}. Qty Stock tidak boleh lebih kecil dari Qty Book.`
                        );

                        partModal.show();
                    })
                    .fail(function(xhr) {
                        showAlert(
                            'danger',
                            xhr.responseJSON?.message ??
                            'Data part gagal diambil.'
                        );
                    });
            });

            $(document).on('click', '.btn-toggle-part', function() {
                const partNumber = $(this).data('id');
                const isActive = Number($(this).data('active')) === 1;

                const actionText = isActive ?
                    'menonaktifkan' :
                    'mengaktifkan';

                if (
                    !confirm(
                        `Yakin ingin ${actionText} part ${partNumber}?`
                    )
                ) {
                    return;
                }

                $.ajax({
                        url: `${baseUrl}/${encodeURIComponent(partNumber)}/toggle-status`,
                        type: 'PATCH',
                    })
                    .done(function(response) {
                        showAlert('success', response.message);
                        loadParts();
                    })
                    .fail(function(xhr) {
                        showAlert(
                            'danger',
                            xhr.responseJSON?.message ??
                            'Status part gagal diubah.'
                        );
                    });
            });

            function loadParts() {
                $('#part-table-body').html(`
                <tr>
                    <td colspan="9" class="text-center py-4">
                        Memuat data...
                    </td>
                </tr>
            `);

                $.ajax({
                        url: `${baseUrl}/data`,
                        type: 'GET',
                    })
                    .done(function(response) {
                        parts = response.data ?? [];

                        renderParts($('#search-part').val());
                    })
                    .fail(function(xhr) {
                        parts = [];

                        $('#part-table-body').html(`
                    <tr>
                        <td
                            colspan="9"
                            class="text-center text-danger py-4"
                        >
                            Data part gagal dimuat.
                        </td>
                    </tr>
                `);

                        showAlert(
                            'danger',
                            xhr.responseJSON?.message ??
                            'Master part gagal dimuat.'
                        );
                    });
            }

            function renderParts(keyword = '') {
                const normalizedKeyword = keyword
                    .toLowerCase()
                    .trim();

                const filteredParts = parts.filter(function(part) {
                    const searchableText = [
                        part.part_number,
                        part.nama_part,
                    ].join(' ').toLowerCase();

                    return searchableText.includes(normalizedKeyword);
                });

                if (filteredParts.length === 0) {
                    $('#part-table-body').empty();
                    $('#part-empty').removeClass('d-none');
                    return;
                }

                $('#part-empty').addClass('d-none');

                const rows = filteredParts.map(function(part, index) {
                    const statusBadge = part.is_active ?
                        '<span class="badge bg-gradient-success">Aktif</span>' :
                        '<span class="badge bg-gradient-secondary">Nonaktif</span>';

                    const toggleLabel = part.is_active ?
                        'Nonaktif' :
                        'Aktifkan';

                    const toggleClass = part.is_active ?
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
                                ${escapeHtml(part.part_number)}
                            </span>
                        </td>

                        <td>
                            <span class="text-sm">
                                ${escapeHtml(part.nama_part)}
                            </span>
                        </td>

                        <td class="text-end">
                            <span class="text-sm font-weight-bold">
                                ${formatRupiah(part.harga)}
                            </span>
                        </td>

                        <td class="text-center">
                            <span class="badge bg-gradient-dark">
                                ${part.qty_stock}
                            </span>
                        </td>

                        <td class="text-center">
                            <span class="badge bg-gradient-success">
                                ${part.qty_rfs}
                            </span>
                        </td>

                        <td class="text-center">
                            <span class="badge bg-gradient-warning">
                                ${part.qty_book}
                            </span>
                        </td>

                        <td class="text-center">
                            ${statusBadge}
                        </td>

                        <td class="text-center">
                            <button
                                type="button"
                                class="btn btn-sm btn-outline-primary px-3 mb-0 me-1 btn-edit-part"
                                data-id="${escapeHtml(part.part_number)}"
                            >
                                Edit
                            </button>

                            <button
                                type="button"
                                class="btn btn-sm ${toggleClass} px-3 mb-0 btn-toggle-part"
                                data-id="${escapeHtml(part.part_number)}"
                                data-active="${part.is_active ? 1 : 0}"
                            >
                                ${toggleLabel}
                            </button>
                        </td>
                    </tr>
                `;
                }).join('');

                $('#part-table-body').html(rows);
            }

            function resetForm() {
                $('#part-form')[0].reset();

                $('#part_number').prop('readonly', false);
                $('#is_active').prop('checked', true);

                clearValidationErrors();
            }

            function showValidationErrors(errors) {
                Object.keys(errors).forEach(function(field) {
                    const input = $(`#${field}`);
                    const errorContainer = $(`#error-${field}`);

                    input.addClass('is-invalid');
                    errorContainer.text(errors[field][0]);

                    if (field === 'harga') {
                        $('#harga')
                            .closest('.input-group')
                            .addClass('has-validation');
                    }
                });
            }

            function clearValidationErrors() {
                $('#part-form .is-invalid').removeClass('is-invalid');
                $('#part-form .invalid-feedback').text('');
            }

            function setSubmitLoading(isLoading) {
                const button = $('#btn-save-part');

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
                        bootstrap.Alert
                            .getOrCreateInstance(alertElement)
                            .close();
                    }
                }, 4000);
            }

            function formatNumber(value) {
                const numericValue = String(value ?? '')
                    .replace(/\D/g, '');

                if (!numericValue) {
                    return '';
                }

                return new Intl.NumberFormat('id-ID')
                    .format(Number(numericValue));
            }

            function formatRupiah(value) {
                return new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR',
                    minimumFractionDigits: 0,
                }).format(Number(value ?? 0));
            }

            function escapeHtml(value) {
                return $('<div>')
                    .text(value ?? '')
                    .html();
            }
        });
    </script>
@endpush
