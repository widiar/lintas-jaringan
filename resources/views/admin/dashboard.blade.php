@extends('admin.index')

@section('main-content')
<!-- Main content -->
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-3 col-6">

                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>{{ $totalPelanggan }}</h3>
                        <p>Total Pelanggan</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-user"></i>
                    </div>
                    <a href="{{ route('admin.user', ['type'=>'Pelanggan']) }}" class="small-box-footer">More info <i
                            class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>

            <div class="col-lg-3 col-6">

                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>{{ $totalInvoice }}</h3>
                        <p>Total Invoice</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-file-invoice"></i>
                    </div>
                    <a href="{{ route('invoice') }}" class="small-box-footer">More info <i
                            class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>

            <div class="col-lg-3 col-6">

                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3>{{ $totalTicket }}</h3>
                        <p>Total Ticket</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-ticket-alt"></i>
                    </div>
                    <a href="{{ route('ticket') }}" class="small-box-footer">More info <i
                            class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>

            <div class="col-lg-3 col-6">

                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3>{{ $totalPaket }}</h3>
                        <p>Total Paket</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-feather-alt"></i>
                    </div>
                    <a href="{{ route('admin.paket') }}" class="small-box-footer">More info <i
                            class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>

        </div>
        <div class="row">
            <div class="col-lg-6 col-md-12">
                <div class="card">
                    <div class="card-header">
                        Data Pelanggan <span class="pelanggan-header">Bulan ini</span>
                    </div>
                    <div class="card-body">
                        <div class="chart">
                            <canvas id="pelangganChart" width="100%"></canvas>
                        </div>
                        <input type="text" id="filterPelanggan" class="form-control">
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-12">
                <div class="card">
                    <div class="card-header">
                        Data Invoice <span class="invoice-header">Bulan ini</span>
                    </div>
                    <div class="card-body">
                        <div class="chart">
                            <canvas id="invoiceChart" width="100%"></canvas>
                        </div>
                        <input type="text" id="filterInvoice" class="form-control">
                    </div>
                </div>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</div>
<!-- /.content -->
@endsection

@section('content-header', 'Dashboard')

@section('script')
<script>
    const pel = @json($pelanggan);
    const inv = @json($invoice);
    // console.log(pel)

    let now = new Date()
    let month = now.getMonth() + 1
    let tanggal = 30
    if(month == 2) tanggal = 28
    let tahun = now.getFullYear()
    let startMonth = month - 2
    let startYear = tahun
    if( startMonth < 1) {
        startYear = tahun - 1
        startMonth = startMonth + 12
    }
    const initChartPelanggan = () => {
        let data = {
            labels: pel.map(p => p.tanggal),
            datasets: [{
                label: 'Jumlah',
                data: pel.map(p => p.jumlah),
                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                borderColor: 'rgba(255, 99, 132, 1)',
                fill: 'start'
            }]
        };
        let canvasPelanggan = $('#pelangganChart').get(0).getContext('2d')
        let chartPelanggan = new Chart(canvasPelanggan, {
            type: 'line',
            data: data,
            options: {
                responsive: true,
                plugins: {
                    // filler: {
                    //     propagate: false,
                    // },
                    title: {
                        display: true,
                        text: (ctx) => 'Data Pelanggan'
                    },
                    legend: {
                        display: false
                    }
                },
                // interaction: {
                //     intersect: false,
                // },
                scales: {
                    y: {
                        ticks: {
                            stepSize: 1,
                            callback: function(value, index, values) {
                                return value.toLocaleString();
                            }
                        }
                    }
                }
            },
        });
    
        $('#filterPelanggan').daterangepicker({
            drops: 'up'
        })
        
        $('#filterPelanggan').data('daterangepicker').setStartDate(`${month}/01/${tahun}`);
        // Mengatur tanggal akhir
        $('#filterPelanggan').data('daterangepicker').setEndDate(`${month}/${tanggal}/${tahun}`);  
        $('#filterPelanggan').on('apply.daterangepicker', function(ev, picker) {
            let startDate = picker.startDate.format('YYYY-MM-DD');
            let endDate = picker.endDate.format('YYYY-MM-DD');
            $.ajax({
                url: `{{ route('admin.filter.chart.pelanggan') }}`,
                data: {
                    tanggal_awal: startDate,
                    tanggal_akhir: endDate
                },
                type: 'post',
                success: (res) => {
                    if(res.status == 'success'){
                        // console.log(res.data)
                        chartPelanggan.data.datasets.forEach(dataset => {
                            dataset.data = res.data.map(p => p.jumlah);
                        });
                        chartPelanggan.data.labels = res.data.map(p => p.tanggal)
                        chartPelanggan.update();
                        $('.pelanggan-header').text(`${startDate} s/d ${endDate}`)
                    }
                    else console.log(res)
                },
                error: (err) => {
                    console.log(err)
                }
            })
        });
    }

    // invoice
    const initChartInvoice = () => {
        let data = {
            labels: inv.map(p => p.tanggal),
            datasets: [{
                label: 'Jumlah',
                data: inv.map(p => p.jumlah),
                backgroundColor: 'rgba(99, 255, 114, 0.2)',
                borderColor: 'rgba(99, 255, 114, 1)',
                fill: 'start'
            }]
        };
        let canvasInv = $('#invoiceChart').get(0).getContext('2d')
        let chartInvoice = new Chart(canvasInv, {
            type: 'line',
            data: data,
            options: {
                responsive: true,
                plugins: {
                        filler: {
                            propagate: false,
                        },
                    title: {
                        display: true,
                        text: (ctx) => 'Data Invoice'
                    },
                    legend: {
                        display: false
                    }
                },
                interaction: {
                    intersect: false,
                },
                // scales: {
                //     y: {
                //         ticks: {
                //             stepSize: 1,
                //             callback: function(value, index, values) {
                //                 return value.toLocaleString();
                //             }
                //         }
                //     }
                // }
            },
        });
    
        $('#filterInvoice').daterangepicker({
            drops: 'up'
        })
        $('#filterInvoice').data('daterangepicker').setStartDate(`${month}/01/${tahun}`);
        // Mengatur tanggal akhir
        $('#filterInvoice').data('daterangepicker').setEndDate(`${month}/${tanggal}/${tahun}`);  
        $('#filterInvoice').on('apply.daterangepicker', function(ev, picker) {
            let startDate = picker.startDate.format('YYYY-MM-DD');
            let endDate = picker.endDate.format('YYYY-MM-DD');
            $.ajax({
                url: `{{ route('admin.filter.chart.invoice') }}`,
                data: {
                    tanggal_awal: startDate,
                    tanggal_akhir: endDate
                },
                type: 'post',
                success: (res) => {
                    if(res.status == 'success'){
                        // console.log(res.data)
                        chartInvoice.data.datasets.forEach(dataset => {
                            dataset.data = res.data.map(p => p.jumlah);
                        });
                        chartInvoice.data.labels = res.data.map(p => p.tanggal)
                        chartInvoice.update();
                        $('.invoice-header').text(`${startDate} s/d ${endDate}`)
                    }
                    else console.log(res)
                },
                error: (err) => {
                    console.log(err)
                }
            })
        });
    }
    

    initChartPelanggan()
    initChartInvoice()

</script>
@endsection