@extends('layouts.app')
@push('styles')
@endpush
@section('content')
<h3>Penggunaan Kendaraan</h3>
<div class="mb-3">
    <div class="input-group rounded-0">
        <input type="date" id="start_date" name="start_date" value="{{date('Y-m-d')}}" class="form-control rounded-0" placeholder="Tanggal awal" required>
        <input type="date" id="end_date" name="end_date" value="{{date('Y-m-d')}}" class="form-control rounded-0" placeholder="Tanggal akhir" required>
        <button type="button" class="btn btn-primary btn-sm rounded-0" id="btn-filter">Filter</button>
    </div>
</div>
<canvas class="my-4 w-100" id="myChart" width="900" height="200"></canvas>
@endsection
@push('scripts')
<script>
    var myChart;
    $(function() {
        getChart();
    })

    $('#btn-filter').click(function() {
        getChart();
    })

    function getChart() {
        let startDate = $('#start_date').val();
        let endDate = $('#end_date').val();
        $.ajax({
            type: 'ajax',
            method: 'get',
            url: `{{route('dashboard.get-chart')}}`,
            data: {
                start_date: startDate,
                end_date: endDate,
            },
            dataType: 'json',
            beforeSend: () => {},
            success: (response) => {
                let data = response.data;
                let labels = [];
                let dataset = [];
                data.forEach((item, index) => {
                    labels.push(item.name);
                    dataset.push(item.jumlah);
                })
                let selector = document.getElementById("myChart");
                setChart(selector, labels, dataset);
            },
            error: (xhr) => {
                let response = xhr.responseJSON;
                alert(response.message);
            }
        });
    }

    function setChart(selector, labels, dataset) {
        if (window.myChart != null) {
            window.myChart.destroy();
        }
        myChart = new Chart(selector, {
            type: "line",
            data: {
                labels: labels,
                datasets: [{
                    data: dataset,
                    lineTension: 0,
                    backgroundColor: "transparent",
                    borderColor: "#007bff",
                    borderWidth: 4,
                    pointBackgroundColor: "#007bff",
                }, ],
                scaleBeginAtZero: false
            },
            options: {
                plugins: {
                    legend: {
                        display: false,
                    },
                    tooltip: {
                        boxPadding: 3,
                    },
                },
            },
        });
    }
</script>
@endpush