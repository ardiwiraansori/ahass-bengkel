@extends('layouts.user_type.auth')

@section('title', 'Form Service Advisor')

@section('content')
    <div class="row">
        <div class="col-12">
            <div id="alert-container"></div>

            <div class="card mb-4">
                <div class="card-header pb-0">
                    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between">
                        <div>
                            <h5 class="mb-1">Form Service Advisor</h5>

                            <p class="text-sm text-secondary mb-0">
                                Catat kedatangan customer, kendaraan, dan keluhan servis.
                            </p>
                        </div>

                        <button type="button" class="btn bg-gradient-primary mt-3 mt-md-0 mb-0" id="btn-add-sa">
                            <i class="fas fa-plus me-1"></i>
                            Buat Form SA
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

                                <input type="text" class="form-control" id="search-sa"
                                    placeholder="Cari ID SA, customer, plat, unit, atau keluhan...">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <select class="form-select" id="filter-sa-status">
                                <option value="">Semua Status</option>
                                <option value="OPEN">OPEN</option>
                                <option value="CONVERTED">CONVERTED</option>
                                <option value="CANCELLED">CANCELLED</option>
                            </select>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table align-items-center mb-0" id="sa-table">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        No.
                                    </th>

                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        ID Form SA
                                    </th>

                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Kedatangan
                                    </th>

                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Customer
                                    </th>

                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Kendaraan
                                    </th>

                                    <th
                                        class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">
                                        KM
                                    </th>

                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Keluhan
                                    </th>

                                    <th
                                        class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">
                                        Status
                                    </th>

                                    <th
                                        class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">
                                        Work Order
                                    </th>

                                    <th
                                        class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">
                                        Aksi
                                    </th>
                                </tr>
                            </thead>

                            <tbody id="sa-table-body">
                                <tr>
                                    <td colspan="10" class="text-center py-4">
                                        Memuat data...
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div id="sa-empty" class="text-center text-secondary py-4 d-none">
                        Form Service Advisor tidak ditemukan.
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal tambah/edit Form SA --}}
    <div class="modal fade" id="sa-modal" tabindex="-1" aria-labelledby="sa-modal-label" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <form id="sa-form">
                    <div class="modal-header">
                        <h5 class="modal-title" id="sa-modal-label">
                            Buat Form Service Advisor
                        </h5>

                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>

                    <div class="modal-body">
                        <div id="sa-form-alert"></div>

                        <input type="hidden" id="sa-form-mode" value="create">

                        <input type="hidden" id="id_sa" name="id_sa">

                        <div class="mb-3 d-none" id="id-sa-container">
                            <label for="id_sa_display" class="form-label">
                                ID Form Service Advisor
                            </label>

                            <input type="text" class="form-control" id="id_sa_display" readonly>
                        </div>

                        <div class="row">
                            <div class="col-md-7">
                                <div class="mb-3">
                                    <label for="id_customer" class="form-label">
                                        Customer
                                    </label>

                                    <select class="form-select" id="id_customer" name="id_customer">
                                        <option value="">
                                            Memuat customer...
                                        </option>
                                    </select>

                                    <div class="invalid-feedback" id="error-id_customer"></div>
                                </div>
                            </div>

                            <div class="col-md-5">
                                <div class="mb-3">
                                    <label for="vehicle_id" class="form-label">
                                        Kendaraan
                                    </label>

                                    <select class="form-select" id="vehicle_id" name="vehicle_id" disabled>
                                        <option value="">
                                            Pilih customer terlebih dahulu
                                        </option>
                                    </select>

                                    <div class="invalid-feedback" id="error-vehicle_id"></div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-7">
                                <div class="mb-3">
                                    <label for="tanggal_kedatangan" class="form-label">
                                        Tanggal dan Waktu Kedatangan
                                    </label>

                                    <input type="datetime-local" class="form-control" id="tanggal_kedatangan"
                                        name="tanggal_kedatangan">

                                    <div class="invalid-feedback" id="error-tanggal_kedatangan"></div>
                                </div>
                            </div>

                            <div class="col-md-5">
                                <div class="mb-3">
                                    <label for="kilometer" class="form-label">
                                        Kilometer
                                    </label>

                                    <input type="number" class="form-control" id="kilometer" name="kilometer"
                                        min="0" step="1" placeholder="Contoh: 25000">

                                    <div class="invalid-feedback" id="error-kilometer"></div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="keluhan" class="form-label">
                                Keluhan Customer
                            </label>

                            <textarea class="form-control" id="keluhan" name="keluhan" rows="4" maxlength="2000"
                                placeholder="Tuliskan keluhan yang disampaikan customer..."></textarea>

                            <div class="invalid-feedback" id="error-keluhan"></div>
                        </div>

                        <div class="mb-3">
                            <label for="catatan_sa" class="form-label">
                                Catatan Service Advisor
                            </label>

                            <textarea class="form-control" id="catatan_sa" name="catatan_sa" rows="3" maxlength="2000"
                                placeholder="Catatan tambahan, kondisi kendaraan, atau permintaan customer..."></textarea>

                            <div class="invalid-feedback" id="error-catatan_sa"></div>
                        </div>

                        <small class="text-secondary">
                            Form SA yang sudah menjadi Work Order atau dibatalkan
                            tidak dapat diedit.
                        </small>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-light mb-0" data-bs-dismiss="modal">
                            Batal
                        </button>

                        <button type="submit" class="btn bg-gradient-primary mb-0" id="btn-save-sa">
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
        #sa-table th,
        #sa-table td {
            vertical-align: middle;
        }

        #sa-table td:nth-child(4),
        #sa-table td:nth-child(5) {
            min-width: 170px;
        }

        #sa-table td:nth-child(7) {
            min-width: 230px;
            max-width: 320px;
            white-space: normal;
        }

        #sa-table td:last-child {
            white-space: nowrap;
        }

        #sa-table .btn {
            font-size: 11px;
        }
    </style>
