@extends('layouts.user_type.auth')

@section('title', 'Master Customer')

@section('content')
    <div class="row">
        <div class="col-12">
            <div id="alert-container"></div>

            <div class="card mb-4">
                <div class="card-header pb-0">
                    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between">
                        <div>
                            <h5 class="mb-1">Master Customer</h5>

                            <p class="text-sm text-secondary mb-0">
                                Kelola data customer dan pemilik kendaraan.
                            </p>
                        </div>

                        <button type="button" class="btn bg-gradient-primary mt-3 mt-md-0 mb-0" id="btn-add-customer">
                            <i class="fas fa-plus me-1"></i>
                            Tambah Customer
                        </button>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-search"></i>
                                </span>

                                <input type="text" class="form-control" id="search-customer"
                                    placeholder="Cari ID, nama, HP, email, atau identitas...">
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table align-items-center mb-0" id="customer-table">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        No.
                                    </th>

                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        ID Customer
                                    </th>

                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Nama Customer
                                    </th>

                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Nomor HP
                                    </th>

                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Email
                                    </th>

                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        No. Identitas
                                    </th>

                                    <th
                                        class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">
                                        Kendaraan
                                    </th>

                                    <th
                                        class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">
                                        Aksi
                                    </th>
                                </tr>
                            </thead>

                            <tbody id="customer-table-body">
                                <tr>
                                    <td colspan="8" class="text-center py-4">
                                        Memuat data...
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div id="customer-empty" class="text-center text-secondary py-4 d-none">
                        Data customer tidak ditemukan.
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal tambah/edit customer --}}
    <div class="modal fade" id="customer-modal" tabindex="-1" aria-labelledby="customer-modal-label" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <form id="customer-form">
                    <div class="modal-header">
                        <h5 class="modal-title" id="customer-modal-label">
                            Tambah Customer
                        </h5>

                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>

                    <div class="modal-body">
                        <input type="hidden" id="form-mode" value="create">

                        <div class="mb-3 d-none" id="id-customer-container">
                            <label for="id_customer" class="form-label">
                                ID Customer
                            </label>

                            <input type="text" class="form-control" id="id_customer" name="id_customer" readonly>

                            <div class="invalid-feedback" id="error-id_customer"></div>
                        </div>

                        <div class="row">
                            <div class="col-md-7">
                                <div class="mb-3">
                                    <label for="nama_customer" class="form-label">
                                        Nama Customer
                                    </label>

                                    <input type="text" class="form-control" id="nama_customer" name="nama_customer"
                                        maxlength="255" autocomplete="off">

                                    <div class="invalid-feedback" id="error-nama_customer"></div>
                                </div>
                            </div>

                            <div class="col-md-5">
                                <div class="mb-3">
                                    <label for="no_hp" class="form-label">
                                        Nomor HP
                                    </label>

                                    <input type="text" class="form-control" id="no_hp" name="no_hp" maxlength="20"
                                        placeholder="Contoh: 081234567890" autocomplete="off">

                                    <div class="invalid-feedback" id="error-no_hp"></div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="email" class="form-label">
                                        Email
                                    </label>

                                    <input type="email" class="form-control" id="email" name="email"
                                        maxlength="255" placeholder="customer@example.com" autocomplete="off">

                                    <div class="invalid-feedback" id="error-email"></div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="no_identitas" class="form-label">
                                        Nomor Identitas
                                    </label>

                                    <input type="text" class="form-control text-uppercase" id="no_identitas"
                                        name="no_identitas" maxlength="50" placeholder="NIK / SIM / identitas lainnya"
                                        autocomplete="off">

                                    <div class="invalid-feedback" id="error-no_identitas"></div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="alamat" class="form-label">
                                Alamat
                            </label>

                            <textarea class="form-control" id="alamat" name="alamat" rows="4" maxlength="1000"></textarea>

                            <div class="invalid-feedback" id="error-alamat"></div>
                        </div>

                        <small class="text-secondary" id="customer-id-info">
                            ID customer akan dibuat otomatis ketika data disimpan.
                        </small>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-light mb-0" data-bs-dismiss="modal">
                            Batal
                        </button>

                        <button type="submit" class="btn bg-gradient-primary mb-0" id="btn-save-customer">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Modal daftar kendaraan customer --}}
    <div class="modal fade" id="vehicle-list-modal" tabindex="-1" aria-labelledby="vehicle-list-modal-label"
        aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <div>
                        <h5 class="modal-title mb-1" id="vehicle-list-modal-label">
                            Kendaraan Customer
                        </h5>

                        <p class="text-sm text-secondary mb-0">
                            <span id="vehicle-customer-name">-</span>
                            <span class="mx-1">•</span>
                            <span id="vehicle-customer-id">-</span>
                        </p>
                    </div>

                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>

                <div class="modal-body">
                    <div class="d-flex justify-content-end mb-3">
                        <button type="button" class="btn bg-gradient-primary mb-0" id="btn-add-vehicle">
                            <i class="fas fa-plus me-1"></i>
                            Tambah Kendaraan
                        </button>
                    </div>

                    <div class="table-responsive">
                        <table class="table align-items-center mb-0" id="vehicle-table">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        No.
                                    </th>

                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Nomor Plat
                                    </th>

                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Kode Motor
                                    </th>

                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Nama Unit
                                    </th>

                                    <th
                                        class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">
                                        Tahun
                                    </th>

                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Nomor Rangka
                                    </th>

                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Nomor Mesin
                                    </th>

                                    <th
                                        class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">
                                        Aksi
                                    </th>
                                </tr>
                            </thead>

                            <tbody id="vehicle-table-body">
                                <tr>
                                    <td colspan="8" class="text-center py-4">
                                        Memuat data...
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div id="vehicle-empty" class="text-center text-secondary py-4 d-none">
                        Customer belum memiliki kendaraan.
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

    {{-- Modal tambah/edit kendaraan --}}
    <div class="modal fade" id="vehicle-form-modal" tabindex="-1" aria-labelledby="vehicle-form-modal-label"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <form id="vehicle-form">
                    <div class="modal-header">
                        <h5 class="modal-title" id="vehicle-form-modal-label">
                            Tambah Kendaraan
                        </h5>

                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>

                    <div class="modal-body">
                        <div id="vehicle-form-alert"></div>

                        <input type="hidden" id="vehicle-form-mode" value="create">

                        <input type="hidden" id="vehicle-id">

                        <input type="hidden" id="vehicle-id-customer">

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="vehicle-no-plat" class="form-label">
                                        Nomor Plat
                                    </label>

                                    <input type="text" class="form-control text-uppercase" id="vehicle-no-plat"
                                        maxlength="20" placeholder="Contoh: BA 1234 AA" autocomplete="off">

                                    <div class="invalid-feedback" id="vehicle-error-no_plat"></div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="vehicle-kode-motor" class="form-label">
                                        Kode Motor
                                    </label>

                                    <input type="text" class="form-control text-uppercase" id="vehicle-kode-motor"
                                        maxlength="20" placeholder="Contoh: BL atau VP" autocomplete="off">

                                    <div class="invalid-feedback" id="vehicle-error-kode_motor"></div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label for="vehicle-nama-unit" class="form-label">
                                        Nama Unit
                                    </label>

                                    <input type="text" class="form-control" id="vehicle-nama-unit" maxlength="255"
                                        placeholder="Contoh: Honda Beat Sporty" autocomplete="off">

                                    <div class="invalid-feedback" id="vehicle-error-nama_unit"></div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="vehicle-tahun" class="form-label">
                                        Tahun
                                    </label>

                                    <input type="number" class="form-control" id="vehicle-tahun" min="1980"
                                        max="{{ now()->year + 1 }}" step="1" autocomplete="off">

                                    <div class="invalid-feedback" id="vehicle-error-tahun"></div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="vehicle-no-rangka" class="form-label">
                                Nomor Rangka
                            </label>

                            <input type="text" class="form-control text-uppercase" id="vehicle-no-rangka"
                                maxlength="50" autocomplete="off">

                            <div class="invalid-feedback" id="vehicle-error-no_rangka"></div>
                        </div>

                        <div class="mb-3">
                            <label for="vehicle-no-mesin" class="form-label">
                                Nomor Mesin
                            </label>

                            <input type="text" class="form-control text-uppercase" id="vehicle-no-mesin"
                                maxlength="50" autocomplete="off">

                            <div class="invalid-feedback" id="vehicle-error-no_mesin"></div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-light mb-0" data-bs-dismiss="modal">
                            Batal
                        </button>

                        <button type="submit" class="btn bg-gradient-primary mb-0" id="btn-save-vehicle">
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
        #customer-table th,
        #customer-table td {
            vertical-align: middle;
        }

        #customer-table td:nth-child(3) {
            min-width: 190px;
        }

        #customer-table td:last-child {
            white-space: nowrap;
        }

        #customer-table .btn {
            font-size: 11px;
        }

        #vehicle-table th,

        #vehicle-table td {
            vertical-align: middle;
        }

        #vehicle-table td {
            white-space: nowrap;
        }

        #vehicle-table td:nth-child(4) {
            min-width: 180px;
            white-space: normal;
        }

        #vehicle-table .btn {
            font-size: 11px;
        }
    </style>
@endpush

@push('scripts')
    <script>
        $(function() {
            const baseUrl = @json(url('/master/customers'));
            const detailUrl = `${baseUrl}/detail`;
            const vehicleBaseUrl = @json(url('/master/vehicles'));

            const vehicleListModalElement =
                document.getElementById('vehicle-list-modal');

            const vehicleFormModalElement =
                document.getElementById('vehicle-form-modal');

            const vehicleListModal =
                bootstrap.Modal.getOrCreateInstance(
                    vehicleListModalElement
                );

            const vehicleFormModal =
                bootstrap.Modal.getOrCreateInstance(
                    vehicleFormModalElement
                );

            let activeCustomerId = null;
            let activeCustomerName = null;
            let vehicles = [];
            let returnToVehicleList = false;

            const customerModalElement =
                document.getElementById('customer-modal');

            const customerModal =
                bootstrap.Modal.getOrCreateInstance(
                    customerModalElement
                );

            let customers = [];

            loadCustomers();

            $('#btn-add-customer').on('click', function() {
                resetForm();

                $('#form-mode').val('create');
                $('#customer-modal-label').text('Tambah Customer');
                $('#id-customer-container').addClass('d-none');

                $('#customer-id-info').text(
                    'ID customer akan dibuat otomatis ketika data disimpan.'
                );

                customerModal.show();
            });

            $('#search-customer').on('input', function() {
                renderCustomers($(this).val());
            });

            $('#no_hp').on('input', function() {
                const validCharacters = $(this)
                    .val()
                    .replace(/[^0-9+\-\s()]/g, '');

                $(this).val(validCharacters);
            });

            $('#no_identitas').on('input', function() {
                $(this).val(
                    $(this).val().toUpperCase()
                );
            });

            $('#customer-form').on('submit', function(event) {
                event.preventDefault();

                clearValidationErrors();

                const mode = $('#form-mode').val();
                const isEdit = mode === 'edit';

                const payload = {
                    nama_customer: $('#nama_customer').val().trim(),

                    no_hp: $('#no_hp').val().trim(),

                    email: $('#email').val().trim(),

                    no_identitas: $('#no_identitas').val().trim(),

                    alamat: $('#alamat').val().trim(),
                };

                if (isEdit) {
                    payload.id_customer =
                        $('#id_customer').val();
                }

                setSubmitLoading(true);

                $.ajax({
                        url: baseUrl,
                        type: isEdit ? 'PUT' : 'POST',
                        data: payload,
                    })
                    .done(function(response) {
                        customerModal.hide();

                        showAlert('success', response.message);
                        loadCustomers();
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
                            'Terjadi kesalahan saat menyimpan customer.'
                        );
                    })
                    .always(function() {
                        setSubmitLoading(false);
                    });
            });

            $(document).on(
                'click',
                '.btn-edit-customer',
                function() {
                    const customerId = $(this).data('id');

                    clearValidationErrors();

                    $.ajax({
                            url: detailUrl,
                            type: 'GET',
                            data: {
                                id_customer: customerId,
                            },
                        })
                        .done(function(response) {
                            const customer = response.data;

                            $('#form-mode').val('edit');

                            $('#customer-modal-label')
                                .text('Edit Customer');

                            $('#id-customer-container')
                                .removeClass('d-none');

                            $('#id_customer')
                                .val(customer.id_customer);

                            $('#nama_customer')
                                .val(customer.nama_customer);

                            $('#no_hp')
                                .val(customer.no_hp);

                            $('#email')
                                .val(customer.email ?? '');

                            $('#no_identitas')
                                .val(customer.no_identitas ?? '');

                            $('#alamat')
                                .val(customer.alamat ?? '');

                            $('#customer-id-info').text(
                                'ID customer tidak dapat diubah.'
                            );

                            customerModal.show();
                        })
                        .fail(function(xhr) {
                            showAlert(
                                'danger',
                                xhr.responseJSON?.message ??
                                'Data customer gagal diambil.'
                            );
                        });
                }
            );
            $(document).on(
                'click',
                '.btn-manage-vehicles',
                function() {
                    const customerId = $(this).data('id');

                    loadVehicleData(customerId, true);
                }
            );

            $('#btn-add-vehicle').on('click', function() {
                resetVehicleForm();

                $('#vehicle-form-mode').val('create');
                $('#vehicle-form-modal-label')
                    .text('Tambah Kendaraan');

                $('#vehicle-id-customer')
                    .val(activeCustomerId);

                transitionToVehicleForm();
            });

            $(document).on(
                'click',
                '.btn-edit-vehicle',
                function() {
                    const vehicleId = $(this).data('id');

                    clearVehicleValidationErrors();

                    $.ajax({
                            url: `${vehicleBaseUrl}/${vehicleId}`,
                            type: 'GET',
                        })
                        .done(function(response) {
                            const vehicle = response.data;

                            $('#vehicle-form-mode').val('edit');
                            $('#vehicle-form-modal-label')
                                .text('Edit Kendaraan');

                            $('#vehicle-id').val(vehicle.id);

                            $('#vehicle-id-customer')
                                .val(vehicle.id_customer);

                            $('#vehicle-no-plat')
                                .val(vehicle.no_plat);

                            $('#vehicle-kode-motor')
                                .val(vehicle.kode_motor);

                            $('#vehicle-nama-unit')
                                .val(vehicle.nama_unit);

                            $('#vehicle-tahun')
                                .val(vehicle.tahun);

                            $('#vehicle-no-rangka')
                                .val(vehicle.no_rangka);

                            $('#vehicle-no-mesin')
                                .val(vehicle.no_mesin);

                            transitionToVehicleForm();
                        })
                        .fail(function(xhr) {
                            showAlert(
                                'danger',
                                xhr.responseJSON?.message ??
                                'Data kendaraan gagal diambil.'
                            );
                        });
                }
            );

            $('#vehicle-form').on('submit', function(event) {
                event.preventDefault();

                clearVehicleValidationErrors();

                const mode = $('#vehicle-form-mode').val();
                const isEdit = mode === 'edit';
                const vehicleId = $('#vehicle-id').val();

                const payload = {
                    no_plat: $('#vehicle-no-plat').val().trim(),

                    kode_motor: $('#vehicle-kode-motor').val().trim(),

                    nama_unit: $('#vehicle-nama-unit').val().trim(),

                    tahun: $('#vehicle-tahun').val(),

                    no_rangka: $('#vehicle-no-rangka').val().trim(),

                    no_mesin: $('#vehicle-no-mesin').val().trim(),
                };

                if (!isEdit) {
                    payload.id_customer =
                        $('#vehicle-id-customer').val();
                }

                setVehicleSubmitLoading(true);

                $.ajax({
                        url: isEdit ?
                            `${vehicleBaseUrl}/${vehicleId}` : vehicleBaseUrl,

                        type: isEdit ? 'PUT' : 'POST',

                        data: payload,
                    })
                    .done(function(response) {
                        showAlert('success', response.message);

                        loadCustomers();

                        loadVehicleData(
                            activeCustomerId,
                            false
                        );

                        vehicleFormModal.hide();
                    })
                    .fail(function(xhr) {
                        if (
                            xhr.status === 422 &&
                            xhr.responseJSON?.errors
                        ) {
                            showVehicleValidationErrors(
                                xhr.responseJSON.errors
                            );

                            return;
                        }

                        showVehicleFormAlert(
                            xhr.responseJSON?.message ??
                            'Kendaraan gagal disimpan.'
                        );
                    })
                    .always(function() {
                        setVehicleSubmitLoading(false);
                    });
            });

            $(
                '#vehicle-no-plat, ' +
                '#vehicle-kode-motor, ' +
                '#vehicle-no-rangka, ' +
                '#vehicle-no-mesin'
            ).on('input', function() {
                $(this).val(
                    $(this).val().toUpperCase()
                );
            });

            vehicleFormModalElement.addEventListener(
                'hidden.bs.modal',
                function() {
                    if (
                        returnToVehicleList &&
                        activeCustomerId
                    ) {
                        returnToVehicleList = false;
                        vehicleListModal.show();
                    }
                }
            );

            function loadCustomers() {
                $('#customer-table-body').html(`
                <tr>
                    <td colspan="8" class="text-center py-4">
                        Memuat data...
                    </td>
                </tr>
            `);

                $.ajax({
                        url: `${baseUrl}/data`,
                        type: 'GET',
                    })
                    .done(function(response) {
                        customers = response.data ?? [];

                        renderCustomers(
                            $('#search-customer').val()
                        );
                    })
                    .fail(function(xhr) {
                        customers = [];

                        $('#customer-table-body').html(`
                    <tr>
                        <td
                            colspan="8"
                            class="text-center text-danger py-4"
                        >
                            Data customer gagal dimuat.
                        </td>
                    </tr>
                `);

                        showAlert(
                            'danger',
                            xhr.responseJSON?.message ??
                            'Master customer gagal dimuat.'
                        );
                    });
            }

            function renderCustomers(keyword = '') {
                const normalizedKeyword = keyword
                    .toLowerCase()
                    .trim();

                const filteredCustomers = customers.filter(
                    function(customer) {
                        const searchableText = [
                                customer.id_customer,
                                customer.nama_customer,
                                customer.no_hp,
                                customer.email,
                                customer.no_identitas,
                                customer.alamat,
                            ]
                            .join(' ')
                            .toLowerCase();

                        return searchableText.includes(
                            normalizedKeyword
                        );
                    }
                );

                if (filteredCustomers.length === 0) {
                    $('#customer-table-body').empty();

                    $('#customer-empty')
                        .removeClass('d-none');

                    return;
                }

                $('#customer-empty').addClass('d-none');

                const rows = filteredCustomers.map(
                    function(customer, index) {
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
                                        customer.id_customer
                                    )}
                                </span>
                            </td>

                            <td>
                                <span class="text-sm">
                                    ${escapeHtml(
                                        customer.nama_customer
                                    )}
                                </span>
                            </td>

                            <td>
                                <span class="text-sm">
                                    ${escapeHtml(
                                        customer.no_hp
                                    )}
                                </span>
                            </td>

                            <td>
                                <span class="text-sm">
                                    ${escapeHtml(
                                        customer.email || '-'
                                    )}
                                </span>
                            </td>

                            <td>
                                <span class="text-sm">
                                    ${escapeHtml(
                                        customer.no_identitas || '-'
                                    )}
                                </span>
                            </td>

                           <td class="text-center">
    <button
        type="button"
        class="btn btn-sm btn-outline-primary px-3 mb-0 me-1 btn-edit-customer"
        data-id="${escapeHtml(customer.id_customer)}"
    >
        Edit
    </button>

    <button
        type="button"
        class="btn btn-sm btn-outline-info px-3 mb-0 btn-manage-vehicles"
        data-id="${escapeHtml(customer.id_customer)}"
    >
        Kendaraan
    </button>
</td>

                            <td class="text-center">
                                <button
                                    type="button"
                                    class="btn btn-sm btn-outline-primary px-3 mb-0 btn-edit-customer"
                                    data-id="${escapeHtml(
                                        customer.id_customer
                                    )}"
                                >
                                    Edit
                                </button>
                            </td>
                        </tr>
                    `;
                    }
                ).join('');

                $('#customer-table-body').html(rows);
            }

            function loadVehicleData(
                customerId,
                showModal = true
            ) {
                $('#vehicle-table-body').html(`
        <tr>
            <td colspan="8" class="text-center py-4">
                Memuat data...
            </td>
        </tr>
    `);

                $('#vehicle-empty').addClass('d-none');

                $.ajax({
                        url: `${vehicleBaseUrl}/data`,
                        type: 'GET',
                        data: {
                            id_customer: customerId,
                        },
                    })
                    .done(function(response) {
                        const result = response.data;

                        activeCustomerId =
                            result.customer.id_customer;

                        activeCustomerName =
                            result.customer.nama_customer;

                        vehicles = result.vehicles ?? [];

                        $('#vehicle-customer-name')
                            .text(activeCustomerName);

                        $('#vehicle-customer-id')
                            .text(activeCustomerId);

                        renderVehicles();

                        if (showModal) {
                            vehicleListModal.show();
                        }
                    })
                    .fail(function(xhr) {
                        showAlert(
                            'danger',
                            xhr.responseJSON?.message ??
                            'Data kendaraan gagal dimuat.'
                        );
                    });
            }

            function renderVehicles() {
                if (vehicles.length === 0) {
                    $('#vehicle-table-body').empty();
                    $('#vehicle-empty').removeClass('d-none');

                    return;
                }

                $('#vehicle-empty').addClass('d-none');

                const rows = vehicles.map(
                    function(vehicle, index) {
                        return `
                <tr>
                    <td class="ps-4">
                        <span class="text-sm">
                            ${index + 1}
                        </span>
                    </td>

                    <td>
                        <span class="text-sm font-weight-bold">
                            ${escapeHtml(vehicle.no_plat)}
                        </span>
                    </td>

                    <td>
                        <span class="badge bg-gradient-info">
                            ${escapeHtml(vehicle.kode_motor)}
                        </span>
                    </td>

                    <td>
                        <span class="text-sm">
                            ${escapeHtml(vehicle.nama_unit)}
                        </span>
                    </td>

                    <td class="text-center">
                        <span class="text-sm">
                            ${escapeHtml(vehicle.tahun)}
                        </span>
                    </td>

                    <td>
                        <span class="text-sm">
                            ${escapeHtml(vehicle.no_rangka)}
                        </span>
                    </td>

                    <td>
                        <span class="text-sm">
                            ${escapeHtml(vehicle.no_mesin)}
                        </span>
                    </td>

                    <td class="text-center">
                        <button
                            type="button"
                            class="btn btn-sm btn-outline-primary px-3 mb-0 btn-edit-vehicle"
                            data-id="${vehicle.id}"
                        >
                            Edit
                        </button>
                    </td>
                </tr>
            `;
                    }
                ).join('');

                $('#vehicle-table-body').html(rows);
            }

            function transitionToVehicleForm() {
                returnToVehicleList = true;

                const showVehicleForm = function() {
                    vehicleListModalElement.removeEventListener(
                        'hidden.bs.modal',
                        showVehicleForm
                    );

                    vehicleFormModal.show();
                };

                vehicleListModalElement.addEventListener(
                    'hidden.bs.modal',
                    showVehicleForm
                );

                vehicleListModal.hide();
            }

            function resetVehicleForm() {
                $('#vehicle-form')[0].reset();

                $('#vehicle-id').val('');
                $('#vehicle-id-customer')
                    .val(activeCustomerId);

                $('#vehicle-form-mode').val('create');
                $('#vehicle-form-alert').empty();

                clearVehicleValidationErrors();
            }

            function showVehicleValidationErrors(errors) {
                Object.keys(errors).forEach(
                    function(field) {
                        if (field === 'id_customer') {
                            showVehicleFormAlert(
                                errors[field][0]
                            );

                            return;
                        }

                        const fieldId = field.replaceAll('_', '-');

                        const input =
                            $(`#vehicle-${fieldId}`);

                        const errorContainer =
                            $(`#vehicle-error-${field}`);

                        input.addClass('is-invalid');

                        errorContainer.text(
                            errors[field][0]
                        );
                    }
                );
            }

            function clearVehicleValidationErrors() {
                $('#vehicle-form .is-invalid')
                    .removeClass('is-invalid');

                $('#vehicle-form .invalid-feedback')
                    .text('');

                $('#vehicle-form-alert').empty();
            }

            function setVehicleSubmitLoading(isLoading) {
                const button = $('#btn-save-vehicle');

                button.prop('disabled', isLoading);

                button.text(
                    isLoading ? 'Menyimpan...' : 'Simpan'
                );
            }

            function showVehicleFormAlert(message) {
                $('#vehicle-form-alert').html(`
        <div
            class="alert alert-danger text-white"
            role="alert"
        >
            ${escapeHtml(message)}
        </div>
    `);
            }

            function resetForm() {
                $('#customer-form')[0].reset();

                $('#id_customer').val('');
                $('#form-mode').val('create');

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
                $('#customer-form .is-invalid')
                    .removeClass('is-invalid');

                $('#customer-form .invalid-feedback')
                    .text('');
            }

            function setSubmitLoading(isLoading) {
                const button = $('#btn-save-customer');

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