@endpush

@push('scripts')
    <script>
        $(function() {
            const baseUrl =
                @json(url('/transactions/service-advisors'));

            const detailUrl = `${baseUrl}/detail`;
            const cancelUrl = `${baseUrl}/cancel`;

            const customerDataUrl =
                @json(url('/master/customers/data'));

            const vehicleDataUrl =
                @json(url('/master/vehicles/data'));

            const saModalElement =
                document.getElementById('sa-modal');

            const saModal =
                bootstrap.Modal.getOrCreateInstance(
                    saModalElement
                );

            let serviceAdvisors = [];
            let customers = [];

            loadCustomerOptions();
            loadServiceAdvisors();

            $('#btn-add-sa').on('click', function() {
                resetForm();

                $('#sa-form-mode').val('create');
                $('#sa-modal-label')
                    .text('Buat Form Service Advisor');

                $('#id-sa-container').addClass('d-none');

                $('#tanggal_kedatangan')
                    .val(currentDateTimeLocal());

                saModal.show();
            });

            $('#search-sa').on('input', function() {
                renderServiceAdvisors();
            });

            $('#filter-sa-status').on('change', function() {
                renderServiceAdvisors();
            });

            $('#id_customer').on('change', function() {
                const customerId = $(this).val();

                loadVehicleOptions(customerId);
            });

            $('#sa-form').on('submit', function(event) {
                event.preventDefault();

                clearValidationErrors();

                const isEdit =
                    $('#sa-form-mode').val() === 'edit';

                const payload = {
                    id_customer: $('#id_customer').val(),

                    vehicle_id: $('#vehicle_id').val(),

                    tanggal_kedatangan: $('#tanggal_kedatangan').val(),

                    kilometer: $('#kilometer').val(),

                    keluhan: $('#keluhan').val().trim(),

                    catatan_sa: $('#catatan_sa').val().trim(),
                };

                if (isEdit) {
                    payload.id_sa = $('#id_sa').val();
                }

                setSubmitLoading(true);

                $.ajax({
                        url: baseUrl,
                        type: isEdit ? 'PUT' : 'POST',
                        data: payload,
                    })
                    .done(function(response) {
                        saModal.hide();

                        showAlert(
                            'success',
                            response.message
                        );

                        loadServiceAdvisors();
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
                            'Form Service Advisor gagal disimpan.'
                        );
                    })
                    .always(function() {
                        setSubmitLoading(false);
                    });
            });

            $(document).on(
                'click',
                '.btn-edit-sa',
                function() {
                    const serviceAdvisorId =
                        $(this).data('id');

                    clearValidationErrors();

                    $.ajax({
                            url: detailUrl,
                            type: 'GET',
                            data: {
                                id_sa: serviceAdvisorId,
                            },
                        })
                        .done(function(response) {
                            const serviceAdvisor =
                                response.data;

                            $('#sa-form-mode').val('edit');

                            $('#sa-modal-label')
                                .text('Edit Form Service Advisor');

                            $('#id-sa-container')
                                .removeClass('d-none');

                            $('#id_sa')
                                .val(serviceAdvisor.id_sa);

                            $('#id_sa_display')
                                .val(serviceAdvisor.id_sa);

                            $('#id_customer')
                                .val(serviceAdvisor.id_customer);

                            $('#tanggal_kedatangan').val(
                                toDateTimeLocal(
                                    serviceAdvisor
                                    .tanggal_kedatangan
                                )
                            );

                            $('#kilometer').val(
                                serviceAdvisor.kilometer ?? ''
                            );

                            $('#keluhan').val(
                                serviceAdvisor.keluhan
                            );

                            $('#catatan_sa').val(
                                serviceAdvisor.catatan_sa ?? ''
                            );

                            loadVehicleOptions(
                                    serviceAdvisor.id_customer,
                                    serviceAdvisor.vehicle_id
                                )
                                .always(function() {
                                    saModal.show();
                                });
                        })
                        .fail(function(xhr) {
                            showAlert(
                                'danger',
                                xhr.responseJSON?.message ??
                                'Form Service Advisor gagal diambil.'
                            );
                        });
                }
            );

            $(document).on(
                'click',
                '.btn-cancel-sa',
                function() {
                    const serviceAdvisorId =
                        $(this).data('id');

                    if (
                        !confirm(
                            `Yakin ingin membatalkan ${serviceAdvisorId}?`
                        )
                    ) {
                        return;
                    }

                    $.ajax({
                            url: cancelUrl,
                            type: 'PATCH',
                            data: {
                                id_sa: serviceAdvisorId,
                            },
                        })
                        .done(function(response) {
                            showAlert(
                                'success',
                                response.message
                            );

                            loadServiceAdvisors();
                        })
                        .fail(function(xhr) {
                            const validationMessage =
                                firstValidationMessage(xhr);

                            showAlert(
                                'danger',
                                validationMessage ??
                                xhr.responseJSON?.message ??
                                'Form Service Advisor gagal dibatalkan.'
                            );
                        });
                }
            );

            function loadCustomerOptions() {
                $('#id_customer')
                    .prop('disabled', true)
                    .html(`
                    <option value="">
                        Memuat customer...
                    </option>
                `);

                $.ajax({
                        url: customerDataUrl,
                        type: 'GET',
                    })
                    .done(function(response) {
                        customers = response.data ?? [];

                        const options = customers.map(
                            function(customer) {
                                return `
                            <option
                                value="${escapeHtml(
                                    customer.id_customer
                                )}"
                            >
                                ${escapeHtml(
                                    customer.nama_customer
                                )}
                                — ${escapeHtml(
                                    customer.no_hp
                                )}
                            </option>
                        `;
                            }
                        ).join('');

                        $('#id_customer')
                            .prop('disabled', false)
                            .html(`
                        <option value="">
                            Pilih customer
                        </option>
                        ${options}
                    `);
                    })
                    .fail(function() {
                        $('#id_customer').html(`
                    <option value="">
                        Customer gagal dimuat
                    </option>
                `);

                        showAlert(
                            'danger',
                            'Data customer gagal dimuat.'
                        );
                    });
            }

            function loadVehicleOptions(
                customerId,
                selectedVehicleId = null
            ) {
                const vehicleSelect = $('#vehicle_id');

                if (!customerId) {
                    vehicleSelect
                        .prop('disabled', true)
                        .html(`
                        <option value="">
                            Pilih customer terlebih dahulu
                        </option>
                    `);

                    return $.Deferred()
                        .resolve()
                        .promise();
                }

                vehicleSelect
                    .prop('disabled', true)
                    .html(`
                    <option value="">
                        Memuat kendaraan...
                    </option>
                `);

                return $.ajax({
                        url: vehicleDataUrl,
                        type: 'GET',
                        data: {
                            id_customer: customerId,
                        },
                    })
                    .done(function(response) {
                        const vehicles =
                            response.data?.vehicles ?? [];

                        if (vehicles.length === 0) {
                            vehicleSelect.html(`
                        <option value="">
                            Customer belum memiliki kendaraan
                        </option>
                    `);

                            return;
                        }

                        const options = vehicles.map(
                            function(vehicle) {
                                return `
                            <option value="${vehicle.id}">
                                ${escapeHtml(
                                    vehicle.no_plat
                                )}
                                — ${escapeHtml(
                                    vehicle.nama_unit
                                )}
                                (${escapeHtml(
                                    vehicle.tahun
                                )})
                            </option>
                        `;
                            }
                        ).join('');

                        vehicleSelect
                            .prop('disabled', false)
                            .html(`
                        <option value="">
                            Pilih kendaraan
                        </option>
                        ${options}
                    `);

                        if (selectedVehicleId) {
                            vehicleSelect.val(
                                String(selectedVehicleId)
                            );
                        }
                    })
                    .fail(function() {
                        vehicleSelect.html(`
                    <option value="">
                        Kendaraan gagal dimuat
                    </option>
                `);

                        showFormAlert(
                            'Data kendaraan customer gagal dimuat.'
                        );
                    });
            }

            function loadServiceAdvisors() {
                $('#sa-table-body').html(`
                <tr>
                    <td colspan="10" class="text-center py-4">
                        Memuat data...
                    </td>
                </tr>
            `);

                $.ajax({
                        url: `${baseUrl}/data`,
                        type: 'GET',
                    })
                    .done(function(response) {
                        serviceAdvisors =
                            response.data ?? [];

                        renderServiceAdvisors();
                    })
                    .fail(function(xhr) {
                        serviceAdvisors = [];

                        $('#sa-table-body').html(`
                    <tr>
                        <td
                            colspan="10"
                            class="text-center text-danger py-4"
                        >
                            Data Form Service Advisor gagal dimuat.
                        </td>
                    </tr>
                `);

                        showAlert(
                            'danger',
                            xhr.responseJSON?.message ??
                            'Data Form Service Advisor gagal dimuat.'
                        );
                    });
            }

            function renderServiceAdvisors() {
                const keyword = $('#search-sa')
                    .val()
                    .toLowerCase()
                    .trim();

                const selectedStatus =
                    $('#filter-sa-status').val();

                const filtered = serviceAdvisors.filter(
                    function(serviceAdvisor) {
                        const searchableText = [
                                serviceAdvisor.id_sa,
                                serviceAdvisor.customer
                                ?.nama_customer,
                                serviceAdvisor.customer?.no_hp,
                                serviceAdvisor.vehicle?.no_plat,
                                serviceAdvisor.vehicle
                                ?.kode_motor,
                                serviceAdvisor.vehicle
                                ?.nama_unit,
                                serviceAdvisor.keluhan,
                                serviceAdvisor.status,
                                serviceAdvisor.work_order?.id_wo,
                            ]
                            .join(' ')
                            .toLowerCase();

                        const matchesKeyword =
                            searchableText.includes(keyword);

                        const matchesStatus = !selectedStatus ||
                            serviceAdvisor.status ===
                            selectedStatus;

                        return (
                            matchesKeyword &&
                            matchesStatus
                        );
                    }
                );

                if (filtered.length === 0) {
                    $('#sa-table-body').empty();
                    $('#sa-empty').removeClass('d-none');

                    return;
                }

                $('#sa-empty').addClass('d-none');

                const rows = filtered.map(
                    function(serviceAdvisor, index) {
                        const isOpen =
                            serviceAdvisor.status === 'OPEN';

                        const workOrder =
                            serviceAdvisor.work_order;

                        const workOrderBadge = workOrder ?
                            `
                            <span class="badge bg-gradient-dark">
                                ${escapeHtml(
                                    workOrder.id_wo
                                )}
                            </span>
                          ` :
                            `
                            <span class="text-xs text-secondary">
                                Belum dibuat
                            </span>
                          `;

                        const actions = isOpen ?
                            `
                            <button
                                type="button"
                                class="btn btn-sm btn-outline-primary px-3 mb-0 me-1 btn-edit-sa"
                                data-id="${escapeHtml(
                                    serviceAdvisor.id_sa
                                )}"
                            >
                                Edit
                            </button>

                            <button
                                type="button"
                                class="btn btn-sm btn-outline-danger px-3 mb-0 btn-cancel-sa"
                                data-id="${escapeHtml(
                                    serviceAdvisor.id_sa
                                )}"
                            >
                                Batal
                            </button>
                          ` :
                            `
                            <span class="text-xs text-secondary">
                                Tidak ada aksi
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
                                        serviceAdvisor.id_sa
                                    )}
                                </span>
                            </td>

                            <td>
                                <span class="text-sm">
                                    ${formatDateTime(
                                        serviceAdvisor
                                            .tanggal_kedatangan
                                    )}
                                </span>
                            </td>

                            <td>
                                <span class="text-sm font-weight-bold d-block">
                                    ${escapeHtml(
                                        serviceAdvisor.customer
                                            ?.nama_customer
                                            ?? '-'
                                    )}
                                </span>

                                <span class="text-xs text-secondary">
                                    ${escapeHtml(
                                        serviceAdvisor.customer
                                            ?.no_hp
                                            ?? '-'
                                    )}
                                </span>
                            </td>

                            <td>
                                <span class="text-sm font-weight-bold d-block">
                                    ${escapeHtml(
                                        serviceAdvisor.vehicle
                                            ?.no_plat
                                            ?? '-'
                                    )}
                                </span>

                                <span class="text-xs text-secondary">
                                    ${escapeHtml(
                                        serviceAdvisor.vehicle
                                            ?.nama_unit
                                            ?? '-'
                                    )}
                                </span>
                            </td>

                            <td class="text-center">
                                <span class="text-sm">
                                    ${
                                        serviceAdvisor.kilometer
                                            !== null
                                            ? formatNumber(
                                                serviceAdvisor
                                                    .kilometer
                                            )
                                            : '-'
                                    }
                                </span>
                            </td>

                            <td>
                                <span
                                    class="text-sm"
                                    title="${escapeHtml(
                                        serviceAdvisor.keluhan
                                    )}"
                                >
                                    ${escapeHtml(
                                        truncateText(
                                            serviceAdvisor
                                                .keluhan,
                                            90
                                        )
                                    )}
                                </span>
                            </td>

                            <td class="text-center">
                                ${statusBadge(
                                    serviceAdvisor.status
                                )}
                            </td>

                            <td class="text-center">
                                ${workOrderBadge}
                            </td>

                            <td class="text-center">
                                ${actions}
                            </td>
                        </tr>
                    `;
                    }
                ).join('');

                $('#sa-table-body').html(rows);
            }

            function resetForm() {
                $('#sa-form')[0].reset();

                $('#id_sa').val('');
                $('#id_sa_display').val('');
                $('#sa-form-mode').val('create');

                $('#id-sa-container').addClass('d-none');

                $('#vehicle_id')
                    .prop('disabled', true)
                    .html(`
                    <option value="">
                        Pilih customer terlebih dahulu
                    </option>
                `);

                clearValidationErrors();
            }

            function showValidationErrors(errors) {
                Object.keys(errors).forEach(
                    function(field) {
                        if (field === 'id_sa') {
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
                $('#sa-form .is-invalid')
                    .removeClass('is-invalid');

                $('#sa-form .invalid-feedback')
                    .text('');

                $('#sa-form-alert').empty();
            }

            function setSubmitLoading(isLoading) {
                const button = $('#btn-save-sa');

                button.prop('disabled', isLoading);

                button.text(
                    isLoading ? 'Menyimpan...' : 'Simpan'
                );
            }

            function showFormAlert(message) {
                $('#sa-form-alert').html(`
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

            function statusBadge(status) {
                const classes = {
                    OPEN: 'bg-gradient-success',
                    CONVERTED: 'bg-gradient-info',
                    CANCELLED: 'bg-gradient-secondary',
                };

                const badgeClass =
                    classes[status] ??
                    'bg-gradient-dark';

                return `
                <span class="badge ${badgeClass}">
                    ${escapeHtml(status)}
                </span>
            `;
            }

            function formatDateTime(value) {
                if (!value) {
                    return '-';
                }

                const date = new Date(value);

                if (Number.isNaN(date.getTime())) {
                    return escapeHtml(value);
                }

                return new Intl.DateTimeFormat(
                    'id-ID', {
                        dateStyle: 'medium',
                        timeStyle: 'short',
                    }
                ).format(date);
            }

            function currentDateTimeLocal() {
                const now = new Date();

                now.setMinutes(
                    now.getMinutes() -
                    now.getTimezoneOffset()
                );

                return now.toISOString().slice(0, 16);
            }

            function toDateTimeLocal(value) {
                if (!value) {
                    return '';
                }

                return String(value)
                    .replace(' ', 'T')
                    .slice(0, 16);
            }

            function formatNumber(value) {
                return new Intl.NumberFormat(
                    'id-ID'
                ).format(Number(value ?? 0));
            }

            function truncateText(value, maximumLength) {
                const text = String(value ?? '');

                if (text.length <= maximumLength) {
                    return text;
                }

                return (
                    text.slice(0, maximumLength) +
                    '...'
                );
            }

            function firstValidationMessage(xhr) {
                const errors =
                    xhr.responseJSON?.errors;

                if (!errors) {
                    return null;
                }

                const firstField =
                    Object.keys(errors)[0];

                return firstField ?
                    errors[firstField][0] :
                    null;
            }

            function escapeHtml(value) {
                return $('<div>')
                    .text(value ?? '')
                    .html();
            }
        });
    </script>
@endpush
